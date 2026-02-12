@extends('layouts.app')

@section('title', 'Détails du Jet')
@section('page-title', $jet->nom)
@section('page-subtitle', $jet->modele ? $jet->modele : 'Jet privé')

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="{{ route('jets.index') }}" class="btn btn-sm btn-light">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                </svg>
            </span>
            Retour à la liste
        </a>
        <a href="{{ route('jets.edit', $jet) }}" class="btn btn-sm btn-primary">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13699 14.115L2.06999 20.315C1.98815 20.5619 1.99022 20.8294 2.07581 21.0749C2.16141 21.3204 2.32718 21.5344 2.54699 21.685C2.67699 21.765 2.82699 21.825 2.98699 21.845C3.09699 21.845 3.19699 21.845 3.29699 21.845L3.68699 21.932Z" fill="black"/>
                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3Z" fill="black"/>
                    <path d="M4.13699 14.1152L9.88699 19.8652L19.8652 9.88699L14.1152 4.13699L4.13699 14.1152Z" fill="black"/>
                </svg>
            </span>
            Modifier
        </a>
        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete()">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"/>
                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"/>
                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"/>
                </svg>
            </span>
            Supprimer
        </button>
    </div>
@endsection

@section('content')
    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">
        
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Summary-->
                    <!--begin::User Info-->
                    <div class="d-flex flex-center flex-column py-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            @if($jet->image)
                                <img src="{{ asset('storage/' . $jet->image) }}" alt="{{ $jet->nom }}" />
                            @else
                                <div class="symbol-label bg-light-primary">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M22 7.5L19.5 5L17 7.5L19.5 10L22 7.5Z" fill="black"/>
                                            <path opacity="0.3" d="M2 7.5L4.5 5L7 7.5L4.5 10L2 7.5ZM12 16L9.5 13.5L7 16L9.5 18.5L12 16Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3">{{ $jet->nom }}</a>
                        <!--end::Name-->
                        <!--begin::Position-->
                        <div class="mb-9">
                            @if($jet->categorie)
                                <span class="badge badge-lg badge-light-{{ 
                                    $jet->categorie === 'Light' ? 'success' : 
                                    ($jet->categorie === 'Mid-size' ? 'warning' : 'primary') 
                                }}">{{ $jet->categorie }} Jet</span>
                            @endif
                            
                            @if($jet->disponible)
                                <span class="badge badge-lg badge-light-success ms-2">Disponible</span>
                            @else
                                <span class="badge badge-lg badge-light-danger ms-2">Indisponible</span>
                            @endif
                        </div>
                        <!--end::Position-->
                    </div>
                    <!--end::User Info-->
                    <!--end::Summary-->
                    <!--begin::Details toggle-->
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Détails
                            <span class="ms-2 rotate-180">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!--end::Details toggle-->
                    <div class="separator"></div>
                    <!--begin::Details content-->
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <!--begin::Details item-->
                            <div class="fw-bolder mt-5">Modèle</div>
                            <div class="text-gray-600">{{ $jet->modele ?: 'Non spécifié' }}</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bolder mt-5">Capacité</div>
                            <div class="text-gray-600">{{ $jet->capacite }} passagers</div>
                            <!--begin::Details item-->
                            <!--begin::Details item-->
                            <div class="fw-bolder mt-5">Prix par heure</div>
                            <div class="text-gray-600">$ {{ number_format($jet->prix, 0, ',', ' ') }}</div>
                            <!--begin::Details item-->
                            @if($jet->localisation)
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Localisation</div>
                                <div class="text-gray-600">{{ $jet->localisation }}</div>
                                <!--begin::Details item-->
                            @endif
                            @if($jet->autonomie_km)
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Autonomie</div>
                                <div class="text-gray-600">{{ number_format($jet->autonomie_km) }} km</div>
                                <!--begin::Details item-->
                            @endif
                            <!--begin::Details item-->
                            <div class="fw-bolder mt-5">Créé le</div>
                            <div class="text-gray-600">{{ $jet->created_at->format('d/m/Y à H:i') }}</div>
                            <!--begin::Details item-->
                        </div>
                    </div>
                    <!--end::Details content-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Connected Accounts-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card header-->
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Statistiques</h3>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-2">
                    <!--begin::Stats-->
                    <div class="d-flex flex-wrap">
                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-2 fw-bolder">{{ $jet->reservations->count() }}</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-bold fs-6 text-gray-400">Réservations</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->
                        <!--begin::Stat-->
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center">
                                <div class="fs-2 fw-bolder">{{ $jet->reservations()->where('status', 'confirmed')->count() }}</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-bold fs-6 text-gray-400">Confirmées</div>
                            <!--end::Label-->
                        </div>
                        <!--end::Stat-->
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Connected Accounts-->

        </div>
        <!--end::Sidebar-->

        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-15">
            
            <!--begin::Tab Content-->
            <div class="tab-content" id="myTabContent">
                
                <!--begin::Tab Pane-->
                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    
                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">Description</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body p-9 pt-4">
                            <!--begin::Billing form-->
                            <div class="row mb-7">
                                <div class="col-md-12">
                                    @if($jet->description)
                                        <p class="text-gray-600 fs-6">{{ $jet->description }}</p>
                                    @else
                                        <p class="text-muted fs-6 fst-italic">Aucune description disponible pour ce jet.</p>
                                    @endif
                                </div>
                            </div>
                            <!--end::Billing form-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                    <!--begin::Card-->
                    @if($jet->images && is_array($jet->images) && count($jet->images) > 0)
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header mt-6">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Galerie d'Images</h2>
                                    <div class="fs-6 fw-bold text-muted">{{ count($jet->images) }} image(s) supplémentaire(s)</div>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body p-9 pt-4">
                                <!--begin::Gallery-->
                                <div class="row g-6 g-xl-9">
                                    @foreach($jet->images as $index => $image)
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <!--begin::Card-->
                                            <div class="card h-100">
                                                <!--begin::Card body-->
                                                <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                                                    <!--begin::Name-->
                                                    <a href="{{ asset('storage/' . $image) }}" class="text-gray-800 text-hover-primary d-flex flex-column" data-fslightbox="gallery">
                                                        <!--begin::Image-->
                                                        <div class="symbol symbol-75px mx-auto mb-5">
                                                            <img src="{{ asset('storage/' . $image) }}" alt="Image {{ $index + 1 }}" class="w-100 h-100 object-fit-cover" />
                                                        </div>
                                                        <!--end::Image-->
                                                        <!--begin::Info-->
                                                        <div class="fs-5 fw-bolder mb-2">Image {{ $index + 1 }}</div>
                                                        <!--end::Info-->
                                                    </a>
                                                    <!--end::Name-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                            <!--end::Card-->
                                        </div>
                                    @endforeach
                                </div>
                                <!--end::Gallery-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    @endif
                    <!--end::Card-->

                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h2 class="mb-1">Réservations Récentes</h2>
                                <div class="fs-6 fw-bold text-muted">{{ $jet->reservations->count() }} réservation(s) au total</div>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <a href="{{ route('reservations.index', ['jet_id' => $jet->id]) }}" class="btn btn-sm btn-light">Voir toutes</a>
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body p-9 pt-4">
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <!--begin::Table head-->
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                        <!--begin::Table row-->
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-100px">Client</th>
                                            <th class="min-w-125px">Route</th>
                                            <th class="min-w-100px">Dates</th>
                                            <th class="min-w-100px">Statut</th>
                                            <th class="min-w-100px">Passagers</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fs-6 fw-bold text-gray-600">
                                        @forelse ($jet->reservations()->latest()->take(10)->get() as $reservation)
                                            <tr>
                                                <!--begin::Client-->
                                                <td>{{ $reservation->first_name }} {{ $reservation->last_name }}</td>
                                                <!--end::Client-->
                                                <!--begin::Route-->
                                                <td>{{ $reservation->departure_location }} → {{ $reservation->arrival_location }}</td>
                                                <!--end::Route-->
                                                <!--begin::Dates-->
                                                <td>{{ $reservation->departure_date->format('d/m/Y') }}</td>
                                                <!--end::Dates-->
                                                <!--begin::Status-->
                                                <td>
                                                    @if($reservation->status === 'confirmed')
                                                        <span class="badge badge-light-success">Confirmée</span>
                                                    @elseif($reservation->status === 'pending')
                                                        <span class="badge badge-light-warning">En attente</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Annulée</span>
                                                    @endif
                                                </td>
                                                <!--end::Status-->
                                                <!--begin::Passengers-->
                                                <td>{{ $reservation->passengers }}</td>
                                                <!--end::Passengers-->
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-10">
                                                    Aucune réservation pour ce jet
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                </div>
                <!--end::Tab Pane-->

            </div>
            <!--end::Tab Content-->

        </div>
        <!--end::Content-->

    </div>
    <!--end::Layout-->

    <!--begin::Delete form-->
    <form id="delete-form" action="{{ route('jets.destroy', $jet) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!--end::Delete form-->

@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        text: "Êtes-vous sûr de vouloir supprimer ce jet ? Cette action est irréversible.",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Oui, supprimer !",
        cancelButtonText: "Non, annuler",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-light"
        }
    }).then(function (result) {
        if (result.value) {
            document.getElementById('delete-form').submit();
        }
    });
}

// Initialize lightbox for gallery
document.addEventListener('DOMContentLoaded', function() {
    // Fslightbox is loaded by Metronic
    refreshFsLightbox();
});
</script>
@endpush