@extends('layouts.app')

@section('content')

<h1>Liste des incidents</h1>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Département</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($incidents as $incident)
        <tr>
            <td>{{ $incident->id_incident }}</td>
            <td>{{ $incident->date_incident }}</td>
            <td>{{ $incident->departement }}</td>
            <td style="color:red">
                    @php
                        $status = strtolower(str_replace(' ', '_', $incident->statut));
                    @endphp

                    <span class="badge {{ $status }}">
                        {{ $incident->statut }}
                    </span>
            </td>
            <td>
                <a href="{{ route('incidents.show', $incident) }}">Détails</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection