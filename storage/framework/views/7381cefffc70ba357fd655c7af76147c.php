<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Dashboard Penjual</span>
                        <button id="refreshStats" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i> Perbarui
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Menu</h5>
                                    <p id="menuCount" class="h4"><?php echo e($menuCount ?? 0); ?></p>
                                    <p class="text-muted mb-0">Menu aktif di toko Anda</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Pesanan Baru</h5>
                                    <p id="newOrders" class="h4"><?php echo e($newOrders ?? 0); ?></p>
                                    <p class="text-muted mb-0">Menunggu konfirmasi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Pendapatan</h5>
                                    <p id="totalRevenue" class="h4">Rp <?php echo e(number_format($totalRevenue ?? 0, 0, ',', '.')); ?></p>
                                    <p class="text-muted mb-0">Dari pesanan selesai</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Cepat -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Aksi Cepat
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo e(route('penjual.menu.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Menu Baru
                                </a>
                                <a href="<?php echo e(route('penjual.orders.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-shopping-bag me-1"></i> Lihat Semua Pesanan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pesanan Terbaru -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Pesanan Terbaru</span>
                            <a href="<?php echo e(route('penjual.orders.index')); ?>" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if(isset($recentOrders) && $recentOrders->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>#<?php echo e($order->id); ?></td>
                                                    <td><?php echo e($order->user->name ?? 'Guest'); ?></td>
                                                    <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                                                    <td>Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?></td>
                                                    <td>
                                                        <?php
                                                            $statusClasses = [
                                                                'pending' => 'bg-warning',
                                                                'processing' => 'bg-info',
                                                                'siap_diambil' => 'bg-primary',
                                                                'selesai' => 'bg-success',
                                                                'batal' => 'bg-danger',
                                                                'cancelled' => '-secondary'
                                                            ];
                                                            $statusTexts = [
                                                                'pending' => 'Menunggu',
                                                                'processing' => 'Diproses',
                                                                'siap_diambil' => 'Siap Diambil',
                                                                'selesai' => 'Selesai',
                                                                'batal' => 'Dibatalkan',
                                                                'cancelled' => 'Dibatalkan'
                                                            ];
                                                        ?>
                                                        <span class="badge <?php echo e($statusClasses[$order->status] ?? 'bg-secondary'); ?>">
                                                            <?php echo e($statusTexts[$order->status] ?? $order->status); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('penjual.orders.show', $order->id)); ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            Lihat
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada pesanan terbaru</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Bantuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Panduan Singkat</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Gunakan tombol "Tambah Menu Baru" untuk menambahkan menu makanan/minuman
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-shopping-bag text-primary me-2"></i>
                        Kelola pesanan masuk melalui halaman "Pesanan"
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-sync-alt text-primary me-2"></i>
                        Gunakan tombol perbarui untuk memperbarui statistik
                    </li>
                </ul>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Jika membutuhkan bantuan lebih lanjut, hubungi tim dukungan kami.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
    // Function to update dashboard stats
    function updateDashboardStats() {
        fetch('<?php echo e(route("penjual.dashboard.stats")); ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update menu count
            document.getElementById('menuCount').textContent = data.menuCount;
            
            // Update new orders
            document.getElementById('newOrders').textContent = data.newOrders;
            
            // Update total revenue
            document.getElementById('totalRevenue').textContent = data.formattedRevenue;
            
            // Show success message
            showToast('Data berhasil diperbarui', 'success');
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            showToast('Gagal memperbarui data', 'error');
        });
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.style.zIndex = '1100';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function () {
            document.body.removeChild(toast);
        });
    }
    
    // Update stats when refresh button is clicked
    document.getElementById('refreshStats').addEventListener('click', updateDashboardStats);
    
    // Update stats every 5 minutes (300000 ms)
    setInterval(updateDashboardStats, 300000);
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.penjual', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen-main\resources\views/penjual/dashboard.blade.php ENDPATH**/ ?>