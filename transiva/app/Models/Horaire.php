<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    protected $table = 'Horaires';
    public $timestamps = false;

    protected $fillable = ['ligne_id', 'heure_depart', 'heure_arrivee', 'jours'];

    public function ligne()
    {
        return $this->belongsTo(Ligne::class, 'ligne_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'horaire_id');
    }
}
