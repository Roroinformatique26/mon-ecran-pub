@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 600px;">

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des zones</h1>
        <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">
            ← Retour
        </a>
    </div>

    {{-- Formulaire ajout --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">Ajouter une zone</div>
        <div class="card-body">
            <form action="{{ route('zones.store') }}" method="POST">
                @csrf
                <div class="d-flex gap-2">
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Nom de la zone"
                           value="{{ old('name') }}">
                    <button type="submit" class="btn btn-primary" style="white-space:nowrap">
                        + Ajouter
                    </button>
                </div>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </form>
        </div>
    </div>

    {{-- Liste --}}
    <div class="card shadow-sm">
        <div class="card-header">Zones existantes</div>
        <ul class="list-group list-group-flush">
            @forelse($zones as $zone)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $zone->name }}</span>
                <form action="{{ route('zones.destroy', $zone->id) }}"
                      method="POST"
                      onsubmit="return confirm('Supprimer la zone « {{ $zone->name }} » ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Supprimer
                    </button>
                </form>
            </li>
            @empty
            <li class="list-group-item text-muted text-center py-4">
                Aucune zone définie.
            </li>
            @endforelse
        </ul>
    </div>

</div>
@endsection