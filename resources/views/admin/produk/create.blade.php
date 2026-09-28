@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Buat produk baru')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body, body * { font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif !important; }
</style>
@endpush

@section('content')

    <form method="POST" action="{{ route('admin.produk.store') }}" id="formProduk">
        @csrf

        {{-- ============ FORM UTAMA ============ --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/60
                    rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                    p-6 mb-4">

            <h2 class="text-base font-bold text-slate-800 mb-5">Informasi Produk</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Kode Produk --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Kode Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="kode_produk" value="{{ old('kode_produk') }}" required
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40
                                  @error('kode_produk') border-rose-300 @enderror">
                    @error('kode_produk')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Produk --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40
                                  @error('nama_produk') border-rose-300 @enderror">
                    @error('nama_produk')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori_id" required
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                   bg-white/80 text-sm text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Unit --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Unit <span class="text-rose-500">*</span>
                    </label>
                    <select name="unit_id" required
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                   bg-white/80 text-sm text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->unit_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipe Produk --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Tipe Produk <span class="text-rose-500">*</span>
                    </label>
                    <select name="tipe_produk" id="tipeProduk" required
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                   bg-white/80 text-sm text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="main"      {{ old('tipe_produk') === 'main' ? 'selected' : '' }}>Main</option>
                        <option value="aksesoris" {{ old('tipe_produk') === 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    </select>
                </div>

                {{-- Brand --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Brand <span class="text-rose-500">*</span>
                    </label>
                    <select name="brand_id" required
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                   bg-white/80 text-sm text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                        <option value="">-- Pilih Brand --</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Area --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Area <span class="text-rose-500">*</span>
                    </label>
                    <select name="area_id" required
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                   bg-white/80 text-sm text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                        <option value="">-- Pilih Area --</option>
                        @foreach ($areas as $a)
                            <option value="{{ $a->id }}" {{ old('area_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Satuan Terkecil --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Satuan Terkecil <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="satuan_terkecil"
                           value="{{ old('satuan_terkecil') }}" required
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                </div>

                {{-- HPP --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        HPP Produk
                    </label>
                    <input type="number" step="0.01" name="hpp_produk"
                           value="{{ old('hpp_produk') }}"
                           placeholder="Opsional"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  placeholder:text-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                </div>

                {{-- Harga Price List --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">
                        Harga Price List
                    </label>
                    <input type="number" step="0.01" name="harga_price_list"
                           value="{{ old('harga_price_list') }}"
                           placeholder="Opsional"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  placeholder:text-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                </div>

            </div>
        </div>

        {{-- ============ FORM AKSESORIS ============ --}}
        <div id="sectionAksesoris" class="hidden">
            <div class="bg-white/70 backdrop-blur-xl border border-white/60
                        rounded-3xl shadow-[0_8px_32px_rgba(15,23,42,0.08)]
                        p-6 mb-4">

                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Aksesoris Produk</h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pilih produk aksesoris yang terkait dengan produk main ini
                        </p>
                    </div>

                    <div class="text-sm text-slate-500">
                        Dipilih: <span id="countAksesoris" class="font-bold text-slate-800">0</span>
                    </div>
                </div>

                {{-- Search --}}
                <div class="mb-3">
                    <input type="text" id="searchAksesoris"
                           placeholder="Cari nama atau kode produk aksesoris..."
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200
                                  bg-white/80 text-sm text-slate-700
                                  placeholder:text-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-slate-400/40">
                </div>

                {{-- Tabel Aksesoris --}}
                <div class="border border-slate-200/60 rounded-2xl overflow-hidden
                            max-h-80 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-slate-50/95 backdrop-blur z-10">
                            <tr class="border-b border-slate-200/60">
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" id="checkAll"
                                           class="rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                                </th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                                <th class="px-3 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Brand</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAksesoris">
                            @foreach ($produkMain as $pm)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 row-aksesoris"
                                    data-brand-id="{{ $pm->brand_id }}"
                                    data-search="{{ strtolower($pm->kode_produk . ' ' . $pm->nama_produk . ' ' . ($pm->brand->nama_brand ?? '')) }}">
                                    <td class="px-3 py-2.5">
                                        <input type="checkbox" name="aksesoris[]" value="{{ $pm->id }}"
                                               class="check-aksesoris rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-500 font-medium">{{ $pm->kode_produk }}</td>
                                    <td class="px-3 py-2.5 text-slate-800 font-semibold">{{ $pm->nama_produk }}</td>
                                    <td class="px-3 py-2.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg
                                                     text-[10px] font-semibold
                                                     bg-slate-100 text-slate-700
                                                     border border-slate-200/60">
                                            {{ $pm->brand->nama_brand ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="emptyAksesoris" class="hidden">
                                <td colspan="4" class="px-3 py-8 text-center text-slate-400 text-sm">
                                    Tidak ada produk yang cocok.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- ============ TOMBOL AKSI ============ --}}
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.produk.index') }}"
               class="px-5 py-2.5 rounded-2xl text-sm font-semibold text-slate-600
                      bg-slate-100 hover:bg-slate-200
                      border border-slate-200
                      transition-all active:scale-[0.98]">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-2xl text-sm font-bold text-white
                           bg-gradient-to-r from-slate-800 to-slate-700
                           hover:from-slate-700 hover:to-slate-600
                           shadow-[0_4px_14px_rgba(15,23,42,0.25)]
                           hover:shadow-[0_6px_20px_rgba(15,23,42,0.35)]
                           transition-all active:scale-[0.98]">
                Simpan Produk
            </button>
        </div>

    </form>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tipeProduk       = document.getElementById('tipeProduk');
        const sectionAksesoris = document.getElementById('sectionAksesoris');
        const countAksesoris   = document.getElementById('countAksesoris');
        const checkAll         = document.getElementById('checkAll');
        const brandSelect      = document.querySelector('select[name="brand_id"]');

        function getAllRows() {
            return document.querySelectorAll('.row-aksesoris');
        }

        function getAllCheckboxes() {
            return document.querySelectorAll('.check-aksesoris');
        }

        function updateCount() {
            const n = document.querySelectorAll('.check-aksesoris:checked').length;
            countAksesoris.textContent = n;
        }

        const searchAksesoris = document.getElementById('searchAksesoris');
        const emptyAksesoris  = document.getElementById('emptyAksesoris');

        // ============ TOGGLE SECTION AKSESORIS ============
        function toggleAksesoris() {
            if (tipeProduk.value === 'main') {
                sectionAksesoris.classList.remove('hidden');
            } else {
                sectionAksesoris.classList.add('hidden');
                getAllCheckboxes().forEach(cb => cb.checked = false);
                updateCount();
            }
        }

        tipeProduk.addEventListener('change', toggleAksesoris);
        toggleAksesoris();
        updateCount();

        // ============ FILTER BY BRAND ============
        function filterAksesorisByBrand() {
            const brandId = brandSelect.value;
            let visible = 0;

            getAllRows().forEach(row => {
                const rowBrand = row.dataset.brandId;
                const match = !brandId || rowBrand === brandId;
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            emptyAksesoris.classList.toggle('hidden', visible > 0);
        }

        brandSelect.addEventListener('change', function () {
            searchAksesoris.value = '';
            filterAksesorisByBrand();
        });

        filterAksesorisByBrand();

        // ============ SEARCH ============
        function applySearch() {
            const q = searchAksesoris.value.toLowerCase().trim();
            const brandId = brandSelect.value;
            let visible = 0;

            getAllRows().forEach(row => {
                const rowBrand = row.dataset.brandId;
                const matchBrand = !brandId || rowBrand === brandId;
                const matchSearch = row.dataset.search.includes(q);
                const match = matchBrand && matchSearch;

                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            emptyAksesoris.classList.toggle('hidden', visible > 0);
        }

        searchAksesoris.addEventListener('input', applySearch);
        searchAksesoris.addEventListener('keyup', applySearch);

        // ============ CHECK ALL ============
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                const visibleRows = [...getAllRows()].filter(r => r.style.display !== 'none');
                visibleRows.forEach(row => {
                    const cb = row.querySelector('.check-aksesoris');
                    if (cb) cb.checked = this.checked;
                });
                updateCount();
            });
        }

        // ============ PER-ITEM CHANGE ============
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('check-aksesoris')) {
                updateCount();
            }
        });

    });
</script>

@endsection