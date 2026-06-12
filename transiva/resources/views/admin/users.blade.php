@extends('layouts.admin')
@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des Utilisateurs</h1>
    <p class="page-sub">Consultez l'ensemble des comptes (Administrateurs, Opérateurs et Voyageurs) enregistrés sur la plateforme.</p>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    @if($users->isEmpty())
        <div style="padding: 4rem; text-align: center; color: var(--muted);">
            Aucun utilisateur enregistré.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nom Complet</th>
                    <th>Adresse Email</th>
                    <th>Téléphone</th>
                    <th>Rôle / Type de compte</th>
                    <th>Réservations Effectuées</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td><code style="font-size: 0.85rem;">{{ $user->email }}</code></td>
                        <td>{{ $user->telephone ?: 'N/A' }}</td>
                        <td>
                            @php
                                $roleClass = match($user->role) {
                                    'Admin'     => 'badge-danger',
                                    'Operator'  => 'badge-success',
                                    default     => 'badge-warning',
                                };
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ $user->role }}</span>
                        </td>
                        <td>
                            <strong style="color: #fff;">{{ $user->reservations_count }}</strong>
                        </td>
                        <td><span class="text-muted" style="font-size: 0.82rem;">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
