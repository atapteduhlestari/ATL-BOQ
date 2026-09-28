@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data dan aktivitas sistem')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body, body * {
        font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif !important;
    }
</style>
@endpush

@section('content')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Card 1: Total Produk --}}
        <div class="group relative overflow-hidden
                    bg-white/70 backdrop-blur-xl
                    border border-white/60
                    rounded-3xl
                    shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    hover:shadow-[0_16px_48px_rgba(15,23,42,0.15)]
                    transition-all duration-300
                    hover:-translate-y-1
                    p-6">

            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                        bg-gradient-to-br from-slate-800/10 to-slate-600/5
                        group-hover:scale-125 transition-transform duration-500">
            </div>

            <div class="relative w-12 h-12 rounded-2xl
                        bg-gradient-to-br from-slate-800 to-slate-600
                        flex items-center justify-center text-white
                        shadow-[0_8px_20px_rgba(15,23,42,0.25)]
                        mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>

            <p class="relative text-xs font-semibold text-slate-500 uppercase tracking-wider">
                Total Produk
            </p>

            <div class="relative flex items-end gap-2 mt-2">
                <p class="text-4xl font-extrabold text-slate-800 leading-none">
                    {{ $jumlahProduk }}
                </p>
                <span class="text-xs font-medium text-slate-400 mb-1">produk</span>
            </div>

            <div class="relative mt-4 h-1 w-12 rounded-full
                        bg-gradient-to-r from-slate-800 to-slate-500
                        group-hover:w-20 transition-all duration-300">
            </div>

        </div>

        {{-- Card 2: Total BOQ --}}
        <div class="group relative overflow-hidden
                    bg-white/70 backdrop-blur-xl
                    border border-white/60
                    rounded-3xl
                    shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    hover:shadow-[0_16px_48px_rgba(15,23,42,0.15)]
                    transition-all duration-300
                    hover:-translate-y-1
                    p-6">

            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                        bg-gradient-to-br from-emerald-500/10 to-emerald-400/5
                        group-hover:scale-125 transition-transform duration-500">
            </div>

            <div class="relative w-12 h-12 rounded-2xl
                        bg-gradient-to-br from-emerald-500 to-emerald-400
                        flex items-center justify-center text-white
                        shadow-[0_8px_20px_rgba(16,185,129,0.35)]
                        mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>

            <p class="relative text-xs font-semibold text-slate-500 uppercase tracking-wider">
                Total BOQ
            </p>

            <div class="relative flex items-end gap-2 mt-2">
                <p class="text-4xl font-extrabold text-slate-800 leading-none">
                    {{ $jumlahBoq }}
                </p>
                <span class="text-xs font-medium text-slate-400 mb-1">data</span>
            </div>

            <div class="relative mt-4 h-1 w-12 rounded-full
                        bg-gradient-to-r from-emerald-500 to-emerald-400
                        group-hover:w-20 transition-all duration-300">
            </div>

        </div>

        {{-- Card 3: BOQ Hari Ini --}}
        <div class="group relative overflow-hidden
                    bg-white/70 backdrop-blur-xl
                    border border-white/60
                    rounded-3xl
                    shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    hover:shadow-[0_16px_48px_rgba(15,23,42,0.15)]
                    transition-all duration-300
                    hover:-translate-y-1
                    p-6">

            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                        bg-gradient-to-br from-amber-500/10 to-amber-400/5
                        group-hover:scale-125 transition-transform duration-500">
            </div>

            <div class="relative w-12 h-12 rounded-2xl
                        bg-gradient-to-br from-amber-500 to-amber-400
                        flex items-center justify-center text-white
                        shadow-[0_8px_20px_rgba(245,158,11,0.35)]
                        mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <p class="relative text-xs font-semibold text-slate-500 uppercase tracking-wider">
                BOQ Hari Ini
            </p>

            <div class="relative flex items-end gap-2 mt-2">
                <p class="text-4xl font-extrabold text-slate-800 leading-none">
                    {{ $jumlahBoqHariIni }}
                </p>
                <span class="text-xs font-medium text-slate-400 mb-1">data</span>
            </div>

            <div class="relative mt-4 h-1 w-12 rounded-full
                        bg-gradient-to-r from-amber-500 to-amber-400
                        group-hover:w-20 transition-all duration-300">
            </div>

        </div>

    </div>

@endsection