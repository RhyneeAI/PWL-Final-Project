(function (window, $) {
    'use strict';

    function digitsOnly(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function formatThousands(value) {
        const digits = digitsOnly(value);

        if (!digits) {
            return '';
        }

        return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function syncHidden($group) {
        const digits = digitsOnly($group.find('[data-formatted-display]').val());
        $group.find('[data-formatted-hidden]').val(digits === '' ? '0' : digits);
    }

    function initFormattedInputGroup($group) {
        const $display = $group.find('[data-formatted-display]');

        $display.on('input', function () {
            const cursorFromEnd = this.value.length - this.selectionStart;
            const formatted = formatThousands(this.value);
            this.value = formatted;
            const nextPos = Math.max(0, formatted.length - cursorFromEnd);
            this.setSelectionRange(nextPos, nextPos);
            syncHidden($group);
        });

        $display.on('blur', function () {
            syncHidden($group);
        });

        $group.closest('form').on('submit', function () {
            syncHidden($group);
        });
    }

    function initFormattedInputs() {
        $('[data-formatted-input-group]').each(function () {
            initFormattedInputGroup($(this));
        });
    }

    window.initFormattedInputs = initFormattedInputs;

    $(initFormattedInputs);
})(window, jQuery);
