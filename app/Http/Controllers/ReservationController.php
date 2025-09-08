<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{

    public function index()
    {
        // Récupérer toutes les réservations
        $reservations = Reservation::paginate(10);

        // Retourner la vue 'index' avec les données
        return view('admin.reservations.index', compact('reservations'));
    }
    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'departureLocation' => 'required|string|max:255',
            'arrivalLocation' => 'required|string|max:255',
            'arrivalDate' => 'required|date',
            'departureDate' => 'required|date',
            'passengers' => 'required|integer|min:1|max:10',
                'jet_id' => 'nullable|exists:jets,id',
            'message' => 'nullable|string|max:1000'
        ]);
            // Vérifier que le jet est disponible
            $jet = Jet::disponible()->findOrFail($validated['jet_id']);

                 // Vérifier la capacité
        if ($validated['passengers'] > $jet->capacite) {
            return response()->json([
                'error' => 'Nombre de passagers dépasse la capacité du jet',
                'max_capacity' => $jet->capacite
            ], 422);
        }

        // Vérifier la disponibilité pour ces dates
        if (!$jet->isAvailableForDates($validated['departure_date'], $validated['arrival_date'])) {
            return response()->json([
                'error' => 'Jet non disponible pour ces dates',
                'jet_name' => $jet->nom
            ], 422);
        }


        // Créer la réservation dans une transaction
        DB::beginTransaction();
        try {
            $reservation = Reservation::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'departure_location' => $validated['departure_location'],
                'arrival_location' => $validated['arrival_location'],
                'departure_date' => $validated['departure_date'],
                'arrival_date' => $validated['arrival_date'],
                'passengers' => $validated['passengers'],
                'jet_id' => $validated['jet_id'],
                'status' => 'pending',
                'message' => $validated['message'] ?? null
            ]);
            DB::commit();

                // Charger les relations pour la réponse
                $reservation->load('jet:id,nom,modele,image');

                return response()->json([
                    'message' => 'Réservation créée avec succès',
                    'reservation' => [
                        'id' => $reservation->id,
                        'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                        'status' => $reservation->status,
                        'full_name' => $reservation->full_name,
                        'email' => $reservation->email,
                        'departure_location' => $reservation->departure_location,
                        'arrival_location' => $reservation->arrival_location,
                        'departure_date' => $reservation->departure_date->format('Y-m-d'),
                        'arrival_date' => $reservation->arrival_date->format('Y-m-d'),
                        'passengers' => $reservation->passengers,
                        'jet' => [
                            'id' => $reservation->jet->id,
                            'nom' => $reservation->jet->nom,
                            'modele' => $reservation->jet->modele,
                            'image_url' => $reservation->jet->image_url
                        ],
                        'created_at' => $reservation->created_at->format('Y-m-d H:i')
                    ]
                ], 201);
    
            } catch (\Exception $e) {
                DB::rollback();
                
                return response()->json([
                    'error' => 'Erreur lors de la création de la réservation',
                    'message' => 'Veuillez réessayer'
                ], 500);
            }
        }
    /**
     * Display the specified reservation.
     */
    // Supprimer une réservation
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully!');
    }

    public function show($id)
    {
        $reservation = Reservation::with('jet:id,nom,modele,image,prix')
                                 ->findOrFail($id);

        return response()->json([
            'id' => $reservation->id,
            'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
            'status' => $reservation->status,
            'status_label' => $this->getStatusLabel($reservation->status),
            'full_name' => $reservation->full_name,
            'email' => $reservation->email,
            'departure_location' => $reservation->departure_location,
            'arrival_location' => $reservation->arrival_location,
            'departure_date' => $reservation->departure_date->format('Y-m-d'),
            'arrival_date' => $reservation->arrival_date->format('Y-m-d'),
            'passengers' => $reservation->passengers,
            'message' => $reservation->message,
            'jet' => [
                'id' => $reservation->jet->id,
                'nom' => $reservation->jet->nom,
                'modele' => $reservation->jet->modele,
                'prix' => $reservation->jet->prix,
                'image_url' => $reservation->jet->image_url
            ],
            'created_at' => $reservation->created_at->format('Y-m-d H:i'),
            'updated_at' => $reservation->updated_at->format('Y-m-d H:i')
        ]);
    }
  /**
     * Check reservation status by email and reference
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reference' => 'required|string'
        ]);

        // Extraire l'ID de la référence (REF-000001 -> 1)
        $id = (int) str_replace('REF-', '', $request->reference);

        $reservation = Reservation::where('id', $id)
                                 ->where('email', $request->email)
                                 ->with('jet:id,nom,modele,image')
                                 ->first();

        if (!$reservation) {
            return response()->json([
                'error' => 'Réservation non trouvée',
                'message' => 'Vérifiez votre email et référence'
            ], 404);
        }

        return response()->json([
            'found' => true,
            'reservation' => [
                'id' => $reservation->id,
                'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                'status' => $reservation->status,
                'status_label' => $this->getStatusLabel($reservation->status),
                'full_name' => $reservation->full_name,
                'departure_location' => $reservation->departure_location,
                'arrival_location' => $reservation->arrival_location,
                'departure_date' => $reservation->departure_date->format('Y-m-d'),
                'arrival_date' => $reservation->arrival_date->format('Y-m-d'),
                'passengers' => $reservation->passengers,
                'jet' => [
                    'nom' => $reservation->jet->nom,
                    'modele' => $reservation->jet->modele,
                    'image_url' => $reservation->jet->image_url
                ],
                'created_at' => $reservation->created_at->format('Y-m-d H:i')
            ]
        ]);
    }
    public function downloadPDF(Reservation $reservation)
    {
        $pdf = Pdf::loadView('Admin.reservations.ticket', compact('reservation'));
        return $pdf->download('ticket_reservation_' . $reservation->id . '.pdf');
    }
    
    /**
     * Cancel a reservation (if allowed)
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'reason' => 'nullable|string|max:500'
        ]);

        $reservation = Reservation::where('id', $id)
                                 ->where('email', $request->email)
                                 ->whereIn('status', ['pending']) // Seulement les pending peuvent être annulées
                                 ->first();

        if (!$reservation) {
            return response()->json([
                'error' => 'Réservation non trouvée ou non annulable',
                'message' => 'Seules les réservations en attente peuvent être annulées'
            ], 404);
        }

        // Vérifier si l'annulation est encore possible (ex: pas moins de 24h avant)
        $hoursUntilDeparture = now()->diffInHours($reservation->departure_date);
        if ($hoursUntilDeparture < 24) {
            return response()->json([
                'error' => 'Annulation impossible',
                'message' => 'L\'annulation doit se faire au moins 24h avant le départ'
            ], 422);
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason
        ]);

        return response()->json([
            'message' => 'Réservation annulée avec succès',
            'reservation_id' => $reservation->id,
            'reference' => 'REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT)
        ]);
    }

    /**
     * Helper method to get status labels
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'cancelled' => 'Annulée'
        ];

        return $labels[$status] ?? $status;
    }


}
