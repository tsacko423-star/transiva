<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Users';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - name : Nom complet de l'utilisateur (remplace Voyageur.nom).
    // - email : Adresse email unique (remplace Voyageur.email).
    // - password : Mot de passe crypté pour l'accès aux dashboards.
    // - role : Rôle de l'utilisateur ('Admin', 'Operator', 'Traveler').
    // - telephone : Numéro de téléphone.
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
    ];

    protected $hidden = [
        'password',
    ];

    public function operator()
    {
        return $this->hasOne(Operator::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
}
