<tr class="transaction-item-row">
    <td class="px-4 py-3 align-top transaction-row-number">{{ (int)$index + 1 }}</td>
    <td class="px-4 py-3 align-top min-w-[250px]">
        <select name="items[{{ $index }}][product_id]" required
            class="transaction-product-select w-full rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            <option value="">— Pilih Produk —</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    data-sell-price="{{ (int) $product->sell_price }}"
                    data-unit="{{ $product->unit->label() }}"
                    data-stock="{{ $product->stock }}"
                    @selected(old("items.{$index}.product_id", $item['product_id'] ?? '') == $product->id)>
                    {{ $product->code }} — {{ $product->name }} (Stok: {{ $product->stock }})
                </option>
            @endforeach
        </select>
    </td>
    <td class="px-4 py-3 align-top">
        <input type="text" readonly
            class="transaction-sell-price w-full rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white text-sm cursor-not-allowed"
            value="Rp 0">
    </td>
    <td class="px-4 py-3 align-top w-20">
        <input type="number" name="items[{{ $index }}][quantity]"
            value="{{ old("items.{$index}.quantity", $item['quantity'] ?? '1') }}"
            min="1" required placeholder="0"
            class="transaction-qty-input w-20 rounded-xl border border-gray-300 dark:border-gray-700 px-2 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm text-center"
            inputmode="numeric" autocomplete="off">
    </td>
    <td class="px-4 py-3 align-top">
        <input type="text" readonly
            class="transaction-subtotal w-full rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm cursor-not-allowed"
            value="Rp 0">
    </td>
    <td class="px-4 py-3 text-center align-top">
        <button type="button" class="btn-action btn-action-delete transaction-remove-item" title="Hapus baris">
            <i class="fas fa-trash-can"></i>
        </button>
    </td>
</tr>
