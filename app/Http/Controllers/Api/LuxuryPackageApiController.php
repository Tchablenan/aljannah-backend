<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LuxuryPackage;
use App\Models\LuxuryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LuxuryPackageApiController extends Controller
{
    /**
     * API - Liste des packages (pour React admin)
     */
    public function index(Request $request)
    {
        $query = LuxuryPackage::withCount('requests');

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Tri
        $sortBy = $request->get('sort', 'popularite');
        switch ($sortBy) {
            case 'prix_asc':
                $query->orderBy('prix_total', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix_total', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->populaires();
        }

        $perPage = $request->get('per_page', 9);
        $packages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $packages->items(),
            'pagination' => [
                'current_page' => $packages->currentPage(),
                'last_page' => $packages->lastPage(),
                'per_page' => $packages->perPage(),
                'total' => $packages->total()
            ]
        ]);
    }

    /**
     * API PUBLIC - Détails d'un package
     */
    public function publicShow($id)
    {
        try {
            $package = LuxuryPackage::visible()->findOrFail($id);
            
            // Incrémenter la popularité
            $package->incrementPopularite();
            
            $services = $package->services();
            $packagesAssocies = LuxuryPackage::where('destination', $package->destination)
                                            ->where('id', '!=', $package->id)
                                            ->visible()
                                            ->limit(3)
                                            ->get();

            return response()->json([
                'success' => true,
                'data' => $package,
                'services' => $services,
                'packages_associes' => $packagesAssocies
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Package non disponible'
            ], 404);
        }
    }

    /**
     * API PUBLIC - Packages par destination
     */
    public function publicByDestination($destination)
    {
        $packages = LuxuryPackage::visible()
                                ->where('destination', 'like', "%{$destination}%")
                                ->orderBy('popularite', 'desc')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'destination' => $destination,
            'total' => $packages->count()
        ]);
    }

    /**
     * API PUBLIC - Packages par type
     */
    public function publicByType($type)
    {
        if (!in_array($type, ['predefinit', 'personnalise'])) {
            return response()->json([
                'success' => false,
                'message' => 'Type de package invalide'
            ], 400);
        }

        $packages = LuxuryPackage::visible()
                                ->where('type', $type)
                                ->orderBy('popularite', 'desc')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'type' => $type,
            'type_label' => $type === 'predefinit' ? 'Prédéfini' : 'Personnalisé'
        ]);
    }

    /**
     * API PUBLIC - Packages populaires
     */
    public function publicPopulaires(Request $request)
    {
        $limit = $request->get('limit', 8);
        $packages = LuxuryPackage::visible()
                                ->populaires($limit)
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'limit' => $limit
        ]);
    }

    /**
     * API PUBLIC - Estimation publique
     */
    public function publicEstimate(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:luxury_services,id',
            'services.*.quantite' => 'required|integer|min:1',
            'services.*.duration' => 'nullable|numeric|min:0.5',
            'services.*.options' => 'nullable|array'
        ]);

        try {
            $total = 0;
            $servicesCalculables = 0;
            $servicesSurDevis = 0;

            foreach ($validated['services'] as $serviceConfig) {
                $service = LuxuryService::find($serviceConfig['service_id']);
                if ($service && $service->actif) {
                    $prix = $service->calculatePrice(
                        $serviceConfig['options'] ?? [],
                        $serviceConfig['duration'] ?? 1,
                        $serviceConfig['quantite']
                    );
                    
                    if ($prix !== null) {
                        $total += $prix;
                        $servicesCalculables++;
                    } else {
                        $servicesSurDevis++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'estimation' => $total,
                'estimation_formatted' => number_format($total, 2, ',', ' ') . ' €',
                'services_calculables' => $servicesCalculables,
                'services_sur_devis' => $servicesSurDevis,
                'note' => $servicesSurDevis > 0 ? 'Estimation partielle - certains services nécessitent un devis' : 'Estimation complète'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'estimation'
            ], 500);
        }
    }

    /**
     * API PUBLIC - Demande de package personnalisé
     */
    public function publicRequestCustom(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:luxury_services,id',
            'services.*.quantite' => 'required|integer|min:1',
            'services.*.duration' => 'nullable|numeric|min:0.5',
            'services.*.options' => 'nullable|array',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'destination' => 'nullable|string|max:255',
            'client_email' => 'required|email',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'date_souhaitee' => 'nullable|date|after:today',
            'budget_approximatif' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Créer le package personnalisé
            $package = LuxuryPackage::createPersonnaliseFromServices(
                $validated['services'],
                [
                    'nom' => $validated['nom'],
                    'description' => $validated['description'],
                    'nombre_personnes' => $validated['nombre_personnes'],
                    'destination' => $validated['destination'],
                    'client_email' => $validated['client_email']
                ]
            );

            // Créer une demande associée (si vous avez le modèle LuxuryPackageRequest)
            // $request = LuxuryPackageRequest::create([
            //     'luxury_package_id' => $package->id,
            //     'client_nom' => $validated['client_nom'],
            //     'client_email' => $validated['client_email'],
            //     'client_telephone' => $validated['client_telephone'],
            //     'date_souhaitee' => $validated['date_souhaitee'],
            //     'budget_approximatif' => $validated['budget_approximatif'],
            //     'status' => 'pending'
            // ]);

            DB::commit();

            // Ici vous pourriez envoyer des emails de notification
            // - Email de confirmation au client
            // - Email de nouvelle demande à l'admin

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de package personnalisé a été créée avec succès ! Nous vous contacterons sous peu.',
                'package_id' => $package->id,
                'estimation' => $package->prix_estime,
                'estimation_formatted' => number_format($package->prix_estime, 2, ',', ' ') . ' €'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de votre demande. Veuillez réessayer.',
                'error' => $e->getMessage()
            ], 500);
        }
}

    /**
     * API - Créer un package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|in:predefinit,personnalise',
            'services_inclus' => 'required|array|min:1',
            'services_inclus.*.service_id' => 'required|exists:luxury_services,id',
            'services_inclus.*.quantite' => 'required|integer|min:1',
            'services_inclus.*.duration' => 'nullable|numeric|min:0.5',
            'services_inclus.*.options' => 'nullable|array',
            'duree' => 'nullable|string|max:100',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'destination' => 'nullable|string|max:255',
            'actif' => 'boolean',
            'visible_public' => 'boolean',
            'client_email' => 'nullable|email'
        ]);

        try {
            DB::beginTransaction();

            $package = LuxuryPackage::create($validated);

            // Calculer le prix automatiquement
            if ($package->type === 'predefinit') {
                $package->prix_total = $package->calculateTotalPrice();
            } else {
                $package->prix_estime = $package->calculateTotalPrice();
            }
            $package->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Package créé avec succès',
                'data' => $package->load('requests')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du package',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Afficher un package
     */
    public function show($id)
    {
        try {
            $package = LuxuryPackage::with('requests')->findOrFail($id);
            $services = $package->services();
            $packagesAssocies = LuxuryPackage::where('destination', $package->destination)
                                            ->where('id', '!=', $package->id)
                                            ->visible()
                                            ->limit(3)
                                            ->get();

            return response()->json([
                'success' => true,
                'data' => $package,
                'services' => $services,
                'packages_associes' => $packagesAssocies
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Package non trouvé'
            ], 404);
        }
    }

    /**
     * API - Mettre à jour un package
     */
    public function update(Request $request, $id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'type' => 'required|in:predefinit,personnalise',
                'services_inclus' => 'required|array|min:1',
                'services_inclus.*.service_id' => 'required|exists:luxury_services,id',
                'services_inclus.*.quantite' => 'required|integer|min:1',
                'services_inclus.*.duration' => 'nullable|numeric|min:0.5',
                'services_inclus.*.options' => 'nullable|array',
                'duree' => 'nullable|string|max:100',
                'nombre_personnes' => 'required|integer|min:1|max:50',
                'destination' => 'nullable|string|max:255',
                'actif' => 'boolean',
                'visible_public' => 'boolean'
            ]);

            DB::beginTransaction();

            $package->update($validated);

            // Recalculer le prix
            if ($package->type === 'predefinit') {
                $package->prix_total = $package->calculateTotalPrice();
            } else {
                $package->prix_estime = $package->calculateTotalPrice();
            }
            $package->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Package mis à jour avec succès',
                'data' => $package->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Supprimer un package
     */
    public function destroy($id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);

            // Vérifier les contraintes
            if ($package->requests()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce package car il a des demandes associées.'
                ], 422);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Packages par type
     */
    public function getByType($type)
    {
        if (!in_array($type, ['predefinit', 'personnalise'])) {
            return response()->json([
                'success' => false,
                'message' => 'Type de package invalide'
            ], 400);
        }

        $packages = LuxuryPackage::where('type', $type)
                                ->withCount('requests')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'type' => $type,
            'type_label' => $type === 'predefinit' ? 'Prédéfini' : 'Personnalisé'
        ]);
    }

    /**
     * API - Packages par destination
     */
    public function getByDestination($destination)
    {
        $packages = LuxuryPackage::where('destination', 'like', "%{$destination}%")
                                ->visible()
                                ->withCount('requests')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'destination' => $destination,
            'total' => $packages->count()
        ]);
    }

    /**
     * API - Recherche avancée de packages
     */
    public function search(Request $request)
    {
        $query = LuxuryPackage::visible();

        // Recherche textuelle
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Filtres spécifiques
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->filled('personnes_min')) {
            $query->where('nombre_personnes', '>=', $request->personnes_min);
        }

        if ($request->filled('personnes_max')) {
            $query->where('nombre_personnes', '<=', $request->personnes_max);
        }

        if ($request->filled('budget_min')) {
            $query->where('prix_total', '>=', $request->budget_min);
        }

        if ($request->filled('budget_max')) {
            $query->where('prix_total', '<=', $request->budget_max);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Tri
        $sortBy = $request->get('sort', 'popularite');
        switch ($sortBy) {
            case 'prix_asc':
                $query->orderBy('prix_total', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix_total', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->populaires();
        }

        $limit = $request->get('limit', 20);
        $packages = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'total' => $packages->count(),
            'filters_applied' => $request->only(['q', 'destination', 'personnes_min', 'personnes_max', 'budget_min', 'budget_max', 'type'])
        ]);
    }

    /**
     * API - Packages populaires
     */
    public function getPopulaires(Request $request)
    {
        $limit = $request->get('limit', 10);
        $packages = LuxuryPackage::visible()
                                ->populaires($limit)
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'limit' => $limit
        ]);
    }

    /**
     * API - Packages visibles au public
     */
    public function getVisibles(Request $request)
    {
        $query = LuxuryPackage::visible();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $packages = $query->get();

        return response()->json([
            'success' => true,
            'data' => $packages,
            'total' => $packages->count()
        ]);
    }

    /**
     * API - Ajouter un service à un package
     */
    public function addService(Request $request, $id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);

            $validated = $request->validate([
                'service_id' => 'required|exists:luxury_services,id',
                'quantite' => 'required|integer|min:1',
                'duration' => 'nullable|numeric|min:0.5',
                'options' => 'nullable|array'
            ]);

            // Vérifier que le service est actif
            $service = LuxuryService::findOrFail($validated['service_id']);
            if (!$service->actif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce service n\'est pas disponible'
                ], 422);
            }

            $package->addService(
                $validated['service_id'],
                $validated['quantite'],
                $validated['duration'] ?? 1,
                $validated['options'] ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Service ajouté au package avec succès',
                'data' => $package->fresh(),
                'nouveau_prix' => $package->type === 'predefinit' ? $package->prix_total : $package->prix_estime
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Retirer un service d'un package
     */
    public function removeService(Request $request, $id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);

            $validated = $request->validate([
                'service_id' => 'required|exists:luxury_services,id'
            ]);

            // Vérifier qu'il restera au moins un service
            $servicesInclus = $package->services_inclus ?? [];
            $servicesRestants = collect($servicesInclus)->where('service_id', '!=', $validated['service_id']);
            
            if ($servicesRestants->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de retirer ce service : un package doit contenir au moins un service'
                ], 422);
            }

            $package->removeService($validated['service_id']);

            return response()->json([
                'success' => true,
                'message' => 'Service retiré du package avec succès',
                'data' => $package->fresh(),
                'nouveau_prix' => $package->type === 'predefinit' ? $package->prix_total : $package->prix_estime
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Obtenir les services d'un package avec détails
     */
    public function getServices($id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);
            $services = $package->services();
            
            // Enrichir avec les détails de configuration du package
            $servicesEnrichis = $services->map(function($service) use ($package) {
                $config = $package->getServiceDetails($service->id);
                return [
                    'service' => $service,
                    'configuration' => $config,
                    'prix_calcule' => $service->calculatePrice(
                        $config['options'] ?? [],
                        $config['duration'] ?? 1,
                        $config['quantite'] ?? 1
                    )
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $servicesEnrichis,
                'total_services' => $services->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Package non trouvé'
            ], 404);
        }
    }

    /**
     * API - Calculer le prix total d'un package
     */
    public function calculatePrice($id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);
            $prixCalcule = $package->calculateTotalPrice();

            return response()->json([
                'success' => true,
                'prix_total' => $prixCalcule,
                'prix_formatted' => number_format($prixCalcule, 2, ',', ' ') . ' €',
                'services_count' => $package->services_count,
                'type' => $package->type,
                'details' => [
                    'prix_actuel' => $package->type === 'predefinit' ? $package->prix_total : $package->prix_estime,
                    'recalcule' => $prixCalcule,
                    'difference' => $prixCalcule - ($package->type === 'predefinit' ? $package->prix_total : $package->prix_estime)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du prix',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Estimer un package personnalisé
     */
    public function estimateCustomPackage(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:luxury_services,id',
            'services.*.quantite' => 'required|integer|min:1',
            'services.*.duration' => 'nullable|numeric|min:0.5',
            'services.*.options' => 'nullable|array'
        ]);

        try {
            $total = 0;
            $servicesDetails = [];
            $servicesNonCalculables = [];

            foreach ($validated['services'] as $serviceConfig) {
                $service = LuxuryService::find($serviceConfig['service_id']);
                if ($service && $service->actif) {
                    $prix = $service->calculatePrice(
                        $serviceConfig['options'] ?? [],
                        $serviceConfig['duration'] ?? 1,
                        $serviceConfig['quantite']
                    );

                    if ($prix !== null) {
                        $total += $prix;
                        $servicesDetails[] = [
                            'service_id' => $service->id,
                            'service_nom' => $service->nom,
                            'quantite' => $serviceConfig['quantite'],
                            'duration' => $serviceConfig['duration'] ?? 1,
                            'prix_unitaire' => $prix / $serviceConfig['quantite'],
                            'prix_total' => $prix
                        ];
                    } else {
                        $servicesNonCalculables[] = [
                            'service_id' => $service->id,
                            'service_nom' => $service->nom,
                            'raison' => 'Service sur devis'
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'estimation_totale' => $total,
                'estimation_formatted' => number_format($total, 2, ',', ' ') . ' €',
                'services_calcules' => $servicesDetails,
                'services_sur_devis' => $servicesNonCalculables,
                'note' => count($servicesNonCalculables) > 0 ? 'Certains services nécessitent un devis personnalisé' : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'estimation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Dupliquer un package
     */
    public function duplicate(Request $request, $id)
    {
        try {
            $package = LuxuryPackage::findOrFail($id);
            
            $validated = $request->validate([
                'client_email' => 'nullable|email',
                'nouveau_nom' => 'nullable|string|max:255'
            ]);
            
            $copy = $package->duplicateAsPersonnalise($validated['client_email'] ?? null);
            
            if (isset($validated['nouveau_nom'])) {
                $copy->nom = $validated['nouveau_nom'];
                $copy->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Package dupliqué avec succès',
                'data' => $copy,
                'original_id' => $package->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Créer un package personnalisé
     */
    public function createPersonalized(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:luxury_services,id',
            'services.*.quantite' => 'required|integer|min:1',
            'services.*.duration' => 'nullable|numeric|min:0.5',
            'services.*.options' => 'nullable|array',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'destination' => 'nullable|string|max:255',
            'client_email' => 'nullable|email',
            'duree' => 'nullable|string|max:100'
        ]);

        try {
            DB::beginTransaction();

            $package = LuxuryPackage::createPersonnaliseFromServices(
                $validated['services'],
                [
                    'nom' => $validated['nom'],
                    'description' => $validated['description'],
                    'nombre_personnes' => $validated['nombre_personnes'],
                    'destination' => $validated['destination'],
                    'client_email' => $validated['client_email'],
                    'duree' => $validated['duree']
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Package personnalisé créé avec succès',
                'data' => $package,
                'estimation' => $package->prix_estime
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du package personnalisé',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Statistiques pour le dashboard
     */
    public function getDashboardStats()
    {
        try {
            $stats = [
                'totaux' => [
                    'total_packages' => LuxuryPackage::count(),
                    'packages_actifs' => LuxuryPackage::where('actif', true)->count(),
                    'packages_visibles' => LuxuryPackage::where('visible_public', true)->count(),
                    'packages_predefinits' => LuxuryPackage::where('type', 'predefinit')->count(),
                    'packages_personnalises' => LuxuryPackage::where('type', 'personnalise')->count()
                ],
                'financier' => [
                    'revenus_estimes' => LuxuryPackage::where('type', 'predefinit')
                                                     ->where('actif', true)
                                                     ->sum('prix_total'),
                    'prix_moyen' => LuxuryPackage::where('type', 'predefinit')
                                                ->where('actif', true)
                                                ->avg('prix_total')
                ],
                'popularite' => [
                    'packages_populaires' => LuxuryPackage::orderBy('popularite', 'desc')
                                                         ->limit(5)
                                                         ->get(['id', 'nom', 'popularite', 'prix_total', 'type']),
                    'total_vues' => LuxuryPackage::sum('popularite')
                ],
                'demandes' => [
                    'total_demandes' => DB::table('luxury_package_requests')->count(),
                    'demandes_ce_mois' => DB::table('luxury_package_requests')
                                           ->whereMonth('created_at', now()->month)
                                           ->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Statistiques par type
     */
    public function getStatsByType()
    {
        try {
            $stats = LuxuryPackage::select(
                        'type',
                        DB::raw('count(*) as total'),
                        DB::raw('sum(case when actif = 1 then 1 else 0 end) as actifs'),
                        DB::raw('sum(case when visible_public = 1 then 1 else 0 end) as visibles'),
                        DB::raw('avg(CASE WHEN type = "predefinit" THEN prix_total ELSE prix_estime END) as prix_moyen'),
                        DB::raw('sum(popularite) as popularite_totale')
                    )
                    ->groupBy('type')
                    ->get()
                    ->map(function($item) {
                        return [
                            'type' => $item->type,
                            'type_label' => $item->type === 'predefinit' ? 'Prédéfini' : 'Personnalisé',
                            'total' => $item->total,
                            'actifs' => $item->actifs,
                            'visibles' => $item->visibles,
                            'inactifs' => $item->total - $item->actifs,
                            'prix_moyen' => round($item->prix_moyen, 2),
                            'popularite_totale' => $item->popularite_totale
                        ];
                    });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Statistiques par destination
     */
    public function getStatsByDestination()
    {
        try {
            $stats = LuxuryPackage::select(
                        'destination',
                        DB::raw('count(*) as total'),
                        DB::raw('avg(CASE WHEN type = "predefinit" THEN prix_total ELSE prix_estime END) as prix_moyen'),
                        DB::raw('sum(popularite) as popularite_totale'),
                        DB::raw('sum(case when visible_public = 1 then 1 else 0 end) as visibles')
                    )
                    ->whereNotNull('destination')
                    ->where('destination', '!=', '')
                    ->groupBy('destination')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'destination' => $item->destination,
                            'total' => $item->total,
                            'visibles' => $item->visibles,
                            'prix_moyen' => round($item->prix_moyen, 2),
                            'popularite_totale' => $item->popularite_totale
                        ];
                    });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   