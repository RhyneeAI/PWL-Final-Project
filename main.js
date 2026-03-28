let isCollapsed = false;
$(document).ready(function() {

    // Load Sidebar
    $('#sidebar-container').load('components/sidebar.html', function() {
        initSidebarMenu();
    });

    // Load Header
    $('#header-container').load('components/header.html', function() {
        initHeaderDropdowns();

        // Event tombol burger
        $('#sidebar-toggle').on('click', function() {
            isCollapsed = !isCollapsed;
            toggleSidebar();
        });
    });

    // Load Footer
    $('#footer-container').load('components/footer.html');

    // Load Default content
    loadContent('dashboard');

});

// ====================== FUNGSI COLLAPSE SIDEBAR ======================
function toggleSidebar() {
    const sidebar = $('#sidebar');

    if (isCollapsed) {
        // === COLLAPSED MODE ===
        sidebar.addClass('lg:w-20');
        $('.menu-text').addClass('hidden');
        $('#logo-text').addClass('hidden');
        $('#sidebar-logo-text').addClass('hidden');
        $('#sidebar-version').addClass('hidden');

        $('#sidebar-toggle i').removeClass('fa-bars').addClass('fa-chevron-right');
    } else {
        // === NORMAL MODE ===
        sidebar.removeClass('lg:w-20');
        $('.menu-text').removeClass('hidden');
        $('#logo-text').removeClass('hidden');
        $('#sidebar-logo-text').removeClass('hidden');
        $('#sidebar-version').removeClass('hidden');

        $('#sidebar-toggle i').removeClass('fa-chevron-right').addClass('fa-bars');
    }
}

// ====================== FUNGSI MENU SIDEBAR ======================
function initSidebarMenu() {
    $('.admin-menu-item').off('click').on('click', function(e) {
        e.preventDefault();

        const page = $(this).data('page');
        if (!page) return;

        $('.admin-menu-item').removeClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');

        $(this).addClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');

        loadContent(page);
    });
}

// ====================== LOAD CONTENT ======================
function loadContent(page) {
    $('#content-wrapper').load(`components/content-${page}.html`, function(response, status) {
        if (status === "error") {
            $('#content-wrapper').html(`
                <div class="p-10 text-center text-gray-400">
                    <p>Halaman <strong>${page}</strong> belum dibuat.</p>
                </div>
            `);
        }
    });

    setTimeout(() => {
        $('.admin-menu-item').removeClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
        $(`.admin-menu-item[data-page="${page}"]`).addClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
    }, 100);
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