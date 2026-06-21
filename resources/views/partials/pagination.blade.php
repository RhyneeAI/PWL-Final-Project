@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-sm text-gray-400 rounded-lg border border-gray-200 dark:border-gray-700 cursor-not-allowed">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-200 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                ←
            </a>
        @endif

        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="px-3 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-lg">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                    class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-200 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-200 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                →
            </a>
        @else
            <span class="px-3 py-1.5 text-sm text-gray-400 rounded-lg border border-gray-200 dark:border-gray-700 cursor-not-allowed">→</span>
        @endif
    </nav>
@endif
