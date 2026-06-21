<tr class="stock-out-item-row">
    <td class="px-4 py-3 align-top">
        <select name="items[{{ $index }}][product_id]" required
            class="stock-out-product-select w-full rounded-xl border border-gray-300 dark:border-gray-700 px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm">
            <option value="">— Pilih Produk —</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    data-unit="{{ $product->unit->label() }}"
                    data-stock="{{ $product->stock }}"
                    @selected(old("items.{$index}.product_id", $item['product_id'] ?? '') == $product->id)>
                    {{ $product->code }} — {{ $product->name }} (Stok: {{ $product->stock }})
                </option>
            @endforeach
        </select>
    </td>
    <td class="px-4 py-3 align-top">
        @include('partials.formatted-number-input', [
            'name' => "items[{$index}][quantity]",
            'value' => old("items.{$index}.quantity", $item['quantity'] ?? ''),
            'placeholder' => '0',
        ])
    </td>
    <td class="px-4 py-3 text-center align-top">
        <button type="button" class="btn-action btn-action-delete stock-out-remove-item" title="Hapus baris">
            <i class="fas fa-trash-can"></i>
        </button>
    </td>
</tr>
