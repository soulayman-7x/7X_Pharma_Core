// Live clock (PHP cannot update live time without reloading)
(function () {
    const el = document.getElementById('pos-clock');
    function tick() {
        if (el) el.textContent = new Date().toLocaleTimeString('en-GB');
    }
    setInterval(tick, 1000);
    tick();
})();

// F3 shortcut to focus search
document.addEventListener('keydown', function (e) {
    const input = document.getElementById('medicine-search');
    if ((e.key === 'F3' || e.key === '/') && document.activeElement !== input) {
        e.preventDefault();
        if (input) { 
            input.focus(); 
            input.select(); 
        }
    }
});