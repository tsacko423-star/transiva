<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'Trips';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - route_id : ID de la route (Ligne) associée.
    // - vehicle_id : ID du véhicule assigné.
    // - heure_depart : Heure de départ du voyage.
    // - heure_arrivee : Heure d'arrivée estimée.
    // - jours : Les jours de service (jours de circulation).
    // - prix : Prix du billet (remplace le tarif fixe global de 500 FCFA).
    protected $fillable = [
        'route_id',
        'vehicle_id',
        'heure_depart',
        'heure_arrivee',
        'jours',
        'prix',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'trip_id');
    }
}
