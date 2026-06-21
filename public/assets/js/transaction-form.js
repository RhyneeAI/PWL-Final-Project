(function (window, $) {
    'use strict';

    let rowIndex = 0;

    function formatCurrency(value) {
        const number = Number(value) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function getRowQty($row) {
        return Number($row.find('.transaction-qty-input').val()) || 0;
    }

    function updateRowSubtotal($row) {
        const price = Number($row.find('.transaction-product-select :selected').data('sellPrice')) || 0;
        const qty = getRowQty($row);
        $row.find('.transaction-subtotal').val(formatCurrency(price * qty));
    }

    function updateRowSellPrice($row) {
        const price = Number($row.find('.transaction-product-select :selected').data('sellPrice')) || 0;
        $row.find('.transaction-sell-price').val(formatCurrency(price));
    }

    function getFormattedHiddenValue(groupIndex) {
        return Number($('#transaction-form').find('[data-formatted-input-group]').eq(groupIndex).find('[data-formatted-hidden]').val()) || 0;
    }

    function updateTotals() {
        let totalQty = 0;
        let totalSubtotal = 0;

        $('#transaction-items-body .transaction-item-row').each(function () {
            const $row = $(this);
            const qty = getRowQty($row);
            const price = Number($row.find('.transaction-product-select :selected').data('sellPrice')) || 0;
            totalQty += qty;
            totalSubtotal += price * qty;
        });

        $('#transaction-total-qty').text(totalQty.toLocaleString('id-ID'));

        const discount = getFormattedHiddenValue(0);
        const total = Math.max(0, totalSubtotal - discount);
        const paidAmount = getFormattedHiddenValue(1);
        const change = Math.max(0, paidAmount - total);

        $('#transaction-subtotal').val(formatCurrency(totalSubtotal));
        $('#transaction-total').val(formatCurrency(total));
        $('#transaction-change').val(formatCurrency(change));
    }

    function getBranchId() {
        return $('#transaction-branch-id').val() || window.transactionInitialBranchId;
    }

    function getBranchProducts() {
        return window.transactionCatalog?.[getBranchId()]?.products || [];
    }

    function getSelectedProductIds($excludeSelect) {
        const ids = [];

        $('#transaction-items-body .transaction-product-select').each(function () {
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
                + ' data-sell-price="' + product.sell_price + '"'
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

        $('#transaction-items-body .transaction-product-select').each(function () {
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
        const $button = $('#transaction-add-item');

        if (!$button.length) {
            return;
        }

        const allSelected = products.length > 0 && selectedCount >= products.length;
        $button.prop('disabled', allSelected).toggleClass('opacity-50 cursor-not-allowed', allSelected);
    }

    function applyBranchCatalog(branchId) {
        const catalog = window.transactionCatalog?.[branchId];

        if (!catalog) {
            return;
        }

        $('#transaction-code-preview').val(catalog.next_code);
        refreshAllProductSelects();

        $('#transaction-items-body .transaction-item-row').each(function () {
            updateRowSubtotal($(this));
            updateRowSellPrice($(this));
        });

        updateTotals();
    }

    function reindexRows() {
        $('#transaction-items-body .transaction-item-row').each(function (index) {
            $(this).find('.transaction-row-number').text(index + 1);

            $(this).find('[name^="items["]').each(function () {
                const name = $(this).attr('name');

                if (!name) {
                    return;
                }

                $(this).attr('name', name.replace(/items\[\d+]/, 'items[' + index + ']'));
            });
        });

        rowIndex = $('#transaction-items-body .transaction-item-row').length;
    }

    function appendRowFromTemplate() {
        const template = document.getElementById('transaction-item-template');

        if (!template) {
            return $();
        }

        const html = template.innerHTML.replace(/__INDEX__/g, String(rowIndex));
        const tbody = document.getElementById('transaction-items-body');

        tbody.insertAdjacentHTML('beforeend', html);

        const $row = $('#transaction-items-body .transaction-item-row').last();

        refreshAllProductSelects();
        updateRowSubtotal($row);
        updateRowSellPrice($row);

        reindexRows();
        updateTotals();

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
        const $rows = $('#transaction-items-body .transaction-item-row');

        if ($rows.length <= 1) {
            window.alert('Minimal satu baris produk harus ada.');
            return;
        }

        $button.closest('.transaction-item-row').remove();
        reindexRows();
        refreshAllProductSelects();
        updateTotals();
    }

    function initTransactionForm() {
        rowIndex = $('#transaction-items-body .transaction-item-row').length;

        $('#transaction-branch-id').on('change', function () {
            applyBranchCatalog($(this).val());
        });

        $('#transaction-add-item').on('click', function (event) {
            event.preventDefault();
            addRow();
        });

        $('#transaction-items-body').on('click', '.transaction-remove-item', function (event) {
            event.preventDefault();
            event.stopPropagation();
            removeRow($(this));
        });

        $('#transaction-items-body').on('change', '.transaction-product-select', function () {
            const $row = $(this).closest('.transaction-item-row');
            updateRowSellPrice($row);
            refreshAllProductSelects();
            updateRowSubtotal($row);
            updateTotals();
        });

        $('#transaction-items-body').on('input', '.transaction-qty-input', function () {
            const $row = $(this).closest('.transaction-item-row');
            updateRowSubtotal($row);
            updateTotals();
        });

        $(document).on('input', '#transaction-form [data-formatted-display]', function () {
            updateTotals();
        });

        if ($('#transaction-branch-id').length) {
            applyBranchCatalog($('#transaction-branch-id').val());
        } else {
            refreshAllProductSelects();
        }

        updateTotals();
    }

    $(initTransactionForm);
})(window, jQuery);
