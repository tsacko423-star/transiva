<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    protected $table = 'Billets';
    public $timestamps = false;

    protected $fillable = ['reservation_id', 'code_qr', 'prix', 'date_emission'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
