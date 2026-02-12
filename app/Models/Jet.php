<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Jet extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'nom',
        'modele',
        'capacite',
        'image',
        'description',
        'prix',
        'images',
        'disponible',
        'localisation',
        'autonomie_km',
        'categorie'
    ];

    protected $casts = [
        'images' => 'array',
        'disponible' => 'boolean',
        'prix' => 'decimal:2'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Scopes
    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    // Accesseurs pour les images
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getImagesUrlsAttribute()
    {
        if (!$this->images)
            return [];

        return collect($this->images)->map(function ($image) {
            return asset('storage/' . $image);
        })->toArray();
    }

    public function getImagesArrayAttribute()
    {
        if (is_array($this->images)) {
            return $this->images;
        }

        if (is_string($this->images)) {
            return json_decode($this->images, true) ?: [];
        }

        return [];
    }

    // Dans App\Models\Jet.php, ajoutez cette méthode

    public function getAllImages()
    {
        $allImages = [];

        // Ajouter l'image principale si elle existe
        if ($this->image) {
            $allImages[] = [
                'url' => $this->image_url,
                'is_primary' => true
            ];
        }

        // Ajouter les images supplémentaires
        if ($this->images) {
            foreach ($this->images_urls as $url) {
                $allImages[] = [
                    'url' => $url,
                    'is_primary' => false
                ];
            }
        }

        return $allImages;
    }
    // Méthodes métier
    public function isAvailableForDates($departure_date, $arrival_date)
    {
        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($departure_date, $arrival_date) {
            $query->whereBetween('departure_date', [$departure_date, $arrival_date])
                ->orWhereBetween('arrival_date', [$departure_date, $arrival_date])
                ->orWhere(function ($q) use ($departure_date, $arrival_date) {
                $q->where('departure_date', '<=', $departure_date)
                    ->where('arrival_date', '>=', $arrival_date);
            }
            );
        })->exists();
    }
}