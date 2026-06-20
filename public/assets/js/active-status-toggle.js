(function (window, $) {
    'use strict';

    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content') || '';
    }

    function updateFilterLabel($cell, isActive) {
        const label = isActive ? 'Aktif' : 'Nonaktif';
        $cell.find('.status-filter-value').text(label);
        $cell.find('.active-toggle .sr-only').text(label);
        $cell.attr('data-order', isActive ? '1' : '0');
    }

    function initActiveStatusToggles() {
        $(document).on('change', '.active-toggle-input', function () {
            const $input = $(this);
            const $cell = $input.closest('td');
            const url = $input.data('url');
            const isActive = $input.is(':checked');
            const previousState = !isActive;

            $input.prop('disabled', true);

            $.ajax({
                url,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    Accept: 'application/json',
                },
                data: {
                    is_active: isActive ? 1 : 0,
                },
            })
                .done(function () {
                    updateFilterLabel($cell, isActive);
                    $input.closest('.active-toggle').attr(
                        'title',
                        isActive ? 'Nonaktifkan' : 'Aktifkan'
                    );
                })
                .fail(function (xhr) {
                    $input.prop('checked', previousState);

                    const message = xhr.responseJSON?.message
                        || 'Gagal memperbarui status. Silakan coba lagi.';

                    window.alert(message);
                })
                .always(function () {
                    $input.prop('disabled', false);
                });
        });
    }

    $(initActiveStatusToggles);
})(window, jQuery);
