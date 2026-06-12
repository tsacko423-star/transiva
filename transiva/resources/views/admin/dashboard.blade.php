@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Plateforme Transiva</h1>
    <p class="page-sub">Vue d'ensemble de la place de marché multi-opérateurs.</p>
</div>

<!-- Grid Statistiques Globales -->
<div class="grid-4" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <p class="stat-label">Opérateurs Partenaires</p>
        <p class="stat-value">{{ $operatorsCount }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Lignes créées</p>
        <p class="stat-value">{{ $routesCount }}</p>
    </div>
    <div class="stat-card" style="border-color: rgba(232,197,71,0.25);">
        <p class="stat-label" style="color: var(--accent);">Volume de Ventes</p>
        <p class="stat-value accent">{{ number_format($volumeTransactions, 0, ',', ' ') }} FCFA</p>
    </div>
    <div class="stat-card" style="border-color: rgba(76,175,130,0.25);">
        <p class="stat-label" style="color: #7dd9a8;">Commissions Transiva</p>
        <p class="stat-value" style="color: #4caf82;">{{ number_format($totalCommissions, 0, ',', ' ') }} FCFA</p>
    </div>
</div>

<div class="grid-2" style="grid-template-columns: 1fr 1.5fr; gap: 1.5rem; margin-bottom: 2rem; align-items: start;">
    <!-- Opérateurs en attente de validation -->
    <div>
        <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Validation Opérateurs</h2>
        <div class="card" style="padding: 1rem 1.25rem;">
            @if($pendingOperators->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 2rem 0;">Aucune demande de validation en attente.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($pendingOperators as $op)
                        <div style="border-bottom: 1px solid #333; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                            <strong style="color: #fff;">{{ $op->nom_compagnie }}</strong>
                            <p style="font-size: 0.78rem; color: var(--muted); margin-bottom: 0.5rem;">Géré par : {{ $op->user->name }} | Tél : {{ $op->user->telephone }}</p>
                            <form action="{{ route('admin.operators.status', $op->id) }}" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="Valide">
                                <input type="number" name="commission_rate" value="10" style="width: 70px; padding: 0.2rem; font-size: 0.75rem; background: #000; border: 1px solid #333;" title="Taux commission %">
                                <button type="submit" class="btn btn-primary btn-sm" style="background: #4caf82; color: #fff;">Valider (10%)</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Dernières Réservations globales -->
    <div>
        <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Réservations Globales Récents</h2>
        <div class="card" style="padding: 0; overflow: hidden;">
            @if($recentReservations->isEmpty())
                <div style="padding: 3rem; text-align: center; color: var(--muted);">Aucune réservation plateforme trouvée.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Opérateur</th>
                            <th>Trajet</th>
                            <th>Montant</th>
                            <th>Com.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentReservations as $resa)
                            @php
                                $rate = $resa->trip->route->operator->commission_rate ?? 10.0;
                                $commission = ($resa->ticket->prix_total ?? 0) * ($rate / 100);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $resa->user->name }}</strong>
                                    <span class="text-muted" style="display: block; font-size: 0.7rem;">{{ $resa->user->email }}</span>
                                </td>
                                <td><span class="badge badge-warning" style="background: rgba(255,255,255,0.05); color: #fff;">{{ $resa->trip->route->operator->nom_compagnie ?? 'N/A' }}</span></td>
                                <td>
                                    <strong>{{ $resa->trip->route->depart }} → {{ $resa->trip->route->arrivee }}</strong>
                                    <span class="text-muted" style="display: block; font-size: 0.7rem;">{{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}</span>
                                </td>
                                <td>{{ number_format($resa->ticket->prix_total ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td style="color: #4caf82; font-weight: 600;">+{{ number_format($commission, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
