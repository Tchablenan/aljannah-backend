{{-- resources/views/admin/luxury/packages/index.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', 'Packages de Luxe')

@section('page-title', 'Packages de Luxe')
@section('page-subtitle', 'Gestion des packages prédéfinis et personnalisés')

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.packages.create') }}" class="btn btn-sm btn-primary me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
            </svg>
        </span>
        Nouveau Package
    </a>
    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#filter_modal">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
            </svg>
        </span>
        Filtrer
    </button>
</div>
@endsection

@section('content')

{{-- Statistiques --}}
<div class="row g-5 g-xl-8 mb-5 mb-xl-8">
    <div class="col-xl-3">
        <div class="card card-xl-stretch">
            <div class="card-body d-flex flex-column">
                <div class="d-flex flex-column flex-grow-1">
                    <span class="text-dark fw-bolder fs-2 mb-2">{{ $stats['total'] }}</span>
                    <span class="text-gray-400 fw-bold fs-6">Total Packages</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-primary">
            <div class="card-body d-flex flex-column">
                <div class="d-flex flex-column flex-grow-1">
                    <span class="text-primary fw-bolder fs-2 mb-2">{{ $stats['predefinis'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Packages Prédéfinis</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-info">
            <div class="card-body d-flex flex-column">
                <div class="d-flex flex-column flex-grow-1">
                    <span class="text-info fw-bolder fs-2 mb-2">{{ $stats['personnalises'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Packages Personnalisés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-success">
            <div class="card-body d-flex flex-column">
                <div class="d-flex flex-column flex-grow-1">
                    <span class="text-success fw-bolder fs-2 mb-2">{{ $stats['actifs'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Packages Actifs</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Liste des packages --}}
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                    </svg>
                </span>
                <input type="text" id="search_input" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher un package..." />
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-subscription-table-toolbar="base">
                <form action="{{ route('admin.luxury.packages.export') }}" method="GET" class="me-3">
                    <button type="submit" name="format" value="csv" class="btn btn-sm btn-light-primary">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
                            </svg>
                        </span>
                        Exporter CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        @if($packages->isEmpty())
            <div class="text-center py-10">
                <img src="{{ asset('media/illustrations/empty-state.png') }}" class="mw-400px" alt="Aucun package" />
                <p class="text-gray-500 fs-4 fw-bold mt-5">Aucun package trouvé</p>
                <a href="{{ route('admin.luxury.packages.create') }}" class="btn btn-primary mt-3">
                    Créer le premier package
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="packages_table">
                    <thead>
                        <tr class="fw-bolder text-muted">
                            <th class="w-25px">
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" id="select_all" />
                                </div>
                            </th>
                            <th class="min-w-300px">Package</th>
                            <th class="min-w-100px">Type</th>
                            <th class="min-w-100px">Services</th>
                            <th class="min-w-100px">Prix</th>
                            <th class="min-w-100px">Personnes</th>
                            <th class="min-w-80px">Popularité</th>
                            <th class="min-w-100px">Statut</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $package->id }}" />
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($package->image_principale_url)
                                        <div class="symbol symbol-50px me-3">
                                            <img src="{{ $package->image_principale_url }}" alt="{{ $package->nom }}" />
                                        </div>
                                    @else
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <span class="text-primary fw-bolder">{{ strtoupper(substr($package->nom, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.luxury.packages.show', $package) }}" class="text-dark fw-bolder text-hover-primary fs-6">
                                            {{ $package->nom }}
                                        </a>
                                        <span class="text-muted fw-bold text-muted d-block fs-7">
                                            {{ Str::limit($package->description, 50) }}
                                        </span>
                                        @if($package->destination)
                                            <span class="badge badge-light-info mt-1">
                                                <i class="bi bi-geo-alt"></i> {{ $package->destination }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($package->type === 'predefinit')
                                    <span class="badge badge-light-primary">Prédéfini</span>
                                @else
                                    <span class="badge badge-light-info">Personnalisé</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light">
                                    {{ $package->services_count }} service(s)
                                </span>
                            </td>
                            <td>
                                @if($package->prix_total)
                                    <span class="text-dark fw-bolder fs-6">$ {{ number_format($package->prix_total, 2, ',', ' ') }}</span>
                                @elseif($package->prix_estime)
                                    <span class="text-muted fw-bold fs-7">~$ {{ number_format($package->prix_estime, 2, ',', ' ') }}</span>
                                @else
                                    <span class="text-gray-600">Sur devis</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark fw-bold">{{ $package->nombre_personnes }}</span>
                                <span class="text-muted">pers.</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="text-dark fw-bolder me-2">{{ $package->popularite }}</span>
                                    <span class="svg-icon svg-icon-5 svg-icon-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($package->actif)
                                        <span class="badge badge-light-success mb-1">Actif</span>
                                    @else
                                        <span class="badge badge-light-danger mb-1">Inactif</span>
                                    @endif

                                    @if($package->visible_public)
                                        <span class="badge badge-light-primary">Visible</span>
                                    @else
                                        <span class="badge badge-light-secondary">Masqué</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                        </svg>
                                    </span>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="{{ route('admin.luxury.packages.show', $package) }}" class="menu-link px-3">
                                            Voir les détails
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{ route('admin.luxury.packages.edit', $package) }}" class="menu-link px-3">
                                            Modifier
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <form action="{{ route('admin.luxury.packages.duplicate', $package) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="menu-link px-3 w-100 text-start border-0 bg-transparent">
                                                Dupliquer
                                            </button>
                                        </form>
                                    </div>
                                    <div class="separator my-2"></div>
                                    <div class="menu-item px-3">
                                        <form action="{{ route('admin.luxury.packages.toggle-status', $package) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="menu-link px-3 w-100 text-start border-0 bg-transparent">
                                                @if($package->actif)
                                                    Désactiver
                                                @else
                                                    Activer
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                    <div class="menu-item px-3">
                                        <form action="{{ route('admin.luxury.packages.toggle-visibility', $package) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="menu-link px-3 w-100 text-start border-0 bg-transparent">
                                                @if($package->visible_public)
                                                    Masquer
                                                @else
                                                    Rendre visible
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                    <div class="separator my-2"></div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3 text-danger" data-bs-toggle="modal" data-bs-target="#delete_modal_{{ $package->id }}">
                                            Supprimer
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal de suppression --}}
                        <div class="modal fade" id="delete_modal_{{ $package->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir supprimer le package <strong>{{ $package->nom }}</strong> ?</p>
                                        <p class="text-danger">Cette action est irréversible.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('admin.luxury.packages.destroy', $package) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                <div class="fs-6 fw-bold text-gray-700">
                    Affichage de {{ $packages->firstItem() }} à {{ $packages->lastItem() }} sur {{ $packages->total() }} packages
                </div>
                <div>
                    {{ $packages->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal de filtrage --}}
<div class="modal fade" id="filter_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.luxury.packages.index') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">Filtrer les packages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="predefinit" {{ request('type') === 'predefinit' ? 'selected' : '' }}>Prédéfinis</option>
                            <option value="personnalise" {{ request('type') === 'personnalise' ? 'selected' : '' }}>Personnalisés</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Statut</label>
                        <select name="actif" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                            <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Visibilité</label>
                        <select name="visible" class="form-select">
                            <option value="">Tous</option>
                            <option value="1" {{ request('visible') === '1' ? 'selected' : '' }}>Visibles</option>
                            <option value="0" {{ request('visible') === '0' ? 'selected' : '' }}>Masqués</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, description, destination...">
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Trier par</label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                            <option value="popularite" {{ request('sort_by') === 'popularite' ? 'selected' : '' }}>Popularité</option>
                            <option value="nom" {{ request('sort_by') === 'nom' ? 'selected' : '' }}>Nom</option>
                            <option value="prix_total" {{ request('sort_by') === 'prix_total' ? 'selected' : '' }}>Prix</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Ordre</label>
                        <select name="sort_order" class="form-select">
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Décroissant</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Croissant</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.luxury.packages.index') }}" class="btn btn-light">Réinitialiser</a>
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche en temps réel
    const searchInput = document.getElementById('search_input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#packages_table tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Sélection multiple
    const selectAll = document.getElementById('select_all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endpush
