<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Trip;
use App\Models\Operator;
use Illuminate\Http\Request;

class LigneController extends Controller
{
    // Notes de changement (Variables en Anglais) :
    // - Route (remplace Ligne) : Le modèle représentant les lignes.
    // - Trip (remplace Horaire) : Représente le voyage plannifié d'un opérateur.
    // - Operator : Représente la compagnie de transport.

    public function index(Request $request)
    {
        // Villes de départ et d'arrivée uniques pour alimenter les formulaires de recherche
        $departs = Route::distinct()->pluck('depart');
        $arrivees = Route::distinct()->pluck('arrivee');
        
        $operators = Operator::where('statut', 'Valide')->withCount('reviews')->get();

        return view('lignes.index', compact('departs', 'arrivees', 'operators'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'depart' => 'required|string',
            'arrivee' => 'required|string',
            'date_voyage' => 'required|date|after_or_equal:today',
        ]);

        $depart = $request->depart;
        $arrivee = $request->arrivee;
        $date_voyage = $request->date_voyage;

        // Trouver toutes les routes correspondant au départ et à l'arrivée
        $routes = Route::where('depart', $depart)
            ->where('arrivee', $arrivee)
            ->pluck('id');

        // Charger les trajets (Trips) correspondants avec les véhicules et les opérateurs valides
        $trips = Trip::with(['route', 'vehicle.operator'])
            ->whereIn('route_id', $routes)
            ->whereHas('vehicle.operator', function ($q) {
                $q->where('statut', 'Valide');
            })
            ->get();

        // Filtrer les jours de circulation (jours de la semaine)
        // ex : 'Mon - Fri' ou 'Tous les jours' ou 'Sam - Dim'
        $jourSemaineNum = date('N', strtotime($date_voyage)); // 1 (Lundi) à 7 (Dimanche)
        
        $trips = $trips->filter(function ($trip) use ($jourSemaineNum) {
            $jours = strtolower($trip->jours);
            if ($jours === 'tous les jours') {
                return true;
            }
            if (str_contains($jours, 'lun - ven') && $jourSemaineNum >= 1 && $jourSemaineNum <= 5) {
                return true;
            }
            if (str_contains($jours, 'lun - sam') && $jourSemaineNum >= 1 && $jourSemaineNum <= 6) {
                return true;
            }
            if (str_contains($jours, 'sam - dim') && ($jourSemaineNum == 6 || $jourSemaineNum == 7)) {
                return true;
            }
            return true; // Fallback par défaut si format personnalisé non reconnu
        });

        return view('lignes.search_results', compact('trips', 'depart', 'arrivee', 'date_voyage'));
    }

    public function operatorProfile($id)
    {
        $operator = Operator::with(['reviews.user', 'routes.trips'])->findOrFail($id);
        $ratingAverage = $operator->reviews->avg('note') ?: 0;
        
        return view('lignes.operator_profile', compact('operator', 'ratingAverage'));
    }
}
