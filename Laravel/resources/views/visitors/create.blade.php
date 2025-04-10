@extends('layouts.app')

@section('title', $isUpdate ? 'Update Visitor' : 'Create Visitor')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">{{ $isUpdate ? 'Update' : 'Create new' }} Visitor</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isUpdate ? route('visitors.update', $visitor) : route('visitors.store') }}" class="needs-validation" novalidate>
                @csrf
                @if($isUpdate)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name', $visitor->full_name ?? '') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email', $visitor->email ?? '') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $visitor->phone ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="registration_date" class="form-label">Registration Date</label>
                        <input type="date" name="registration_date" id="registration_date" class="form-control @error('registration_date') is-invalid @enderror" 
                               value="{{ old('registration_date', $visitor->registration_date->format('Y-m-d') ?? '') }}">
                        @error('registration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdate ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary">
                        Back to list
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection