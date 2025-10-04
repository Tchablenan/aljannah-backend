@extends('layouts.app')

@section('title', 'Gestion des Réservations')
@section('page-title', 'Réservations')
@section('page-subtitle', 'Gestion des demandes de réservation')

@section('toolbar-actions')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <div class="d-flex align-items-center position-relative my-1">
            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                </svg>
            </span>
            <input type="text" id="searchReservations" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher..." value="{{ request('search') }}"/>
        </div>
        <div class="me-4">
            <select class="form-select form-select-solid" id="filterStatus" data-control="select2" data-placeholder="Tous statuts">
                <option value="">Tous statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
            </select>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"/>
                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black"/>
                </svg>
            </span>
            Nouvelle Réservation
        </a>
    </div>
@endsection

@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                        </svg>
                    </span>
                    <input type="text" data-kt-reservations-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Rechercher une réservation" value="{{ request('search') }}"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-reservations-table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="black"/>
                            </svg>
                        </span>
                        Filtrer
                    </button>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bolder">Options de filtrage</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Content-->
                        <div class="px-7 py-5" data-kt-reservations-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold">Statut:</label>
                                <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Sélectionner un statut" data-allow-clear="true" data-kt-reservations-table-filter="status">
                                    <option></option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-bold">Période:</label>
                                <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="Sélectionner une période" data-allow-clear="true" data-kt-reservations-table-filter="period">
                                    <option></option>
                                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                                    <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-reservations-table-filter="reset">Réinitialiser</button>
                                <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-reservations-table-filter="filter">Appliquer</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Menu 1-->
                    <!--end::Filter-->
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_reservations_table">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_reservations_table .form-check-input" value="1"/>
                            </div>
                        </th>
                        <th class="min-w-125px">Client</th>
                        <th class="min-w-125px">Contact</th>
                        <th class="min-w-125px">Route</th>
                        <th class="min-w-125px">Jet</th>
                        <th class="min-w-125px">Dates</th>
                        <th class="min-w-100px">Passagers</th>
                        <th class="min-w-100px">Statut</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                
                <!--begin::Table body-->
                <tbody class="text-gray-600 fw-bold">
                    @forelse($reservations as $reservation)
                        <tr>
                            <!--begin::Checkbox-->
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $reservation->id }}"/>
                                </div>
                            </td>
                            <!--end::Checkbox-->
                            
                            <!--begin::Client-->
                            <td class="d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <span class="text-primary fw-bolder fs-4">{{ strtoupper(substr($reservation->first_name, 0, 1) . substr($reservation->last_name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Client details-->
                                <div class="d-flex flex-column">
                                    <a href="{{ route('reservations.show', $reservation) }}" class="text-gray-800 text-hover-primary mb-1 fw-bolder">{{ $reservation->full_name }}</a>
                                    <span class="text-muted">Réf: REF-{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <!--end::Client details-->
                            </td>
                            <!--end::Client-->
                            
                            <!--begin::Contact-->
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="mailto:{{ $reservation->email }}" class="text-gray-800 text-hover-primary mb-1">{{ $reservation->email }}</a>
                                    @if($reservation->phone)
                                        <span class="text-muted">{{ $reservation->phone }}</span>
                                    @else
                                        <span class="text-muted">{{ $reservation->created_at->format('d/m/Y à H:i') }}</span>
                                    @endif
                                </div>
                            </td>
                            <!--end::Contact-->
                            
                            <!--begin::Route-->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bolder mb-1">{{ $reservation->departure_location }}</span>
                                    <span class="text-muted">→ {{ $reservation->arrival_location }}</span>
                                </div>
                            </td>
                            <!--end::Route-->
                            
                            <!--begin::Jet-->
                            <td>
                                @if($reservation->jet)
                                    <div class="d-flex align-items-center">
                                        @if($reservation->jet->image)
                                            <div class="symbol symbol-30px me-3">
                                                <img src="{{ asset('storage/' . $reservation->jet->image) }}" alt="{{ $reservation->jet->nom }}" class="w-100"/>
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bolder">{{ $reservation->jet->nom }}</span>
                                            @if($reservation->jet->modele)
                                                <span class="text-muted fs-7">{{ $reservation->jet->modele }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Jet non spécifié</span>
                                @endif
                            </td>
                            <!--end::Jet-->
                            
                            <!--begin::Dates-->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bolder mb-1">{{ $reservation->departure_date->format('d/m/Y') }}</span>
                                    @if($reservation->arrival_date->format('Y-m-d') !== $reservation->departure_date->format('Y-m-d'))
                                        <span class="text-muted">→ {{ $reservation->arrival_date->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-muted">Aller simple</span>
                                    @endif
                                </div>
                            </td>
                            <!--end::Dates-->
                            
                            <!--begin::Passengers-->
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="svg-icon svg-icon-2 svg-icon-primary me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M16 15.6315C16 16.7754 15.3284 17.8236 14.2733 18.3315L13 19.0657V20.5C13 21.0523 12.5523 21.5 12 21.5C11.4477 21.5 11 21.0523 11 20.5V19.0657L9.72671 18.3315C8.67159 17.8236 8 16.7754 8 15.6315V13.5C8 12.3954 8.89543 11.5 10 11.5H14C15.1046 11.5 16 12.3954 16 13.5V15.6315Z" fill="black"/>
                                            <path opacity="0.3" d="M12 11.5C13.3807 11.5 14.5 10.3807 14.5 9C14.5 7.61929 13.3807 6.5 12 6.5C10.6193 6.5 9.5 7.61929 9.5 9C9.5 10.3807 10.6193 11.5 12 11.5Z" fill="black"/>
                                            <path opacity="0.3" d="M7.5 6C8.32843 6 9 5.32843 9 4.5C9 3.67157 8.32843 3 7.5 3C6.67157 3 6 3.67157 6 4.5C6 5.32843 6.67157 6 7.5 6ZM16.5 6C17.3284 6 18 5.32843 18 4.5C18 3.67157 17.3284 3 16.5 3C15.6716 3 15 3.67157 15 4.5C15 5.32843 15.6716 6 16.5 6Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <span class="text-gray-800 fw-bolder">{{ $reservation->passengers }}</span>
                                </div>
                            </td>
                            <!--end::Passengers-->
                            
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
                            
                            <!--begin::Action-->
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black"/>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('reservations.show', $reservation) }}" class="menu-link px-3">
                                            <span class="svg-icon svg-icon-3 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black"/>
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black"/>
                                                </svg>
                                            </span>
                                            Voir les détails
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('reservations.edit', $reservation) }}" class="menu-link px-3">
                                            <span class="svg-icon svg-icon-3 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9961 6.37355 21.9961 6.91345C21.9961 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"/>
                                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3Z" fill="black"/>
                                                    <path d="M20.4615 8.65304L18.3025 10.811L12.5465 5.055L14.7055 2.897C15.0873 2.51528 15.6051 2.30093 16.145 2.30093C16.6849 2.30093 17.2027 2.51528 17.5845 2.897L20.4615 5.774C20.8432 6.15581 21.0576 6.67355 21.0576 7.21345C21.0576 7.75335 20.8432 8.27122 20.4615 8.65304Z" fill="black"/>
                                                </svg>
                                            </span>
                                            Modifier
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    
                                    @if($reservation->status === 'pending')
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" onclick="updateStatus({{ $reservation->id }}, 'confirmed')">
                                                <span class="svg-icon svg-icon-3 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                                                    </svg>
                                                </span>
                                                Confirmer
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    @endif
                                    
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('reservations.pdf', $reservation) }}" class="menu-link px-3" target="_blank">
                                            <span class="svg-icon svg-icon-3 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7.5 13.4 7.5 14C7.5 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 18.5 12 18.5C12.6 18.5 12.5 18.6 12.5 18V14.5H16C16.6 14.5 16.5 14.6 16.5 14C16.5 13.4 16.6 13.5 16 13.5Z" fill="black"/>
                                                    <rect x="11" y="19" width="10" height="2" rx="1" fill="black"/>
                                                    <rect x="7" y="13" width="5" height="2" rx="1" fill="black"/>
                                                </svg>
                                            </span>
                                            Télécharger PDF
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3 text-danger" onclick="confirmDelete({{ $reservation->id }})">
                                            <span class="svg-icon svg-icon-3 me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"/>
                                                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"/>
                                                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"/>
                                                </svg>
                                            </span>
                                            Supprimer
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                            <!--end::Action-->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="svg-icon svg-icon-muted svg-icon-2hx mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black"/>
                                            <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <span class="text-muted fs-4">Aucune réservation trouvée</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-muted fs-6">Commencez par ajouter votre première réservation</span>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('reservations.create') }}" class="btn btn-primary">Nouvelle Réservation</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!--begin::Pagination-->
    @if($reservations->hasPages())
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">
                Affichage {{ $reservations->firstItem() }} à {{ $reservations->lastItem() }} 
                sur {{ $reservations->total() }} résultats
            </div>
            <ul class="pagination">
                {{ $reservations->appends(request()->query())->links() }}
            </ul>
        </div>
    @endif
    <!--end::Pagination-->

    <!--begin::Hidden forms for actions-->
    <form id="status-update-form" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="status-input">
    </form>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!--end::Hidden forms-->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Recherche en temps réel
    $('#searchReservations, [data-kt-reservations-table-filter="search"]').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        if (value.length > 2 || value.length === 0) {
            // Redirection avec paramètre de recherche
            let url = new URL(window.location);
            if (value) {
                url.searchParams.set('search', value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }
    });

    // Synchroniser les deux champs de recherche
    $('#searchReservations').val($('[data-kt-reservations-table-filter="search"]').val());
    $('[data-kt-reservations-table-filter="search"]').val($('#searchReservations').val());

    // Filtres rapides dans la toolbar
    $('#filterStatus').on('change', function() {
        const status = $(this).val();
        let url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    });

    // Checkbox "tout sélectionner"
    $('[data-kt-check="true"]').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('#kt_reservations_table .form-check-input').prop('checked', isChecked);
    });

    // Filtres avancés
    $('[data-kt-reservations-table-filter="filter"]').on('click', function() {
        const status = $('[data-kt-reservations-table-filter="status"]').val();
        const period = $('[data-kt-reservations-table-filter="period"]').val();
        
        let url = new URL(window.location);
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        if (period) url.searchParams.set('period', period);
        else url.searchParams.delete('period');
        
        window.location.href = url.toString();
    });

    // Reset filtres
    $('[data-kt-reservations-table-filter="reset"]').on('click', function() {
        $('[data-kt-reservations-table-filter="status"]').val('').trigger('change');
        $('[data-kt-reservations-table-filter="period"]').val('').trigger('change');
        
        // Supprimer tous les paramètres de filtrage de l'URL
        let url = new URL(window.location);
        url.searchParams.delete('status');
        url.searchParams.delete('period');
        url.searchParams.delete('search');
        window.location.href = url.toString();
    });
});

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

// Fonction pour confirmer la suppression
function confirmDelete(reservationId) {
    Swal.fire({
        text: "Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.",
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
            const form = document.getElementById('delete-form');
            form.action = `/reservations/${reservationId}`;
            form.submit();
        }
    });
}
</script>
@endpush
                 