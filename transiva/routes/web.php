<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VoyageurController;
use App\Http\Controllers\AdminController;

// ─── Page d'accueil ──────────────────────────────────────────────────────────
Route::get('/', [LigneController::class, 'index'])->name('home');

// ─── Lignes ──────────────────────────────────────────────────────────────────
Route::get('/lignes', [LigneController::class, 'index'])->name('lignes.index');
Route::get('/lignes/{id}', [LigneController::class, 'show'])->name('lignes.show');

// ─── Réservations ────────────────────────────────────────────────────────────
Route::get('/reserver/{horaire_id}', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');

// ─── Espace voyageur ─────────────────────────────────────────────────────────
Route::get('/mon-espace', [VoyageurController::class, 'index'])->name('voyageur.index');
Route::get('/mon-espace/reservations', [VoyageurController::class, 'reservations'])->name('voyageur.reservations');

// ─── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/lignes', [AdminController::class, 'lignes'])->name('lignes');
    Route::post('/lignes', [AdminController::class, 'storeLigne'])->name('lignes.store');
    Route::delete('/lignes/{id}', [AdminController::class, 'destroyLigne'])->name('lignes.destroy');

    Route::get('/horaires', [AdminController::class, 'horaires'])->name('horaires');
    Route::post('/horaires', [AdminController::class, 'storeHoraire'])->name('horaires.store');
    Route::delete('/horaires/{id}', [AdminController::class, 'destroyHoraire'])->name('horaires.destroy');

    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    Route::patch('/reservations/{id}/statut', [AdminController::class, 'updateStatut'])->name('reservations.statut');

    Route::get('/voyageurs', [AdminController::class, 'voyageurs'])->name('voyageurs');
});
