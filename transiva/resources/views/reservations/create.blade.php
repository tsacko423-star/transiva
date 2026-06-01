@extends('layouts.app')
@section('title', 'Réserver')

@section('content')
<div class="container" style="max-width:640px">
    <a href="{{ route('lignes.show', $horaire->ligne_id) }}" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem">← Retour</a>

    <h1 class="page-title">Réserver un billet</h1>
    <p class="page-sub">Complétez le formulaire pour confirmer votre place.</p>

    <div class="card" style="margin-bottom:1.5rem;padding:1rem 1.5rem">
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
            <div>
                <p class="text-muted" style="font-size:.75rem;margin-bottom:.2rem">LIGNE</p>
                <p style="font-weight:600">{{ $horaire->ligne->nom }}</p>
            </div>
            <div>
                <p class="text-muted" style="font-size:.75rem;margin-bottom:.2rem">TRAJET</p>
                <p style="font-weight:600">{{ $horaire->ligne->depart }} → {{ $horaire->ligne->arrivee }}</p>
            </div>
            <div>
                <p class="text-muted" style="font-size:.75rem;margin-bottom:.2rem">HORAIRE</p>
                <p style="font-weight:600;color:var(--accent)">
                    {{ \Carbon\Carbon::parse($horaire->heure_depart)->format('H:i') }}
                    → {{ \Carbon\Carbon::parse($horaire->heure_arrivee)->format('H:i') }}
                </p>
            </div>
            <div>
                <p class="text-muted" style="font-size:.75rem;margin-bottom:.2rem">JOURS</p>
                <p style="font-weight:600">{{ $horaire->jours }}</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style:none">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf
            <input type="hidden" name="horaire_id" value="{{ $horaire->id }}">

            <p style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1.25rem">Vos informations</p>

            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex: Amadou Diallo" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" placeholder="+223 XX XX XX XX" required>
            </div>

            <p style="font-family:'Syne',sans-serif;font-weight:700;margin:1.5rem 0 1.25rem">Détails du voyage</p>

            <div class="grid-2">
                <div class="form-group">
                    <label>Date du voyage</label>
                    <input type="date" name="date_voyage" value="{{ old('date_voyage') }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Nombre de places</label>
                    <select name="nb_places" required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('nb_places') == $i ? 'selected' : '' }}>{{ $i }} place{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div style="background:#111;border-radius:var(--radius);padding:1rem;margin-bottom:1.5rem;font-size:.85rem;color:var(--muted)">
                💡 Un billet avec code QR sera généré automatiquement après confirmation. Le tarif est de <strong style="color:var(--white)">500 FCFA</strong> par place.
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Confirmer la réservation
            </button>
        </form>
    </div>
</div>
@endsection
