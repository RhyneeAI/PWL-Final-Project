(function () {
    const STORAGE_KEY = 'theme';
    const DEFAULT_THEME = 'dark';

    function getTheme() {
        const saved = localStorage.getItem(STORAGE_KEY);
        return saved === 'light' ? 'light' : DEFAULT_THEME;
    }

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
        document.body?.classList.toggle('dark', isDark);

        const icon = document.getElementById('theme-toggle-icon');
        if (icon) {
            icon.classList.toggle('fa-sun', isDark);
            icon.classList.toggle('fa-moon', !isDark);
        }
    }

    function toggleTheme() {
        const next = getTheme() === 'dark' ? 'light' : 'dark';
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
    }

    // Terapkan sebelum render agar tidak flash
    applyTheme(getTheme());

    window.MyFanelTheme = { getTheme, applyTheme, toggleTheme };
})();
