@extends('layouts.app')

@section('title', 'Visitors')

@section('content')
    <h1>Visitors</h1>

    <a href="{{ route('visitors.create') }}" class="btn btn-primary mb-3">Create New Visitor</a>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                @foreach($visitors as $visitor)
                    <tr>
                        <td>{{ $visitor->id }}</td>
                        <td>{{ $visitor->full_name }}</td>
                        <td>{{ $visitor->email}}</td>
                        <td>{{ $visitor->phone }}</td>
                        <td>{{ $visitor->registration_date}}</td>
                        <td>
                            <a href="{{ route('visitors.show', $visitor) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('visitors.edit', $visitor) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>

    <x-pagination :paginator="$visitors" />
@endsection