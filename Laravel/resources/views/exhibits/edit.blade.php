@extends('layouts.app')

@section('title', $isUpdate ? 'Update Exhibit' : 'Create Exhibit')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">{{ $isUpdate ? 'Update' : 'Create new' }} Exhibit</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isUpdate ? route('exhibits.update', $exhibit) : route('exhibits.store') }}" class="needs-validation" novalidate>
                @csrf
                @if($isUpdate)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $exhibit->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ old('description', $exhibit->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" 
                               value="{{ old('author', $exhibit->author ?? '') }}">
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="creation_year" class="form-label">Creation Year</label>
                        <input type="text" name="creation_year" id="creation_year" class="form-control @error('creation_year') is-invalid @enderror" 
                               value="{{ old('creation_year', $exhibit->creation_year ?? '') }}">
                        @error('creation_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-8 mb-3">
                        <label for="exhibition_id" class="form-label">Exhibition</label>
                        <select name="exhibition_id" id="exhibition_id" class="form-select @error('exhibition_id') is-invalid @enderror">
                            <option value="">Select Exhibition</option>
                            @foreach($exhibitions as $exhibition)
                                <option value="{{ $exhibition->id }}" 
                                    {{ (old('exhibition_id', $exhibit->exhibition_id ?? '') == $exhibition->id ? 'selected' : '' ) }}>
                                    {{ $exhibition->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exhibition_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdate ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('exhibits.index') }}" class="btn btn-outline-secondary">
                        Back to list
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection