<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Trip;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    // Notes de changement (Variables en Anglais) :
    // - operator : Profil transporteur de l'utilisateur connecté.
    // - vehicle : Flotte de bus/cars gérée.
    // - route : Lignes/trajets proposés par cet opérateur.
    // - trip : Départs planifiés avec prix dynamique et véhicule assigné.
    // - commission_rate : Pourcentage retenu par Transiva sur les paiements.

    private function getOperatorOrRedirect()
    {
        if (!auth()->check() || auth()->user()->role !== 'Operator') {
            abort(403, 'Accès réservé aux opérateurs de transport.');
        }

        $operator = auth()->user()->operator;
        
        if (!$operator) {
            // Création automatique d'un profil par défaut si inexistant pour faciliter le test
            $operator = Operator::create([
                'user_id' => auth()->id(),
                'nom_compagnie' => auth()->user()->name,
                'description' => 'Nouvel opérateur enregistré sur Transiva.',
                'statut' => 'Valide'
            ]);
        }

        return $operator;
    }

    public function dashboard()
    {
        $operator = $this->getOperatorOrRedirect();

        $vehiclesCount = $operator->vehicles()->count();
        $routesCount = $operator->routes()->count();

        // Récupérer les IDs de tous les trajets (Trips) de cet opérateur
        $tripIds = Trip::whereIn('route_id', $operator->routes()->pluck('id'))->pluck('id');

        // Réservations sur les trajets de l'opérateur
        $reservations = Reservation::whereIn('trip_id', $tripIds)->get();
        $reservationsCount = $reservations->count();

        // Chiffre d'affaires brut (somme des paiements réussis)
        $paymentTotal = Payment::whereIn('reservation_id', $reservations->pluck('id'))->sum('montant');
        
        // Calcul des revenus nets après déduction de la commission Transiva
        $commissionFactor = (100 - $operator->commission_rate) / 100;
        $revenuNet = $paymentTotal * $commissionFactor;

        // Dernières réservations passées
        $recentReservations = Reservation::with(['user', 'trip.route', 'trip.vehicle', 'ticket'])
            ->whereIn('trip_id', $tripIds)
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('operator.dashboard', compact(
            'operator', 
            'vehiclesCount', 
            'routesCount', 
            'reservationsCount', 
            'paymentTotal', 
            'revenuNet',
            'recentReservations'
        ));
    }

    // === GESTION VEHICULES ===
    public function vehicles()
    {
        $operator = $this->getOperatorOrRedirect();
        $vehicles = $operator->vehicles;
        return view('operator.vehicles', compact('operator', 'vehicles'));
    }

    public function storeVehicle(Request $request)
    {
        $operator = $this->getOperatorOrRedirect();
        
        $request->validate([
            'nom'             => 'required|string|max:100',
            'immatriculation' => 'required|string|max:50',
            'capacite'        => 'required|integer|min:1|max:100',
            'type'            => 'required|string|in:Bus,Minibus,Ferry,Train,Taxi',
        ]);

        Vehicle::create([
            'operator_id'     => $operator->id,
            'nom'             => $request->nom,
            'immatriculation' => $request->immatriculation,
            'capacite'        => $request->capacite,
            'type'            => $request->type,
        ]);

        return back()->with('success', 'Véhicule ajouté avec succès à votre flotte.');
    }

    public function destroyVehicle($id)
    {
        $operator = $this->getOperatorOrRedirect();
        $vehicle = Vehicle::where('operator_id', $operator->id)->findOrFail($id);
        $vehicle->delete();
        return back()->with('success', 'Véhicule retiré de la flotte.');
    }

    // === GESTION ROUTES ===
    public function routes()
    {
        $operator = $this->getOperatorOrRedirect();
        $routes = $operator->routes;
        return view('operator.routes', compact('operator', 'routes'));
    }

    public function storeRoute(Request $request)
    {
        $operator = $this->getOperatorOrRedirect();

        $request->validate([
            'nom'       => 'required|string|max:100',
            'depart'    => 'required|string|max:100',
            'arrivee'   => 'required|string|max:100',
            'duree_min' => 'required|integer|min:1',
        ]);

        Route::create([
            'operator_id' => $operator->id,
            'nom'         => $request->nom,
            'depart'      => $request->depart,
            'arrivee'     => $request->arrivee,
            'duree_min'   => $request->duree_min,
        ]);

        return back()->with('success', 'Ligne de transport créée.');
    }

    public function destroyRoute($id)
    {
        $operator = $this->getOperatorOrRedirect();
        $route = Route::where('operator_id', $operator->id)->findOrFail($id);
        $route->delete();
        return back()->with('success', 'Ligne supprimée.');
    }

    // === GESTION TRIPS (VOYAGES ET TARIFS) ===
    public function trips()
    {
        $operator = $this->getOperatorOrRedirect();
        
        $routes = $operator->routes;
        $vehicles = $operator->vehicles;
        
        $trips = Trip::with(['route', 'vehicle'])
            ->whereIn('route_id', $routes->pluck('id'))
            ->get();

        return view('operator.trips', compact('operator', 'routes', 'vehicles', 'trips'));
    }

    public function storeTrip(Request $request)
    {
        $operator = $this->getOperatorOrRedirect();

        $request->validate([
            'route_id'      => 'required|exists:Routes,id',
            'vehicle_id'    => 'required|exists:Vehicles,id',
            'heure_depart'  => 'required',
            'heure_arrivee' => 'required',
            'jours'         => 'required|string|max:100',
            'prix'          => 'required|numeric|min:0',
        ]);

        // S'assurer que la route appartient bien à l'opérateur
        Route::where('operator_id', $operator->id)->findOrFail($request->route_id);
        // S'assurer que le véhicule appartient bien à l'opérateur
        Vehicle::where('operator_id', $operator->id)->findOrFail($request->vehicle_id);

        Trip::create($request->only('route_id', 'vehicle_id', 'heure_depart', 'heure_arrivee', 'jours', 'prix'));

        return back()->with('success', 'Nouveau voyage planifié avec succès.');
    }

    public function destroyTrip($id)
    {
        $operator = $this->getOperatorOrRedirect();
        $trip = Trip::whereHas('route', function($q) use ($operator) {
            $q->where('operator_id', $operator->id);
        })->findOrFail($id);
        
        $trip->delete();
        return back()->with('success', 'Voyage planifié annulé.');
    }

    // === GESTION RESERVATIONS ===
    public function reservations()
    {
        $operator = $this->getOperatorOrRedirect();
        $tripIds = Trip::whereIn('route_id', $operator->routes()->pluck('id'))->pluck('id');
        
        $reservations = Reservation::with(['user', 'trip.route', 'trip.vehicle', 'ticket', 'payment'])
            ->whereIn('trip_id', $tripIds)
            ->orderByDesc('id')
            ->get();

        return view('operator.reservations', compact('operator', 'reservations'));
    }
}
