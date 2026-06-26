@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Poppins', sans-serif !important;
    }
    
    body {
        background: #f5f7fa;
    }
    
    input, select, button {
        font-size: 13px !important;
    }
    
    label {
        font-size: 11px !important;
        letter-spacing: 0.3px;
        font-weight: 500;
        color: #4a5568;
    }
    
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
        overflow: hidden;
    }
    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
    }
    
    .section-body {
        padding: 20px;
    }
    
    .input-field {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        transition: all 0.2s;
        font-size: 13px;
        color: #2d3748;
    }
    .input-field:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    .input-field:read-only {
        background: #f7fafc;
        color: #2d3748;
        font-weight: 500;
    }
    
    .data-box {
        background: #f7fafc;
        border-radius: 8px;
        padding: 10px 14px;
        border: 1px solid #edf2f7;
    }
    
    .data-box label {
        font-size: 9px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #a0aec0;
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
    }
    
    .data-box .value {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .data-box-dim {
        background: #f7fafc;
        border-radius: 8px;
        padding: 10px 14px;
        border: 1px solid #edf2f7;
    }
    
    .data-box-dim label {
        font-size: 9px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4a5568;
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
    }
    
    .data-box-dim .value {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .btn-primary {
        background: #2d3748;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        border: none;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }
    .btn-primary:hover {
        background: #1a202c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 55, 72, 0.2);
    }
    
    .btn-secondary {
        background: #4a5568;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        border: none;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }
    .btn-secondary:hover {
        background: #2d3748;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 85, 104, 0.2);
    }
    
    .btn-pdf {
        background: #e53e3e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        border: none;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }
    .btn-pdf:hover {
        background: #c53030;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(229, 62, 62, 0.2);
    }
    
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 12px;
    }
    
    .table-container table {
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
    }
    
    .table-container th {
        background: #f7fafc;
        padding: 10px 14px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table-container td {
        padding: 10px 14px;
        border-bottom: 1px solid #edf2f7;
        color: #2d3748;
    }
    
    .table-container tr:last-child td {
        border-bottom: none;
    }
    
    .table-container tr:hover {
        background: #f7fafc;
    }
    
    .badge-area {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
        background: #edf2f7;
        color: #4a5568;
    }
    
    .total-box {
        background: #2d3748;
        border-radius: 12px;
        padding: 20px 24px;
        color: white;
    }
    
    .total-box .label {
        font-size: 12px;
        color: #a0aec0;
        font-weight: 400;
    }
    
    .total-box .amount {
        font-size: 28px;
        font-weight: 700;
        color: white;
    }
    
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }
    
    .waste-input {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .waste-input input {
        width: 80px;
        text-align: center;
    }
    .waste-input span {
        font-size: 12px;
        color: #4a5568;
    }
    
    .header-main {
        background: linear-gradient(135deg, #2d3748, #1a202c);
        border-radius: 12px;
        padding: 24px 28px;
        color: white;
    }
    
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .section-subtitle {
        font-size: 11px;
        color: #718096;
    }
    
    .badge-section {
        background: #edf2f7;
        color: #4a5568;
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }
    
    .warning-text {
        font-size: 11px;
        color: #dd6b20;
        margin-top: 4px;
    }
    
    .info-text {
        font-size: 9px;
        color: #a0aec0;
        margin-top: 2px;
        display: block;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-main">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-1">BOQ - Pelana + Dinding</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material atap pelana dengan dinding</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">{{ $brand->nama_brand ?? 'IKO - ATAP' }}</span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        
        <!-- BAGIAN 1: ATAP PELANA -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🏠 Atap Pelana (Kemiringan {{ $sudut ?? 0 }}°)</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk atap pelana</p>
                    </div>
                    <span class="badge-section">BAGIAN 1</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($luasAtap ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($starter ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="nok_jurai" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($nokJurai ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>
                
                <!-- Data Dimensi -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    <div class="data-box-dim">
                        <label>Panjang</label>
                        <div class="value">
                            <input type="number" id="panjang" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($panjang ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box-dim">
                        <label>Lebar</label>
                        <div class="value">
                            <input type="number" id="lebar" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($lebar ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box-dim">
                        <label>Kemiringan</label>
                        <div class="value">
                            <input type="number" id="sudut_atap" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pilih Produk -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers ?? [] as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if(isset($sudut) && $sudut <= 30 && $sudut > 0)
                            <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut }}° (≤ 30°), disarankan menggunakan underlayer khusus.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Flashing & Waste -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Flashing</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" id="flashing" class="input-field" style="width: 100px;" readonly value="{{ number_format($flashing ?? 0, 2) }}">
                            <span style="font-size: 12px; color: #a0aec0;">meter</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste (%)</label>
                        <div class="waste-input">
                            <input type="number" id="waste" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungBagianAtap()" class="btn-primary">
                    🏠 Hitung Material Atap
                </button>
                
                <div id="hasilAtap" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material - Atap</h4>
                        <div class="table-container" id="tableAtap"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotalAtap">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- BAGIAN 2: DINDING -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🧱 Dinding ({{ $jumlahSisi ?? 2 }} Sisi)</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk dinding</p>
                    </div>
                    <span class="badge-section">BAGIAN 2</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Dinding</label>
                        <div class="value">
                            <input type="number" id="luas_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($luasDinding ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Wall Flashing</label>
                        <div class="value">
                            <input type="number" id="wall_flashing" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($wallFlashing ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Jumlah Sisi</label>
                        <div class="value">
                            <input type="number" id="jumlah_sisi" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $jumlahSisi ?? 2 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">sisi</span>
                        </div>
                    </div>
                </div>
                
                <!-- Data Dimensi -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    <div class="data-box-dim">
                        <label>Panjang Dinding</label>
                        <div class="value">
                            <input type="number" id="panjang_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($panjangDinding ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box-dim">
                        <label>Tinggi Dinding</label>
                        <div class="value">
                            <input type="number" id="tinggi_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($tinggiDinding ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box-dim">
                        <label>Jumlah Sisi</label>
                        <div class="value">
                            <input type="number" id="jumlah_sisi_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $jumlahSisi ?? 2 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">sisi</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pilih Produk Dinding -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama (Dinding)</label>
                        <select id="produk_dinding" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                        <span class="info-text">*Produk sama dengan atap utama</span>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste Dinding (%)</label>
                        <div class="waste-input">
                            <input type="number" id="waste_dinding" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungBagianDinding()" class="btn-secondary">
                    🧱 Hitung Material Dinding
                </button>
                
                <div id="hasilDinding" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material - Dinding</h4>
                        <div class="table-container" id="tableDinding"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotalDinding">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- TOTAL KESELURUHAN -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
                <p class="text-gray-400 text-[10px]">Atap + Dinding (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/atap-kombinasi/pelana-dinding/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Pelana + Dinding">
            <input type="hidden" name="brand" value="{{ $brand->nama_brand ?? 'IKO - ATAP' }}">
            
            <!-- Data Atap -->
            <input type="hidden" name="bagian1[data_perhitungan][luas_atap]" id="pdf_luas_atap" value="{{ $luasAtap ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][sudut]" id="pdf_sudut" value="{{ $sudut ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][starter]" id="pdf_starter" value="{{ $starter ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][nok_jurai]" id="pdf_nok_jurai" value="{{ $nokJurai ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][flashing]" id="pdf_flashing" value="{{ $flashing ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][panjang]" id="pdf_panjang" value="{{ $panjang ?? 0 }}">
            <input type="hidden" name="bagian1[data_perhitungan][lebar]" id="pdf_lebar" value="{{ $lebar ?? 0 }}">
            <input type="hidden" name="bagian1[hasil]" id="pdf_hasil_atap">
            <input type="hidden" name="bagian1[total]" id="pdf_total_atap">
            <input type="hidden" name="waste_atap" id="pdf_waste_atap">
            
            <!-- Data Dinding -->
            <input type="hidden" name="bagian2[data_perhitungan][luas_dinding]" id="pdf_luas_dinding" value="{{ $luasDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][wall_flashing]" id="pdf_wall_flashing" value="{{ $wallFlashing ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][panjang_dinding]" id="pdf_panjang_dinding" value="{{ $panjangDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][tinggi_dinding]" id="pdf_tinggi_dinding" value="{{ $tinggiDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][jumlah_sisi]" id="pdf_jumlah_sisi" value="{{ $jumlahSisi ?? 2 }}">
            <input type="hidden" name="bagian2[hasil]" id="pdf_hasil_dinding">
            <input type="hidden" name="bagian2[total]" id="pdf_total_dinding">
            <input type="hidden" name="waste_dinding" id="pdf_waste_dinding">
            
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF
            </button>
        </form>
    </div>
</div>

<script>
let hasilAtap = [];
let hasilDinding = [];

function hitungBagianAtap() {
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap').value) || 0,
        sudut: parseFloat(document.getElementById('sudut').value) || 0,
        panjang_starter: parseFloat(document.getElementById('starter').value) || 0,
        panjang_nok_jurai: parseFloat(document.getElementById('nok_jurai').value) || 0,
        panjang_flashing: parseFloat(document.getElementById('flashing').value) || 0,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: 0,
        produk_atap_id: document.getElementById('produk_atap').value,
        underlayer_id: document.getElementById('underlayer').value,
        rangka: 'Baja Ringan',
        lantai_kerja: 'Plywood 9 mm',
        waste: parseFloat(document.getElementById('waste').value) || 5
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama terlebih dahulu!');
        return;
    }
    
    fetch('/boq/iko-atap/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            hasilAtap = data.results;
            renderTable('Atap', hasilAtap, document.getElementById('grandTotalAtap'));
            document.getElementById('hasilAtap').classList.remove('hidden');
            updateTotal();
            updatePdfData();
        } else {
            alert('Error: ' + (data.message || 'Gagal menghitung'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan server');
    });
}

function hitungBagianDinding() {
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_dinding').value) || 0,
        panjang_wall_flashing: parseFloat(document.getElementById('wall_flashing').value) || 0,
        produk_atap_id: document.getElementById('produk_dinding').value,
        waste: parseFloat(document.getElementById('waste_dinding').value) || 5
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama untuk dinding terlebih dahulu!');
        return;
    }
    
    fetch('/boq/iko-atap/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            hasilDinding = data.results;
            renderTable('Dinding', hasilDinding, document.getElementById('grandTotalDinding'));
            document.getElementById('hasilDinding').classList.remove('hidden');
            updateTotal();
            updatePdfData();
        } else {
            alert('Error: ' + (data.message || 'Gagal menghitung'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan server');
    });
}

function renderTable(bagian, results, grandTotalEl) {
    let containerId = bagian === 'Atap' ? 'tableAtap' : 'tableDinding';
    let container = document.getElementById(containerId);
    let html = `<table><thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
    let total = 0;
    results.forEach(item => {
        total += item.total_harga;
        html += `<tr>
            <td><strong>${item.nama_produk}</strong></td>
            <td><span class="badge-area">${item.area}</span></td>
            <td style="text-align:right; font-weight:500;">${item.qty}</td>
            <td style="text-align:right; color:#718096;">${item.satuan}</td>
            <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${item.total_harga.toLocaleString()}</td>
        </tr>`;
    });
    html += `</tbody></table>`;
    container.innerHTML = html;
    grandTotalEl.innerHTML = `Rp ${total.toLocaleString()}`;
}

function updateTotal() {
    let totalAtap = hasilAtap.reduce((s, i) => s + i.total_harga, 0);
    let totalDinding = hasilDinding.reduce((s, i) => s + i.total_harga, 0);
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${(totalAtap + totalDinding).toLocaleString()}`;
}

function updatePdfData() {
    // Waste
    document.getElementById('pdf_waste_atap').value = document.getElementById('waste').value;
    document.getElementById('pdf_waste_dinding').value = document.getElementById('waste_dinding').value;
    
    // Hasil Atap
    document.getElementById('pdf_hasil_atap').value = JSON.stringify(hasilAtap);
    document.getElementById('pdf_total_atap').value = document.getElementById('grandTotalAtap').innerText;
    
    // Hasil Dinding
    document.getElementById('pdf_hasil_dinding').value = JSON.stringify(hasilDinding);
    document.getElementById('pdf_total_dinding').value = document.getElementById('grandTotalDinding').innerText;
    
    // Grand Total
    document.getElementById('pdf_grand_total').value = document.getElementById('totalKeseluruhan').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection