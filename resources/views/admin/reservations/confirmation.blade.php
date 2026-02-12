@extends('layouts.app')

@section('title', 'Confirmation d\'action')
@section('page-title', 'Action effectuée')
@section('page-subtitle', 'Confirmation de modification')

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-light btn-sm">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black"/>
                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black"/>
                </svg>
            </span>
            Voir détails
        </a>
        <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-sm">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="black"/>
                </svg>
            </span>
            Retour à la liste
        </a>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!--begin::Card-->
            <div class="card shadow-sm">
                <!--begin::Card body-->
                <div class="card-body text-center py-10 px-10">
                    
                    @if(session('action') === 'confirmed')
                        <!--begin::Success Icon-->
                        <div class="mb-10">
                            <div class="symbol symbol-100px mx-auto mb-5">
                                <div class="symbol-label bg-light-success">
                                    <span class="svg-icon svg-icon-5x svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Success Icon-->
                        
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-5">Réservation Confirmée !</h1>
                        <!--end::Title-->
                        
                        <!--begin::Description-->
                        <div class="fw-bold fs-6 text-gray-600 mb-8">
                            La réservation <strong>REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</strong> 
                            de <strong>{{ $reservation->full_name }}</strong> 
                            a été confirmée avec succès.
                        </div>
                        <!--end::Description-->
                        
                        <!--begin::Details-->
                        <div class="bg-light-success rounded p-6 mb-8">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="fw-bold text-gray-700 mb-3">Détails de la confirmation :</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Route :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->departure_location }} → {{ $reservation->arrival_location }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Date :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->departure_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Passagers :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->passengers }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Jet :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->jet->nom ?? 'Non spécifié' }}</div>
                                </div>
                            </div>
                        </div>
                        <!--end::Details-->

                    @elseif(session('action') === 'cancelled')
                        <!--begin::Warning Icon-->
                        <div class="mb-10">
                            <div class="symbol symbol-100px mx-auto mb-5">
                                <div class="symbol-label bg-light-danger">
                                    <span class="svg-icon svg-icon-5x svg-icon-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Warning Icon-->
                        
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-5">Réservation Annulée</h1>
                        <!--end::Title-->
                        
                        <!--begin::Description-->
                        <div class="fw-bold fs-6 text-gray-600 mb-8">
                            La réservation <strong>REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</strong> 
                            de <strong>{{ $reservation->full_name }}</strong> 
                            a été annulée.
                        </div>
                        <!--end::Description-->
                        
                        <!--begin::Details-->
                        <div class="bg-light-danger rounded p-6 mb-8">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="fw-bold text-gray-700 mb-3">Réservation annulée :</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Route :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->departure_location }} → {{ $reservation->arrival_location }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">Date :</div>
                                    <div class="fw-bold text-gray-900">{{ $reservation->departure_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-warning mt-3">
                                        <div class="d-flex align-items-center">
                                            <span class="svg-icon svg-icon-2 svg-icon-warning me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"/>
                                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"/>
                                                </svg>
                                            </span>
                                            <div class="text-gray-700">
                                                <strong>Note :</strong> Un email de notification a été envoyé au client.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Details-->

                    @elseif(session('action') === 'completed')
                        <!--begin::Info Icon-->
                        <div class="mb-10">
                            <div class="symbol symbol-100px mx-auto mb-5">
                                <div class="symbol-label bg-light-info">
                                    <span class="svg-icon svg-icon-5x svg-icon-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8V12H4C3.4 12 3 11.6 3 11V9C3 8.4 3.4 8 4 8H10Z" fill="black"/>
                                            <path opacity="0.3" d="M21 15V17C21 17.6 20.6 18 20 18H14V14H20C20.6 14 21 14.4 21 15ZM10 14V18H4C3.4 18 3 17.6 3 17V15C3 14.4 3.4 14 4 14H10Z" fill="black"/>
                                            <path d="M12 3C11.4 3 11 3.4 11 4V20C11 20.6 11.4 21 12 21C12.6 21 13 20.6 13 20V4C13 3.4 12.6 3 12 3Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Info Icon-->
                        
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-5">Vol Terminé</h1>
                        <!--end::Title-->
                        
                        <!--begin::Description-->
                        <div class="fw-bold fs-6 text-gray-600 mb-8">
                            La réservation <strong>REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</strong> 
                            a été marquée comme terminée avec succès.
                        </div>
                        <!--end::Description-->

                    @else
                        <!--begin::Default Icon-->
                        <div class="mb-10">
                            <div class="symbol symbol-100px mx-auto mb-5">
                                <div class="symbol-label bg-light-primary">
                                    <span class="svg-icon svg-icon-5x svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                            <rect x="11" y="7" width="2" height="6" rx="1" fill="black"/>
                                            <rect x="11" y="15" width="2" height="2" rx="1" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Default Icon-->
                        
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-5">Action Effectuée</h1>
                        <!--end::Title-->
                        
                        <!--begin::Description-->
                        <div class="fw-bold fs-6 text-gray-600 mb-8">
                            L'action sur la réservation <strong>REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</strong> 
                            a été effectuée avec succès.
                        </div>
                        <!--end::Description-->
                    @endif
                    
                    <!--begin::Actions-->
                    <div class="d-flex flex-center flex-wrap gap-3">
                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-primary">
                            <span class="svg-icon svg-icon-3 me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black"/>
                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black"/>
                                </svg>
                            </span>
                            Voir la réservation
                        </a>
                        
                        <a href="{{ route('reservations.index') }}" class="btn btn-light">
                            <span class="svg-icon svg-icon-3 me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="black"/>
                                </svg>
                            </span>
                            Retour à la liste
                        </a>
                        
                        @if(session('action') === 'confirmed')
                            <a href="mailto:{{ $reservation->email }}" class="btn btn-light-success">
                                <span class="svg-icon svg-icon-3 me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="black"/>
                                        <path d="M21 5H2.99999C2.69999 5 2.49999 5.3 2.59999 5.5L11.4 14.3C11.8 14.7 12.2 14.7 12.6 14.3L21.4 5.5C21.5 5.3 21.3 5 21 5Z" fill="black"/>
                                    </svg>
                                </span>
                                Contacter le client
                            </a>
                            
                            <a href="{{ route('reservations.pdf', $reservation) }}" class="btn btn-light-info" target="_blank">
                                <span class="svg-icon svg-icon-3 me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7.5 13.4 7.5 14C7.5 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 18.5 12 18.5C12.6 18.5 12.5 18.6 12.5 18V14.5H16C16.6 14.5 16.5 14.6 16.5 14C16.5 13.4 16.6 13.5 16 13.5Z" fill="black"/>
                                        <rect x="11" y="19" width="10" height="2" rx="1" fill="black"/>
                                        <rect x="7" y="13" width="5" height="2" rx="1" fill="black"/>
                                    </svg>
                                </span>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Auto-redirection après 8 secondes (optionnel)
// setTimeout(function() {
//     window.location.href = "{{ route('reservations.index') }}";
// }, 8000);

// Animation d'entrée
$(document).ready(function() {
    $('.card').hide().fadeIn(1000);
});
</script>
@endpush