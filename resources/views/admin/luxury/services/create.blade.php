{{-- resources/views/admin/luxury/services/create.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', 'Nouveau Service de Luxe')

@section('page-title', 'Services de Luxe')
@section('page-subtitle', 'Créer un nouveau service')

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.services.index') }}" class="btn btn-sm btn-light me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"/>
                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
            </svg>
        </span>
        Retour
    </a>
</div>
@endsection

@section('content')

<form action="{{ route('admin.luxury.services.store') }}" method="POST" enctype="multipart/form-data" id="kt_services_create_form">
    @csrf

    <div class="row g-5 g-xl-8">
        {{-- Colonne principale --}}
        <div class="col-xl-8">
            {{-- Informations générales --}}
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <span class="card-label fw-bolder fs-3 mb-1">Informations Générales</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-7">
                        <label class="required fs-6 fw-bold mb-2">Nom du service</label>
                        <input type="text" class="form-control form-control-solid @error('nom') is-invalid @enderror"
                               name="nom" value="{{ old('nom') }}" placeholder="Ex: Transport privé en limousine"/>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-7">
                        <label class="required fs-6 fw-bold mb-2">Catégorie</label>
                        <select class="form-select form-select-solid @error('categorie') is-invalid @enderror"
                                name="categorie" data-control="select2" data-placeholder="Sélectionner une catégorie">
                            <option></option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('categorie') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-7">
                        <label class="required fs-6 fw-bold mb-2">Description</label>
                        <textarea class="form-control form-control-solid @error('description') is-invalid @enderror"
                                  name="description" rows="5" placeholder="Décrivez votre service de luxe en détail...">{{ old('description') }}</textarea>
                        <div class="form-text">Maximum 2000 caractères</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tarification --}}
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <span class="card-label fw-bolder fs-3 mb-1">Tarification</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-7">
                        <label class="required fs-6 fw-bold mb-2">Type de tarification</label>
                        <select class="form-select form-select-solid @error('type_prix') is-invalid @enderror"
                                name="type_prix" data-control="select2" id="type_prix_select">
                            @foreach($typesPrix as $key => $label)
                                <option value="{{ $key }}" {{ old('type_prix', 'sur_devis') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_prix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-7" id="prix_base_container">
                        <label class="fs-6 fw-bold mb-2">Prix de base (€)</label>
                        <input type="number" class="form-control form-control-solid @error('prix_base') is-invalid @enderror"
                               name="prix_base" value="{{ old('prix_base') }}" placeholder="0.00" min="0" step="0.01"/>
                        <div class="form-text">Laissez vide pour "Sur devis"</div>
                        @error('prix_base')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Options disponibles --}}
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <span class="card-label fw-bolder fs-3 mb-1">Options Disponibles</span>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary" id="add-option">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                </svg>
                            </span>
                            Ajouter une option
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-text mb-5">Les options permettent de personnaliser le service avec des suppléments tarifaires</div>
                    <div id="options-container">
                        @if(old('options_disponibles'))
                            @foreach(old('options_disponibles') as $index => $option)
                            <div class="border border-gray-300 border-dashed rounded p-4 mb-4 option-item">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Option #<span class="option-number">{{ $index + 1 }}</span></h5>
                                    <button type="button" class="btn btn-sm btn-light-danger remove-option">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="fs-7 fw-bold mb-2">Clé (identifiant)</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                               name="options_disponibles[{{ $index }}][key]" value="{{ $option['key'] ?? '' }}"
                                               placeholder="Ex: vip_seat" required/>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="fs-7 fw-bold mb-2">Nom de l'option</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                               name="options_disponibles[{{ $index }}][nom]" value="{{ $option['nom'] ?? '' }}"
                                               placeholder="Ex: Siège VIP" required/>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="fs-7 fw-bold mb-2">Prix supplément (€)</label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                               name="options_disponibles[{{ $index }}][prix_supplement]" value="{{ $option['prix_supplement'] ?? '' }}"
                                               placeholder="0.00" min="0" step="0.01"/>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div id="no-options-message" class="text-center text-muted py-5" style="{{ old('options_disponibles') ? 'display:none;' : '' }}">
                        Aucune option ajoutée. Cliquez sur "Ajouter une option" pour commencer.
                    </div>
                </div>
            </div>

            {{-- Fournisseur --}}
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <span class="card-label fw-bolder fs-3 mb-1">Informations Fournisseur</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-7">
                        <label class="fs-6 fw-bold mb-2">Nom du fournisseur</label>
                        <input type="text" class="form-control form-control-solid @error('fournisseur') is-invalid @enderror"
                               name="fournisseur" value="{{ old('fournisseur') }}" placeholder="Ex: Prestige Limousines Paris"/>
                        @error('fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-7">
                        <label class="fs-6 fw-bold mb-2">Contact fournisseur</label>
                        <input type="text" class="form-control form-control-solid @error('contact_fournisseur') is-invalid @enderror"
                               name="contact_fournisseur" value="{{ old('contact_fournisseur') }}"
                               placeholder="Email, téléphone ou nom du contact"/>
                        @error('contact_fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-xl-4">
            {{-- Statut et Image --}}
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <span class="card-label fw-bolder fs-3 mb-1">Statut & Image</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-7">
                        <label class="fs-6 fw-bold mb-2">Statut</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="actif" value="1"
                                   id="service_actif" {{ old('actif', true) ? 'checked' : '' }}/>
                            <label class="form-check-label" for="service_actif">
                                Service actif
                            </label>
                        </div>
                        <div class="form-text">Un service inactif n'apparaît pas publiquement</div>
                    </div>

                    <div class="separator my-5"></div>

                    <div class="mb-7">
                        <label class="fs-6 fw-bold mb-2">Image du service</label>
                        <input type="file" class="form-control form-control-solid @error('image') is-invalid @enderror"
                               name="image" accept="image/jpeg,image/png,image/jpg,image/gif" id="image_input"/>
                        <div class="form-text">Formats: JPG, PNG, GIF. Max: 8MB</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="image_preview" class="mb-7" style="display:none;">
                        <img src="" alt="Aperçu" class="w-100 rounded"/>
                    </div>
                </div>
            </div>

            {{-- Aide --}}
            <div class="card bg-light-primary">
                <div class="card-body">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>Aide
                    </h5>
                    <ul class="ps-3 mb-0">
                        <li class="mb-2">Le <strong>type de tarification</strong> détermine comment le prix est calculé</li>
                        <li class="mb-2">Les <strong>options</strong> permettent d'ajouter des suppléments personnalisables</li>
                        <li class="mb-2">La <strong>clé</strong> de l'option doit être unique (ex: vip, premium)</li>
                        <li>Un service inactif reste visible dans l'administration mais pas publiquement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end mt-8">
        <a href="{{ route('admin.luxury.services.index') }}" class="btn btn-light me-3">Annuler</a>
        <button type="submit" class="btn btn-primary" id="submit_button">
            <span class="indicator-label">Créer le service</span>
            <span class="indicator-progress">Création en cours...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let optionIndex = {{ count(old('options_disponibles', [])) }};

    // Gestion de l'aperçu de l'image
    const imageInput = document.getElementById('image_input');
    const imagePreview = document.getElementById('image_preview');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    // Ajouter une option
    document.getElementById('add-option').addEventListener('click', function() {
        const container = document.getElementById('options-container');
        const noOptionsMessage = document.getElementById('no-options-message');

        const newOption = document.createElement('div');
        newOption.className = 'border border-gray-300 border-dashed rounded p-4 mb-4 option-item';
        newOption.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Option #<span class="option-number">${optionIndex + 1}</span></h5>
                <button type="button" class="btn btn-sm btn-light-danger remove-option">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="fs-7 fw-bold mb-2">Clé (identifiant)</label>
                    <input type="text" class="form-control form-control-sm form-control-solid"
                           name="options_disponibles[${optionIndex}][key]"
                           placeholder="Ex: vip_seat" required/>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="fs-7 fw-bold mb-2">Nom de l'option</label>
                    <input type="text" class="form-control form-control-sm form-control-solid"
                           name="options_disponibles[${optionIndex}][nom]"
                           placeholder="Ex: Siège VIP" required/>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="fs-7 fw-bold mb-2">Prix supplément (€)</label>
                    <input type="number" class="form-control form-control-sm form-control-solid"
                           name="options_disponibles[${optionIndex}][prix_supplement]"
                           placeholder="0.00" min="0" step="0.01"/>
                </div>
            </div>
        `;

        container.appendChild(newOption);
        noOptionsMessage.style.display = 'none';
        optionIndex++;
        updateOptionNumbers();
    });

    // Supprimer une option
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            e.target.closest('.option-item').remove();

            const optionItems = document.querySelectorAll('.option-item');
            if (optionItems.length === 0) {
                document.getElementById('no-options-message').style.display = 'block';
            }
            updateOptionNumbers();
        }
    });

    // Mettre à jour la numérotation des options
    function updateOptionNumbers() {
        document.querySelectorAll('.option-number').forEach((el, index) => {
            el.textContent = index + 1;
        });
    }

    // Validation et soumission du formulaire
    const form = document.getElementById('kt_services_create_form');
    const submitButton = document.getElementById('submit_button');

    form.addEventListener('submit', function(e) {
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
    });
});
</script>
@endpush
