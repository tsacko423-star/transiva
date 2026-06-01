<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'Reservations';
    public $timestamps = false;

    protected $fillable = ['voyageur_id', 'horaire_id', 'date_voyage', 'nb_places', 'statut'];

    public function voyageur()
    {
        return $this->belongsTo(Voyageur::class, 'voyageur_id');
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class, 'horaire_id');
    }

    public function billet()
    {
        return $this->hasOne(Billet::class, 'reservation_id');
    }
}
