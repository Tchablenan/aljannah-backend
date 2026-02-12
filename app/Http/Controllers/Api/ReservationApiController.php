<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Models\Jet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReservationCreatedNotification;
use App\Http\Requests\StoreReservationRequest;

class ReservationApiController extends Controller
{
    /**
     * Créer une nouvelle réservation depuis React
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();


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

            // Calcul des taxes (Phase 1)
            $taxCalculator = new \App\Services\TaxCalculator();
            $taxDetails = $taxCalculator->calculateGhanaTaxes((float)$jet->prix);

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
                'message' => $validated['message'],
                // Phase 1 : APIS & Luggage
                'passport_number' => $validated['passport_number'] ?? null,
                'passport_expiry' => $validated['passport_expiry'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'luggage_count' => $validated['luggage_count'] ?? 0,
                'luggage_weight_kg' => $validated['luggage_weight_kg'] ?? 0.00,
                'data_protection_consent' => $validated['data_protection_consent'] ?? false,
                // Phase 1 : Taxes
                'base_price' => $taxDetails['base_price'],
                'nhil_amount' => $taxDetails['nhil'],
                'getfund_amount' => $taxDetails['get_fund'],
                'covid_levy_amount' => $taxDetails['covid_levy'],
                'vat_amount' => $taxDetails['vat'],
                'total_taxes_amount' => $taxDetails['total_taxes'],
                'total_amount_with_taxes' => $taxDetails['total_amount'],
            ]);

            DB::commit();

            // Send notification to the user
            try {
                Notification::send(null, new ReservationCreatedNotification($reservation));
                // Note: Notification::route('mail', $reservation->email) is also valid,
                // but static Notification::route doesn't exist in all Laravel versions the same way.
                // Using Notification facade with route property or simple user notify if auth enabled.
                // Re-verifying how I did it before.
                \Illuminate\Support\Facades\Notification::route('mail', $reservation->email)
                    ->notify(new ReservationCreatedNotification($reservation));
            }
            catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de l'email de confirmation: " . $e->getMessage());
            // Don't fail the request if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Réservation créée avec succès. Devis fiscal Ghana généré.',
                'data' => [
                    'reservation_id' => $reservation->id,
                    'reference' => $reservation->reference,
                    'status' => $reservation->status,
                    'tax_summary' => $taxDetails
                ]
            ], 201);

        }
        catch (\Exception $e) {
            DB::rollback();
            Log::error("Erreur API Réservation: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la réservation: ' . $e->getMessage()
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

    /**
     * Obtenir une estimation du prix avec taxes
     */
    public function previewQuote(Request $request)
    {
        $request->validate([
            'jet_id' => 'required|exists:jets,id'
        ]);

        $jet = Jet::findOrFail($request->jet_id);
        $taxCalculator = new \App\Services\TaxCalculator();
        $taxDetails = $taxCalculator->calculateGhanaTaxes((float)$jet->prix);

        return response()->json([
            'success' => true,
            'data' => $taxDetails
        ]);
    }
}