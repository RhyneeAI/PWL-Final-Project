$(document).ready(function() {
    // Load semua component
    $('#sidebar-container').load('components/sidebar.html');
    $('#footer-container').load('components/footer.html');
    $('#content-wrapper').load('components/content-dashboard.html');

    // Load other content
    $('.menu-item').on('click', function() {
      const page = $(this).data('page');
      $('#content-container').load(`components/content-${page}.html`);
    });

    $('#header-container').load('components/header.html', function() {
        initDropdowns();
    });
});

function initDropdowns() {
    // Notification Dropdown
    $('#notification-btn').off('click').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#notification-dropdown').toggleClass('hidden');
        $('#user-dropdown').addClass('hidden');
    });

    // User Dropdown
    $('#user-menu-btn').off('click').on('click', function(e) {
        e.stopImmediatePropagation();
        $('#user-dropdown').toggleClass('hidden');
        $('#notification-dropdown').addClass('hidden');
    });

    // Tutup dropdown saat klik di luar
    $(document).off('click').on('click', function(e) {
        if (!$(e.target).closest('#notification-btn, #notification-dropdown').length) {
            $('#notification-dropdown').addClass('hidden');
        }
        if (!$(e.target).closest('#user-menu-btn, #user-dropdown').length) {
            $('#user-dropdown').addClass('hidden');
        }
    });
}