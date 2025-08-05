<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jet extends Model
{
    protected $fillable = [
        'nom',
        'modele',
        'capacite',
        'image',
        'description',
        'prix',
        'images', // Cast le champ images en tableau
    ];

     // Cast 'images' as array
     protected $casts = [
                'images' => 'array',
     ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


}
