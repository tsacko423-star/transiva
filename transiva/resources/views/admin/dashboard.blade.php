@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Vue d'ensemble de l'activité Transiva</p>
</div>

<!-- Stats -->
<div class="grid-4" style="margin-bottom:2rem">
    <div class="stat-card">
        <p class="stat-label">Lignes</p>
        <p class="stat-value accent">{{ $stats['lignes'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Réservations</p>
        <p class="stat-value">{{ $stats['reservations'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Voyageurs</p>
        <p class="stat-value">{{ $stats['voyageurs'] }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Revenus</p>
        <p class="stat-value accent">{{ number_format($stats['revenus'], 0, ',', ' ') }}</p>
        <p class="text-muted" style="font-size:.78rem;margin-top:.25rem">FCFA</p>
    </div>
</div>

<!-- Statuts réservations -->
<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;margin-bottom:2rem">
    <div class="card">
        <p style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1.25rem">Statuts des réservations</p>
        @foreach($reservations_par_statut as $statut => $total)
            @php
                $color = match($statut) {
                    'Confirmée' => '#4caf82',
                    'Annulée'   => '#e85a4f',
                    default     => '#e8c547',
                };
            @endphp
            <div style="margin-bottom:.85rem">
                <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.3rem">
                    <span>{{ $statut }}</span>
                    <strong>{{ $total }}</strong>
                </div>
                <div style="background:#111;border-radius:4px;height:6px;overflow:hidden">
                    <div style="height:100%;width:{{ $stats['reservations'] > 0 ? ($total / $stats['reservations']) * 100 : 0 }}%;background:{{ $color }};border-radius:4px;transition:width .5s"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card" style="padding:0;overflow:hidden">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #222">
            <p style="font-family:'Syne',sans-serif;font-weight:700">Dernières réservations</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Voyageur</th>
                    <th>Ligne</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dernieres_reservations as $resa)
                <tr>
                    <td>
                        <p style="font-weight:500">{{ $resa->voyageur->nom }}</p>
                        <p style="font-size:.78rem;color:var(--muted)">{{ $resa->voyageur->email }}</p>
                    </td>
                    <td>{{ $resa->horaire->ligne->depart }} → {{ $resa->horaire->ligne->arrivee }}</td>
                    <td style="color:var(--muted)">{{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}</td>
                    <td style="color:var(--accent)">{{ $resa->billet ? number_format($resa->billet->prix, 0) . ' F' : '—' }}</td>
                    <td>
                        @php $bc = match($resa->statut) { 'Confirmée'=>'badge-success','Annulée'=>'badge-danger',default=>'badge-warning' }; @endphp
                        <span class="badge {{ $bc }}">{{ $resa->statut }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Raccourcis rapides -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
    <a href="{{ route('admin.lignes') }}" class="card" style="text-decoration:none;text-align:center;padding:1.5rem;transition:border-color .2s;border-color:#252525" onmouseover="this.style.borderColor='#e8c547'" onmouseout="this.style.borderColor='#252525'">
        <p style="font-size:1.75rem;margin-bottom:.5rem">🗺️</p>
        <p style="font-weight:600">Gérer les lignes</p>
        <p class="text-muted" style="font-size:.8rem">{{ $stats['lignes'] }} ligne(s)</p>
    </a>
    <a href="{{ route('admin.horaires') }}" class="card" style="text-decoration:none;text-align:center;padding:1.5rem;transition:border-color .2s" onmouseover="this.style.borderColor='#e8c547'" onmouseout="this.style.borderColor='#252525'">
        <p style="font-size:1.75rem;margin-bottom:.5rem">🕐</p>
        <p style="font-weight:600">Gérer les horaires</p>
        <p class="text-muted" style="font-size:.8rem">{{ $stats['horaires'] }} horaire(s)</p>
    </a>
    <a href="{{ route('admin.reservations') }}" class="card" style="text-decoration:none;text-align:center;padding:1.5rem;transition:border-color .2s" onmouseover="this.style.borderColor='#e8c547'" onmouseout="this.style.borderColor='#252525'">
        <p style="font-size:1.75rem;margin-bottom:.5rem">🎫</p>
        <p style="font-weight:600">Réservations</p>
        <p class="text-muted" style="font-size:.8rem">{{ $stats['reservations'] }} au total</p>
    </a>
    <a href="{{ route('admin.voyageurs') }}" class="card" style="text-decoration:none;text-align:center;padding:1.5rem;transition:border-color .2s" onmouseover="this.style.borderColor='#e8c547'" onmouseout="this.style.borderColor='#252525'">
        <p style="font-size:1.75rem;margin-bottom:.5rem">👤</p>
        <p style="font-weight:600">Voyageurs</p>
        <p class="text-muted" style="font-size:.8rem">{{ $stats['voyageurs'] }} inscrit(s)</p>
    </a>
</div>
@endsection
