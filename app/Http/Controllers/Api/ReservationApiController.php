<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Models\Jet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationApiController extends Controller
{
    /**
     * Créer une nouvelle réservation depuis React
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'departure_location' => 'required|string|max:255',
            'arrival_location' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'arrival_date' => 'required|date|after:departure_date',
            'passengers' => 'required|integer|min:1|max:50',
            'jet_id' => 'required|exists:jets,id',
            'message' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $jet = Jet::findOrFail($validated['jet_id']);

            // Vérifier la capacité
            if ($validated['passengers'] > $jet->capacite) {
                return response()->json([
                    'success' => false,
                    'message' => "Le jet peut accueillir maximum {$jet->capacite} passagers"
                ], 422);
            }

            // Vérifier la disponibilité
            if (!$jet->isAvailableForDates($validated['departure_date'], $validated['arrival_date'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jet non disponible pour ces dates'
                ], 422);
            }

            $reservation = Reservation::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'departure_location' => $validated['departure_location'],
                'arrival_location' => $validated['arrival_location'],
                'departure_date' => $validated['departure_date'],
                'arrival_date' => $validated['arrival_date'],
                'passengers' => $validated['passengers'],
                'jet_id' => $validated['jet_id'],
                'status' => 'pending',
                'message' => $validated['message']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Réservation créée avec succès',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                    'status' => $reservation->status
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la réservation'
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'une réservation
     */
    public function checkStatus(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $reservation = Reservation::with('jet:id,nom,modele,image')
                                  ->where('id', $id)
                                  ->where('email', $request->email)
                                  ->first();

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $reservation->id,
                'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                'full_name' => $reservation->full_name,
                'email' => $reservation->email,
                'departure_location' => $reservation->departure_location,
                'arrival_location' => $reservation->arrival_location,
                'departure_date' => $reservation->departure_date->format('Y-m-d'),
                'arrival_date' => $reservation->arrival_date->format('Y-m-d'),
                'passengers' => $reservation->passengers,
                'status' => $reservation->status,
                'jet' => [
                    'nom' => $reservation->jet->nom,
                    'modele' => $reservation->jet->modele,
                    'image_url' => $reservation->jet->image_url
                ]
            ]
        ]);
    }
}