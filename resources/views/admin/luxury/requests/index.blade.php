{{-- resources/views/admin/luxury/requests/index.blade.php --}}

@extends('layouts.luxery_services.app')

@section('title', 'Demandes Clients')

@section('page-title', 'Demandes Clients')
@section('page-subtitle', 'Gestion des demandes de packages personnalisés')

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <button type="button" class="btn btn-sm btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#filter_modal">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
            </svg>
        </span>
        Filtrer
    </button>
    <a href="{{ route('admin.luxury.requests.export') }}" class="btn btn-sm btn-light-success">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
            </svg>
        </span>
        Exporter
    </a>
</div>
@endsection

@section('content')

{{-- Statistiques --}}
<div class="row g-5 g-xl-8 mb-5">
    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-primary">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="text-primary fw-bolder fs-2x mb-2">{{ $stats['total'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Total Demandes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-warning">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="text-warning fw-bolder fs-2x mb-2">{{ $stats['nouvelles'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Nouvelles</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-info">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="text-info fw-bolder fs-2x mb-2">{{ $stats['en_cours'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">En Cours</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-xl-stretch bg-light-danger">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="text-danger fw-bolder fs-2x mb-2">{{ $stats['urgentes'] }}</span>
                    <span class="text-gray-700 fw-bold fs-6">Urgentes/VIP</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Liste des demandes --}}
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
                <input type="text" id="search_input" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher..." />
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        @if($requests->isEmpty())
            <div class="text-center py-10">
                <div class="text-gray-500 fs-2 mb-3">📋</div>
                <p class="text-gray-500 fs-4 fw-bold">Aucune demande trouvée</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="requests_table">
                    <thead>
                        <tr class="fw-bolder text-muted">
                            <th class="w-25px">
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" id="select_all" />
                                </div>
                            </th>
                            <th class="min-w-150px">Référence</th>
                            <th class="min-w-200px">Client</th>
                            <th class="min-w-150px">Destination</th>
                            <th class="min-w-100px">Date Départ</th>
                            <th class="min-w-80px">Personnes</th>
                            <th class="min-w-100px">Statut</th>
                            <th class="min-w-80px">Priorité</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $request->id }}" />
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.luxury.requests.show', $request) }}" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                    {{ $request->reference }}
                                </a>
                                <span class="text-muted fw-bold text-muted d-block fs-7">
                                    {{ $request->titre_demande }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-bolder mb-1">{{ $request->client_nom_complet }}</span>
                                    <span class="text-muted fs-7">{{ $request->client_email }}</span>
                                    @if($request->client_telephone)
                                        <span class="text-muted fs-7">{{ $request->client_telephone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-dark fw-bold">{{ $request->destination_principale }}</span>
                                @if($request->destinations_multiples && count($request->destinations_multiples) > 0)
                                    <span class="badge badge-light-info ms-2">+{{ count($request->destinations_multiples) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark fw-bold">{{ $request->date_debut_souhaitee->format('d/m/Y') }}</span>
                                @if($request->date_fin_souhaitee)
                                    <span class="text-muted d-block fs-7">→ {{ $request->date_fin_souhaitee->format('d/m/Y') }}</span>
                                    <span class="badge badge-light">{{ $request->duree_sejour_display }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark fw-bolder">{{ $request->nombre_personnes }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-{{ $request->statut_color }}">
                                    {{ $request->statut_display }}
                                </span>
                                @if($request->concierge_assigne)
                                    <span class="badge badge-light-primary mt-1 d-block">
                                        👤 {{ $request->concierge_assigne }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $request->priorite_color }}">
                                    {{ $request->priorite_display }}
                                </span>
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
                                        <a href="{{ route('admin.luxury.requests.show', $request) }}" class="menu-link px-3">
                                            👁️ Voir les détails
                                        </a>
                                    </div>
                                    
                                    @if($request->statut === 'nouvelle')
                                    <div class="menu-item px-3">
                                        <button type="button" class="menu-link px-3 w-100 text-start border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#assign_modal_{{ $request->id }}">
                                            👤 Assigner un concierge
                                        </button>
                                    </div>
                                    @endif

                                    <div class="menu-item px-3">
                                        <button type="button" class="menu-link px-3 w-100 text-start border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#status_modal_{{ $request->id }}">
                                            🔄 Changer le statut
                                        </button>
                                    </div>

                                    <div class="separator my-2"></div>

                                    @if($request->canBeCancelled())
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3 text-danger" data-bs-toggle="modal" data-bs-target="#delete_modal_{{ $request->id }}">
                                            🗑️ Supprimer
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Assigner Concierge --}}
                        <div class="modal fade" id="assign_modal_{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Assigner un concierge</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.luxury.requests.assign', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label required">Nom du concierge</label>
                                                <input type="text" name="concierge_assigne" class="form-control" required />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Assigner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Changer Statut --}}
                        <div class="modal fade" id="status_modal_{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Changer le statut</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.luxury.requests.update-status', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label required">Nouveau statut</label>
                                                <select name="statut" class="form-select" required>
                                                    <option value="nouvelle" {{ $request->statut === 'nouvelle' ? 'selected' : '' }}>Nouvelle demande</option>
                                                    <option value="en_analyse" {{ $request->statut === 'en_analyse' ? 'selected' : '' }}>En cours d'analyse</option>
                                                    <option value="devis_envoye" {{ $request->statut === 'devis_envoye' ? 'selected' : '' }}>Devis envoyé</option>
                                                    <option value="en_negociation" {{ $request->statut === 'en_negociation' ? 'selected' : '' }}>En négociation</option>
                                                    <option value="confirme" {{ $request->statut === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                                    <option value="en_preparation" {{ $request->statut === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                                    <option value="en_cours" {{ $request->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                                    <option value="termine" {{ $request->statut === 'termine' ? 'selected' : '' }}>Terminé</option>
                                                    <option value="annule" {{ $request->statut === 'annule' ? 'selected' : '' }}>Annulé</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Suppression --}}
                        <div class="modal fade" id="delete_modal_{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir supprimer la demande <strong>{{ $request->reference }}</strong> ?</p>
                                        <p class="text-danger">Cette action est irréversible.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('admin.luxury.requests.destroy', $request) }}" method="POST" class="d-inline">
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
                    Affichage de {{ $requests->firstItem() }} à {{ $requests->lastItem() }} sur {{ $requests->total() }} demandes
                </div>
                <div>
                    {{ $requests->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal de filtrage --}}
<div class="modal fade" id="filter_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.luxury.requests.index') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">Filtrer les demandes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="nouvelle">Nouvelle demande</option>
                            <option value="en_analyse">En cours d'analyse</option>
                            <option value="devis_envoye">Devis envoyé</option>
                            <option value="en_negociation">En négociation</option>
                            <option value="confirme">Confirmé</option>
                            <option value="en_preparation">En préparation</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Priorité</label>
                        <select name="priorite" class="form-select">
                            <option value="">Toutes les priorités</option>
                            <option value="normale">Normale</option>
                            <option value="urgente">Urgente</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Concierge</label>
                        <input type="text" name="concierge" class="form-control" placeholder="Nom du concierge...">
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Période</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" name="date_debut" class="form-control">
                            </div>
                            <div class="col-6">
                                <input type="date" name="date_fin" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="Client, destination...">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.luxury.requests.index') }}" class="btn btn-light">Réinitialiser</a>
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
            const rows = document.querySelectorAll('#requests_table tbody tr');
            
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