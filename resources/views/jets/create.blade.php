@extends('layouts.app')

@section('title', 'Ajouter un Jet')

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
                            <div class="card">
                                <div class="card-header bg-primary text-white fw-bold">
                                    Ajouter un nouveau Jet
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('jets.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom du Jet</label>
                                            <input type="text" class="form-control" name="nom" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="modele" class="form-label">Modèle</label>
                                            <input type="text" class="form-control" name="modele">
                                        </div>

                                        <div class="mb-3">
                                            <label for="capacite" class="form-label">Capacité (nombre de places)</label>
                                            <input type="number" class="form-control" name="capacite" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Image du Jet</label>
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>

                                        <!-- Champ pour télécharger plusieurs images supplémentaires -->
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Autres images</label>
                                            <input type="file" class="form-control" name="images[]" accept="image/*"
                                                multiple>
                                        </div>


                                        <!-- Champ prix -->
                                        <div class="mb-3">
                                            <label for="prix" class="form-label">Prix par heure (USD)</label>
                                            <input type="number" step="0.01" min="0" class="form-control"
                                                name="prix" required placeholder="Ex: 450.00">
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
    </div>
@endsection
