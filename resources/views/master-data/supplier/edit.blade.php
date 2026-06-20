@extends('layouts.main')

@section('title', 'Edit Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('partials.session-alert')
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Edit Supplier</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui data supplier.</p>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($canSelectBranch)
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Cabang</label>
                        <input
                            type="text"
                            value="{{ $supplier->branch->name }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                                   bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400
                                   px-4 py-3 cursor-not-allowed"
                        >
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Kode Supplier</label>
                    <input
                        type="text"
                        value="{{ $supplier->code }}"
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
                        value="{{ old('name', $supplier->name) }}"
                        placeholder="PT Sumber Makmur"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Telepon</label>
                    @include('partials.phone-input', ['value' => old('phone', $supplier->phone)])
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Status</label>
                    <select
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="1" @selected(old('is_active', $supplier->is_active ? '1' : '0') == '1')>Aktif</option>
                        <option value="0" @selected(old('is_active', $supplier->is_active ? '1' : '0') == '0')>Nonaktif</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $supplier->email) }}"
                        placeholder="supplier@email.com"
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                           px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('suppliers.index') }}"
                    class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition">
                    Update Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/assets/js/phone-input.js"></script>
@endpush
