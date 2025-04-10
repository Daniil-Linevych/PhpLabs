@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <h1>Tickets</h1>

    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create New Ticket</a>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Price</th>
                <th>Purchase Date</th>
                <th>Visitor</th>
                <th>Exhibition</th>
            </tr>
        </thead>
        <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->price }}</td>
                        <td>{{ $ticket->purchase_date->format('Y-m-d')}}</td>
                        <td>{{ $ticket->visitor->full_name ?? 'N/A' }}</td>
                        <td>{{ $ticket->exhibition->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection