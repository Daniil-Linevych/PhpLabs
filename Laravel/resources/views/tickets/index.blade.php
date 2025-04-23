@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <h1>Tickets</h1>

    @auth
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create New Ticket</a>
        @endif
    @endauth
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Price</th>
                <th>Purchase Date</th>
                <th>Visitor</th>
                <th>Exhibition</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->price }}</td>
                        <td>{{ $ticket->purchase_date?->format('Y-m-d H:i:s') ?? ''}}</td>
                        <td>{{ $ticket->visitor->full_name ?? 'Free' }}</td>
                        <td>{{ $ticket->exhibition->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info btn-sm">Show</a>
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                            @endauth
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>

    <x-pagination :paginator="$tickets" />
@endsection