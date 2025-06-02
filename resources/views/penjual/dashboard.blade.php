@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Penjual Dashboard') }}</div>

                <div class="card-body">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <div class="mt-4">
                        <h6>Menu Penjual:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ url('/penjual/menu') }}" class="btn btn-primary">Kelola Menu</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ url('/penjual/orders') }}" class="btn btn-primary">Lihat Pesanan</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection