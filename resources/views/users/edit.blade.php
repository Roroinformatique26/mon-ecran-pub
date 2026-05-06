@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <!-- HEADER -->
        <div class="card-header">
            <h2>✏️ Modifier utilisateur</h2>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <!-- USERNAME -->
                <label>Username</label>
                <input type="text" name="username" value="{{ $user->username }}" required>

                <!-- PASSWORD -->
                <label>Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password">

                <!-- ROLE -->
                <label>Rôle</label>
                <select name="role" required>

                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                        User
                    </option>

                    <option value="incident" {{ $user->role == 'incident' ? 'selected' : '' }}>
                        Incident
                    </option>

                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                </select>

                <br><br>

                <!-- ACTIONS -->
                <div class="card-footer">

                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        ⬅ Retour
                    </a>

                    <button type="submit" class="btn btn-warning">
                        💾 Modifier
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection