<?php

namespace App\Http\Controllers\Api;

use App\Models\Jet;
use App\Http\Controllers\Controller;

class JetApiController extends Controller
{
    /**
     * Display a listing of jets with image URLs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Jet::query()->disponible(); // Seulement les jets disponibles

        // Filtres optionnels
        if ($request->filled('capacite_min')) {
            $query->where('capacite', '>=', $request->capacite_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        $jets = $query->select([
            'id', 'nom', 'modele', 'capacite', 'prix', 
            'image', 'localisation', 'categorie'
        ])->paginate(12);

        // Transformer les données pour l'API
        $jets->getCollection()->transform(function ($jet) {
            return [
                'id' => $jet->id,
                'nom' => $jet->nom,
                'modele' => $jet->modele,
                'capacite' => $jet->capacite,
                'prix' => $jet->prix,
                'localisation' => $jet->localisation,
                'categorie' => $jet->categorie,
                'image_url' => $jet->image_url, // Utilise l'accesseur du modèle
                'has_gallery' => !empty($jet->images)
            ];
        });

        return response()->json([
            'data' => $jets->items(),
            'pagination' => [
                'current_page' => $jets->currentPage(),
                'last_page' => $jets->lastPage(),
                'per_page' => $jets->perPage(),
                'total' => $jets->total()
            ]
        ]);
    }

    /**
     * Display the specified jet with its image URL.
     *
     * @param Jet $jet
     * @return \Illuminate\Http\JsonResponse
     */



     /**
     * Display the specified jet with full details
     */
    public function show($id)
    {
        $jet = Jet::disponible()
                  ->with(['reservations' => function($query) {
                      $query->where('status', '!=', 'cancelled')
                            ->select('jet_id', 'departure_date', 'arrival_date', 'status');
                  }])
                  ->findOrFail($id);

        return response()->json([
            'id' => $jet->id,
            'nom' => $jet->nom,
            'modele' => $jet->modele,
            'capacite' => $jet->capacite,
            'description' => $jet->description,
            'prix' => $jet->prix,
            'localisation' => $jet->localisation,
            'categorie' => $jet->categorie,
            'autonomie_km' => $jet->autonomie_km,
            'image_url' => $jet->image_url,
            'images_urls' => $jet->images_urls,
            'all_images' => $jet->getAllImages(),
            'reservations_count' => $jet->reservations->count(),
            'next_available_date' => $this->getNextAvailableDate($jet)
        ]);
    }

       /**
     * Check availability for specific dates
     */
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'departure_date' => 'required|date|after:today',
            'arrival_date' => 'required|date|after:departure_date'
        ]);

        $jet = Jet::disponible()->findOrFail($id);
        
        $isAvailable = $jet->isAvailableForDates(
            $request->departure_date, 
            $request->arrival_date
        );

        return response()->json([
            'available' => $isAvailable,
            'jet_id' => $jet->id,
            'dates' => [
                'departure' => $request->departure_date,
                'arrival' => $request->arrival_date
            ],
            'message' => $isAvailable 
                ? 'Jet disponible pour ces dates' 
                : 'Jet non disponible pour ces dates'
        ]);
    }
   /**
     * Search jets with advanced filters
     */
    public function search(Request $request)
    {
        $request->validate([
            'departure_date' => 'nullable|date|after:today',
            'arrival_date' => 'nullable|date|after:departure_date',
            'passengers' => 'nullable|integer|min:1|max:50',
            'departure_location' => 'nullable|string|max:255',
            'arrival_location' => 'nullable|string|max:255',
            'prix_max' => 'nullable|numeric|min:0'
        ]);

        $query = Jet::query()->disponible();

        // Filtrer par capacité si nombre de passagers spécifié
        if ($request->filled('passengers')) {
            $query->where('capacite', '>=', $request->passengers);
        }

        // Filtrer par prix maximum
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Si des dates sont spécifiées, vérifier la disponibilité
        if ($request->filled(['departure_date', 'arrival_date'])) {
            $query->whereDoesntHave('reservations', function ($q) use ($request) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($subQuery) use ($request) {
                      $subQuery->whereBetween('departure_date', [$request->departure_date, $request->arrival_date])
                               ->orWhereBetween('arrival_date', [$request->departure_date, $request->arrival_date])
                               ->orWhere(function ($overlapQuery) use ($request) {
                                   $overlapQuery->where('departure_date', '<=', $request->departure_date)
                                                ->where('arrival_date', '>=', $request->arrival_date);
                               });
                  });
            });
        }

        $jets = $query->paginate(12);

        $jets->getCollection()->transform(function ($jet) {
            return [
                'id' => $jet->id,
                'nom' => $jet->nom,
                'modele' => $jet->modele,
                'capacite' => $jet->capacite,
                'prix' => $jet->prix,
                'localisation' => $jet->localisation,
                'image_url' => $jet->image_url,
                'has_gallery' => !empty($jet->images)
            ];
        });

        return response()->json([
            'data' => $jets->items(),
            'search_params' => $request->only([
                'departure_date', 'arrival_date', 'passengers', 
                'departure_location', 'arrival_location', 'prix_max'
            ]),
            'pagination' => [
                'current_page' => $jets->currentPage(),
                'last_page' => $jets->lastPage(),
                'per_page' => $jets->perPage(),
                'total' => $jets->total()
            ]
        ]);
    }

    /**
     * Get categories with jet counts
     */
    public function categories()
    {
        $categories = Jet::disponible()
                         ->selectRaw('categorie, COUNT(*) as count, MIN(prix) as prix_min, MAX(prix) as prix_max')
                         ->whereNotNull('categorie')
                         ->groupBy('categorie')
                         ->get();

        return response()->json($categories);
    }

    /**
     * Get price range for filtering
     */
    public function priceRange()
    {
        $priceRange = Jet::disponible()
                         ->selectRaw('MIN(prix) as min_prix, MAX(prix) as max_prix')
                         ->first();

        return response()->json($priceRange);
    }

    /**
     * Helper method to get next available date
     */
    private function getNextAvailableDate($jet)
    {
        $nextReservation = $jet->reservations()
                              ->where('status', '!=', 'cancelled')
                              ->where('arrival_date', '>', now())
                              ->orderBy('departure_date')
                              ->first();

        return $nextReservation ? $nextReservation->arrival_date : null;
    }



}
