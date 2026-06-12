@extends('layouts.app')
@section('title', 'Gestion des Lignes — ' . $operator->nom_compagnie)

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
                <p class="text-muted" style="margin-top: 0.2rem;">Gérez vos lignes de transport et configurez des points de départ et d'arrivée.</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Barre de Navigation Opérateur -->
        <div class="op-sidebar">
            <a href="{{ route('operator.dashboard') }}" class="op-nav-link">📊 Dashboard</a>
            <a href="{{ route('operator.vehicles') }}" class="op-nav-link">🚌 Ma Flotte</a>
            <a href="{{ route('operator.routes') }}" class="op-nav-link active">🗺️ Mes Lignes</a>
            <a href="{{ route('operator.trips') }}" class="op-nav-link">🕐 Trajets & Tarifs</a>
            <a href="{{ route('operator.reservations') }}" class="op-nav-link">🎫 Ventes & Résa</a>
        </div>

        <!-- Corps principal -->
        <div>
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
            @endif

            <div class="grid-2" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
                <!-- Liste des routes/lignes -->
                <div>
                    <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Mes Lignes actives ({{ $routes->count() }})</h2>
                    
                    @if($routes->isEmpty())
                        <div class="card" style="text-align:center; padding:3rem; color:var(--muted)">
                            Aucune ligne créée pour le moment. Utilisez le formulaire pour ajouter votre premier trajet.
                        </div>
                    @else
                        <div class="card" style="padding:0; overflow:hidden">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Durée estimée</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $route)
                                        <tr>
                                            <td><strong>{{ $route->nom }}</strong></td>
                                            <td>{{ $route->depart }}</td>
                                            <td>{{ $route->arrivee }}</td>
                                            <td><strong style="color:var(--accent);">{{ $route->duree_min }} min</strong></td>
                                            <td>
                                                <form action="{{ route('operator.routes.destroy', $route->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ligne ? Les trajets associés seront impactés.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Ajouter une route -->
                <div>
                    <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin-bottom: 1rem;">Créer une Ligne</h2>
                    <div class="card">
                        <form method="POST" action="{{ route('operator.routes.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Nom de la Ligne</label>
                                <input type="text" name="nom" placeholder="Ex: Ligne Bamako - Ségou VIP" required>
                            </div>
                            <div class="form-group">
                                <label>Ville de départ</label>
                                <input type="text" name="depart" placeholder="Ex: Bamako" required>
                            </div>
                            <div class="form-group">
                                <label>Ville d'arrivée</label>
                                <input type="text" name="arrivee" placeholder="Ex: Ségou" required>
                            </div>
                            <div class="form-group">
                                <label>Durée du trajet (Minutes)</label>
                                <input type="number" name="duree_min" placeholder="Ex: 240" min="1" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
