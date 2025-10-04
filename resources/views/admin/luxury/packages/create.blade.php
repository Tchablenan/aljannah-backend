{{-- resources/views/admin/luxury/packages/create.blade.php --}}

@extends('layouts.luxery_services.app')

@section('title', 'Nouveau Package')

@section('page-title', 'Packages de Luxe')
@section('page-subtitle', 'Créer un nouveau package')

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.packages.index') }}" class="btn btn-sm btn-light">
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

<form action="{{ route('admin.luxury.packages.store') }}" method="POST" enctype="multipart/form-data" id="package_form">
    @csrf

    <div class="row g-5 g-xl-8">
        {{-- Colonne principale --}}
        <div class="col-xl-8">
            {{-- Informations générales --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Informations Générales</h3>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <label class="required form-label">Nom du package</label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                               value="{{ old('nom') }}" placeholder="Ex: Escapade Romantique à Paris" required />
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="required form-label">Description</label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Décrivez le package en détail..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Destination</label>
                            <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror" 
                                   value="{{ old('destination') }}" placeholder="Ex: Paris, France" />
                            @error('destination')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-5">
                            <label class="form-label">Durée</label>
                            <input type="text" name="duree" class="form-control @error('duree') is-invalid @enderror" 
                                   value="{{ old('duree') }}" placeholder="Ex: 3 jours / 2 nuits" />
                            @error('duree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label">Nombre de personnes</label>
                        <input type="number" name="nombre_personnes" class="form-control @error('nombre_personnes') is-invalid @enderror" 
                               value="{{ old('nombre_personnes', 2) }}" min="1" max="100" required />
                        @error('nombre_personnes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Services inclus --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Services Inclus</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary" id="add_service_btn">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                </svg>
                            </span>
                            Ajouter un service
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="services_container">
                        {{-- Les services seront ajoutés ici dynamiquement --}}
                    </div>

                    <div class="alert alert-info d-flex align-items-center" id="no_services_alert">
                        <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"/>
                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"/>
                            </svg>
                        </span>
                        <div class="d-flex flex-column">
                            <span>Aucun service ajouté. Cliquez sur "Ajouter un service" pour commencer.</span>
                        </div>
                    </div>

                    @error('services_inclus')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Personnalisations (optionnel) --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Personnalisations</h3>
                </div>
                <div class="card-body">
                    <div id="personnalisations_container">
                        <button type="button" class="btn btn-sm btn-light-primary" id="add_customization_btn">
                            Ajouter une personnalisation
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-xl-4">
            {{-- Type de package --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Type de Package</h3>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <label class="form-check form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" type="radio" name="type" value="predefinit" 
                                   {{ old('type', 'predefinit') === 'predefinit' ? 'checked' : '' }} required />
                            <span class="form-check-label">
                                <span class="fw-bolder">Package Prédéfini</span>
                                <span class="text-muted d-block fs-7">Prix fixe, visible au public</span>
                            </span>
                        </label>
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="type" value="personnalise" 
                                   {{ old('type') === 'personnalise' ? 'checked' : '' }} />
                            <span class="form-check-label">
                                <span class="fw-bolder">Package Personnalisé</span>
                                <span class="text-muted d-block fs-7">Sur mesure pour un client</span>
                            </span>
                        </label>
                    </div>

                    <div id="client_email_field" style="display: none;">
                        <label class="form-label">Email du client</label>
                        <input type="email" name="client_email" class="form-control" 
                               value="{{ old('client_email') }}" placeholder="client@example.com" />
                    </div>
                </div>
            </div>

            {{-- Prix --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Tarification</h3>
                </div>
                <div class="card-body">
                    <div id="prix_total_field">
                        <label class="form-label">Prix total (€)</label>
                        <input type="number" name="prix_total" class="form-control" 
                               value="{{ old('prix_total') }}" step="0.01" min="0" 
                               placeholder="Calculé automatiquement" />
                        <div class="form-text">Laissez vide pour calculer automatiquement</div>
                    </div>

                    <div id="prix_estime_field" style="display: none;">
                        <label class="form-label">Prix estimé (€)</label>
                        <input type="number" name="prix_estime" class="form-control" 
                               value="{{ old('prix_estime') }}" step="0.01" min="0" readonly />
                        <div class="form-text">Calculé automatiquement</div>
                    </div>

                    <div class="mt-5" id="prix_calcule_display">
                        <div class="border border-dashed border-gray-300 rounded p-5">
                            <div class="text-gray-600 fw-bold mb-2">Prix calculé</div>
                            <div class="fs-2 fw-bolder text-primary" id="prix_calcule_value">0,00 €</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Images --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Images</h3>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <label class="form-label">Image principale</label>
                        <input type="file" name="image_principale" class="form-control @error('image_principale') is-invalid @enderror" 
                               accept="image/*" id="image_principale_input" />
                        @error('image_principale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, PNG. Taille max: 4MB</div>
                        
                        <div id="image_principale_preview" class="mt-3" style="display: none;">
                            <img id="image_principale_preview_img" class="w-100 rounded" />
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Galerie d'images</label>
                        <input type="file" name="galerie_images[]" class="form-control" 
                               accept="image/*" multiple id="galerie_images_input" />
                        <div class="form-text">Format: JPG, PNG. Taille max: 2MB par image</div>
                        
                        <div id="galerie_preview" class="row g-3 mt-3"></div>
                    </div>
                </div>
            </div>

            {{-- Statut --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Statut</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                        <input class="form-check-input" type="checkbox" name="actif" value="1" 
                               {{ old('actif', true) ? 'checked' : '' }} id="actif_switch" />
                        <label class="form-check-label" for="actif_switch">
                            Package actif
                        </label>
                    </div>

                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="visible_public" value="1" 
                               {{ old('visible_public', true) ? 'checked' : '' }} id="visible_switch" />
                        <label class="form-check-label" for="visible_switch">
                            Visible au public
                        </label>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="black"/>
                                <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM16 11.6L12.7 8.29999C12.3 7.89999 11.7 7.89999 11.3 8.29999L8 11.6H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H16Z" fill="black"/>
                            </svg>
                        </span>
                        Créer le package
                    </button>
                    <a href="{{ route('admin.luxury.packages.index') }}" class="btn btn-light w-100">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Template pour service inclus --}}
<template id="service_template">
    <div class="service-item border border-gray-300 rounded p-5 mb-5" data-index="INDEX">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="mb-0">Service #<span class="service-number">1</span></h4>
            <button type="button" class="btn btn-sm btn-icon btn-light-danger remove-service">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                    </svg>
                </span>
            </button>
        </div>

        <div class="mb-4">
            <label class="required form-label">Service</label>
            <select name="services_inclus[INDEX][service_id]" class="form-select service-select" required>
                <option value="">Sélectionner un service</option>
                @foreach($categories as $catKey => $catLabel)
                    <optgroup label="{{ $catLabel }}">
                        @foreach($services->get($catKey, collect()) as $service)
                            <option value="{{ $service->id }}" 
                                    data-prix="{{ $service->prix_base }}" 
                                    data-type="{{ $service->type_prix }}">
                                {{ $service->nom }} - {{ $service->prix_base ? number_format($service->prix_base, 2) . ' €' : 'Sur devis' }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="required form-label">Quantité</label>
                <input type="number" name="services_inclus[INDEX][quantite]" class="form-control service-quantite" 
                       value="1" min="1" required />
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label">Durée</label>
                <input type="number" name="services_inclus[INDEX][duration]" class="form-control service-duration" 
                       value="1" min="0.5" step="0.5" placeholder="1" />
                <div class="form-text">En heures ou jours selon le service</div>
            </div>
        </div>

        <div class="service-options"></div>

        <div class="mt-3 p-3 bg-light-primary rounded">
            <div class="text-gray-700 fw-bold">Prix du service: <span class="service-price text-primary">0,00 €</span></div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = 0;

    // Gestion du type de package
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const clientEmailField = document.getElementById('client_email_field');
    const prixTotalField = document.getElementById('prix_total_field');
    const prixEstimeField = document.getElementById('prix_estime_field');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'personnalise') {
                clientEmailField.style.display = 'block';
                prixTotalField.style.display = 'none';
                prixEstimeField.style.display = 'block';
            } else {
                clientEmailField.style.display = 'none';
                prixTotalField.style.display = 'block';
                prixEstimeField.style.display = 'none';
            }
        });
    });

    // Ajouter un service
    document.getElementById('add_service_btn').addEventListener('click', function() {
        const template = document.getElementById('service_template');
        const clone = template.content.cloneNode(true);
        
        // Remplacer INDEX par le vrai index
        const html = clone.querySelector('.service-item').outerHTML.replace(/INDEX/g, serviceIndex);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        document.getElementById('services_container').appendChild(tempDiv.firstElementChild);
        document.getElementById('no_services_alert').style.display = 'none';
        
        updateServiceNumbers();
        serviceIndex++;
        calculateTotal();
    });

    // Supprimer un service
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            e.target.closest('.service-item').remove();
            updateServiceNumbers();
            calculateTotal();
            
            if (document.querySelectorAll('.service-item').length === 0) {
                document.getElementById('no_services_alert').style.display = 'block';
            }
        }
    });

    // Calculer le prix quand on change un service
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('service-select') || 
            e.target.classList.contains('service-quantite') || 
            e.target.classList.contains('service-duration')) {
            calculateTotal();
        }
    });

    function updateServiceNumbers() {
        document.querySelectorAll('.service-item').forEach((item, index) => {
            item.querySelector('.service-number').textContent = index + 1;
        });
    }

    function calculateTotal() {
        let total = 0;
        
        document.querySelectorAll('.service-item').forEach(item => {
            const select = item.querySelector('.service-select');
            const quantite = parseFloat(item.querySelector('.service-quantite').value) || 1;
            const duration = parseFloat(item.querySelector('.service-duration').value) || 1;
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                const prix = parseFloat(option.dataset.prix) || 0;
                const type = option.dataset.type;
                
                let servicePrice = prix;
                if (type === 'heure' || type === 'jour') {
                    servicePrice = prix * duration;
                }
                servicePrice *= quantite;
                
                total += servicePrice;
                item.querySelector('.service-price').textContent = servicePrice.toFixed(2) + ' €';
            }
        });
        
        document.getElementById('prix_calcule_value').textContent = total.toFixed(2).replace('.', ',') + ' €';
    }

    // Preview image principale
    document.getElementById('image_principale_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_principale_preview').style.display = 'block';
                document.getElementById('image_principale_preview_img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview galerie
    document.getElementById('galerie_images_input').addEventListener('change', function(e) {
        const preview = document.getElementById('galerie_preview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `<img src="${e.target.result}" class="w-100 rounded" />`;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush