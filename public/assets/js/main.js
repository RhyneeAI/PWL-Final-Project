let isCollapsed = false;
$(document).ready(function() {
    // Load Sidebar
    initSidebarMenu();

    // Load Header
    initHeaderDropdowns();

    // Event tombol burger
    $('#sidebar-toggle').on('click', function() {
        isCollapsed = !isCollapsed;
        toggleSidebar();
    });

});

// ====================== FUNGSI COLLAPSE SIDEBAR ======================
function toggleSidebar() {
    const sidebar = $('#sidebar');

    if (isCollapsed) {
        // === COLLAPSED MODE ===
        sidebar.addClass('w-16').removeClass('w-64');
        $('.menu-text').addClass('hidden');
        $('#logo-text').addClass('hidden');
        $('#sidebar-logo-text').addClass('hidden');
        $('#sidebar-version').addClass('hidden');

        $('#sidebar-toggle i').removeClass('fa-bars').addClass('fa-chevron-right');
    } else {
        // === NORMAL MODE ===
        sidebar.removeClass('w-16').addClass('w-64');
        $('.menu-text').removeClass('hidden');
        $('#logo-text').removeClass('hidden');
        $('#sidebar-logo-text').removeClass('hidden');
        $('#sidebar-version').removeClass('hidden');

        $('#sidebar-toggle i').removeClass('fa-chevron-right').addClass('fa-bars');
    }
}

// ====================== FUNGSI MENU SIDEBAR ======================
function initSidebarMenu() {
    $('.admin-menu-item').off('click').on('click', function() {
        const $this = $(this);
        
        // Update active state
        $('.admin-menu-item').removeClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
        $this.addClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
        
        // Let href do the navigation
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
    });
}