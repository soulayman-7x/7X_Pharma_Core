// Live clock
(function () {
    const el = document.getElementById('pos-clock');
    function tick() {
        if (el) el.textContent = new Date().toLocaleTimeString('en-GB');
    }
    setInterval(tick, 1000);
    tick();
})();
