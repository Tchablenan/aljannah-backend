@extends('layouts.app')

@section('title', 'Liste des Jets')

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

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="fw-bold text-dark">Jets enregistrés</h2>
                                <a href="{{ route('jets.create') }}" class="btn btn-primary">+ Ajouter un Jet</a>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="card card-flush shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Tableau des Jets</h3>
                                </div>
                                <div class="card-body py-5">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover align-middle">
                                            <thead class="table-light fw-bold">
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Nom</th>
                                                    <th>Modèle</th>
                                                    <th>Capacité</th>
                                                    <th>Prix</th> {{-- ✅ Ajouté ici --}}
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($jets as $jet)
                                                    <tr>
                                                        <td style="width: 160px;">
                                                            <img src="{{ $jet->image ? asset('storage/' . $jet->image) : asset('assets/media/stock/airplane-default.jpg') }}"
                                                                alt="Jet" class="rounded shadow-sm"
                                                                style="width: 100%; height: auto; object-fit: cover;">
                                                        </td>
                                                        <td>{{ $jet->nom }}</td>
                                                        <td>{{ $jet->modele ?? 'N/A' }}</td>
                                                        <td>{{ $jet->capacite }} personnes</td>
                                                        <td>{{ number_format($jet->prix, 2) }} $ / heure</td>
                                                        {{-- ✅ Prix formaté --}}
                                                        <td class="text-end">
                                                            <a href="{{ route('jets.show', $jet) }}"
                                                                class="btn btn-sm btn-light-info me-2">🛈</a>
                                                            <a href="{{ route('jets.edit', $jet) }}"
                                                                class="btn btn-sm btn-light-warning me-2">✏️</a>
                                                            <form action="{{ route('jets.destroy', $jet) }}" method="POST"
                                                                class="d-inline"
                                                                onsubmit="return confirm('Confirmer la suppression ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-light-danger">🗑️</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">Aucun jet
                                                            enregistré pour le moment.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
