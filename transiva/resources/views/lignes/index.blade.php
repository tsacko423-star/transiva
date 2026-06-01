@extends('layouts.app')
@section('title', 'Lignes disponibles')

@section('styles')
<style>
.hero {
    background: linear-gradient(135deg, #111 0%, #1a1a0a 100%);
    border-bottom: 1px solid #222;
    padding: 5rem 2.5rem 4rem;
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
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(232,197,71,.08) 0%, transparent 70%);
}
.hero h1 {
    font-family: 'Syne', sans-serif;
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1rem;
    position: relative;
}
.hero h1 em { font-style: normal; color: var(--accent); }
.hero p { color: var(--muted); font-size: 1.05rem; max-width: 480px; margin: 0 auto 2rem; position: relative; }

.search-bar {
    display: flex;
    gap: .75rem;
    max-width: 520px;
    margin: 0 auto;
    position: relative;
}
.search-bar input {
    flex: 1;
    padding: .85rem 1.25rem;
    border-radius: 50px;
    font-size: .95rem;
}
.search-bar button {
    padding: .85rem 1.75rem;
    border-radius: 50px;
    white-space: nowrap;
}

.ligne-card {
    background: var(--gray);
    border: 1px solid #252525;
    border-radius: 14px;
    padding: 1.5rem;
    transition: border-color .2s, transform .2s;
    text-decoration: none;
    color: inherit;
    display: block;
}
.ligne-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}
.ligne-route {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1rem;
}
.ville {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
}
.route-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--accent), #333);
    position: relative;
}
.route-line::after {
    content: '→';
    position: absolute;
    right: -6px;
    top: -10px;
    color: var(--accent);
    font-size: .85rem;
}
.ligne-meta {
    display: flex;
    gap: 1.5rem;
}
.meta-item { font-size: .82rem; color: var(--muted); }
.meta-item strong { color: var(--white); display: block; font-size: .95rem; }
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    color: var(--muted);
}
.empty-state svg { margin-bottom: 1rem; opacity: .3; }
</style>
@endsection

@section('content')
<div class="hero">
    <h1>Voyagez partout<br>avec <em>Transiva</em></h1>
    <p>Consultez les lignes disponibles et réservez votre place en quelques clics.</p>
    <form method="GET" action="{{ route('lignes.index') }}" class="search-bar">
        <input type="text" name="q" placeholder="Rechercher une ville ou ligne..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary">Chercher</button>
    </form>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex-between" style="margin-bottom:1.5rem">
        <div>
            <h2 class="page-title">Lignes disponibles</h2>
            <p class="page-sub">{{ $lignes->count() }} ligne(s) au total</p>
        </div>
    </div>

    @if($lignes->isEmpty())
        <div class="empty-state">
            <p>Aucune ligne disponible pour le moment.</p>
        </div>
    @else
        <div class="grid-3">
            @foreach($lignes as $ligne)
            <a href="{{ route('lignes.show', $ligne->id) }}" class="ligne-card">
                <div class="ligne-route">
                    <span class="ville">{{ $ligne->depart }}</span>
                    <div class="route-line"></div>
                    <span class="ville">{{ $ligne->arrivee }}</span>
                </div>
                <div class="ligne-meta">
                    <div class="meta-item">
                        <strong>{{ $ligne->nom }}</strong>
                        Ligne
                    </div>
                    <div class="meta-item">
                        <strong>{{ $ligne->duree_min }} min</strong>
                        Durée
                    </div>
                    <div class="meta-item">
                        <strong>{{ $ligne->horaires_count }}</strong>
                        Horaire(s)
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
