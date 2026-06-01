<?php

namespace App\Http\Controllers;

use App\Models\Voyageur;
use Illuminate\Http\Request;

class VoyageurController extends Controller
{
    public function index()
    {
        return view('voyageur.search');
    }

    public function reservations(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $voyageur = Voyageur::with([
            'reservations.horaire.ligne',
            'reservations.billet',
        ])->where('email', $request->email)->first();

        if (!$voyageur) {
            return back()->with('error', 'Aucun voyageur trouvé avec cet email.');
        }

        return view('voyageur.reservations', compact('voyageur'));
    }
}
