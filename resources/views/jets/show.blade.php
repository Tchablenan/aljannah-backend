@extends('layouts.app')

@section('title', 'Détails du Jet')

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
        <h2 class="fw-bold text-dark">Jet : {{ $jet->nom }}</h2>
        <a href="{{ route('jets.index') }}" class="btn btn-light">⬅️ Retour</a>
    </div>

    <div class="card card-flush shadow-sm">
        <div class="card-header">
            <h3 class="card-title text-primary">Informations générales</h3>
        </div>

        <div class="card-body d-flex flex-wrap gap-5">
            <div class="w-100 w-md-40">
                <img src="{{ $jet->image ? asset('storage/' . $jet->image) : asset('assets/media/stock/airplane-default.jpg') }}"
                     alt="Image du Jet"
                     class="img-fluid rounded shadow-sm" style="object-fit: cover;">
            </div>

            <div class="w-100 w-md-55">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted">Nom</th>
                        <td class="fw-bold">{{ $jet->nom }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Modèle</th>
                        <td>{{ $jet->modele ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Capacité</th>
                        <td>{{ $jet->capacite }} personnes</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Description</th>
                        <td>{{ $jet->description ?? '—' }}</td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="{{ route('jets.edit', $jet) }}" class="btn btn-warning me-2">✏️ Modifier</a>
                    <form action="{{ route('jets.destroy', $jet) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>
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
