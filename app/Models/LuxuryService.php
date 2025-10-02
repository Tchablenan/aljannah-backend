<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuxuryService extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
        'description',
        'prix_base',
        'type_prix',
        'options_disponibles',
        'fournisseur',
        'contact_fournisseur',
        'actif',
        'image'
    ];

    protected $casts = [
        'options_disponibles' => 'array',
        'actif' => 'boolean',
        'prix_base' => 'decimal:2'
    ];

    /**
     * RELATIONS
     */
    public function packages()
    {
        return $this->belongsToMany(LuxuryPackage::class, 'luxury_package_services', 'service_id', 'package_id')
                    ->withPivot('quantite', 'options_selectionnees');
    }

public function getRequestsCountAttribute()
{
    // Compter les demandes qui contiennent ce service dans services_souhaites
    return LuxuryPackageRequest::whereJsonContains('services_souhaites', function($query) {
        $query->where('service_id', $this->id);
    })->count();
}

    /**
     * SCOPES
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

public function scopePopulaires($query, $limit = 10)
{
    return $query->where('actif', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit);
}

    /**
     * ACCESSEURS
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getCategorieDisplayAttribute()
    {
        return self::getCategories()[$this->categorie] ?? $this->categorie;
    }

    public function getTypePrixDisplayAttribute()
    {
        $types = [
            'fixe' => 'Prix fixe',
            'heure' => 'Par heure',
            'jour' => 'Par jour',
            'forfait' => 'Forfait',
            'sur_devis' => 'Sur devis'
        ];

        return $types[$this->type_prix] ?? $this->type_prix;
    }

    /**
     * MÉTHODES MÉTIER
     */
    public function calculatePrice($options = [], $duration = 1, $quantite = 1)
    {
        if ($this->type_prix === 'sur_devis') {
            return null; // Nécessite un devis personnalisé
        }

        $basePrice = $this->prix_base ?? 0;

        // Calcul selon le type de prix
        switch ($this->type_prix) {
            case 'fixe':
            case 'forfait':
                $unitPrice = $basePrice;
                break;
            case 'heure':
            case 'jour':
                $unitPrice = $basePrice * $duration;
                break;
            default:
                $unitPrice = $basePrice;
        }

        // Ajouter les suppléments pour options
        if ($this->options_disponibles && !empty($options)) {
            foreach ($this->options_disponibles as $option) {
                $optionKey = $option['key'] ?? '';
                if (in_array($optionKey, $options) && isset($option['prix_supplement'])) {
                    $unitPrice += $option['prix_supplement'];
                }
            }
        }

        return round($unitPrice * $quantite, 2);
    }

    public function getAvailableOptions()
    {
        return $this->options_disponibles ?? [];
    }

    public function hasOption($optionKey)
    {
        $options = $this->getAvailableOptions();
        return collect($options)->contains('key', $optionKey);
    }

    /**
     * CONSTANTES ET HELPERS
     */
    public static function getCategories()
    {
        return [
            'transport_luxe' => 'Transport de Luxe',
            'hebergement' => 'Hébergement Premium',
            'restauration' => 'Restauration Gastronomique',
            'divertissement' => 'Divertissement & Événements',
            'shopping' => 'Shopping & Mode',
            'bien_etre' => 'Bien-être & Spa',
            'business' => 'Services Business',
            'famille' => 'Services Famille',
            'securite' => 'Sécurité & Protection',
            'autre' => 'Autres Services'
        ];
    }

    public static function getTypesPrix()
    {
        return [
            'fixe' => 'Prix fixe',
            'heure' => 'Par heure',
            'jour' => 'Par jour',
            'forfait' => 'Forfait',
            'sur_devis' => 'Sur devis'
        ];
    }

    // Suggestions de services complémentaires
    public function getSuggestedServices($limit = 5)
    {
        return self::where('categorie', '!=', $this->categorie)
                   ->where('actif', true)
                   ->where('id', '!=', $this->id)
                   ->inRandomOrder()
                   ->limit($limit)
                   ->get();
    }
}
