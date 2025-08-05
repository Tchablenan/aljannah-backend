<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'departure_location',
        'arrival_location',
        'plane_type',
        'arrival_date',
        'departure_date',
        'passengers',
        'status',
          'jet_id',
    ];
    public function jet()
    {
        return $this->belongsTo(Jet::class);
    }

}
