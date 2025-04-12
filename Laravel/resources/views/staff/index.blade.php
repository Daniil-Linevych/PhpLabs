@extends('layouts.app')

@section('title', 'Staff')

@section('content')
    <h1>Staff</h1>

    <a href="{{ route('staff.create') }}" class="btn btn-primary mb-3">Create New Staff Member</a>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Full Name</th>
                <th>Position</th>
                <th>Phone</th>
                <th>Hire Date</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                @foreach($staff as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->full_name }}</td>
                        <td>{{ $member->position}}</td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->hire_date->format('Y-m-d') }}</td>
                        <td>{{ $member->salary }}</td>
                        <td>
                            <a href="{{ route('staff.show', $member) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('staff.edit', $member) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>

    <x-pagination :paginator="$staff" />
@endsection