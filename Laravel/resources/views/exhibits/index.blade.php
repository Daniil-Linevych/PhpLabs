@extends('layouts.app')

@section('title', 'Exhibits')

@section('content')
    <h1>Exhibits</h1>

    <a href="{{ route('exhibits.create') }}" class="btn btn-primary mb-3">Create New Exhibit</a>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Author</th>
                <th>Creation Year</th>
                <th>Exhibition</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                @foreach($exhibits as $exhibit)
                    <tr>
                        <td>{{ $exhibit->id }}</td>
                        <td>{{ $exhibit->name }}</td>
                        <td>{{ $exhibit->author}}</td>
                        <td>{{ $exhibit->creation_year }}</td>
                        <td>{{ $exhibit->exhibition->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('exhibits.show', $exhibit) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('exhibits.edit', $exhibit) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
    
    <x-pagination :paginator="$exhibits" />
@endsection