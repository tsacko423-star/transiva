<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligne extends Model
{
    protected $table = 'Lignes';
    public $timestamps = false;

    protected $fillable = ['nom', 'depart', 'arrivee', 'duree_min'];

    public function horaires()
    {
        return $this->hasMany(Horaire::class, 'ligne_id');
    }
}
