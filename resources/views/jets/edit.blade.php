@extends('layouts.app')

@section('title', 'Modifier un Jet')

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
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Modifier le Jet</h1>
                            </div>
                        </div>
                    </div>

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div class="container-fluid mt-10">
                            <div class="card">
                                <div class="card-header bg-primary text-white fw-bold">
                                    Modifier le Jet : {{ $jet->nom }}
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('jets.update', $jet->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom du Jet</label>
                                            <input type="text" class="form-control" name="nom" value="{{ old('nom', $jet->nom) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="modele" class="form-label">Modèle</label>
                                            <input type="text" class="form-control" name="modele" value="{{ old('modele', $jet->modele) }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="capacite" class="form-label">Capacité (nombre de places)</label>
                                            <input type="number" class="form-control" name="capacite" value="{{ old('capacite', $jet->capacite) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3">{{ old('description', $jet->description) }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Image du Jet</label>
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                            @if($jet->image)
                                                <div class="mt-3">
                                                    <img src="{{ asset('storage/' . $jet->image) }}" alt="Image actuelle" class="img-fluid" style="max-height: 200px;">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label for="images" class="form-label">Images supplémentaires</label>
                                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                            @if($jet->images)
                                                <div class="mt-3">
                                                    @foreach(json_decode($jet->images) as $image)
                                                        <img src="{{ asset('storage/' . $image) }}" alt="Image actuelle" class="img-fluid" style="max-height: 200px;">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label for="prix" class="form-label">Prix par heure (USD)</label>
                                            <input type="number" class="form-control" name="prix" value="{{ old('prix', $jet->prix) }}" required>
                                        </div>

                                        <button type="submit" class="btn btn-success">Enregistrer</button>
                                        <a href="{{ route('jets.index') }}" class="btn btn-secondary">Annuler</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
