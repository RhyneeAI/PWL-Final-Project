@extends('layouts.main')

@section('title', 'Tambah Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Tambah Supplier</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan supplier baru untuk stok masuk barang.</p>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Cabang</label>
                    <select
                        id="supplier-branch-id"
                        name="branch_id"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        @foreach ($branches as $branch)
                            <option
                                value="{{ $branch->id }}"
                                data-next-code="{{ \App\Models\Supplier::generateNextCode($branch->id) }}"
                                @selected(old('branch_id', $selectedBranchId) == $branch->id)
                            >
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Kode Supplier</label>
                    <input
                        type="text"
                        id="supplier-code-preview"
                        value="{{ $nextCode }}"
                        readonly
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400
                               px-4 py-3 cursor-not-allowed"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Nama Supplier</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="PT Sumber Makmur"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Telepon</label>
                    @include('partials.phone-input', ['value' => old('phone')])
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Status</label>
                    <select
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <option value="1" @selected(old('is_active', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active') === '0')>Nonaktif</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="supplier@email.com"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Alamat</label>
                <textarea
                    name="address"
                    rows="4"
                    placeholder="Jl. alamat lengkap supplier..."
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-gray-500
                           px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                >{{ old('address') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('suppliers.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600 transition">
                    Simpan Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/assets/js/phone-input.js"></script>
    <script src="/assets/js/supplier-code.js"></script>
@endpush
