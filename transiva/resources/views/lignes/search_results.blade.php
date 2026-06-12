@extends('layouts.app')
@section('title', 'Résultats de recherche')

@section('styles')
<style>
.search-header {
    background: var(--gray);
    border-bottom: 1px solid #222;
    padding: 2rem 0;
}
.trip-card {
    background: var(--gray);
    border: 1px solid #222;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    transition: border-color 0.2s;
    display: grid;
    grid-template-columns: 1.5fr 2fr 1fr 1.2fr;
    align-items: center;
    gap: 1.5rem;
}
.trip-card:hover {
    border-color: #333;
}
.time-block {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.time-val {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--white);
}
.duration-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 0.75rem;
    color: var(--muted);
    position: relative;
    width: 60px;
}
.duration-line {
    width: 100%;
    height: 2px;
    background: #333;
    position: relative;
    margin: 0.25rem 0;
}
.duration-line::after {
    content: '';
    position: absolute;
    right: 0;
    top: -3px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent);
}
.price-block {
    text-align: right;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--accent);
}
.price-block span {
    font-size: 0.8rem;
    color: var(--muted);
    font-weight: 400;
}
@media (max-width: 768px) {
    .trip-card {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    .time-block {
        justify-content: center;
    }
    .price-block {
        text-align: center;
    }
}
</style>
@endsection

@section('content')
<div class="search-header">
    <div class="container" style="padding-top:0; padding-bottom:0;">
        <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="margin-bottom: 1rem;">← Modifier la recherche</a>
        <div class="flex-between">
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem;">
                    {{ $depart }} <span style="color: var(--accent);">→</span> {{ $arrivee }}
                </h1>
                <p class="text-muted" style="margin-top: 0.25rem;">
                    Départs le <strong>{{ \Carbon\Carbon::parse($date_voyage)->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid #222; padding: 0.5rem 1rem; border-radius: 8px; text-align: right;">
                <span class="text-muted" style="font-size: 0.8rem;">Trajets trouvés</span>
                <strong style="display: block; font-size: 1.2rem; color: var(--accent);">{{ $trips->count() }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="container">
    @if($trips->isEmpty())
        <div class="card" style="text-align: center; padding: 5rem 2rem;">
            <p style="font-size: 3rem; margin-bottom: 1rem;">🚌</p>
            <h3 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Aucun voyage disponible</h3>
            <p class="text-muted" style="max-width: 420px; margin: 0 auto;">Il n'y a pas de trajets prévus pour cette date entre {{ $depart }} et {{ $arrivee }}. Essayez une autre date ou d'autres villes.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Retour à l'accueil</a>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach($trips as $trip)
                <div class="trip-card">
                    <!-- Bloc Opérateur -->
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 42px; height: 42px; background: rgba(232,197,71,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--accent);">🚌</div>
                        <div>
                            <strong style="display: block; color: #fff; font-size: 1rem;">{{ $trip->vehicle->operator->nom_compagnie }}</strong>
                            <span class="text-muted" style="font-size: 0.8rem;">Véhicule : {{ $trip->vehicle->nom }}</span>
                        </div>
                    </div>

                    <!-- Bloc Horaires -->
                    <div class="time-block">
                        <div style="text-align: right;">
                            <span class="time-val">{{ \Carbon\Carbon::parse($trip->heure_depart)->format('H:i') }}</span>
                            <span style="display: block; font-size: 0.72rem; color: var(--muted);">{{ $depart }}</span>
                        </div>
                        <div class="duration-indicator">
                            <span style="font-size: 0.7rem;">{{ $trip->route->duree_min }} min</span>
                            <div class="duration-line"></div>
                            <span style="font-size: 0.7rem; color: var(--accent);">Direct</span>
                        </div>
                        <div>
                            <span class="time-val">{{ \Carbon\Carbon::parse($trip->heure_arrivee)->format('H:i') }}</span>
                            <span style="display: block; font-size: 0.72rem; color: var(--muted);">{{ $arrivee }}</span>
                        </div>
                    </div>

                    <!-- Bloc Tarifs -->
                    <div class="price-block">
                        {{ number_format($trip->prix, 0, ',', ' ') }} <span style="font-size: 0.9rem; font-weight: 600;">FCFA</span>
                        <span style="display: block;">par passager</span>
                    </div>

                    <!-- Action -->
                    <div style="text-align: right;">
                        <a href="{{ route('reservations.create', ['trip_id' => $trip->id, 'date_voyage' => $date_voyage]) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            Sélectionner
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
