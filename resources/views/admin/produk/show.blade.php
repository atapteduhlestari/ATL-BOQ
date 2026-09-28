@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')
@section('page-subtitle', 'Informasi lengkap produk')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body, body * { font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif !important; }
</style>
@endpush

@section('content')

    {{-- ==================== HEADER ==================== --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                p-6 mb-4">

        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4 min-w-0">
                {{-- Icon --}}
                <div class="w-14 h-14 rounded-2xl shrink-0
                            bg-gradient-to-br from-slate-800 to-slate-600
                            flex items-center justify-center text-white
                            shadow-[0_8px_20px_rgba(15,23,42,0.25)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <h2 class="text-xl font-bold text-slate-800 truncate">
                            {{ $produk->nama_produk }}
                        </h2>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                     text-[10px] font-bold uppercase tracking-wider
                                     {{ $produk->tipe_produk === 'main'
                                         ? 'bg-slate-100 text-slate-700 border border-slate-200'
                                         : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            {{ $produk->tipe_produk }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">{{ $produk->kode_produk }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.produk.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl
                          text-sm font-semibold text-slate-600
                          bg-slate-100 hover:bg-slate-200
                          border border-slate-200
                          transition-all active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('admin.produk.edit', $produk->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl
                          text-sm font-bold text-white
                          bg-gradient-to-r from-slate-800 to-slate-700
                          hover:from-slate-700 hover:to-slate-600
                          shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                          hover:shadow-[0_6px_20px_rgba(15,23,42,0.35)]
                          transition-all active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- ==================== HARGA (Highlight) ==================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

        {{-- HPP --}}
        <div class="relative overflow-hidden
                    bg-white/70 backdrop-blur-xl border border-white/60
                    rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    p-6">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                        bg-gradient-to-br from-slate-800/10 to-slate-600/5"></div>

            <div class="relative flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        HPP Produk
                    </p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">
                        Rp {{ number_format($produk->hpp_produk ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl shrink-0
                            bg-gradient-to-br from-slate-800 to-slate-600
                            flex items-center justify-center text-white
                            shadow-[0_8px_20px_rgba(15,23,42,0.25)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Harga Price List --}}
        <div class="relative overflow-hidden
                    bg-white/70 backdrop-blur-xl border border-white/60
                    rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    p-6">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full
                        bg-gradient-to-br from-emerald-500/10 to-emerald-400/5"></div>

            <div class="relative flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Harga Price List
                    </p>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">
                        Rp {{ number_format($produk->harga_price_list ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-2xl shrink-0
                            bg-gradient-to-br from-emerald-500 to-emerald-400
                            flex items-center justify-center text-white
                            shadow-[0_8px_20px_rgba(16,185,129,0.35)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== INFORMASI PRODUK ==================== --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                overflow-hidden mb-4">

        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Informasi Produk</h3>
            <p class="text-xs text-slate-500 mt-0.5">Detail lengkap produk</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Kode Produk --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Kode Produk
                    </p>
                    <p class="text-sm font-bold text-slate-800">{{ $produk->kode_produk }}</p>
                </div>

                {{-- Nama Produk --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Nama Produk
                    </p>
                    <p class="text-sm font-bold text-slate-800">{{ $produk->nama_produk }}</p>
                </div>

                {{-- Tipe Produk --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Tipe Produk
                    </p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 text-xs font-bold uppercase tracking-wider
                                 {{ $produk->tipe_produk === 'main'
                                     ? 'bg-slate-100 text-slate-700 border border-slate-200'
                                     : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ $produk->tipe_produk }}
                    </span>
                </div>

                {{-- Kategori --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Kategori
                    </p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 text-xs font-semibold
                                 bg-slate-100 text-slate-700
                                 border border-slate-200/60">
                        {{ $produk->kategori->category_name ?? '-' }}
                    </span>
                </div>

                {{-- Brand --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Brand
                    </p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 text-xs font-semibold
                                 bg-slate-100 text-slate-700
                                 border border-slate-200/60">
                        {{ $produk->brand->nama_brand ?? '-' }}
                    </span>
                </div>

                {{-- Unit --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Unit
                    </p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $produk->unit->unit_name ?? '-' }}
                    </p>
                </div>

                {{-- Area --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Area
                    </p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $produk->area->nama_area ?? '-' }}
                    </p>
                </div>

                {{-- Satuan Terkecil --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Satuan Terkecil
                    </p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $produk->satuan_terkecil ?? '-' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- ==================== AKSESORIS ==================== --}}
    @if ($produk->tipe_produk === 'main')
        <div class="bg-white/70 backdrop-blur-xl border border-white/60
                    rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Aksesoris Produk</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Total <span class="font-semibold text-slate-700">{{ $produk->accessories->count() }}</span> aksesoris terkait
                    </p>
                </div>
            </div>

            @if ($produk->accessories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200/60">
                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-14">No</th>
                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Brand</th>
                                <th class="px-5 py-3.5 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Harga</th>
                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk->accessories as $i => $acc)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-3.5 text-slate-400 font-medium">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $acc->kode_produk }}</td>
                                    <td class="px-5 py-3.5 text-slate-800 font-semibold">{{ $acc->nama_produk }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                                     text-xs font-semibold
                                                     bg-slate-100 text-slate-700
                                                     border border-slate-200/60">
                                            {{ $acc->brand->nama_brand ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-slate-800 font-bold">
                                        Rp {{ number_format($acc->harga_price_list ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $acc->unit->unit_name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-14 flex flex-col items-center gap-2">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100
                                flex items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Belum ada aksesoris</p>
                    <p class="text-xs text-slate-400">Produk ini belum memiliki aksesoris terkait</p>
                </div>
            @endif

        </div>
    @endif

@endsection