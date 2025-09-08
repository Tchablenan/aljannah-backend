<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Jet;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache les statistiques pour 5 minutes (performance)
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return $this->calculateStats();
        });

        return view('dashboard', $stats);
    }

    /**
     * Calcule toutes les statistiques du dashboard
     */
    private function calculateStats()
    {
        return [
            // KPIs principaux
            'kpis' => $this->getMainKPIs(),
            
            // Statistiques des réservations
            'reservations' => $this->getReservationStats(),
            
            // Statistiques des jets
            'jets' => $this->getJetStats(),
            
            // Graphiques
            'charts' => $this->getChartData(),
            
            // Activité récente
            'recent_activity' => $this->getRecentActivity(),
            
            // Top performers
            'top_performers' => $this->getTopPerformers()
        ];
    }

    /**
     * KPIs principaux pour les cartes du dashboard
     */
    private function getMainKPIs()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Réservations totales
        $totalReservations = Reservation::count();
        $currentMonthReservations = Reservation::where('created_at', '>=', $currentMonth)->count();
        $previousMonthReservations = Reservation::whereBetween('created_at', [
            $previousMonth, 
            $previousMonth->copy()->endOfMonth()
        ])->count();
        
        // Calcul du pourcentage de croissance
        $reservationGrowth = $previousMonthReservations > 0 
            ? (($currentMonthReservations - $previousMonthReservations) / $previousMonthReservations) * 100 
            : 0;

        // Jets disponibles
        $availableJets = Jet::where('disponible', true)->count();
        $totalJets = Jet::count();
        
        // Clients uniques
        $uniqueClients = Reservation::distinct('email')->count('email');
        $newClientsThisMonth = Reservation::where('created_at', '>=', $currentMonth)
                                         ->distinct('email')
                                         ->count('email');

        // Réservations en attente
        $pendingReservations = Reservation::where('status', 'pending')->count();
        
        // Taux de confirmation
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $confirmationRate = $totalReservations > 0 
            ? ($confirmedReservations / $totalReservations) * 100 
            : 0;

        // Revenus estimés (basé sur les réservations confirmées)
        $estimatedRevenue = $this->calculateEstimatedRevenue();

        return [
            'total_reservations' => [
                'value' => $totalReservations,
                'growth' => round($reservationGrowth, 1),
                'trend' => $reservationGrowth >= 0 ? 'up' : 'down'
            ],
            'pending_reservations' => [
                'value' => $pendingReservations,
                'urgent' => $this->getUrgentReservations(),
                'trend' => $pendingReservations > 5 ? 'warning' : 'normal'
            ],
            'available_jets' => [
                'value' => $availableJets,
                'total' => $totalJets,
                'percentage' => $totalJets > 0 ? round(($availableJets / $totalJets) * 100, 1) : 0
            ],
            'unique_clients' => [
                'value' => $uniqueClients,
                'new_this_month' => $newClientsThisMonth,
                'trend' => 'up'
            ],
            'confirmation_rate' => [
                'value' => round($confirmationRate, 1),
                'trend' => $confirmationRate >= 70 ? 'good' : 'warning'
            ],
            'estimated_revenue' => [
                'value' => $estimatedRevenue,
                'currency' => '€',
                'period' => 'mois'
            ]
        ];
    }

    /**
     * Statistiques détaillées des réservations
     */
    private function getReservationStats()
    {
        return [
            'by_status' => Reservation::select('status', DB::raw('count(*) as count'))
                                    ->groupBy('status')
                                    ->pluck('count', 'status')
                                    ->toArray(),
            
            'by_month' => $this->getMonthlyReservations(),
            
            'by_jet_type' => $this->getReservationsByJetType(),
            
            'average_per_month' => $this->getAverageReservationsPerMonth(),
            
            'peak_months' => $this->getPeakMonths(),
            
            'upcoming_departures' => $this->getUpcomingDepartures()
        ];
    }

    /**
     * Statistiques des jets
     */
    private function getJetStats()
    {
        return [
            'by_category' => Jet::select('categorie', DB::raw('count(*) as count'))
                               ->whereNotNull('categorie')
                               ->groupBy('categorie')
                               ->pluck('count', 'categorie')
                               ->toArray(),
            
            'by_capacity' => $this->getJetsByCapacity(),
            
            'most_popular' => $this->getMostPopularJets(),
            
            'utilization_rate' => $this->getJetUtilizationRate(),
            
            'availability_forecast' => $this->getAvailabilityForecast()
        ];
    }

    /**
     * Données pour les graphiques
     */
    private function getChartData()
    {
        return [
            'monthly_reservations' => $this->getMonthlyReservationsChart(),
            'reservations_by_status' => $this->getReservationsByStatusChart(),
            'jets_utilization' => $this->getJetsUtilizationChart(),
            'client_acquisition' => $this->getClientAcquisitionChart()
        ];
    }

    /**
     * Activité récente
     */
    private function getRecentActivity()
    {
        $recentReservations = Reservation::with('jet:id,nom,modele')
                                        ->latest()
                                        ->take(5)
                                        ->get()
                                        ->map(function ($reservation) {
                                            return [
                                                'id' => $reservation->id,
                                                'client_name' => $reservation->full_name,
                                                'jet_name' => optional($reservation->jet)->nom,
                                                'status' => $reservation->status,
                                                'departure_date' => $reservation->departure_date,
                                                'created_at' => $reservation->created_at,
                                                'route' => $reservation->departure_location . ' → ' . $reservation->arrival_location
                                            ];
                                        });

        return [
            'recent_reservations' => $recentReservations,
            'today_departures' => $this->getTodayDepartures(),
            'pending_actions' => $this->getPendingActions()
        ];
    }

    /**
     * Top performers
     */
    private function getTopPerformers()
    {
        return [
            'most_booked_jets' => $this->getMostBookedJets(),
            'busiest_routes' => $this->getBusiestRoutes(),
            'top_clients' => $this->getTopClients()
        ];
    }

    /**
     * Méthodes utilitaires
     */

    private function getMonthlyReservations()
    {
        $monthlyData = Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->groupBy('month')
                                 ->pluck('count', 'month')
                                 ->toArray();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = $monthlyData[$i] ?? 0;
        }

        return $result;
    }

    private function getReservationsByJetType()
    {
        return Reservation::join('jets', 'reservations.jet_id', '=', 'jets.id')
                         ->select('jets.categorie', DB::raw('count(*) as count'))
                         ->whereNotNull('jets.categorie')
                         ->groupBy('jets.categorie')
                         ->pluck('count', 'categorie')
                         ->toArray();
    }

    private function getAverageReservationsPerMonth()
    {
        $average = Reservation::select(
            DB::raw('COUNT(*) / COUNT(DISTINCT DATE_FORMAT(created_at, "%Y-%m")) as average')
        )->value('average');

        return round($average ?? 0, 2);
    }

    private function getPeakMonths()
    {
        return Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                         ->whereYear('created_at', Carbon::now()->year)
                         ->groupBy('month')
                         ->orderByDesc('count')
                         ->take(3)
                         ->get()
                         ->map(function ($item) {
                             return [
                                 'month' => Carbon::create()->month($item->month)->format('F'),
                                 'count' => $item->count
                             ];
                         });
    }

    private function getUpcomingDepartures()
    {
        return Reservation::with('jet:id,nom')
                         ->where('departure_date', '>=', Carbon::today())
                         ->where('departure_date', '<=', Carbon::today()->addDays(7))
                         ->where('status', 'confirmed')
                         ->orderBy('departure_date')
                         ->take(10)
                         ->get();
    }

    private function getJetsByCapacity()
    {
        return Jet::selectRaw('
                CASE 
                    WHEN capacite <= 6 THEN "Light (1-6)"
                    WHEN capacite <= 12 THEN "Mid-size (7-12)"
                    ELSE "Heavy (13+)"
                END as capacity_range,
                COUNT(*) as count
            ')
            ->groupBy('capacity_range')
            ->pluck('count', 'capacity_range')
            ->toArray();
    }

    private function getMostPopularJets()
    {
        return Jet::withCount(['reservations' => function ($query) {
                    $query->where('status', '!=', 'cancelled');
                }])
                ->orderByDesc('reservations_count')
                ->take(5)
                ->get(['id', 'nom', 'modele', 'reservations_count']);
    }

    private function getJetUtilizationRate()
    {
        $totalJets = Jet::count();
        $activeJets = Jet::whereHas('reservations', function ($query) {
            $query->where('status', '!=', 'cancelled')
                  ->where('departure_date', '>=', Carbon::now()->subMonth());
        })->count();

        return $totalJets > 0 ? round(($activeJets / $totalJets) * 100, 1) : 0;
    }

    private function getAvailabilityForecast()
    {
        // Jets disponibles dans les 30 prochains jours
        $next30Days = Carbon::now()->addDays(30);
        
        $busyJets = Reservation::where('departure_date', '<=', $next30Days)
                              ->where('arrival_date', '>=', Carbon::now())
                              ->where('status', '!=', 'cancelled')
                              ->distinct('jet_id')
                              ->count();

        $totalJets = Jet::where('disponible', true)->count();
        
        return [
            'available' => $totalJets - $busyJets,
            'busy' => $busyJets,
            'percentage_available' => $totalJets > 0 ? round((($totalJets - $busyJets) / $totalJets) * 100, 1) : 0
        ];
    }

    private function getMonthlyReservationsChart()
    {
        $data = $this->getMonthlyReservations();
        return [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => array_values($data)
        ];
    }

    private function getReservationsByStatusChart()
    {
        $statusData = Reservation::select('status', DB::raw('count(*) as count'))
                                ->groupBy('status')
                                ->pluck('count', 'status')
                                ->toArray();

        return [
            'labels' => ['En attente', 'Confirmées', 'Annulées'],
            'data' => [
                $statusData['pending'] ?? 0,
                $statusData['confirmed'] ?? 0,
                $statusData['cancelled'] ?? 0
            ],
            'colors' => ['#FFA726', '#66BB6A', '#EF5350']
        ];
    }

    private function getJetsUtilizationChart()
    {
        return Jet::withCount(['reservations' => function ($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->where('departure_date', '>=', Carbon::now()->subMonth());
                }])
                ->orderByDesc('reservations_count')
                ->take(10)
                ->get(['nom', 'reservations_count'])
                ->pluck('reservations_count', 'nom')
                ->toArray();
    }

    private function getClientAcquisitionChart()
    {
        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $newClients = Reservation::where('created_at', '>=', $month->startOfMonth())
                                   ->where('created_at', '<=', $month->endOfMonth())
                                   ->distinct('email')
                                   ->count('email');
            
            $last6Months[$month->format('M')] = $newClients;
        }

        return [
            'labels' => array_keys($last6Months),
            'data' => array_values($last6Months)
        ];
    }

    private function getTodayDepartures()
    {
        return Reservation::with('jet:id,nom')
                         ->where('departure_date', Carbon::today())
                         ->where('status', 'confirmed')
                         ->orderBy('departure_date')
                         ->get();
    }

    private function getPendingActions()
    {
        return [
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'departures_tomorrow' => Reservation::where('departure_date', Carbon::tomorrow())
                                              ->where('status', 'confirmed')
                                              ->count(),
            'jets_maintenance' => Jet::where('disponible', false)->count()
        ];
    }

    private function getMostBookedJets()
    {
        return Jet::withCount(['reservations' => function ($query) {
                    $query->where('status', '!=', 'cancelled');
                }])
                ->having('reservations_count', '>', 0)
                ->orderByDesc('reservations_count')
                ->take(5)
                ->get(['nom', 'modele', 'reservations_count']);
    }

    private function getBusiestRoutes()
    {
        return Reservation::select(
                    DB::raw('CONCAT(departure_location, " → ", arrival_location) as route'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('status', '!=', 'cancelled')
                ->groupBy('departure_location', 'arrival_location')
                ->orderByDesc('count')
                ->take(5)
                ->get();
    }

    private function getTopClients()
    {
        return Reservation::select('email', DB::raw('COUNT(*) as reservations_count'))
                         ->where('status', '!=', 'cancelled')
                         ->groupBy('email')
                         ->orderByDesc('reservations_count')
                         ->take(5)
                         ->get();
    }

    private function getUrgentReservations()
    {
        return Reservation::where('status', 'pending')
                         ->where('departure_date', '<=', Carbon::now()->addDays(3))
                         ->count();
    }

    private function calculateEstimatedRevenue()
    {
        return Reservation::join('jets', 'reservations.jet_id', '=', 'jets.id')
                         ->where('reservations.status', 'confirmed')
                         ->whereMonth('reservations.created_at', Carbon::now()->month)
                         ->sum('jets.prix');
    }
}