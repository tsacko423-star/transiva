@extends('layouts.admin')
@section('title', 'Réservations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Réservations</h1>
    <p class="page-sub">{{ $reservations->count() }} réservation(s) au total</p>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Voyageur</th>
                <th>Ligne</th>
                <th>Date voyage</th>
                <th>Places</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $resa)
            <tr>
                <td class="text-muted">{{ $resa->id }}</td>
                <td>
                    <p style="font-weight:500">{{ $resa->voyageur->nom }}</p>
                    <p style="font-size:.78rem;color:var(--muted)">{{ $resa->voyageur->email }}</p>
                </td>
                <td>
                    <p>{{ $resa->horaire->ligne->depart }} → {{ $resa->horaire->ligne->arrivee }}</p>
                    <p style="font-size:.78rem;color:var(--accent)">
                        {{ \Carbon\Carbon::parse($resa->horaire->heure_depart)->format('H:i') }}
                    </p>
                </td>
                <td>{{ \Carbon\Carbon::parse($resa->date_voyage)->format('d/m/Y') }}</td>
                <td>{{ $resa->nb_places }}</td>
                <td style="color:var(--accent)">{{ $resa->billet ? number_format($resa->billet->prix, 0).' F' : '—' }}</td>
                <td>
                    @php $bc = match($resa->statut) { 'Confirmée'=>'badge-success','Annulée'=>'badge-danger',default=>'badge-warning' }; @endphp
                    <span class="badge {{ $bc }}">{{ $resa->statut }}</span>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.reservations.statut', $resa->id) }}" style="display:flex;gap:.4rem">
                        @csrf @method('PATCH')
                        <select name="statut" style="padding:.3rem .6rem;font-size:.78rem">
                            <option value="En attente" {{ $resa->statut=='En attente'?'selected':'' }}>En attente</option>
                            <option value="Confirmée"  {{ $resa->statut=='Confirmée'?'selected':'' }}>Confirmée</option>
                            <option value="Annulée"    {{ $resa->statut=='Annulée'?'selected':'' }}>Annulée</option>
                        </select>
                        <button type="submit" class="btn btn-outline btn-sm">✓</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:2rem">Aucune réservation.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
