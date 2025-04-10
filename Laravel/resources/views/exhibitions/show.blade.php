@extends('layouts.app')

@section('title', 'Exhibitions | '.$exhibition->name)

@section('content')
    <h1>Exhibition: {{ $exhibition->name }}</h1>

    <table class="table">
        <tbody>
            <tr>
                <th>Id</th>
                <td>{{ $exhibition->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $exhibition->name }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ $exhibition->start_date->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ $exhibition->end_date->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>Staff Memers</th>
                <td>{{ $exhibition->getStaffMembersString() }}</td>
            </tr>
        </tbody>
    </table>

    <div class="buttons-container">     
        <a href="{{ route('exhibitions.index') }}" class="btn btn-outline-secondary">Back to list</a>
        <a href="{{ route('exhibitions.edit', $exhibition) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('exhibitions.destroy', $exhibition) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>                
    </div>
    
    <div class="container mt-4">
    <div class="row g-3">
        <div class="col-md-6">
            <h3 class="mb-4">Related exhibits:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exhibition->exhibits as $exhibit)
                         <tr>
                            <td>{{ $exhibit->id }}</td>
                            <td><a href="{{ route('exhibits.show', $exhibit) }}" class="text-decoration-none text-dark">{{ $exhibit->name }}</a></td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3 class="mb-4">Related Tickets:</h3>
            <ul class="list-group">
                @foreach($exhibition->tickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}" class="text-decoration-none">
                        <li class="list-group-item">Ticket {{ $ticket->id }}</li>
                    </a>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
@endsection