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
        background: #0c2340;
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
        background: #1a365d;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(12, 35, 64, 0.2);
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
    
    .table-container td {
        padding: 6px 10px;
        border-bottom: 1px solid #edf2f7;
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
        background: #0c2340;
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
        background: linear-gradient(135deg, #0c2340 0%, #1a365d 50%, #2a4a7f 100%);
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

    .group-header {
        background: #f0f4f8;
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
        border-left: 3px solid #2b6cb0;
    }
    
    .group-header-aksesoris {
        border-left: 3px solid #38a169;
    }
    
    .group-header-additional {
        border-left: 3px solid #e53e3e;
    }

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

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #a0aec0;
        font-size: 13px;
    }

    /* Style untuk auto-select nok */
    .nok-auto-select {
        background: #f0fdf4 !important;
        border-color: #86efac !important;
    }
    
    .nok-auto-select:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1) !important;
    }
    
    .nok-type-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }

    .nok-type-indicator.u {
        background: #dbeafe;
        color: #1e40af;
    }

    .nok-type-indicator.v {
        background: #fce7f3;
        color: #9d174d;
    }

    .nok-type-indicator.bulat {
        background: #fef3c7;
        color: #92400e;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-main">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-1">BOQ - FLEXI ROOF Limas + Pelana</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material FLEXI ROOF untuk atap kombinasi Limas + Pelana</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">FLEXI ROOF</span>
            </div>
        </div>
    </div>

    <!-- ===== NOTES ===== -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Petunjuk Pengisian BOQ FLEXI ROOF - LIMAS + PELANA
        </div>
        <ul class="notes-list">
            <li>
                <span class="bullet">•</span>
                <span>Pastikan data luas atap dari setiap bagian sudah benar.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Pilih jenis <strong>Tape Roof Nok</strong> (U / V / Bulat), maka <strong>Nok Tutup</strong> akan otomatis menyesuaikan.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>FLEXI ROOF</strong> menggunakan sistem perhitungan yang telah disesuaikan dengan spesifikasi produk.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>Hal yang perlu diperhatikan:</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jarak usuk per <strong>61 cm</strong> pakai <strong>Plywood minimal 12 mm</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jarak usuk per <strong>40.5 cm</strong> pakai <strong>Plywood minimal 9 mm</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Pemakaian underlayer <strong>self adhesive</strong> direkomendasikan</span>
            </li>
        </ul>
    </div>

    <!-- Data Perhitungan (Readonly) -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">📐 Data Perhitungan</h3>
                    <p class="section-subtitle">Data luas atap dari perhitungan sebelumnya</p>
                </div>
                <span class="badge-section">READONLY</span>
            </div>
        </div>
        <div class="section-body">
            <!-- Bagian 1: Limasan -->
            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                <div class="text-xs font-semibold text-gray-600 mb-2">Bagian 1 - Limasan</div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="nok_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bagian 2: Pelana -->
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs font-semibold text-gray-600 mb-2">Bagian 2 - Pelana</div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="nok_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total -->
            <div class="bg-blue-50 rounded-lg p-3 mt-3 border border-blue-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="data-box" style="background: #dbeafe;">
                        <label style="color: #1e40af;">Total Luas Atap</label>
                        <div class="value" style="color: #1e3a5f;">
                            <input type="number" id="total_luas_atap" class="input-field" style="border: none; padding: 0; background: transparent; font-weight: 700;" readonly>
                            <span style="font-size: 11px; color: #1e40af; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box" style="background: #dbeafe;">
                        <label style="color: #1e40af;">Total Starter</label>
                        <div class="value" style="color: #1e3a5f;">
                            <input type="number" id="total_starter" class="input-field" style="border: none; padding: 0; background: transparent; font-weight: 700;" readonly>
                            <span style="font-size: 11px; color: #1e40af; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="background: #dbeafe;">
                        <label style="color: #1e40af;">Total Flashing</label>
                        <div class="value" style="color: #1e3a5f;">
                            <input type="number" id="total_flashing" class="input-field" style="border: none; padding: 0; background: transparent; font-weight: 700;" readonly>
                            <span style="font-size: 11px; color: #1e40af; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="background: #dbeafe;">
                        <label style="color: #1e40af;">Total Nok & Jurai</label>
                        <div class="value" style="color: #1e3a5f;">
                            <input type="number" id="total_nok_jurai" class="input-field" style="border: none; padding: 0; background: transparent; font-weight: 700;" readonly>
                            <span style="font-size: 11px; color: #1e40af; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== OPSI TAMBAHAN ===== -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">🔧 Opsi Tambahan</h3>
                    <p class="section-subtitle">Masukkan panjang atap yang berbatasan dengan elemen lain</p>
                </div>
                <span class="badge-section">OPSIONAL</span>
            </div>
        </div>
        <div class="section-body">
            <div class="opsi-tambahan-grid">
                <div class="opsi-item">
                    <label>
                        🪟 Flashing Kaca
                        <span class="opsi-desc">(panjang flashing untuk kaca)</span>
                    </label>
                    <input type="number" 
                           id="opsi_kaca" 
                           step="0.1" 
                           min="0"
                           placeholder="0"
                           value="0">
                    <span class="opsi-satuan">meter</span>
                </div>
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
                        🏭 Cerobong Asap
                        <span class="opsi-desc">(jumlah cerobong asap)</span>
                    </label>
                    <input type="number" 
                           id="opsi_cerobong" 
                           step="1" 
                           min="0"
                           placeholder="0"
                           value="{{ $opsiCerobong ?? 0 }}">
                    <span class="opsi-satuan">unit</span>
                </div>
                <div class="opsi-item">
                    <label>
                        ⚡ Penangkal Petir
                        <span class="opsi-desc">(panjang instalasi)</span>
                    </label>
                    <input type="number" 
                           id="opsi_penangkal" 
                           step="0.1" 
                           min="0"
                           placeholder="0"
                           value="{{ $opsiPenangkal ?? 0 }}">
                    <span class="opsi-satuan">meter</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Produk & Hitung Material -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">🏷️ Pilih Produk & Hitung Material</h3>
                    <p class="section-subtitle">Pilih produk atap yang akan digunakan</p>
                </div>
                <span class="badge-section">UTAMA</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">TAPE ROOF NOK <span class="text-red-500">*</span></label>
                    <select id="nok_dropdown" class="input-field" required onchange="updateNokOptions()">
                        <option value="">Pilih Jenis Nok</option>
                        @foreach($nokOptions ?? [] as $nok)
                            <option value="{{ $nok->id }}" data-tipe="{{ $nok->productTipe->kode_tipe ?? 'U' }}">
                                {{ $nok->nama_produk }} 
                            </option>
                        @endforeach
                    </select>
                    <div id="nok_selected_info" class="text-[9px] text-gray-400 mt-1">
                        <span class="nok-type-indicator u" id="nok_type_badge" style="display:none;">Tipe: U</span>
                        <span class="text-gray-400">* Nok Tutup otomatis menyesuaikan</span>
                    </div>
                </div>

                <!-- TAMPILAN NOK TUTUP (READONLY / AUTO) -->
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                        NOK TUTUP
                    </label>
                    <div>
                        <input type="text" id="nok_tutup_display" 
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 nok-auto-select" 
                            readonly 
                            placeholder="Pilih Nok terlebih dahulu">
                        <input type="hidden" id="nok_tutup_id" value="">
                    </div>
                    <p class="text-[9px] text-gray-400 mt-1">
                        <span id="nok_tutup_status">⏳ Menunggu pemilihan Nok</span>
                    </p>
                </div>

                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                    <select id="rangka" class="input-field">
                        <option value="Baja Ringan" selected>Baja Ringan</option>
                        <option value="Baja Berat">Baja Berat</option>
                        <option value="Beton">Beton</option>
                        <option value="Kayu">Kayu</option>
                    </select>
                </div>
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
            
            <button onclick="hitungMaterial()" class="btn-primary">
                🏠 Hitung Material
            </button>
            
            <div id="hasilMaterial" class="mt-4 hidden">
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material</h4>
                    <div class="table-container" id="tableMaterial"></div>
                    <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal">Rp 0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL KESELURUHAN -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
                <p class="text-gray-400 text-[10px]">Limas + Pelana FLEXI ROOF (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="{{ route('boq.flexiroof.limas-pelana.export-pdf') }}" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Limas + Pelana FLEXI ROOF">
            <input type="hidden" name="brand" value="FLEXI ROOF">
            <input type="hidden" name="luas_atap" id="pdf_luas_atap">
            <input type="hidden" name="starter" id="pdf_starter">
            <input type="hidden" name="nok_jurai" id="pdf_nok_jurai">
            <input type="hidden" name="flashing" id="pdf_flashing">
            <input type="hidden" name="sudut" id="pdf_sudut">
            <input type="hidden" name="waste" id="pdf_waste">
            <input type="hidden" name="hasil" id="pdf_hasil">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            
            <input type="hidden" name="opsi_kaca" id="pdf_opsi_kaca">
            <input type="hidden" name="opsi_dinding" id="pdf_opsi_dinding">
            <input type="hidden" name="opsi_cerobong" id="pdf_opsi_cerobong">
            <input type="hidden" name="opsi_penangkal" id="pdf_opsi_penangkal">
            
            <input type="hidden" name="lantai_kerja" id="pdf_lantai_kerja">
            <input type="hidden" name="rangka" id="pdf_rangka">
            <input type="hidden" name="detail_results" id="pdf_detail_results">
            
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <input type="hidden" name="luas_atap_1" id="pdf_luas_atap_1">
            <input type="hidden" name="starter_1" id="pdf_starter_1">
            <input type="hidden" name="flashing_1" id="pdf_flashing_1">
            <input type="hidden" name="nok_1" id="pdf_nok_1">
            
            <input type="hidden" name="luas_atap_2" id="pdf_luas_atap_2">
            <input type="hidden" name="starter_2" id="pdf_starter_2">
            <input type="hidden" name="flashing_2" id="pdf_flashing_2">
            <input type="hidden" name="nok_2" id="pdf_nok_2">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF
            </button>
        </form>
    </div>
</div>

<script>
    // Data mapping dari server
    const nokMapping = @json($nokMapping ?? []);

    // Fungsi untuk update Nok options
    function updateNokOptions() {
        const nokDropdown = document.getElementById('nok_dropdown');
        const selectedOption = nokDropdown.options[nokDropdown.selectedIndex];
        const nokId = nokDropdown.value;
        
        const nokTutupDisplay = document.getElementById('nok_tutup_display');
        const nokTutupId = document.getElementById('nok_tutup_id');
        const nokTutupStatus = document.getElementById('nok_tutup_status');
        const nokTypeBadge = document.getElementById('nok_type_badge');
        
        if (!nokId || !nokMapping[nokId]) {
            // Reset semua
            nokTutupDisplay.value = '';
            nokTutupId.value = '';
            nokTutupStatus.innerHTML = '⏳ Menunggu pemilihan Nok';
            nokTutupDisplay.className = 'w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700';
            nokTypeBadge.style.display = 'none';
            return;
        }
        
        const data = nokMapping[nokId];
        const tipe = data.tipe || 'U';
        
        // Update badge tipe
        nokTypeBadge.textContent = 'Tipe: ' + tipe;
        nokTypeBadge.className = 'nok-type-indicator ' + tipe.toLowerCase();
        nokTypeBadge.style.display = 'inline-block';
        
        // Update Nok Tutup
        if (data.nokTutupId) {
            nokTutupDisplay.value = data.nokTutupName;
            nokTutupId.value = data.nokTutupId;
            nokTutupStatus.innerHTML = '✅ <span class="text-green-600">Otomatis terpilih</span>';
            nokTutupDisplay.className = 'w-full px-3 py-2 text-sm border border-green-300 rounded-lg bg-green-50 text-gray-700 nok-auto-select';
        } else {
            nokTutupDisplay.value = '❌ Tidak tersedia untuk tipe ' + tipe;
            nokTutupId.value = '';
            nokTutupStatus.innerHTML = '❌ <span class="text-red-600">Tidak tersedia</span>';
            nokTutupDisplay.className = 'w-full px-3 py-2 text-sm border border-red-300 rounded-lg bg-red-50 text-gray-700';
        }
    }

    // Jalankan saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(updateNokOptions, 200);
    });

    let results = [];

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Bagian 1 (Limasan)
        document.getElementById('luas_atap_1').value = parseFloat(urlParams.get('luas_atap_1') || 0).toFixed(2);
        document.getElementById('starter_1').value = parseFloat(urlParams.get('starter_1') || 0).toFixed(2);
        document.getElementById('flashing_1').value = parseFloat(urlParams.get('flashing_1') || 0).toFixed(2);
        document.getElementById('nok_1').value = parseFloat(urlParams.get('nok_1') || 0).toFixed(2);
        
        // Bagian 2 (Pelana)
        document.getElementById('luas_atap_2').value = parseFloat(urlParams.get('luas_atap_2') || 0).toFixed(2);
        document.getElementById('starter_2').value = parseFloat(urlParams.get('starter_2') || 0).toFixed(2);
        document.getElementById('flashing_2').value = parseFloat(urlParams.get('flashing_2') || 0).toFixed(2);
        document.getElementById('nok_2').value = parseFloat(urlParams.get('nok_2') || 0).toFixed(2);
        
        // Total
        let totalLuas = parseFloat(document.getElementById('luas_atap_1').value) + 
                         parseFloat(document.getElementById('luas_atap_2').value);
        document.getElementById('total_luas_atap').value = totalLuas.toFixed(2);
        
        let totalStarter = parseFloat(document.getElementById('starter_1').value) + 
                           parseFloat(document.getElementById('starter_2').value);
        document.getElementById('total_starter').value = totalStarter.toFixed(2);
        
        let totalFlashing = parseFloat(document.getElementById('flashing_1').value) + 
                            parseFloat(document.getElementById('flashing_2').value);
        document.getElementById('total_flashing').value = totalFlashing.toFixed(2);
        
        let totalNok = parseFloat(document.getElementById('nok_1').value) + 
                       parseFloat(document.getElementById('nok_2').value);
        document.getElementById('total_nok_jurai').value = totalNok.toFixed(2);
        
        document.getElementById('opsi_kaca').value = urlParams.get('opsi_kaca') || 0;
        document.getElementById('opsi_dinding').value = urlParams.get('opsi_dinding') || 0;
        document.getElementById('opsi_cerobong').value = urlParams.get('opsi_cerobong') || 0;
        document.getElementById('opsi_penangkal').value = urlParams.get('opsi_penangkal') || 0;
        
        // Update Nok options setelah data terisi
        setTimeout(updateNokOptions, 300);
        
        updatePdfData();
    };

    function hitungMaterial() {
        let btn = event.target;
        let originalText = btn.innerHTML;
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
        
        let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
        
        let opsiKaca = parseFloat(document.getElementById('opsi_kaca')?.value) || 0;
        let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
        let opsiCerobong = parseFloat(document.getElementById('opsi_cerobong')?.value) || 0;
        let opsiPenangkal = parseFloat(document.getElementById('opsi_penangkal')?.value) || 0;
        
        let nokDropdown = document.getElementById('nok_dropdown');
        let nokId = nokDropdown ? nokDropdown.value : '';
        let nokTutupId = document.getElementById('nok_tutup_id')?.value || '';
        
        if (!nokId) {
            alert('⚠️ Pilih Tape Roof Nok terlebih dahulu!');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        if (!nokTutupId) {
            alert('⚠️ Nok Tutup tidak tersedia untuk tipe yang dipilih! Silakan pilih Nok lain.');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        let totalLuas = parseFloat(document.getElementById('total_luas_atap').value) || 0;
        let totalStarter = parseFloat(document.getElementById('total_starter').value) || 0;
        let totalNokJurai = parseFloat(document.getElementById('total_nok_jurai').value) || 0;
        let totalFlashing = parseFloat(document.getElementById('total_flashing').value) || 0;
        
        let data = {
            luas_atap: totalLuas,
            panjang_starter: totalStarter,
            panjang_nok_jurai: totalNokJurai,
            panjang_flashing: totalFlashing,
            sudut: 30,
            opsi_kaca: opsiKaca,
            opsi_dinding: opsiDinding,
            opsi_cerobong: opsiCerobong,
            opsi_penangkal: opsiPenangkal,
            nok_id: nokId,
            nok_tutup_id: nokTutupId,
            rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
            lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
            waste: wasteValue
        };
        
        console.log('DATA DIKIRIM:', data);
        
        fetch('{{ route("boq.flexiroof.limas-pelana.hitung") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                results = data.results;
                renderTable(results);
                document.getElementById('hasilMaterial').classList.remove('hidden');
                updatePdfData();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan server: ' + err.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function renderTable(results) {
        let container = document.getElementById('tableMaterial');
        
        let kelompok = {
            'Atap Utama': [],
            'Aksesoris': [],
            'Additional': [],
            'Sistem Pendukung': []
        };
        
        const aksesorisAreas = ['Starter', 'Tape Roof Nok & Jurai', 'Nok Tutup', 'Metal Flashing', 'Paku & Screw'];
        const additionalAreas = ['Wall Flashing', 'Cerobong Asap', 'Penangkal Petir', 'Flashing Kaca'];
        const systemAreas = ['Lantai Kerja', 'Underlayer', 'Screw Plywood'];
        
        results.forEach(item => {
            let area = item.area || '';
            if (area === 'Atap Utama') {
                kelompok['Atap Utama'].push(item);
            } else if (additionalAreas.includes(area)) {
                if (item.qty > 0) {
                    kelompok['Additional'].push(item);
                }
            } else if (systemAreas.includes(area)) {
                kelompok['Sistem Pendukung'].push(item);
            } else if (aksesorisAreas.includes(area)) {
                kelompok['Aksesoris'].push(item);
            } else {
                kelompok['Aksesoris'].push(item);
            }
        });
        
        let html = '';
        let grandTotal = 0;
        
        // ATAP UTAMA
        if (kelompok['Atap Utama'].length > 0) {
            html += `<div class="group-header group-header-atap">🏠 ATAP UTAMA</div>`;
            html += `<table>`;
            kelompok['Atap Utama'].forEach(item => {
                grandTotal += item.total_harga || 0;
                html += `<tr>
                    <td style="width:35%;"><strong>${item.nama_produk}</strong></td>
                    <td style="width:20%;"><span class="badge-area">${item.area}</span></td>
                    <td style="width:15%; text-align:right; font-weight:500;">${item.qty}</td>
                    <td style="width:15%; text-align:right; color:#718096;">${item.satuan}</td>
                    <td style="width:15%; text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
                </tr>`;
            });
            html += `</table>`;
        }
        
        // AKSESORIS
        if (kelompok['Aksesoris'].length > 0) {
            html += `<div class="group-header group-header-aksesoris">🔧 AKSESORIS</div>`;
            html += `<table>`;
            kelompok['Aksesoris'].forEach(item => {
                grandTotal += item.total_harga || 0;
                html += `<tr>
                    <td style="width:35%;"><strong>${item.nama_produk}</strong></td>
                    <td style="width:20%;"><span class="badge-area">${item.area}</span></td>
                    <td style="width:15%; text-align:right; font-weight:500;">${item.qty}</td>
                    <td style="width:15%; text-align:right; color:#718096;">${item.satuan}</td>
                    <td style="width:15%; text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
                </tr>`;
            });
            html += `</table>`;
        }
        
        // ADDITIONAL
        if (kelompok['Additional'].length > 0) {
            html += `<div class="group-header group-header-additional">➕ ADDITIONAL</div>`;
            html += `<table>`;
            kelompok['Additional'].forEach(item => {
                grandTotal += item.total_harga || 0;
                html += `<tr>
                    <td style="width:35%;"><strong>${item.nama_produk}</strong></td>
                    <td style="width:20%;"><span class="badge-area">${item.area}</span></td>
                    <td style="width:15%; text-align:right; font-weight:500;">${item.qty}</td>
                    <td style="width:15%; text-align:right; color:#718096;">${item.satuan}</td>
                    <td style="width:15%; text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
                </tr>`;
            });
            html += `</table>`;
        }
        
        // SISTEM PENDUKUNG
        if (kelompok['Sistem Pendukung'].length > 0) {
            html += `<div class="group-header group-header-aksesoris" style="border-left-color: #805ad5;">⚙️ SISTEM PENDUKUNG</div>`;
            html += `<table>`;
            kelompok['Sistem Pendukung'].forEach(item => {
                grandTotal += item.total_harga || 0;
                html += `<tr>
                    <td style="width:35%;"><strong>${item.nama_produk}</strong></td>
                    <td style="width:20%;"><span class="badge-area">${item.area}</span></td>
                    <td style="width:15%; text-align:right; font-weight:500;">${item.qty}</td>
                    <td style="width:15%; text-align:right; color:#718096;">${item.satuan}</td>
                    <td style="width:15%; text-align:right; font-weight:600; color:#2d3748;">Rp ${(item.total_harga || 0).toLocaleString()}</td>
                </tr>`;
            });
            html += `</table>`;
        }
        
        if (html === '') {
            html = `<div class="empty-state">Belum ada data material</div>`;
        }
        
        container.innerHTML = html;
        document.getElementById('grandTotal').innerHTML = `Rp ${grandTotal.toLocaleString()}`;
        document.getElementById('totalKeseluruhan').innerHTML = `Rp ${grandTotal.toLocaleString()}`;
    }

    function updatePdfData() {
        document.getElementById('pdf_hasil').value = JSON.stringify(results);
        document.getElementById('pdf_luas_atap').value = document.getElementById('total_luas_atap').value;
        document.getElementById('pdf_starter').value = document.getElementById('total_starter').value;
        document.getElementById('pdf_nok_jurai').value = document.getElementById('total_nok_jurai').value;
        document.getElementById('pdf_flashing').value = document.getElementById('total_flashing').value;
        document.getElementById('pdf_sudut').value = 30;
        document.getElementById('pdf_waste').value = document.getElementById('waste').value;
        document.getElementById('pdf_grand_total').value = document.getElementById('grandTotal').innerText;
        
        document.getElementById('pdf_opsi_kaca').value = document.getElementById('opsi_kaca')?.value || 0;
        document.getElementById('pdf_opsi_dinding').value = document.getElementById('opsi_dinding')?.value || 0;
        document.getElementById('pdf_opsi_cerobong').value = document.getElementById('opsi_cerobong')?.value || 0;
        document.getElementById('pdf_opsi_penangkal').value = document.getElementById('opsi_penangkal')?.value || 0;
        
        document.getElementById('pdf_lantai_kerja').value = document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm';
        document.getElementById('pdf_rangka').value = document.getElementById('rangka')?.value || 'Baja Ringan';
        document.getElementById('pdf_detail_results').value = JSON.stringify(results);
        
        document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
        document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
        
        document.getElementById('pdf_luas_atap_1').value = document.getElementById('luas_atap_1').value;
        document.getElementById('pdf_starter_1').value = document.getElementById('starter_1').value;
        document.getElementById('pdf_flashing_1').value = document.getElementById('flashing_1').value;
        document.getElementById('pdf_nok_1').value = document.getElementById('nok_1').value;
        
        document.getElementById('pdf_luas_atap_2').value = document.getElementById('luas_atap_2').value;
        document.getElementById('pdf_starter_2').value = document.getElementById('starter_2').value;
        document.getElementById('pdf_flashing_2').value = document.getElementById('flashing_2').value;
        document.getElementById('pdf_nok_2').value = document.getElementById('nok_2').value;
    }
</script>
@endsection