@extends('layouts.app')
@section('title', 'Recherche de voyages')

@section('styles')
<style>
.hero {
    background: linear-gradient(135deg, #0f0f0f 0%, #171610 100%);
    border-bottom: 1px solid #222;
    padding: 6rem 2.5rem 5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: 50%;
    transform: translateX(-50%);
    width: 700px;
    height: 700px;
    background: radial-gradient(circle, rgba(232,197,71,.06) 0%, transparent 70%);
}
.hero h1 {
    font-family: 'Syne', sans-serif;
    font-size: 3.2rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1rem;
    position: relative;
    letter-spacing: -0.02em;
}
.hero h1 em { font-style: normal; color: var(--accent); }
.hero p { color: var(--muted); font-size: 1.1rem; max-width: 540px; margin: 0 auto 2.5rem; position: relative; }

.search-container {
    background: var(--gray);
    border: 1px solid #2e2e2e;
    border-radius: 16px;
    padding: 1.5rem;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}
.search-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}
.search-form-grid label {
    text-align: left;
    margin-bottom: 0.35rem;
}

.operator-showcase {
    margin-top: 4rem;
}
.operator-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.operator-card {
    background: var(--gray);
    border: 1px solid #222;
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    transition: border-color 0.25s, transform 0.2s;
}
.operator-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}
.operator-logo-stub {
    width: 48px;
    height: 48px;
    background: rgba(232,197,71,0.08);
    border: 1px solid rgba(232,197,71,0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent);
}
</style>
@endsection

@section('content')
<div class="hero">
    <h1>Comparez & Réservez vos trajets<br>en <em>Afrique de l'Ouest</em></h1>
    <p>Trouvez les meilleures compagnies de bus, comparez les prix, choisissez vos sièges et payez en toute sécurité par Mobile Money.</p>
    
    <div class="search-container">
        <form method="GET" action="{{ route('trips.search') }}">
            <div class="search-form-grid">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Ville de départ</label>
                    <select name="depart" required>
                        <option value="">Sélectionner...</option>
                        @foreach($departs as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Ville de destination</label>
                    <select name="arrivee" required>
                        <option value="">Sélectionner...</option>
                        @foreach($arrivees as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Date du voyage</label>
                    <input type="date" name="date_voyage" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 45px; padding: 0 2rem;">
                    Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="operator-showcase">
        <div style="margin-bottom: 2rem; text-align: center;">
            <h2 class="page-title">Nos partenaires transport</h2>
            <p class="text-muted">Des compagnies certifiées pour voyager en toute sécurité.</p>
        </div>

        <div class="operator-grid">
            @foreach($operators as $op)
            <a href="{{ route('operator.profile', $op->id) }}" class="operator-card">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div class="operator-logo-stub">🚌</div>
                    <div>
                        <h3 style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; color: #fff;">{{ $op->nom_compagnie }}</h3>
                        <p style="font-size: 0.8rem; color: var(--muted);">Taux de commission : {{ number_format($op->commission_rate, 0) }}%</p>
                    </div>
                </div>
                <p class="text-muted" style="font-size: 0.85rem; line-height: 1.4; margin-bottom: 1rem; height: 3.6em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    {{ $op->description ?: 'Aucune description disponible.' }}
                </p>
                <div class="flex-between" style="border-top: 1px solid #222; padding-top: 0.75rem; font-size: 0.8rem;">
                    <span style="color: var(--accent); font-weight: 600;">⭐ {{ number_format($op->reviews->avg('note') ?: 5.0, 1) }} / 5</span>
                    <span class="text-muted">{{ $op->reviews_count }} avis</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
