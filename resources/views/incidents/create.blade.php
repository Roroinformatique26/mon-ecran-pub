@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nouvel incident</h1>
        <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">
            ← Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('partials.form')

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Créer l'incident</button>
                    <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection