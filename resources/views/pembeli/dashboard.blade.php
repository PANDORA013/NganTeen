@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Available Menu') }}</div>

                <div class="card-body">
                    <div class="row">
                        @foreach($menus as $menu)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $menu->nama_menu }}</h5>
                                        <p class="card-text">
                                            Harga: Rp {{ number_format($menu->harga, 0, ',', '.') }}<br>
                                            Stok: {{ $menu->stok }}<br>
                                            Warung: {{ $menu->nama_warung }}<br>
                                            Area: {{ $menu->area_kampus }}
                                        </p>
                                        @if($menu->stok > 0)
                                            <form action="{{ url('/pembeli/cart') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                <div class="input-group mb-3">
                                                    <input type="number" name="jumlah" class="form-control" value="1" min="1" max="{{ $menu->stok }}">
                                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                                </div>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary" disabled>Out of Stock</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection