<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - Nganteen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Nganteen</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Dashboard Penjual</h2>
        
        <div class="card mt-4">
            <div class="card-header">
                Tambah Menu Makanan
            </div>
            <div class="card-body">
                <form action="{{ route('penjual.makanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Makanan</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kampus</label>
                        <select class="form-control" name="campus_location" required>
                            <option value="">Pilih Lokasi</option>
                            <option value="Kampus 1">Kampus 1</option>
                            <option value="Kampus 2">Kampus 2</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto Makanan</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Tambah Menu</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>