<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'Reviews';
    public $timestamps = false;

    // Notes de changement (Variables en Anglais) :
    // - user_id : ID de l'utilisateur (Traveler) auteur de la note.
    // - operator_id : ID de la compagnie (Operator) évaluée.
    // - note : Note sur 5 étoiles (1 à 5).
    // - commentaire : Texte ou avis rédigé.
    // - date_avis : Date de publication de l'évaluation.
    protected $fillable = [
        'user_id',
        'operator_id',
        'note',
        'commentaire',
        'date_avis',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
}
