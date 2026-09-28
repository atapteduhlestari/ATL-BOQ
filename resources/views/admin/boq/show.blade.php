@extends('layouts.admin')

@section('title', 'Detail BOQ')
@section('page-title', 'Detail BOQ')
@section('page-subtitle', 'Informasi lengkap Bill of Quantity')

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
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <h2 class="text-xl font-bold text-slate-800 truncate">
                            {{ $boq->nomor_boq }}
                        </h2>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">
                        {{ \Carbon\Carbon::parse($boq->tanggal_boq)->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.boq.index') }}"
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

                <form method="POST"
                      action="{{ route('admin.boq.destroy', $boq->id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus BOQ ini?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl
                                   text-sm font-bold text-white
                                   bg-gradient-to-r from-rose-500 to-rose-400
                                   hover:from-rose-600 hover:to-rose-500
                                   shadow-[0_4px_14px_rgba(244,63,94,0.35)]
                                   hover:shadow-[0_6px_20px_rgba(244,63,94,0.45)]
                                   transition-all active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== INFO BOQ ==================== --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                overflow-hidden mb-4">

        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Informasi BOQ</h3>
            <p class="text-xs text-slate-500 mt-0.5">Detail dari dokumen BOQ</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Nomor BOQ
                    </p>
                    <p class="text-sm font-bold text-slate-800">{{ $boq->nomor_boq }}</p>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Tanggal BOQ
                    </p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($boq->tanggal_boq)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Jumlah Item
                    </p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                 text-xs font-bold
                                 bg-slate-100 text-slate-700
                                 border border-slate-200/60">
                        {{ $boq->details->count() }} item
                    </span>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Dibuat
                    </p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $boq->created_at->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- ==================== DETAIL ITEM ==================== --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h3 class="text-base font-bold text-slate-800">Detail Item</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Total <span class="font-semibold text-slate-700">{{ $boq->details->count() }}</span> item pada BOQ ini
                </p>
            </div>
        </div>

        @if ($boq->details->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60">
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-14">No</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode Produk</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Brand</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-24">Unit</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold text-slate-500 uppercase tracking-wider w-28">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boq->details as $i => $d)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">

                                <td class="px-5 py-3.5 text-slate-400 font-medium">
                                    {{ $i + 1 }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-500 font-medium">
                                    {{ $d->kode_produk ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-slate-800 font-semibold">
                                    {{ $d->produk->nama_produk ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5">
                                    @if ($d->produk && $d->produk->brand)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                                     text-xs font-semibold
                                                     bg-slate-100 text-slate-700
                                                     border border-slate-200/60">
                                            {{ $d->produk->brand->nama_brand }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $d->produk->unit->unit_name ?? '-' }}
                                </td>

                                <td class="px-5 py-3.5 text-right text-slate-800 font-bold">
                                    {{ number_format($d->qty, 2, ',', '.') }}
                                </td>

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
                <p class="text-sm font-semibold text-slate-500">Belum ada item</p>
                <p class="text-xs text-slate-400">BOQ ini belum memiliki detail item</p>
            </div>
        @endif

    </div>

@endsection