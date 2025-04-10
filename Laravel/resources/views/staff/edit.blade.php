@extends('layouts.app')

@section('title', $isUpdate ? 'Update Staff' : 'Create Staff')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">{{ $isUpdate ? 'Update' : 'Create new' }} Staff</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isUpdate ? route('staff.update', $staff) : route('staff.store') }}" class="needs-validation" novalidate>
                @csrf
                @if($isUpdate)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name', $staff->full_name ?? '') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-6 mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input name="position" id="position" class="form-control @error('position') is-invalid @enderror" 
                                value="{{ old('position', $staff->position ?? '') }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $staff->phone ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" step="0.01" name="salary" id="salary" class="form-control @error('salary') is-invalid @enderror" 
                               value="{{ old('salary', $staff->salary ?? '') }}">
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="hire_date" class="form-label">Hire Date</label>
                        <input type="date" name="hire_date" id="hire_date" class="form-control @error('hire_date') is-invalid @enderror" 
                               value="{{ old('hire_date', $staff->hire_date->format('Y-m-d') ?? '') }}">
                        @error('hire_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="col-8 mb-3">
                        <label for="exhibitions" class="form-label">Exhibitions</label>
                        <select name="exhibitions[]" id="exhibitions" multiple class="form-control @error('exhibitions') is-invalid @enderror">
                            @foreach($exhibitions as $exhibition)
                                <option value="{{ $exhibition->id }}" 
                                    @if(old('exhibitions'))
                                        {{ in_array($exhibition->id, old('exhibitions')) ? 'selected' : '' }}
                                    @else
                                        {{ isset($staff) && $staff->exhibitions->contains($exhibition->id) ? 'selected' : '' }}
                                    @endif
                                >
                                    {{ $exhibition->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exhibition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdate ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
                        Back to list
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection