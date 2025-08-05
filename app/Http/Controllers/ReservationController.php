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
            'planeType' => 'required|string|max:255',
            'arrivalDate' => 'required|date',
            'departureDate' => 'required|date',
            'passengers' => 'required|integer|min:1|max:10',
                'jet_id' => 'nullable|exists:jets,id',
        ]);

        $reservation = Reservation::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'departure_location' => $validated['departureLocation'],
            'arrival_location' => $validated['arrivalLocation'],
            'plane_type' => $validated['planeType'],
            'arrival_date' => $validated['arrivalDate'],
            'departure_date' => $validated['departureDate'],
            'passengers' => $validated['passengers'],
                'jet_id' => $validated['jet_id'] ?? null, 
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation,
        ], 201);
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

    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    public function downloadPDF(Reservation $reservation)
    {
        $pdf = Pdf::loadView('Admin.reservations.ticket', compact('reservation'));
        return $pdf->download('ticket_reservation_' . $reservation->id . '.pdf');
    }

}
