@extends('layouts.penjual')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Daftar Menu</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                Tambah Menu
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Warung</th>
                            <th>Area</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>
                                @if($menu->gambar)
                                    <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" width="50">
                                @else
                                    <span class="text-muted">No img</span>
                                @endif
                            </td>
                            <td>{{ $menu->nama_menu }}</td>
                            <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>{{ $menu->stok }}</td>
                            <td>{{ $menu->nama_warung }}</td>
                            <td>{{ $menu->area_kampus }}</td>
                            <td>
                                <a href="{{ route('penjual.menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('penjual.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus menu?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
