<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .table {
            margin-top: 20px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn-sm {
            margin-right: 5px;
        }

        .actions-column {
            white-space: nowrap;
        }

        .buttons-container {
            display: flex;
            gap: 5px; 
            align-items: center;
        }
        .buttons-container form {
            margin: 0; 
        }

        .navbar-fields{
            display: flex;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar-dark bg-dark mb-4">
        <div class="container">
        <div class="navbar-nav flex-row w-100">
            <div class="d-flex">
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <a class="nav-link p-3" href="{{ route('exhibits.index') }}"><p class="fs-5 fw-normal">Exhibits</p></a>
                    <a class="nav-link p-3" href="{{ route('exhibitions.index') }}"><p class="fs-5 fw-normal">Exhibitions</p></a>
                    <a class="nav-link p-3" href="{{ route('staff.index') }}"><p class="fs-5 fw-normal">Staff</p></a>
                    <a class="nav-link p-3" href="{{ route('visitors.index') }}"><p class="fs-5 fw-normal">Visitors</p></a>
                    <a class="nav-link p-3" href="{{ route('tickets.index') }}"><p class="fs-5 fw-normal">Tickets</p></a>
                @elseif(auth()->check() && auth()->user()->hasRole('worker'))
                    <a class="nav-link p-3" href="{{ route('exhibits.index') }}"><p class="fs-5 fw-normal">Exhibits</p></a>
                    <a class="nav-link p-3" href="{{ route('exhibitions.index') }}"><p class="fs-5 fw-normal">Exhibitions</p></a>
                @else
                    <a class="nav-link p-3" href="{{ route('tickets.index') }}"><p class="fs-5 fw-normal">Tickets</p></a>
                @endif
            </div>
            <div class="ms-auto d-flex">
                @if(auth()->check())
                    <a class="nav-link p-3" href="{{ route('profile.index') }}"><p class="fs-5 fw-normal">Profile</p></a>
                @else
                    <a class="nav-link p-3" href="{{ route('login') }}"><p class="fs-5 fw-normal">Login</p></a>
                    <a class="nav-link p-3" href="{{ route('register') }}"><p class="fs-5 fw-normal">Register</p></a>
                @endif
            </div>
        </div>
    </div>

        </nav>

        <main class="py-4">
            <div class="container">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
