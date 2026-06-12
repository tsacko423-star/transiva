@extends('layouts.admin')
@section('title', 'Transactions Financières')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rapports Financiers & Transactions</h1>
    <p class="page-sub">Consultez l'ensemble des réservations payées et suivez les flux financiers de la plateforme.</p>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    @if($payments->isEmpty())
        <div style="padding: 4rem; text-align: center; color: var(--muted);">
            Aucune transaction financière n'a été enregistrée sur la plateforme.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date Paiement</th>
                    <th>Réf Transaction</th>
                    <th>Client</th>
                    <th>Transporteur / Voyage</th>
                    <th>Mode</th>
                    <th>Montant Brut</th>
                    <th>Taux Com.</th>
                    <th>Commission Transiva</th>
                    <th>Revenu Opérateur</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $pay)
                    @php
                        $resa = $pay->reservation;
                        $operator = $resa->trip->route->operator ?? null;
                        $rate = $operator ? $operator->commission_rate : 10.0;
                        $commission = $pay->montant * ($rate / 100);
                        $revenuOp = $pay->montant - $commission;
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pay->date_paiement)->format('d/m/Y H:i') }}</td>
                        <td><code style="font-size: 0.82rem;">{{ $pay->reference_transaction }}</code></td>
                        <td>
                            <strong>{{ $resa->user->name }}</strong>
                            <span class="text-muted" style="display: block; font-size: 0.72rem;">{{ $resa->user->email }}</span>
                        </td>
                        <td>
                            <strong>{{ $operator->nom_compagnie ?? 'N/A' }}</strong>
                            <span class="text-muted" style="display: block; font-size: 0.72rem;">{{ $resa->trip->route->depart }} → {{ $resa->trip->route->arrivee }}</span>
                        </td>
                        <td><span class="badge badge-warning" style="background: rgba(255,255,255,0.05); color: #fff;">{{ $pay->mode_paiement }}</span></td>
                        <td><strong style="color: #fff;">{{ number_format($pay->montant, 0, ',', ' ') }} FCFA</strong></td>
                        <td><span class="text-muted">{{ number_format($rate, 1) }}%</span></td>
                        <td style="color: #4caf82; font-weight: 600;">+{{ number_format($commission, 0, ',', ' ') }} FCFA</td>
                        <td style="color: var(--accent); font-weight: 600;">{{ number_format($revenuOp, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge badge-success">{{ $pay->statut }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
