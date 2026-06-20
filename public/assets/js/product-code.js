(function (window, $) {
    'use strict';

    function initProductCodePreview() {
        const $branchSelect = $('#product-branch-id');
        const $codePreview = $('#product-code-preview');

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

    $(initProductCodePreview);
})(window, jQuery);
