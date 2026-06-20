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

    function applyColumnFilters(table, filterState) {
        table.columns().search('');

        Object.keys(filterState).forEach(function (column) {
            const value = filterState[column];

            if (value) {
                const escaped = $.fn.dataTable.util.escapeRegex(value);
                table.column(Number(column)).search('^' + escaped + '$', true, false);
            }
        });

        table.draw();
    }

    function initMasterDataTable(tableSelector, options = {}) {
        const {
            searchInput,
            filterButtons,
            branchFilter,
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

        const filterState = {};

        if (searchInput) {
            $(searchInput).on('keyup search', function () {
                table.search(this.value).draw();
            });
        }

        if (filterButtons) {
            bindFilterButtons(table, filterButtons, filterState);
        }

        if (branchFilter) {
            bindBranchFilter(table, branchFilter, filterState);
        }

        return table;
    }

    function bindBranchFilter(table, branchFilter, filterState) {
        const { select, column } = branchFilter;
        const columnKey = String(column);

        $(select).on('change', function () {
            const value = $(this).val();

            if (value) {
                filterState[columnKey] = value;
            } else {
                delete filterState[columnKey];
            }

            applyColumnFilters(table, filterState);
        });
    }

    function bindFilterButtons(table, buttonsSelector, filterState) {
        const $buttons = $(buttonsSelector);

        $buttons.on('click', function () {
            const $btn = $(this);
            const column = String($btn.data('filterColumn'));
            const value = String($btn.data('filterValue') ?? '');

            $buttons.removeClass('is-active');
            $btn.addClass('is-active');

            if (column === '' || column === undefined) {
                Object.keys(filterState).forEach(function (key) {
                    delete filterState[key];
                });
            } else if (value === '') {
                delete filterState[column];
            } else {
                filterState[column] = value;
            }

            applyColumnFilters(table, filterState);
        });
    }

    window.initMasterDataTable = initMasterDataTable;
})(window, jQuery);
