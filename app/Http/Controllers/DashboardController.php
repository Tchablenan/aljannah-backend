<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $uniqueClients = Reservation::distinct('email')->count('email');

        // Réservations par mois
        $monthlyReservations = Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Formater pour tous les 12 mois
        $monthlyStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStats[] = $monthlyReservations[$i] ?? 0;
        }
            // Moyenne des réservations en ligne par mois
    $avgReservationsPerMonth = Reservation::select(
        DB::raw('COUNT(*) / COUNT(DISTINCT DATE_FORMAT(created_at, "%Y-%m")) as average')
    )->value('average');

        $reservationsByPlaneType = Reservation::select('plane_type', DB::raw('count(*) as total'))
        ->groupBy('plane_type')
        ->get();

        return view('dashboard', [
            'totalReservations' => $totalReservations,
            'pendingReservations' => $pendingReservations,
            'uniqueClients' => $uniqueClients,
                    'monthlyStats' => json_encode($monthlyStats),
         'avgReservationsPerMonth' => round($avgReservationsPerMonth, 2),
            'reservationsByPlaneType' => $reservationsByPlaneType,
        ]);
    }
}
