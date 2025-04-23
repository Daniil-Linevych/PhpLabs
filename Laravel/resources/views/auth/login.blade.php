@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

        <label for="inputEmail">Email</label>
        <input 
            type="email" 
            id="inputEmail" 
            class="form-control @error('email') is-invalid @enderror" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="email" 
            autofocus
        >
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <label for="inputPassword">Password</label>
        <input 
            type="password" 
            id="inputPassword" 
            class="form-control @error('password') is-invalid @enderror" 
            name="password" 
            required 
            autocomplete="current-password"
        >
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <button class="btn btn-lg btn-primary mt-3" type="submit">
            Sign in
        </button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        @endif
    </form>
</div>
@endsection