

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
        // Simple SVG switch based on theme
        if (theme === 'light') {
            // Moon icon for light mode (to switch to dark)
            themeIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`;
        } else {
            // Sun icon for dark mode (to switch to light)
            themeIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`;
        }
    }
});
