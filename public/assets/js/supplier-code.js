(function (window, $) {
    'use strict';

    function initSupplierCodePreview() {
        const $branchSelect = $('#supplier-branch-id');
        const $codePreview = $('#supplier-code-preview');

        if (!$branchSelect.length || !$codePreview.length) {
            return;
        }

        $branchSelect.on('change', function () {
            const nextCode = $(this).find(':selected').data('nextCode');

            if (nextCode) {
                $codePreview.val(nextCode);
            }
        });
    }

    $(initSupplierCodePreview);
})(window, jQuery);
