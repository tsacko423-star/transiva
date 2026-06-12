<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'Payments';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - reservation_id : ID de la réservation associée.
    // - mode_paiement : Méthode de paiement ('Mobile Money', 'Carte Bancaire').
    // - montant : Somme réglée en FCFA.
    // - statut : Statut de la transaction ('Reussi', 'Echoue').
    // - reference_transaction : Numéro unique de transaction bancaire ou télécom.
    // - date_paiement : Date et heure de validation de la transaction.
    protected $fillable = [
        'reservation_id',
        'mode_paiement',
        'montant',
        'statut',
        'reference_transaction',
        'date_paiement',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
