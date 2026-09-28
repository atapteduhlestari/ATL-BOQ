@extends('layouts.admin')

@section('title', 'BOQ')
@section('page-title', 'BOQ')
@section('page-subtitle', 'Daftar Bill of Quantity')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body, body * { font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif !important; }
</style>
@endpush

@section('content')

    {{-- FILTER --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/60
                rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                p-5 mb-4">

        <form method="GET" action="{{ route('admin.boq.index') }}"
              class="grid grid-cols-1 md:grid-cols-5 gap-3">

            {{-- Search --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nomor BOQ..."
                       class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 text-sm text-slate-700
                              placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-slate-400/40">
            </div>

            {{-- Tanggal Dari --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}"
                       class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 text-sm text-slate-700
                              focus:outline-none focus:ring-2 focus:ring-slate-400/40">
            </div>

            {{-- Tanggal Sampai --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                       class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                              bg-white/80 text-sm text-slate-700
                              focus:outline-none focus:ring-2 focus:ring-slate-400/40">
            </div>

            {{-- Tombol --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-2xl text-sm font-bold text-white
                               bg-gradient-to-r from-slate-800 to-slate-700
                               hover:from-slate-700 hover:to-slate-600
                               shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                               transition-all active:scale-[0.98]">
                    Filter
                </button>
                <a href="{{ route('admin.boq.index') }}"
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

        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar BOQ</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Total <span class="font-semibold text-slate-700">{{ $boq->total() }}</span> data
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/60">
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-14">No</th>
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nomor BOQ</th>

                        {{-- Kolom Tanggal dengan toggle sort --}}
                        <th class="px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('admin.boq.index', array_merge(request()->query(), ['sort' => 'tanggal_boq', 'dir' => ($sort === 'tanggal_boq' && $dir === 'asc') ? 'desc' : 'asc'])) }}"
                               class="inline-flex items-center gap-1.5 hover:text-slate-800 transition-colors">
                                <span>Tanggal</span>
                                @if ($sort === 'tanggal_boq')
                                    @if ($dir === 'asc')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-800" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-800" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    </svg>
                                @endif
                            </a>
                        </th>

                        <th class="px-4 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-28">Item</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($boq as $i => $b)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">

                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $boq->firstItem() + $i }}
                            </td>

                            <td class="px-4 py-3.5 text-slate-800 font-semibold">
                                {{ $b->nomor_boq }}
                            </td>

                            <td class="px-4 py-3.5 text-slate-600">
                                {{ \Carbon\Carbon::parse($b->tanggal_boq)->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                                             text-xs font-bold
                                             bg-slate-100 text-slate-700
                                             border border-slate-200/60">
                                    {{ $b->details_count }} item
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Detail --}}
                                    <a href="{{ route('admin.boq.show', $b->id) }}"
                                       title="Detail"
                                       class="w-8 h-8 rounded-xl
                                              flex items-center justify-center
                                              bg-blue-50 text-blue-600
                                              hover:bg-blue-100
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

                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.boq.destroy', $b->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus BOQ ini?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus"
                                                class="w-8 h-8 rounded-xl
                                                       flex items-center justify-center
                                                       bg-rose-50 text-rose-600
                                                       hover:bg-rose-100
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
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100
                                                flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">Belum ada data BOQ</p>
                                    <p class="text-xs text-slate-400">Data BOQ akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($boq->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $boq->links() }}
            </div>
        @endif

    </div>

@endsection