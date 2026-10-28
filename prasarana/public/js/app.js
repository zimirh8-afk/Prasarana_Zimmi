document.addEventListener('DOMContentLoaded', function () {

    // Auto-dismiss alerts after 4 seconds
    setTimeout(function () {
        document.querySelectorAll('.alert').forEach(function (alert) {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(function () { alert.remove(); }, 400);
        });
    }, 4000);

    // Auto-assign status badge classes
    document.querySelectorAll('.status-badge').forEach(function (badge) {
        const text = badge.textContent.trim();
        const map = {
            'Diajukan':        'badge-diajukan',
            'Diproses':        'badge-diproses',
            'Dalam Perbaikan': 'badge-perbaikan',
            'Selesai':         'badge-selesai',
            'Ditolak':         'badge-ditolak',
        };
        if (map[text]) badge.classList.add(map[text]);
    });

    // Highlight active sidebar link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-nav a').forEach(function (link) {
        if (currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

});

function confirmDelete(message) {
    return confirm(message || 'Apakah kamu yakin ingin menghapus data ini?');
}

function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

// Lightbox
function openLightbox(src) {
    var lb = document.getElementById('lightbox');
    if (!lb) {
        lb = document.createElement('div');
        lb.id = 'lightbox';
        lb.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:zoom-out';
        lb.innerHTML = '<button onclick="closeLightbox()" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,0.15);border:none;color:white;width:40px;height:40px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:1">✕</button><img id="lb-img" style="max-width:90vw;max-height:90vh;border-radius:8px;object-fit:contain">';
        lb.addEventListener('click', function(e){ if(e.target===lb) closeLightbox(); });
        document.body.appendChild(lb);
    }
    document.getElementById('lb-img').src = src;
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    var lb = document.getElementById('lightbox');
    if (lb) lb.style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
