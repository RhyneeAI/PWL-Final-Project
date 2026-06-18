@props(['show' => true])

@if ($show)
    <td class="px-6 py-4 text-center whitespace-nowrap">
        <div class="inline-flex items-center justify-center gap-2">
            <button type="button" title="Edit" class="btn-action btn-action-edit">
                <i class="fas fa-pen-to-square"></i>
            </button>
            <button type="button" title="Hapus" class="btn-action btn-action-delete">
                <i class="fas fa-trash-can"></i>
            </button>
        </div>
    </td>
@endif
