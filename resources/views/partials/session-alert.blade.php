@if (session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-sm">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
