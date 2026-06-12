<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Tickets';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - reservation_id : ID de la réservation associée (foreign key).
    // - code_qr : Code ou URL encodé dans le QR code généré.
    // - code_reservation : Code court de confirmation unique (ex: TRV-XYZ-1234).
    // - prix_total : Prix payé total pour la réservation (nb_places * prix).
    // - date_emission : Date et heure de génération du ticket.
    protected $fillable = [
        'reservation_id',
        'code_qr',
        'code_reservation',
        'prix_total',
        'date_emission',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
