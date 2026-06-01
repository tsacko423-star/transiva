@extends('layouts.app')
@section('title', 'Mon espace voyageur')

@section('content')
<div class="container" style="max-width:520px;padding-top:5rem">
    <div style="text-align:center;margin-bottom:2.5rem">
        <div style="width:64px;height:64px;background:rgba(232,197,71,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.75rem">🎫</div>
        <h1 class="page-title">Mon espace voyageur</h1>
        <p class="page-sub">Entrez votre email pour consulter vos réservations et billets.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <form method="GET" action="{{ route('voyageur.reservations') }}">
            <div class="form-group">
                <label>Adresse email</label>
                <input type="email" name="email" placeholder="votre@email.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Accéder à mes réservations
            </button>
        </form>
    </div>
</div>
@endsection
