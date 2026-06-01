@extends('layouts.admin')
@section('title', 'Lignes')

@section('content')
<div class="page-header flex-between">
    <div>
        <h1 class="page-title">Lignes</h1>
        <p class="page-sub">{{ $lignes->count() }} ligne(s) enregistrée(s)</p>
    </div>
</div>

<!-- Formulaire ajout -->
<div class="card" style="margin-bottom:1.5rem">
    <p style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1rem">Ajouter une ligne</p>
    <form method="POST" action="{{ route('admin.lignes.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Nom de la ligne</label>
                <input type="text" name="nom" placeholder="Ex: Ligne 1" required>
            </div>
            <div class="form-group">
                <label>Ville de départ</label>
                <input type="text" name="depart" placeholder="Ex: Bamako" required>
            </div>
            <div class="form-group">
                <label>Ville d'arrivée</label>
                <input type="text" name="arrivee" placeholder="Ex: Ségou" required>
            </div>
            <div class="form-group">
                <label>Durée (minutes)</label>
                <input type="number" name="duree_min" min="1" placeholder="180" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter la ligne</button>
    </form>
</div>

<!-- Liste -->
<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Durée</th>
                <th>Horaires</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
            <tr>
                <td class="text-muted">{{ $ligne->id }}</td>
                <td><strong>{{ $ligne->nom }}</strong></td>
                <td>{{ $ligne->depart }}</td>
                <td>{{ $ligne->arrivee }}</td>
                <td>{{ $ligne->duree_min }} min</td>
                <td><span class="badge badge-warning">{{ $ligne->horaires_count }}</span></td>
                <td>
                    <form method="POST" action="{{ route('admin.lignes.destroy', $ligne->id) }}" onsubmit="return confirm('Supprimer cette ligne ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:2rem">Aucune ligne enregistrée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
