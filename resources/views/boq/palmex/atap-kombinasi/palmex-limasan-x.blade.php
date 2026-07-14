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

    .required-star {
        color: #e53e3e;
        margin-left: 2px;
    }
    
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
        border-radius: 12px 12px 0 0;
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

    .opsi-tambahan-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
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

    .grid-cols-5 {
        grid-template-columns: repeat(5, 1fr);
    }

    .grid-cols-6 {
        grid-template-columns: repeat(6, 1fr);
    }

    .grid-cols-7 {
        grid-template-columns: repeat(7, 1fr);
    }

    @media (max-width: 768px) {
        .grid-cols-5 {
            grid-template-columns: repeat(2, 1fr);
        }
        .grid-cols-6 {
            grid-template-columns: repeat(2, 1fr);
        }
        .grid-cols-7 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* ===== RESULT SECTION STYLE ===== */
    .result-section {
        margin-bottom: 20px;
    }

    .result-section .section-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 6px 6px 0 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .result-table {
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
    }

    .result-table th {
        text-align: left;
        padding: 8px 12px;
        font-weight: 500;
        color: #94a3b8;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
    }

    .result-table td {
        padding: 7px 12px;
        border-bottom: 1px solid #f1f4f9;
        color: #1a1a2e;
    }

    .result-table tr:last-child td {
        border-bottom: none;
    }

    .result-table .product-name {
        font-weight: 500;
    }

    .result-table .area-tag {
        font-size: 10px;
        color: #94a3b8;
    }

    .result-table .qty {
        font-weight: 600;
        text-align: center;
    }

    .result-table .unit {
        color: #94a3b8;
        font-size: 11px;
        text-align: left;
    }

    .result-table .total {
        font-weight: 500;
        text-align: right;
    }

    .result-table .total-empty {
        color: #cbd5e1;
        font-weight: 400;
        text-align: right;
    }

    .result-table .text-center {
        text-align: center;
    }

    .result-table .text-right {
        text-align: right;
    }

    .grand-total-minimal {
        background: #1a1a2e;
        border-radius: 8px;
        padding: 14px 20px;
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .grand-total-minimal .label {
        color: rgba(255,255,255,0.6);
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .grand-total-minimal .amount {
        color: white;
        font-size: 20px;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* HEADER ELEGANT MINIMALIS */
    .header-brand {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 16px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .header-brand::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    
    .header-brand .brand-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }
    
    .header-brand .brand-icon.palmex {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
    
    .header-brand .brand-name {
        font-size: 20px;
        font-weight: 600;
        color: white;
        letter-spacing: -0.3px;
    }
    
    .header-brand .brand-sub {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        font-weight: 400;
        margin-top: 2px;
    }
    
    .header-brand .brand-badge {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 500;
        color: rgba(255,255,255,0.7);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon palmex">P</div>
                <div>
                    <div class="brand-name">BOQ - PALMEX Limasan X</div>
                    <div class="brand-sub">Hitung kebutuhan material atap Limasan X (3 Tingkat)</div>
                </div>
            </div>
            <div class="brand-badge">PALMEX</div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- ===== NOTES / PEMBERITAHUAN ===== -->
        <div class="notes-container">
            <div class="notes-title">
                <span class="icon">📋</span> Petunjuk Pengisian BOQ - PALMEX
            </div>
            <ul class="notes-list">
                <li>
                    <span class="bullet">•</span>
                    <span>Cek lebih detail apakah atap yang akan dipasang itu <strong>Expose</strong> atau <strong>Non-Expose</strong>.</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span><strong>Expose</strong> = Kemiringan minimal <span class="highlight">30°</span> (≥ 30 derajat)</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span><strong>Non-Expose</strong> = Kemiringan minimal <span class="highlight">15°</span> (≥ 15 derajat)</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span>Cek lebih detail apakah ada atap yang bertemu langsung dengan <strong>dinding</strong> atau <strong>kaca</strong>.</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span>Jika bertemu dinding atau kaca, silahkan input berapa panjang/area pertemuannya di bagian <strong>"Opsi Tambahan"</strong>.</span>
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
                    <span>• Pemakaian underlayer <strong>self adhesive</strong> (IKO Stormshield)</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span><strong>Jurai</strong> dan <strong>Nok Atas</strong> WAJIB dipilih dari dropdown.</span>
                </li>
            </ul>
        </div>
        
        <!-- ===== BAGIAN 1: LIMASAN X (TOTAL KESELURUHAN) ===== -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🏠 Limasan X</h3>
                        <p class="section-subtitle">Data perhitungan total dari 3 tingkat atap limasan</p>
                    </div>
                    <span class="badge-section">TOTAL</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan TOTAL -->
                <div class="grid grid-cols-2 md:grid-cols-7 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="total_luas" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($totalLuas ?? 0, 2, '.', '') }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="total_sudut" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="total_starter" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($totalStarter ?? 0, 2, '.', '') }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Jurai</label>
                        <div class="value">
                            <input type="number" id="total_jurai" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($totalJurai ?? 0, 2, '.', '') }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok Atas</label>
                        <div class="value">
                            <input type="number" id="total_nok_atas" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($totalNokAtas ?? 0, 2, '.', '') }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Talang Jurai</label>
                        <div class="value">
                            <input type="number" id="total_talang_jurai" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($talangJurai ?? 0, 2, '.', '') }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="total_flashing" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ number_format($totalFlashing ?? 0, 2, '.', '') }}">
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
                </div>

                <!-- ===== SISTEM PEMASANGAN ===== -->
                <div class="mt-4">
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Sistem Pemasangan</label>
                    <select id="sistem_pemasangan" class="input-field" onchange="toggleFields()">
                        <option value="expose">Expose</option>
                        <option value="non-expose">Non-Expose</option>
                    </select>
                </div>

                <!-- Pilihan Material -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ===== DROPDOWN JURAI (WAJIB) ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                            Jurai <span class="required-star">*</span>
                        </label>
                        <select id="jurai_dropdown" class="input-field" required>
                            <option value="">Pilih Jurai</option>
                            @foreach($juraiOptions ?? [] as $jurai)
                                <option value="{{ $jurai->id }}">{{ $jurai->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ===== DROPDOWN NOK ATAS (WAJIB) ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                            Nok Atas <span class="required-star">*</span>
                        </label>
                        <select id="nok_atas_dropdown" class="input-field" required>
                            <option value="">Pilih Nok Atas</option>
                            @foreach($nokAtasOptions ?? [] as $nok)
                                <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="underlayer_container">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if($sudut <= 30 && $sudut > 0)
                            <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut }}° (≤ 30°), disarankan menggunakan underlayer khusus.</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="rangka" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <div id="lantai_kerja_container">
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
                
                <button onclick="hitungTotal()" class="btn-primary">
                    Hitung Material Limasan X
                </button>
                
                <div id="hasilTotal" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Limasan X</h4>
                        <div class="table-container" id="tableTotal"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal">Rp 0</div>
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
                <p class="text-gray-400 text-[10px]">Limasan X (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/palmex/atap-kombinasi/limasan-x/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Limasan X">
            <input type="hidden" name="brand" value="PALMEX">
            
            <input type="hidden" name="waste" id="pdf_waste">
            
            <input type="hidden" name="total[luas_atap]" id="pdf_luas">
            <input type="hidden" name="total[sudut]" id="pdf_sudut">
            <input type="hidden" name="total[starter]" id="pdf_starter">
            <input type="hidden" name="total[jurai]" id="pdf_jurai">
            <input type="hidden" name="total[nok_atas]" id="pdf_nok_atas">
            <input type="hidden" name="total[flashing]" id="pdf_flashing">
            <input type="hidden" name="total[talang_jurai]" id="pdf_talang_jurai">
            <input type="hidden" name="total[hasil]" id="pdf_hasil">
            <input type="hidden" name="total[grand_total]" id="pdf_grand_total">
            
            <input type="hidden" name="opsi[dinding]" id="pdf_opsi_dinding">
            <input type="hidden" name="opsi[kaca]" id="pdf_opsi_kaca">
            
            <input type="hidden" name="jurai_id" id="pdf_jurai_id">
            <input type="hidden" name="nok_atas_id" id="pdf_nok_atas_id">
            
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF Custom
            </button>
        </form>
    </div>
</div>

<script>
let resultsTotal = [];

function toggleFields() {
    let sistem = document.getElementById('sistem_pemasangan').value;
    let underlayerContainer = document.getElementById('underlayer_container');
    let lantaiKerjaContainer = document.getElementById('lantai_kerja_container');
    
    if (sistem === 'expose') {
        if (underlayerContainer) underlayerContainer.style.display = 'none';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'none';
    } else {
        if (underlayerContainer) underlayerContainer.style.display = 'block';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'block';
    }
}

window.onload = function() {
    document.getElementById('sistem_pemasangan').value = 'expose';
    toggleFields();
    updatePdfData();
};

function renderResultTable(results, grandTotalEl) {
    let container = document.getElementById('tableTotal');
    if (!container) return;
    
    if (!results || results.length === 0) {
        container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
        if (grandTotalEl) grandTotalEl.innerHTML = 'Rp 0';
        return;
    }
    
    const groups = {
        'Atap Utama': { label: 'KELOMPOK ATAP UTAMA', items: [] },
        'Aksesoris': { label: 'AKSESORIS', items: [] },
        'Additional': { label: 'ADDITIONAL', items: [] }
    };
    
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca'];
    const aksesorisAreas = ['Starter', 'Nok Atas', 'Jurai', 'Underlayer', 'Lantai Kerja', 'Screw Plywood', 'Topcap', 'Screw', 'Rail', 'Wind', 'Metal Flashing', 'Talang Jurai', 'Flashing', 'Paku & Screw', 'Shingle Stick'];
    
    results.forEach(item => {
        if (item.area === 'Atap Utama') {
            groups['Atap Utama'].items.push(item);
        } else if (additionalAreas.includes(item.area) && item.qty > 0) {
            groups['Additional'].items.push(item);
        } else if (aksesorisAreas.includes(item.area)) {
            groups['Aksesoris'].items.push(item);
        } else {
            groups['Aksesoris'].items.push(item);
        }
    });
    
    let html = '';
    let grandTotal = 0;
    
    const groupKeys = ['Atap Utama', 'Aksesoris', 'Additional'];
    
    groupKeys.forEach(key => {
        const group = groups[key];
        if (group.items.length === 0) return;
        
        const groupTotal = group.items.reduce((sum, item) => sum + (item.total_harga || 0), 0);
        grandTotal += groupTotal;
        
        html += `<div class="result-section">`;
        html += `<div class="section-label">${group.label}</div>`;
        html += `<table class="result-table">
            <thead>
                <tr>
                    <th style="width:35%;">Nama Produk</th>
                    <th style="width:20%;">Area</th>
                    <th style="width:15%;text-align:center;">Qty</th>
                    <th style="width:15%;">Satuan</th>
                    <th style="width:20%;text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>`;
        
        group.items.forEach(item => {
            let totalDisplay = item.total_harga > 0 ? `<span class="total">Rp ${item.total_harga.toLocaleString()}</span>` : `<span class="total-empty">-</span>`;
            html += `<tr>
                <td class="product-name">${item.nama_produk || '-'}</td>
                <td><span class="area-tag">${item.area || '-'}</span></td>
                <td class="qty text-center">${(item.qty || 0).toLocaleString()}</td>
                <td class="unit">${item.satuan || '-'}</td>
                <td class="text-right">${totalDisplay}</td>
            </tr>`;
        });
        
        html += `</tbody></table></div>`;
    });
    
    html += `<div class="grand-total-minimal">
        <span class="label">Grand Total</span>
        <span class="amount">Rp ${grandTotal.toLocaleString()}</span>
    </div>`;
    
    container.innerHTML = html;
    if (grandTotalEl) grandTotalEl.innerHTML = 'Rp ' + grandTotal.toLocaleString();
}

function hitungTotal() {
    let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
    
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding').value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca').value) || 0;
    
    let sistemPemasangan = document.getElementById('sistem_pemasangan').value || 'expose';
    let rangka = document.getElementById('rangka')?.value || 'Baja Ringan';
    let lantaiKerja = document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm';
    
    // ===== AMBIL DARI DROPDOWN =====
    let juraiDropdown = document.getElementById('jurai_dropdown');
    let juraiId = juraiDropdown ? juraiDropdown.value : '';
    
    let nokAtasDropdown = document.getElementById('nok_atas_dropdown');
    let nokAtasId = nokAtasDropdown ? nokAtasDropdown.value : '';
    
    // ===== VALIDASI WAJIB =====
    if (!juraiId) {
        alert('⚠️ Pilih Jurai terlebih dahulu! (wajib)');
        document.getElementById('jurai_dropdown').focus();
        return;
    }
    if (!nokAtasId) {
        alert('⚠️ Pilih Nok Atas terlebih dahulu! (wajib)');
        document.getElementById('nok_atas_dropdown').focus();
        return;
    }
    
    let data = {
        luas_atap: parseFloat(document.getElementById('total_luas').value) || 0,
        sudut: parseFloat(document.getElementById('total_sudut').value) || 0,
        panjang_starter: parseFloat(document.getElementById('total_starter').value) || 0,
        panjang_jurai: parseFloat(document.getElementById('total_jurai').value) || 0,
        panjang_nok_atas: parseFloat(document.getElementById('total_nok_atas').value) || 0,
        panjang_flashing: parseFloat(document.getElementById('total_flashing').value) || 0,
        panjang_talang_jurai: parseFloat(document.getElementById('total_talang_jurai').value) || 0,
        panjang_wall_flashing: opsiDinding,
        produk_atap_id: document.getElementById('produk_atap').value,
        jurai_id: juraiId,
        nok_atas_id: nokAtasId,
        underlayer_id: document.getElementById('underlayer').value,
        rangka: rangka,
        lantai_kerja: lantaiKerja,
        waste: wasteValue,
        opsi_dinding: opsiDinding,
        opsi_kaca: opsiKaca,
        sistem_pemasangan: sistemPemasangan
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih produk atap utama terlebih dahulu!');
        return;
    }
    
    if (sistemPemasangan === 'expose') {
        data.underlayer_id = data.underlayer_id || '';
    } else {
        if (!data.underlayer_id) {
            alert('Pilih underlayer terlebih dahulu!');
            return;
        }
    }
    
    let btn = event?.target || document.querySelector('#hasilTotal .btn-primary');
    let originalText = btn ? btn.innerHTML : 'Menghitung...';
    if (btn) {
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
    }
    
    fetch('/boq/palmex/atap-kombinasi/limasan-x/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            resultsTotal = data.results;
            renderResultTable(resultsTotal, document.getElementById('grandTotal'));
            document.getElementById('hasilTotal').classList.remove('hidden');
            updateTotal();
            updatePdfData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server');
    })
    .finally(() => {
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
}

function updateTotal() {
    let total = resultsTotal.reduce((s, i) => s + (i.total_harga || 0), 0);
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${total.toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_waste').value = document.getElementById('waste').value || 5;
    
    document.getElementById('pdf_luas').value = document.getElementById('total_luas').value || 0;
    document.getElementById('pdf_sudut').value = document.getElementById('total_sudut').value || 0;
    document.getElementById('pdf_starter').value = document.getElementById('total_starter').value || 0;
    document.getElementById('pdf_jurai').value = document.getElementById('total_jurai').value || 0;
    document.getElementById('pdf_nok_atas').value = document.getElementById('total_nok_atas').value || 0;
    document.getElementById('pdf_flashing').value = document.getElementById('total_flashing').value || 0;
    document.getElementById('pdf_talang_jurai').value = document.getElementById('total_talang_jurai').value || 0;
    document.getElementById('pdf_hasil').value = JSON.stringify(resultsTotal);
    document.getElementById('pdf_grand_total').value = document.getElementById('totalKeseluruhan').innerText || 'Rp 0';
    
    document.getElementById('pdf_opsi_dinding').value = document.getElementById('opsi_dinding').value || 0;
    document.getElementById('pdf_opsi_kaca').value = document.getElementById('opsi_kaca').value || 0;
    
    document.getElementById('pdf_jurai_id').value = document.getElementById('jurai_dropdown').value || '';
    document.getElementById('pdf_nok_atas_id').value = document.getElementById('nok_atas_dropdown').value || '';
    
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection