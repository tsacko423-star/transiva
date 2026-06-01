@extends('layouts.app')
@section('title', $ligne->nom)

@section('content')
<div class="container">
    <a href="{{ route('lignes.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem">← Retour aux lignes</a>

    <div class="card" style="margin-bottom:2rem">
        <div style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap">
            <div>
                <p class="text-muted" style="margin-bottom:.25rem">Ligne</p>
                <h1 class="page-title" style="margin-bottom:0">{{ $ligne->nom }}</h1>
            </div>
            <div style="flex:1;display:flex;align-items:center;gap:1rem;justify-content:center">
                <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.3rem">{{ $ligne->depart }}</span>
                <span style="color:var(--accent);font-size:1.5rem">→</span>
                <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.3rem">{{ $ligne->arrivee }}</span>
            </div>
            <div style="text-align:right">
                <p class="text-muted" style="margin-bottom:.25rem">Durée estimée</p>
                <p style="font-size:1.4rem;font-weight:700;color:var(--accent)">{{ $ligne->duree_min }} min</p>
            </div>
        </div>
    </div>

    <h2 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1rem">Horaires disponibles</h2>

    @if($ligne->horaires->isEmpty())
        <div class="card" style="text-align:center;padding:3rem;color:var(--muted)">
            Aucun horaire disponible pour cette ligne.
        </div>
    @else
        <div class="card" style="padding:0;overflow:hidden">
            <table>
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Jours</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ligne->horaires as $horaire)
                    <tr>
                        <td>
                            <span style="font-size:1.1rem;font-weight:600;color:var(--accent)">
                                {{ \Carbon\Carbon::parse($horaire->heure_depart)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size:1.1rem;font-weight:600">
                                {{ \Carbon\Carbon::parse($horaire->heure_arrivee)->format('H:i') }}
                            </span>
                        </td>
                        <td><span class="text-muted">{{ $horaire->jours }}</span></td>
                        <td style="text-align:right">
                            <a href="{{ route('reservations.create', $horaire->id) }}" class="btn btn-primary btn-sm">
                                Réserver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
