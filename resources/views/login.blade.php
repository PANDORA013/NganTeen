@extends('layout.master')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="login-box">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>

        @error('login')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('actionlogin') }}">
            @csrf
            <div class="mb-3">
                <input type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    placeholder="Email"
                    name="email" 
                    value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <input type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Password"
                    name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}">Create Account</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.login-box {
    max-width: 360px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}
.logo {
    height: 60px;
    width: auto;
    margin-bottom: 20px;
}
</style>
@endpush
