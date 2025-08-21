@extends('layouts.admin')

@section('title', 'Landing Page Features')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-star me-2 text-primary"></i>Landing Page Features
                    </h1>
                    <p class="text-muted mb-0">Kelola fitur-fitur yang ditampilkan di halaman landing</p>
                </div>
                <a href="{{ route('admin.content.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Features Form -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit me-2"></i>Edit Fitur Landing Page
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.content.features.store') }}" method="POST">
                @csrf
                <div id="features-container">
                    @foreach($features as $index => $feature)
                    <div class="feature-item border rounded p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Fitur {{ $index + 1 }}</h6>
                            @if($index > 0)
                            <button type="button" class="btn btn-sm btn-outline-danger remove-feature">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Judul Fitur</label>
                                    <input type="text" class="form-control" name="features[{{ $index }}][title]" 
                                           value="{{ $feature['title'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Icon (FontAwesome)</label>
                                    <input type="text" class="form-control" name="features[{{ $index }}][icon]" 
                                           value="{{ $feature['icon'] }}" placeholder="fas fa-clock" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Warna</label>
                                    <select class="form-control" name="features[{{ $index }}][color]" required>
                                        <option value="orange" {{ $feature['color'] == 'orange' ? 'selected' : '' }}>Orange</option>
                                        <option value="blue" {{ $feature['color'] == 'blue' ? 'selected' : '' }}>Blue</option>
                                        <option value="green" {{ $feature['color'] == 'green' ? 'selected' : '' }}>Green</option>
                                        <option value="purple" {{ $feature['color'] == 'purple' ? 'selected' : '' }}>Purple</option>
                                        <option value="red" {{ $feature['color'] == 'red' ? 'selected' : '' }}>Red</option>
                                        <option value="yellow" {{ $feature['color'] == 'yellow' ? 'selected' : '' }}>Yellow</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="features[{{ $index }}][description]" 
                                      rows="3" required>{{ $feature['description'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary" id="add-feature">
                        <i class="fas fa-plus me-2"></i>Tambah Fitur
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-eye me-2"></i>Preview Landing Page
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($features as $feature)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="w-16 h-16 bg-{{ $feature['color'] }}-100 rounded-2xl d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                                <i class="{{ $feature['icon'] }} text-2xl text-{{ $feature['color'] }}-600" style="font-size: 1.5rem;"></i>
                            </div>
                            <h5 class="card-title">{{ $feature['title'] }}</h5>
                            <p class="card-text text-muted">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let featureIndex = {{ count($features) }};
    
    // Add new feature
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newFeature = `
            <div class="feature-item border rounded p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Fitur ${featureIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-feature">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Judul Fitur</label>
                            <input type="text" class="form-control" name="features[${featureIndex}][title]" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Icon (FontAwesome)</label>
                            <input type="text" class="form-control" name="features[${featureIndex}][icon]" 
                                   placeholder="fas fa-clock" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <select class="form-control" name="features[${featureIndex}][color]" required>
                                <option value="orange">Orange</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="purple">Purple</option>
                                <option value="red">Red</option>
                                <option value="yellow">Yellow</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="features[${featureIndex}][description]" 
                              rows="3" required></textarea>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newFeature);
        featureIndex++;
    });
    
    // Remove feature
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });
});
</script>
@endsection
