<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use App\Models\Horaire;
use App\Models\Reservation;
use App\Models\Voyageur;
use App\Models\Billet;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'lignes'       => Ligne::count(),
            'horaires'     => Horaire::count(),
            'reservations' => Reservation::count(),
            'voyageurs'    => Voyageur::count(),
            'billets'      => Billet::count(),
            'revenus'      => Billet::sum('prix'),
        ];

        $dernieres_reservations = Reservation::with(['voyageur', 'horaire.ligne', 'billet'])
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $reservations_par_statut = Reservation::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        return view('admin.dashboard', compact('stats', 'dernieres_reservations', 'reservations_par_statut'));
    }

    // === LIGNES ===
    public function lignes()
    {
        $lignes = Ligne::withCount('horaires')->get();
        return view('admin.lignes', compact('lignes'));
    }

    public function storeLigne(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:100',
            'depart'   => 'required|string|max:100',
            'arrivee'  => 'required|string|max:100',
            'duree_min'=> 'required|integer|min:1',
        ]);
        Ligne::create($request->only('nom', 'depart', 'arrivee', 'duree_min'));
        return back()->with('success', 'Ligne créée avec succès.');
    }

    public function destroyLigne($id)
    {
        Ligne::findOrFail($id)->delete();
        return back()->with('success', 'Ligne supprimée.');
    }

    // === HORAIRES ===
    public function horaires()
    {
        $horaires = Horaire::with('ligne')->get();
        $lignes   = Ligne::all();
        return view('admin.horaires', compact('horaires', 'lignes'));
    }

    public function storeHoraire(Request $request)
    {
        $request->validate([
            'ligne_id'     => 'required|exists:Lignes,id',
            'heure_depart' => 'required',
            'heure_arrivee'=> 'required',
            'jours'        => 'required|string|max:100',
        ]);
        Horaire::create($request->only('ligne_id', 'heure_depart', 'heure_arrivee', 'jours'));
        return back()->with('success', 'Horaire ajouté avec succès.');
    }

    public function destroyHoraire($id)
    {
        Horaire::findOrFail($id)->delete();
        return back()->with('success', 'Horaire supprimé.');
    }

    // === RÉSERVATIONS ===
    public function reservations()
    {
        $reservations = Reservation::with(['voyageur', 'horaire.ligne', 'billet'])
            ->orderByDesc('id')->get();
        return view('admin.reservations', compact('reservations'));
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate(['statut' => 'required|in:En attente,Confirmée,Annulée']);
        Reservation::findOrFail($id)->update(['statut' => $request->statut]);
        return back()->with('success', 'Statut mis à jour.');
    }

    // === VOYAGEURS ===
    public function voyageurs()
    {
        $voyageurs = Voyageur::withCount('reservations')->get();
        return view('admin.voyageurs', compact('voyageurs'));
    }
}
