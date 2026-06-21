(function () {
    const localeId = {
        days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
        months: [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        today: 'Hari ini',
        clear: 'Hapus',
        dateFormat: 'dd/MM/yyyy',
        firstDay: 1,
    };

    function ymdToDate(ymd) {
        if (!ymd || !/^\d{4}-\d{2}-\d{2}$/.test(ymd)) {
            return undefined;
        }

        const [year, month, day] = ymd.split('-').map(Number);

        return new Date(year, month - 1, day);
    }

    function dateToYmd(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function initDatePickers(root) {
        if (typeof AirDatepicker === 'undefined') {
            return;
        }

        const scope = root || document;

        scope.querySelectorAll('[data-date-picker]:not([data-date-picker-init])').forEach(function (group) {
            group.setAttribute('data-date-picker-init', '1');

            const display = group.querySelector('.date-picker-display');
            const hidden = group.querySelector('[data-date-hidden]');

            if (!display || !hidden) {
                return;
            }

            const initialDate = ymdToDate(hidden.value);

            new AirDatepicker(display, {
                locale: localeId,
                dateFormat: 'dd/MM/yyyy',
                autoClose: true,
                selectedDates: initialDate ? [initialDate] : [],
                onSelect({ date }) {
                    hidden.value = date ? dateToYmd(date) : '';
                    display.dispatchEvent(new Event('input', { bubbles: true }));
                },
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initDatePickers();
        });
    } else {
        initDatePickers();
    }

    window.initDatePickers = initDatePickers;
})();
