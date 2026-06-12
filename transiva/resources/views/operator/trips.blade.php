@extends('layouts.app')
@section('title', 'Planification des Voyages & Tarifs — ' . $operator->nom_compagnie)

@section('styles')
<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 2rem;
}
.op-sidebar {
    background: var(--gray);
    border: 1px solid #222;
    border-radius: 12px;
    padding: 1rem 0;
    display: flex;
    flex-direction: column;
    height: fit-content;
}
.op-nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    color: var(--muted);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}
.op-nav-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.02);
}
.op-nav-link.active {
    color: var(--accent);
    background: rgba(232, 197, 71, 0.05);
    border-left-color: var(--accent);
}
@media (max-width: 900px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="container">
    <div style="background: linear-gradient(135deg, #111 0%, #162a1a 100%); border: 1px solid rgba(76,175,130,0.2); border-radius: 14px; padding: 2rem; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div style="width: 60px; height: 60px; background: rgba(76,175,130,0.1); border: 1px solid rgba(76,175,130,0.25); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #4caf82;">
                💼
            </div>
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem; color: #fff;">{{ $operator->nom_compagnie }}</h1>
                <p class="text-muted" style="margin-top: 0.2rem;">Planifiez des départs spécifiques, affectez vos bus et définissez vos tarifs librement.</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Barre de Navigation Opérateur -->
        <div class="op-sidebar">
            <a href="{{ route('operator.dashboard') }}" class="op-nav-link">📊 Dashboard</a>
            <a href="{{ route('operator.vehicles') }}" class="op-nav-link">🚌 Ma Flotte</a>
            <a href="{{ route('operator.routes') }}" class="op-nav-link">🗺️ Mes Lignes</a>
            <a href="{{ route('operator.trips') }}" class="op-nav-link active">🕐 Trajets & Tarifs</a>
            <a href="{{ route('operator.reservations') }}" class="op-nav-link">🎫 Ventes & Résa</a>
        </div>

        <!-- Corps principal -->
        <div>
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
            @endif

            <div class="grid-2" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
                <!-- Liste des trajets programmés -->
                <div>
                    <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Voyages Planifiés ({{ $trips->count() }})</h2>
                    
                    @if($trips->isEmpty())
                        <div class="card" style="text-align:center; padding:3rem; color:var(--muted)">
                            Aucun voyage planifié pour le moment. Veuillez remplir le formulaire pour ajouter des départs.
                        </div>
                    @else
                        <div class="card" style="padding:0; overflow:hidden">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Trajet</th>
                                        <th>Véhicule</th>
                                        <th>Horaires</th>
                                        <th>Jours</th>
                                        <th>Prix Billet</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trips as $trip)
                                        <tr>
                                            <td>
                                                <strong>{{ $trip->route->depart }} → {{ $trip->route->arrivee }}</strong>
                                                <span style="display:block; font-size:0.75rem; color:var(--muted);">{{ $trip->route->nom }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $trip->vehicle->nom }}</strong>
                                                <span style="display:block; font-size:0.75rem; color:var(--muted);">Capacité : {{ $trip->vehicle->capacite }}</span>
                                            </td>
                                            <td>
                                                <strong style="color:var(--accent);">{{ \Carbon\Carbon::parse($trip->heure_depart)->format('H:i') }}</strong>
                                                <span style="color:var(--muted);">→ {{ \Carbon\Carbon::parse($trip->heure_arrivee)->format('H:i') }}</span>
                                            </td>
                                            <td><span class="text-muted" style="font-size:0.82rem;">{{ $trip->jours }}</span></td>
                                            <td><strong style="color:#fff;">{{ number_format($trip->prix, 0, ',', ' ') }} FCFA</strong></td>
                                            <td>
                                                <form action="{{ route('operator.trips.destroy', $trip->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce voyage ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Planifier un départ -->
                <div>
                    <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Planifier un Voyage</h2>
                    
                    @if($routes->isEmpty() || $vehicles->isEmpty())
                        <div class="card" style="padding:1rem; background:rgba(232,90,79,0.06); border:1px solid rgba(232,90,79,0.2); border-radius:8px; font-size:0.85rem; color:#f08b84;">
                            ⚠️ Vous devez d'abord ajouter au moins <strong>une ligne</strong> et <strong>un véhicule</strong> dans votre espace avant de pouvoir planifier des voyages.
                        </div>
                    @else
                        <div class="card">
                            <form method="POST" action="{{ route('operator.trips.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Ligne de transport</label>
                                    <select name="route_id" required>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}">{{ $route->depart }} → {{ $route->arrivee }} ({{ $route->nom }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Véhicule affecté</label>
                                    <select name="vehicle_id" required>
                                        @foreach($vehicles as $veh)
                                            <option value="{{ $veh->id }}">{{ $veh->nom }} ({{ $veh->immatriculation }} - {{ $veh->capacite }} pl.)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Heure de départ</label>
                                    <input type="time" name="heure_depart" required>
                                </div>
                                <div class="form-group">
                                    <label>Heure d'arrivée estimée</label>
                                    <input type="time" name="heure_arrivee" required>
                                </div>
                                <div class="form-group">
                                    <label>Jours de circulation</label>
                                    <input type="text" name="jours" placeholder="Ex: Tous les jours, Lun - Ven, Sam - Dim" required>
                                </div>
                                <div class="form-group">
                                    <label>Prix du billet (FCFA)</label>
                                    <input type="number" name="prix" placeholder="Ex: 5000" min="0" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
                                    Planifier
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
