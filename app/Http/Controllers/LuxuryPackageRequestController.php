<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LuxuryPackageRequest;
use App\Models\LuxuryPackage;
use App\Models\LuxuryService;
use Illuminate\Support\Facades\DB;

class LuxuryPackageRequestController extends Controller
{
    /**
     * ADMINISTRATION - Liste des demandes
     */
    public function index(Request $request)
    {
        $query = LuxuryPackageRequest::with(['package'])->latest();

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtrage par concierge assigné
        if ($request->filled('concierge')) {
            $query->where('concierge_assigne', 'like', '%' . $request->concierge . '%');
        }

        // Filtrage par période
        if ($request->filled('date_debut')) {
            $query->whereDate('date_debut_souhaitee', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_debut_souhaitee', '<=', $request->date_fin);
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_nom', 'like', "%{$search}%")
                  ->orWhere('client_prenom', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('titre_demande', 'like', "%{$search}%")
                  ->orWhere('destination_principale', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15);

        // Statistiques pour les filtres
        $stats = [
            'total' => LuxuryPackageRequest::count(),
            'nouvelles' => LuxuryPackageRequest::where('statut', 'nouvelle')->count(),
            'en_cours' => LuxuryPackageRequest::whereIn('statut', ['en_analyse', 'devis_envoye', 'en_negociation', 'en_preparation', 'en_cours'])->count(),
            'confirmees' => LuxuryPackageRequest::where('statut', 'confirme')->count(),
            'urgentes' => LuxuryPackageRequest::urgentes()->count()
        ];

        return view('admin.luxury.requests.index', compact('requests', 'stats'));
    }

    /**
     * ADMINISTRATION - Afficher le formulaire de création manuelle
     */
    public function create()
    {
        $packages = LuxuryPackage::actif()->get();
        $services = LuxuryService::actif()->get()->groupBy('categorie');
        $categories = LuxuryService::getCategories();

        return view('admin.luxury.requests.create', compact('packages', 'services', 'categories'));
    }

    /**
     * ADMINISTRATION - Enregistrer une nouvelle demande
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'luxury_package_id' => 'nullable|exists:luxury_packages,id',
            'client_prenom' => 'required|string|max:255',
            'client_nom' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'preferences_client' => 'nullable|array',
            'titre_demande' => 'required|string|max:255',
            'description_demande' => 'required|string|max:2000',
            'services_souhaites' => 'required|array|min:1',
            'services_souhaites.*.service_id' => 'required|exists:luxury_services,id',
            'services_souhaites.*.quantite' => 'required|integer|min:1',
            'services_souhaites.*.duration' => 'nullable|numeric|min:0.5',
            'services_souhaites.*.options' => 'nullable|array',
            'personnalisations_demandees' => 'nullable|array',
            'date_debut_souhaitee' => 'required|date|after:now',
            'date_fin_souhaitee' => 'nullable|date|after:date_debut_souhaitee',
            'destination_principale' => 'required|string|max:255',
            'destinations_multiples' => 'nullable|array',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'budget_estime' => 'nullable|numeric|min:0',
            'priorite' => 'required|in:normale,urgente,vip',
            'concierge_assigne' => 'nullable|string|max:255',
            'notes_internes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Générer une référence unique
            $validated['reference'] = LuxuryPackageRequest::generateReference();
            $validated['statut'] = 'nouvelle';

            $packageRequest = LuxuryPackageRequest::create($validated);

            // Calculer le prix proposé automatiquement
            $prixEstime = $packageRequest->calculateEstimatedPrice();
            $packageRequest->update(['prix_propose' => $prixEstime]);

            // Assigner un concierge si spécifié
            if ($validated['concierge_assigne']) {
                $packageRequest->assignConcierge($validated['concierge_assigne'], 'Demande créée manuellement par l\'administration');
            }

            DB::commit();

            return redirect()->route('admin.luxury.requests.show', $packageRequest)
                           ->with('success', 'Demande créée avec succès !');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la création de la demande.');
        }
    }

    /**
     * PUBLIC - Créer une demande depuis le site public
     */
    public function createFromPublic(Request $request)
    {
        $validated = $request->validate([
            'luxury_package_id' => 'nullable|exists:luxury_packages,id',
            'client_prenom' => 'required|string|max:255',
            'client_nom' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'preferences_client' => 'nullable|array',
            'titre_demande' => 'required|string|max:255',
            'description_demande' => 'required|string|max:1000',
            'services_souhaites' => 'nullable|array',
            'date_debut_souhaitee' => 'required|date|after:now',
            'date_fin_souhaitee' => 'nullable|date|after:date_debut_souhaitee',
            'destination_principale' => 'required|string|max:255',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'budget_estime' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Si c'est basé sur un package existant, copier ses services
            if ($validated['luxury_package_id']) {
                $package = LuxuryPackage::find($validated['luxury_package_id']);
                if ($package) {
                    $validated['services_souhaites'] = $package->services_inclus;
                    $validated['personnalisations_demandees'] = $package->personnalisations;
                }
            }

            // Définir les valeurs par défaut pour les demandes publiques
            $validated['reference'] = LuxuryPackageRequest::generateReference();
            $validated['statut'] = 'nouvelle';
            $validated['priorite'] = 'normale';

            $packageRequest = LuxuryPackageRequest::create($validated);

            // Calculer le prix proposé
            if ($packageRequest->services_souhaites) {
                $prixEstime = $packageRequest->calculateEstimatedPrice();
                $packageRequest->update(['prix_propose' => $prixEstime]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'reference' => $packageRequest->reference,
                'message' => 'Votre demande a été enregistrée avec succès ! Vous recevrez une réponse sous 24h.',
                'redirect_url' => route('public.luxury.request.status', $packageRequest->reference)
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de votre demande. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * ADMINISTRATION - Afficher une demande
     */
    public function show(LuxuryPackageRequest $packageRequest)
    {
        $packageRequest->load(['package']);
        $servicesDetails = $packageRequest->getServicesDetails();

        return view('admin.luxury.requests.show', compact('packageRequest', 'servicesDetails'));
    }

    /**
     * ADMINISTRATION - Afficher le formulaire d'édition
     */
    public function edit(LuxuryPackageRequest $packageRequest)
    {
        $services = LuxuryService::actif()->get()->groupBy('categorie');
        $categories = LuxuryService::getCategories();
        $packages = LuxuryPackage::actif()->get();

        return view('admin.luxury.requests.edit', compact('packageRequest', 'services', 'categories', 'packages'));
    }

    /**
     * ADMINISTRATION - Mettre à jour une demande
     */
    public function update(Request $request, LuxuryPackageRequest $packageRequest)
    {
        $validated = $request->validate([
            'titre_demande' => 'required|string|max:255',
            'description_demande' => 'required|string|max:2000',
            'services_souhaites' => 'required|array|min:1',
            'services_souhaites.*.service_id' => 'required|exists:luxury_services,id',
            'services_souhaites.*.quantite' => 'required|integer|min:1',
            'services_souhaites.*.duration' => 'nullable|numeric|min:0.5',
            'services_souhaites.*.options' => 'nullable|array',
            'personnalisations_demandees' => 'nullable|array',
            'date_debut_souhaitee' => 'required|date',
            'date_fin_souhaitee' => 'nullable|date|after:date_debut_souhaitee',
            'destination_principale' => 'required|string|max:255',
            'destinations_multiples' => 'nullable|array',
            'nombre_personnes' => 'required|integer|min:1|max:50',
            'budget_estime' => 'nullable|numeric|min:0',
            'prix_propose' => 'nullable|numeric|min:0',
            'prix_final' => 'nullable|numeric|min:0',
            'statut' => 'required|in:nouvelle,en_analyse,devis_envoye,en_negociation,confirme,en_preparation,en_cours,termine,annule',
            'priorite' => 'required|in:normale,urgente,vip',
            'concierge_assigne' => 'nullable|string|max:255',
            'notes_internes' => 'nullable|string',
            'date_expiration_devis' => 'nullable|date|after:now'
        ]);

        try {
            $oldStatut = $packageRequest->statut;
            $packageRequest->update($validated);

            // Recalculer le prix proposé si les services ont changé
            if ($packageRequest->wasChanged('services_souhaites')) {
                $prixCalcule = $packageRequest->calculateEstimatedPrice();
                if (!$validated['prix_propose']) {
                    $packageRequest->update(['prix_propose' => $prixCalcule]);
                }
            }

            // Log du changement de statut
            if ($oldStatut !== $validated['statut']) {
                $packageRequest->addNote("Statut modifié de {$oldStatut} vers {$validated['statut']}");
            }

            return redirect()->route('admin.luxury.requests.show', $packageRequest)
                           ->with('success', 'Demande mise à jour avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la mise à jour de la demande.');
        }
    }

    /**
     * ADMINISTRATION - Mettre à jour rapidement le statut
     */
    public function updateStatus(Request $request, LuxuryPackageRequest $packageRequest)
    {
        $request->validate([
            'statut' => 'required|in:nouvelle,en_analyse,devis_envoye,en_negociation,confirme,en_preparation,en_cours,termine,annule',
            'notes' => 'nullable|string'
        ]);

        try {
            $packageRequest->updateStatus($request->statut, $request->notes);

            return redirect()->route('admin.luxury.requests.show', $packageRequest)
                           ->with('success', 'Statut mis à jour avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la mise à jour du statut.');
        }
    }

    /**
     * ADMINISTRATION - Assigner un concierge
     */
    public function assignConcierge(Request $request, LuxuryPackageRequest $packageRequest)
    {
        $request->validate([
            'concierge_assigne' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $packageRequest->assignConcierge($request->concierge_assigne, $request->notes);

            return redirect()->route('admin.luxury.requests.show', $packageRequest)
                           ->with('success', 'Concierge assigné avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de l\'assignation du concierge.');
        }
    }

    /**
     * ADMINISTRATION - Ajouter un service à la demande
     */
    public function addService(Request $request, LuxuryPackageRequest $packageRequest)
    {
        $request->validate([
            'service_id' => 'required|exists:luxury_services,id',
            'quantite' => 'required|integer|min:1',
            'duration' => 'nullable|numeric|min:0.5',
            'options' => 'nullable|array'
        ]);

        if (!$packageRequest->canBeModified()) {
            return redirect()->back()
                           ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        try {
            $packageRequest->addService(
                $request->service_id,
                $request->quantite,
                $request->duration ?? 1,
                $request->options ?? []
            );

            return redirect()->back()
                           ->with('success', 'Service ajouté à la demande avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de l\'ajout du service.');
        }
    }

    /**
     * ADMINISTRATION - Retirer un service de la demande
     */
    public function removeService(Request $request, LuxuryPackageRequest $packageRequest)
    {
        $request->validate([
            'service_id' => 'required|exists:luxury_services,id'
        ]);

        if (!$packageRequest->canBeModified()) {
            return redirect()->back()
                           ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        try {
            $packageRequest->removeService($request->service_id);

            return redirect()->back()
                           ->with('success', 'Service retiré de la demande avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression du service.');
        }
    }

    /**
     * ADMINISTRATION - Supprimer une demande
     */
    public function destroy(LuxuryPackageRequest $packageRequest)
    {
        try {
            if (!$packageRequest->canBeCancelled()) {
                return redirect()->back()
                               ->with('error', 'Cette demande ne peut pas être supprimée.');
            }

            $packageRequest->delete();

            return redirect()->route('admin.luxury.requests.index')
                           ->with('success', 'Demande supprimée avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression de la demande.');
        }
    }

    /**
     * ADMINISTRATION - Dupliquer une demande
     */
    public function duplicate(LuxuryPackageRequest $packageRequest)
    {
        try {
            $copy = $packageRequest->duplicate();

            return redirect()->route('admin.luxury.requests.edit', $copy)
                           ->with('success', 'Demande dupliquée avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la duplication de la demande.');
        }
    }

    /**
     * ADMINISTRATION - Créer un package à partir de la demande
     */
    public function createPackageFromRequest(LuxuryPackageRequest $packageRequest)
    {
        try {
            $package = $packageRequest->createPersonalizedPackage();

            return redirect()->route('admin.luxury.packages.show', $package)
                           ->with('success', 'Package créé à partir de la demande avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la création du package.');
        }
    }

    /**
     * PUBLIC - Vérifier le statut d'une demande
     */
    public function checkStatus($reference)
    {
        $packageRequest = LuxuryPackageRequest::where('reference', $reference)->first();

        if (!$packageRequest) {
            abort(404, 'Demande non trouvée');
        }

        $servicesDetails = $packageRequest->getServicesDetails();

        return view('public.luxury.request-status', compact('packageRequest', 'servicesDetails'));
    }

    /**
     * ADMINISTRATION - Dashboard des demandes
     */
    public function dashboard()
    {
        $stats = [
            'total_demandes' => LuxuryPackageRequest::count(),
            'nouvelles_demandes' => LuxuryPackageRequest::where('statut', 'nouvelle')->count(),
            'demandes_urgentes' => LuxuryPackageRequest::urgentes()->count(),
            'revenus_potentiels' => LuxuryPackageRequest::whereNotNull('prix_propose')->sum('prix_propose'),
            'revenus_confirmes' => LuxuryPackageRequest::whereNotNull('prix_final')->sum('prix_final'),
            'taux_conversion' => $this->calculateConversionRate(),
            'demandes_par_statut' => LuxuryPackageRequest::select('statut', DB::raw('count(*) as total'))
                                                       ->groupBy('statut')
                                                       ->pluck('total', 'statut'),
            'demandes_recentes' => LuxuryPackageRequest::latest()->limit(10)->get(),
            'concierges_actifs' => LuxuryPackageRequest::whereNotNull('concierge_assigne')
                                                     ->distinct('concierge_assigne')
                                                     ->count('concierge_assigne')
        ];

        return view('admin.luxury.requests.dashboard', compact('stats'));
    }

    /**
     * Calculer le taux de conversion
     */
    private function calculateConversionRate()
    {
        $totalDemandes = LuxuryPackageRequest::count();
        $demandesConfirmees = LuxuryPackageRequest::whereIn('statut', ['confirme', 'en_preparation', 'en_cours', 'termine'])->count();

        return $totalDemandes > 0 ? round(($demandesConfirmees / $totalDemandes) * 100, 2) : 0;
    }
}
