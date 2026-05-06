<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PlanEx</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

@include('partials.navbar')

<main class="container">
    @yield('content')
</main>

@include('partials.footer')

</body>
</html>