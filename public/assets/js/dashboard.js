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

// ===== SALES CHART =====
(function initChart() {
    const ctx = document.getElementById('sales-chart');
    if (!ctx) return;

    const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const salesData = [3200, 4100, 2800, 5200, 3900, 4832, 1200];

    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const labelColor = isDark ? '#9CA3AF' : '#4B5563';

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Sales (DH)',
                data: salesData,
                backgroundColor: 'rgba(6, 182, 212, 0.25)', 
                borderColor: '#06b6d4', 
                borderWidth: 2,
                borderRadius: 6,
                hoverBackgroundColor: 'rgba(6, 182, 212, 0.45)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y.toLocaleString() + ' DH'
                    }
                }
            },
            scales: {
                x: { 
                    grid: { color: gridColor }, 
                    ticks: { color: labelColor } 
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: labelColor,
                        callback: val => val.toLocaleString() + ' DH'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            setTimeout(() => {
                const nowDark = document.documentElement.getAttribute('data-theme') !== 'light';
                const gc = nowDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                const lc = nowDark ? '#9CA3AF' : '#4B5563';
                
                chart.options.scales.x.grid.color = gc;
                chart.options.scales.x.ticks.color = lc;
                chart.options.scales.y.grid.color = gc;
                chart.options.scales.y.ticks.color = lc;
                chart.update();
            }, 50); 
        });
    }
})();