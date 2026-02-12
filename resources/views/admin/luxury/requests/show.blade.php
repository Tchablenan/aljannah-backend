{{-- resources/views/admin/luxury/requests/show.blade.php --}}

@extends('layouts.luxury_services.app')

@section('title', 'Demande ' . $packageRequest->reference)

@section('page-title', 'Demandes Clients')
@section('page-subtitle', 'Détails de la demande ' . $packageRequest->reference)

@section('toolbar-actions')
<div class="d-flex align-items-center py-1">
    <a href="{{ route('admin.luxury.requests.index') }}" class="btn btn-sm btn-light me-3">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"/>
                <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
            </svg>
        </span>
        Retour
    </a>
    
    @if($packageRequest->canBeModified())
    <button type="button" class="btn btn-sm btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#add_note_modal">
        Ajouter une note
    </button>
    @endif

    @if($packageRequest->canBeCancelled())
    <button type="button" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#delete_modal">
        Supprimer
    </button>
    @endif
</div>
@endsection

@section('content')

<div class="row g-5 g-xl-8">
    {{-- Colonne principale --}}
    <div class="col-xl-8">
        {{-- En-tête de la demande --}}
        <div class="card mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-5">
                    <div>
                        <h1 class="fw-bolder text-dark mb-2">{{ $packageRequest->titre_demande }}</h1>
                        <div class="text-muted fw-bold fs-5">Référence: <span class="text-dark">{{ $packageRequest->reference }}</span></div>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-lg badge-light-{{ $packageRequest->statut_color }} mb-2">
                            {{ $packageRequest->statut_display }}
                        </span>
                        <br>
                        <span class="badge badge-lg badge-{{ $packageRequest->priorite_color }}">
                            {{ $packageRequest->priorite_display }}
                        </span>
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="mb-7">
                    <h5 class="mb-3">Description de la demande</h5>
                    <p class="text-gray-800 fs-6">{{ $packageRequest->description_demande }}</p>
                </div>

                {{-- Informations de voyage --}}
                <div class="row g-5 mb-7">
                    <div class="col-md-6">
                        <div class="border border-gray-300 border-dashed rounded p-4">
                            <div class="fw-bold text-gray-600 mb-2">Destination principale</div>
                            <div class="text-gray-800 fw-bolder fs-5">{{ $packageRequest->destination_principale }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border border-gray-300 border-dashed rounded p-4">
                            <div class="fw-bold text-gray-600 mb-2">Nombre de personnes</div>
                            <div class="text-gray-800 fw-bolder fs-5">{{ $packageRequest->nombre_personnes }} personne(s)</div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 mb-7">
                    <div class="col-md-6">
                        <div class="border border-gray-300 border-dashed rounded p-4">
                            <div class="fw-bold text-gray-600 mb-2">Date de départ souhaitée</div>
                            <div class="text-gray-800 fw-bolder fs-5">{{ $packageRequest->date_debut_souhaitee->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border border-gray-300 border-dashed rounded p-4">
                            <div class="fw-bold text-gray-600 mb-2">Date de retour</div>
                            <div class="text-gray-800 fw-bolder fs-5">
                                {{ $packageRequest->date_fin_souhaitee ? $packageRequest->date_fin_souhaitee->format('d/m/Y') : 'Non précisée' }}
                            </div>
                            @if($packageRequest->duree_sejour)
                                <span class="badge badge-light-primary mt-2">{{ $packageRequest->duree_sejour_display }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Destinations multiples --}}
                @if($packageRequest->destinations_multiples && count($packageRequest->destinations_multiples) > 0)
                <div class="mb-7">
                    <h5 class="mb-3">Destinations supplémentaires</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($packageRequest->destinations_multiples as $destination)
                            <span class="badge badge-light-info">{{ $destination }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Services souhaités --}}
        @if($servicesDetails->isNotEmpty())
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Services Souhaités</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bolder text-muted">
                                <th>Service</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-center">Durée</th>
                                <th class="text-end">Prix unitaire</th>
                                <th class="text-end">Prix total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicesDetails as $detail)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder">{{ $detail['service']->nom }}</span>
                                        <span class="text-muted fs-7">{{ $detail['service']->categorie_display }}</span>
                                        @if(!empty($detail['options']))
                                            <span class="badge badge-light-primary mt-1">Options: {{ implode(', ', $detail['options']) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bolder">{{ $detail['quantite'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bolder">{{ $detail['duration'] }}</span>
                                </td>
                                <td class="text-end">
                                    @if($detail['sur_devis'])
                                        <span class="text-muted">Sur devis</span>
                                    @else
                                        <span class="fw-bolder">$ {{ number_format($detail['prix_unitaire'], 2, ',', ' ') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($detail['sur_devis'])
                                        <span class="text-muted">Sur devis</span>
                                    @else
                                        <span class="fw-bolder text-primary">$ {{ number_format($detail['prix_total'], 2, ',', ' ') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top-2">
                                <td colspan="4" class="text-end fw-bolder fs-5">Prix estimé total:</td>
                                <td class="text-end fw-bolder fs-4 text-success">
                                    {{ number_format($packageRequest->calculateEstimatedPrice(), 2, ',', ' ') }} $
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Personnalisations --}}
        @if($packageRequest->personnalisations_demandees && count($packageRequest->personnalisations_demandees) > 0)
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Personnalisations Demandées</h3>
            </div>
            <div class="card-body pt-0">
                @foreach($packageRequest->personnalisations_demandees as $key => $value)
                <div class="mb-4">
                    <div class="fw-bold text-gray-600 mb-1">{{ ucfirst($key) }}</div>
                    <div class="text-gray-800">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notes internes --}}
        @if($packageRequest->notes_internes)
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Notes Internes</h3>
            </div>
            <div class="card-body pt-0">
                <div class="bg-light-warning p-5 rounded">
                    <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $packageRequest->notes_internes }}</pre>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne latérale --}}
    <div class="col-xl-4">
        {{-- Informations client --}}
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Informations Client</h3>
            </div>
            <div class="card-body pt-0">
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Nom complet</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $packageRequest->client_nom_complet }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Email</div>
                    <div class="text-gray-800 fw-bolder fs-6">
                        <a href="mailto:{{ $packageRequest->client_email }}">{{ $packageRequest->client_email }}</a>
                    </div>
                </div>

                @if($packageRequest->client_telephone)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Téléphone</div>
                    <div class="text-gray-800 fw-bolder fs-6">
                        <a href="tel:{{ $packageRequest->client_telephone }}">{{ $packageRequest->client_telephone }}</a>
                    </div>
                </div>
                @endif

                @if($packageRequest->preferences_client && count($packageRequest->preferences_client) > 0)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Préférences</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($packageRequest->preferences_client as $pref)
                            <span class="badge badge-light">{{ $pref }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Tarification --}}
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Tarification</h3>
            </div>
            <div class="card-body pt-0">
                @if($packageRequest->budget_estime)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Budget du client</div>
                    <div class="text-gray-800 fw-bolder fs-5">$ {{ number_format($packageRequest->budget_estime, 2, ',', ' ') }}</div>
                </div>
                @endif

                @if($packageRequest->prix_propose)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Prix proposé</div>
                    <div class="text-primary fw-bolder fs-4">$ {{ number_format($packageRequest->prix_propose, 2, ',', ' ') }}</div>
                </div>
                @endif

                @if($packageRequest->prix_final)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Prix final</div>
                    <div class="text-success fw-bolder fs-3">$ {{ number_format($packageRequest->prix_final, 2, ',', ' ') }}</div>
                </div>
                @endif

                @if($packageRequest->date_expiration_devis)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Expiration du devis</div>
                    <div class="text-gray-800 fw-bolder">{{ $packageRequest->date_expiration_devis->format('d/m/Y') }}</div>
                    @if($packageRequest->isDevisExpired())
                        <span class="badge badge-danger mt-2">Expiré</span>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Gestion --}}
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Gestion</h3>
            </div>
            <div class="card-body pt-0">
                @if($packageRequest->concierge_assigne)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Concierge assigné</div>
                    <div class="text-gray-800 fw-bolder fs-6">{{ $packageRequest->concierge_assigne }}</div>
                </div>
                @else
                <div class="mb-5">
                    <div class="alert alert-warning">
                        Aucun concierge assigné
                    </div>
                </div>
                @endif

                @if($packageRequest->package)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Package associé</div>
                    <a href="{{ route('admin.luxury.packages.show', $packageRequest->package) }}" class="btn btn-sm btn-light-primary">
                        {{ $packageRequest->package->nom }}
                    </a>
                </div>
                @endif

                @if($packageRequest->date_confirmation)
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Date de confirmation</div>
                    <div class="text-gray-800 fw-bolder">{{ $packageRequest->date_confirmation->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Informations système --}}
        <div class="card mb-5">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title">Informations</h3>
            </div>
            <div class="card-body pt-0">
                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Créée le</div>
                    <div class="text-gray-800 fw-bolder">{{ $packageRequest->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="mb-5">
                    <div class="fw-bold text-gray-600 mb-2">Dernière modification</div>
                    <div class="text-gray-800 fw-bolder">{{ $packageRequest->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card bg-light-primary">
            <div class="card-body">
                <h5 class="text-primary mb-4">Actions rapides</h5>
                <div class="d-grid gap-2">
                    @if(!$packageRequest->concierge_assigne)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assign_modal">
                        Assigner un concierge
                    </button>
                    @endif

                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#status_modal">
                        Changer le statut
                    </button>

                    @if($packageRequest->canBeModified())
                    <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#add_note_modal">
                        Ajouter une note
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@include('admin.luxury.requests.partials.modals', ['packageRequest' => $packageRequest])

@endsection