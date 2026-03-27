$(document).ready(function() {

    // Load semua component
    $('#header-container').load('components/header.html');
    $('#sidebar-container').load('components/sidebar.html');
    $('#footer-container').load('components/footer.html');
    
    // Load content default (dashboard)
    $('#content-wrapper').load('components/content.html');

    // Contoh: Load content berbeda saat menu diklik
    // $('.menu-link').on('click', function() {
    //   const page = $(this).data('page');
    //   $('#content-container').load(`components/content-${page}.html`);
    // });
});