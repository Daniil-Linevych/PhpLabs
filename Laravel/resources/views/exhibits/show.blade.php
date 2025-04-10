@extends('layouts.app')

@section('title', 'Exhibits | '.$exhibit->name)

@section('content')
    <h1>Exhibit: {{ $exhibit->name }}</h1>

    <table class="table">
        <tbody>
            <tr>
                <th>Id</th>
                <td>{{ $exhibit->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $exhibit->name }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $exhibit->description }}</td>
            </tr>
            <tr>
                <th>Creation Year</th>
                <td>{{ $exhibit->creationYear }}</td>
            </tr>
            <tr>
                <th>Author</th>
                <td>{{ $exhibit->author }}</td>
            </tr>
            <tr>
                <th>Exhibition</th>
                <td>{{ $exhibit->exhibition->name ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="buttons-container">     
        <a href="{{ route('exhibits.index') }}" class="btn btn-outline-secondary">Back to list</a>
        <a href="{{ route('exhibits.edit', $exhibit) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('exhibits.destroy', $exhibit) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>                
    </div>  
@endsection