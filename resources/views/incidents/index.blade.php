@extends('layouts.app')

@section('content')

{{-- SIDEBAR --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<div class="sidebar" id="sidebar">
    <button class="sidebar-close" onclick="closeSidebar()">✕</button>
    <div class="sidebar-logo">
        <img src="{{ asset('images/Planex.jpg') }}" alt="PlanEx">
    </div>
    <div class="sidebar-divider"></div>
    <nav class="sidebar-nav">
        <a href="{{ route('incidents.index') }}" class="sidebar-link active">
            <span class="sidebar-icon" style="font-size:16px">📋</span> Incidents
        </a>
        <a href="{{ route('incidents.create') }}" class="sidebar-link">
            <span class="sidebar-icon" style="font-size:16px">➕</span> Nouvel incident
        </a>
        <a href="{{ route('zones.index') }}" class="sidebar-link">
            <span class="sidebar-icon" style="font-size:16px">📍</span> Gérer les zones
        </a>
        <div class="sidebar-divider"></div>
        <a href="{{ route('incidents.create') }}" class="sidebar-cta">
            <span style="font-size:16px">💬</span> Ajouter un incident
        </a>
    </nav>
    <div class="sidebar-divider"></div>
    <div class="sidebar-footer">
        <div class="sidebar-footer-item">
            <span style="font-size:16px">👤</span>
            <span>{{ auth()->user()->username ?? '—' }}</span>
        </div>
        <div class="sidebar-footer-item">
            <span style="font-size:16px">🔰</span>
            <span>{{ ucfirst(auth()->user()->role ?? 'user') }}</span>
        </div>
    </div>
</div>

<div class="container py-4">

    <button class="sidebar-toggle" onclick="openSidebar()">☰</button>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h3 mb-0">Incidents</h1>
        </div>
        <a href="{{ route('incidents.create') }}" class="btn btn-primary">
            + Ajouter un incident
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="incidentsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Émis le</th>
                        <th>Photo ouv.</th>
                        <th>Photo ferm.</th>
                        <th>Clôture le</th>
                        <th>Discipline</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="incidentsBody">
                @forelse($incidents as $incident)
                    <tr data-id="{{ $incident->id_incident }}">

                        <td class="fw-semibold text-muted">
                            {{ $incident->id_incident }}
                        </td>

                        <td>
                            {{ $incident->date_emis
                                ? \Carbon\Carbon::parse($incident->date_emis)->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td>
                            @if($incident->photo_ouverte)
                                <a href="{{ asset('storage/'.$incident->photo_ouverte) }}"
                                   target="_blank">
                                    <img src="{{ asset('storage/'.$incident->photo_ouverte) }}"
                                         class="photo-thumb">
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <td>
                            @if($incident->photo_fermee)
                                <a href="{{ asset('storage/'.$incident->photo_fermee) }}"
                                   target="_blank">
                                    <img src="{{ asset('storage/'.$incident->photo_fermee) }}"
                                         class="photo-thumb">
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <td>
                            {{ $incident->date_cloture
                                ? \Carbon\Carbon::parse($incident->date_cloture)->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td>{{ $incident->discipline ?? '—' }}</td>

                        <td>
                            @php
                                $badgeMap = [
                                    'na'       => ['secondary', '⬛ N/A'],
                                    'ouvert'   => ['danger',    '🟥 Ouvert'],
                                    'en_cours' => ['warning',   '🟧 En cours'],
                                    'fermer'   => ['success',   '🟩 Fermé'],
                                ];
                                $b = $badgeMap[$incident->statut]
                                    ?? ['secondary', ucfirst($incident->statut)];
                            @endphp
                            <span class="badge bg-{{ $b[0] }}">{{ $b[1] }}</span>
                        </td>

                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('incidents.show', $incident->id_incident) }}"
                                   class="btn btn-outline-secondary">Voir</a>
                                @if($incident->statut !== 'fermer')
                                    <a href="{{ route('incidents.edit', $incident->id_incident) }}"
                                       class="btn btn-outline-primary">Modifier</a>
                                @endif
                                <form action="{{ route('incidents.destroy', $incident->id_incident) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cet incident ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger" type="submit">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="8" class="text-center text-muted py-5">
                            Aucun incident enregistré.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
const POLL_URL   = '{{ route("incidents.poll") }}';
const POLL_DELAY = 5000;

let lastKnownId = {{ $incidents->isNotEmpty() ? $incidents->first()->id_incident : 0 }};

async function pollIncidents() {
    try {
        const res  = await fetch(`${POLL_URL}?last_id=${lastKnownId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await res.json();

        if (data.nouveaux && data.nouveaux.length > 0) {
            lastKnownId = data.last_id;
            insertRows(data.nouveaux);
        }

        setLiveStatus(true);
    } catch (e) {
        setLiveStatus(false);
    }

    setTimeout(pollIncidents, POLL_DELAY);
}

function insertRows(nouveaux) {
    const tbody    = document.getElementById('incidentsBody');
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();

    [...nouveaux].reverse().forEach(inc => {
        if (tbody.querySelector(`tr[data-id="${inc.id}"]`)) return;
        const tr = buildRow(inc);
        tbody.insertBefore(tr, tbody.firstChild);
        tr.classList.add('row-new');
        setTimeout(() => tr.classList.remove('row-new'), 3000);
    });
}

function buildRow(inc) {
    const badgeMap = {
        'na':       ['secondary', '⬛ N/A'],
        'ouvert':   ['danger',    '🟥 Ouvert'],
        'en_cours': ['warning',   '🟧 En cours'],
        'fermer':   ['success',   '🟩 Fermé'],
    };
    const [color, label] = badgeMap[inc.statut] ?? ['secondary', inc.statut];
    const isClosed  = inc.statut === 'fermer';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const photoOuv = inc.photo_ouverte
        ? `<a href="/storage/${inc.photo_ouverte}" target="_blank">
               <img src="/storage/${inc.photo_ouverte}" class="photo-thumb">
           </a>`
        : '<span class="text-muted small">—</span>';

    const photoFerm = inc.photo_fermee
        ? `<a href="/storage/${inc.photo_fermee}" target="_blank">
               <img src="/storage/${inc.photo_fermee}" class="photo-thumb">
           </a>`
        : '<span class="text-muted small">—</span>';

    const tr = document.createElement('tr');
    tr.dataset.id = inc.id;
    tr.innerHTML = `
        <td class="fw-semibold text-muted">${inc.id}</td>
        <td>${inc.date_emis ?? '—'}</td>
        <td>${photoOuv}</td>
        <td>${photoFerm}</td>
        <td>${inc.date_cloture ?? '—'}</td>
        <td>${inc.discipline ?? '—'}</td>
        <td><span class="badge bg-${color}">${label}</span></td>
        <td class="text-end">
            <div class="btn-group btn-group-sm">
                <a href="${inc.url_voir}" class="btn btn-outline-secondary">Voir</a>
                ${!isClosed
                    ? `<a href="${inc.url_edit}" class="btn btn-outline-primary">Modifier</a>`
                    : ''}
                <form action="${inc.url_delete}" method="POST"
                      onsubmit="return confirm('Supprimer cet incident ?')"
                      style="display:inline">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-outline-danger" type="submit">Supprimer</button>
                </form>
            </div>
        </td>`;
    return tr;
}

function setLiveStatus(online) {
    const el = document.getElementById('liveIndicator');
    if (!el) return;
    el.classList.toggle('live-offline', !online);
}

function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(pollIncidents, POLL_DELAY);
});
</script>

@endsection