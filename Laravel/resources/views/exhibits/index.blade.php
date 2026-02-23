@extends('layouts.app')

@section('title', 'Exhibits')

@section('content')
    <h1>Exhibits</h1>

    <a href="{{ route('exhibits.create') }}" class="btn btn-primary mb-3">Create New Exhibit</a>

    <form method="get">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control"
                    value="{{ request('name') }}" 
                    placeholder="Filter by name">
            </div>
            <div class="col-md-3">
                <input type="text" name="author" class="form-control"
                    value="{{ request('author') }}" 
                    placeholder="Filter by author">
            </div>
            <div class="col-md-3">
                <input type="text" name="creation_year" class="form-control"
                    value="{{ request('creation_year') }}" 
                    placeholder="Filter by creation year">
            </div>
            <div class="col-md-3">
                <input type="text" name="exhibition" class="form-control"
                    value="{{ request('exhibtion') }}" 
                    placeholder="Filter by exhibition">
            </div>
        </div>
        
        <button type="submit" class="btn btn-info mt-2">Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary mt-2 ms-2">Reset</a>
    </form>

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