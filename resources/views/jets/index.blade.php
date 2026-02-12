@extends('layouts.app')

@section('title', 'Gestion des Jets')
@section('page-title', 'Jets Privés')
@section('page-subtitle', 'Gestion de votre flotte')

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <div class="d-flex align-items-center position-relative my-1">
            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                </svg>
            </span>
            <input type="text" id="searchJets" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher un jet..."/>
        </div>
        <div class="me-4">
            <select class="form-select form-select-solid" id="filterCategory" data-control="select2" data-placeholder="Toutes catégories">
                <option value="">Toutes catégories</option>
                <option value="Light">Light Jets</option>
                <option value="Mid-size">Mid-size Jets</option>
                <option value="Heavy">Heavy Jets</option>
            </select>
        </div>
        <div class="me-4">
            <select class="form-select form-select-solid" id="filterStatus" data-control="select2" data-placeholder="Tous statuts">
                <option value="">Tous statuts</option>
                <option value="1">Disponibles</option>
                <option value="0">Indisponibles</option>
            </select>
        </div>
        <a href="{{ route('jets.create') }}" class="btn btn-primary">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                </svg>
            </span>
            Ajouter un Jet
        </a>
    </div>
@endsection

@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                        </svg>
                    </span>
                    <input type="text" data-kt-jets-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher un jet"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-jets-table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                            </svg>
                        </span>
                        Filtrer
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
                        <div class="px-7 py-5" data-kt-jets-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold">Catégorie:</label>
                                <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Sélectionner une catégorie" data-allow-clear="true" data-kt-jets-table-filter="category">
                                    <option></option>
                                    <option value="Light">Light Jets</option>
                                    <option value="Mid-size">Mid-size Jets</option>
                                    <option value="Heavy">Heavy Jets</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold">Statut:</label>
                                <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Sélectionner un statut" data-allow-clear="true" data-kt-jets-table-filter="status">
                                    <option></option>
                                    <option value="1">Disponible</option>
                                    <option value="0">Indisponible</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-jets-table-filter="reset">Réinitialiser</button>
                                <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-jets-table-filter="filter">Appliquer</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Menu 1-->
                    <!--end::Filter-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_jets_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_jets_table .form-check-input" value="1"/>
                            </div>
                        </th>
                        <th class="min-w-125px">Jet</th>
                        <th class="min-w-125px">Détails</th>
                        <th class="min-w-125px">Catégorie</th>
                        <th class="min-w-125px">Prix/Heure</th>
                        <th class="min-w-125px">Statut</th>
                        <th class="min-w-125px">Réservations</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="text-gray-600 fw-bold">
                    @forelse ($jets as $jet)
                        <tr>
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $jet->id }}"/>
                                </div>
                            </td>
                            <!--end::Checkbox-->
                            <!--begin::Jet-->
                            <td class="d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <a href="{{ route('jets.show', $jet) }}">
                                        @if($jet->image)
                                            <div class="symbol-label">
                                                <img src="{{ asset('storage/' . $jet->image) }}" alt="{{ $jet->nom }}" class="w-100"/>
                                            </div>
                                        @else
                                            <div class="symbol-label bg-light-primary">
                                                <span class="svg-icon svg-icon-3 svg-icon-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M22 7.5L19.5 5L17 7.5L19.5 10L22 7.5Z" fill="black"/>
                                                        <path opacity="0.3" d="M2 7.5L4.5 5L7 7.5L4.5 10L2 7.5ZM12 16L9.5 13.5L7 16L9.5 18.5L12 16Z" fill="black"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Jet details-->
                                <div class="d-flex flex-column">
                                    <a href="{{ route('jets.show', $jet) }}" class="text-gray-800 text-hover-primary mb-1 fw-bolder">{{ $jet->nom }}</a>
                                    <span class="text-muted">{{ $jet->modele ?? 'Modèle non spécifié' }}</span>
                                </div>
                                <!--begin::Jet details-->
                            </td>
                            <!--end::Jet-->
                            <!--begin::Details-->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bolder mb-1">{{ $jet->capacite }} passagers</span>
                                    @if($jet->localisation)
                                        <span class="text-muted">{{ $jet->localisation }}</span>
                                    @endif
                                    @if($jet->autonomie_km)
                                        <span class="text-muted">{{ number_format($jet->autonomie_km) }} km</span>
                                    @endif
                                </div>
                            </td>
                            <!--end::Details-->
                            <!--begin::Category-->
                            <td>
                                @if($jet->categorie)
                                    <span class="badge badge-light-{{ 
                                        $jet->categorie === 'Light' ? 'success' : 
                                        ($jet->categorie === 'Mid-size' ? 'warning' : 'primary') 
                                    }}">{{ $jet->categorie }}</span>
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </td>
                            <!--end::Category-->
                            <!--begin::Price-->
                            <td>
                                <span class="text-gray-800 fw-bolder">$ {{ number_format($jet->prix, 0, ',', ' ') }}</span>
                                <span class="text-muted">/heure</span>
                            </td>
                            <!--end::Price-->
                            <!--begin::Status-->
                            <td>
                                @if($jet->disponible)
                                    <span class="badge badge-light-success">Disponible</span>
                                @else
                                    <span class="badge badge-light-danger">Indisponible</span>
                                @endif
                            </td>
                            <!--end::Status-->
                            <!--begin::Reservations-->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bolder">{{ $jet->reservations->count() }}</span>
                                    <span class="text-muted fs-7">réservations</span>
                                </div>
                            </td>
                            <!--end::Reservations-->
                            <!--begin::Action-->
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('jets.show', $jet) }}" class="menu-link px-3">Voir</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('jets.edit', $jet) }}" class="menu-link px-3">Modifier</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce jet ?')) { document.getElementById('delete-form-{{ $jet->id }}').submit(); }">
                                            Supprimer
                                        </a>
                                        <form id="delete-form-{{ $jet->id }}" action="{{ route('jets.destroy', $jet) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                            <!--end::Action-->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="svg-icon svg-icon-muted svg-icon-2hx mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M22 7.5L19.5 5L17 7.5L19.5 10L22 7.5Z" fill="black"/>
                                            <path opacity="0.3" d="M2 7.5L4.5 5L7 7.5L4.5 10L2 7.5ZM12 16L9.5 13.5L7 16L9.5 18.5L12 16Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <span class="text-muted fs-4">Aucun jet enregistré</span>
                                    <span class="text-muted fs-6">Commencez par ajouter votre premier jet</span>
                                    <a href="{{ route('jets.create') }}" class="btn btn-primary mt-3">Ajouter un Jet</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!--begin::Pagination-->
    @if($jets->hasPages())
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">
                Affichage {{ $jets->firstItem() }} à {{ $jets->lastItem() }} 
                sur {{ $jets->total() }} résultats
            </div>
            <ul class="pagination">
                {{ $jets->links() }}
            </ul>
        </div>
    @endif
    <!--end::Pagination-->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filtrage en temps réel
    $('[data-kt-jets-table-filter="search"]').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#kt_jets_table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Checkbox "tout sélectionner"
    $('[data-kt-check="true"]').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('[data-kt-check-target="#kt_jets_table .form-check-input"]').prop('checked', isChecked);
    });

    // Actions sur les jets sélectionnés
    function getSelectedJets() {
        let selected = [];
        $('#kt_jets_table .form-check-input:checked').each(function() {
            if ($(this).val() !== '1') { // Exclure le checkbox "tout sélectionner"
                selected.push($(this).val());
            }
        });
        return selected;
    }

    // Affichage conditionnel des actions de groupe
    $('#kt_jets_table .form-check-input').on('change', function() {
        const selected = getSelectedJets();
        if (selected.length > 0) {
            // Afficher les actions de groupe (à implémenter si nécessaire)
            console.log('Jets sélectionnés:', selected);
        }
    });
});
</script>
@endpush