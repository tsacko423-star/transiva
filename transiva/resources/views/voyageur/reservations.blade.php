@extends('layouts.app')
@section('title', 'Mes Billets & Réservations')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="flex-between" style="margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span class="text-muted" style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Tableau de bord</span>
            <h1 class="page-title" style="margin-top: 0.2rem;">Mes Réservations</h1>
            <p class="text-muted">Bonjour <strong>{{ $user->name }}</strong> — Retrouvez vos billets numériques et gérez vos avis.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">🔍 Rechercher un voyage</a>
    </div>

    <div class="grid-2" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Liste des Réservations -->
        <div>
            <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1.25rem;">Billets Actifs</h2>

            @if($reservations->isEmpty())
                <div class="card" style="text-align: center; padding: 4rem 2rem;">
                    <p style="font-size: 3rem; margin-bottom: 1rem;">🎫</p>
                    <h3 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Aucun billet trouvé</h3>
                    <p class="text-muted" style="max-width: 380px; margin: 0 auto;">Vous n'avez pas encore effectué de réservation sur Transiva. Lancez une recherche pour réserver votre premier trajet !</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Rechercher un trajet</a>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach($reservations as $resa)
                        <div class="card" style="padding: 1.5rem;">
                            <!-- En-tête de la réservation -->
                            <div class="flex-between" style="border-bottom: 1px solid #222; padding-bottom: 1rem; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <span style="font-size: 0.75rem; color: var(--muted); text-transform: uppercase; display: block;">Transporteur :</span>
                                    <strong style="font-family: 'Syne', sans-serif; font-size: 1.1rem; color: #fff;">{{ $resa->trip->route->operator->nom_compagnie }}</strong>
                                </div>
                                <div style="text-align: right;">
                                    <span class="badge badge-success">{{ $resa->statut }}</span>
                                </div>
                            </div>

                            <!-- Détails du trajet -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Trajet :</span>
                                    <strong style="display: block; color: var(--white); font-size: 0.95rem; margin-top: 0.2rem;">
                                        {{ $resa->trip->route->depart }} → {{ $resa->trip->route->arrivee }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Date du voyage :</span>
                                    <strong style="display: block; color: var(--white); font-size: 0.95rem; margin-top: 0.2rem;">
                                        {{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Horaire :</span>
                                    <strong style="display: block; color: var(--accent); font-size: 0.95rem; margin-top: 0.2rem;">
                                        {{ \Carbon\Carbon::parse($resa->trip->heure_depart)->format('H:i') }} 
                                        → {{ \Carbon\Carbon::parse($resa->trip->heure_arrivee)->format('H:i') }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Siège(s) :</span>
                                    <strong style="display: block; color: var(--white); font-size: 0.95rem; margin-top: 0.2rem;">
                                        {{ $resa->sieges ?: 'N/A' }} ({{ $resa->nb_places }} place{{ $resa->nb_places > 1 ? 's' : '' }})
                                    </strong>
                                </div>
                            </div>

                            <!-- Ticket & QR Code -->
                            @if($resa->ticket)
                                <div style="background: #0d0d0d; border: 1px solid #222; border-radius: 8px; padding: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                                    <div style="flex: 1;">
                                        <span class="text-muted" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; display: block;">Référence Billet :</span>
                                        <strong style="font-family: monospace; color: var(--accent); font-size: 0.95rem;">{{ $resa->ticket->code_reservation }}</strong>
                                        
                                        @if($resa->payment)
                                            <span style="display: block; font-size: 0.75rem; color: #555; margin-top: 0.3rem;">
                                                Payé par {{ $resa->payment->mode_paiement }} | Réf: {{ $resa->payment->reference_transaction }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="text-align: right; display: flex; align-items: center; gap: 1rem;">
                                        <div>
                                            <span class="text-muted" style="font-size: 0.7rem; display: block;">Total Payé :</span>
                                            <strong style="color: #fff; font-size: 1.1rem;">{{ number_format($resa->ticket->prix_total, 0, ',', ' ') }} FCFA</strong>
                                        </div>
                                        <div style="background: #fff; padding: 0.4rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px;">
                                            <!-- Simulation QR code visuel -->
                                            <div style="background: #000; width: 100%; height: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; padding: 2px;">
                                                <div style="background:#fff;"></div><div style="background:#000;"></div><div style="background:#fff;"></div><div style="background:#fff;"></div>
                                                <div style="background:#000;"></div><div style="background:#fff;"></div><div style="background:#000;"></div><div style="background:#fff;"></div>
                                                <div style="background:#fff;"></div><div style="background:#000;"></div><div style="background:#fff;"></div><div style="background:#000;"></div>
                                                <div style="background:#fff;"></div><div style="background:#fff;"></div><div style="background:#000;"></div><div style="background:#fff;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Soumettre un Avis -->
        <div>
            <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1.25rem;">Laisser un avis</h2>
            <div class="card">
                <form method="POST" action="{{ route('voyageur.review') }}">
                    @csrf
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">Partagez votre expérience de voyage pour aider les autres utilisateurs.</p>

                    <div class="form-group">
                        <label>Sélectionner le transporteur</label>
                        <select name="operator_id" required>
                            @foreach($operators as $op)
                                <option value="{{ $op->id }}">{{ $op->nom_compagnie }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note globale (1 à 5 étoiles)</label>
                        <select name="note" required>
                            <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5)</option>
                            <option value="4">⭐⭐⭐⭐ Très bon (4/5)</option>
                            <option value="3">⭐⭐⭐ Moyen (3/5)</option>
                            <option value="2">⭐⭐ Insuffisant (2/5)</option>
                            <option value="1">⭐ Mauvais (1/5)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Votre commentaire</label>
                        <textarea name="commentaire" placeholder="Décrivez le confort du bus, la ponctualité, l'accueil du chauffeur..." rows="4"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        Publier l'avis
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
