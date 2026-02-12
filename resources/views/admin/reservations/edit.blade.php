@extends('layouts.app')

@section('page-title', 'Modifier la réservation #' . $reservation->id)
@section('page-subtitle', 'Modification des détails')

@section('toolbar-actions')
<div class="d-flex align-items-center gap-2">
    <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-light">
        <i class="ki-duotone ki-eye fs-4 me-1"></i>
        Voir les détails
    </a>
    <a href="{{ route('reservations.pdf', $reservation) }}" class="btn btn-sm btn-light">
        <i class="ki-duotone ki-file-down fs-4 me-1"></i>
        Télécharger PDF
    </a>
    <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-light">
        <i class="ki-duotone ki-arrow-left fs-4 me-1"></i>
        Retour à la liste
    </a>
</div>
@endsection

@section('content')
<!--begin::Form-->
<form id="kt_reservation_form" class="form d-flex flex-column flex-lg-row"
    action="{{ route('reservations.update', $reservation) }}" method="POST">
    @csrf
    @method('PUT')

    <!--begin::Aside column-->
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <!--begin::Actions card-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Statut & Actions</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body text-center pt-0">
                <!--begin::Status badge-->
                <div class="mb-5">
                    <span
                        class="badge badge-lg badge-light-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'cancelled' ? 'danger' : ($reservation->status == 'completed' ? 'info' : 'warning')) }}">
                        @switch($reservation->status)
                        @case('confirmed')
                        Confirmée
                        @break
                        @case('cancelled')
                        Annulée
                        @break
                        @case('completed')
                        Terminée
                        @break
                        @default
                        En attente
                        @endswitch
                    </span>
                </div>
                <!--end::Status badge-->

                <!--begin::Status-->
                <div class="mb-5">
                    <label class="form-label">Changer le statut</label>
                    <select name="status" class="form-select" data-control="select2"
                        data-placeholder="Sélectionner un statut">
                        <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmée
                        </option>
                        <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Annulée
                        </option>
                        <option value="completed" {{ $reservation->status == 'completed' ? 'selected' : '' }}>Terminée
                        </option>
                    </select>
                </div>
                <!--end::Status-->

                <!--begin::Actions-->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="kt_save_button">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-check fs-2"></i>
                            Sauvegarder les modifications
                        </span>
                        <span class="indicator-progress">
                            Sauvegarde... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="ki-duotone ki-trash fs-2"></i>
                        Supprimer
                    </button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Actions card-->

        <!--begin::Jet selection-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Jet assigné</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if($reservation->jet)
                <div class="mb-3 p-3 bg-light-success rounded">
                    <div class="fw-bold text-success">Jet actuel :</div>
                    <div class="fw-semibold">{{ $reservation->jet->nom }}</div>
                    <div class="text-muted fs-7">
                        {{ $reservation->jet->capacite }} passagers
                        @if($reservation->jet->prix)
                        • $ {{ number_format($reservation->jet->prix) }}/h
                        @endif
                    </div>
                </div>
                @endif

                <select name="jet_id" class="form-select" data-control="select2" data-placeholder="Sélectionner un jet">
                    <option></option>
                    @foreach($jets as $jet)
                    <option value="{{ $jet->id }}" data-capacity="{{ $jet->capacite }}"
                        data-price="{{ $jet->prix ?? 0 }}" {{ $reservation->jet_id == $jet->id ? 'selected' : '' }}>
                        {{ $jet->nom }} ({{ $jet->capacite }} passagers)
                        @if($jet->prix)
                        - $ {{ number_format($jet->prix) }}/h
                        @endif
                    </option>
                    @endforeach
                </select>
                <div class="text-muted fs-7 mt-2">Laisser vide pour retirer l'assignation</div>
                <div id="jet-info" class="mt-3 p-3 bg-light-info rounded d-none">
                    <div class="fw-bold text-info">Informations du jet :</div>
                    <div id="jet-details"></div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Jet selection-->

        <!--begin::Timeline-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Historique</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-plus fs-2 text-primary"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-5 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-7 mb-2">Réservation créée</div>
                                <div class="text-muted fs-8">{{ $reservation->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($reservation->updated_at != $reservation->created_at)
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-pencil fs-2 text-warning"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-5 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-7 mb-2">Dernière modification</div>
                                <div class="text-muted fs-8">{{ $reservation->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Timeline-->
    </div>
    <!--end::Aside column-->

    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin::Passenger info-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Informations du passager principal</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Prénom</label>
                        <input type="text" name="first_name" class="form-control mb-2" placeholder="Prénom du passager"
                            value="{{ old('first_name', $reservation->first_name) }}" required />
                        @error('first_name')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Nom</label>
                        <input type="text" name="last_name" class="form-control mb-2" placeholder="Nom du passager"
                            value="{{ old('last_name', $reservation->last_name) }}" required />
                        @error('last_name')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Email</label>
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email du passager"
                            value="{{ old('email', $reservation->email) }}" required />
                        @error('email')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-control mb-2" placeholder="Numéro de téléphone"
                            value="{{ old('phone', $reservation->phone) }}" />
                        @error('phone')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="mb-6 fv-row">
                    <label class="required form-label">Nombre de passagers</label>
                    <input type="number" name="passengers" class="form-control mb-2" placeholder="Nombre de passagers"
                        value="{{ old('passengers', $reservation->passengers) }}" min="1" max="20" required />
                    <div class="form-text">Maximum 20 passagers</div>
                    @error('passengers')
                    <div class="text-danger fs-7">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Input group-->

                <div class="separator separator-dashed my-6"></div>
                <h4 class="mb-6">Informations APIS (Requis GCAA)</h4>

                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Numéro de Passeport / Passport #</label>
                        <input type="text" name="passport_number" class="form-control mb-2" placeholder="X0000000"
                            value="{{ old('passport_number', $reservation->passport_number) }}" />
                        @error('passport_number')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Date d'expiration / Expiry Date</label>
                        <input type="date" name="passport_expiry" class="form-control mb-2"
                            value="{{ old('passport_expiry', $reservation->passport_expiry ? $reservation->passport_expiry->format('Y-m-d') : '') }}" />
                        @error('passport_expiry')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Date de naissance / DOB</label>
                        <input type="date" name="date_of_birth" class="form-control mb-2"
                            value="{{ old('date_of_birth', $reservation->date_of_birth ? $reservation->date_of_birth->format('Y-m-d') : '') }}" />
                        @error('date_of_birth')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Nationalité / Nationality</label>
                        <input type="text" name="nationality" class="form-control mb-2" placeholder="Ghanaian"
                            value="{{ old('nationality', $reservation->nationality) }}" />
                        @error('nationality')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <div class="separator separator-dashed my-6"></div>
                <h4 class="mb-6">Bagages</h4>

                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Nombre de sacs</label>
                        <input type="number" name="luggage_count" class="form-control mb-2" placeholder="0"
                            value="{{ old('luggage_count', $reservation->luggage_count) }}" min="0" />
                        @error('luggage_count')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="form-label">Poids total (kg)</label>
                        <input type="number" step="0.1" name="luggage_weight_kg" class="form-control mb-2"
                            placeholder="0.0" value="{{ old('luggage_weight_kg', $reservation->luggage_weight_kg) }}"
                            min="0" />
                        @error('luggage_weight_kg')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <div class="separator separator-dashed my-6"></div>

                <!--begin::Input group-->
                <div class="fv-row mb-6">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="data_protection_consent" value="1"
                            id="data_protection_consent" {{ old('data_protection_consent',
                            $reservation->data_protection_consent) ? 'checked' : '' }} required />
                        <label class="form-check-label" for="data_protection_consent">
                            Consentement à la protection des données (Act 843)
                        </label>
                    </div>
                    @error('data_protection_consent')
                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Passenger info-->

        <!--begin::Flight details-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Détails du vol</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Lieu de départ</label>
                        <input type="text" name="departure_location" class="form-control mb-2"
                            placeholder="Ville ou aéroport de départ"
                            value="{{ old('departure_location', $reservation->departure_location) }}" required />
                        @error('departure_location')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Lieu d'arrivée</label>
                        <input type="text" name="arrival_location" class="form-control mb-2"
                            placeholder="Ville ou aéroport d'arrivée"
                            value="{{ old('arrival_location', $reservation->arrival_location) }}" required />
                        @error('arrival_location')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Date et heure de départ</label>
                        <input type="datetime-local" name="departure_date" class="form-control mb-2"
                            value="{{ old('departure_date', $reservation->departure_date ? \Carbon\Carbon::parse($reservation->departure_date)->format('Y-m-d\TH:i') : '') }}"
                            required />
                        @error('departure_date')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="required form-label">Date et heure d'arrivée</label>
                        <input type="datetime-local" name="arrival_date" class="form-control mb-2"
                            value="{{ old('arrival_date', $reservation->arrival_date ? \Carbon\Carbon::parse($reservation->arrival_date)->format('Y-m-d\TH:i') : '') }}"
                            required />
                        @error('arrival_date')
                        <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="mb-6 fv-row">
                    <label class="form-label">Message / Demandes spéciales</label>
                    <textarea name="message" class="form-control mb-2" rows="4"
                        placeholder="Demandes spéciales, allergies, équipements particuliers...">{{ old('message', $reservation->message ?? '') }}</textarea>
                    <div class="form-text">Optionnel - Toute information importante pour le vol</div>
                    @error('message')
                    <div class="text-danger fs-7">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Flight details-->
    </div>
    <!--end::Main column-->
</form>
<!--end::Form-->

<!--begin::Delete form-->
<form id="delete-form" action="{{ route('reservations.destroy', $reservation) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<!--end::Delete form-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Éléments du formulaire
        const departureInput = document.querySelector('input[name="departure_date"]');
        const arrivalInput = document.querySelector('input[name="arrival_date"]');
        const passengersInput = document.querySelector('input[name="passengers"]');
        const jetSelect = document.querySelector('select[name="jet_id"]');
        const jetInfo = document.getElementById('jet-info');
        const jetDetails = document.getElementById('jet-details');

        // Valider que l'arrivée est après le départ
        departureInput.addEventListener('change', function () {
            if (this.value) {
                arrivalInput.min = this.value;
                if (arrivalInput.value && arrivalInput.value <= this.value) {
                    arrivalInput.value = '';
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('La date d\'arrivée doit être postérieure à la date de départ.');
                    } else {
                        alert('La date d\'arrivée doit être postérieure à la date de départ.');
                    }
                }
            }
        });

        // Afficher les infos du jet sélectionné
        jetSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                const capacity = selectedOption.getAttribute('data-capacity');
                const price = selectedOption.getAttribute('data-price');
                const passengers = passengersInput.value;

                // Afficher les infos
                jetDetails.innerHTML = `
                <div class="fs-7">Capacité: <strong>${capacity} passagers</strong></div>
                ${price && price > 0 ? `<div class="fs-7">Prix: <strong>${new Intl.NumberFormat('fr-FR').format(price)}$/h</strong></div>` : ''}
            `;
                jetInfo.classList.remove('d-none');

                // Vérifier la capacité
                if (passengers && parseInt(passengers) > parseInt(capacity)) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning(`Le jet sélectionné ne peut accueillir que ${capacity} passagers maximum.`);
                    } else {
                        alert(`Le jet sélectionné ne peut accueillir que ${capacity} passagers maximum.`);
                    }
                }
            } else {
                jetInfo.classList.add('d-none');
            }
        });

        // Vérifier la capacité lors du changement du nombre de passagers
        passengersInput.addEventListener('change', function () {
            const selectedOption = jetSelect.options[jetSelect.selectedIndex];
            const capacity = selectedOption ? selectedOption.getAttribute('data-capacity') : null;

            if (capacity && this.value && parseInt(this.value) > parseInt(capacity)) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning(`Le jet sélectionné ne peut accueillir que ${capacity} passagers maximum.`);
                } else {
                    alert(`Le jet sélectionné ne peut accueillir que ${capacity} passagers maximum.`);
                }
            }
        });

        // Validation du formulaire
        const form = document.getElementById('kt_reservation_form');
        const saveButton = document.getElementById('kt_save_button');

        form.addEventListener('submit', function (e) {
            const departure = new Date(departureInput.value);
            const arrival = new Date(arrivalInput.value);

            if (departure >= arrival) {
                e.preventDefault();
                if (typeof toastr !== 'undefined') {
                    toastr.error('La date d\'arrivée doit être postérieure à la date de départ.');
                } else {
                    alert('La date d\'arrivée doit être postérieure à la date de départ.');
                }
                return false;
            }

            // Animation du bouton
            saveButton.setAttribute('data-kt-indicator', 'on');
            saveButton.disabled = true;
        });
    });

    // Fonction de confirmation de suppression
    function confirmDelete() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action supprimera définitivement la réservation !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        } else {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
                document.getElementById('delete-form').submit();
            }
        }
    }
</script>
@endpush