@extends('layouts.admin')
@section('title', 'Horaires')

@section('content')
<div class="page-header">
    <h1 class="page-title">Horaires</h1>
    <p class="page-sub">{{ $horaires->count() }} horaire(s) enregistré(s)</p>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <p style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1rem">Ajouter un horaire</p>
    <form method="POST" action="{{ route('admin.horaires.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Ligne</label>
                <select name="ligne_id" required>
                    <option value="">Sélectionner une ligne</option>
                    @foreach($lignes as $ligne)
                        <option value="{{ $ligne->id }}">{{ $ligne->nom }} ({{ $ligne->depart }} → {{ $ligne->arrivee }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Heure de départ</label>
                <input type="time" name="heure_depart" required>
            </div>
            <div class="form-group">
                <label>Heure d'arrivée</label>
                <input type="time" name="heure_arrivee" required>
            </div>
            <div class="form-group">
                <label>Jours</label>
                <input type="text" name="jours" placeholder="Ex: Lun-Ven, Tous les jours" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'horaire</button>
    </form>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ligne</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Jours</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($horaires as $h)
            <tr>
                <td class="text-muted">{{ $h->id }}</td>
                <td>
                    <strong>{{ $h->ligne->nom }}</strong>
                    <p style="font-size:.78rem;color:var(--muted)">{{ $h->ligne->depart }} → {{ $h->ligne->arrivee }}</p>
                </td>
                <td style="color:var(--accent);font-weight:600">{{ \Carbon\Carbon::parse($h->heure_depart)->format('H:i') }}</td>
                <td style="font-weight:600">{{ \Carbon\Carbon::parse($h->heure_arrivee)->format('H:i') }}</td>
                <td class="text-muted">{{ $h->jours }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.horaires.destroy', $h->id) }}" onsubmit="return confirm('Supprimer cet horaire ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:2rem">Aucun horaire enregistré.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
