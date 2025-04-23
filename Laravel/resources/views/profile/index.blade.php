@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Your role: {{ $user->role->name }}</p>

    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <p class="fs-5 fw-normal m-0">Logout</p>
        </button>
    </form>

    @if($user->role->slug == 'user')
    <div class="container mt-4">
    <div class="row g-3">
        <div class="col-md-6">
            <h3 class="mb-4">Buyed tickets:</h3>
            <ul class="list-group">
                @foreach($tickets as $ticket)
                <div class="container">
                    <div class="row">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-decoration-none col-md-10">
                            <li class="list-group-item">Ticket {{ $ticket->id }}</li>
                        </a>
                        <a href="{{ route('tickets.sell', $ticket) }}" class="btn btn-danger mt-1 col-md-2">Sell</a>
                    </div>
                </div>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
    @endif
</div>
@endsection