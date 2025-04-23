@extends('layouts.app')

@section('title', 'Staff Members | '.$staff->full_name)

@section('content')
    <h1>Staff Member: {{ $staff->full_name }}</h1>

    <table class="table">
        <tbody>
            <tr>
                <th>Id</th>
                <td>{{ $staff->id }}</td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td>{{ $staff->full_name }}</td>
            </tr>
            <tr>
                <th>Position</th>
                <td>{{ $staff->position }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $staff->phone}}</td>
            </tr>
            <tr>
                <th>Hire Date</th>
                <td>{{ $staff->hire_date->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>Salary</th>
                <td>{{ $staff->salary }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $staff->user ? $staff->user->email : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Exhibitions</th>
                <td>{{ $staff->getExhibitionsString() }}</td>
            </tr>
        </tbody>
    </table>

    <div class="buttons-container">     
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">Back to list</a>
        <a href="{{ route('staff.edit', $staff) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('staff.destroy', $staff) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>                
    </div>  
@endsection