{{-- resources/views/admin/luxury/dashboard.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', 'Dashboard Conciergerie de Luxe')

@section('page-title', 'Conciergerie de Luxe')
@section('page-subtitle', 'Tableau de bord général')

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.services.create') }}" class="btn btn-sm btn-primary me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
            </svg>
        </span>
        Nouveau Service
    </a>
    <a href="{{ route('admin.luxury.packages.create') }}" class="btn btn-sm btn-light-primary">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15Z" fill="black"/>
                <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14Z" fill="black"/>
            </svg>
        </span>
        Nouveau Package
    </a>
</div>
@endsection

@section('content')

{{-- Affichage des erreurs si présentes --}}
@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-45 11 14)" fill="black"/>
                <rect x="8" y="12" width="7" height="2" rx="1" transform="rotate(45 8 12)" fill="black"/>
            </svg>
        </span>
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-danger">Attention</h4>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

{{-- Vue d'ensemble - KPIs --}}
<div class="row g-5 g-xl-8 mb-5 mb-xl-8">
    <div class="col-xl-3">
        <div class="card bg-body hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                        <path d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                    </svg>
                </span>
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Services de Luxe</div>
                <div class="fw-bold text-gray-400">{{ $stats['total_services'] ?? 0 }} services</div>
                <div class="fw-bold text-success">{{ $stats['services_actifs'] ?? 0 }} actifs</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card bg-light-warning hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                        <path opacity="0.3" d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                    </svg>
                </span>
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Packages Premium</div>
                <div class="fw-bold text-gray-400">{{ $stats['total_packages'] ?? 0 }} packages</div>
                <div class="fw-bold text-warning">{{ $stats['packages_visibles'] ?? 0 }} publics</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card bg-light-info hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black"/>
                        <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6Z" fill="black"/>
                    </svg>
                </span>
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Demandes Clients</div>
                <div class="fw-bold text-gray-400">{{ $stats['total_demandes'] ?? 0 }} demandes</div>
                <div class="fw-bold text-info">{{ $stats['demandes_ce_mois'] ?? 0 }} ce mois</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card bg-light-success hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9244 21.5526 14.99 21.3735L21.443 6.07449C21.5189 5.87094 21.5351 5.649 21.4898 5.43586C21.4446 5.22272 21.3394 5.02788 21.1889 4.8774C21.0384 4.72692 20.8436 4.62177 20.6304 4.57652C20.4173 4.53127 20.1953 4.54743 19.9918 4.62349L4.69178 11.0765C4.51259 11.1423 4.35788 11.2606 4.24615 11.4167C4.13442 11.5728 4.07097 11.7581 4.06357 11.9498C4.05618 12.1416 4.10511 12.3307 4.20338 12.4946C4.30166 12.6585 4.44519 12.7896 4.61378 12.8735L9.92478 15.4095C10.0926 15.4931 10.2825 15.5176 10.4656 15.4804C10.6487 15.4431 10.8171 15.3457 10.948 15.2015L15.43 8.56949Z" fill="black"/>
                    </svg>
                </span>
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Chiffre d'Affaires</div>
                <div class="fw-bold text-gray-400">$ {{ number_format($stats['revenus_estimes'] ?? 0, 0, ',', ' ') }}</div>
                <div class="fw-bold text-success">Estimé</div>
            </div>
        </div>
    </div>
</div>

{{-- Alertes et Actions rapides --}}
<div class="row g-5 g-xl-8 mb-5 mb-xl-8">
    {{-- Alertes et notifications --}}
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Alertes & Notifications</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Éléments nécessitant votre attention</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.luxury.requests.index', ['status' => 'nouvelle']) }}" class="btn btn-sm btn-light-primary">
                        Voir toutes
                    </a>
                </div>
            </div>
            <div class="card-body pt-5">
                @if(($stats['demandes_urgentes'] ?? 0) > 0)
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-40px me-4">
                        <span class="symbol-label bg-light-danger">
                            <span class="svg-icon svg-icon-2 svg-icon-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-45 11 14)" fill="black"/>
                                    <rect x="8" y="12" width="7" height="2" rx="1" transform="rotate(45 8 12)" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('admin.luxury.requests.index', ['priorite' => 'critique']) }}" class="fs-6 text-gray-800 text-hover-primary fw-bolder">Demandes urgentes</a>
                        <span class="text-muted fw-bold">{{ $stats['demandes_urgentes'] }} demandes nécessitent une attention immédiate</span>
                    </div>
                </div>
                @endif

                @if(($stats['demandes_non_assignees'] ?? 0) > 0)
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-40px me-4">
                        <span class="symbol-label bg-light-warning">
                            <span class="svg-icon svg-icon-2 svg-icon-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="black"/>
                                    <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.8 8.8 6 11 6C11.3 6 11.7 6.1 12 6.2C12.3 6.1 12.7 6 13 6C15.2 6 17 7.8 17 10V13C17 14.1 17.9 15 19 15Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('admin.luxury.requests.index', ['assigned_to' => 'unassigned']) }}" class="fs-6 text-gray-800 text-hover-primary fw-bolder">Demandes non assignées</a>
                        <span class="text-muted fw-bold">{{ $stats['demandes_non_assignees'] }} demandes en attente d'assignation</span>
                    </div>
                </div>
                @endif

                @if(($stats['services_inactifs'] ?? 0) > 0)
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-40px me-4">
                        <span class="symbol-label bg-light-info">
                            <span class="svg-icon svg-icon-2 svg-icon-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M2 4V2C2 1.4 2.4 1 3 1H21C21.6 1 22 1.4 22 2V4C22 4.6 21.6 5 21 5H3C2.4 5 2 4.6 2 4Z" fill="black"/>
                                    <path d="M20 5H4C3.4 5 3 5.4 3 6V18C3 18.6 3.4 19 4 19H20C20.6 19 21 18.6 21 18V6C21 5.4 20.6 5 20 5Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('admin.luxury.services.index', ['actif' => '0']) }}" class="fs-6 text-gray-800 text-hover-primary fw-bolder">Services inactifs</a>
                        <span class="text-muted fw-bold">{{ $stats['services_inactifs'] }} services nécessitent une révision</span>
                    </div>
                </div>
                @endif

                @if(($stats['demandes_urgentes'] ?? 0) == 0 && ($stats['demandes_non_assignees'] ?? 0) == 0 && ($stats['services_inactifs'] ?? 0) == 0)
                <div class="text-center py-10">
                    <span class="svg-icon svg-icon-5x svg-icon-success mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                        </svg>
                    </span>
                    <h3 class="text-gray-800 fw-bolder fs-2 mb-2">Tout va bien !</h3>
                    <span class="text-muted fw-bold fs-6">Aucune alerte en cours</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Actions Rapides</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Raccourcis vers les tâches courantes</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('admin.luxury.services.create') }}" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-7 d-flex align-items-center mb-3 w-100">
                            <span class="svg-icon svg-icon-3x me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold text-start">
                                <span class="text-dark fw-bolder d-block fs-4 mb-2">Nouveau Service</span>
                                <span class="text-muted fw-bold fs-6">Ajouter un service de luxe</span>
                            </span>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('admin.luxury.packages.create') }}" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning p-7 d-flex align-items-center mb-3 w-100">
                            <span class="svg-icon svg-icon-3x me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15Z" fill="black"/>
                                    <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold text-start">
                                <span class="text-dark fw-bolder d-block fs-4 mb-2">Nouveau Package</span>
                                <span class="text-muted fw-bold fs-6">Créer un package premium</span>
                            </span>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('admin.luxury.requests.index') }}" class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info p-7 d-flex align-items-center mb-3 w-100">
                            <span class="svg-icon svg-icon-3x me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black"/>
                                    <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold text-start">
                                <span class="text-dark fw-bolder d-block fs-4 mb-2">Gérer Demandes</span>
                                <span class="text-muted fw-bold fs-6">Traiter les demandes clients</span>
                            </span>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="{{ route('admin.luxury.services.export') }}" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success p-7 d-flex align-items-center mb-3 w-100">
                            <span class="svg-icon svg-icon-3x me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold text-start">
                                <span class="text-dark fw-bolder d-block fs-4 mb-2">Exporter Données</span>
                                <span class="text-muted fw-bold fs-6">Rapports et statistiques</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistiques détaillées --}}
<div class="row g-5 g-xl-8 mb-5 mb-xl-8">
    {{-- Services populaires --}}
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Services Populaires</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Les plus demandés ce mois</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.luxury.services.index') }}" class="btn btn-sm btn-light-primary">
                        Voir tous
                    </a>
                </div>
            </div>
            <div class="card-body pt-5">
                @if(isset($stats['services_populaires']) && $stats['services_populaires']->count() > 0)
                    @foreach($stats['services_populaires'] as $service)
                    <div class="d-flex align-items-center mb-7">
                        <div class="d-flex flex-column">
                            <a href="{{ route('admin.luxury.services.show', $service) }}" class="text-dark fw-bolder text-hover-primary fs-6">{{ $service->nom }}</a>
                            <span class="text-muted fw-bold fs-7">{{ $service->categorie_display ?? $service->categorie }}</span>
                        </div>
                        <div class="ms-auto">
                            <span class="badge badge-light-success">{{ $service->package_requests_count ?? 0 }} demandes</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-10">
                        <span class="text-muted">Aucune donnée disponible</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Demandes récentes --}}
    <div class="col-xl-6">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Demandes Récentes</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Dernières demandes reçues</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.luxury.requests.index') }}" class="btn btn-sm btn-light-primary">
                        Voir toutes
                    </a>
                </div>
            </div>
            <div class="card-body pt-5">
                @if(isset($stats['demandes_recentes']) && $stats['demandes_recentes']->count() > 0)
                    @foreach($stats['demandes_recentes'] as $demande)
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-{{ $demande->status_color ?? 'primary' }}">
                                <span class="svg-icon svg-icon-2x svg-icon-{{ $demande->status_color ?? 'primary' }}">
                                    @if($demande->status === 'nouvelle')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="black"/>
                                            <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.8 8.8 6 11 6C11.3 6 11.7 6.1 12 6.2C12.3 6.1 12.7 6 13 6C15.2 6 17 7.8 17 10V13C17 14.1 17.9 15 19 15Z" fill="black"/>
                                        </svg>
                                    @elseif($demande->status === 'en_cours')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="black"/>
                                            <path d="M12 6V12L16 14" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @elseif($demande->status === 'terminee')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black"/>
                                            <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6Z" fill="black"/>
                                        </svg>
                                    @endif
                                </span>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="{{ route('admin.luxury.requests.show', $demande) }}" class="text-dark fw-bolder text-hover-primary fs-6">
                                {{ $demande->package->nom ?? 'Demande personnalisée' }}
                            </a>
                            <span class="text-muted fw-bold fs-7">
                                {{ $demande->client_nom }} - {{ $demande->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="ms-auto">
                            <span class="badge badge-light-{{ $demande->status_color ?? 'primary' }}">
                                {{ $demande->status_display ?? ucfirst($demande->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-10">
                        <span class="text-muted">Aucune demande récente</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Graphiques et métriques avancées --}}
<div class="row g-5 g-xl-8 mb-5 mb-xl-8">
    {{-- Répartition par statut --}}
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Répartition par Statut</span>
                    <span class="text-muted mt-1 fw-bold fs-7">État des demandes</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                @if(isset($stats['demandes_par_statut']) && $stats['demandes_par_statut']->count() > 0)
                    @foreach($stats['demandes_par_statut'] as $status => $count)
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-30px me-4">
                            <span class="symbol-label bg-light-{{
                                $status === 'nouvelle' ? 'warning' :
                                ($status === 'en_cours' ? 'info' :
                                ($status === 'terminee' ? 'success' : 'danger'))
                            }}">
                                <span class="fs-7 text-{{
                                    $status === 'nouvelle' ? 'warning' :
                                    ($status === 'en_cours' ? 'info' :
                                    ($status === 'terminee' ? 'success' : 'danger'))
                                }}">{{ $count }}</span>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="text-gray-400 fw-bold fs-7">
                                {{ round(($count / $stats['total_demandes']) * 100, 1) }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-10">
                        <span class="text-muted">Aucune donnée disponible</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Services par catégorie --}}
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Services par Catégorie</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Répartition des services</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                @if(isset($stats['services_par_categorie']) && $stats['services_par_categorie']->count() > 0)
                    @foreach($stats['services_par_categorie'] as $categorie => $count)
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-30px me-4">
                            <span class="symbol-label bg-light-primary">
                                <span class="fs-7 text-primary">{{ $count }}</span>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                {{ ucfirst($categorie) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="text-gray-400 fw-bold fs-7">
                                {{ round(($count / $stats['services_actifs']) * 100, 1) }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-10">
                        <span class="text-muted">Aucune donnée disponible</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Métriques de performance --}}
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Métriques de Performance</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Indicateurs clés</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                {{-- Taux de conversion --}}
                <div class="d-flex align-items-center mb-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-success">
                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M3 13C2.4 13 2 12.6 2 12C2 11.4 2.4 11 3 11H13C13.6 11 14 11.4 14 12C14 12.6 13.6 13 13 13H3Z" fill="black"/>
                                    <path d="M8 16L12.7 19.6999C13.1 20.0999 13.4 20.0999 13.8 19.6999L22.7 10.8C23.1 10.4 23.1 9.80001 22.7 9.40001C22.3 9.00001 21.7 9.00001 21.3 9.40001L13 17.7L9.7 14.4C9.3 14 8.7 14 8.3 14.4C7.9 14.8 7.9 15.4 8.3 15.8L8 16Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="text-dark fw-bolder fs-6">Taux de Conversion</div>
                        <div class="text-gray-400 fw-bold fs-7">{{ number_format($stats['taux_conversion'] ?? 0, 1) }}%</div>
                    </div>
                </div>

                {{-- Temps de réponse moyen --}}
                <div class="d-flex align-items-center mb-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-warning">
                            <span class="svg-icon svg-icon-2x svg-icon-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="black"/>
                                    <path d="M12 6V12L16 14" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="text-dark fw-bolder fs-6">Temps de Réponse</div>
                        <div class="text-gray-400 fw-bold fs-7">{{ $stats['temps_reponse_moyen'] ?? 0 }}h moyenne</div>
                    </div>
                </div>

                {{-- Panier moyen --}}
                <div class="d-flex align-items-center mb-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-info">
                            <span class="svg-icon svg-icon-2x svg-icon-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M6 8.725C6 8.125 6.4 7.725 7 7.725H8L10.5 0.875C10.6 0.475 11.1 0.475 11.4 0.875L14 7.725H15C15.6 7.725 16 8.125 16 8.725C16 9.325 15.6 9.725 15 9.725H14.8L13.1 15.925C12.9 16.625 12.1 17.125 11.3 17.125H9.7C8.9 17.125 8.1 16.625 7.9 15.925L6.2 9.725H6C5.4 9.725 5 9.325 5 8.725Z" fill="black"/>
                                    <path opacity="0.3" d="M9 11.725H12L11.6 10.725H9.4L9 11.725Z" fill="black"/>
                                    <path opacity="0.3" d="M9.7 15.925C9.9 15.925 10.1 15.725 10.1 15.525L10.8 12.725H8.2L8.9 15.525C8.9 15.725 9.1 15.925 9.3 15.925H9.7Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="text-dark fw-bolder fs-6">Panier Moyen</div>
                        <div class="text-gray-400 fw-bold fs-7">$ {{ number_format($stats['panier_moyen'] ?? 0, 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Actions rapides supplémentaires --}}
<div class="row g-5 g-xl-8">
    <div class="col-12">
        <div class="card card-xl-stretch">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Actions & Raccourcis</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Gestion rapide de la conciergerie</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.luxury.requests.index', ['status' => 'nouvelle']) }}" class="btn btn-light-primary btn-active-primary w-100 py-3">
                            <span class="svg-icon svg-icon-2x mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="black"/>
                                    <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.8 8.8 6 11 6C11.3 6 11.7 6.1 12 6.2C12.3 6.1 12.7 6 13 6C15.2 6 17 7.8 17 10V13C17 14.1 17.9 15 19 15Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold fs-7">Nouvelles Demandes</span>
                        </a>
                    </div>

                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.luxury.services.index') }}" class="btn btn-light-success btn-active-success w-100 py-3">
                            <span class="svg-icon svg-icon-2x mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                                    <path d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold fs-7">Gérer Services</span>
                        </a>
                    </div>

                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.luxury.packages.index') }}" class="btn btn-light-warning btn-active-warning w-100 py-3">
                            <span class="svg-icon svg-icon-2x mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15Z" fill="black"/>
                                    <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14Z" fill="black"/>
                                </svg>
                            </span>
                            <span class="d-block fw-bold fs-7">Gérer Packages</span>
                        </a>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au survol
    const cards = document.querySelectorAll('.card.hoverable');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Actualisation automatique des données toutes les 5 minutes
    setInterval(function() {
        if (window.location.pathname.includes('luxury/dashboard')) {
            // Recharger uniquement les sections avec données dynamiques
            updateDashboardStats();
        }
    }, 300000); // 5 minutes



    function updateKPIs(stats) {
        // Mettre à jour les chiffres dans les cartes KPI
        const kpiSelectors = {
            'total_services': '.card:nth-child(1) .text-gray-400',
            'total_packages': '.card:nth-child(2) .text-gray-400',
            'total_demandes': '.card:nth-child(3) .text-gray-400',
            'revenus_estimes': '.card:nth-child(4) .text-gray-400'
        };

        Object.keys(kpiSelectors).forEach(key => {
            const element = document.querySelector(kpiSelectors[key]);
            if (element && stats[key] !== undefined) {
                if (key === 'revenus_estimes') {
                    element.textContent = new Intl.NumberFormat('fr-FR').format(stats[key]) + ' $';
                } else {
                    element.textContent = stats[key] + (key.includes('services') ? ' services' :
                                                        key.includes('packages') ? ' packages' :
                                                        ' demandes');
                }
            }
        });
    }

    function showUpdateNotification() {
        // Créer une notification toast discrète
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'bottom: 20px; right: 20px; z-index: 1060;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    Données actualisées
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        document.body.appendChild(toast);

        // Initialiser et afficher le toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();

        // Supprimer l'élément après fermeture
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Tooltips pour les métriques
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(element => {
        new bootstrap.Tooltip(element);
    });
});
</script>
@endpush

@push('styles')
<style>
/* Animations et styles personnalisés pour le dashboard */
.card.hoverable {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card.hoverable:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 25px rgba(0,0,0,0.1);
}

/* Indicateurs de statut colorés */
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-nouvelle { background-color: #ffc700; }
.status-en_cours { background-color: #009ef7; }
.status-terminee { background-color: #50cd89; }
.status-annulee { background-color: #f1416c; }

/* Amélioration des badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Animation pour les chiffres */
@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-count {
    animation: countUp 0.6s ease-out;
}

/* Responsive design amélioré */
@media (max-width: 768px) {
    .card-body .svg-icon-3x {
        width: 2rem !important;
        height: 2rem !important;
    }

    .btn.py-3 {
        padding: 1rem !important;
    }

    .fs-4 {
        font-size: 1rem !important;
    }
}

/* Toast personnalisé */
.toast {
    border-radius: 0.475rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15);
}

/* Loading state pour les cartes */
.card-loading {
    position: relative;
    overflow: hidden;
}

.card-loading::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>
@endpush
