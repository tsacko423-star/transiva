<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'Operators';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - user_id : ID de l'utilisateur gestionnaire (User) de rôle 'Operator'.
    // - nom_compagnie : Nom de l'opérateur / entreprise de transport.
    // - description : Description ou profil public.
    // - logo_url : Image ou logo de la compagnie.
    // - commission_rate : Taux de commission en % (ex: 10.00).
    // - statut : Statut d'approbation administrative ('En attente', 'Valide', 'Suspendu').
    protected $fillable = [
        'user_id',
        'nom_compagnie',
        'description',
        'logo_url',
        'commission_rate',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'operator_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'operator_id');
    }

    public function trips()
    {
        return $this->hasManyThrough(Trip::class, Route::class, 'operator_id', 'route_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'operator_id');
    }
}
