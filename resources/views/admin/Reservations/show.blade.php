@extends('layouts.app')

@section('title', 'Détail Réservation #' . $reservation->id)
@section('page-title', 'Détail Réservation')
@section('page-subtitle', 'Réf: REF-' . str_pad($reservation->id, 6, '0', STR_PAD_LEFT))

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="{{ route('reservations.index') }}" class="btn btn-light btn-sm">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="black"/>
                </svg>
            </span>
            Retour à la liste
        </a>
        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-light-primary btn-sm">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9961 6.37355 21.9961 6.91345C21.9961 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"/>
                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3Z" fill="black"/>
                    <path d="M20.4615 8.65304L18.3025 10.811L12.5465 5.055L14.7055 2.897C15.0873 2.51528 15.6051 2.30093 16.145 2.30093C16.6849 2.30093 17.2027 2.51528 17.5845 2.897L20.4615 5.774C20.8432 6.15581 21.0576 6.67355 21.0576 7.21345C21.0576 7.75335 20.8432 8.27122 20.4615 8.65304Z" fill="black"/>
                </svg>
            </span>
            Modifier
        </a>
        <a href="{{ route('reservations.pdf', $reservation) }}" class="btn btn-primary btn-sm" target="_blank">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7.5 13.4 7.5 14C7.5 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 18.5 12 18.5C12.6 18.5 12.5 18.6 12.5 18V14.5H16C16.6 14.5 16.5 14.6 16.5 14C16.5 13.4 16.6 13.5 16 13.5Z" fill="black"/>
                    <rect x="11" y="19" width="10" height="2" rx="1" fill="black"/>
                    <rect x="7" y="13" width="5" height="2" rx="1" fill="black"/>
                </svg>
            </span>
            Télécharger PDF
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xl-8">
            <!--begin::Details Card-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Informations de la Réservation</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Détails complets de la demande</span>
                    </h3>
                    <div class="card-toolbar">
                        @if($reservation->status === 'confirmed')
                            <span class="badge badge-light-success fs-7 fw-bold">Confirmée</span>
                        @elseif($reservation->status === 'pending')
                            <span class="badge badge-light-warning fs-7 fw-bold">En attente</span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge badge-light-danger fs-7 fw-bold">Annulée</span>
                        @else
                            <span class="badge badge-light-info fs-7 fw-bold">Terminée</span>
                        @endif
                    </div>
                </div>
                <!--end::Header-->
                
                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <tbody>
                                <tr>
                                    <td class="fw-bolder text-muted">Référence</td>
                                    <td class="text-gray-900 fw-bolder">REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder text-muted">Client</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <div class="symbol-label bg-light-primary text-primary fs-6 fw-bolder">
                                                    {{ strtoupper(substr($reservation->first_name, 0, 1) . substr($reservation->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span class="text-gray-900 fw-bolder fs-6">{{ $reservation->first_name }} {{ $reservation->last_name }}</span>
                                                <a href="mailto:{{ $reservation->email }}" class="text-muted text-hover-primary fw-bold fs-7">{{ $reservation->email }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if($reservation->phone)
                                <tr>
                                    <td class="fw-bolder text-muted">Téléphone</td>
                                    <td class="text-gray-900">{{ $reservation->phone }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bolder text-muted">Itinéraire</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fw-bolder me-3">{{ $reservation->departure_location }}</span>
                                            <span class="svg-icon svg-icon-3 text-muted me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"/>
                                                    <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
                                                </svg>
                                            </span>
                                            <span class="text-gray-900 fw-bolder">{{ $reservation->arrival_location }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder text-muted">Date de départ</td>
                                    <td class="text-gray-900">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder text-muted">Date d'arrivée</td>
                                    <td class="text-gray-900">{{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder text-muted">Nombre de passagers</td>
                                    <td>
                                        <span class="badge badge-light-primary fs-7">
                                            <span class="svg-icon svg-icon-4 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M16 15.6315C16 16.7754 15.3284 17.8236 14.2733 18.3315L13 19.0657V20.5C13 21.0523 12.5523 21.5 12 21.5C11.4477 21.5 11 21.0523 11 20.5V19.0657L9.72671 18.3315C8.67159 17.8236 8 16.7754 8 15.6315V13.5C8 12.3954 8.89543 11.5 10 11.5H14C15.1046 11.5 16 12.3954 16 13.5V15.6315Z" fill="black"/>
                                                    <path opacity="0.3" d="M12 11.5C13.3807 11.5 14.5 10.3807 14.5 9C14.5 7.61929 13.3807 6.5 12 6.5C10.6193 6.5 9.5 7.61929 9.5 9C9.5 10.3807 10.6193 11.5 12 11.5Z" fill="black"/>
                                                    <path opacity="0.3" d="M7.5 6C8.32843 6 9 5.32843 9 4.5C9 3.67157 8.32843 3 7.5 3C6.67157 3 6 3.67157 6 4.5C6 5.32843 6.67157 6 7.5 6ZM16.5 6C17.3284 6 18 5.32843 18 4.5C18 3.67157 17.3284 3 16.5 3C15.6716 3 15 3.67157 15 4.5C15 5.32843 15.6716 6 16.5 6Z" fill="black"/>
                                                </svg>
                                            </span>
                                            {{ $reservation->passengers }} passager(s)
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder text-muted">Type d'avion</td>
                                    <td>
                                        @if($reservation->jet)
                                            <div class="d-flex align-items-center">
                                                @if($reservation->jet->image)
                                                    <div class="symbol symbol-50px me-3">
                                                        <img src="{{ $reservation->jet->image_url }}" alt="{{ $reservation->jet->nom }}" class="w-100"/>
                                                    </div>
                                                @endif
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-900 fw-bolder">{{ $reservation->jet->nom }}</span>
                                                    @if($reservation->jet->modele)
                                                        <span class="text-muted fs-7">{{ $reservation->jet->modele }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-900">{{ $reservation->plane_type ?? 'Non spécifié' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($reservation->message)
                                <tr>
                                    <td class="fw-bolder text-muted">Message/Demandes</td>
                                    <td class="text-gray-900">{{ $reservation->message }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bolder text-muted">Date de création</td>
                                    <td class="text-gray-900">{{ $reservation->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                @if($reservation->status_updated_at)
                                <tr>
                                    <td class="fw-bolder text-muted">Dernière mise à jour</td>
                                    <td class="text-gray-900">{{ $reservation->status_updated_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Details Card-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::Actions Card-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Actions</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Gérer cette réservation</span>
                    </h3>
                </div>
                <!--end::Header-->
                
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="d-flex flex-column gap-3">
                        @if($reservation->status === 'pending')
                            <button class="btn btn-success btn-sm" onclick="updateStatus({{ $reservation->id }}, 'confirmed')">
                                <span class="svg-icon svg-icon-3 me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                                    </svg>
                                </span>
                                Confirmer la réservation
                            </button>
                        @endif
                        
                        @if($reservation->status !== 'cancelled')
                            <button class="btn btn-danger btn-sm" onclick="updateStatus({{ $reservation->id }}, 'cancelled')">
                                <span class="svg-icon svg-icon-3 me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                                    </svg>
                                </span>
                                Annuler la réservation
                            </button>
                        @endif
                        
                        <a href="mailto:{{ $reservation->email }}" class="btn btn-light-info btn-sm">
                            <span class="svg-icon svg-icon-3 me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="black"/>
                                    <path d="M21 5H2.99999C2.69999 5 2.49999 5.3 2.59999 5.5L11.4 14.3C11.8 14.7 12.2 14.7 12.6 14.3L21.4 5.5C21.5 5.3 21.3 5 21 5Z" fill="black"/>
                                </svg>
                            </span>
                            Contacter le client
                        </a>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Actions Card-->

            <!--begin::Timeline Card-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Historique</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Suivi des modifications</span>
                    </h3>
                </div>
                <!--end::Header-->
                
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="timeline">
                        <!--begin::Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-success">
                                    <span class="svg-icon svg-icon-2 svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                            <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-bold mb-2">Réservation créée</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">{{ $reservation->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Timeline item-->
                        
                        @if($reservation->status_updated_at)
                        <!--begin::Timeline item-->
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-warning">
                                    <span class="svg-icon svg-icon-2 svg-icon-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black"/>
                                            <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-bold mb-2">Statut mis à jour</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">{{ $reservation->status_updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Timeline item-->
                        @endif
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Timeline Card-->
        </div>
        <!--end::Col-->
    </div>

    <!--begin::Hidden forms-->
    <form id="status-update-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="status-input">
    </form>
    <!--end::Hidden forms-->
@endsection

@push('scripts')
<script>
// Fonction pour mettre à jour le statut
function updateStatus(reservationId, newStatus) {
    const statusLabels = {
        'confirmed': 'confirmer',
        'cancelled': 'annuler'
    };
    
    Swal.fire({
        text: `Êtes-vous sûr de vouloir ${statusLabels[newStatus]} cette réservation ?`,
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: `Oui, ${statusLabels[newStatus]} !`,
        cancelButtonText: "Non, annuler",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-light"
        }
    }).then(function (result) {
        if (result.value) {
            const form = document.getElementById('status-update-form');
            form.action = `/reservations/${reservationId}/status`;
            document.getElementById('status-input').value = newStatus;
            form.submit();
        }
    });
}
</script>
@endpush