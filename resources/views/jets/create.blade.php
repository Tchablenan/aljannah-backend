@extends('layouts.app')

@section('title', 'Ajouter un Jet')
@section('page-title', 'Ajouter un Jet')
@section('page-subtitle', 'Création d\'un nouveau jet privé')

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
        <button type="submit" form="jet-form" class="btn btn-sm btn-primary">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                    <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                </svg>
            </span>
            Enregistrer le Jet
        </button>
    </div>
@endsection

@section('content')
    <form id="jet-form" action="{{ route('jets.store') }}" method="POST" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row">
        @csrf
        
        <!--begin::Aside column-->
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            
            <!--begin::Thumbnail settings-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Image Principale</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <!--begin::Image input-->
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-150px h-150px"></div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Changer l'image">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="image" accept=".png,.jpg,.jpeg" />
                            <input type="hidden" name="avatar_remove" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Annuler l'image">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Supprimer l'image">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Définissez l'image principale du jet. Seuls les fichiers *.png, *.jpg et *.jpeg jusqu'à 10MB sont acceptés</div>
                    <!--end::Description-->
                    @error('image')
                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->

            <!--begin::Status-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Statut</h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Select2-->
                    <select class="form-select mb-2" name="disponible" data-control="select2" data-hide-search="true" data-placeholder="Sélectionner un statut" id="kt_ecommerce_add_product_status_select">
                        <option></option>
                        <option value="1" selected>Disponible</option>
                        <option value="0">Indisponible</option>
                    </select>
                    <!--end::Select2-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Définit la disponibilité du jet pour les réservations.</div>
                    <!--end::Description-->
                    @error('disponible')
                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Status-->

            <!--begin::Category-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Catégorie</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Select2-->
                    <select class="form-select mb-2" name="categorie" data-control="select2" data-placeholder="Sélectionner une catégorie" data-allow-clear="true">
                        <option></option>
                        <option value="Light">Light Jets (1-8 passagers)</option>
                        <option value="Mid-size">Mid-size Jets (6-12 passagers)</option>
                        <option value="Heavy">Heavy Jets (10+ passagers)</option>
                    </select>
                    <!--end::Select2-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7 mb-7">Sélectionnez la catégorie correspondant au type de jet.</div>
                    <!--end::Description-->
                    @error('categorie')
                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->

        </div>
        <!--end::Aside column-->

        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            
            <!--begin::General options-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Informations Générales</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Nom du Jet</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="nom" class="form-control mb-2" placeholder="Nom du jet" value="{{ old('nom') }}" required />
                        <!--end::Input-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Le nom doit être unique et facilement identifiable.</div>
                        <!--end::Description-->
                        @error('nom')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row">
                        <!--begin::Label-->
                        <label class="form-label">Modèle</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="modele" class="form-control mb-2" placeholder="Ex: Dassault Falcon 7X" value="{{ old('modele') }}" />
                        <!--end::Input-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Spécifiez le modèle exact du jet (constructeur et modèle).</div>
                        <!--end::Description-->
                        @error('modele')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row">
                        <!--begin::Label-->
                        <label class="form-label">Description</label>
                        <!--end::Label-->
                        <!--begin::Editor-->
                        <textarea name="description" class="form-control mb-2" rows="5" placeholder="Description détaillée du jet, équipements, caractéristiques...">{{ old('description') }}</textarea>
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Décrivez les caractéristiques principales, équipements et avantages du jet.</div>
                        <!--end::Description-->
                        @error('description')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::General options-->

            <!--begin::Technical specs-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Caractéristiques Techniques</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row">
                        <!--begin::Input group-->
                        <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Capacité</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" name="capacite" class="form-control mb-2" placeholder="Nombre de passagers" value="{{ old('capacite') }}" min="1" max="50" required />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Nombre maximum de passagers.</div>
                            <!--end::Description-->
                            @error('capacite')
                                <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Autonomie</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="input-group mb-2">
                                <input type="number" name="autonomie_km" class="form-control" placeholder="Distance max" value="{{ old('autonomie_km') }}" min="0" />
                                <span class="input-group-text">km</span>
                            </div>
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Distance maximale en kilomètres.</div>
                            <!--end::Description-->
                            @error('autonomie_km')
                                <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="row">
                        <!--begin::Input group-->
                        <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Localisation</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="localisation" class="form-control mb-2" placeholder="Ex: Paris, Nice, Londres" value="{{ old('localisation') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Base d'opération principale du jet.</div>
                            <!--end::Description-->
                            @error('localisation')
                                <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Prix par heure</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="input-group mb-2">
                                <input type="number" name="prix" class="form-control" placeholder="Prix par heure" value="{{ old('prix') }}" step="0.01" min="0" required />
                                <span class="input-group-text">€</span>
                            </div>
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Tarif de location par heure de vol.</div>
                            <!--end::Description-->
                            @error('prix')
                                <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Technical specs-->

            <!--begin::Media-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Galerie d'Images</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="fv-row mb-2">
                        <!--begin::Dropzone-->
                        <div class="dropzone" id="kt_ecommerce_add_product_media">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <!--begin::Icon-->
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <!--end::Icon-->
                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Glissez les fichiers ici ou cliquez pour télécharger.</h3>
                                    <span class="fs-7 fw-bold text-gray-400">Téléchargez jusqu'à 10 fichiers</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Dropzone-->
                        <!--begin::Input-->
                        <input type="file" name="images[]" multiple accept="image/*" style="display: none;" id="gallery-input">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Hint-->
                    <div class="text-muted fs-7">Définissez des images supplémentaires pour la galerie du jet. Seuls les fichiers *.png, *.jpg et *.jpeg jusqu'à 10MB sont acceptés</div>
                    <!--end::Hint-->
                    @error('images')
                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Media-->

        </div>
        <!--end::Main column-->
    </form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image input functionality
    var imageInputs = document.querySelectorAll('[data-kt-image-input]');
    imageInputs.forEach(function(element) {
        KTImageInput.getInstance(element) || new KTImageInput(element);
    });

    // Dropzone functionality
    var dropzone = document.querySelector("#kt_ecommerce_add_product_media");
    if (dropzone) {
        dropzone.addEventListener('click', function() {
            document.getElementById('gallery-input').click();
        });
    }

    // Gallery input change handler
    document.getElementById('gallery-input').addEventListener('change', function(e) {
        var files = e.target.files;
        var dropzone = document.querySelector("#kt_ecommerce_add_product_media .dz-message");
        
        if (files.length > 0) {
            dropzone.innerHTML = `
                <i class="bi bi-check-circle text-success fs-3x"></i>
                <div class="ms-4">
                    <h3 class="fs-5 fw-bolder text-gray-900 mb-1">${files.length} fichier(s) sélectionné(s)</h3>
                    <span class="fs-7 fw-bold text-gray-400">Cliquez pour modifier la sélection</span>
                </div>
            `;
        }
    });

    // Form validation
    $('#jet-form').on('submit', function(e) {
        var nom = $('input[name="nom"]').val().trim();
        var capacite = $('input[name="capacite"]').val();
        var prix = $('input[name="prix"]').val();

        if (!nom) {
            e.preventDefault();
            Swal.fire({
                text: "Le nom du jet est obligatoire.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return false;
        }

        if (!capacite || capacite < 1) {
            e.preventDefault();
            Swal.fire({
                text: "La capacité doit être d'au moins 1 passager.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return false;
        }

        if (!prix || prix <= 0) {
            e.preventDefault();
            Swal.fire({
                text: "Le prix par heure doit être supérieur à 0.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return false;
        }
    });

    // Status color update
    document.getElementById('kt_ecommerce_add_product_status_select').addEventListener('change', function() {
        var statusIndicator = document.getElementById('kt_ecommerce_add_product_status');
        if (this.value === '1') {
            statusIndicator.className = 'rounded-circle bg-success w-15px h-15px';
        } else {
            statusIndicator.className = 'rounded-circle bg-danger w-15px h-15px';
        }
    });
});
</script>
@endpush