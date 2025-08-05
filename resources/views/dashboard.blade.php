@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="toolbar" id="kt_toolbar">
                        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard</h1>
                            </div>
                        </div>
                    </div>

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div class="container-fluid mt-10">

                            <!--begin::Row-->
                            <div class="row gy-5 g-xl-2">
                                <!-- Statistiques générales -->
                                <div class="col-xl-6">
                                    <div class="card card-xl-stretch">
                                        <div class="card-header border-0 bg-danger py-5">
                                            <h3 class="card-title fw-bolder text-white">Statistiques Réservations</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="mixed-widget-2-chart card-rounded-bottom bg-danger"
                                                style="height: 150px"></div>
                                            <div class="card-p mt-n20 position-relative">
                                                <div class="row g-0">
                                                    <div class="col bg-light-info px-6 py-8 rounded-2 me-7 mb-7">
                                                        <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                                                            <i class="bi bi-collection fs-1 text-info"></i>
                                                        </span>
                                                        <div class="fw-bold fs-6 text-info">Total Réservations</div>
                                                        <div class="fs-2 fw-bolder">{{ $totalReservations }}</div>
                                                    </div>
                                                    <div class="col bg-light-warning px-6 py-8 rounded-2 mb-7">
                                                        <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                                                        </span>
                                                        <div class="fw-bold fs-6 text-warning">En Attente</div>
                                                        <div class="fs-2 fw-bolder">{{ $pendingReservations }}</div>
                                                    </div>
                                                </div>

                                                <div class="row g-0">
                                                    <div class="col bg-light-success px-6 py-8 rounded-2 me-7">
                                                        <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                                            <i class="bi bi-people fs-1 text-success"></i>
                                                        </span>
                                                        <div class="fw-bold fs-6 text-success">Clients Uniques</div>
                                                        <div class="fs-2 fw-bolder">{{ $uniqueClients }}</div>
                                                    </div>
                                                    <div class="col bg-light-primary px-6 py-8 rounded-2">
                                                        <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                                            <i class="bi bi-bar-chart-line fs-1 text-primary"></i>
                                                        </span>
                                                        <div class="fw-bold fs-6 text-primary">Moyenne Réservations/Jour
                                                        </div>
                                                        <div class="fs-2 fw-bolder">
                                                            {{ number_format($avgReservationsPerMonth, 1) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistiques par type d’avion -->
                                <div class="col-xl-6">
                                    <div class="card card-xl-stretch mb-xl-8">
                                        <div class="card-header border-0 bg-info py-5">
                                            <h3 class="card-title fw-bold text-white">Réservations par type d’avion</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="planeTypeChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Row-->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const planeTypeCtx = document.getElementById('planeTypeChart').getContext('2d');

        const planeTypeChart = new Chart(planeTypeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($reservationsByPlaneType->pluck('plane_type')) !!},
                datasets: [{
                    label: 'Nombre de réservations',
                    data: {!! json_encode($reservationsByPlaneType->pluck('total')) !!},
                    backgroundColor: '#30b9b4',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endsection
