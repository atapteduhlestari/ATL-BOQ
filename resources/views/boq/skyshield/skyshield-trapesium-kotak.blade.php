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

    /* Opsi Tambahan Grid */
    .opsi-tambahan-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
    }
    
    @media (max-width: 640px) {
        .opsi-tambahan-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .opsi-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .opsi-item label {
        font-size: 10px !important;
        font-weight: 500;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .opsi-item label .opsi-desc {
        font-weight: 400;
        color: #a0aec0;
        font-size: 9px;
    }
    
    .opsi-item input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
        background: white;
    }
    
    .opsi-item input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    
    .opsi-item input::placeholder {
        color: #cbd5e1;
        font-size: 11px;
    }
    
    .opsi-item .opsi-satuan {
        font-size: 10px;
        color: #a0aec0;
        margin-top: 2px;
    }

    /* NOTES / PEMBERITAHUAN */
    .notes-container {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    
    .notes-container .notes-title {
        font-size: 13px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .notes-container .notes-title .icon {
        font-size: 18px;
    }
    
    .notes-container .notes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .notes-container .notes-list li {
        font-size: 12px;
        color: #78350f;
        padding: 4px 0;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.5;
    }
    
    .notes-container .notes-list li .bullet {
        color: #d97706;
        font-weight: 700;
    }
    
    .notes-container .notes-list li strong {
        color: #92400e;
    }
    
    .notes-container .notes-list li .highlight {
        background: #fef3c7;
        padding: 0 6px;
        border-radius: 4px;
        font-weight: 500;
        color: #92400e;
    }
    
    .notes-container .notes-list li .badge-angin {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        padding: 1px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }
    
    .notes-container .notes-list li .warning-text {
        color: #991b1b;
        font-weight: 600;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-main">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-1">BOQ - Atap Trapesium Kotak</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material atap trapesium kotak</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">{{ $brand->nama_brand ?? 'SKYSHIELD' }}</span>
            </div>
        </div>
    </div>

         <!-- ===== NOTES / PEMBERITAHUAN ===== -->
<div class="notes-container">
    <div class="notes-title">
        <span class="icon">📋</span> Petunjuk Pengisian BOQ
    </div>
    <ul class="notes-list">
        <li>
            <span class="bullet">•</span>
            <span>Cek lebih detail apakah ada atap yang bertemu langsung dengan <strong>dinding</strong>, <strong>kaca</strong>, <strong>penangkal petir</strong>, <strong>Ventilasi Exhaust</strong> dll.</span>
        </li>
        <li>
            <span class="bullet">•</span>
            <span>Jika bertemu dinding, kaca, penangkal petir, ventilasi exhaust, silahkan input berapa panjang/area pertemuannya di bagian <strong>"Opsi Tambahan"</strong> masing-masing bagian atap.</span>
        </li>
        <li>
            <span class="bullet">•</span>
            <span>Untuk atap dengan kemiringan dibawah <strong>15 Derajat</strong> tidak disarankan menggunakan ridge ventilator.</span>
        </li>
        <li>
            <span class="bullet">•</span>
            <span><strong>Hal yang perlu diperhatikan:</strong></span>
        </li>
        <li style="padding-left: 28px;">
            <span>• Jarak usuk per <strong>60 cm</strong> pakai <strong>Plywood minimal 12 mm</strong>, tidak disarankan pakai 9 mm</span>
        </li>
        <li style="padding-left: 28px;">
            <span>• Jarak usuk per <strong>40 cm</strong> pakai <strong>Plywood minimal 9 mm</strong></span>
        </li>
        <li style="padding-left: 32px;">
            <span>• Pemakaian underlayer <strong>self adhesives</strong> direkomendasikan</span>
        </li>
    </ul>
</div>
        
    <div class="space-y-6">
        
        <!-- BAGIAN 1: TRAPESIUM KOTAK -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">📐 Trapesium Kotak</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk atap trapesium kotak</p>
                    </div>
                    <span class="badge-section">BAGIAN 1</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Panjang Atas</label>
                        <div class="value">
                            <input type="number" id="tk_panjang_atas" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($panjangAtas ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Panjang Bawah</label>
                        <div class="value">
                            <input type="number" id="tk_panjang_bawah" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($panjangBawah ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Tinggi</label>
                        <div class="value">
                            <input type="number" id="tk_tinggi" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($tinggi ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Kemiringan</label>
                        <div class="value">
                            <input type="number" id="tk_sudut" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="tk_luas_atap" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($luasAtap ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="tk_starter" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($starter ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="tk_nok_jurai" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($nokJurai ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="tk_flashing" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($flashing ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>

                <!-- ===== OPSI TAMBAHAN ===== -->
                <div class="opsi-tambahan-grid">
                    <div class="opsi-item">
                        <label>
                            📐 Dinding
                            <span class="opsi-desc">(panjang atap yang berbatasan dinding)</span>
                        </label>
                        <input type="number" 
                               id="opsi_dinding" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiDinding ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            🪟 Kaca
                            <span class="opsi-desc">(panjang area kaca/genteng kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiKaca ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            ⚡ Penangkal Petir
                            <span class="opsi-desc">(jumlah titik)</span>
                        </label>
                        <input type="number" 
                               id="opsi_penangkal" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiPenangkal ?? 0 }}">
                        <span class="opsi-satuan">titik</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            💨 Exhaust
                            <span class="opsi-desc">(jumlah titik ventilasi)</span>
                        </label>
                        <input type="number" 
                               id="opsi_exhaust" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiExhaust ?? 0 }}">
                        <span class="opsi-satuan">titik</span>
                    </div>
                </div>
                
                <!-- Pilih Produk -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="tk_produk_atap" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="tk_underlayer" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers ?? [] as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if(isset($sudut) && $sudut <= 30 && $sudut > 0)
                            <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut }}° (≤ 30°), disarankan menggunakan underlayer khusus.</p>
                        @endif
                    </div>
                    <!-- ===== DROPDOWN STARTER ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Starter</label>
                        <select id="tk_starter_produk" class="input-field">
                            <option value="">Pilih Starter</option>
                            @foreach($starters ?? [] as $starter)
                                <option value="{{ $starter->id }}">{{ $starter->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- ===== DROPDOWN STRUKTUR RANGKA ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="tk_rangka" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <!-- ===== DROPDOWN LANTAI KERJA ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Lantai Kerja</label>
                        <select id="tk_lantai_kerja" class="input-field">
                            <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                            <option value="Plywood 12 mm">Plywood 12 mm</option>
                            <option value="Plywood 15 mm">Plywood 15 mm</option>
                            <option value="GRC 9 mm">GRC 9 mm</option>
                            <option value="GRC 12 mm">GRC 12 mm</option>
                            <option value="GRC 15 mm">GRC 15 mm</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste (%)</label>
                        <div class="waste-input">
                            <input type="number" id="tk_waste" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungTrapesiumKotak()" class="btn-primary">
                    📐 Hitung Material Trapesium Kotak
                </button>
                
                <div id="tk_hasil" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Trapesium Kotak</h4>
                        <div class="table-container" id="tk_table"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="tk_grandTotal">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- TOTAL KESELURUHAN + TOMBOL PDF -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
                <p class="text-gray-400 text-[10px]">Trapesium Kotak (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="tk_totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/skyshield/trapesium-kotak/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Atap Trapesium Kotak">
            <input type="hidden" name="brand" value="{{ $brand->nama_brand ?? 'SKYSHIELD' }}">
            
            <input type="hidden" name="panjang_atas" id="pdf_panjang_atas" value="{{ $panjangAtas ?? 0 }}">
            <input type="hidden" name="panjang_bawah" id="pdf_panjang_bawah" value="{{ $panjangBawah ?? 0 }}">
            <input type="hidden" name="tinggi" id="pdf_tinggi" value="{{ $tinggi ?? 0 }}">
            <input type="hidden" name="sudut" id="pdf_sudut" value="{{ $sudut ?? 0 }}">
            <input type="hidden" name="luas_atap" id="pdf_luas_atap" value="{{ $luasAtap ?? 0 }}">
            <input type="hidden" name="starter" id="pdf_starter" value="{{ $starter ?? 0 }}">
            <input type="hidden" name="nok_jurai" id="pdf_nok_jurai" value="{{ $nokJurai ?? 0 }}">
            <input type="hidden" name="flashing" id="pdf_flashing" value="{{ $flashing ?? 0 }}">
            <input type="hidden" name="waste" id="pdf_waste">
            <input type="hidden" name="hasil" id="pdf_hasil">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <!-- Opsi Tambahan -->
            <input type="hidden" name="opsi_dinding" id="pdf_opsi_dinding">
            <input type="hidden" name="opsi_kaca" id="pdf_opsi_kaca">
            <input type="hidden" name="opsi_penangkal" id="pdf_opsi_penangkal">
            <input type="hidden" name="opsi_exhaust" id="pdf_opsi_exhaust">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF Custom
            </button>
        </form>
    </div>
</div>

<script>
let tk_results = [];

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    document.getElementById('tk_panjang_atas').value = urlParams.get('panjang_atas') || 0;
    document.getElementById('tk_panjang_bawah').value = urlParams.get('panjang_bawah') || 0;
    document.getElementById('tk_tinggi').value = urlParams.get('tinggi') || 0;
    document.getElementById('tk_sudut').value = urlParams.get('sudut') || 0;
    document.getElementById('tk_luas_atap').value = urlParams.get('luas_atap') || 0;
    document.getElementById('tk_starter').value = urlParams.get('starter') || 0;
    document.getElementById('tk_nok_jurai').value = urlParams.get('nok_jurai') || 0;
    document.getElementById('tk_flashing').value = urlParams.get('flashing') || 0;
    
    // Opsi dari URL
    document.getElementById('opsi_dinding').value = urlParams.get('dinding') || 0;
    document.getElementById('opsi_kaca').value = urlParams.get('kaca') || 0;
    document.getElementById('opsi_penangkal').value = urlParams.get('penangkal') || 0;
    document.getElementById('opsi_exhaust').value = urlParams.get('exhaust') || 0;
    
    updatePdfData();
};

function hitungTrapesiumKotak() {
    let wasteValue = parseFloat(document.getElementById('tk_waste').value) || 5;
    
    // Ambil nilai opsi tambahan
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca')?.value) || 0;
    let opsiPenangkal = parseInt(document.getElementById('opsi_penangkal')?.value) || 0;
    let opsiExhaust = parseInt(document.getElementById('opsi_exhaust')?.value) || 0;
    
    // ===== AMBIL NILAI RANGKA & LANTAI KERJA DARI DROPDOWN =====
    let rangka = document.getElementById('tk_rangka')?.value || 'Baja Ringan';
    let lantaiKerja = document.getElementById('tk_lantai_kerja')?.value || 'Plywood 9 mm';
    
    let data = {
        luas_atap: parseFloat(document.getElementById('tk_luas_atap').value) || 0,
        sudut: parseFloat(document.getElementById('tk_sudut').value) || 0,
        panjang_starter: parseFloat(document.getElementById('tk_starter').value) || 0,
        panjang_nok_jurai: parseFloat(document.getElementById('tk_nok_jurai').value) || 0,
        panjang_flashing: parseFloat(document.getElementById('tk_flashing').value) || 0,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: opsiDinding,
        opsi_kaca: opsiKaca,
        opsi_penangkal: opsiPenangkal,
        opsi_exhaust: opsiExhaust,
        produk_atap_id: document.getElementById('tk_produk_atap').value,
        underlayer_id: document.getElementById('tk_underlayer').value,
        starter_produk_id: document.getElementById('tk_starter_produk').value,
        rangka: rangka,
        lantai_kerja: lantaiKerja,
        waste: wasteValue
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama terlebih dahulu!');
        return;
    }
    
    let btn = event?.target;
    let originalText = btn ? btn.innerHTML : 'Menghitung...';
    if (btn) {
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
    }
    
    // ===== URL UNTUK SKYSHIELD =====
    let url = '/boq/skyshield/hitung';
    
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            tk_results = data.results;
            renderTable(tk_results);
            document.getElementById('tk_hasil').classList.remove('hidden');
            document.getElementById('tk_hasil').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            updateTotal();
            updatePdfData();
        } else {
            alert('Error: ' + (data.message || 'Gagal menghitung'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan server');
    })
    .finally(() => {
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
}

function renderTable(results) {
    let container = document.getElementById('tk_table');
    let html = `<table><thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
    let total = 0;
    results.forEach(item => {
        total += item.total_harga || 0;
        html += `<tr>
            <td><strong>${item.nama_produk}</strong></td>
            <td><span class="badge-area">${item.area}</span></td>
            <td style="text-align:right; font-weight:500;">${item.qty}</td>
            <td style="text-align:right; color:#718096;">${item.satuan}</td>
            <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
        </tr>`;
    });
    html += `</tbody></table>`;
    container.innerHTML = html;
    document.getElementById('tk_grandTotal').innerHTML = `Rp ${total.toLocaleString()}`;
}

function updateTotal() {
    let total = tk_results.reduce((s, i) => s + (i.total_harga || 0), 0);
    document.getElementById('tk_totalKeseluruhan').innerHTML = `Rp ${total.toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_panjang_atas').value = document.getElementById('tk_panjang_atas').value;
    document.getElementById('pdf_panjang_bawah').value = document.getElementById('tk_panjang_bawah').value;
    document.getElementById('pdf_tinggi').value = document.getElementById('tk_tinggi').value;
    document.getElementById('pdf_sudut').value = document.getElementById('tk_sudut').value;
    document.getElementById('pdf_luas_atap').value = document.getElementById('tk_luas_atap').value;
    document.getElementById('pdf_starter').value = document.getElementById('tk_starter').value;
    document.getElementById('pdf_nok_jurai').value = document.getElementById('tk_nok_jurai').value;
    document.getElementById('pdf_flashing').value = document.getElementById('tk_flashing').value;
    document.getElementById('pdf_waste').value = document.getElementById('tk_waste').value;
    document.getElementById('pdf_hasil').value = JSON.stringify(tk_results);
    document.getElementById('pdf_grand_total').value = document.getElementById('tk_grandTotal').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
    
    // Opsi Tambahan    document.getElementById('pdf_opsi_dinding').value = document.getElementById('opsi_dinding').value || 0;
    document.getElementById('pdf_opsi_kaca').value = document.getElementById('opsi_kaca').value || 0;
    document.getElementById('pdf_opsi_penangkal').value = document.getElementById('opsi_penangkal').value || 0;
    document.getElementById('pdf_opsi_exhaust').value = document.getElementById('opsi_exhaust').value || 0;
}
</script>
@endsection