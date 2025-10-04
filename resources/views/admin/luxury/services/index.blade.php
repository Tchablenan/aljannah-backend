{{-- resources/views/admin/luxury/services/index.blade.php --}}

@extends('layouts.luxery_services.app')

@section('title', 'Services de Luxe')

@section('page-title', 'Services de Luxe')
@section('page-subtitle', 'Gestion des services de conciergerie')

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
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-sm btn-light-primary dropdown-toggle" data-bs-toggle="dropdown">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
                </svg>
            </span>
            Exporter
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Excel</a></li>
            <li><a class="dropdown-item" href="#">PDF</a></li>
        </ul>
    </div>
</div>
@endsection

@section('content')

{{-- Filtres et recherche --}}
<div class="card mb-5 mb-xl-8">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                        <path opacity="0.5" d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                    </svg>
                </span>
                <input type="text" data-kt-services-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher un service..."/>
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-services-table-toolbar="base">
                <div class="me-4">
                    <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Catégorie" data-allow-clear="true" data-kt-services-table-filter="categorie">
                        <option></option>
                        <option value="transport">Transport</option>
                        <option value="hebergement">Hébergement</option>
                        <option value="restauration">Restauration</option>
                        <option value="loisirs">Loisirs</option>
                        <option value="bien-etre">Bien-être</option>
                        <option value="culture">Culture</option>
                        <option value="shopping">Shopping</option>
                        <option value="business">Business</option>
                    </select>
                </div>
                <div class="me-4">
                    <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Statut" data-allow-clear="true" data-kt-services-table-filter="statut">
                        <option></option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary" data-kt-services-table-filter="reset">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M14.5 20.7259C14.6 21.2259 14.2 21.7259 13.7 21.7259C13.2 21.7259 12.7 21.2259 12.8 20.7259L13.4 17.6259C11.6 17.4259 9.9 16.7259 8.7 15.6259C6.2 13.1259 6.2 9.12592 8.7 6.62592C11.2 4.12592 15.2 4.12592 17.7 6.62592C20.2 9.12592 20.2 13.1259 17.7 15.6259C16.5 16.8259 14.8 17.5259 13 17.6259L14.5 20.7259Z" fill="black"/>
                            <path opacity="0.3" d="M15.8 6.62592C18.3 9.12592 18.3 13.0259 15.8 15.6259C13.3 18.1259 9.4 18.1259 6.9 15.6259C4.4 13.1259 4.4 9.12592 6.9 6.62592C9.4 4.12592 13.2 4.12592 15.8 6.62592Z" fill="black"/>
                        </svg>
                    </span>
                    Réinitialiser
                </button>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        {{-- Tableau des services --}}
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_services_table">
                <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_services_table .form-check-input" value="1"/>
                            </div>
                        </th>
                        <th class="min-w-200px">Service</th>
                        <th class="min-w-100px">Catégorie</th>
                        <th class="min-w-100px">Prix</th>
                        <th class="min-w-100px">Statut</th>
                        <th class="min-w-80px">Popularité</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-bold">
                    @forelse($services as $service)
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="{{ $service->id }}"/>
                            </div>
                        </td>
                        <td class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                @if($service->image_url)
                                    <div class="symbol-label">
                                        <img src="{{ $service->image_url }}" alt="{{ $service->nom }}" class="w-100"/>
                                    </div>
                                @else
                                    <div class="symbol-label bg-light-primary text-primary fw-bolder">
                                        {{ strtoupper(substr($service->nom, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex flex-column">
                                <a href="{{ route('admin.luxury.services.show', $service) }}" class="text-gray-800 text-hover-primary mb-1">{{ $service->nom }}</a>
                                <span class="text-muted">{{ Str::limit($service->description, 50) }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $service->categorie_color ?? 'primary' }}">
                                {{ $service->categorie_display ?? ucfirst($service->categorie) }}
                            </span>
                        </td>
                        <td>
                            @if($service->prix_base)
                                <span class="fw-bolder">{{ number_format($service->prix_base, 0, ',', ' ') }} €</span>
                                @if($service->prix_max && $service->prix_max > $service->prix_base)
                                    <span class="text-muted"> - {{ number_format($service->prix_max, 0, ',', ' ') }} €</span>
                                @endif
                            @else
                                <span class="text-muted">Sur devis</span>
                            @endif
                        </td>
                        <td>
                            @if($service->actif)
                                <div class="badge badge-light-success fw-bolder">Actif</div>
                            @else
                                <div class="badge badge-light-danger fw-bolder">Inactif</div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress h-6px w-60px me-2">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $service->popularite ?? 0 }}%"></div>
                                </div>
                                <span class="text-muted fs-8">{{ $service->popularite ?? 0 }}%</span>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                    </svg>
                                </span>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.luxury.services.show', $service) }}" class="menu-link px-3">Voir</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.luxury.services.edit', $service) }}" class="menu-link px-3">Modifier</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-services-table-filter="toggle_status" data-service-id="{{ $service->id }}">
                                        {{ $service->actif ? 'Désactiver' : 'Activer' }}
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger" data-kt-services-table-filter="delete_row" data-service-id="{{ $service->id }}">Supprimer</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10">
                            <div class="d-flex flex-column align-items-center">
                                <span class="svg-icon svg-icon-5x svg-icon-muted mb-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                                        <path d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                                    </svg>
                                </span>
                                <h3 class="text-gray-800 fw-bolder fs-2 mb-2">Aucun service trouvé</h3>
                                <span class="text-muted fw-bold fs-6 mb-5">Commencez par créer votre premier service de luxe</span>
                                <a href="{{ route('admin.luxury.services.create') }}" class="btn btn-primary">Créer un service</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($services->hasPages())
        <div class="row">
            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                <div class="dataTables_info">
                    Affichage de {{ $services->firstItem() }} à {{ $services->lastItem() }} sur {{ $services->total() }} services
                </div>
            </div>
            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                {{ $services->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Statistiques rapides --}}
<div class="row g-5 g-xl-8">
    <div class="col-xl-3">
        <div class="card bg-light-success card-xl-stretch mb-xl-8">
            <div class="card-body">
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Services Actifs</div>
                <div class="fw-bolder text-success fs-2">{{ $stats['actifs'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card bg-light-warning card-xl-stretch mb-xl-8">
            <div class="card-body">
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Services Inactifs</div>
                <div class="fw-bolder text-warning fs-2">{{ $stats['inactifs'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card bg-light-primary card-xl-stretch mb-xl-8">
            <div class="card-body">
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Demandes ce mois</div>
                <div class="fw-bolder text-primary fs-2">{{ $stats['demandes_mois'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card bg-light-info card-xl-stretch mb-xl-8">
            <div class="card-body">
                <div class="text-gray-900 fw-bolder fs-6 mb-2">Chiffre d'Affaires</div>
                <div class="fw-bolder text-info fs-2">{{ number_format($stats['ca_estime'] ?? 0, 0, ',', ' ') }}€</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les filtres
    const searchInput = document.querySelector('[data-kt-services-table-filter="search"]');
    const categorieFilter = document.querySelector('[data-kt-services-table-filter="categorie"]');
    const statutFilter = document.querySelector('[data-kt-services-table-filter="statut"]');
    const resetButton = document.querySelector('[data-kt-services-table-filter="reset"]');

    // Fonction de filtrage (peut être étendue avec DataTables)
    function filterTable() {
        // Logique de filtrage ici
        console.log('Filtrage en cours...');
    }

    // Événements
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (categorieFilter) categorieFilter.addEventListener('change', filterTable);
    if (statutFilter) statutFilter.addEventListener('change', filterTable);

    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (categorieFilter) $(categorieFilter).val('').trigger('change');
            if (statutFilter) $(statutFilter).val('').trigger('change');
            filterTable();
        });
    }

    // Toggle statut
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-kt-services-table-filter="toggle_status"]')) {
            e.preventDefault();
            const serviceId = e.target.getAttribute('data-service-id');
            // Logique AJAX pour changer le statut
            console.log('Toggle statut pour service:', serviceId);
        }

        if (e.target.matches('[data-kt-services-table-filter="delete_row"]')) {
            e.preventDefault();
            const serviceId = e.target.getAttribute('data-service-id');
            if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
                // Logique AJAX pour supprimer
                console.log('Suppression service:', serviceId);
            }
        }
    });
});
</script>
@endpush
