(function (window, $) {
    'use strict';

    let rowIndex = 0;

    function formatCurrency(value) {
        const number = Number(value) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function parseHiddenValue($row, fieldIndex) {
        return Number($row.find('[data-formatted-input-group]').eq(fieldIndex).find('[data-formatted-hidden]').val()) || 0;
    }

    function updateSubtotal($row) {
        const qty = parseHiddenValue($row, 0);
        const price = parseHiddenValue($row, 1);
        $row.find('.stock-in-subtotal').val(formatCurrency(qty * price));
    }

    function updateAllSubtotals() {
        $('#stock-in-items-body .stock-in-item-row').each(function () {
            updateSubtotal($(this));
        });
    }

    function getBranchId() {
        return $('#stock-in-branch-id').val() || window.stockInInitialBranchId;
    }

    function getBranchProducts() {
        return window.stockInCatalog?.[getBranchId()]?.products || [];
    }

    function getSelectedProductIds($excludeSelect) {
        const ids = [];

        $('#stock-in-items-body .stock-in-product-select').each(function () {
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

            html += '<option value="' + product.id + '" data-buy-price="' + product.buy_price + '" data-unit="' + product.unit + '"'
                + (isSelected ? ' selected' : '') + '>'
                + product.code + ' — ' + product.name + '</option>';
        });

        return html;
    }

    function refreshAllProductSelects() {
        const products = getBranchProducts();

        $('#stock-in-items-body .stock-in-product-select').each(function () {
            const $select = $(this);
            const selected = $select.val();
            const excludedIds = getSelectedProductIds($select);

            $select.html(buildProductOptions(products, selected, excludedIds));
        });

        updateAddItemButtonState();
    }

    function rebuildSupplierSelect(suppliers, selectedId) {
        const $select = $('#stock-in-supplier-id');
        let html = '<option value="">— Pilih Supplier —</option>';

        suppliers.forEach(function (supplier) {
            const selected = String(supplier.id) === String(selectedId) ? ' selected' : '';
            html += '<option value="' + supplier.id + '"' + selected + '>' + supplier.name + '</option>';
        });

        $select.html(html);
    }

    function updateAddItemButtonState() {
        const products = getBranchProducts();
        const selectedCount = getSelectedProductIds().length;
        const $button = $('#stock-in-add-item');

        if (!$button.length) {
            return;
        }

        const allSelected = products.length > 0 && selectedCount >= products.length;
        $button.prop('disabled', allSelected).toggleClass('opacity-50 cursor-not-allowed', allSelected);
    }

    function applyBranchCatalog(branchId) {
        const catalog = window.stockInCatalog?.[branchId];

        if (!catalog) {
            return;
        }

        rebuildSupplierSelect(catalog.suppliers, $('#stock-in-supplier-id').val());
        $('#stock-in-code-preview').val(catalog.next_code);
        refreshAllProductSelects();
        updateAllSubtotals();
    }

    function reindexRows() {
        $('#stock-in-items-body .stock-in-item-row').each(function (index) {
            $(this).find('[name^="items["]').each(function () {
                const name = $(this).attr('name');

                if (!name) {
                    return;
                }

                $(this).attr('name', name.replace(/items\[\d+]/, 'items[' + index + ']'));
            });
        });

        rowIndex = $('#stock-in-items-body .stock-in-item-row').length;
    }

    function appendRowFromTemplate() {
        const template = document.getElementById('stock-in-item-template');

        if (!template) {
            return $();
        }

        const html = template.innerHTML.replace(/__INDEX__/g, String(rowIndex));
        const tbody = document.getElementById('stock-in-items-body');

        tbody.insertAdjacentHTML('beforeend', html);

        const $row = $('#stock-in-items-body .stock-in-item-row').last();

        if (window.initFormattedInputs) {
            window.initFormattedInputs($row);
        }

        refreshAllProductSelects();
        updateSubtotal($row);
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
        const $rows = $('#stock-in-items-body .stock-in-item-row');

        if ($rows.length <= 1) {
            window.alert('Minimal satu baris produk harus ada.');
            return;
        }

        $button.closest('.stock-in-item-row').remove();
        reindexRows();
        refreshAllProductSelects();
        updateAllSubtotals();
    }

    function initStockInForm() {
        rowIndex = $('#stock-in-items-body .stock-in-item-row').length;

        $('#stock-in-branch-id').on('change', function () {
            applyBranchCatalog($(this).val());
        });

        $('#stock-in-add-item').on('click', function (event) {
            event.preventDefault();
            addRow();
        });

        $('#stock-in-items-body').on('click', '.stock-in-remove-item', function (event) {
            event.preventDefault();
            event.stopPropagation();
            removeRow($(this));
        });

        $('#stock-in-items-body').on('change', '.stock-in-product-select', function () {
            const $row = $(this).closest('.stock-in-item-row');
            const buyPrice = $(this).find(':selected').data('buyPrice') || 0;
            const $priceGroup = $row.find('[data-formatted-input-group]').eq(1);
            const formatted = Number(buyPrice) > 0
                ? Number(buyPrice).toLocaleString('id-ID')
                : '';

            $priceGroup.find('[data-formatted-display]').val(formatted);
            $priceGroup.find('[data-formatted-hidden]').val(Number(buyPrice) || 0);
            refreshAllProductSelects();
            updateSubtotal($row);
        });

        $('#stock-in-items-body').on('input', '[data-formatted-display]', function () {
            updateSubtotal($(this).closest('.stock-in-item-row'));
        });

        if ($('#stock-in-branch-id').length) {
            applyBranchCatalog($('#stock-in-branch-id').val());
        } else {
            refreshAllProductSelects();
        }

        updateAllSubtotals();
    }

    $(initStockInForm);
})(window, jQuery);
