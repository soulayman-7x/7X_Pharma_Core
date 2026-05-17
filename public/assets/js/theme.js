document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');

    // Check for saved theme preference, otherwise default to dark
    const currentTheme = localStorage.getItem('theme') || 'dark';

    // Apply the saved theme on load
    if (currentTheme === 'light') {
        htmlElement.setAttribute('data-theme', 'light');
        updateIcon('light');
    } else {
        htmlElement.setAttribute('data-theme', 'dark');
        updateIcon('dark');
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            let targetTheme = 'dark';

            if (htmlElement.getAttribute('data-theme') === 'dark') {
                targetTheme = 'light';
            }

            htmlElement.setAttribute('data-theme', targetTheme);
            localStorage.setItem('theme', targetTheme);
            updateIcon(targetTheme);
        });
    }

    function updateIcon(theme) {
        if (!themeIcon) return;

        // Font Awesome icons switch based on theme
        if (theme === 'light') {
            // Moon icon for light mode (to switch to dark)
            themeIcon.innerHTML = `<i class="fa-solid fa-moon"></i>`;
        } else {
            // Sun icon for dark mode (to switch to light)
            themeIcon.innerHTML = `<i class="fa-solid fa-sun"></i>`;
        }
    }
});