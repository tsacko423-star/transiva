<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'Reservations';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - user_id : ID de l'utilisateur voyageur (remplace voyageur_id).
    // - trip_id : ID du voyage choisi (remplace horaire_id).
    // - date_voyage : Date programmée du départ.
    // - nb_places : Nombre de tickets achetés.
    // - sieges : Numéros de sièges choisis (nouveauté marketplace).
    // - statut : Statut actuel ('En attente', 'Payee', 'Annulee').
    protected $fillable = [
        'user_id',
        'trip_id',
        'date_voyage',
        'nb_places',
        'sieges',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'reservation_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id');
    }
}
