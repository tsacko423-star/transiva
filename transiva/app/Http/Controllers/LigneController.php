<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use Illuminate\Http\Request;

class LigneController extends Controller
{
    public function index()
    {
        $lignes = Ligne::withCount('horaires')->get();
        return view('lignes.index', compact('lignes'));
    }

    public function show($id)
    {
        $ligne = Ligne::with('horaires')->findOrFail($id);
        return view('lignes.show', compact('ligne'));
    }
}
