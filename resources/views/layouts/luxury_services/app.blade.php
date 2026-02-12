<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <base href="" />
    <title>@yield('title', 'Aljannah') - Administration</title>
    <meta charset="utf-8" />
    <meta name="description" content="Plateforme de gestion des jets privés Aljannah" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Canonical & Favicon -->
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    
    <!-- Page Vendor Styles -->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Global Styles Bundle -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            
            <!-- Sidebar -->
            @include('layouts.luxury_services.sidebar')
            
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                
                <!-- Header -->
                @include('layouts.luxury_services.header')
                
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    
                    <!--begin::Toolbar-->
                    @hasSection('toolbar')
                        <div class="toolbar" id="kt_toolbar">
                            <div class="container-fluid d-flex flex-stack">
                                @yield('toolbar')
                            </div>
                        </div>
                    @else
                        <div class="toolbar" id="kt_toolbar">
                            <div class="container-fluid d-flex flex-stack">
                                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                    <!--begin::Title-->
                                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                                        @yield('page-title', 'Dashboard')
                                        @hasSection('page-subtitle')
                                            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                                            <small class="text-muted fs-7 fw-bold my-1 ms-1">@yield('page-subtitle')</small>
                                        @endif
                                    </h1>
                                    <!--end::Title-->
                                </div>
                                
                                <!--begin::Actions-->
                                <div class="d-flex align-items-center py-1">
                                    @yield('toolbar-actions')
                                </div>
                                <!--end::Actions-->
                            </div>
                        </div>
                    @endif
                    <!--end::Toolbar-->
                    
                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        <div id="kt_content_container" class="container-fluid">
                            
                            <!-- Flash Messages -->
                            @if(session('success'))
                                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                    <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black"/>
                                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8255 13.6747 11.5 13.6747 11.9073 13.2773L15.4857 9.58639C15.771 9.29702 15.771 8.83242 15.4857 8.54305C15.1946 8.24775 14.7181 8.24775 14.427 8.54305L10.5606 11.3042Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-success">Succès!</h4>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                    <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-45 11 14)" fill="black"/>
                                            <rect x="8" y="12" width="7" height="2" rx="1" transform="rotate(45 8 12)" fill="black"/>
                                        </svg>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-danger">Erreur!</h4>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                    <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-45 11 14)" fill="black"/>
                                            <rect x="8" y="12" width="7" height="2" rx="1" transform="rotate(45 8 12)" fill="black"/>
                                        </svg>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-danger">Erreurs de validation</h4>
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Main Content -->
                            @yield('content')
                            
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
                
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted fw-bold me-1">{{ date('Y') }}&copy;</span>
                            <a href="#" target="_blank" class="text-gray-800 text-hover-primary">Aljannah Jet Charter</a>
                        </div>
                        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
                            <li class="menu-item">
                                <span class="menu-link px-2">Version 1.0.0</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!-- Global JS Bundle -->
    <script>var hostUrl = "{{ asset('assets/') }}/";</script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    
    <!-- Page Vendors JS -->
    <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    
    <!-- Page Custom JS -->
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
    
    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>