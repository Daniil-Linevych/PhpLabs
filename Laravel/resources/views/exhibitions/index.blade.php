@extends('layouts.app')

@section('title', 'Exhibitions')

@section('content')
    <h1>Exhibitions</h1>

    <a href="{{ route('exhibitions.create') }}" class="btn btn-primary mb-3">Create New Exhibit</a>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                @foreach($exhibitions as $exhibition)
                    <tr>
                        <td>{{ $exhibition->id }}</td>
                        <td>{{ $exhibition->name }}</td>
                        <td>{{ $exhibition->start_date->format('Y-m-d')}}</td>
                        <td>{{ $exhibition->end_date->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('exhibitions.show', $exhibition) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('exhibitions.edit', $exhibition) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection