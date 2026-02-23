@extends('layouts.app')

@section('title', 'Exhibitions')

@section('content')
    <h1>Exhibitions</h1>

    <a href="{{ route('exhibitions.create') }}" class="btn btn-primary mb-3">Create New Exhibit</a>

    <form method="get">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control"
                    value="{{ request('name') }}" 
                    placeholder="Filter by name">
            </div>
            <div class="col-md-3">
                <input type="text" name="start_date" class="form-control"
                    value="{{ request('start_date') }}" 
                    placeholder="Filter by start date">
            </div>
            <div class="col-md-3">
                <input type="text" name="end_date" class="form-control"
                    value="{{ request('end_date') }}" 
                    placeholder="Filter by end date">
            </div>
            <div class="col-md-3">
                <input type="text" name="staff" class="form-control"
                    value="{{ request('staff') }}" 
                    placeholder="Filter by staff">
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
                <th>Start Date</th>
                <th>End Date</th>
                <th>Staff Members</th>
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
                        <td>{{ $exhibition->getStaffMembersString() }}</td>
                        <td>
                            <a href="{{ route('exhibitions.show', $exhibition) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('exhibitions.edit', $exhibition) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>

    <x-pagination :paginator="$exhibitions" :perPageOptions="[1, 2, 3]"/>
@endsection