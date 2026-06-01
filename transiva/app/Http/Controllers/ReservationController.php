<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Voyageur;
use App\Models\Horaire;
use App\Models\Billet;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create($horaire_id)
    {
        $horaire = Horaire::with('ligne')->findOrFail($horaire_id);
        return view('reservations.create', compact('horaire'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string|max:100',
            'email'      => 'required|email|max:150',
            'telephone'  => 'required|string|max:20',
            'horaire_id' => 'required|exists:Horaires,id',
            'date_voyage'=> 'required|date|after_or_equal:today',
            'nb_places'  => 'required|integer|min:1|max:10',
        ]);

        // Créer ou récupérer le voyageur
        $voyageur = Voyageur::firstOrCreate(
            ['email' => $request->email],
            ['nom' => $request->nom, 'telephone' => $request->telephone]
        );

        // Créer la réservation
        $reservation = Reservation::create([
            'voyageur_id' => $voyageur->id,
            'horaire_id'  => $request->horaire_id,
            'date_voyage' => $request->date_voyage,
            'nb_places'   => $request->nb_places,
            'statut'      => 'En attente',
        ]);

        // Générer un billet automatiquement
        Billet::create([
            'reservation_id' => $reservation->id,
            'code_qr'        => strtoupper('TRV-' . $voyageur->id . '-' . $reservation->id . '-' . now()->format('YmdHis')),
            'prix'           => $request->nb_places * 500, // 500 FCFA par place (à adapter)
            'date_emission'  => now(),
        ]);

        return redirect()->route('voyageur.reservations', ['email' => $voyageur->email])
            ->with('success', 'Réservation confirmée ! Votre billet a été généré.');
    }
}
