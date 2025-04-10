@extends('layouts.app')

@section('title', 'Tickets | '.$ticket->id)

@section('content')
    <h1>Ticket: {{ $ticket->id }}</h1>

    <table class="table">
        <tbody>
            <tr>
                <th>Id</th>
                <td>{{ $ticket->id }}</td>
            </tr>
            <tr>
                <th>Price</th>
                <td>{{ $ticket->price }}</td>
            </tr>
            <tr>
                <th>Purchase Date</th>
                <td>{{ $ticket->purchase_date }}</td>
            </tr>
            <tr>
                <th>Visitor</th>
                <td>{{ $ticket->visitor->full_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Exhibition</th>
                <td>{{ $ticket->exhibition->name ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="buttons-container">     
        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Back to list</a>
        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            <a href="{{ route('tickets.buy', $ticket) }}" class="btn btn-info">Buy</a>
        </form>                
    </div>  
@endsection