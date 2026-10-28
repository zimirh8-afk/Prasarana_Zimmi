<?php
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? '';
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';

function isActive($path) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
    $check = $basePath . '/' . ltrim($path, '/');
    $check = rtrim($check, '/');
    return ($uri === $check || strpos($uri, $check . '/') === 0) ? 'active' : '';
}
?>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:52px;object-fit:contain;padding:2px 0">
        <button class="sidebar-close" id="sidebarClose" onclick="closeSidebar()">✕</button>
    </div>

    <?php if ($user): ?>
    <div class="sidebar-user">
        <?php
        $fotoSidebar = $user['foto'] ?? ($_SESSION['user']['foto'] ?? null);
        $nisDisplay  = '';
        if ($role === 'siswa') {
            $siswaModel  = new Siswa();
            $siswaData   = $siswaModel->findByUserId($user['id']);
            $nisDisplay  = $siswaData['nis'] ?? '';
        }
        ?>
        <div class="sidebar-avatar" style="<?= $fotoSidebar ? 'padding:0;overflow:hidden' : '' ?>">
            <?php if ($fotoSidebar): ?>
                <img src="<?= url('public/uploads/profil/' . htmlspecialchars($fotoSidebar)) ?>" style="width:100%;height:100%;object-fit:cover">
            <?php else: ?>
                <?= strtoupper(substr($user['nama'], 0, 1)) ?>
            <?php endif; ?>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= htmlspecialchars($user['nama']) ?></div>
            <?php if ($nisDisplay): ?>
                <div class="sidebar-user-role">NIS: <?= htmlspecialchars($nisDisplay) ?></div>
            <?php else: ?>
                <div class="sidebar-user-role"><?= ucfirst($user['role']) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <nav class="sidebar-nav">

        <?php if ($role === 'admin'): ?>
        <?php
        $userModel = new User();
        $pendingCount = $userModel->countPending();
        ?>

            <div class="sidebar-nav-label">Menu</div>

            <a href="<?= url('admin/dashboard') ?>" class="<?= isActive('admin/dashboard') ?>">
                <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
            </a>
            <a href="<?= url('admin/pengaduan') ?>" class="<?= isActive('admin/pengaduan') ?>">
                <i data-lucide="clipboard-list" class="nav-icon"></i> Pengaduan
            </a>
            <a href="<?= url('admin/verifikasi') ?>" class="<?= isActive('admin/verifikasi') ?>" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:10px">
                    <i data-lucide="user-check" class="nav-icon"></i> Verifikasi Akun
                </span>
                <?php if ($pendingCount > 0): ?>
                    <span style="background:var(--danger);color:white;border-radius:20px;padding:1px 7px;font-size:11px;font-weight:700"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= url('admin/feedback') ?>" class="<?= isActive('admin/feedback') ?>">
                <i data-lucide="message-square" class="nav-icon"></i> Feedback
            </a>
            <a href="<?= url('admin/histori') ?>" class="<?= isActive('admin/histori') ?>">
                <i data-lucide="history" class="nav-icon"></i> Histori
            </a>

            <div class="sidebar-nav-label">Akun</div>

            <a href="<?= url('admin/profile') ?>" class="<?= isActive('admin/profile') ?>">
                <i data-lucide="user" class="nav-icon"></i> Profil
            </a>
            <a href="<?= url('logout') ?>" style="color:var(--danger)">
                <i data-lucide="log-out" class="nav-icon"></i> Logout
            </a>

        <?php else: ?>

            <div class="sidebar-nav-label">Menu</div>

            <a href="<?= url('siswa/dashboard') ?>" class="<?= isActive('siswa/dashboard') ?>">
                <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
            </a>
            <a href="<?= url('siswa/pengaduan') ?>" class="<?= isActive('siswa/pengaduan') && !isActive('siswa/pengaduan/create') && !isActive('siswa/pengaduan/edit') && !isActive('siswa/pengaduan/detail') ? 'active' : '' ?>">
                <i data-lucide="clipboard-list" class="nav-icon"></i> Pengaduan Saya
            </a>
            <a href="<?= url('siswa/pengaduan/create') ?>" class="<?= isActive('siswa/pengaduan/create') ?>">
                <i data-lucide="plus-circle" class="nav-icon"></i> Buat Pengaduan
            </a>
            <a href="<?= url('siswa/feedback') ?>" class="<?= isActive('siswa/feedback') ?>">
                <i data-lucide="message-square" class="nav-icon"></i> Feedback
            </a>
            <a href="<?= url('siswa/histori') ?>" class="<?= isActive('siswa/histori') ?>">
                <i data-lucide="history" class="nav-icon"></i> Histori
            </a>

            <div class="sidebar-nav-label">Akun</div>

            <a href="<?= url('siswa/profile') ?>" class="<?= isActive('siswa/profile') ?>">
                <i data-lucide="user" class="nav-icon"></i> Profil
            </a>
            <a href="<?= url('logout') ?>" style="color:var(--danger)">
                <i data-lucide="log-out" class="nav-icon"></i> Logout
            </a>

        <?php endif; ?>

    </nav>

    <div class="sidebar-footer" style="margin-top:auto;border-top:1px solid var(--gray-100);position:sticky;bottom:0;background:white;z-index:1">
        <a href="<?= url('logout') ?>">
            <i data-lucide="log-out" class="nav-icon"></i> Logout
        </a>
    </div>

</aside>

<div class="main-content">
    <!-- Mobile topbar -->
    <div class="mobile-topbar">
        <button onclick="openSidebar()" class="hamburger-btn">
            <span></span><span></span><span></span>
        </button>
        <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:36px;object-fit:contain">
        <div style="width:40px"></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div class="page-body">
