<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VoyageurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;

// ─── Simulateur de connexion ──────────────────────────────────────────────────
// Note : Cette route permet de basculer de profil en 1 clic pour tester les fonctionnalités.
// Accessible en dehors du groupe middleware pour éviter une boucle de redirection.
Route::get('/switch-user/{id}', function ($id) {
    session(['simulated_user_id' => $id]);
    return redirect()->back()->with('success', 'Changement de profil effectué !');
})->name('switch-user');

// ─── Groupe principal ─────────────────────────────────────────────────────────
// Note (variables anglais) :
//   - 'simulate.user' : alias du middleware SimulateUser (App\Http\Middleware\SimulateUser)
//     Pourquoi : Route::middleware() requiert un string (alias enregistré dans Kernel.php),
//                non une Closure — correction de l'erreur "Closure could not be converted to string"
//     Où : Kernel.php → $middlewareAliases → 'simulate.user'
Route::middleware(['web', 'simulate.user'])->group(function () {

    // ─── Public / Recherche & Comparaison ───────────────────────────────────────
    Route::get('/', [LigneController::class, 'index'])->name('home');
    Route::get('/trips', [LigneController::class, 'search'])->name('trips.search');
    Route::get('/operator/{id}/profile', [LigneController::class, 'operatorProfile'])->name('operator.profile');

    // ─── Réservations ────────────────────────────────────────────────────────────
    Route::get('/reserver/{trip_id}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');

    // ─── Espace voyageur (Gestion billets & avis) ───────────────────────────────
    Route::get('/mon-espace', [VoyageurController::class, 'index'])->name('voyageur.index');
    Route::get('/mon-espace/reservations', [VoyageurController::class, 'reservations'])->name('voyageur.reservations');
    Route::post('/mon-espace/avis', [VoyageurController::class, 'storeReview'])->name('voyageur.review');

    // ─── Portail Transport Operator (Gestion flotte, trajets, prix) ─────────────
    Route::prefix('operator')->name('operator.')->group(function () {
        Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');

        Route::get('/vehicles', [OperatorController::class, 'vehicles'])->name('vehicles');
        Route::post('/vehicles', [OperatorController::class, 'storeVehicle'])->name('vehicles.store');
        Route::delete('/vehicles/{id}', [OperatorController::class, 'destroyVehicle'])->name('vehicles.destroy');

        Route::get('/routes', [OperatorController::class, 'routes'])->name('routes');
        Route::post('/routes', [OperatorController::class, 'storeRoute'])->name('routes.store');
        Route::delete('/routes/{id}', [OperatorController::class, 'destroyRoute'])->name('routes.destroy');

        Route::get('/trips', [OperatorController::class, 'trips'])->name('trips');
        Route::post('/trips', [OperatorController::class, 'storeTrip'])->name('trips.store');
        Route::delete('/trips/{id}', [OperatorController::class, 'destroyTrip'])->name('trips.destroy');

        Route::get('/reservations', [OperatorController::class, 'reservations'])->name('reservations');
    });

    // ─── Portail Admin Plateforme (Validation, commissions, rapports) ───────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/operators', [AdminController::class, 'operators'])->name('operators');
        Route::patch('/operators/{id}/status', [AdminController::class, 'updateOperatorStatus'])->name('operators.status');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
    });
});
