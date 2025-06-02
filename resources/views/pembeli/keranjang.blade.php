@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Shopping Cart') }}</div>

                <div class="card-body">
                    @if($keranjang->count() > 0)
                        <form action="{{ url('/pembeli/checkout') }}" method="POST">
                            @csrf
                            <div class="list-group mb-3">
                                @php $total = 0; @endphp
                                @foreach($keranjang as $item)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $item->menu->nama_menu }}</h6>
                                                <small>{{ $item->menu->nama_warung }} - {{ $item->menu->area_kampus }}</small>
                                                <p class="mb-0">
                                                    Rp {{ number_format($item->menu->harga, 0, ',', '.') }} x {{ $item->jumlah }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="h6">Rp {{ number_format($item->menu->harga * $item->jumlah, 0, ',', '.') }}</span>
                                                <form action="{{ url('/pembeli/cart/' . $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm ms-2">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @php $total += $item->menu->harga * $item->jumlah; @endphp
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Total:</h5>
                                <h5>Rp {{ number_format($total, 0, ',', '.') }}</h5>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Checkout</button>
                            </div>
                        </form>
                    @else
                        <p class="text-center mb-0">Your cart is empty.</p>
                        <div class="text-center mt-3">
                            <a href="{{ url('/home') }}" class="btn btn-primary">Browse Menu</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection