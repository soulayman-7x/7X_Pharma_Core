// ===== LIVE CLOCK =====
function updateClock() {
    const el = document.getElementById('navbar-clock');
    if (el) {
        el.textContent = new Date().toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
}
setInterval(updateClock, 1000);
updateClock();

// ===== MOBILE SIDEBAR TOGGLE =====
const sidebarToggle = document.getElementById('btn-sidebar-toggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');

if (sidebarToggle && sidebar && overlay) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });
}