<!--begin::Header-->
<div id="kt_header" class="header align-items-stretch">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                        <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                    </svg>
                </span>
            </div>
        </div>
        <!--end::Aside mobile toggle-->

        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ route('admin.luxury.dashboard') }}" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/media/logos/logo-2.svg') }}" class="h-30px" />
            </a>
        </div>
        <!--end::Mobile logo-->

        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <!--begin::Menu wrapper-->
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
                    data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                    data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <!--begin::Menu-->
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">

                        <div data-kt-menu-placement="bottom-start" class="menu-item">
                            <a href="{{ route('admin.luxury.dashboard') }}" class="menu-link py-3 {{ request()->routeIs('admin.luxury.dashboard') ? 'active' : '' }}">
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>

                        <div data-kt-menu-placement="bottom-start" class="menu-item">
                            <a href="{{ route('admin.luxury.services.index') }}" class="menu-link py-3 {{ request()->routeIs('admin.luxury.services.*') ? 'active' : '' }}">
                                <span class="menu-title">Services</span>
                                @php
                                    $inactiveServicesCount = \App\Models\LuxuryService::where('actif', false)->count();
                                @endphp
                                @if ($inactiveServicesCount > 0)
                                    <span class="badge badge-sm badge-circle badge-light-warning ms-2">{{ $inactiveServicesCount }}</span>
                                @endif
                            </a>
                        </div>

                        <div data-kt-menu-placement="bottom-start" class="menu-item">
                            <a href="{{ route('admin.luxury.packages.index') }}" class="menu-link py-3 {{ request()->routeIs('admin.luxury.packages.*') ? 'active' : '' }}">
                                <span class="menu-title">Packages</span>
                                @php
                                    $invisiblePackagesCount = \App\Models\LuxuryPackage::where('actif', true)->where('visible_public', false)->count();
                                @endphp
                                @if ($invisiblePackagesCount > 0)
                                    <span class="badge badge-sm badge-circle badge-light-info ms-2">{{ $invisiblePackagesCount }}</span>
                                @endif
                            </a>
                        </div>

                        <div data-kt-menu-placement="bottom-start" class="menu-item">
                            <a href="{{ route('admin.luxury.requests.index') }}" class="menu-link py-3 {{ request()->routeIs('admin.luxury.requests.*') ? 'active' : '' }}">
                                <span class="menu-title">Demandes</span>
                                @php
                                    $pendingRequestsCount = \App\Models\LuxuryPackageRequest::whereIn('statut', ['nouvelle', 'en_cours'])->count();
                                @endphp
                                @if ($pendingRequestsCount > 0)
                                    <span class="badge badge-sm badge-circle badge-light-success ms-2">{{ $pendingRequestsCount }}</span>
                                @endif
                            </a>
                        </div>

                        <div data-kt-menu-placement="bottom-start" class="menu-item">
                            <a href="{{ route('dashboard') }}" class="menu-link py-3">
                                <span class="menu-title">Retour Jets</span>
                            </a>
                        </div>

                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::Navbar-->

            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">

                <!--begin::Quick notifications-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="black"/>
                                <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.8 8.8 6 11 6C11.3 6 11.7 6.1 12 6.2C12.3 6.1 12.7 6 13 6C15.2 6 17 7.8 17 10V13C17 14.1 17.9 15 19 15Z" fill="black"/>
                            </svg>
                        </span>
                        @php
                            $totalLuxuryNotifications = \App\Models\LuxuryPackageRequest::whereIn('statut', ['nouvelle'])->count();
                        @endphp
                        @if ($totalLuxuryNotifications > 0)
                            <span class="position-absolute badge badge-sm badge-circle badge-light-danger top-0 start-100 translate-middle">{{ $totalLuxuryNotifications }}</span>
                        @endif
                    </div>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset('assets/media/misc/menu-header-bg.jpg') }}')">
                            <h3 class="text-white fw-bold px-9 mt-10 mb-6">Notifications Conciergerie
                                @if ($totalLuxuryNotifications > 0)
                                    <span class="fs-8 opacity-75 ps-3">{{ $totalLuxuryNotifications }} nouvelles</span>
                                @endif
                            </h3>
                        </div>
                        <div class="scroll-y mh-325px my-5 px-8">
                            @forelse(\App\Models\LuxuryPackageRequest::where('statut', 'nouvelle')->latest()->take(5)->get() as $notification)
                                <div class="d-flex flex-stack py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-4">
                                            <span class="symbol-label bg-light-success">
                                                <i class="fas fa-concierge-bell text-success"></i>
                                            </span>
                                        </div>
                                        <div class="mb-0 me-2">
                                            <a href="{{ route('admin.luxury.requests.show', $notification) }}" class="fs-6 text-gray-800 text-hover-primary fw-bolder">
                                                Nouvelle demande premium
                                            </a>
                                            <div class="text-gray-400 fs-7">{{ $notification->client_nom }}</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-light fs-8">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <span class="text-gray-400">Aucune notification</span>
                                </div>
                            @endforelse
                        </div>
                        <div class="py-3 text-center border-top">
                            <a href="{{ route('admin.luxury.requests.index') }}" class="btn btn-color-gray-600 btn-active-color-primary">
                                Voir toutes les demandes
                                <span class="svg-icon svg-icon-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Quick notifications-->

                <!--begin::User menu-->
                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        @if (Auth::user()->avatar ?? false)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" />
                        @else
                            <div class="symbol-label bg-primary text-inverse-primary fw-bolder">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    @if (Auth::user()->avatar ?? false)
                                        <img alt="{{ Auth::user()->name }}" src="{{ Storage::url(Auth::user()->avatar) }}" />
                                    @else
                                        <div class="symbol-label bg-primary text-inverse-primary fw-bolder fs-2">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">
                                        {{ Auth::user()->name }}
                                        @if (Auth::user()->is_admin)
                                            <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Concierge</span>
                                        @endif
                                    </div>
                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route('profile.edit') }}" class="menu-link px-5">Mon Profil</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.luxury.dashboard') }}" class="menu-link px-5">Dashboard Conciergerie</a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="menu-link px-5 w-100 text-start" style="background: none; border: none; color: inherit;">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                    <!--end::User account menu-->
                </div>
                <!--end::User menu-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->
