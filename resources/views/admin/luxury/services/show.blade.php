{{-- resources/views/admin/luxury/services/show.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', $service->nom)

@section('page-title', 'Services de Luxe')
@section('page-subtitle', $service->nom)

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
    <a href="{{ route('admin.luxury.services.edit', $service) }}" class="btn btn-sm btn-primary me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303Z" fill="black"/>
                <path d="M11.644 7.42501L18.08 13.861L8.893 23.048C8.39287 23.5481 7.70323 23.8302 6.98299 23.8302C6.26275 23.8302 5.57311 23.5481 5.07298 23.048L1.98999 19.965C1.48986 19.4649 1.20777 18.7752 1.20777 18.055C1.20777 17.3348 1.48986 16.6451 1.98999 16.145L11.644 7.42501Z" fill="black"/>
            </svg>
        </span>
        Modifier
    </a>
    <button type="button" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#delete_modal">
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

<div class="row g-5 g-xl-8">
    {{-- Colonne principale --}}
    <div class="col-xl-8">
        {{-- Informations principales --}}
        <div class="card mb-5 mb-xl-8">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center mb-7">
                    @if($service->image_url)
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed me-7 mb-4">
                            <img src="{{ $service->image_url }}" alt="{{ $service->nom }}" />
                        </div>
                    @else
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed me-7 mb-4">
                            <div class="symbol-label bg-light-primary">
                                <span class="text-primary fs-2x fw-bolder">{{ strtoupper(substr($service->nom, 0, 2)) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-800 fs-2 fw-bolder me-3">{{ $service->nom }}</span>
                                    @if($service->actif)
                                        <span class="badge badge-light-success">Actif</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactif</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                                                <path d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                                            </svg>
                                        </span>
                                        {{ $service->categorie_display }}
                                    </span>
                                    <span class="d-flex align-items-center text-gray-400 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                                                <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                                            </svg>
                                        </span>
                                        {{ $service->type_prix_display }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($service->prix_base)
                        <div class="d-flex flex-wrap justify-content-start">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bolder">$ {{ number_format($service->prix_base, 2, ',', ' ') }}</div>
                                </div>
                                <div class="fw-bold fs-6 text-gray-400">Prix de base</div>
                            </div>
                        </div>
                        @else
                        <div class="d-flex flex-wrap justify-content-start">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="fs-4 fw-bolder text-gray-600">Sur devis</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="mb-10">
                    <h5 class="mb-4">Description</h5>
                    <p class="text-gray-800 fs-6">{{ $service->description }}</p>
                </div>
            </div>
        </div>

        {{-- Options disponibles --}}
        @if($service->options_disponibles && count($service->options_disponibles) > 0)
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Options Disponibles</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bolder text-muted">
                                <th class="min-w-200px">Option</th>
                                <th class="min-w-100px">Clé</th>
                                <th class="min-w-100px text-end">Prix supplément</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->options_disponibles as $option)
                            <tr>
                                <td>
                                    <span class="text-dark fw-bolder fs-6">{{ $option['nom'] ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary">{{ $option['key'] ?? 'N/A' }}</span>
                                </td>
                                <td class="text-end">
                                    @if(isset($option['prix_supplement']) && $option['prix_supplement'] > 0)
                                        <span class="text-dark fw-bolder">+ $ {{ number_format($option['prix_supplement'], 2, ',', ' ') }}</span>
                                    @else
                                        <span class="text-muted">Inclus</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Services associés --}}
        @if($servicesAssocies && $servicesAssocies->count() > 0)
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Services Complémentaires</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row g-5">
                    @foreach($servicesAssocies as $serviceAssocie)
                    <div class="col-md-6">
                        <div class="card card-bordered">
                            <div class="card-body p-5">
                                <div class="d-flex align-items-center mb-3">
                                    @if($serviceAssocie->image_url)
                                        <div class="symbol symbol-50px me-3">
                                            <img src="{{ $serviceAssocie->image_url }}" alt="{{ $serviceAssocie->nom }}" />
                                        </div>
                                    @else
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <span class="text-primary fw-bolder">{{ strtoupper(substr($serviceAssocie->nom, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <a href="{{ route('admin.luxury.services.show', $serviceAssocie) }}" class="text-dark fw-bolder text-hover-primary fs-6">
                                            {{ $serviceAssocie->nom }}
                                        </a>
                                        <span class="text-muted fw-bold d-block">{{ $serviceAssocie->categorie_display }}</span>
                                    </div>
                                </div>
                                <p class="text-gray-600 fw-bold fs-7 mb-0">{{ Str::limit($serviceAssocie->description, 80) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne latérale --}}
    <div class="col-xl-4">
        {{-- Informations fournisseur --}}
        @if($service->fournisseur || $service->contact_fournisseur)
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Fournisseur</span>
                </div>
            </div>
            <div class="card-body pt-0">
                @if($service->fournisseur)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Nom</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $service->fournisseur }}</div>
                </div>
                @endif

                @if($service->contact_fournisseur)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Contact</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $service->contact_fournisseur }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Informations système --}}
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Informations</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">ID</div>
                    <div class="text-gray-800 fw-bolder fs-6">#{{ $service->id }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Statut</div>
                    @if($service->actif)
                        <span class="badge badge-success">Actif</span>
                    @else
                        <span class="badge badge-danger">Inactif</span>
                    @endif
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Créé le</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $service->created_at->format('d/m/Y à H:i') }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Dernière modification</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $service->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="card bg-light-primary">
            <div class="card-body">
                <h5 class="text-primary mb-4">Actions rapides</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.luxury.services.edit', $service) }}" class="btn btn-primary">
                        Modifier le service
                    </a>
                    @if($service->actif)
                        <form action="{{ route('admin.luxury.services.toggle-status', $service) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-warning w-100">Désactiver</button>
                        </form>
                    @else
                        <form action="{{ route('admin.luxury.services.toggle-status', $service) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-success w-100">Activer</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de suppression --}}
<div class="modal fade" id="delete_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le service <strong>{{ $service->nom }}</strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.luxury.services.destroy', $service) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
