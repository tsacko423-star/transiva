<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'Routes';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - operator_id : ID de l'opérateur qui a défini ce trajet (anciennement global à la plateforme).
    // - nom : Nom descriptif de la ligne (ex: Ligne Directe 1).
    // - depart : Ville de départ (departure city).
    // - arrivee : Ville d'arrivée (destination/arrival city).
    // - duree_min : Durée estimée du trajet en minutes.
    protected $fillable = [
        'operator_id',
        'nom',
        'depart',
        'arrivee',
        'duree_min',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'route_id');
    }
}
