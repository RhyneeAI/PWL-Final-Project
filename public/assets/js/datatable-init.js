(function (window, $) {
    'use strict';

    const DEFAULT_LANGUAGE = {
        emptyTable: 'Tidak ada data tersedia',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 data',
        infoFiltered: '(difilter dari _MAX_ total data)',
        lengthMenu: 'Tampilkan _MENU_ data',
        loadingRecords: 'Memuat...',
        processing: 'Memproses...',
        search: 'Cari:',
        zeroRecords: 'Data tidak ditemukan',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: '<i class="fas fa-chevron-right"></i>',
            previous: '<i class="fas fa-chevron-left"></i>',
        },
    };

    function initMasterDataTable(tableSelector, options = {}) {
        const {
            searchInput,
            filterButtons,
            order = [[0, 'asc']],
            columnDefs = [],
            pageLength = 10,
        } = options;

        const $table = $(tableSelector);
        if (!$table.length || $.fn.DataTable.isDataTable($table)) {
            return $table.DataTable ? $table.DataTable() : null;
        }

        const hasActionColumn = $table.find('thead th').last().text().trim().toLowerCase() === 'aksi';
        const defaultColumnDefs = hasActionColumn
            ? [{ orderable: false, searchable: false, targets: -1 }]
            : [];

        const table = $table.DataTable({
            dom: 'rt<"dt-footer"ip>',
            pageLength,
            lengthChange: false,
            autoWidth: false,
            responsive: true,
            order,
            language: DEFAULT_LANGUAGE,
            columnDefs: [...defaultColumnDefs, ...columnDefs],
        });

        if (searchInput) {
            $(searchInput).on('keyup search', function () {
                table.search(this.value).draw();
            });
        }

        if (filterButtons) {
            bindFilterButtons(table, filterButtons);
        }

        return table;
    }

    function bindFilterButtons(table, buttonsSelector) {
        const $buttons = $(buttonsSelector);

        $buttons.on('click', function () {
            const $btn = $(this);
            const column = $btn.data('filterColumn');
            const value = String($btn.data('filterValue') ?? '');

            $buttons.removeClass('is-active');
            $btn.addClass('is-active');

            if (value === '' || column === '' || column === undefined) {
                table.columns().search('').draw();
                return;
            }

            table.columns().search('');
            table.column(column).search(value, true, false).draw();
        });
    }

    window.initMasterDataTable = initMasterDataTable;
})(window, jQuery);
