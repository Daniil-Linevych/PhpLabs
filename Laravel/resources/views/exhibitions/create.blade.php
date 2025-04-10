@extends('layouts.app')

@section('title', $isUpdate ? 'Update Exhibition' : 'Create Exhibition')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">{{ $isUpdate ? 'Update' : 'Create new' }} Exhibition</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isUpdate ? route('exhibitions.update', $exhibition) : route('exhibitions.store') }}" class="needs-validation" novalidate>
                @csrf
                @if($isUpdate)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $exhibition->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                value="{{ old('start_date', $exhibition->start_date ?? '') }}"></input>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                 value="{{ old('end_date', $exhibition->end_date ?? '') }}"></input>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="staff" class="form-label">Staff Members</label>
                        <select name="staff[]" id="staff" multiple class="form-control @error('staff') is-invalid @enderror">
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" 
                                    @if(old('staff'))
                                        {{ in_array($staff->id, old('staff')) ? 'selected' : '' }}
                                    @else
                                        {{ isset($exhibition) && $exhibition->staff->contains($staff->id) ? 'selected' : '' }}
                                    @endif
                                >
                                    {{ $staff->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('staff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdate ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('exhibitions.index') }}" class="btn btn-outline-secondary">
                        Back to list
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection