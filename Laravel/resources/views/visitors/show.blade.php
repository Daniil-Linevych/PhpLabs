@extends('layouts.app')

@section('title', 'Visitors | '.$visitor->name)

@section('content')
    <h1>Visitor: {{ $visitor->name }}</h1>

    <table class="table">
        <tbody>
            <tr>
                <th>Id</th>
                <td>{{ $visitor->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $visitor->full_name }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $visitor->email }}</td>
            </tr>
            <tr>
                <th>Creation Year</th>
                <td>{{ $visitor->phone }}</td>
            </tr>
            <tr>
                <th>Registration Date</th>
                <td>{{ $visitor->registration_date->format('Y-m-d') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="buttons-container">     
        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">Back to list</a>
        <a href="{{ route('visitors.edit', $visitor) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>                
    </div>  

    <div class="container mt-4">
    <div class="row g-3">
        <div class="col-md-6">
            <h3 class="mb-4">Buyed tickets:</h3>
            <ul class="list-group">
                @foreach($visitor->tickets as $ticket)
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
@endsection