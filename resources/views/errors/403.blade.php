@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('icon', 'fas fa-shield-alt')
@section('icon-color', 'text-red-400')
@section('heading', 'Akses Ditolak')
@section('description', 'Anda tidak memiliki izin untuk mengakses halaman ini.')

@section('action')
    <a href="{{ route('dashboard') }}"
        class="inline-flex items-center gap-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
        <i class="fas fa-home"></i>
        Kembali ke Dashboard
    </a>
@endsection
