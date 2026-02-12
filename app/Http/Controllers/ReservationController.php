<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Jet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StoreReservationRequest;
use App\Notifications\ReservationConfirmedNotification;
use App\Notifications\ReservationCancelledNotification;

class ReservationController extends Controller
{

    public function index(Request $request)
    {
        $query = Reservation::with(['jet'])->latest();

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par période
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('departure_date', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('departure_date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('departure_date', now()->month)
                        ->whereYear('departure_date', now()->year);
                    break;
                case 'upcoming':
                    $query->where('departure_date', '>=', now()->toDateString());
                    break;
            }
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('departure_location', 'like', "%{$search}%")
                    ->orWhere('arrival_location', 'like', "%{$search}%");
            });
        }

        $reservations = $query->paginate(15)->withQueryString();

        return view('admin.reservations.index', compact('reservations'));
    }
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $jets = Jet::disponible()->get();
        return view('admin.reservations.create', compact('jets'));
    }
    /**
     * Store a newly created reservation in storage.
     */
    /**
     * Store a newly created reservation in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();

        $jet = Jet::findOrFail($validated['jet_id']);

        // Vérifier la capacité
        if ($validated['passengers'] > $jet->capacite) {
            return redirect()->back()
                ->withErrors(['passengers' => "Nombre de passagers dépasse la capacité du jet ({$jet->capacite} max)"])
                ->withInput();
        }

        // Vérifier la disponibilité pour ces dates
        if (!$this->isJetAvailable($validated['jet_id'], $validated['departure_date'], $validated['arrival_date'])) {
            return redirect()->back()
                ->withErrors(['jet_id' => 'Jet non disponible pour ces dates'])
                ->withInput();
        }

        // Calcul des taxes (Phase 1)
        $taxCalculator = new \App\Services\TaxCalculator();
        $taxDetails = $taxCalculator->calculateGhanaTaxes((float)$jet->prix);

        // Créer la réservation dans une transaction
        DB::beginTransaction();
        try {
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
                'message' => $validated['message'] ?? null,
                // APIS & Luggage
                'passport_number' => $validated['passport_number'] ?? null,
                'passport_expiry' => $validated['passport_expiry'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'luggage_count' => $validated['luggage_count'] ?? 0,
                'luggage_weight_kg' => $validated['luggage_weight_kg'] ?? 0.00,
                'data_protection_consent' => true,
                // Taxes (Ghana Finance)
                'base_price' => $taxDetails['base_price'],
                'nhil_amount' => $taxDetails['nhil'],
                'getfund_amount' => $taxDetails['get_fund'],
                'covid_levy_amount' => $taxDetails['covid_levy'],
                'vat_amount' => $taxDetails['vat'],
                'total_taxes_amount' => $taxDetails['total_taxes'],
                'total_amount_with_taxes' => $taxDetails['total_amount'],
            ]);

            DB::commit();

            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Réservation créée avec succès ! Les taxes ghanéennes (VAT, NHIL, GETFund) ont été calculées pour le devis.');
        }
        catch (\Exception $e) {
            DB::rollback();
            \Log::error("Erreur création réservation: " . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création de la réservation'])
                ->withInput();
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

        return view('admin.reservations.show', compact('reservation'));
    }
    /**
     * Check reservation status by email and reference
     */

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $jets = Jet::disponible()->get();

        return view('admin.reservations.edit', compact('reservation', 'jets'));
    }
    /**
     * Mettre à jour une réservation
     */
    public function update(StoreReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $validated = $request->validated();

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation mise à jour avec succès !');
    }
    /**
     * Mettre à jour le statut d'une réservation
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $reservation = Reservation::with('jet')->findOrFail($id);
        $oldStatus = $reservation->status;
        $newStatus = $request->status;

        // Mettre à jour le statut
        $reservation->update([
            'status' => $newStatus,
            'status_updated_at' => now(),
        ]);

        // Envoyer une notification email au client selon le nouveau statut
        $this->sendStatusNotification($reservation, $newStatus);

        // Rediriger vers la vue de confirmation avec l'action effectuée
        return redirect()
            ->route('reservations.confirmation', $reservation->id)
            ->with('action', $newStatus)
            ->with('success', $this->getSuccessMessage($newStatus));
    }

    /**
     * Afficher la page de confirmation d'action
     */
    public function confirmation($id)
    {
        $reservation = Reservation::with('jet')->findOrFail($id);

        return view('admin.reservations.confirmation', compact('reservation'));
    }
    /**
     * Supprimer une réservation
     */


    /**
     * Vérifier le statut d'une réservation (page publique)
     */
    public function checkStatusPage()
    {
        return view('public.reservations.check-status');
    }

    /**
     * Traiter la vérification de statut
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reference' => 'required|string'
        ]);

        // Extraire l'ID de la référence (REF-000001 -> 1)
        $referenceNumber = str_replace(['REF-', 'ref-'], '', strtolower($request->reference));
        $id = (int)ltrim($referenceNumber, '0');

        $reservation = Reservation::where('id', $id)
            ->where('email', $request->email)
            ->with('jet:id,nom,modele,image')
            ->first();

        if (!$reservation) {
            return redirect()->back()
                ->withErrors(['error' => 'Réservation non trouvée. Vérifiez votre email et référence.'])
                ->withInput();
        }

        return view('public.reservations.status', compact('reservation'));
    }
    /**
     * Télécharger le PDF d'une réservation
     */
    /**
     * Télécharger le PDF du ticket de réservation
     */
    public function downloadPDF(Reservation $reservation)
    {
        try {
            // Charger la réservation avec ses relations
            $reservation->load('jet');

            // Configuration du PDF
            $pdf = Pdf::loadView('admin.reservations.ticket', compact('reservation'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'debugKeepTemp' => false,
            ]);

            // Nom du fichier
            $filename = 'ticket_reservation_' . $reservation->id . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        }
        catch (\Exception $e) {
            // Log l'erreur
            \Log::error('Erreur génération PDF réservation: ' . $e->getMessage(), [
                'reservation_id' => $reservation->id,
                'trace' => $e->getTraceAsString()
            ]);

            // Retourner une erreur utilisateur
            return redirect()->back()->with('error', 'Erreur lors de la génération du PDF. Veuillez réessayer.');
        }
    }

    /**
     * Prévisualiser le ticket (pour déboguer)
     */
    public function previewTicket(Reservation $reservation)
    {
        $reservation->load('jet');
        return view('admin.reservations.ticket', compact('reservation'));
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
            return redirect()->back()
                ->withErrors(['error' => 'Réservation non trouvée ou non annulable. Seules les réservations en attente peuvent être annulées.']);
        }

        // Vérifier si l'annulation est encore possible (ex: pas moins de 24h avant)
        $hoursUntilDeparture = now()->diffInHours($reservation->departure_date);
        if ($hoursUntilDeparture < 24) {
            return redirect()->back()
                ->withErrors(['error' => 'L\'annulation doit se faire au moins 24h avant le départ.']);
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason
        ]);

        return view('public.reservations.cancelled', compact('reservation'))
            ->with('success', 'Réservation annulée avec succès.');
    }


    /**
     * Vérifier la disponibilité d'un jet
     */
    private function isJetAvailable($jetId, $departureDate, $arrivalDate)
    {
        $conflictingReservations = Reservation::where('jet_id', $jetId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($departureDate, $arrivalDate) {
            $query->whereBetween('departure_date', [$departureDate, $arrivalDate])
                ->orWhereBetween('arrival_date', [$departureDate, $arrivalDate])
                ->orWhere(function ($q) use ($departureDate, $arrivalDate) {
                $q->where('departure_date', '<=', $departureDate)
                    ->where('arrival_date', '>=', $arrivalDate);
            }
            );
        })
            ->exists();

        return !$conflictingReservations;
    }

    /**
     * Envoyer une notification de changement de statut
     */
    private function sendStatusNotification(Reservation $reservation, $status)
    {
        // Logique d'envoi d'email selon le statut
        try {
            switch ($status) {
                case 'confirmed':
                    Notification::route('mail', $reservation->email)
                        ->notify(new ReservationConfirmedNotification($reservation));
                    break;
                case 'cancelled':
                    Notification::route('mail', $reservation->email)
                        ->notify(new ReservationCancelledNotification($reservation));
                    break;
                case 'completed':
                    // Optionnel: Notification de remerciement après voyage
                    // Notification::route('mail', $reservation->email)
                    //    ->notify(new ReservationCompletedNotification($reservation));
                    break;
            }
        }
        catch (\Exception $e) {
            Log::error("Erreur envoi notification statut $status: " . $e->getMessage());
        }
    }

    /**
     * Obtenir le message de succès selon l'action
     */
    private function getSuccessMessage($status)
    {
        $messages = [
            'confirmed' => 'Réservation confirmée avec succès. Le client a été notifié par email.',
            'cancelled' => 'Réservation annulée. Le client a été informé de l\'annulation.',
            'completed' => 'Réservation marquée comme terminée.',
            'pending' => 'Réservation remise en attente.'
        ];

        return $messages[$status] ?? 'Statut mis à jour avec succès.';
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