{{-- resources/views/admin/reservations/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">

            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Toolbar-->
                    <div class="toolbar" id="kt_toolbar">
                        <!--begin::Container-->
                        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                            <!--begin::Page title-->
                            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <!--begin::Title-->
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                                    Gestion des Réservations
                                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                    <small class="text-muted fs-7 fw-normal ms-2">{{ $reservations->total() }} réservation(s)</small>
                                </h1>
                                <!--end::Title-->
                            </div>
                            <!--end::Page title-->
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                <!-- Export Button -->
                                <button class="btn btn-sm btn-light-primary" data-bs-toggle="tooltip" title="Exporter en Excel">
                                    <i class="fas fa-file-excel"></i> Exporter
                                </button>
                                <!-- New Reservation Button -->
                                <a href="{{ route('reservations.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Nouvelle Réservation
                                </a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Toolbar-->

                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div class="container-fluid mt-5">
                            
                            <!--begin::Stats Cards-->
                            <div class="row g-5 g-xl-8 mb-8">
                                <div class="col-xl-3">
                                    <div class="card card-xl-stretch mb-xl-8">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px">
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="fas fa-calendar-check text-primary fs-2x"></i>
                                                    </span>
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fw-bold fs-2 text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                                                    <div class="fs-7 text-muted">En attente</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="card card-xl-stretch mb-xl-8">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px">
                                                    <span class="symbol-label bg-light-success">
                                                        <i class="fas fa-check-circle text-success fs-2x"></i>
                                                    </span>
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fw-bold fs-2 text-gray-900">{{ $stats['confirmed'] ?? 0 }}</div>
                                                    <div class="fs-7 text-muted">Confirmées</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="card card-xl-stretch mb-xl-8">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px">
                                                    <span class="symbol-label bg-light-info">
                                                        <i class="fas fa-plane text-info fs-2x"></i>
                                                    </span>
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fw-bold fs-2 text-gray-900">{{ $stats['completed'] ?? 0 }}</div>
                                                    <div class="fs-7 text-muted">Terminées</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="card card-xl-stretch mb-xl-8">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px">
                                                    <span class="symbol-label bg-light-danger">
                                                        <i class="fas fa-times-circle text-danger fs-2x"></i>
                                                    </span>
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fw-bold fs-2 text-gray-900">{{ $stats['cancelled'] ?? 0 }}</div>
                                                    <div class="fs-7 text-muted">Annulées</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Stats Cards-->

                            <!--begin::Card-->
                            <div class="card card-xl-stretch mb-5 mb-xl-8">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Liste des Réservations</span>
                                        <span class="text-muted mt-1 fw-bold fs-7">Gérez toutes les réservations de jets privés</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <!-- Search and Filters -->
                                        <div class="d-flex justify-content-end" data-kt-reservation-table-toolbar="base">
                                            <!--begin::Filter-->
                                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                <i class="fas fa-filter fs-2"></i>Filtrer
                                            </button>
                                            <!--begin::Menu 1-->
                                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                                <!--begin::Header-->
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-dark fw-bolder">Options de filtrage</div>
                                                </div>
                                                <!--end::Header-->
                                                <!--begin::Separator-->
                                                <div class="separator border-gray-200"></div>
                                                <!--end::Separator-->
                                                <!--begin::Content-->
                                                <div class="px-7 py-5" data-kt-reservation-table-filter="form">
                                                    <!--begin::Input group-->
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-bold">Statut:</label>
                                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Sélectionner un statut" data-allow-clear="true" data-kt-reservation-table-filter="status">
                                                            <option></option>
                                                            <option value="pending">En attente</option>
                                                            <option value="confirmed">Confirmée</option>
                                                            <option value="completed">Terminée</option>
                                                            <option value="cancelled">Annulée</option>
                                                        </select>
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Actions-->
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-reservation-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-reservation-table-filter="filter">Appliquer</button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Menu 1-->
                                            <!--end::Filter-->
                                            
                                            <!--begin::Search-->
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <input type="text" data-kt-reservation-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher une réservation" />
                                            </div>
                                            <!--end::Search-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_reservation_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th class="w-25px">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check" />
                                                        </div>
                                                    </th>
                                                    <th class="min-w-200px">Client</th>
                                                    <th class="min-w-140px">Contact</th>
                                                    <th class="min-w-120px">Itinéraire</th>
                                                    <th class="min-w-120px">Jet / Dates</th>
                                                    <th class="min-w-100px">Passagers</th>
                                                    <th class="min-w-100px">Statut</th>
                                                    <th class="text-end min-w-100px">Actions</th>
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->

                                            <!--begin::Table body-->
                                            <tbody>
                                                @forelse ($reservations as $reservation)
                                                    <tr>
                                                        <td>
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input widget-9-check" type="checkbox" value="{{ $reservation->id }}" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-45px me-5">
                                                                    <div class="symbol-label bg-light-primary text-primary fs-6 fw-bolder">
                                                                        {{ strtoupper(substr($reservation->first_name, 0, 1) . substr($reservation->last_name, 0, 1)) }}
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="{{ route('reservations.show', $reservation->id) }}" class="text-dark fw-bolder text-hover-primary fs-6">
                                                                        {{ $reservation->first_name }} {{ $reservation->last_name }}
                                                                    </a>
                                                                    <span class="text-muted fw-bold text-muted d-block fs-7">
                                                                        Réservé le {{ $reservation->created_at->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <a href="mailto:{{ $reservation->email }}" class="text-dark fw-bolder text-hover-primary d-block fs-6">
                                                                    <i class="fas fa-envelope text-muted me-2"></i>{{ $reservation->email }}
                                                                </a>
                                                                @if($reservation->phone)
                                                                    <span class="text-muted fw-bold fs-7 mt-1">
                                                                        <i class="fas fa-phone text-muted me-2"></i>{{ $reservation->phone }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bolder fs-7 text-gray-800">
                                                                    <i class="fas fa-plane-departure text-primary me-2"></i>{{ $reservation->departure_location }}
                                                                </span>
                                                                <span class="text-muted fw-bold fs-7 mt-1">
                                                                    <i class="fas fa-plane-arrival text-success me-2"></i>{{ $reservation->arrival_location }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bolder fs-7 text-gray-800 mb-1">
                                                                    <i class="fas fa-jet-fighter text-info me-2"></i>{{ $reservation->jet->name ?? 'N/A' }}
                                                                </span>
                                                                <div class="badge badge-light-info fs-8">
                                                                    {{ \Carbon\Carbon::parse($reservation->departure_date)->format('d/m/Y') }}
                                                                    @if($reservation->arrival_date && $reservation->arrival_date !== $reservation->departure_date)
                                                                        - {{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d/m/Y') }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="badge badge-light-primary fs-7">
                                                                <i class="fas fa-users me-1"></i>{{ $reservation->passengers }} passager(s)
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusConfig = [
                                                                    'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'En attente'],
                                                                    'confirmed' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Confirmée'],
                                                                    'completed' => ['class' => 'info', 'icon' => 'plane', 'text' => 'Terminée'],
                                                                    'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Annulée']
                                                                ];
                                                                $status = $statusConfig[$reservation->status ?? 'pending'];
                                                            @endphp
                                                            <span class="badge badge-light-{{ $status['class'] }} fs-7 fw-bold">
                                                                <i class="fas fa-{{ $status['icon'] }} me-1"></i>{{ $status['text'] }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                <!--begin::Menu-->
                                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="black" />
                                                                            <rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="black" />
                                                                            <rect x="11" y="7" width="2.6" height="2.6" rx="1.3" fill="black" />
                                                                            <rect x="11" y="15" width="2.6" height="2.6" rx="1.3" fill="black" />
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                                <!--begin::Menu 3-->
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="menu-link px-3">
                                                                            <i class="fas fa-eye text-primary me-2"></i>Voir détails
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="{{ route('reservations.edit', $reservation->id) }}" class="menu-link px-3">
                                                                            <i class="fas fa-edit text-info me-2"></i>Modifier
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#statusModal{{ $reservation->id }}">
                                                                            <i class="fas fa-exchange-alt text-warning me-2"></i>Changer statut
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="delete-form">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="menu-link px-3 text-danger delete-btn">
                                                                                <i class="fas fa-trash me-2"></i>Supprimer
                                                                            </a>
                                                                        </form>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu 3-->
                                                                <!--end::Menu-->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-10">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i class="fas fa-inbox text-muted fs-3x mb-3"></i>
                                                                <span class="text-muted fs-5">Aucune réservation trouvée</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                    </div>
                    <!--end::Post-->
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reservations->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!--begin::Modals-->
    @foreach($reservations as $reservation)
        <!-- Status Change Modal -->
        <div class="modal fade" id="statusModal{{ $reservation->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bolder">Changer le statut</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-13 text-center">
                                <h1 class="mb-3">Modifier le statut de la réservation</h1>
                                <div class="text-muted fw-bold fs-5">Client: {{ $reservation->first_name }} {{ $reservation->last_name }}</div>
                            </div>
                            <div class="d-flex flex-column mb-8 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Nouveau statut</span>
                                </label>
                                <select class="form-select form-select-solid" name="status" required>
                                    <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="completed" {{ $reservation->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                                    <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!--end::Modals-->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation de suppression
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette action ne peut pas être annulée!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Recherche en temps réel
    const searchInput = document.querySelector('[data-kt-reservation-table-filter="search"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            // Implémentation de la recherche AJAX ici
            console.log('Recherche:', this.value);
        });
    }
});
</script>
@endpush