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
    /* Group Header */
.group-header {
    padding: 8px 12px;
    border-radius: 6px;
    margin-top: 8px;
    font-weight: 600;
    font-size: 12px;
    color: #2d3748;
}
.group-header:first-child {
    margin-top: 0;
}
.group-header-atap {
    background: #ebf8ff;
    border-left: 3px solid #2b6cb0;
}
.group-header-aksesoris {
    background: #f0fff4;
    border-left: 3px solid #38a169;
}
.group-header-additional {
    background: #fff5f5;
    border-left: 3px solid #e53e3e;
}
.empty-state {
    text-align: center;
    padding: 20px;
    color: #a0aec0;
    font-size: 13px;
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
                <span>Untuk lokasi dengan potensi <strong>angin kencang (&gt;97 km/jam)</strong>, direkomendasikan menggunakan <span class="highlight">Cambridge</span>.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span class="warning-text">⚠️ Jika memaksakan menggunakan produk lain selain Cambridge, maka garansi akan hilang.</span>
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
            <li style="padding-left: 28px;">
                <span>• Pemakaian underlayer <strong>self adhesive</strong> direkomendasikan</span>
            </li>
        </ul>
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
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
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
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($flashing ?? 0, 2) }}">
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

                <!-- ===== OPSI TAMBAHAN BAGIAN 1 ===== -->
                <div class="opsi-tambahan-grid">
                    <div class="opsi-item">
                        <label>
                            📐 Dinding
                            <span class="opsi-desc">(panjang atap yang berbatasan dinding)</span>
                        </label>
                        <input type="number" 
                               id="opsi_dinding_1" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiDinding1 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            🪟 Kaca
                            <span class="opsi-desc">(panjang area kaca/genteng kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca_1" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiKaca1 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            ⚡ Penangkal Petir
                            <span class="opsi-desc">(jumlah titik)</span>
                        </label>
                        <input type="number" 
                               id="opsi_penangkal_1" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiPenangkal1 ?? 0 }}">
                        <span class="opsi-satuan">titik</span>
                    </div>
                </div>
                
                <!-- Pilih Produk -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
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
                    <!-- ===== DROPDOWN STARTER ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Starter</label>
                        <select id="starter_produk" class="input-field">
                            <option value="">Pilih Starter</option>
                            @foreach($starters ?? [] as $starter)
                                <option value="{{ $starter->id }}">{{ $starter->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- ===== DROPDOWN STRUKTUR RANGKA ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="rangka" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <!-- ===== DROPDOWN LANTAI KERJA ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Lantai Kerja</label>
                        <select id="lantai_kerja" class="input-field">
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
                <div class="grid grid-cols-2 md:grid-cols-2 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Dinding</label>
                        <div class="value">
                            <input type="number" id="luas_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($luasDinding ?? 0, 2) }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
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

                <!-- ===== OPSI TAMBAHAN BAGIAN 2 ===== -->
                <div class="opsi-tambahan-grid">
                    <div class="opsi-item">
                        <label>
                            📐 Dinding Tambahan
                            <span class="opsi-desc">(panjang dinding tambahan)</span>
                        </label>
                        <input type="number" 
                               id="opsi_dinding_2" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiDinding2 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            🪟 Kaca
                            <span class="opsi-desc">(panjang area kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca_2" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiKaca2 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            ⚡ Penangkal Petir
                            <span class="opsi-desc">(jumlah titik)</span>
                        </label>
                        <input type="number" 
                               id="opsi_penangkal_2" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiPenangkal2 ?? 0 }}">
                        <span class="opsi-satuan">titik</span>
                    </div>
                </div>
                
                <!-- Pilih Produk Dinding -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
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
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Starter (Dinding)</label>
                        <select id="starter_produk_dinding" class="input-field">
                            <option value="">Pilih Starter</option>
                            @foreach($starters ?? [] as $starter)
                                <option value="{{ $starter->id }}">{{ $starter->nama_produk }}</option>
                            @endforeach
                        </select>
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
            
            <!-- Opsi Atap -->
            <input type="hidden" name="bagian1[opsi][dinding]" id="pdf_opsi_dinding_1">
            <input type="hidden" name="bagian1[opsi][kaca]" id="pdf_opsi_kaca_1">
            <input type="hidden" name="bagian1[opsi][penangkal]" id="pdf_opsi_penangkal_1">
            
            <!-- Data Dinding -->
            <input type="hidden" name="bagian2[data_perhitungan][luas_dinding]" id="pdf_luas_dinding" value="{{ $luasDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][panjang_dinding]" id="pdf_panjang_dinding" value="{{ $panjangDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][tinggi_dinding]" id="pdf_tinggi_dinding" value="{{ $tinggiDinding ?? 0 }}">
            <input type="hidden" name="bagian2[data_perhitungan][jumlah_sisi]" id="pdf_jumlah_sisi" value="{{ $jumlahSisi ?? 2 }}">
            <input type="hidden" name="bagian2[hasil]" id="pdf_hasil_dinding">
            <input type="hidden" name="bagian2[total]" id="pdf_total_dinding">
            <input type="hidden" name="waste_dinding" id="pdf_waste_dinding">
            
            <!-- Opsi Dinding -->
            <input type="hidden" name="bagian2[opsi][dinding]" id="pdf_opsi_dinding_2">
            <input type="hidden" name="bagian2[opsi][kaca]" id="pdf_opsi_kaca_2">
            <input type="hidden" name="bagian2[opsi][penangkal]" id="pdf_opsi_penangkal_2">
            
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
    let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
    
    // Ambil nilai opsi tambahan
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding_1')?.value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca_1')?.value) || 0;
    let opsiPenangkal = parseInt(document.getElementById('opsi_penangkal_1')?.value) || 0;
    
    // ===== AMBIL NILAI RANGKA & LANTAI KERJA DARI DROPDOWN =====
    let rangka = document.getElementById('rangka')?.value || 'Baja Ringan';
    let lantaiKerja = document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm';
    
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap').value) || 0,
        sudut: parseFloat(document.getElementById('sudut').value) || 0,
        panjang_starter: parseFloat(document.getElementById('starter').value) || 0,
        panjang_nok_jurai: parseFloat(document.getElementById('nok_jurai').value) || 0,
        panjang_flashing: parseFloat(document.getElementById('flashing').value) || 0,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: opsiDinding,
        opsi_kaca: opsiKaca,
        opsi_penangkal: opsiPenangkal,
        opsi_exhaust: 0,
        produk_atap_id: document.getElementById('produk_atap').value,
        underlayer_id: document.getElementById('underlayer').value,
        starter_produk_id: document.getElementById('starter_produk').value,
        rangka: rangka,
        lantai_kerja: lantaiKerja,
        waste: wasteValue
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama terlebih dahulu!');
        return;
    }
    
    fetch('{{ route("boq.iko-atap.hitung") }}', {
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
    let wasteValue = parseFloat(document.getElementById('waste_dinding').value) || 5;
    
    // Ambil nilai opsi tambahan dinding
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding_2')?.value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca_2')?.value) || 0;
    let opsiPenangkal = parseInt(document.getElementById('opsi_penangkal_2')?.value) || 0;
    
    // ===== AMBIL NILAI STARTER DINDING =====
    let starterProdukId = document.getElementById('starter_produk_dinding')?.value || '';
    
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_dinding').value) || 0,
        sudut: 0,
        panjang_starter: 0,
        panjang_nok_jurai: 0,
        panjang_flashing: 0,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: opsiDinding,
        opsi_kaca: opsiKaca,
        opsi_penangkal: opsiPenangkal,
        opsi_exhaust: 0,
        produk_atap_id: document.getElementById('produk_dinding').value,
        underlayer_id: '',
        starter_produk_id: starterProdukId,
        rangka: 'Baja Ringan',
        lantai_kerja: 'Plywood 9 mm',
        waste: wasteValue
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama untuk dinding terlebih dahulu!');
        return;
    }
    
    fetch('{{ route("boq.iko-atap.hitung") }}', {
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
    if (!container) return;
    
    // ===== KELOMPOKKAN HASIL =====
    let kelompok = {
        'Atap Utama': [],
        'Aksesoris': [],
        'Additional': []
    };
    
    const aksesorisAreas = ['Starter', 'Nok & Jurai', 'Underlayer', 'Paku & Screw', 'Flashing', 'Metal Flashing', 'Shingle Stick', 'Talang Jurai'];
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca', 'Penangkal Petir', 'Ventilasi Exhaust'];
    
    results.forEach(item => {
        if (item.area === 'Atap Utama') {
            kelompok['Atap Utama'].push(item);
        } else if (additionalAreas.includes(item.area)) {
            // ONLY SHOW IF QTY > 0
            if (item.qty > 0) {
                kelompok['Additional'].push(item);
            }
        } else if (aksesorisAreas.includes(item.area)) {
            kelompok['Aksesoris'].push(item);
        } else {
            kelompok['Aksesoris'].push(item);
        }
    });
    
    let html = '';
    let grandTotal = 0;
    
    // ===== RENDER ATAP UTAMA =====
    if (kelompok['Atap Utama'].length > 0) {
        html += `<div class="group-header group-header-atap">🏠 ATAP UTAMA</div>`;
        html += `<table>`;
        html += `<thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
        kelompok['Atap Utama'].forEach(item => {
            grandTotal += item.total_harga || 0;
            html += `<tr>
                <td><strong>${item.nama_produk}</strong></td>
                <td><span class="badge-area">${item.area}</span></td>
                <td style="text-align:right; font-weight:500;">${item.qty}</td>
                <td style="text-align:right; color:#718096;">${item.satuan}</td>
                <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    }
    
    // ===== RENDER AKSESORIS =====
    if (kelompok['Aksesoris'].length > 0) {
        html += `<div class="group-header group-header-aksesoris">🔧 AKSESORIS</div>`;
        html += `<table>`;
        html += `<thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
        kelompok['Aksesoris'].forEach(item => {
            grandTotal += item.total_harga || 0;
            html += `<tr>
                <td><strong>${item.nama_produk}</strong></td>
                <td><span class="badge-area">${item.area}</span></td>
                <td style="text-align:right; font-weight:500;">${item.qty}</td>
                <td style="text-align:right; color:#718096;">${item.satuan}</td>
                <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    }
    
    // ===== RENDER ADDITIONAL (hanya jika ada qty > 0) =====
    if (kelompok['Additional'].length > 0) {
        html += `<div class="group-header group-header-additional">➕ ADDITIONAL</div>`;
        html += `<table>`;
        html += `<thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
        kelompok['Additional'].forEach(item => {
            grandTotal += item.total_harga || 0;
            html += `<tr>
                <td><strong>${item.nama_produk}</strong></td>
                <td><span class="badge-area">${item.area}</span></td>
                <td style="text-align:right; font-weight:500;">${item.qty}</td>
                <td style="text-align:right; color:#718096;">${item.satuan}</td>
                <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    }
    
    if (html === '') {
        html = `<div class="empty-state">Belum ada data material</div>`;
    }
    
    container.innerHTML = html;
    if (grandTotalEl) grandTotalEl.innerHTML = `Rp ${grandTotal.toLocaleString()}`;
}

function updateTotal() {
    let totalAtap = hasilAtap.reduce((s, i) => s + (i.total_harga || 0), 0);
    let totalDinding = hasilDinding.reduce((s, i) => s + (i.total_harga || 0), 0);
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${(totalAtap + totalDinding).toLocaleString()}`;
}

function updatePdfData() {
    // Waste
    document.getElementById('pdf_waste_atap').value = document.getElementById('waste').value;
    document.getElementById('pdf_waste_dinding').value = document.getElementById('waste_dinding').value;
    
    // Hasil Atap
    document.getElementById('pdf_hasil_atap').value = JSON.stringify(hasilAtap);
    document.getElementById('pdf_total_atap').value = document.getElementById('grandTotalAtap').innerText;
    
    // Opsi Atap
    document.getElementById('pdf_opsi_dinding_1').value = document.getElementById('opsi_dinding_1').value || 0;
    document.getElementById('pdf_opsi_kaca_1').value = document.getElementById('opsi_kaca_1').value || 0;
    document.getElementById('pdf_opsi_penangkal_1').value = document.getElementById('opsi_penangkal_1').value || 0;
    
    // Hasil Dinding
    document.getElementById('pdf_hasil_dinding').value = JSON.stringify(hasilDinding);
    document.getElementById('pdf_total_dinding').value = document.getElementById('grandTotalDinding').innerText;
    
    // Opsi Dinding
    document.getElementById('pdf_opsi_dinding_2').value = document.getElementById('opsi_dinding_2').value || 0;
    document.getElementById('pdf_opsi_kaca_2').value = document.getElementById('opsi_kaca_2').value || 0;
    document.getElementById('pdf_opsi_penangkal_2').value = document.getElementById('opsi_penangkal_2').value || 0;
    
    // Grand Total
    document.getElementById('pdf_grand_total').value = document.getElementById('totalKeseluruhan').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection