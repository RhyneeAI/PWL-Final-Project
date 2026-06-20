(function (window, $) {
    'use strict';

    function digitsOnly(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function normalizeNationalDigits(value) {
        let digits = digitsOnly(value);

        if (digits.startsWith('62')) {
            digits = digits.slice(2);
        } else if (digits.startsWith('0')) {
            digits = digits.slice(1);
        }

        return digits;
    }

    function groupDigits(digits) {
        if (digits.length === 10 && digits.startsWith('8')) {
            return digits.slice(0, 3) + '-' + digits.slice(3, 7) + '-' + digits.slice(7);
        }

        const parts = [];

        while (digits.length > 4) {
            parts.push(digits.slice(0, 4));
            digits = digits.slice(4);
        }

        if (digits.length) {
            parts.push(digits);
        }

        return parts.join('-');
    }

    function formatNationalDisplay(value) {
        const digits = normalizeNationalDigits(value);
        return digits ? groupDigits(digits) : '';
    }

    function formatStoredPhone(value) {
        const digits = normalizeNationalDigits(value);
        return digits ? '+62 ' + groupDigits(digits) : '';
    }

    function syncHidden($group) {
        const display = $group.find('[data-phone-national]').val();
        const stored = formatStoredPhone(display);
        $group.find('[data-phone-hidden]').val(stored);
    }

    function initPhoneInputGroup($group) {
        const $national = $group.find('[data-phone-national]');
        const stored = $group.find('[data-phone-hidden]').val();

        if (stored) {
            $national.val(formatNationalDisplay(stored));
        }

        syncHidden($group);

        $national.on('input', function () {
            const cursorFromEnd = this.value.length - this.selectionStart;
            const formatted = formatNationalDisplay(this.value);
            this.value = formatted;
            const nextPos = Math.max(0, formatted.length - cursorFromEnd);
            this.setSelectionRange(nextPos, nextPos);
            syncHidden($group);
        });

        $national.on('blur', function () {
            syncHidden($group);
        });

        $group.closest('form').on('submit', function () {
            syncHidden($group);
        });
    }

    function initPhoneInputs() {
        $('[data-phone-input-group]').each(function () {
            initPhoneInputGroup($(this));
        });
    }

    window.initPhoneInputs = initPhoneInputs;

    $(initPhoneInputs);
})(window, jQuery);
