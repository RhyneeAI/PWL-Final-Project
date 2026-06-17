@extends('errors.layout')

@section('title', 'Kesalahan Server')
@section('code', '500')
@section('icon', 'fas fa-exclamation-triangle')
@section('icon-color', 'text-red-400')
@section('heading', 'Terjadi Kesalahan')
@section('description', 'Server mengalami masalah. Tim kami sedang menangani ini. Silakan coba beberapa saat lagi.')

@section('action')
    <div class="flex items-center justify-center gap-x-3">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
            <i class="fas fa-home"></i>
            Kembali ke Dashboard
        </a>
        <button onclick="location.reload()"
            class="inline-flex items-center gap-x-2 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium rounded-xl transition-colors">
            <i class="fas fa-redo"></i>
            Coba Lagi
        </button>
    </div>
@endsection
