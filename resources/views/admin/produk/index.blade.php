@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Produk')
@section('page-subtitle', 'Daftar produk main')

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

    {{-- FILTER --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                p-5 mb-4">

        <form method="GET" action="{{ route('admin.produk.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Brand --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Brand</label>
                <select name="brand_id"
                        class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                               bg-white/80 text-sm text-slate-700
                               focus:outline-none focus:ring-2 focus:ring-slate-400/40
                               transition-all">
                    <option value="">Semua Brand</option>
                    @foreach ($brands as $b)
                        <option value="{{ $b->id }}"
                            {{ (string) $brandId === (string) $b->id ? 'selected' : '' }}>
                            {{ $b->nama_brand }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kategori</label>
                <select name="kategori_id"
                        class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                               bg-white/80 text-sm text-slate-700
                               focus:outline-none focus:ring-2 focus:ring-slate-400/40
                               transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}"
                            {{ (string) $kategoriId === (string) $k->id ? 'selected' : '' }}>
                            {{ $k->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Nama atau kode produk..."
                       class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 text-sm text-slate-700
                              placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-slate-400/40
                              transition-all">
            </div>

            {{-- Tombol --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-2xl text-sm font-bold text-white
                               bg-gradient-to-r from-slate-800 to-slate-700
                               hover:from-slate-700 hover:to-slate-600
                               shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                               hover:shadow-[0_6px_20px_rgba(15,23,42,0.35)]
                               transition-all active:scale-[0.98]">
                    Filter
                </button>
                <a href="{{ route('admin.produk.index') }}"
                   class="px-4 py-2.5 rounded-2xl text-sm font-semibold text-slate-600
                          bg-slate-100 hover:bg-slate-200
                          border border-slate-200
                          transition-all active:scale-[0.98]">
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                overflow-hidden">

        {{-- Header table --}}
        <div class="px-5 py-4 border-b border-slate-100
                    flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Produk</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Total <span class="font-semibold text-slate-700">{{ $produk->total() }}</span> produk
                </p>
            </div>

            {{-- Tombol Tambah Produk --}}
            <a href="{{ route('admin.produk.create') }}"
               class="inline-flex items-center gap-2
                      px-4 py-2.5 rounded-2xl
                      text-sm font-bold text-white
                      bg-gradient-to-r from-slate-800 to-slate-700
                      hover:from-slate-700 hover:to-slate-600
                      shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                      hover:shadow-[0_6px_20px_rgba(15,23,42,0.35)]
                      transition-all active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Produk</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/60">
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-14">No</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Brand</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3.5 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Unit</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $i => $p)
                        <tr class="border-b border-slate-100
                                   hover:bg-slate-50/70 transition-colors">

                            {{-- No --}}
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $produk->firstItem() + $i }}
                            </td>

                            {{-- Nama Produk --}}
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-800 leading-tight">
                                    {{ $p->nama_produk }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ $p->kode_produk }}
                                </div>
                            </td>

                            {{-- Brand --}}
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                             text-xs font-semibold
                                             bg-slate-100 text-slate-700
                                             border border-slate-200/60">
                                    {{ $p->brand->nama_brand ?? '-' }}
                                </span>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                             text-xs font-semibold
                                             bg-slate-100 text-slate-700
                                             border border-slate-200/60">
                                    {{ $p->kategori->category_name ?? '-' }}
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="px-4 py-3.5 text-right">
                                <span class="font-bold text-slate-800">
                                    Rp {{ number_format($p->harga_price_list ?? 0, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Unit --}}
                            <td class="px-4 py-3.5 text-slate-600 font-medium">
                                {{ $p->unit->unit_name ?? '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Detail --}}
                                    <a href="{{ route('admin.produk.show', $p->id) }}"
                                       title="Detail"
                                       class="w-8 h-8 rounded-xl
                                              flex items-center justify-center
                                              bg-blue-50 text-blue-600
                                              hover:bg-blue-100 hover:text-blue-700
                                              border border-blue-200/60
                                              transition-all active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.produk.edit', $p->id) }}"
                                       title="Edit"
                                       class="w-8 h-8 rounded-xl
                                              flex items-center justify-center
                                              bg-amber-50 text-amber-600
                                              hover:bg-amber-100 hover:text-amber-700
                                              border border-amber-200/60
                                              transition-all active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.produk.destroy', $p->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus"
                                                class="w-8 h-8 rounded-xl
                                                       flex items-center justify-center
                                                       bg-rose-50 text-rose-600
                                                       hover:bg-rose-100 hover:text-rose-700
                                                       border border-rose-200/60
                                                       transition-all active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100
                                                flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">Tidak ada data produk</p>
                                    <p class="text-xs text-slate-400">Coba ubah filter atau kata kunci pencarian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($produk->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $produk->links() }}
            </div>
        @endif

    </div>

@endsection