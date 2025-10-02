<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuxuryPackageRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'luxury_package_id',
        'client_prenom',
        'client_nom',
        'client_email',
        'client_telephone',
        'preferences_client',
        'titre_demande',
        'description_demande',
        'services_souhaites',
        'personnalisations_demandees',
        'date_debut_souhaitee',
        'date_fin_souhaitee',
        'destination_principale',
        'destinations_multiples',
        'nombre_personnes',
        'budget_estime',
        'prix_propose',
        'prix_final',
        'statut',
        'priorite',
        'concierge_assigne',
        'notes_internes',
        'date_confirmation',
        'date_expiration_devis',
        'reference'
    ];

    protected $casts = [
        'preferences_client' => 'array',
        'services_souhaites' => 'array',
        'personnalisations_demandees' => 'array',
        'destinations_multiples' => 'array',
        'date_debut_souhaitee' => 'datetime',
        'date_fin_souhaitee' => 'datetime',
        'date_confirmation' => 'datetime',
        'date_expiration_devis' => 'datetime',
        'budget_estime' => 'decimal:2',
        'prix_propose' => 'decimal:2',
        'prix_final' => 'decimal:2'
    ];

    /**
     * RELATIONS
     */
    public function package()
    {
        return $this->belongsTo(LuxuryPackage::class, 'luxury_package_id');
    }

    /**
     * SCOPES
     */
    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', ['nouvelle', 'en_analyse', 'devis_envoye', 'en_negociation']);
    }

    public function scopeConfirmees($query)
    {
        return $query->whereIn('statut', ['confirme', 'en_preparation', 'en_cours']);
    }

    public function scopeTerminees($query)
    {
        return $query->whereIn('statut', ['termine', 'annule']);
    }

    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeByPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    public function scopeUrgentes($query)
    {
        return $query->whereIn('priorite', ['urgente', 'vip']);
    }

    public function scopeParPriorite($query)
    {
        return $query->orderByRaw("
            CASE priorite 
            WHEN 'vip' THEN 1 
            WHEN 'urgente' THEN 2 
            WHEN 'normale' THEN 3 
            END
        ");
    }

    /**
     * ACCESSEURS
     */
    public function getClientNomCompletAttribute()
    {
        return $this->client_prenom . ' ' . $this->client_nom;
    }

    public function getStatutDisplayAttribute()
    {
        $statuts = [
            'nouvelle' => 'Nouvelle demande',
            'en_analyse' => 'En cours d\'analyse',
            'devis_envoye' => 'Devis envoyé',
            'en_negociation' => 'En négociation',
            'confirme' => 'Confirmé',
            'en_preparation' => 'En préparation',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getPrioriteDisplayAttribute()
    {
        $priorites = [
            'normale' => 'Normale',
            'urgente' => 'Urgente',
            'vip' => 'VIP'
        ];

        return $priorites[$this->priorite] ?? $this->priorite;
    }

    public function getStatutColorAttribute()
    {
        $colors = [
            'nouvelle' => 'primary',
            'en_analyse' => 'info',
            'devis_envoye' => 'warning',
            'en_negociation' => 'warning',
            'confirme' => 'success',
            'en_preparation' => 'info',
            'en_cours' => 'primary',
            'termine' => 'success',
            'annule' => 'danger'
        ];

        return $colors[$this->statut] ?? 'secondary';
    }

    public function getPrioriteColorAttribute()
    {
        $colors = [
            'normale' => 'success',
            'urgente' => 'warning',
            'vip' => 'danger'
        ];

        return $colors[$this->priorite] ?? 'secondary';
    }

    public function getDureeSejourAttribute()
    {
        if (!$this->date_fin_souhaitee) return null;
        
        return $this->date_debut_souhaitee->diffInDays($this->date_fin_souhaitee) + 1;
    }

    public function getDureeSejourDisplayAttribute()
    {
        $duree = $this->getDureeSejourAttribute();
        
        if (!$duree) return 'Non définie';
        
        if ($duree == 1) return '1 jour';
        
        return $duree . ' jours';
    }

    /**
     * MÉTHODES MÉTIER
     */
    public static function generateReference()
    {
        $lastRequest = self::latest()->first();
        $nextNumber = $lastRequest ? (intval(substr($lastRequest->reference, -6)) + 1) : 1;
        
        return 'LUX-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->statut;
        $this->statut = $newStatus;
        
        // Actions automatiques selon le statut
        switch ($newStatus) {
            case 'confirme':
                if ($oldStatus !== 'confirme') {
                    $this->date_confirmation = now();
                }
                break;
            case 'devis_envoye':
                if (!$this->date_expiration_devis) {
                    $this->date_expiration_devis = now()->addDays(15); // Devis valide 15 jours
                }
                break;
            case 'termine':
                if (!$this->prix_final && $this->prix_propose) {
                    $this->prix_final = $this->prix_propose;
                }
                break;
        }
        
        if ($notes) {
            $this->addNote($notes);
        }
        
        $this->save();
    }

    public function addNote($note)
    {
        $currentNotes = $this->notes_internes ? $this->notes_internes . "\n\n" : '';
        $timestamp = now()->format('d/m/Y H:i');
        $this->notes_internes = $currentNotes . "[{$timestamp}] {$note}";
    }

    public function assignConcierge($conciergeName, $notes = null)
    {
        $this->concierge_assigne = $conciergeName;
        
        if ($this->statut === 'nouvelle') {
            $this->statut = 'en_analyse';
        }
        
        if ($notes) {
            $this->addNote($notes);
        }
        
        $this->save();
    }

    public function calculateEstimatedPrice()
    {
        $total = 0;
        
        if ($this->services_souhaites) {
            foreach ($this->services_souhaites as $serviceData) {
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
        $services = $this->services_souhaites ?? [];
        
        // Vérifier si le service existe déjà
        $existingIndex = null;
        foreach ($services as $index => $service) {
            if ($service['service_id'] == $serviceId) {
                $existingIndex = $index;
                break;
            }
        }
        
        if ($existingIndex !== null) {
            // Mettre à jour le service existant
            $services[$existingIndex] = [
                'service_id' => $serviceId,
                'quantite' => $quantite,
                'duration' => $duration,
                'options' => $options,
                'updated_at' => now()->toISOString()
            ];
        } else {
            // Ajouter nouveau service
            $services[] = [
                'service_id' => $serviceId,
                'quantite' => $quantite,
                'duration' => $duration,
                'options' => $options,
                'added_at' => now()->toISOString()
            ];
        }
        
        $this->services_souhaites = $services;
        
        // Recalculer le prix estimé
        $this->prix_propose = $this->calculateEstimatedPrice();
        
        $this->save();
    }

    public function removeService($serviceId)
    {
        $services = collect($this->services_souhaites ?? [])
            ->reject(function ($service) use ($serviceId) {
                return $service['service_id'] == $serviceId;
            })
            ->values()
            ->toArray();
        
        $this->services_souhaites = $services;
        
        // Recalculer le prix estimé
        $this->prix_propose = $this->calculateEstimatedPrice();
        
        $this->save();
    }

    public function updateService($serviceId, $quantite, $duration = 1, $options = [])
    {
        $services = $this->services_souhaites ?? [];
        
        foreach ($services as &$service) {
            if ($service['service_id'] == $serviceId) {
                $service['quantite'] = $quantite;
                $service['duration'] = $duration;
                $service['options'] = $options;
                $service['updated_at'] = now()->toISOString();
                break;
            }
        }
        
        $this->services_souhaites = $services;
        $this->prix_propose = $this->calculateEstimatedPrice();
        $this->save();
    }

    public function canBeModified()
    {
        return in_array($this->statut, ['nouvelle', 'en_analyse', 'en_negociation']);
    }

    public function canBeCancelled()
    {
        return !in_array($this->statut, ['termine', 'annule']);
    }

    public function canBeConfirmed()
    {
        return in_array($this->statut, ['devis_envoye', 'en_negociation']) && !$this->isDevisExpired();
    }

    public function isDevisExpired()
    {
        return $this->date_expiration_devis && $this->date_expiration_devis < now();
    }

    public function getServicesDetails()
    {
        if (!$this->services_souhaites) return collect();
        
        $services = collect();
        foreach ($this->services_souhaites as $serviceData) {
            $service = LuxuryService::find($serviceData['service_id']);
            if ($service) {
                $prixUnitaire = $service->calculatePrice(
                    $serviceData['options'] ?? [],
                    $serviceData['duration'] ?? 1,
                    1
                );
                
                $prixTotal = $service->calculatePrice(
                    $serviceData['options'] ?? [],
                    $serviceData['duration'] ?? 1,
                    $serviceData['quantite'] ?? 1
                );
                
                $services->push([
                    'service' => $service,
                    'quantite' => $serviceData['quantite'] ?? 1,
                    'duration' => $serviceData['duration'] ?? 1,
                    'options' => $serviceData['options'] ?? [],
                    'prix_unitaire' => $prixUnitaire,
                    'prix_total' => $prixTotal,
                    'sur_devis' => $service->type_prix === 'sur_devis'
                ]);
            }
        }
        
        return $services;
    }

    public function getServicesCount()
    {
        return count($this->services_souhaites ?? []);
    }

    public function getTotalServicesQuantity()
    {
        $total = 0;
        foreach ($this->services_souhaites ?? [] as $service) {
            $total += $service['quantite'] ?? 1;
        }
        return $total;
    }

    public function hasServiceSurDevis()
    {
        foreach ($this->services_souhaites ?? [] as $serviceData) {
            $service = LuxuryService::find($serviceData['service_id']);
            if ($service && $service->type_prix === 'sur_devis') {
                return true;
            }
        }
        return false;
    }

    // Créer un package personnalisé à partir de cette demande
    public function createPersonalizedPackage()
    {
        return LuxuryPackage::create([
            'nom' => $this->titre_demande,
            'description' => $this->description_demande,
            'type' => 'personnalise',
            'services_inclus' => $this->services_souhaites,
            'prix_estime' => $this->calculateEstimatedPrice(),
            'personnalisations' => $this->personnalisations_demandees,
            'nombre_personnes' => $this->nombre_personnes,
            'destination' => $this->destination_principale,
            'duree' => $this->duree_sejour_display,
            'client_email' => $this->client_email,
            'date_expiration' => $this->date_expiration_devis,
            'actif' => true,
            'visible_public' => false
        ]);
    }

    // Dupliquer cette demande pour modifications
    public function duplicate()
    {
        $copy = $this->replicate();
        $copy->reference = self::generateReference();
        $copy->statut = 'nouvelle';
        $copy->date_confirmation = null;
        $copy->date_expiration_devis = null;
        $copy->notes_internes = null;
        $copy->concierge_assigne = null;
        $copy->titre_demande = $copy->titre_demande . ' (Copie)';
        $copy->save();
        
        return $copy;
    }

    // Conversion en format d'export (pour PDF, emails, etc.)
    public function toExportFormat()
    {
        return [
            'reference' => $this->reference,
            'client' => $this->client_nom_complet,
            'email' => $this->client_email,
            'telephone' => $this->client_telephone,
            'titre' => $this->titre_demande,
            'description' => $this->description_demande,
            'destination' => $this->destination_principale,
            'dates' => [
                'debut' => $this->date_debut_souhaitee?->format('d/m/Y'),
                'fin' => $this->date_fin_souhaitee?->format('d/m/Y'),
                'duree' => $this->duree_sejour_display
            ],
            'personnes' => $this->nombre_personnes,
            'services' => $this->getServicesDetails()->map(function($item) {
                return [
                    'nom' => $item['service']->nom,
                    'categorie' => $item['service']->categorie_display,
                    'quantite' => $item['quantite'],
                    'duration' => $item['duration'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'prix_total' => $item['prix_total'],
                    'sur_devis' => $item['sur_devis']
                ];
            })->toArray(),
            'prix' => [
                'estime' => $this->calculateEstimatedPrice(),
                'propose' => $this->prix_propose,
                'final' => $this->prix_final
            ],
            'statut' => $this->statut_display,
            'priorite' => $this->priorite_display,
            'concierge' => $this->concierge_assigne,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i')
        ];
    }
}