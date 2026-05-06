@extends('layouts.app')

@section('content')

<h1>Gestion des utilisateurs</h1>

<a href="{{ route('users.create') }}" class="btn btn-add">➕ Ajouter</a>

<table class="table">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th>Actions</th>
</tr>

@foreach($users as $user)
<tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->role }}</td>
    <td>
        <a href="{{ route('users.edit', $user->id) }}">✏️</a>
    </td>
</tr>
@endforeach

</table>

@endsection