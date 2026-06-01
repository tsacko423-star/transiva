@extends('layouts.admin')
@section('title', 'Voyageurs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Voyageurs</h1>
    <p class="page-sub">{{ $voyageurs->count() }} voyageur(s) enregistré(s)</p>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Réservations</th>
            </tr>
        </thead>
        <tbody>
            @forelse($voyageurs as $v)
            <tr>
                <td class="text-muted">{{ $v->id }}</td>
                <td><strong>{{ $v->nom }}</strong></td>
                <td class="text-muted">{{ $v->email }}</td>
                <td class="text-muted">{{ $v->telephone }}</td>
                <td><span class="badge badge-warning">{{ $v->reservations_count }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:2rem">Aucun voyageur.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
