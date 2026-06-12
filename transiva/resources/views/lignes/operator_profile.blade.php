@extends('layouts.app')
@section('title', $operator->nom_compagnie)

@section('content')
<div class="container">
    <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="margin-bottom: 1.5rem;">← Retour à la recherche</a>

    <div class="card" style="margin-bottom: 2rem; padding: 2rem;">
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="width: 80px; height: 80px; background: rgba(232,197,71,0.08); border: 1px solid rgba(232,197,71,0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--accent);">
                🚌
            </div>
            <div style="flex: 1;">
                <h1 class="page-title" style="margin-bottom: 0.25rem;">{{ $operator->nom_compagnie }}</h1>
                <p class="text-muted" style="font-size: 1rem;">Transporteur Partenaire Certifié Transiva</p>
            </div>
            <div style="text-align: right; background: rgba(255,255,255,0.02); border: 1px solid #222; padding: 1rem 1.5rem; border-radius: 10px;">
                <span class="text-muted" style="font-size: 0.75rem; text-transform: uppercase;">Note Moyenne</span>
                <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent); margin-top: 0.2rem;">
                    ⭐ {{ number_format($ratingAverage, 1) }} <span style="font-size: 1rem; color: var(--muted); font-weight: 400;">/ 5</span>
                </div>
            </div>
        </div>
        <div style="margin-top: 1.5rem; border-top: 1px solid #222; padding-top: 1.5rem;">
            <h3 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 0.5rem; font-size: 1.1rem; color: #fff;">À propos</h3>
            <p class="text-muted" style="line-height: 1.6;">{{ $operator->description ?: 'Aucune description disponible pour ce transporteur.' }}</p>
        </div>
    </div>

    <div class="grid-2">
        <!-- Lignes et Départs -->
        <div>
            <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Lignes exploitées</h2>
            @if($operator->routes->isEmpty())
                <div class="card text-muted" style="text-align: center; padding: 2rem;">
                    Aucune ligne active répertoriée pour le moment.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($operator->routes as $route)
                        <div class="card" style="padding: 1rem 1.25rem;">
                            <div class="flex-between">
                                <div>
                                    <strong style="display: block; font-size: 1rem; color: #fff;">{{ $route->depart }} → {{ $route->arrivee }}</strong>
                                    <span class="text-muted" style="font-size: 0.8rem;">Nom : {{ $route->nom }} | Durée : {{ $route->duree_min }} min</span>
                                </div>
                                <span style="background: rgba(232,197,71,0.06); color: var(--accent); border: 1px solid rgba(232,197,71,0.2); border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600;">
                                    {{ $route->trips->count() }} départ(s)
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Avis des Voyageurs -->
        <div>
            <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Avis des voyageurs ({{ $operator->reviews->count() }})</h2>
            @if($operator->reviews->isEmpty())
                <div class="card text-muted" style="text-align: center; padding: 3rem;">
                    💭 Aucun avis pour le moment. Soyez le premier à réserver et à donner votre avis !
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 500px; overflow-y: auto; padding-right: 0.5rem;">
                    @foreach($operator->reviews as $review)
                        <div class="card" style="padding: 1rem 1.25rem;">
                            <div class="flex-between" style="margin-bottom: 0.5rem;">
                                <strong>{{ $review->user->name }}</strong>
                                <span style="color: var(--accent); font-weight: 600; font-size: 0.85rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->note ? '★' : '☆' }}
                                    @endfor
                                </span>
                            </div>
                            <p class="text-muted" style="font-size: 0.88rem; line-height: 1.4; margin-bottom: 0.5rem;">
                                "{{ $review->commentaire }}"
                            </p>
                            <span style="font-size: 0.75rem; color: #555;">Publié le {{ \Carbon\Carbon::parse($review->date_avis)->format('d/m/Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
