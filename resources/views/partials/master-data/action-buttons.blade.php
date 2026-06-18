@props([
    'show' => true,
    'id' => null
])

@if ($show)
<td class="px-6 py-4 text-center whitespace-nowrap">
    <div class="inline-flex items-center justify-center gap-2">

        <a href="{{ route('branch.edit', $id) }}"
           title="Edit"
           class="btn-action btn-action-edit">
            <i class="fas fa-pen-to-square"></i>
        </a>

        <form action="{{ route('branch.destroy', $id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data ini?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    title="Hapus"
                    class="btn-action btn-action-delete">
                <i class="fas fa-trash-can"></i>
            </button>

        </form>

    </div>
</td>
@endif