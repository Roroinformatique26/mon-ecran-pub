@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 860px;">

    {{-- ===== EN-TÊTE ===== --}}
    <div class="mb-4">
        {{-- Titre + badge sur la même ligne, wrap propre sur mobile --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold">Incident #{{ $incident->id_incident }}</h1>
                <span class="text-muted small">Émis par <strong>{{ $incident->emis_par ?? '—' }}</strong></span>
            </div>
            @php
                $badgeMap = [
                    'na'      => ['secondary', '⬛ N/A'],
                    'ouvert'  => ['danger',    '🟥 Ouvert'],
                    'en_cours'=> ['warning',   '🟧 En cours'],
                    'fermer'  => ['success',   '🟩 Fermé'],
                ];
                $b = $badgeMap[$incident->statut] ?? ['secondary', ucfirst($incident->statut)];
            @endphp
            <span class="badge bg-{{ $b[0] }} fs-6 px-3 py-2">{{ $b[1] }}</span>
        </div>
    </div>

    {{-- ===== INFORMATIONS GÉNÉRALES ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">Informations générales</div>
        <div class="card-body p-0">
            <dl class="info-grid mb-0">
                <div class="info-row">
                    <dt>Discipline</dt>
                    <dd>{{ $incident->discipline ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Système</dt>
                    <dd>{{ $incident->systeme ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Lot de travail</dt>
                    <dd>{{ $incident->lot_travail ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Zone</dt>
                    <dd>{{ $incident->zoneobj->name ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Étiquette</dt>
                    <dd>{{ $incident->etiquette ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Catégorie</dt>
                    <dd>{{ $incident->categorie_label ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Interne</dt>
                    <dd>{{ $incident->interne ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Responsabilité</dt>
                    <dd>{{ $incident->responsabilite ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- ===== SUIVI DES DATES ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">Suivi</div>
        <div class="card-body">
            {{-- 2 colonnes même sur mobile avec col-6 --}}
            <div class="row g-3">
                @foreach([
                    'Émis le'        => $incident->date_emis,
                    'Mise à jour'    => $incident->date_maj,
                    'Clôture'        => $incident->date_cloture,
                    'Clôture prévue' => $incident->cloture_prevue,
                ] as $label => $date)
                <div class="col-6 col-sm-3">
                    <div class="date-chip">
                        <span class="date-chip-label">{{ $label }}</span>
                        <span class="date-chip-value">
                            {{ $date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== DESCRIPTION ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">Description & remarques</div>
        <div class="card-body">
            <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">
                {{ $incident->description ?? '—' }}
            </p>
        </div>
    </div>

    {{-- ===== QFC ===== --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">QFC</div>
        <div class="card-body p-0">
            <dl class="info-grid mb-0">
                <div class="info-row">
                    <dt>Ouvert n°</dt>
                    <dd>{{ $incident->qfc_ouvert ?? '—' }}</dd>
                </div>
                <div class="info-row">
                    <dt>Fermé n°</dt>
                    <dd>{{ $incident->qfc_ferme ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- ===== PHOTOS ===== --}}
    @if($incident->photo_ouverte || $incident->photo_fermee)
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold">Photos</div>
        <div class="card-body">
            <div class="row g-3">
                @if($incident->photo_ouverte)
                <div class="col-12 col-sm-6">
                    <p class="text-muted small fw-semibold mb-2">Photo ouverte</p>
                    <a href="{{ asset('storage/'.$incident->photo_ouverte) }}" target="_blank">
                        <img src="{{ asset('storage/'.$incident->photo_ouverte) }}"
                             class="img-fluid rounded border w-100"
                             style="object-fit: cover; max-height: 260px;">
                    </a>
                </div>
                @endif
                @if($incident->photo_fermee)
                <div class="col-12 col-sm-6">
                    <p class="text-muted small fw-semibold mb-2">Photo fermée</p>
                    <a href="{{ asset('storage/'.$incident->photo_fermee) }}" target="_blank">
                        <img src="{{ asset('storage/'.$incident->photo_fermee) }}"
                             class="img-fluid rounded border w-100"
                             style="object-fit: cover; max-height: 260px;">
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ===== ACTIONS ===== --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">
            ← Retour
        </a>
        @if($incident->statut !== 'fermer')
            <a href="{{ route('incidents.edit', $incident->id_incident) }}" class="btn btn-primary">
                Modifier
            </a>
        @endif
    </div>

</div>
@endsection