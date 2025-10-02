<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuxuryPackage;
use App\Models\LuxuryService;
use App\Models\LuxuryPackageRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LuxuryPackageController extends Controller
{
    /**
     * ADMINISTRATION - Liste des packages
     */
    public function index(Request $request)
    {
        $query = LuxuryPackage::query();

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par visibilité
        if ($request->filled('visible')) {
            $query->where('visible_public', $request->boolean('visible'));
        }

        // Filtrage par statut actif
        if ($request->filled('actif')) {
            $query->where('actif', $request->boolean('actif'));
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'popularite') {
            $query->orderBy('popularite', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $packages = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => LuxuryPackage::count(),
            'predefinis' => LuxuryPackage::where('type', 'predefinit')->count(),
            'personnalises' => LuxuryPackage::where('type', 'personnalise')->count(),
            'visibles' => LuxuryPackage::where('visible_public', true)->count(),
            'actifs' => LuxuryPackage::where('actif', true)->count()
        ];

        return view('admin.luxury.packages.index', compact('packages', 'stats'));
    }

    /**
     * ADMINISTRATION - Formulaire de création
     */
    public function create()
    {
        $services = LuxuryService::actif()->get()->groupBy('categorie');
        $categories = LuxuryService::getCategories();

        return view('admin.luxury.packages.create', compact('services', 'categories'));
    }

    /**
     * ADMINISTRATION - Enregistrer un nouveau package
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
            'prix_total' => 'nullable|numeric|min:0',
            'prix_estime' => 'nullable|numeric|min:0',
            'personnalisations' => 'nullable|array',
            'duree' => 'nullable|string|max:100',
            'nombre_personnes' => 'required|integer|min:1|max:100',
            'destination' => 'nullable|string|max:255',
            'actif' => 'boolean',
            'visible_public' => 'boolean',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:096',
            'galerie_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
            'client_email' => 'nullable|email|required_if:type,personnalise',
            'date_expiration' => 'nullable|date|after:now'
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'image principale
            if ($request->hasFile('image_principale')) {
                $imagePath = $request->file('image_principale')->store('luxury-packages', 'public');
                $validated['image_principale'] = basename($imagePath);
            }

            // Gestion de la galerie d'images
            if ($request->hasFile('galerie_images')) {
                $galerie = [];
                foreach ($request->file('galerie_images') as $image) {
                    $path = $image->store('luxury-packages', 'public');
                    $galerie[] = basename($path);
                }
                $validated['galerie_images'] = $galerie;
            }

            // Formater les services inclus
            $validated['services_inclus'] = array_values($validated['services_inclus']);

            // Calculer le prix automatiquement si non fourni
            if ($validated['type'] === 'predefinit' && !isset($validated['prix_total'])) {
                $prixCalcule = $this->calculatePackagePrice($validated['services_inclus']);
                $validated['prix_total'] = $prixCalcule;
            } elseif ($validated['type'] === 'personnalise') {
                $prixCalcule = $this->calculatePackagePrice($validated['services_inclus']);
                $validated['prix_estime'] = $prixCalcule;
                $validated['prix_total'] = null;
            }

            $package = LuxuryPackage::create($validated);

            DB::commit();

            return redirect()->route('admin.luxury.packages.show', $package)
                           ->with('success', 'Package créé avec succès !');

        } catch (\Exception $e) {
            DB::rollback();

            // Nettoyer les images uploadées en cas d'erreur
            if (isset($validated['image_principale'])) {
                Storage::disk('public')->delete('luxury-packages/' . $validated['image_principale']);
            }
            if (isset($validated['galerie_images'])) {
                foreach ($validated['galerie_images'] as $image) {
                    Storage::disk('public')->delete('luxury-packages/' . $image);
                }
            }

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la création du package : ' . $e->getMessage());
        }
    }

    /**
     * ADMINISTRATION - Afficher un package
     */
    public function show(LuxuryPackage $luxury_package)
    {
        $services = $luxury_package->services();
        $demandes = LuxuryPackageRequest::where('luxury_package_id', $luxury_package->id)
                                       ->latest()
                                       ->limit(10)
                                       ->get();

        return view('admin.luxury.packages.show', [
        'package' => $luxury_package,
        'services' => $services,
        'demandes' => $demandes
    ]);
    }

    /**
     * ADMINISTRATION - Formulaire d'édition
     */
    public function edit(LuxuryPackage $luxury_package)
    {
        $services = LuxuryService::actif()->get()->groupBy('categorie');
        $categories = LuxuryService::getCategories();

        return view('admin.luxury.packages.edit', [
        'package' => $luxury_package,
        'services' => $services,
        'categories' => $categories
    ]);
    }

    /**
     * ADMINISTRATION - Mettre à jour un package
     */
    public function update(Request $request, LuxuryPackage $package)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'services_inclus' => 'required|array|min:1',
            'services_inclus.*.service_id' => 'required|exists:luxury_services,id',
            'services_inclus.*.quantite' => 'required|integer|min:1',
            'services_inclus.*.duration' => 'nullable|numeric|min:0.5',
            'services_inclus.*.options' => 'nullable|array',
            'prix_total' => 'nullable|numeric|min:0',
            'prix_estime' => 'nullable|numeric|min:0',
            'personnalisations' => 'nullable|array',
            'duree' => 'nullable|string|max:100',
            'nombre_personnes' => 'required|integer|min:1|max:100',
            'destination' => 'nullable|string|max:255',
            'actif' => 'boolean',
            'visible_public' => 'boolean',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'galerie_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_expiration' => 'nullable|date|after:now'
        ]);

        try {
            DB::beginTransaction();

            // Gestion de l'image principale
            if ($request->hasFile('image_principale')) {
                // Supprimer l'ancienne image
                if ($package->image_principale) {
                    Storage::disk('public')->delete('luxury-packages/' . $package->image_principale);
                }
                $imagePath = $request->file('image_principale')->store('luxury-packages', 'public');
                $validated['image_principale'] = basename($imagePath);
            }

            // Gestion de la galerie d'images
            if ($request->hasFile('galerie_images')) {
                // Supprimer les anciennes images si demandé
                if ($request->boolean('replace_gallery') && $package->galerie_images) {
                    foreach ($package->galerie_images as $oldImage) {
                        Storage::disk('public')->delete('luxury-packages/' . $oldImage);
                    }
                    $galerie = [];
                } else {
                    $galerie = $package->galerie_images ?? [];
                }

                foreach ($request->file('galerie_images') as $image) {
                    $path = $image->store('luxury-packages', 'public');
                    $galerie[] = basename($path);
                }
                $validated['galerie_images'] = $galerie;
            }

            // Formater les services inclus
            $validated['services_inclus'] = array_values($validated['services_inclus']);

            // Recalculer le prix si les services ont changé
            if ($package->type === 'predefinit' && !isset($validated['prix_total'])) {
                $validated['prix_total'] = $this->calculatePackagePrice($validated['services_inclus']);
            } elseif ($package->type === 'personnalise') {
                $validated['prix_estime'] = $this->calculatePackagePrice($validated['services_inclus']);
            }

            $package->update($validated);

            DB::commit();

            return redirect()->route('admin.luxury.packages.show', $package)
                           ->with('success', 'Package mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la mise à jour du package.');
        }
    }

    /**
     * ADMINISTRATION - Supprimer un package
     */
    public function destroy(LuxuryPackage $package)
    {
        try {
            // Vérifier si le package est utilisé dans des demandes
            if (LuxuryPackageRequest::where('luxury_package_id', $package->id)->exists()) {
                return redirect()->back()
                               ->with('error', 'Impossible de supprimer ce package car il est utilisé dans des demandes.');
            }

            // Supprimer les images
            if ($package->image_principale) {
                Storage::disk('public')->delete('luxury-packages/' . $package->image_principale);
            }
            if ($package->galerie_images) {
                foreach ($package->galerie_images as $image) {
                    Storage::disk('public')->delete('luxury-packages/' . $image);
                }
            }

            $package->delete();

            return redirect()->route('admin.luxury.packages.index')
                           ->with('success', 'Package supprimé avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression du package.');
        }
    }

    /**
     * ADMINISTRATION - Basculer l'état actif/inactif
     */
    public function toggleStatus(LuxuryPackage $package)
    {
        try {
            $package->update(['actif' => !$package->actif]);

            $message = $package->actif ? 'Package activé' : 'Package désactivé';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors du changement de statut.');
        }
    }

    /**
     * ADMINISTRATION - Basculer la visibilité publique
     */
    public function toggleVisibility(LuxuryPackage $package)
    {
        try {
            $package->update(['visible_public' => !$package->visible_public]);

            $message = $package->visible_public ? 'Package rendu visible' : 'Package masqué';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors du changement de visibilité.');
        }
    }

    /**
     * ADMINISTRATION - Dupliquer un package
     */
    public function duplicate(LuxuryPackage $package)
    {
        try {
            $copy = $package->replicate();
            $copy->nom = $copy->nom . ' (Copie)';
            $copy->popularite = 0;
            $copy->visible_public = false;
            $copy->save();

            return redirect()->route('admin.luxury.packages.edit', $copy)
                           ->with('success', 'Package dupliqué avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la duplication du package.');
        }
    }

    /**
     * PUBLIC - Catalogue des packages
     */
    public function catalog(Request $request)
    {
        $query = LuxuryPackage::visible();

        // Filtrage par destination
        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        // Filtrage par nombre de personnes
        if ($request->filled('personnes')) {
            $query->where('nombre_personnes', '>=', $request->personnes);
        }

        // Filtrage par budget
        if ($request->filled('budget_min')) {
            $query->where('prix_total', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('prix_total', '<=', $request->budget_max);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
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
            case 'recent':
                $query->latest();
                break;
            default:
                $query->populaires();
        }

        $packages = $query->paginate(12)->withQueryString();

        return view('public.luxury.packages', compact('packages'));
    }

    /**
     * PUBLIC - Détails d'un package
     */
    public function details(LuxuryPackage $package)
    {
        if (!$package->actif || !$package->visible_public) {
            abort(404);
        }

        $package->incrementPopularite();

        $services = $package->services();
        $packagesAssocies = LuxuryPackage::visible()
                                        ->where('id', '!=', $package->id)
                                        ->inRandomOrder()
                                        ->limit(4)
                                        ->get();

        return view('public.luxury.package-details', compact('package', 'services', 'packagesAssocies'));
    }

    /**
     * PUBLIC - Personnaliser un package
     */
    public function customize(LuxuryPackage $package)
    {
        if (!$package->actif || !$package->visible_public) {
            abort(404);
        }

        $services = LuxuryService::actif()->get()->groupBy('categorie');
        $categories = LuxuryService::getCategories();

        return view('public.luxury.customize-package', compact('package', 'services', 'categories'));
    }

    /**
     * PUBLIC - Enregistrer la personnalisation
     */
    public function storeCustomization(Request $request, LuxuryPackage $package)
    {
        $validated = $request->validate([
            'client_email' => 'required|email',
            'services_inclus' => 'required|array|min:1',
            'services_inclus.*.service_id' => 'required|exists:luxury_services,id',
            'services_inclus.*.quantite' => 'required|integer|min:1',
            'services_inclus.*.duration' => 'nullable|numeric|min:0.5',
            'services_inclus.*.options' => 'nullable|array',
            'personnalisations' => 'nullable|array',
            'nombre_personnes' => 'required|integer|min:1',
            'destination' => 'nullable|string|max:255'
        ]);

        try {
            $copy = $package->duplicateAsPersonnalise($validated['client_email']);

            // Mettre à jour avec les personnalisations
            $copy->update([
                'services_inclus' => $validated['services_inclus'],
                'personnalisations' => $validated['personnalisations'],
                'nombre_personnes' => $validated['nombre_personnes'],
                'destination' => $validated['destination'] ?? $package->destination,
                'prix_estime' => $this->calculatePackagePrice($validated['services_inclus'])
            ]);

            return response()->json([
                'success' => true,
                'package_id' => $copy->id,
                'message' => 'Package personnalisé créé avec succès !',
                'redirect_url' => route('public.luxury.request.create', ['package_id' => $copy->id])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la personnalisation du package.'
            ], 500);
        }
    }

    /**
     * API - Obtenir les détails d'un package (AJAX)
     */
    public function getDetails(LuxuryPackage $package)
    {
        if (!$package->actif) {
            return response()->json(['error' => 'Package non disponible'], 404);
        }

        $services = $package->services();

        return response()->json([
            'package' => $package,
            'services' => $services->map(function($service) use ($package) {
                $serviceData = collect($package->services_inclus)
                    ->firstWhere('service_id', $service->id);

                return [
                    'id' => $service->id,
                    'nom' => $service->nom,
                    'categorie' => $service->categorie_display,
                    'quantite' => $serviceData['quantite'] ?? 1,
                    'duration' => $serviceData['duration'] ?? 1,
                    'options' => $serviceData['options'] ?? [],
                    'prix_base' => $service->prix_base,
                    'type_prix' => $service->type_prix
                ];
            }),
            'prix_total' => $package->prix_total ?? $package->prix_estime
        ]);
    }

    /**
     * Calculer le prix total d'un package
     */
    private function calculatePackagePrice($servicesInclus)
    {
        $total = 0;

        foreach ($servicesInclus as $serviceData) {
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

        return round($total, 2);
    }

    /**
     * ADMINISTRATION - Export des packages
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $packages = LuxuryPackage::all()->map(function($package) {
            return [
                'ID' => $package->id,
                'Nom' => $package->nom,
                'Type' => $package->type_label,
                'Destination' => $package->destination,
                'Nombre de personnes' => $package->nombre_personnes,
                'Durée' => $package->duree,
                'Services inclus' => $package->services_count,
                'Prix' => $package->prix_total ?? $package->prix_estime,
                'Popularité' => $package->popularite,
                'Actif' => $package->actif ? 'Oui' : 'Non',
                'Visible' => $package->visible_public ? 'Oui' : 'Non',
                'Créé le' => $package->created_at->format('d/m/Y'),
            ];
        });

        $filename = 'packages_luxe_' . now()->format('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ];

            $callback = function() use ($packages) {
                $file = fopen('php://output', 'w');

                if ($packages->isNotEmpty()) {
                    fputcsv($file, array_keys($packages->first()));

                    foreach ($packages as $package) {
                        fputcsv($file, array_values($package));
                    }
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Format d\'export non supporté.');
    }
}
