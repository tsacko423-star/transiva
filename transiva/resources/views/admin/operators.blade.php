@extends('layouts.admin')
@section('title', 'Gestion des Opérateurs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des Opérateurs de Transport</h1>
    <p class="page-sub">Validez les nouveaux transporteurs et modifiez les taux de commission.</p>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    @if($operators->isEmpty())
        <div style="padding: 4rem; text-align: center; color: var(--muted);">
            Aucun opérateur de transport n'est enregistré.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Compagnie</th>
                    <th>Gestionnaire</th>
                    <th>Téléphone</th>
                    <th>Flotte</th>
                    <th>Lignes</th>
                    <th>Taux Commission</th>
                    <th>Statut</th>
                    <th>Action / Validation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($operators as $op)
                    <tr>
                        <td>
                            <strong>{{ $op->nom_compagnie }}</strong>
                            <span class="text-muted" style="display: block; font-size: 0.75rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $op->description ?: 'Pas de description.' }}
                            </span>
                        </td>
                        <td>{{ $op->user->name }}</td>
                        <td>{{ $op->user->telephone ?: 'N/A' }}</td>
                        <td><span style="font-weight: 600; color: #fff;">{{ $op->vehicles_count }} bus</span></td>
                        <td><span style="font-weight: 600; color: #fff;">{{ $op->routes_count }} lignes</span></td>
                        <td>
                            <strong style="color: var(--accent);">{{ number_format($op->commission_rate, 2) }}%</strong>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($op->statut) {
                                    'Valide'    => 'badge-success',
                                    'Suspendu'  => 'badge-danger',
                                    default     => 'badge-warning',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $op->statut }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.operators.status', $op->id) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                @csrf
                                @method('PATCH')
                                <select name="statut" style="padding: 0.25rem; font-size: 0.78rem; background: #000; border: 1px solid #333; color: #fff; border-radius: 4px;">
                                    <option value="Valide" {{ $op->statut === 'Valide' ? 'selected' : '' }}>Valide</option>
                                    <option value="En attente" {{ $op->statut === 'En attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="Suspendu" {{ $op->statut === 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
                                </select>
                                <input type="number" name="commission_rate" value="{{ $op->commission_rate }}" step="0.5" min="0" max="100" style="width: 65px; padding: 0.25rem; font-size: 0.78rem; background: #000; border: 1px solid #333; color: #fff; border-radius: 4px;" title="Taux de commission %">
                                <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
