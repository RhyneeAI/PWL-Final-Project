(function (window, $) {
    'use strict';

    let rowIndex = 0;

    function getBranchId() {
        return $('#stock-out-branch-id').val() || window.stockOutInitialBranchId;
    }

    function getBranchProducts() {
        return window.stockOutCatalog?.[getBranchId()]?.products || [];
    }

    function getSelectedProductIds($excludeSelect) {
        const ids = [];

        $('#stock-out-items-body .stock-out-product-select').each(function () {
            if ($excludeSelect && this === $excludeSelect[0]) {
                return;
            }

            const value = $(this).val();

            if (value) {
                ids.push(String(value));
            }
        });

        return ids;
    }

    function buildProductOptions(products, selectedId, excludedIds) {
        let html = '<option value="">— Pilih Produk —</option>';

        products.forEach(function (product) {
            const id = String(product.id);
            const isSelected = id === String(selectedId);
            const isTaken = excludedIds.includes(id) && !isSelected;

            if (isTaken) {
                return;
            }

            html += '<option value="' + product.id + '"'
                + ' data-unit="' + product.unit + '"'
                + ' data-stock="' + product.stock + '"'
                + (isSelected ? ' selected' : '') + '>'
                + product.code + ' — ' + product.name + ' (Stok: ' + Number(product.stock).toLocaleString('id-ID') + ')'
                + '</option>';
        });

        return html;
    }

    function refreshAllProductSelects() {
        const products = getBranchProducts();

        $('#stock-out-items-body .stock-out-product-select').each(function () {
            const $select = $(this);
            const selected = $select.val();
            const excludedIds = getSelectedProductIds($select);

            $select.html(buildProductOptions(products, selected, excludedIds));
        });

        updateAddItemButtonState();
    }

    function updateAddItemButtonState() {
        const products = getBranchProducts();
        const selectedCount = getSelectedProductIds().length;
        const $button = $('#stock-out-add-item');

        if (!$button.length) {
            return;
        }

        const allSelected = products.length > 0 && selectedCount >= products.length;
        $button.prop('disabled', allSelected).toggleClass('opacity-50 cursor-not-allowed', allSelected);
    }

    function applyBranchCatalog(branchId) {
        const catalog = window.stockOutCatalog?.[branchId];

        if (!catalog) {
            return;
        }

        $('#stock-out-code-preview').val(catalog.next_code);
        refreshAllProductSelects();
    }

    function reindexRows() {
        $('#stock-out-items-body .stock-out-item-row').each(function (index) {
            $(this).find('[name^="items["]').each(function () {
                const name = $(this).attr('name');

                if (!name) {
                    return;
                }

                $(this).attr('name', name.replace(/items\[\d+]/, 'items[' + index + ']'));
            });
        });

        rowIndex = $('#stock-out-items-body .stock-out-item-row').length;
    }

    function appendRowFromTemplate() {
        const template = document.getElementById('stock-out-item-template');

        if (!template) {
            return $();
        }

        const html = template.innerHTML.replace(/__INDEX__/g, String(rowIndex));
        const tbody = document.getElementById('stock-out-items-body');

        tbody.insertAdjacentHTML('beforeend', html);

        const $row = $('#stock-out-items-body .stock-out-item-row').last();

        if (window.initFormattedInputs) {
            window.initFormattedInputs($row);
        }

        refreshAllProductSelects();
        rowIndex += 1;

        return $row;
    }

    function addRow() {
        const products = getBranchProducts();
        const selectedCount = getSelectedProductIds().length;

        if (products.length > 0 && selectedCount >= products.length) {
            window.alert('Semua produk cabang ini sudah ditambahkan.');
            return;
        }

        appendRowFromTemplate();
    }

    function removeRow($button) {
        const $rows = $('#stock-out-items-body .stock-out-item-row');

        if ($rows.length <= 1) {
            window.alert('Minimal satu baris produk harus ada.');
            return;
        }

        $button.closest('.stock-out-item-row').remove();
        reindexRows();
        refreshAllProductSelects();
    }

    function initStockOutForm() {
        rowIndex = $('#stock-out-items-body .stock-out-item-row').length;

        $('#stock-out-branch-id').on('change', function () {
            applyBranchCatalog($(this).val());
        });

        $('#stock-out-add-item').on('click', function (event) {
            event.preventDefault();
            addRow();
        });

        $('#stock-out-items-body').on('click', '.stock-out-remove-item', function (event) {
            event.preventDefault();
            event.stopPropagation();
            removeRow($(this));
        });

        $('#stock-out-items-body').on('change', '.stock-out-product-select', function () {
            refreshAllProductSelects();
        });

        if ($('#stock-out-branch-id').length) {
            applyBranchCatalog($('#stock-out-branch-id').val());
        } else {
            refreshAllProductSelects();
        }
    }

    $(initStockOutForm);
})(window, jQuery);
