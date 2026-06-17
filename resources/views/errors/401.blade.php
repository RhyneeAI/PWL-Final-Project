@extends('errors.layout')

@section('title', 'Tidak Terautentikasi')
@section('code', '401')
@section('icon', 'fas fa-lock')
@section('icon-color', 'text-amber-400')
@section('heading', 'Sesi Anda Telah Berakhir')
@section('description', 'Anda perlu login terlebih dahulu untuk mengakses halaman ini.')

@section('action')
    <a href="{{ route('login') }}"
        class="inline-flex items-center gap-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
        <i class="fas fa-sign-in-alt"></i>
        Kembali ke Login
    </a>
@endsection
