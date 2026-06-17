@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('icon', 'fas fa-map-signs')
@section('icon-color', 'text-blue-400')
@section('heading', 'Halaman Tidak Ditemukan')
@section('description', 'Halaman yang Anda cari tidak ada atau sudah dipindahkan.')

@section('action')
    <div class="flex items-center justify-center gap-x-3">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
            <i class="fas fa-home"></i>
            Kembali ke Dashboard
        </a>
        <button onclick="history.back()"
            class="inline-flex items-center gap-x-2 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium rounded-xl transition-colors">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </button>
    </div>
@endsection
