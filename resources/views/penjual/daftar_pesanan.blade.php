@extends('layouts.app')

@section('content')
<div class="container max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Daftar Pesanan (COD)</h2>
    @foreach($orders as $order)
        <div class="border p-2 mb-2">
            <p>{{ $order->menu->nama }} - Status: {{ $order->status }}</p>

            @if($order->status != 'paid')
            <form action="{{ route('order.paid', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white p-1 rounded">Tandai Sudah Dibayar</button>
            </form>
            @endif
        </div>
    @endforeach
</div>
@endsection
