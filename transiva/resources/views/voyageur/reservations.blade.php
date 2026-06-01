@extends('layouts.app')
@section('title', 'Mes réservations')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex-between" style="margin-bottom:2rem">
        <div>
            <h1 class="page-title">Mes réservations</h1>
            <p class="page-sub">Bonjour <strong>{{ $voyageur->nom }}</strong> — {{ $voyageur->reservations->count() }} réservation(s)</p>
        </div>
        <a href="{{ route('lignes.index') }}" class="btn btn-primary">+ Nouvelle réservation</a>
    </div>

    @if($voyageur->reservations->isEmpty())
        <div class="card" style="text-align:center;padding:4rem;color:var(--muted)">
            <p style="font-size:2rem;margin-bottom:1rem">🎫</p>
            <p>Vous n'avez aucune réservation pour le moment.</p>
            <a href="{{ route('lignes.index') }}" class="btn btn-primary" style="margin-top:1rem">Voir les lignes</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:1rem">
            @foreach($voyageur->reservations as $resa)
            <div class="card" style="padding:1.25rem 1.5rem">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem">
                    <div style="flex:1">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem">
                            <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.05rem">
                                {{ $resa->horaire->ligne->depart }} → {{ $resa->horaire->ligne->arrivee }}
                            </span>
                            @php
                                $badgeClass = match($resa->statut) {
                                    'Confirmée' => 'badge-success',
                                    'Annulée'   => 'badge-danger',
                                    default     => 'badge-warning',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $resa->statut }}</span>
                        </div>
                        <div style="display:flex;gap:2rem;flex-wrap:wrap">
                            <div class="meta-item">
                                <span class="text-muted">Ligne</span>
                                <strong style="display:block;color:var(--white)">{{ $resa->horaire->ligne->nom }}</strong>
                            </div>
                            <div class="meta-item">
                                <span class="text-muted">Date voyage</span>
                                <strong style="display:block;color:var(--white)">{{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}</strong>
                            </div>
                            <div class="meta-item">
                                <span class="text-muted">Horaire</span>
                                <strong style="display:block;color:var(--accent)">
                                    {{ \Carbon\Carbon::parse($resa->horaire->heure_depart)->format('H:i') }}
                                    → {{ \Carbon\Carbon::parse($resa->horaire->heure_arrivee)->format('H:i') }}
                                </strong>
                            </div>
                            <div class="meta-item">
                                <span class="text-muted">Places</span>
                                <strong style="display:block;color:var(--white)">{{ $resa->nb_places }}</strong>
                            </div>
                        </div>
                    </div>

                    @if($resa->billet)
                    <div style="background:#111;border:1px solid #2a2a2a;border-radius:10px;padding:1rem 1.25rem;text-align:center;min-width:160px">
                        <p style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:.4rem">Code billet</p>
                        <p style="font-family:monospace;font-size:.8rem;color:var(--accent);word-break:break-all">{{ $resa->billet->code_qr }}</p>
                        <p style="font-size:.75rem;color:var(--muted);margin-top:.5rem">QR: 🔲</p>
                        <p style="font-size:1rem;font-weight:700;color:var(--white);margin-top:.4rem">{{ number_format($resa->billet->prix, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
