{{-- resources/views/admin/luxury/packages/show.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', $package->nom)

@section('page-title', 'Packages de Luxe')
@section('page-subtitle', $package->nom)

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.packages.index') }}" class="btn btn-sm btn-light me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"/>
                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
            </svg>
        </span>
        Retour
    </a>
    <a href="{{ route('admin.luxury.packages.edit', $package) }}" class="btn btn-sm btn-primary me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303Z" fill="black"/>
                <path d="M11.644 7.42501L18.08 13.861L8.893 23.048C8.39287 23.5481 7.70323 23.8302 6.98299 23.8302C6.26275 23.8302 5.57311 23.5481 5.07298 23.048L1.98999 19.965C1.48986 19.4649 1.20777 18.7752 1.20777 18.055C1.20777 17.3348 1.48986 16.6451 1.98999 16.145L11.644 7.42501Z" fill="black"/>
            </svg>
        </span>
        Modifier
    </a>
    <form action="{{ route('admin.luxury.packages.duplicate', $package) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-light-primary me-3">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="black"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="black"/>
                </svg>
            </span>
            Dupliquer
        </button>
    </form>
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
                    @if($package->image_principale_url)
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed me-7 mb-4">
                            <img src="{{ $package->image_principale_url }}" alt="{{ $package->nom }}" />
                        </div>
                    @else
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed me-7 mb-4">
                            <div class="symbol-label bg-light-primary">
                                <span class="text-primary fs-2x fw-bolder">{{ strtoupper(substr($package->nom, 0, 2)) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-800 fs-2 fw-bolder me-3">{{ $package->nom }}</span>
                                    @if($package->type === 'predefinit')
                                        <span class="badge badge-light-primary">Prédéfini</span>
                                    @else
                                        <span class="badge badge-light-info">Personnalisé</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    @if($package->destination)
                                    <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z" fill="black"/>
                                                <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z" fill="black"/>
                                            </svg>
                                        </span>
                                        {{ $package->destination }}
                                    </span>
                                    @endif
                                    <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.0077 19.2901L12.9293 17.5311C12.3487 17.1993 11.6407 17.1796 11.0426 17.4787L6.89443 19.5528C5.56462 20.2177 4 19.2507 4 17.7639V5C4 3.89543 4.89543 3 6 3H17C18.1046 3 19 3.89543 19 5V17.5536C19 19.0893 17.341 20.052 16.0077 19.2901Z" fill="black"/>
                                            </svg>
                                        </span>
                                        {{ $package->services_count }} service(s)
                                    </span>
                                    <span class="d-flex align-items-center text-gray-400 mb-2">
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z" fill="black"/>
                                                <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="black"/>
                                            </svg>
                                        </span>
                                        {{ $package->nombre_personnes }} personne(s)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-start">
                            @if($package->prix_total)
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bolder text-primary">$ {{ number_format($package->prix_total, 2, ',', ' ') }}</div>
                                </div>
                                <div class="fw-bold fs-6 text-gray-400">Prix total</div>
                            </div>
                            @elseif($package->prix_estime)
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bolder text-info">~$ {{ number_format($package->prix_estime, 2, ',', ' ') }}</div>
                                </div>
                                <div class="fw-bold fs-6 text-gray-400">Prix estimé</div>
                            </div>
                            @else
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="fs-4 fw-bolder text-gray-600">Sur devis</div>
                            </div>
                            @endif

                            @if($package->duree)
                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-4 fw-bolder">{{ $package->duree }}</div>
                                </div>
                                <div class="fw-bold fs-6 text-gray-400">Durée</div>
                            </div>
                            @endif

                            <div class="border border-gray-300 border-dashed rounded py-3 px-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-4 fw-bolder text-warning">{{ $package->popularite }}</div>
                                    <span class="svg-icon svg-icon-3 svg-icon-warning ms-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="black"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="fw-bold fs-6 text-gray-400">Popularité</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="mb-10">
                    <h5 class="mb-4">Description</h5>
                    <p class="text-gray-800 fs-6">{{ $package->description }}</p>
                </div>

                @if($package->galerie_images_urls && count($package->galerie_images_urls) > 0)
                <div class="mb-10">
                    <h5 class="mb-4">Galerie d'images</h5>
                    <div class="row g-3">
                        @foreach($package->galerie_images_urls as $imageUrl)
                        <div class="col-md-4">
                            <a href="{{ $imageUrl }}" data-fslightbox="gallery">
                                <img src="{{ $imageUrl }}" class="w-100 rounded" alt="Galerie" />
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Services inclus --}}
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Services Inclus</span>
                </div>
            </div>
            <div class="card-body pt-0">
                @if($services->isEmpty())
                    <div class="alert alert-warning">
                        Aucun service inclus dans ce package
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="min-w-200px">Service</th>
                                    <th class="min-w-100px">Catégorie</th>
                                    <th class="min-w-80px text-center">Quantité</th>
                                    <th class="min-w-80px text-center">Durée</th>
                                    <th class="min-w-100px text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                @php
                                    $serviceData = collect($package->services_inclus)->firstWhere('service_id', $service->id);
                                    $quantite = $serviceData['quantite'] ?? 1;
                                    $duration = $serviceData['duration'] ?? 1;
                                    $options = $serviceData['options'] ?? [];
                                    $prix = $service->calculatePrice($options, $duration, $quantite);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($service->image)
                                                <div class="symbol symbol-45px me-3">
                                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->nom }}" />
                                                </div>
                                            @endif
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('admin.luxury.services.show', $service) }}" class="text-dark fw-bolder text-hover-primary fs-6">
                                                    {{ $service->nom }}
                                                </a>
                                                @if(!empty($options))
                                                    <span class="text-muted fw-bold fs-7">
                                                        Options: {{ implode(', ', $options) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $service->categorie_display }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark fw-bolder">{{ $quantite }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark fw-bold">{{ $duration }}</span>
                                        <span class="text-muted">{{ $service->type_prix === 'heure' ? 'h' : 'j' }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($prix !== null)
                                            <span class="text-dark fw-bolder">$ {{ number_format($prix, 2, ',', ' ') }}</span>
                                        @else
                                            <span class="text-muted">Sur devis</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Demandes associées --}}
        @if($demandes->count() > 0)
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <span class="card-label fw-bolder fs-3 mb-1">Demandes Récentes</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bolder text-muted">
                                <th>Client</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $demande)
                            <tr>
                                <td>
                                    <span class="text-dark fw-bolder">{{ $demande->client_nom ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $demande->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-info">{{ $demande->statut ?? 'En attente' }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne latérale --}}
    <div class="col-xl-4">
        {{-- Statut --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">Statut</h3>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">État</div>
                    @if($package->actif)
                        <span class="badge badge-success">Actif</span>
                    @else
                        <span class="badge badge-danger">Inactif</span>
                    @endif
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Visibilité</div>
                    @if($package->visible_public)
                        <span class="badge badge-primary">Visible au public</span>
                    @else
                        <span class="badge badge-secondary">Masqué</span>
                    @endif
                </div>

                @if($package->type === 'personnalise')
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Client</div>
                    <span class="text-dark fw-bolder">{{ $package->client_email ?? 'N/A' }}</span>
                </div>

                @if($package->date_expiration)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Date d'expiration</div>
                    <span class="text-dark fw-bolder">{{ $package->date_expiration->format('d/m/Y') }}</span>
                    @if($package->isExpired())
                        <span class="badge badge-danger ms-2">Expiré</span>
                    @endif
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- Informations système --}}
        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">Informations</h3>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">ID</div>
                    <div class="text-gray-800 fw-bolder fs-6">#{{ $package->id }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Type</div>
                    <span class="badge badge-light">{{ $package->type_label }}</span>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Créé le</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $package->created_at->format('d/m/Y à H:i') }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Dernière modification</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $package->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="card bg-light-primary">
            <div class="card-body">
                <h5 class="text-primary mb-4">Actions rapides</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.luxury.packages.edit', $package) }}" class="btn btn-primary">
                        Modifier le package
                    </a>
                    
                    @if($package->actif)
                        <form action="{{ route('admin.luxury.packages.toggle-status', $package) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-warning w-100">Désactiver</button>
                        </form>
                    @else
                        <form action="{{ route('admin.luxury.packages.toggle-status', $package) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-success w-100">Activer</button>
                        </form>
                    @endif

                    @if($package->visible_public)
                        <form action="{{ route('admin.luxury.packages.toggle-visibility', $package) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-secondary w-100">Masquer</button>
                        </form>
                    @else
                        <form action="{{ route('admin.luxury.packages.toggle-visibility', $package) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-light-primary w-100">Rendre visible</button>
                        </form>
                    @endif

                    <form action="{{ route('admin.luxury.packages.duplicate', $package) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light-info w-100">Dupliquer</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Personnalisations --}}
        @if($package->personnalisations && count($package->personnalisations) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Personnalisations</h3>
            </div>
            <div class="card-body">
                @foreach($package->personnalisations as $key => $value)
                <div class="mb-3">
                    <div class="fw-bold text-gray-600 mb-1">{{ ucfirst($key) }}</div>
                    <div class="text-gray-800">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal de suppression --}}
<div class="modal fade" id="delete_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le package <strong>{{ $package->nom }}</strong> ?</p>
                @if($demandes->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Attention :</strong> Ce package a {{ $demandes->count() }} demande(s) associée(s).
                    </div>
                @endif
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.js"></script>
@endpush