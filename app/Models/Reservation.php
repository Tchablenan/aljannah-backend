<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Reservation extends Model
{
    use HasFactory, LogsActivity;

    protected static function booted()
    {
        static::created(function ($reservation) {
            if (!$reservation->reference) {
                $reservation->reference = 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT);
                $reservation->saveQuietly();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'reference',
        'first_name',
        'last_name',
        'email',
        'phone',
        'departure_location',
        'arrival_location',
        'arrival_date',
        'departure_date',
        'passengers',
        'status',
        'jet_id',
        'message',
        // Phase 1 : APIS & Luggage
        'passport_number',
        'passport_expiry',
        'date_of_birth',
        'nationality',
        'luggage_count',
        'luggage_weight_kg',
        'data_protection_consent',
        // Phase 1 : Taxes (GRA Finance)
        'base_price',
        'nhil_amount',
        'getfund_amount',
        'covid_levy_amount',
        'vat_amount',
        'total_taxes_amount',
        'total_amount_with_taxes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'passport_expiry' => 'date',
        'date_of_birth' => 'date',
        'luggage_weight_kg' => 'decimal:2',
        'data_protection_consent' => 'boolean',
        'base_price' => 'decimal:2',
        'nhil_amount' => 'decimal:2',
        'getfund_amount' => 'decimal:2',
        'covid_levy_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_taxes_amount' => 'decimal:2',
        'total_amount_with_taxes' => 'decimal:2',
    ];

    public function jet()
    {
        return $this->belongsTo(Jet::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Accesseur pour nom complet
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}