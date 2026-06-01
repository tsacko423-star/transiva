<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voyageur extends Model
{
    protected $table = 'Voyageurs';
    public $timestamps = false;

    protected $fillable = ['nom', 'email', 'telephone'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'voyageur_id');
    }
}
