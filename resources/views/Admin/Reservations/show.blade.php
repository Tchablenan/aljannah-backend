@extends('layouts.app')

@section('title', 'Détail Réservation')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="toolbar" id="kt_toolbar">
                        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard</h1>
                            </div>
                        </div>
                    </div>

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div class="container-fluid mt-10">
                            <!--begin::Card-->
    <div class="card shadow-sm card-xl border-0 rounded-4 overflow-hidden">
        <!--begin::Card header-->
        <div class="card-header bg-primary text-white py-5 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/media/logos/logo-2.svg') }}" alt="Logo" class="me-5" style="height: 50px;">
                <h2 class="mb-0 fs-2 fw-bold text-white text-uppercase">Boarding Pass - Première Classe</h2>
            </div>

            <a href="{{ route('reservations.pdf', $reservation->id) }}" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-printer me-1"></i> Télécharger PDF
            </a>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body bg-info bg-opacity-10 py-10 px-10">
            <!--begin::Row-->
            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Nom :</div>
                    <div class="text-dark fs-6">{{ $reservation->first_name }} {{ $reservation->last_name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">N° Réservation :</div>
                    <div class="text-dark fs-6">#{{ $reservation->id }}</div>
                </div>
            </div>

            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Date départ :</div>
                    <div class="text-dark fs-6">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('d M Y') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Heure :</div>
                    <div class="text-dark fs-6">{{ \Carbon\Carbon::parse($reservation->departure_date)->format('H:i') }}</div>
                </div>
            </div>

            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">De :</div>
                    <div class="text-dark fs-6">{{ $reservation->departure_location }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Vers :</div>
                    <div class="text-dark fs-6">{{ $reservation->arrival_location }}</div>
                </div>
            </div>

            <div class="row mb-7">
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Avion :</div>
                    <div class="text-dark fs-6">{{ $reservation->plane_type }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fs-5 fw-bold text-gray-800">Passagers :</div>
                    <div class="text-dark fs-6">{{ $reservation->passengers }}</div>
                </div>
            </div>

            <!-- Barcode -->
            <div class="text-center mt-10">
                <img src="https://barcode.tec-it.com/barcode.ashx?data=RES{{ $reservation->id }}&code=Code128&dpi=96" alt="Barcode" style="height: 60px;">
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-700 fs-6">
                Merci d’avoir choisi notre service ✈️ <br>
                Bon vol avec <span class="fw-bold text-primary">Aljannah Airlines</span> !
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
