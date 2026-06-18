const SIDEBAR_STORAGE_KEY = 'sidebar-collapsed';

let isCollapsed = localStorage.getItem(SIDEBAR_STORAGE_KEY) === 'true';

$(document).ready(function() {
    initSidebarMenu();
    initHeaderDropdowns();
    initThemeToggle();
    initNavbarSearch();
    applySidebarState(false);

    $('#sidebar-toggle').on('click', function() {
        isCollapsed = !isCollapsed;
        localStorage.setItem(SIDEBAR_STORAGE_KEY, isCollapsed);
        applySidebarState(true);
    });
});

// ====================== THEME TOGGLE ======================
function initThemeToggle() {
    if (window.MyFanelTheme) {
        window.MyFanelTheme.applyTheme(window.MyFanelTheme.getTheme());
    }

    $('#theme-toggle').off('click').on('click', function(e) {
        e.stopImmediatePropagation();
        window.MyFanelTheme?.toggleTheme();
    });
}

// ====================== SIDEBAR COLLAPSE ======================
function applySidebarState(animate) {
    const sidebar = $('#sidebar');
    const toggleIcon = $('#sidebar-toggle i');

    if (isCollapsed) {
        sidebar.addClass('is-collapsed');
        $('.menu-text').addClass('hidden');
        $('#logo-text').addClass('hidden');
        toggleIcon.removeClass('fa-bars').addClass('fa-chevron-right');
    } else {
        sidebar.removeClass('is-collapsed');
        $('.menu-text').removeClass('hidden');
        $('#logo-text').removeClass('hidden');
        toggleIcon.removeClass('fa-chevron-right').addClass('fa-bars');
    }

    if (!animate) {
        sidebar.css('transition', 'none');
        requestAnimationFrame(function() {
            sidebar.css('transition', '');
        });
    }
}

// ====================== FUNGSI MENU SIDEBAR ======================
function initSidebarMenu() {
    $('.admin-menu-item').off('click').on('click', function() {
        const $this = $(this);

        $('.admin-menu-item').removeClass('active');
        $this.addClass('active');
    });
}

// ====================== DROPDOWN HEADER ======================
function initHeaderDropdowns() {
    $('#notification-btn').off('click').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#notification-dropdown').toggleClass('hidden');
        $('#user-dropdown').addClass('hidden');
    });

    $('#user-menu-btn').off('click').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#user-dropdown').toggleClass('hidden');
        $('#notification-dropdown').addClass('hidden');
    });

    $(document).off('click').on('click', function(e) {
        if (!$(e.target).closest('#notification-btn, #notification-dropdown').length) {
            $('#notification-dropdown').addClass('hidden');
        }
        if (!$(e.target).closest('#user-menu-btn, #user-dropdown').length) {
            $('#user-dropdown').addClass('hidden');
        }
        if (!$(e.target).closest('#navbar-search-wrap').length) {
            $('#navbar-search-dropdown').addClass('hidden');
        }
    });
}
