<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuxuryService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LuxuryPackage;
use App\Models\LuxuryPackageRequest;


class LuxuryServiceController extends Controller
{
    /**
     * ADMINISTRATION - Liste des services
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

        $services = $query->paginate(15);

        // Données pour les filtres
        $categories = LuxuryService::getCategories();
        $typesPrix = LuxuryService::getTypesPrix();

        return view('admin.luxury.services.index', compact('services', 'categories', 'typesPrix'));
    }

    /**
     * ADMINISTRATION - Afficher le formulaire de création
     */
    public function create()
    {
        $categories = LuxuryService::getCategories();
        $typesPrix = LuxuryService::getTypesPrix();

        return view('admin.luxury.services.create', compact('categories', 'typesPrix'));
    }

    /**
     * ADMINISTRATION - Enregistrer un nouveau service
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
            'options_disponibles.*.key' => 'required|string|max:50',
            'options_disponibles.*.nom' => 'required|string|max:100',
            'options_disponibles.*.prix_supplement' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'contact_fournisseur' => 'nullable|string|max:255',
            'actif' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048'
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('luxury-services', 'public');
                $validated['image'] = $imagePath;
            }

            // Traitement des options
            if (isset($validated['options_disponibles'])) {
                $validated['options_disponibles'] = array_values(array_filter($validated['options_disponibles'], function($option) {
                    return !empty($option['key']) && !empty($option['nom']);
                }));
            }

            $service = LuxuryService::create($validated);

            DB::commit();

            return redirect()->route('admin.luxury.services.show', $service)
                           ->with('success', 'Service créé avec succès !');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la création du service.');
        }
    }

    /**
     * ADMINISTRATION - Afficher un service
     */
    public function show(LuxuryService $luxury_service)
    {
        $service = $luxury_service;
        $servicesAssocies = $service->getSuggestedServices(4);

        return view('admin.luxury.services.show', compact('service', 'servicesAssocies'));
    }

    /**
     * ADMINISTRATION - Afficher le formulaire d'édition
     */
    public function edit(LuxuryService $luxury_service)
    {
        $service = $luxury_service;
        $categories = LuxuryService::getCategories();
        $typesPrix = LuxuryService::getTypesPrix();

        return view('admin.luxury.services.edit', compact('service', 'categories', 'typesPrix'));
    }

    /**
     * ADMINISTRATION - Mettre à jour un service
     */
    public function update(Request $request, LuxuryService $luxury_service)
    {

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|in:' . implode(',', array_keys(LuxuryService::getCategories())),
            'description' => 'required|string|max:2000',
            'prix_base' => 'nullable|numeric|min:0',
            'type_prix' => 'required|in:fixe,heure,jour,forfait,sur_devis',
            'options_disponibles' => 'nullable|array',
            'options_disponibles.*.key' => 'required|string|max:50',
            'options_disponibles.*.nom' => 'required|string|max:100',
            'options_disponibles.*.prix_supplement' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'contact_fournisseur' => 'nullable|string|max:255',
            'actif' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048'
        ]);
         $service = $luxury_service;

        try {
            DB::beginTransaction();

            // Gestion de l'upload d'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($service->image) {
                    Storage::disk('public')->delete($service->image);
                }

                $imagePath = $request->file('image')->store('luxury-services', 'public');
                $validated['image'] = $imagePath;
            }

            // Traitement des options
            if (isset($validated['options_disponibles'])) {
                $validated['options_disponibles'] = array_values(array_filter($validated['options_disponibles'], function($option) {
                    return !empty($option['key']) && !empty($option['nom']);
                }));
            }

            $service->update($validated);

            DB::commit();

            return redirect()->route('admin.luxury.services.show', $service)
                           ->with('success', 'Service mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollback();



            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la mise à jour du service.');
        }
    }

    /**
     * ADMINISTRATION - Supprimer un service
     */
    public function destroy(LuxuryService $service)
    {
        try {
            // Vérifier que le service n'est pas utilisé dans des packages/demandes
            if ($service->packageRequests()->exists()) {
                return redirect()->back()
                               ->with('error', 'Impossible de supprimer ce service car il est utilisé dans des demandes.');
            }

            // Supprimer l'image associée
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();

            return redirect()->route('admin.luxury.services.index')
                           ->with('success', 'Service supprimé avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression du service.');
        }
    }

    /**
     * ADMINISTRATION - Changer le statut actif/inactif
     */
    public function toggleStatus(LuxuryService $service)
    {
        try {
            $service->update(['actif' => !$service->actif]);

            $message = $service->actif ? 'Service activé' : 'Service désactivé';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors du changement de statut.');
        }
    }

    /**
     * API - Obtenir les services par catégorie (pour formulaires dynamiques)
     */
    public function getByCategory($categorie)
    {
        $services = LuxuryService::actif()
                                ->where('categorie', $categorie)
                                ->select('id', 'nom', 'description', 'prix_base', 'type_prix', 'options_disponibles')
                                ->get();

        return response()->json($services);
    }

    /**
     * API - Calculer le prix d'un service (AJAX)
     */
    public function calculatePrice(Request $request, LuxuryService $service)
    {
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
                'prix_base' => $service->prix_base,
                'type_prix' => $service->type_prix,
                'duration' => $duration,
                'quantite' => $quantite,
                'options_count' => count($options)
            ]
        ]);
    }

    /**
     * PUBLIC - Catalogue des services (page publique)
     */
    public function catalog(Request $request)
    {
        $query = LuxuryService::actif();

        // Filtrage par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri par popularité ou prix
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

        $services = $query->paginate(12)->withQueryString();
        $categories = LuxuryService::getCategories();

        return view('public.luxury.catalog', compact('services', 'categories'));
    }

    /**
     * PUBLIC - Détails d'un service (page publique)
     */
    public function details(LuxuryService $service)
    {
        if (!$service->actif) {
            abort(404);
        }

        $servicesAssocies = $service->getSuggestedServices();

        return view('public.luxury.service-details', compact('service', 'servicesAssocies'));
    }

    /**
     * ADMINISTRATION - Dashboard des services
     */
/**
 * ADMINISTRATION - Dashboard principal de la conciergerie
 */
/**
 * ADMINISTRATION - Dashboard principal de la conciergerie
 */
public function dashboard()
{
    try {
        $stats = [
            // KPIs principaux
            'total_services' => LuxuryService::count(),
            'services_actifs' => LuxuryService::where('actif', true)->count(),
            'services_inactifs' => LuxuryService::where('actif', false)->count(),
            'total_packages' => LuxuryPackage::count(),
            'packages_visibles' => LuxuryPackage::where('visible_public', true)->count(),
            'total_demandes' => LuxuryPackageRequest::count(),
            'demandes_ce_mois' => LuxuryPackageRequest::whereMonth('created_at', now()->month)
                                                     ->whereYear('created_at', now()->year)
                                                     ->count(),

            // Revenus (corrigé pour utiliser les bons champs)
            'revenus_confirmes' => LuxuryPackageRequest::where('statut', 'confirme')
                                                       ->whereMonth('created_at', now()->month)
                                                       ->sum('prix_final'),
            'revenus_potentiels' => LuxuryPackageRequest::whereIn('statut', ['devis_envoye', 'en_negociation'])
                                                        ->sum('prix_propose'),

            // Alertes (corrigé avec les bons statuts et champs)
            'demandes_urgentes' => LuxuryPackageRequest::whereIn('priorite', ['urgente', 'vip'])
                                                       ->whereNotIn('statut', ['termine', 'annule'])
                                                       ->count(),
            'demandes_non_assignees' => LuxuryPackageRequest::whereNull('concierge_assigne')
                                                            ->whereNotIn('statut', ['termine', 'annule'])
                                                            ->count(),
            'devis_expirant' => LuxuryPackageRequest::where('statut', 'devis_envoye')
                                                    ->whereNotNull('date_expiration_devis')
                                                    ->where('date_expiration_devis', '<=', now()->addDays(3))
                                                    ->count(),

            // Services populaires (relation corrigée)
            'services_populaires' => $this->getPopularServices(),

            // Demandes récentes avec le bon statut
            'demandes_recentes' => LuxuryPackageRequest::with(['package'])
                                                       ->orderBy('created_at', 'desc')
                                                       ->limit(5)
                                                       ->get()
                                                       ->map(function($demande) {
                                                           return [
                                                               'id' => $demande->id,
                                                               'reference' => $demande->reference,
                                                               'client' => $demande->client_nom_complet,
                                                               'statut' => $demande->statut,
                                                               'statut_display' => $demande->statut_display,
                                                               'statut_color' => $demande->statut_color,
                                                               'priorite' => $demande->priorite,
                                                               'priorite_color' => $demande->priorite_color,
                                                               'prix' => $demande->prix_propose ?? $demande->budget_estime,
                                                               'created_at' => $demande->created_at
                                                           ];
                                                       }),

            // Répartition par statut (avec les bons statuts)
            'demandes_par_statut' => LuxuryPackageRequest::select('statut', DB::raw('count(*) as total'))
                                                         ->groupBy('statut')
                                                         ->get()
                                                         ->mapWithKeys(function($item) {
                                                             $statuts_display = [
                                                                 'nouvelle' => 'Nouvelles',
                                                                 'en_analyse' => 'En analyse',
                                                                 'devis_envoye' => 'Devis envoyé',
                                                                 'en_negociation' => 'Négociation',
                                                                 'confirme' => 'Confirmées',
                                                                 'en_preparation' => 'Préparation',
                                                                 'en_cours' => 'En cours',
                                                                 'termine' => 'Terminées',
                                                                 'annule' => 'Annulées'
                                                             ];
                                                             return [$statuts_display[$item->statut] ?? $item->statut => $item->total];
                                                         }),

            // Répartition par catégorie avec labels français
            'services_par_categorie' => LuxuryService::select('categorie', DB::raw('count(*) as total'))
                                                     ->where('actif', true)
                                                     ->groupBy('categorie')
                                                     ->get()
                                                     ->mapWithKeys(function($item) {
                                                         $categories = LuxuryService::getCategories();
                                                         return [$categories[$item->categorie] ?? $item->categorie => $item->total];
                                                     }),

            // Métriques de performance
            'taux_conversion' => $this->calculateConversionRate(),
            'temps_reponse_moyen' => $this->calculateAverageResponseTime(),
            'panier_moyen' => LuxuryPackageRequest::where('statut', 'confirme')
                                                  ->avg('prix_final') ?? 0,

            // Statistiques temporelles
            'evolution_demandes' => $this->getDemandesEvolution(),

            // Top clients
            'top_clients' => LuxuryPackageRequest::select('client_email', 'client_nom', 'client_prenom')
                                                 ->selectRaw('COUNT(*) as nb_demandes')
                                                 ->selectRaw('SUM(CASE WHEN statut = "confirme" THEN prix_final ELSE 0 END) as total_achats')
                                                 ->groupBy('client_email', 'client_nom', 'client_prenom')
                                                 ->orderBy('total_achats', 'desc')
                                                 ->limit(5)
                                                 ->get()
        ];

        return view('admin.luxury.dashboard', compact('stats'));

    } catch (\Exception $e) {
        \Log::error('Erreur dashboard luxury: ' . $e->getMessage());

        // Stats vides en cas d'erreur
        $stats = $this->getEmptyStats();

        return view('admin.luxury.dashboard', compact('stats'))
               ->with('error', 'Erreur lors du chargement des statistiques.');
    }
}

/**
 * Obtenir les services populaires
 */
private function getPopularServices()
{
    // Compter les services utilisés dans les demandes
    $serviceIds = [];
    $demandes = LuxuryPackageRequest::whereMonth('created_at', now()->month)
                                   ->get(['services_souhaites']);

    foreach ($demandes as $demande) {
        if ($demande->services_souhaites) {
            foreach ($demande->services_souhaites as $service) {
                $serviceId = $service['service_id'] ?? null;
                if ($serviceId) {
                    if (!isset($serviceIds[$serviceId])) {
                        $serviceIds[$serviceId] = 0;
                    }
                    $serviceIds[$serviceId] += $service['quantite'] ?? 1;
                }
            }
        }
    }

    // Trier et prendre le top 5
    arsort($serviceIds);
    $topServiceIds = array_slice(array_keys($serviceIds), 0, 5);

    return LuxuryService::whereIn('id', $topServiceIds)
                       ->get()
                       ->map(function($service) use ($serviceIds) {
                           $service->demandes_count = $serviceIds[$service->id] ?? 0;
                           return $service;
                       })
                       ->sortByDesc('demandes_count');
}

/**
 * Calculer le taux de conversion
 */
private function calculateConversionRate()
{
    $totalDemandes = LuxuryPackageRequest::whereMonth('created_at', '>=', now()->subMonths(3))
                                        ->count();
    if ($totalDemandes === 0) return 0;

    $demandesConfirmees = LuxuryPackageRequest::whereMonth('created_at', '>=', now()->subMonths(3))
                                              ->where('statut', 'confirme')
                                              ->count();

    return round(($demandesConfirmees / $totalDemandes) * 100, 1);
}

/**
 * Calculer le temps de réponse moyen
 */
private function calculateAverageResponseTime()
{
    // Calculer basé sur le passage de 'nouvelle' à 'en_analyse' (assignation)
    $demandes = LuxuryPackageRequest::whereNotNull('concierge_assigne')
                                   ->where('created_at', '>=', now()->subMonth())
                                   ->get(['created_at', 'updated_at', 'statut']);

    if ($demandes->isEmpty()) return 0;

    $totalHours = 0;
    $count = 0;

    foreach ($demandes as $demande) {
        if ($demande->statut !== 'nouvelle') {
            $hours = $demande->created_at->diffInHours($demande->updated_at);
            $totalHours += $hours;
            $count++;
        }
    }

    return $count > 0 ? round($totalHours / $count, 1) : 0;
}

/**
 * Obtenir l'évolution des demandes sur 30 jours
 */
private function getDemandesEvolution()
{
    $data = [];

    for ($i = 29; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $count = LuxuryPackageRequest::whereDate('created_at', $date)->count();

        $data[] = [
            'date' => $date->format('d/m'),
            'count' => $count
        ];
    }

    return $data;
}

/**
 * Obtenir les statistiques vides en cas d'erreur
 */
private function getEmptyStats()
{
    return [
        'total_services' => 0,
        'services_actifs' => 0,
        'services_inactifs' => 0,
        'total_packages' => 0,
        'packages_visibles' => 0,
        'total_demandes' => 0,
        'demandes_ce_mois' => 0,
        'revenus_confirmes' => 0,
        'revenus_potentiels' => 0,
        'demandes_urgentes' => 0,
        'demandes_non_assignees' => 0,
        'devis_expirant' => 0,
        'services_populaires' => collect(),
        'demandes_recentes' => collect(),
        'demandes_par_statut' => collect(),
        'services_par_categorie' => collect(),
        'taux_conversion' => 0,
        'temps_reponse_moyen' => 0,
        'panier_moyen' => 0,
        'evolution_demandes' => [],
        'top_clients' => collect()
    ];
}

    /**
     * ADMINISTRATION - Export des services (CSV/Excel)
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $services = LuxuryService::with('packageRequests')
                                ->get()
                                ->map(function($service) {
                                    return [
                                        'ID' => $service->id,
                                        'Nom' => $service->nom,
                                        'Catégorie' => $service->categorie_display,
                                        'Prix de base' => $service->prix_base,
                                        'Type de prix' => $service->type_prix_display,
                                        'Fournisseur' => $service->fournisseur,
                                        'Actif' => $service->actif ? 'Oui' : 'Non',
                                        'Nb demandes' => $service->package_requests_count ?? 0,
                                        'Créé le' => $service->created_at->format('d/m/Y'),
                                        'Modifié le' => $service->updated_at->format('d/m/Y')
                                    ];
                                });

        $filename = 'services_luxe_' . now()->format('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ];

            $callback = function() use ($services) {
                $file = fopen('php://output', 'w');

                // En-têtes CSV
                fputcsv($file, array_keys($services->first()));

                // Données
                foreach ($services as $service) {
                    fputcsv($file, array_values($service));
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Autres formats peuvent être ajoutés ici (Excel, PDF, etc.)
        return redirect()->back()->with('error', 'Format d\'export non supporté.');
    }
}
