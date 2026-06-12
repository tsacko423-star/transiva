@extends('layouts.app')
@section('title', 'Réserver vos places')

@section('styles')
<style>
.seat-map-container {
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
}
.bus-layout {
    display: inline-block;
    background: #0d0d0d;
    border: 2px solid #333;
    border-radius: 10px;
    padding: 1.5rem 1rem;
    margin: 1rem 0;
    position: relative;
    max-width: 320px;
    width: 100%;
}
.bus-front {
    border-bottom: 2px dashed #444;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.75rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.seat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    justify-items: center;
}
.seat {
    width: 38px;
    height: 38px;
    border: 1px solid #333;
    background: #181818;
    color: #888;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    user-select: none;
}
.seat:hover:not(.occupied) {
    border-color: var(--accent);
    color: var(--accent);
    background: rgba(232,197,71,0.05);
}
.seat.selected {
    background: var(--accent) !important;
    border-color: var(--accent) !important;
    color: var(--black) !important;
}
.seat.occupied {
    background: #2a1111 !important;
    border-color: #441a1a !important;
    color: #552222 !important;
    cursor: not-allowed;
}
/* Allée au milieu du bus */
.seat-grid > div:nth-child(4n-2) {
    margin-right: 1.5rem;
}

.legend {
    display: flex;
    justify-content: center;
    gap: 1rem;
    font-size: 0.75rem;
    margin-top: 1rem;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--muted);
}
.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}
</style>
@endsection

@section('content')
<div class="container" style="max-width: 900px;">
    <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem">← Retour</a>

    <h1 class="page-title">Réserver vos places</h1>
    <p class="page-sub">Sélectionnez vos sièges dans le bus et complétez les informations pour finaliser l'achat de vos billets.</p>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid-2">
        <!-- Colonne Gauche : Plan des Sièges -->
        <div>
            <div class="seat-map-container">
                <p style="font-family:'Syne',sans-serif; font-weight:700; color:#fff;">Plan du véhicule ({{ $trip->vehicle->type }})</p>
                <p class="text-muted" style="font-size: 0.8rem; margin-top:0.25rem;">Sélectionnez un ou plusieurs sièges pour continuer.</p>
                
                <div class="bus-layout">
                    <div class="bus-front">Avant du bus 👤 Chauffeur</div>
                    
                    <div class="seat-grid">
                        @for($i = 1; $i <= $trip->vehicle->capacite; $i++)
                            @php
                                $isOccupied = in_array((string)$i, $siegesOccupes);
                            @endphp
                            <div class="seat {{ $isOccupied ? 'occupied' : '' }}" data-seat-num="{{ $i }}">
                                {{ $i }}
                            </div>
                        @endfor
                    </div>
                    
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background:#181818; border:1px solid #333;"></div>
                            Libre
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background:var(--accent);"></div>
                            Sélectionné
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background:#2a1111; border:1px solid #441a1a;"></div>
                            Occupé
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Formulaire & Paiement -->
        <div>
            <div class="card">
                <form method="POST" action="{{ route('reservations.store') }}" id="bookingForm">
                    @csrf
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                    <input type="hidden" name="date_voyage" value="{{ $date_voyage }}">
                    <input type="hidden" name="nb_places" id="nb_places_input" value="0">
                    <input type="hidden" name="sieges" id="sieges_input" value="">

                    <!-- Détails Trajet -->
                    <div style="background:#111; border-radius:8px; padding:1rem; margin-bottom:1.5rem; font-size:0.85rem; border:1px solid #222;">
                        <p style="font-size:0.75rem; text-transform:uppercase; color:var(--muted); margin-bottom:0.25rem;">Compagnie : <strong>{{ $trip->vehicle->operator->nom_compagnie }}</strong></p>
                        <p style="color:#fff; font-weight:600; margin-bottom:0.25rem;">{{ $trip->route->depart }} → {{ $trip->route->arrivee }}</p>
                        <p class="text-accent" style="font-weight:600;">Départ : {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H:i') }} le {{ \Carbon\Carbon::parse($date_voyage)->format('d/m/Y') }}</p>
                        <p class="text-muted" style="margin-top:0.25rem;">Tarif unitaire : {{ number_format($trip->prix, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <p style="font-family:'Syne',sans-serif; font-weight:700; margin-bottom:1rem; color:#fff;">Coordonnées Voyageur</p>
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom" value="{{ old('nom', auth()->check() ? auth()->user()->name : '') }}" placeholder="Ex: Fatoumata Sidibé" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" placeholder="Ex: fatou@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', auth()->check() ? auth()->user()->telephone : '') }}" placeholder="Ex: +223 XX XX XX XX" required>
                    </div>

                    <p style="font-family:'Syne',sans-serif; font-weight:700; margin:1.5rem 0 1rem; color:#fff;">Paiement Sécurisé</p>
                    <div class="form-group">
                        <label>Mode de Paiement</label>
                        <select name="mode_paiement" required>
                            <option value="Mobile Money">Mobile Money (Orange Money, Wave, Mobicash)</option>
                            <option value="Carte Bancaire">Carte Bancaire (Visa, Mastercard)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Numéro de paiement (Mobile ou Carte)</label>
                        <input type="text" name="numero_paiement" placeholder="Numéro de téléphone ou numéro de carte" required>
                    </div>

                    <!-- Recap Facturation -->
                    <div style="background:#141414; border-radius:8px; padding:1.25rem; margin:1.5rem 0; border: 1px dashed #333;">
                        <div class="flex-between" style="margin-bottom:0.5rem; font-size:0.9rem;">
                            <span class="text-muted">Sièges sélectionnés :</span>
                            <strong style="color:#fff;" id="selected-seats-label">-</strong>
                        </div>
                        <div class="flex-between" style="border-top:1px solid #222; padding-top:0.5rem;">
                            <span style="font-weight:600;">Total à payer :</span>
                            <strong style="color:var(--accent); font-size:1.3rem;" id="total-price-label">0 FCFA</strong>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn" style="width:100%; justify-content:center;" disabled>
                        Sélectionner au moins un siège
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seats = document.querySelectorAll('.seat:not(.occupied)');
    const selectedSeatsLabel = document.getElementById('selected-seats-label');
    const totalPriceLabel = document.getElementById('total-price-label');
    const nbPlacesInput = document.getElementById('nb_places_input');
    const siegesInput = document.getElementById('sieges_input');
    const submitBtn = document.getElementById('submitBtn');
    
    const unitPrice = {{ $trip->prix }};
    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            const seatNum = this.getAttribute('data-seat-num');
            
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s !== seatNum);
            } else {
                if (selectedSeats.length >= 10) {
                    alert("Vous pouvez réserver un maximum de 10 sièges par commande.");
                    return;
                }
                this.classList.add('selected');
                selectedSeats.push(seatNum);
            }
            
            updateRecap();
        });
    });

    function updateRecap() {
        if (selectedSeats.length > 0) {
            selectedSeats.sort((a, b) => a - b);
            selectedSeatsLabel.textContent = selectedSeats.join(', ');
            totalPriceLabel.textContent = (selectedSeats.length * unitPrice).toLocaleString('fr-FR') + ' FCFA';
            nbPlacesInput.value = selectedSeats.length;
            siegesInput.value = selectedSeats.join(',');
            submitBtn.disabled = false;
            submitBtn.textContent = "Confirmer et Payer " + (selectedSeats.length * unitPrice).toLocaleString('fr-FR') + " FCFA";
        } else {
            selectedSeatsLabel.textContent = '-';
            totalPriceLabel.textContent = '0 FCFA';
            nbPlacesInput.value = 0;
            siegesInput.value = '';
            submitBtn.disabled = true;
            submitBtn.textContent = "Sélectionner au moins un siège";
        }
    }
});
</script>
@endsection
