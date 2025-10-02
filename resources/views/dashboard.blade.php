@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble de votre activité')

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="{{ route('reservations.index', ['status' => 'pending']) }}" class="btn btn-sm btn-light-warning">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"/>
                </svg>
            </span>
            {{ $kpis['pending_reservations']['value'] }} En Attente
        </a>
        <a href="{{ route('jets.create') }}" class="btn btn-sm btn-primary">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                </svg>
            </span>
            Ajouter Jet
        </a>
    </div>
@endsection

@section('content')
    {{-- KPIs Cards Row --}}
    <div class="row g-5 g-xl-8 mb-8">
        {{-- Total Réservations --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-xl-stretch">
                <div class="card-body d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-primary">
                            <i class="bi bi-calendar-check fs-1 text-primary"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bolder text-gray-800 me-2">{{ $kpis['total_reservations']['value'] }}</span>
                            @if($kpis['total_reservations']['growth'] > 0)
                                <span class="badge badge-light-success fs-base">
                                    <i class="bi bi-arrow-up"></i> +{{ $kpis['total_reservations']['growth'] }}%
                                </span>
                            @elseif($kpis['total_reservations']['growth'] < 0)
                                <span class="badge badge-light-danger fs-base">
                                    <i class="bi bi-arrow-down"></i> {{ $kpis['total_reservations']['growth'] }}%
                                </span>
                            @endif
                        </div>
                        <span class="text-muted fw-bold fs-6">Total Réservations</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Réservations en Attente --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-xl-stretch">
                <div class="card-body d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-warning">
                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bolder text-gray-800 me-2">{{ $kpis['pending_reservations']['value'] }}</span>
                            @if($kpis['pending_reservations']['urgent'] > 0)
                                <span class="badge badge-light-danger fs-base">{{ $kpis['pending_reservations']['urgent'] }} urgentes</span>
                            @endif
                        </div>
                        <span class="text-muted fw-bold fs-6">En Attente de Confirmation</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jets Disponibles --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-xl-stretch">
                <div class="card-body d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-success">
                            <i class="bi bi-airplane fs-1 text-success"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bolder text-gray-800 me-2">{{ $kpis['available_jets']['value'] }}</span>
                            <span class="fs-6 text-muted">/ {{ $kpis['available_jets']['total'] }}</span>
                        </div>
                        <span class="text-muted fw-bold fs-6">Jets Disponibles ({{ $kpis['available_jets']['percentage'] }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenus du Mois --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-xl-stretch">
                <div class="card-body d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-info">
                            <i class="bi bi-currency-euro fs-1 text-info"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bolder text-gray-800 me-2">{{ number_format($kpis['estimated_revenue']['value'], 0, ',', ' ') }}GH₵</span>
                        </div>
                        <span class="text-muted fw-bold fs-6">Revenus Estimés ce Mois</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-5 g-xl-8 mb-8">
        {{-- Évolution Mensuelle --}}
        <div class="col-xl-8">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Évolution des Réservations</span>
                        <span class="text-muted fw-bold fs-7">Tendance sur l'année {{ date('Y') }}</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fs-7">{{ $reservations['average_per_month'] }} réservations/mois en moyenne</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyEvolutionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Répartition par Statut --}}
        <div class="col-xl-4">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Statut des Réservations</span>
                        <span class="text-muted fw-bold fs-7">Répartition actuelle</span>
                    </h3>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="statusDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance & Activity Row --}}
    <div class="row g-5 g-xl-8 mb-8">
        {{-- Jets les Plus Populaires --}}
        <div class="col-xl-6">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Jets les Plus Demandés</span>
                        <span class="text-muted fw-bold fs-7">Top 5 par nombre de réservations</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    @forelse($top_performers['most_booked_jets'] as $index => $jet)
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-40px me-5">
                                <span class="symbol-label bg-light-{{ ['primary', 'success', 'warning', 'info', 'dark'][$index] }}">
                                    <span class="fs-4 fw-bold text-{{ ['primary', 'success', 'warning', 'info', 'dark'][$index] }}">{{ $index + 1 }}</span>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-dark fw-bolder text-hover-primary fs-6 mb-1">{{ $jet->nom }}</div>
                                <span class="text-muted fw-bold fs-7">{{ $jet->modele }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge badge-light-primary fs-8">{{ $jet->reservations_count }} réservations</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="bi bi-airplane text-muted fs-2x mb-3"></i>
                            <div class="text-muted">Aucune donnée disponible</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Activité Récente --}}
        <div class="col-xl-6">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Activité Récente</span>
                        <span class="text-muted fw-bold fs-7">Dernières réservations</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-light">Voir tout</a>
                    </div>
                </div>
                <div class="card-body py-3">
                    @forelse($recent_activity['recent_reservations'] as $reservation)
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label bg-light-{{ $reservation['status'] == 'confirmed' ? 'success' : ($reservation['status'] == 'pending' ? 'warning' : 'danger') }}">
                                    @if($reservation['status'] == 'confirmed')
                                        <i class="bi bi-check-circle text-success"></i>
                                    @elseif($reservation['status'] == 'pending')
                                        <i class="bi bi-clock text-warning"></i>
                                    @else
                                        <i class="bi bi-x-circle text-danger"></i>
                                    @endif
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-dark fw-bolder fs-6 mb-1">{{ $reservation['client_name'] }}</div>
                                <div class="text-muted fw-bold fs-7">
                                    {{ $reservation['route'] }}
                                    @if($reservation['jet_name'])
                                        • {{ $reservation['jet_name'] }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-dark fw-bolder fs-7">{{ $reservation['departure_date']->format('d/m/Y') }}</div>
                                <div class="text-muted fs-8">{{ $reservation['created_at']->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="bi bi-calendar-x text-muted fs-2x mb-3"></i>
                            <div class="text-muted">Aucune activité récente</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="row g-5 g-xl-8">
        {{-- Statistiques Rapides --}}
        <div class="col-xl-4">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Statistiques Rapides</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-5">
                        <i class="bi bi-people text-primary fs-1 me-4"></i>
                        <div>
                            <div class="text-dark fw-bolder fs-2">{{ $kpis['unique_clients']['value'] }}</div>
                            <div class="text-muted fs-7">Clients Uniques</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-5">
                        <i class="bi bi-percent text-success fs-1 me-4"></i>
                        <div>
                            <div class="text-dark fw-bolder fs-2">{{ $kpis['confirmation_rate']['value'] }}%</div>
                            <div class="text-muted fs-7">Taux de Confirmation</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up text-info fs-1 me-4"></i>
                        <div>
                            <div class="text-dark fw-bolder fs-2">{{ $reservations['average_per_month'] }}</div>
                            <div class="text-muted fs-7">Réservations/Mois</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jets par Catégorie --}}
        <div class="col-xl-8">
            <div class="card card-xl-stretch">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Répartition Jets par Catégorie</span>
                        <span class="text-muted fw-bold fs-7">Distribution de votre flotte</span>
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="jetCategoriesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration globale des graphiques
    Chart.defaults.font.family = 'Poppins';
    Chart.defaults.color = '#7E8299';

    // Graphique Évolution Mensuelle
    const monthlyCtx = document.getElementById('monthlyEvolutionChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($charts['monthly_reservations']['labels']) !!},
            datasets: [{
                label: 'Réservations',
                data: {!! json_encode($charts['monthly_reservations']['data']) !!},
                borderColor: '#009EF7',
                backgroundColor: 'rgba(0, 158, 247, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#009EF7',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#009EF7',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(126, 130, 153, 0.1)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Graphique Répartition par Statut
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($charts['reservations_by_status']['labels']) !!},
            datasets: [{
                data: {!! json_encode($charts['reservations_by_status']['data']) !!},
                backgroundColor: {!! json_encode($charts['reservations_by_status']['colors']) !!},
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#009EF7',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.parsed / total) * 100);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Graphique Jets par Catégorie
    const jetCategoriesCtx = document.getElementById('jetCategoriesChart').getContext('2d');
    const categoriesData = {!! json_encode($jets['by_category']) !!};
    
    new Chart(jetCategoriesCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(categoriesData),
            datasets: [{
                label: 'Nombre de jets',
                data: Object.values(categoriesData),
                backgroundColor: [
                    '#009EF7',
                    '#50CD89', 
                    '#FFC700',
                    '#F1416C',
                    '#7239EA'
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#009EF7',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(126, 130, 153, 0.1)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush