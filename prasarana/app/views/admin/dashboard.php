<?php
$pageTitle = 'Dashboard Admin - SIADU';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['user']['nama']) ?></p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-card-icon"><i data-lucide="clipboard-list"></i></div>
        <span>Total Pengaduan</span>
        <strong><?= $totalPengaduan ?? 0 ?></strong>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-icon"><i data-lucide="clock"></i></div>
        <span>Diajukan</span>
        <strong><?= $menunggu ?? 0 ?></strong>
    </div>
    <div class="stat-card info">
        <div class="stat-card-icon"><i data-lucide="settings"></i></div>
        <span>Diproses</span>
        <strong><?= $diproses ?? 0 ?></strong>
    </div>
    <div class="stat-card success">
        <div class="stat-card-icon"><i data-lucide="check-circle"></i></div>
        <span>Selesai</span>
        <strong><?= $selesai ?? 0 ?></strong>
    </div>
    <?php if (($pendingAkun ?? 0) > 0): ?>
    <div class="stat-card danger" style="cursor:pointer" onclick="window.location='<?= url('admin/verifikasi') ?>'">
        <div class="stat-card-icon"><i data-lucide="user-check"></i></div>
        <span>Akun Menunggu</span>
        <strong><?= $pendingAkun ?></strong>
        <p style="font-size:11px;color:var(--danger);margin-top:4px;font-weight:600">Klik untuk verifikasi →</p>
    </div>
    <?php endif; ?>
</div>

<div class="content-card">

    <div class="section-header">
        <h2>Pengaduan Terbaru</h2>
        <a href="<?= url('admin/pengaduan') ?>">Lihat Semua →</a>
    </div>

    <?php if (!empty($terbaru)): ?>

    <!-- Desktop: tabel -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Judul</th><th>Siswa</th><th>Kategori</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($terbaru as $item):
                $statusClass = match($item['status'] ?? '') {
                    'Diajukan'        => 'badge-diajukan',
                    'Diproses'        => 'badge-diproses',
                    'Dalam Perbaikan' => 'badge-perbaikan',
                    'Selesai'         => 'badge-selesai',
                    'Ditolak'         => 'badge-ditolak',
                    default           => ''
                };
            ?>
                <tr>
                    <td style="color:var(--gray-400);font-weight:600">#<?= $item['id'] ?></td>
                    <td style="font-weight:500"><?= htmlspecialchars($item['judul'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span></td>
                    <td><a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile: card list -->
    <div class="mobile-only">
        <?php foreach ($terbaru as $item):
            $statusClass = match($item['status'] ?? '') {
                'Diajukan'        => 'badge-diajukan',
                'Diproses'        => 'badge-diproses',
                'Dalam Perbaikan' => 'badge-perbaikan',
                'Selesai'         => 'badge-selesai',
                'Ditolak'         => 'badge-ditolak',
                default           => ''
            };
        ?>
        <div style="padding:14px 18px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;gap:12px">
            <div style="flex:1;min-width:0">
                <div style="font-weight:600;font-size:13.5px;margin-bottom:3px"><?= htmlspecialchars($item['judul'] ?? '-') ?></div>
                <div style="font-size:12px;color:var(--gray-500)"><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?> &middot; <?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span>
                <a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">→</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="inbox" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada pengaduan</h3>
        <p>Pengaduan dari siswa akan muncul di sini.</p>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
