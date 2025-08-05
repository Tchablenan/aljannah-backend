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
    public function index()
    {
        // Ajouter l'URL complète de l'image à chaque jet
        $jets = Jet::all()->map(function ($jet) {
            // Vérifier si une image est présente et ajouter l'URL
            $jet->image_url = $jet->image ? asset('storage/' . $jet->image) : 'https://via.placeholder.com/400x200?text=No+Image'; // Fallback image
            return $jet;
        });

        return response()->json($jets);
    }

    /**
     * Display the specified jet with its image URL.
     *
     * @param Jet $jet
     * @return \Illuminate\Http\JsonResponse
     */


    /*public function show($id)
    {
        $jet = Jet::findOrFail($id); // Charge l’objet Jet correctement

        $jet->image_url = filter_var($jet->image, FILTER_VALIDATE_URL)
            ? $jet->image
            : ($jet->image ? asset('storage/' . $jet->image) : asset('assets/media/stock/default-jet.jpg'));

        return response()->json([
            'id' => $jet->id,
            'nom' => $jet->nom,
            'modele' => $jet->modele,
            'capacite' => $jet->capacite,
            'description' => $jet->description,
            'prix' => $jet->prix,
            'image_url' => $jet->image_url,
        ]);
    }*/
    public function show($id)
    {
        $jet = Jet::findOrFail($id); // Charge l’objet Jet correctement

        // Vérifie si l'URL de l'image principale est valide ou non
        $jet->image_url = filter_var($jet->image, FILTER_VALIDATE_URL)
            ? $jet->image
            : ($jet->image ? asset('storage/' . $jet->image) : asset('assets/media/stock/default-jet.jpg'));

        // Récupérer les autres images liées au jet, s'il y en a
        $jet->other_images = $jet->images ? json_decode($jet->images) : [];

        return response()->json([
            'id' => $jet->id,
            'nom' => $jet->nom,
            'modele' => $jet->modele,
            'capacite' => $jet->capacite,
            'description' => $jet->description,
            'prix' => $jet->prix,
            'image_url' => $jet->image_url,  // L'URL de l'image principale
            'other_images' => $jet->other_images,  // Tableau des autres images
        ]);
    }



}
