<!--begin::Aside-->
<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ route('admin.luxury.dashboard') }}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-1.svg') }}" class="h-30px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                        transform="rotate(45 17.0365 15.1223)" fill="black" />
                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                        fill="black" />
                </svg>
            </span>
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->

    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">

                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Conciergerie</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.luxury.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.luxury.dashboard') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                    <rect x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.luxury.services.*') ? 'active' : '' }}"
                        href="{{ route('admin.luxury.services.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3" d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16.6 18 17 17.6 17 17V10H20C20.6 10 21 9.6 21 9C21 8.4 20.6 8 20 8Z" fill="black"/>
                                    <path d="M22 2H2C1.4 2 1 2.4 1 3V21C1 21.6 1.4 22 2 22H22C22.6 22 23 21.6 23 21V3C23 2.4 22.6 2 22 2Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Services de Luxe</span>
                        @php
                            $inactiveServicesCount = \App\Models\LuxuryService::where('actif', false)->count();
                        @endphp
                        @if ($inactiveServicesCount > 0)
                            <span class="menu-badge">
                                <span class="badge badge-light-warning">{{ $inactiveServicesCount }}</span>
                            </span>
                        @endif
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.luxury.packages.*') ? 'active' : '' }}"
                        href="{{ route('admin.luxury.packages.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15Z" fill="black"/>
                                    <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Packages Premium</span>
                        @php
                            $invisiblePackagesCount = \App\Models\LuxuryPackage::where('actif', true)->where('visible_public', false)->count();
                        @endphp
                        @if ($invisiblePackagesCount > 0)
                            <span class="menu-badge">
                                <span class="badge badge-light-info">{{ $invisiblePackagesCount }}</span>
                            </span>
                        @endif
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.luxury.requests.*') ? 'active' : '' }}"
                        href="{{ route('admin.luxury.requests.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="black"/>
                                    <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Demandes Clients</span>
                        @php
                            $pendingRequestsCount = \App\Models\LuxuryPackageRequest::whereIn('statut', ['nouvelle', 'en_cours'])->count();
                        @endphp
                        @if ($pendingRequestsCount > 0)
                            <span class="menu-badge">
                                <span class="badge badge-light-success">{{ $pendingRequestsCount }}</span>
                            </span>
                        @endif
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-content">
                        <div class="separator mx-1 my-4"></div>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Gestion</span>
                    </div>
                </div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="black"/>
                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Rapports</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Statistiques Services</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Revenus Conciergerie</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Satisfaction Clients</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.8C4.1 18.4 4.1 17.8 4.4 17.3L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.4 4.7 8.6 5.2 7.9L4.4 6.8C4.1 6.4 4.1 5.7 4.5 5.3L5.3 4.5C5.7 4.1 6.3 4.1 6.8 4.4L7.9 5.2C8.6 4.8 9.4 4.4 10.2 4.2L10.4 2.9C10.5 2.4 11 2 11.5 2H12.6C13.2 2 13.6 2.4 13.7 2.9L13.9 4.2C14.7 4.4 15.5 4.7 16.2 5.2L17.3 4.4C17.7 4.1 18.4 4.1 18.8 4.5L19.6 5.3C20 5.7 20 6.3 19.7 6.7L18.9 7.8C19.3 8.5 19.7 9.3 19.9 10.1L21.2 10.3C21.7 10.4 22.1 10.9 22.1 11.4V11.5Z" fill="black"/>
                                    <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Paramètres</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-content">
                        <div class="separator mx-1 my-4"></div>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Navigation</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black"/>
                                    <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Retour Dashboard Jets</span>
                    </a>
                </div>

            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->

    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <a href="#" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover"
            data-bs-dismiss-="click" title="Créer un nouveau service">
            <span class="btn-label">Nouveau Service</span>
            <span class="svg-icon btn-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                        transform="rotate(-90 11.364 20.364)" fill="black" />
                    <rect x="4.364" y="11.364" width="16" height="2" rx="1" fill="black" />
                </svg>
            </span>
        </a>
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->
