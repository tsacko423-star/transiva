<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'Vehicles';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - operator_id : ID de l'opérateur (compagnie) auquel appartient le véhicule.
    // - nom : Libellé ou nom de code (ex: Car Climatisé VIP).
    // - immatriculation : Plaque d'immatriculation (plate number).
    // - capacite : Capacité maximale de sièges (seat capacity).
    // - type : Type de transport ('Bus', 'Minibus', 'Ferry', 'Train', 'Taxi').
    protected $fillable = [
        'operator_id',
        'nom',
        'immatriculation',
        'capacite',
        'type',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'vehicle_id');
    }
}
