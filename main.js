$(document).ready(function() {
    // Load Sidebar dengan callback
    $('#sidebar-container').load('components/sidebar.html', function() {
        initSidebarMenu();
    });

    // Load Header
    $('#header-container').load('components/header.html', function() {
        initDropdowns();
    });

    // Load Footer
    $('#footer-container').load('components/footer.html');

    // Load Default content
    loadContent('dashboard');
});

// ====================== FUNGSI MENU SIDEBAR ======================
function initSidebarMenu() {
    $('.admin-menu-item').off('click').on('click', function(e) {
        e.preventDefault();

        const page = $(this).data('page');
        if (!page) return;

        // Hapus active dari semua menu
        $('.admin-menu-item').removeClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');

        // Tambahkan class active ke menu yang diklik
        $(this).addClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');

        // Load konten
        loadContent(page);
    });
}

// Load Content 
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

    // Set active menu 
    setTimeout(() => {
        $('.admin-menu-item').removeClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
        $(`.admin-menu-item[data-page="${page}"]`).addClass('active bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-400');
    }, 100);
}

// ====================== FUNGSI DROPDOWN HEADER ======================
function initDropdowns() {
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