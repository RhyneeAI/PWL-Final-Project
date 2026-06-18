{{-- Search bar + filter shortcut buttons --}}
@props([
    'searchId',
    'searchPlaceholder' => 'Cari data...',
    'filters' => [],
])

<div class="table-toolbar bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="w-full lg:w-2/5 relative">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input
            type="text"
            id="{{ $searchId }}"
            placeholder="{{ $searchPlaceholder }}"
            class="table-search-input w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
    </div>

    @if (count($filters))
        <div class="flex flex-wrap gap-2 lg:justify-end">
            @foreach ($filters as $index => $filter)
                <button
                    type="button"
                    class="table-filter-btn px-4 py-2 text-sm rounded-xl transition-colors {{ $index === 0 ? 'is-active' : '' }}"
                    data-filter-column="{{ $filter['column'] ?? '' }}"
                    data-filter-value="{{ $filter['value'] ?? '' }}"
                >
                    {{ $filter['label'] }}
                </button>
            @endforeach
        </div>
    @endif
</div>
