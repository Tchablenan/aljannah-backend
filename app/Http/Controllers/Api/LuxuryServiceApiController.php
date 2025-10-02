<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LuxuryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LuxuryServiceApiController extends Controller
{
    /**
     * API - Liste des services (pour React admin)
     */
    public function index(Request $request)
    {
        $query = LuxuryService::query();

        // Filtrage par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filtrage par statut actif/inactif
        if ($request->filled('actif')) {
            $query->where('actif', $request->boolean('actif'));
        }

        // Filtrage par type de prix
        if ($request->filled('type_prix')) {
            $query->where('type_prix', $request->type_prix);
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('fournisseur', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $services = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $services->items(),
            'pagination' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total()
            ],
            'filters' => [
                'categories' => LuxuryService::getCategories(),
                'types_prix' => LuxuryService::getTypesPrix()
            ]
        ]);
    }

    /**
     * API - Créer un service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|in:' . implode(',', array_keys(LuxuryService::getCategories())),
            'description' => 'required|string|max:2000',
            'prix_base' => 'nullable|numeric|min:0',
            'type_prix' => 'required|in:fixe,heure,jour,forfait,sur_devis',
            'options_disponibles' => 'nullable|array',
            'fournisseur' => 'nullable|string|max:255',
            'contact_fournisseur' => 'nullable|string|max:255',
            'actif' => 'boolean'
        ]);

        try {
            $service = LuxuryService::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service créé avec succès',
                'data' => $service
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du service',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Afficher un service
     */
    public function show($id)
    {
        try {
            $service = LuxuryService::findOrFail($id);
            $service->loadCount('packageRequests');
            $suggestions = $service->getSuggestedServices(4);

            return response()->json([
                'success' => true,
                'data' => $service,
                'suggestions' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service non trouvé'
            ], 404);
        }
    }

    /**
     * API - Mettre à jour un service
     */
    public function update(Request $request, $id)
    {
        try {
            $service = LuxuryService::findOrFail($id);

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'categorie' => 'required|in:' . implode(',', array_keys(LuxuryService::getCategories())),
                'description' => 'required|string|max:2000',
                'prix_base' => 'nullable|numeric|min:0',
                'type_prix' => 'required|in:fixe,heure,jour,forfait,sur_devis',
                'options_disponibles' => 'nullable|array',
                'fournisseur' => 'nullable|string|max:255',
                'contact_fournisseur' => 'nullable|string|max:255',
                'actif' => 'boolean'
            ]);

            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service mis à jour avec succès',
                'data' => $service
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Supprimer un service
     */
    public function destroy($id)
    {
        try {
            $service = LuxuryService::findOrFail($id);

            // Vérifier que le service n'est pas utilisé
            if ($service->packageRequests()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce service car il est utilisé dans des demandes.'
                ], 422);
            }

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * API - Services par catégorie
     */
    public function getByCategory($categorie)
    {
        $services = LuxuryService::actif()
                                ->byCategorie($categorie)
                                ->select('id', 'nom', 'description', 'prix_base', 'type_prix', 'options_disponibles')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'categorie' => $categorie,
            'categorie_display' => LuxuryService::getCategories()[$categorie] ?? $categorie
        ]);
    }

    /**
     * API - Recherche de services
     */
    public function search(Request $request)
    {
        $query = LuxuryService::actif();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $services = $query->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'total' => $services->count()
        ]);
    }

    /**
     * API - Services actifs
     */
    public function getActifs()
    {
        $services = LuxuryService::actif()->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'total' => $services->count()
        ]);
    }

    /**
     * API - Services populaires
     */
    public function getPopulaires(Request $request)
    {
        $limit = $request->get('limit', 10);
        $services = LuxuryService::populaires($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * API - Calculer le prix d'un service
     */
    public function calculatePrice(Request $request, $id)
    {
        try {
            $service = LuxuryService::findOrFail($id);

            $request->validate([
                'options' => 'nullable|array',
                'duration' => 'nullable|numeric|min:0.5',
                'quantite' => 'nullable|integer|min:1'
            ]);

            $options = $request->get('options', []);
            $duration = $request->get('duration', 1);
            $quantite = $request->get('quantite', 1);

            $prix = $service->calculatePrice($options, $duration, $quantite);

            if ($prix === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce service nécessite un devis personnalisé',
                    'sur_devis' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'prix' => $prix,
                'prix_formatted' => number_format($prix, 2, ',', ' ') . ' €',
                'details' => [
                    'service_id' => $service->id,
                    'service_nom' => $service->nom,
                    'prix_base' => $service->prix_base,
                    'type_prix' => $service->type_prix,
                    'duration' => $duration,
                    'quantite' => $quantite,
                    'options_count' => count($options)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du prix'
            ], 500);
        }
    }

    /**
     * API - Obtenir les catégories
     */
    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => LuxuryService::getCategories()
        ]);
    }

    /**
     * API - Obtenir les types de prix
     */
    public function getTypesPrix()
    {
        return response()->json([
            'success' => true,
            'data' => LuxuryService::getTypesPrix()
        ]);
    }

    /**
     * API - Suggestions pour un service
     */
    public function getSuggestions($id)
    {
        try {
            $service = LuxuryService::findOrFail($id);
            $suggestions = $service->getSuggestedServices();

            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service non trouvé'
            ], 404);
        }
    }

    /**
     * API - Statistiques pour le dashboard
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_services' => LuxuryService::count(),
            'services_actifs' => LuxuryService::where('actif', true)->count(),
            'services_inactifs' => LuxuryService::where('actif', false)->count(),
            'services_sur_devis' => LuxuryService::where('type_prix', 'sur_devis')->count(),
            'services_par_categorie' => LuxuryService::select('categorie', DB::raw('count(*) as total'))
                                                    ->groupBy('categorie')
                                                    ->get()
                                                    ->pluck('total', 'categorie'),
            'services_populaires' => LuxuryService::withCount('packageRequests')
                                                 ->orderBy('package_requests_count', 'desc')
                                                 ->limit(5)
                                                 ->get(['id', 'nom', 'package_requests_count'])
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * API - Statistiques par catégorie
     */
    public function getStatsByCategory()
    {
        $stats = LuxuryService::select('categorie', 
                                      DB::raw('count(*) as total'),
                                      DB::raw('sum(case when actif = 1 then 1 else 0 end) as actifs'))
                              ->groupBy('categorie')
                              ->get()
                              ->map(function($item) {
                                  return [
                                      'categorie' => $item->categorie,
                                      'categorie_display' => LuxuryService::getCategories()[$item->categorie] ?? $item->categorie,
                                      'total' => $item->total,
                                      'actifs' => $item->actifs,
                                      'inactifs' => $item->total - $item->actifs
                                  ];
                              });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // ============= ROUTES PUBLIQUES =============

    /**
     * API PUBLIC - Liste des services (visiteurs)
     */
    public function publicIndex(Request $request)
    {
        $query = LuxuryService::actif();

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'popularite');
        switch ($sortBy) {
            case 'prix_asc':
                $query->orderBy('prix_base', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix_base', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            default:
                $query->populaires();
        }

        $services = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $services->items(),
            'pagination' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total()
            ]
        ]);
    }

    /**
     * API PUBLIC - Détails d'un service
     */
    public function publicShow($id)
    {
        try {
            $service = LuxuryService::where('actif', true)->findOrFail($id);
            $suggestions = $service->getSuggestedServices();

            return response()->json([
                'success' => true,
                'data' => $service,
                'suggestions' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service non disponible'
            ], 404);
        }
    }

    /**
     * API PUBLIC - Services par catégorie
     */
    public function publicByCategory($categorie)
    {
        $services = LuxuryService::actif()
                                ->byCategorie($categorie)
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'categorie' => $categorie,
            'categorie_display' => LuxuryService::getCategories()[$categorie] ?? $categorie
        ]);
    }

    /**
     * API PUBLIC - Recherche publique
     */
    public function publicSearch(Request $request)
    {
        $query = LuxuryService::actif();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * API PUBLIC - Services populaires
     */
    public function publicPopulaires()
    {
        $services = LuxuryService::actif()->populaires(8)->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * API PUBLIC - Catégories disponibles
     */
    public function publicGetCategories()
    {
        $categories = LuxuryService::getCategories();
        
        // Ajouter le nombre de services par catégorie
        $categoriesWithCount = [];
        foreach ($categories as $key => $name) {
            $count = LuxuryService::actif()->where('categorie', $key)->count();
            $categoriesWithCount[] = [
                'key' => $key,
                'name' => $name,
                'count' => $count
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $categoriesWithCount
        ]);
    }
}