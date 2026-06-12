<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Trip;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    // Notes de changement (Variables en Anglais) :
    // - Trip (remplace Horaire) : Voyage sélectionné.
    // - Ticket (remplace Billet) : Billet numérique.
    // - Payment : Enregistrement de la transaction financière.
    // - User : Le client effectuant la réservation.
    // - sieges (champ table) : Choix des numéros de sièges.

    public function create(Request $request, $trip_id)
    {
        $request->validate([
            'date_voyage' => 'required|date|after_or_equal:today',
        ]);

        $date_voyage = $request->date_voyage;
        $trip = Trip::with(['route', 'vehicle.operator'])->findOrFail($trip_id);

        // Récupérer tous les sièges déjà réservés pour ce trajet à cette date spécifique
        $reservationsExistantes = Reservation::where('trip_id', $trip_id)
            ->where('date_voyage', $date_voyage)
            ->whereIn('statut', ['Payee', 'En attente'])
            ->pluck('sieges')
            ->toArray();

        $siegesOccupes = [];
        foreach ($reservationsExistantes as $siegesStr) {
            if ($siegesStr) {
                $siegesOccupes = array_merge($siegesOccupes, explode(',', $siegesStr));
            }
        }
        $siegesOccupes = array_map('trim', $siegesOccupes);

        return view('reservations.create', compact('trip', 'date_voyage', 'siegesOccupes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'             => 'required|string|max:100',
            'email'           => 'required|email|max:150',
            'telephone'       => 'required|string|max:20',
            'trip_id'         => 'required|exists:Trips,id',
            'date_voyage'     => 'required|date|after_or_equal:today',
            'nb_places'       => 'required|integer|min:1|max:10',
            'sieges'          => 'required|string', // ex: "5,6"
            'mode_paiement'   => 'required|string|in:Mobile Money,Carte Bancaire',
            'numero_paiement' => 'required|string', // Numéro Mobile money ou carte
        ]);

        $trip = Trip::with('vehicle.operator')->findOrFail($request->trip_id);
        $prixTotal = $trip->prix * $request->nb_places;

        // 1. Récupérer ou Créer l'utilisateur (rôle Traveler)
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->nom, 
                'telephone' => $request->telephone, 
                'password' => bcrypt('password'), // Mot de passe par défaut
                'role' => 'Traveler'
            ]
        );

        // 2. Vérifier à nouveau la disponibilité des sièges sélectionnés
        $siegesChoisis = array_map('trim', explode(',', $request->sieges));
        
        $reservationsExistantes = Reservation::where('trip_id', $request->trip_id)
            ->where('date_voyage', $request->date_voyage)
            ->whereIn('statut', ['Payee', 'En attente'])
            ->pluck('sieges')
            ->toArray();

        $siegesOccupes = [];
        foreach ($reservationsExistantes as $siegesStr) {
            if ($siegesStr) {
                $siegesOccupes = array_merge($siegesOccupes, explode(',', $siegesStr));
            }
        }
        $siegesOccupes = array_map('trim', $siegesOccupes);

        // S'assurer qu'aucun siège sélectionné n'est déjà occupé
        foreach ($siegesChoisis as $s) {
            if (in_array($s, $siegesOccupes)) {
                return back()->withErrors("Le siège {$s} a déjà été réservé par un autre voyageur. Veuillez choisir un autre siège.")->withInput();
            }
        }

        // 3. Créer la réservation
        $reservation = Reservation::create([
            'user_id'     => $user->id,
            'trip_id'     => $request->trip_id,
            'date_voyage' => $request->date_voyage,
            'nb_places'   => $request->nb_places,
            'sieges'      => $request->sieges,
            'statut'      => 'Payee', // Simulation paiement immédiat réussi
        ]);

        // 4. Générer la transaction de paiement (simulée)
        $reference = strtoupper('TXN-' . Str::random(4) . '-' . time());
        Payment::create([
            'reservation_id' => $reservation->id,
            'mode_paiement'  => $request->mode_paiement,
            'montant'        => $prixTotal,
            'statut'         => 'Reussi',
            'reference_transaction' => $reference,
        ]);

        // 5. Générer le Ticket avec son code de réservation unique et QR
        $codeReservation = strtoupper('TRV-' . Str::random(4) . '-' . $reservation->id);
        $codeQR = 'QR-' . $codeReservation . '-' . $reservation->date_voyage;

        Ticket::create([
            'reservation_id'   => $reservation->id,
            'code_qr'           => $codeQR,
            'code_reservation'  => $codeReservation,
            'prix_total'        => $prixTotal,
            'date_emission'     => now(),
        ]);

        // Connecter l'utilisateur dans la session simulée
        session(['simulated_user_id' => $user->id]);

        return redirect()->route('voyageur.reservations')
            ->with('success', 'Réservation et paiement confirmés avec succès ! Votre billet a été généré.');
    }
}
