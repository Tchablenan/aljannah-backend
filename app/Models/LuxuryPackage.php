<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuxuryPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'type',
        'services_inclus',
        'prix_total',
        'prix_estime',
        'personnalisations',
        'duree',
        'nombre_personnes',
        'destination',
        'actif',
        'visible_public',
        'popularite',
        'image_principale',
        'galerie_images',
        'client_email',
        'date_expiration'
    ];

    protected $casts = [
        'services_inclus' => 'array',
        'personnalisations' => 'array',
        'galerie_images' => 'array',
        'prix_total' => 'decimal:2',
        'prix_estime' => 'decimal:2',
        'actif' => 'boolean',
        'visible_public' => 'boolean',
        'date_expiration' => 'datetime'
    ];

    /**
     * RELATIONS
     */
    public function requests()
    {
        return $this->hasMany(LuxuryPackageRequest::class);
    }

    public function services()
    {
        $serviceIds = collect($this->services_inclus)->pluck('service_id')->filter();
        return LuxuryService::whereIn('id', $serviceIds)->get();
    }

    /**
     * SCOPES
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('visible_public', true)->where('actif', true);
    }

    public function scopePredefinit($query)
    {
        return $query->where('type', 'predefinit');
    }

    public function scopePersonnalise($query)
    {
        return $query->where('type', 'personnalise');
    }

    public function scopePopulaires($query, $limit = 10)
    {
        return $query->orderBy('popularite', 'desc')->limit($limit);
    }

    /**
     * ACCESSEURS
     */
    public function getImagePrincipaleUrlAttribute()
    {
        return $this->image_principale ? asset('storage/luxury-packages/' . $this->image_principale) : null;
    }

    public function getGalerieImagesUrlsAttribute()
    {
        if (!$this->galerie_images) return [];
        
        return collect($this->galerie_images)->map(function ($image) {
            return asset('storage/luxury-packages/' . $image);
        })->toArray();
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'predefinit' ? 'Package prédéfini' : 'Package personnalisé';
    }

    public function getServicesCountAttribute()
    {
        return count($this->services_inclus ?? []);
    }

    public function getDureeDisplayAttribute()
    {
        return $this->duree ?: 'À définir';
    }

    /**
     * MÉTHODES MÉTIER
     */
    public function calculateTotalPrice()
    {
        $total = 0;
        
        if ($this->services_inclus) {
            foreach ($this->services_inclus as $serviceData) {
                $service = LuxuryService::find($serviceData['service_id']);
                if ($service) {
                    $quantite = $serviceData['quantite'] ?? 1;
                    $duration = $serviceData['duration'] ?? 1;
                    $options = $serviceData['options'] ?? [];
                    
                    $servicePrice = $service->calculatePrice($options, $duration, $quantite);
                    if ($servicePrice !== null) {
                        $total += $servicePrice;
                    }
                }
            }
        }
        
        return round($total, 2);
    }

    public function addService($serviceId, $quantite = 1, $duration = 1, $options = [])
    {
        $services = $this->services_inclus ?? [];
        
        $services[] = [
            'service_id' => $serviceId,
            'quantite' => $quantite,
            'duration' => $duration,
            'options' => $options,
            'added_at' => now()->toISOString()
        ];
        
        $this->services_inclus = $services;
        
        // Recalculer le prix si c'est un package prédéfini
        if ($this->type === 'predefinit') {
            $this->prix_total = $this->calculateTotalPrice();
        } else {
            $this->prix_estime = $this->calculateTotalPrice();
        }
        
        $this->save();
    }

    public function removeService($serviceId)
    {
        $services = collect($this->services_inclus ?? [])
            ->reject(function ($service) use ($serviceId) {
                return $service['service_id'] == $serviceId;
            })
            ->values()
            ->toArray();
        
        $this->services_inclus = $services;
        
        // Recalculer le prix
        if ($this->type === 'predefinit') {
            $this->prix_total = $this->calculateTotalPrice();
        } else {
            $this->prix_estime = $this->calculateTotalPrice();
        }
        
        $this->save();
    }

    public function getServiceDetails($serviceId)
    {
        return collect($this->services_inclus ?? [])
            ->firstWhere('service_id', $serviceId);
    }

    public function duplicateAsPersonnalise($clientEmail = null)
    {
        $copy = $this->replicate();
        $copy->type = 'personnalise';
        $copy->client_email = $clientEmail;
        $copy->visible_public = false;
        $copy->date_expiration = now()->addDays(30); // Valide 30 jours
        $copy->prix_estime = $copy->prix_total;
        $copy->prix_total = null;
        $copy->nom = $copy->nom . ' (Personnalisé)';
        $copy->save();
        
        return $copy;
    }

    public function incrementPopularite()
    {
        $this->increment('popularite');
    }

    public function isExpired()
    {
        return $this->date_expiration && $this->date_expiration < now();
    }

    public function canBeModified()
    {
        return $this->type === 'personnalise' && !$this->isExpired();
    }

    // Générer un package personnalisé à partir de services sélectionnés
    public static function createPersonnaliseFromServices($services, $clientData = [])
    {
        $servicesData = [];
        $totalEstime = 0;
        
        foreach ($services as $serviceConfig) {
            $service = LuxuryService::find($serviceConfig['service_id']);
            if ($service) {
                $servicesData[] = [
                    'service_id' => $service->id,
                    'quantite' => $serviceConfig['quantite'] ?? 1,
                    'duration' => $serviceConfig['duration'] ?? 1,
                    'options' => $serviceConfig['options'] ?? []
                ];
                
                $servicePrice = $service->calculatePrice(
                    $serviceConfig['options'] ?? [],
                    $serviceConfig['duration'] ?? 1,
                    $serviceConfig['quantite'] ?? 1
                );
                
                if ($servicePrice !== null) {
                    $totalEstime += $servicePrice;
                }
            }
        }
        
        return self::create([
            'nom' => $clientData['nom'] ?? 'Package Personnalisé',
            'description' => $clientData['description'] ?? 'Package créé selon vos besoins',
            'type' => 'personnalise',
            'services_inclus' => $servicesData,
            'prix_estime' => $totalEstime,
            'nombre_personnes' => $clientData['nombre_personnes'] ?? 1,
            'destination' => $clientData['destination'] ?? null,
            'client_email' => $clientData['client_email'] ?? null,
            'date_expiration' => now()->addDays(30),
            'actif' => true,
            'visible_public' => false
        ]);
    }
}