@extends('layouts.app')
@section('title', 'Réservations et Ventes — ' . $operator->nom_compagnie)

@section('styles')
<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 2rem;
}
.op-sidebar {
    background: var(--gray);
    border: 1px solid #222;
    border-radius: 12px;
    padding: 1rem 0;
    display: flex;
    flex-direction: column;
    height: fit-content;
}
.op-nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    color: var(--muted);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}
.op-nav-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.02);
}
.op-nav-link.active {
    color: var(--accent);
    background: rgba(232, 197, 71, 0.05);
    border-left-color: var(--accent);
}
@media (max-width: 900px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="container">
    <div style="background: linear-gradient(135deg, #111 0%, #162a1a 100%); border: 1px solid rgba(76,175,130,0.2); border-radius: 14px; padding: 2rem; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div style="width: 60px; height: 60px; background: rgba(76,175,130,0.1); border: 1px solid rgba(76,175,130,0.25); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #4caf82;">
                💼
            </div>
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem; color: #fff;">{{ $operator->nom_compagnie }}</h1>
                <p class="text-muted" style="margin-top: 0.2rem;">Suivez toutes les réservations, affectations de sièges et paiements de vos passagers.</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Barre de Navigation Opérateur -->
        <div class="op-sidebar">
            <a href="{{ route('operator.dashboard') }}" class="op-nav-link">📊 Dashboard</a>
            <a href="{{ route('operator.vehicles') }}" class="op-nav-link">🚌 Ma Flotte</a>
            <a href="{{ route('operator.routes') }}" class="op-nav-link">🗺️ Mes Lignes</a>
            <a href="{{ route('operator.trips') }}" class="op-nav-link">🕐 Trajets & Tarifs</a>
            <a href="{{ route('operator.reservations') }}" class="op-nav-link active">🎫 Ventes & Résa</a>
        </div>

        <!-- Corps principal -->
        <div>
            <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Toutes les Réservations ({{ $reservations->count() }})</h2>
            
            <div class="card" style="padding:0; overflow:hidden">
                @if($reservations->isEmpty())
                    <div style="padding:4rem; text-align:center; color:var(--muted);">
                        Aucune réservation enregistrée sur vos lignes de transport pour l'instant.
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Ligne & Voyage</th>
                                <th>Date Voyage</th>
                                <th>Places</th>
                                <th>Sièges</th>
                                <th>Code Billet</th>
                                <th>Mode Paiement</th>
                                <th>Chiffre d'Affaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $resa)
                                <tr>
                                    <td>
                                        <strong>{{ $resa->user->name }}</strong>
                                        <span class="text-muted" style="display:block; font-size:0.75rem;">{{ $resa->user->email }}</span>
                                    </td>
                                    <td>{{ $resa->user->telephone ?: 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $resa->trip->route->depart }} → {{ $resa->trip->route->arrivee }}</strong>
                                        <span class="text-muted" style="display:block; font-size:0.75rem;">{{ \Carbon\Carbon::parse($resa->trip->heure_depart)->format('H:i') }} | {{ $resa->trip->vehicle->nom }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}</td>
                                    <td><strong style="color:var(--accent);">{{ $resa->nb_places }}</strong></td>
                                    <td><code style="background:#000; border:1px solid #222; padding:0.25rem; border-radius:4px;">{{ $resa->sieges ?: 'N/A' }}</code></td>
                                    <td><code style="font-size:0.78rem;">{{ $resa->ticket->code_reservation ?? 'N/A' }}</code></td>
                                    <td>
                                        <span class="badge badge-success">{{ $resa->payment->mode_paiement ?? 'Mobile Money' }}</span>
                                    </td>
                                    <td><strong style="color:#fff;">{{ number_format($resa->ticket->prix_total ?? 0, 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
