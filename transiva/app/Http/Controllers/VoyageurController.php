<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Review;
use App\Models\Operator;
use Illuminate\Http\Request;

class VoyageurController extends Controller
{
    // Notes de changement (Variables en Anglais) :
    // - User : Remplace l'ancien modèle Voyageur.
    // - Review : Modèle de notation/avis.
    // - Reservation : Modèle de réservation associé à Trip.

    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'Traveler') {
            return redirect()->route('voyageur.reservations');
        }
        return view('voyageur.search');
    }

    public function reservations(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            $request->validate(['email' => 'required|email']);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->with('error', 'Aucun compte voyageur trouvé avec cet email.');
            }
            session(['simulated_user_id' => $user->id]);
        }

        // Récupérer les réservations avec les trajets, lignes, opérateurs et billets
        $reservations = Reservation::with([
            'trip.route.operator',
            'trip.vehicle',
            'ticket',
            'payment'
        ])
        ->where('user_id', $user->id)
        ->orderByDesc('id')
        ->get();

        // Récupérer la liste des opérateurs pour permettre au client de laisser un avis
        $operators = Operator::where('statut', 'Valide')->get();

        return view('voyageur.reservations', compact('user', 'reservations', 'operators'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:Operators,id',
            'note'        => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        if (!auth()->check()) {
            return back()->with('error', 'Vous devez être connecté pour laisser un avis.');
        }

        Review::create([
            'user_id'     => auth()->id(),
            'operator_id' => $request->operator_id,
            'note'        => $request->note,
            'commentaire' => $request->commentaire,
            'date_avis'   => now(),
        ]);

        return back()->with('success', 'Merci ! Votre avis a été publié avec succès.');
    }
}
