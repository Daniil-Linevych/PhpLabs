@extends('layouts.app')

@section('title', $isUpdate ? 'Update Ticket' : 'Create Ticket')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1 class="h4">{{ $isUpdate ? 'Update' : 'Create new' }} Ticket</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isUpdate ? route('tickets.update', $ticket) : route('tickets.store') }}" class="needs-validation" novalidate>
                @csrf
                @if($isUpdate)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $ticket->price ?? '') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="datetime-local" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" 
                               value="{{ old('purchase_date', $ticket->purchase_date ?? now()->format('Y-m-d H:i')) }}">
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="exhibition_id" class="form-label">exhibition</label>
                        <select name="exhibition_id" id="exhibition_id" class="form-select @error('exhibition_id') is-invalid @enderror">
                            <option value="">Select exhibition</option>
                            @foreach($exhibitions as $exhibition)
                                <option value="{{ $exhibition->id }}" 
                                    {{ (old('exhibition_id', $ticket->exhibition_id ?? '') == $exhibition->id ? 'selected' : '' ) }}>
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
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                        Back to list
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection