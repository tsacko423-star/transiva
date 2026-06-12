<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Trip;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Notes de changement (Variables en Anglais) :
    // - User : Gère tous les comptes d'utilisateurs.
    // - Operator : Modèle des transporteurs.
    // - Payment : Transactions financières.
    // - totalCommissions : Revenu de commission calculé de manière dynamique.

    private function checkAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Accès réservé aux administrateurs de la plateforme.');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        // Statistiques globales de la plateforme
        $operatorsCount = Operator::count();
        $routesCount = Route::count();
        $reservationsCount = Reservation::count();
        
        // Calculer le volume total des ventes et les commissions cumulées
        $payments = Payment::with('reservation.trip.route.operator')
            ->where('statut', 'Reussi')
            ->get();
            
        $volumeTransactions = $payments->sum('montant');
        $totalCommissions = 0;

        foreach ($payments as $payment) {
            $operator = $payment->reservation->trip->route->operator ?? null;
            $rate = $operator ? $operator->commission_rate : 10.00;
            $totalCommissions += $payment->montant * ($rate / 100);
        }

        // Récupérer les opérateurs en attente de validation
        $pendingOperators = Operator::with('user')
            ->where('statut', 'En attente')
            ->get();

        // Récupérer les 10 dernières réservations sur toute la plateforme
        $recentReservations = Reservation::with(['user', 'trip.route.operator', 'ticket', 'payment'])
            ->orderByDesc('id')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'operatorsCount', 
            'routesCount', 
            'reservationsCount', 
            'volumeTransactions', 
            'totalCommissions', 
            'pendingOperators', 
            'recentReservations'
        ));
    }

    // === GESTION DES OPÉRATEURS ===
    public function operators()
    {
        $this->checkAdmin();
        $operators = Operator::with('user')->withCount(['vehicles', 'routes'])->get();
        return view('admin.operators', compact('operators'));
    }

    public function updateOperatorStatus(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'statut' => 'required|in:En attente,Valide,Suspendu',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $operator = Operator::findOrFail($id);
        
        $updateData = ['statut' => $request->statut];
        if ($request->has('commission_rate')) {
            $updateData['commission_rate'] = $request->commission_rate;
        }

        $operator->update($updateData);

        return back()->with('success', "Le statut de l'opérateur {$operator->nom_compagnie} a été mis à jour.");
    }

    // === TRANSACTIONS (PAIEMENTS) ===
    public function transactions()
    {
        $this->checkAdmin();
        
        $payments = Payment::with(['reservation.user', 'reservation.trip.route.operator', 'reservation.ticket'])
            ->orderByDesc('id')
            ->get();

        return view('admin.transactions', compact('payments'));
    }

    // === UTILISATEURS ===
    public function users()
    {
        $this->checkAdmin();
        $users = User::withCount('reservations')->orderBy('id', 'desc')->get();
        return view('admin.users', compact('users'));
    }
}
