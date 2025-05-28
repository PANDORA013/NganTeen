{{-- resources/views/pembeli/home.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Beranda Pembeli</title>
</head>
<body>
    <h1>Daftar Makanan</h1>

    @if($makanan->isEmpty())
        <p>Belum ada makanan tersedia.</p>
    @else
        <ul>
            @foreach($makanan as $item)
                <li>
                    <strong>{{ $item->name }}</strong> - Rp {{ number_format($item->price, 0, ',', '.') }}<br>
                    Lokasi: {{ $item->location }}<br>
                    Estimasi Waktu: {{ $item->estimated_time }} menit<br>
                    <img src="{{ asset('storage/' . $item->photo_url) }}" alt="{{ $item->name }}" width="100">
                </li>
                <hr>
            @endforeach
        </ul>
    @endif
</body>
</html>
