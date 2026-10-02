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
    
    .section-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
    }
    
    .section-body {
        padding: 20px;
    }
    
    .btn-primary {
        background: #2d3748;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .btn-primary:hover {
        background: #1a202c;
    }
    
    .btn-pdf {
        background: #e53e3e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .btn-pdf:hover {
        background: #c53030;
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
    
    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .section-subtitle {
        font-size: 11px;
        color: #718096;
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
    
    .notes-container .notes-list li .warning-text {
        color: #991b1b;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 13px;
    }
    
    .result-table .product-name {
        font-weight: 500;
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
    
    .result-table .text-center {
        text-align: center;
    }
    
    .result-table .text-right {
        text-align: right;
    }
    
    .group-header {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 12px;
        margin: 8px 0 4px 0;
        border-radius: 4px;
        background: #f1f4f9;
        color: #1a1a2e;
        border-left: 3px solid #1a1a2e;
    }
    
    .group-header-atap {
        background: #e8edf5;
        border-left-color: #0f3460;
        color: #1a3a5c;
    }
    
    .group-header-aksesoris {
        background: #e8f5ed;
        border-left-color: #38a169;
        color: #1a5c3a;
    }
    
    .group-header-additional {
        background: #f5ede8;
        border-left-color: #e67e22;
        color: #5c3a1a;
    }
    
    .group-header-sistem {
        background: #f5e8ed;
        border-left-color: #e53e3e;
        color: #5c1a3a;
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
    
    /* ===== DATA GEOMETRIK ===== */
    .data-geometrik {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 4px 0;
    }
    
    .data-geometrik .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .data-geometrik .row:last-child {
        border-bottom: none;
    }
    
    .data-geometrik .row .label {
        font-size: 12px;
        font-weight: 500;
        color: #4a5568;
    }
    
    .data-geometrik .row .value {
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .data-geometrik .row .value .unit {
        font-weight: 400;
        color: #94a3b8;
        margin-left: 2px;
    }
    
    .data-geometrik .row .value input[readonly] {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
        width: 70px;
        padding: 0;
        text-align: right;
    }

    /* Pilih Material - 1 KOLOM */
    .pilih-material {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 4px 0;
    }
    
    .pilih-material .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .pilih-material .row:last-child {
        border-bottom: none;
    }
    
    .pilih-material .label {
        font-size: 12px;
        font-weight: 500;
        color: #4a5568;
        min-width: 140px;
    }
    
    .pilih-material .value {
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        flex: 1;
        text-align: right;
    }
    
    .pilih-material .value select,
    .pilih-material .value input {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 12px;
        background: white;
        width: 100%;
        max-width: 280px;
    }
    
    .pilih-material .value select:focus,
    .pilih-material .value input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    
    .pilih-material .value .waste-inline {
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: flex-end;
    }
    .pilih-material .value .waste-inline input {
        width: 80px;
        text-align: center;
    }
    .pilih-material .value .waste-inline span {
        font-size: 12px;
        color: #94a3b8;
    }
    
    /* Opsi Tambahan - Indent */
    .opsi-tambahan {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 2px solid #e2e8f0;
    }
    
    .opsi-tambahan .opsi-title {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }
    
    .opsi-tambahan .opsi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 20px;
        padding-left: 24px;
    }
    
    .opsi-tambahan .opsi-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .opsi-tambahan .opsi-item label {
        font-size: 10px !important;
        font-weight: 500;
        color: #4a5568;
    }
    
    .opsi-tambahan .opsi-item input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
        transition: all 0.2s;
        background: white;
    }
    
    .opsi-tambahan .opsi-item input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    
    .opsi-tambahan .opsi-item .satuan {
        font-size: 10px;
        color: #a0aec0;
        margin-top: 2px;
    }
    
    @media (max-width: 768px) {
        .pilih-material .row {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .pilih-material .value {
            text-align: left;
            width: 100%;
        }
        .pilih-material .value select,
        .pilih-material .value input {
            max-width: 100%;
        }
        .pilih-material .value .waste-inline {
            justify-content: flex-start;
        }
        .opsi-tambahan .opsi-grid {
            grid-template-columns: 1fr;
            padding-left: 0;
        }
    }
    
    .flex { display: flex; }
    .items-center { align-items: center; }
    .gap-3 { gap: 12px; }
    .mt-1 { margin-top: 4px; }
    .mt-4 { margin-top: 16px; }
    .mb-2 { margin-bottom: 8px; }
    .hidden { display: none; }
    .border-t { border-top: 1px solid #e2e8f0; }
    .pt-4 { padding-top: 16px; }
    .text-right { text-align: right; }
    .text-base { font-size: 16px; }
    .font-semibold { font-weight: 600; }
    .text-gray-700 { color: #374151; }
    .text-xs { font-size: 12px; }
    .text-red-500 { color: #ef4444; }
    .text-gray-200 { color: #e5e7eb; }
    .text-[10px] { font-size: 10px; }
    .font-medium { font-weight: 500; }
    .text-xl { font-size: 20px; }
    .mb-1 { margin-bottom: 4px; }
    .space-y-6 > * + * { margin-top: 24px; }
    
    .btn-master {
        background: #0f3460;
        transition: all 0.2s;
    }
    .btn-master:hover {
        background: #1a1a2e;
    }
    
    .btn-master-pdf {
        background: #e53e3e;
    }
    .btn-master-pdf:hover {
        background: #c53030;
    }
    
    .total-box {
        background: #1a1a2e;
        border-radius: 12px;
        padding: 20px 24px;
        color: white;
    }
    .total-box .label {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        font-weight: 400;
    }
    .total-box .amount {
        font-size: 28px;
        font-weight: 700;
        color: white;
    }
    
    .waste-inline {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .waste-inline input {
        width: 60px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 12px;
        background: white;
    }
    .waste-inline input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }
    .waste-inline span {
        font-size: 12px;
        color: #94a3b8;
    }

    /* HEADER BRAND */
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

    /* Sembunyikan Underlayer & Lantai Kerja secara default */
    #underlayer_container,
    #lantai_kerja_container {
        display: none !important;
    }

    #underlayer_container.show,
    #lantai_kerja_container.show {
        display: flex !important;
    }

    /* FORCED NON-EXPOSE BADGE */
    .forced-badge {
        display: inline-block;
        background: #dc2626;
        color: white;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 10px;
        margin-left: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sistem-label-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }

    /* COVERAGE INFO */
    .coverage-info {
        font-size: 10px;
        color: #6b7280;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .coverage-info .badge {
        display: inline-block;
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 600;
    }

    .coverage-info .badge.auto {
        background: #d1fae5;
        color: #065f46;
    }

    .coverage-recommendation {
        font-size: 10px;
        color: #6b7280;
        margin-top: 4px;
        padding: 4px 10px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        display: inline-block;
    }

    .coverage-recommendation strong {
        color: #16a34a;
    }

    /* SISTEM WARNING */
    #sistem_warning {
        transition: all 0.3s ease;
        font-size: 11px;
    }

    #sistem_warning .warning-icon {
        font-size: 14px;
    }

    select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f7fafc !important;
    }
</style>

<div class="space-y-6">

    <!-- ===== NOTES / PEMBERITAHUAN ===== -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Petunjuk Pengisian BOQ
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
                <span><strong style="color: #dc2626;">⚠️ PERHATIAN PENTING:</strong> Jika sudut atap < 30°, sistem akan <strong style="color: #dc2626;">OTOMATIS</strong> menggunakan <strong style="color: #dc2626;">NON-EXPOSE</strong> dan TIDAK BISA diubah ke EXPOSE</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>Coverage (daun/m²) dapat dipilih sendiri:</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Untuk Expose, disarankan pilih <strong>7 atau 8 daun/m²</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Untuk Non-Expose, disarankan pilih <strong>7 daun/m²</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jika sudut > 40° Non-Expose, bisa pilih <strong>6 atau 7 daun/m²</strong></span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>Hal yang perlu diperhatikan:</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jarak usuk per <strong>61 cm</strong> pakai <strong>Plywood minimal 12 mm</strong>, tidak disarankan pakai 9 mm</span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jarak usuk per <strong>40.5 cm</strong> pakai <strong>Plywood minimal 9 mm</strong></span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Pemakaian underlayer <strong>self adhesive</strong></span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Jika atap bertemu dinding, silahkan input panjangnya di bagian <strong>"Opsi Tambahan"</strong> → Dinding = ___ m</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Jika atap bertemu kaca, silahkan input panjangnya di bagian <strong>"Opsi Tambahan"</strong> → Kaca = ___ m</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>Jurai</strong> dan <strong>Nok Bulat</strong> WAJIB dipilih dari dropdown.</span>
            </li>
        </ul>
    </div>

    <!-- Data Geometrik -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">Data Geometrik</h3>
                    <p class="section-subtitle">Data luas atap dari perhitungan sebelumnya</p>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="data-geometrik">
                <div class="row">
                    <span class="label">Luas Atap</span>
                    <span class="value">
                        <input type="text" id="luas_atap" readonly>
                        <span class="unit">m²</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sudut</span>
                    <span class="value">
                        <input type="text" id="sudut" readonly>
                        <span class="unit">°</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Starter</span>
                    <span class="value">
                        <input type="text" id="panjang_starter" readonly>
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Jurai</span>
                    <span class="value">
                        <input type="text" id="panjang_jurai" readonly>
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Flashing</span>
                    <span class="value">
                        <input type="text" id="panjang_flashing" readonly>
                        <span class="unit">m</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Material -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">Pilih Material</h3>
                    <p class="section-subtitle">Pilih material dan aksesoris atap</p>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="pilih-material">
                <!-- Produk Atap Utama -->
                <div class="row">
                    <span class="label">Produk Atap Utama</span>
                    <span class="value">
                        <select id="produk_atap_id">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>

                <!-- Jurai -->
                <div class="row">
                    <span class="label">Jurai <span style="color:#e53e3e;">*</span></span>
                    <span class="value">
                        <select id="jurai_dropdown">
                            <option value="">Pilih Jurai</option>
                            @foreach($juraiOptions ?? [] as $jurai)
                                <option value="{{ $jurai->id }}">{{ $jurai->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>

                <!-- Nok Bulat -->
                <div class="row">
                    <span class="label">Nok Bulat <span style="color:#e53e3e;">*</span></span>
                    <span class="value">
                        <select id="nok_bulat_dropdown">
                            <option value="">Pilih Nok Bulat</option>
                            @foreach($nokBulatOptions ?? [] as $nok)
                                <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>

                <!-- SISTEM PEMASANGAN -->
                <div class="row">
                    <span class="label">
                        <span class="sistem-label-wrapper">
                            Sistem Pemasangan
                            <span id="forced_badge" class="forced-badge hidden">FORCED NON-EXPOSE</span>
                        </span>
                    </span>
                    <span class="value">
                        <select id="sistem_pemasangan" onchange="validateAndToggleFields()">
                            <option value="">Pilih Sistem Pemasangan</option>
                            <option value="expose">Expose</option>
                            <option value="non-expose">Non-Expose</option>
                        </select>
                    </span>
                </div>

                <!-- ===== WARNING SISTEM ===== -->
                <div id="sistem_warning" class="hidden" style="margin-top:4px; padding:6px 12px; background:#fef3c7; border:1px solid #fcd34d; border-radius:6px; font-size:11px; color:#92400e; display:flex; align-items:center; gap:8px;">
                    <span>⚠️</span>
                    <span id="sistem_warning_text">Sudut atap < 30°, sistem otomatis NON-EXPOSE (tidak bisa diubah)</span>
                </div>

                <!-- ===== COVERAGE ===== -->
                <div class="row">
                    <span class="label">Coverage <span style="color:#e53e3e;">*</span></span>
                    <span class="value">
                        <select id="coverage" required>
                            <option value="">Pilih Coverage</option>
                            <option value="8">8 daun / m²</option>
                            <option value="7">7 daun / m²</option>
                            <option value="6">6 daun / m²</option>
                        </select>
                    </span>
                </div>
                <!-- Underlayer -->
                <div class="row" id="underlayer_container">
                    <span class="label">Underlayer</span>
                    <span class="value">
                        <select id="underlayer_id">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>

                <!-- Struktur Rangka -->
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka">
                            @foreach($rangkaOptions as $option)
                                <option value="{{ $option }}" {{ $option == 'Baja Ringan' ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>

                <!-- Lantai Kerja -->
                <div class="row" id="lantai_kerja_container">
                    <span class="label">Lantai Kerja</span>
                    <span class="value">
                        <select id="lantai_kerja">
                           <option value="">Pilih Lantai Kerja</option>
                            @foreach($lantaiKerjaOptions as $lantaiKerja)
                                <option value="{{ $lantaiKerja->id }}">{{ $lantaiKerja->nama_produk }}</option>
                            @endforeach   
                        </select>
                    </span>
                </div>

                <!-- Waste -->
                <div class="row">
                    <span class="label">Waste</span>
                    <span class="value">
                        <div class="waste-inline">
                            <input type="number" id="waste" step="1" value="5">
                            <span>%</span>
                        </div>
                    </span>
                </div>
            </div>

            <div class="opsi-tambahan">
                <div class="opsi-title">Opsi Tambahan</div>
                <div class="opsi-grid">
                    <div class="opsi-item">
                        <label>Dinding</label>
                        <input type="number" id="opsi_dinding" step="0.1" min="0" placeholder="0" value="0">
                        <span class="satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>Kaca</label>
                        <input type="number" id="opsi_kaca" step="0.1" min="0" placeholder="0" value="0">
                        <span class="satuan">meter</span>
                    </div>
                </div>
            </div>

            <button onclick="hitungMaterial()" class="btn-primary btn-master" style="margin-top:16px;">
                Hitung Material
            </button>

            <div id="hasilMaterial" class="mt-4 hidden">
                <div class="border-t pt-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material</h4>
                    <div class="table-container" id="tableMaterial"></div>
                    <div class="grand-total-minimal">
                        <span class="label">Grand Total</span>
                        <span class="amount" id="grandTotal">Rp 0</span>
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
            </div>
            <div class="text-right">
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <button onclick="exportToPDF()" class="btn-pdf btn-master-pdf" style="margin-top:16px; width:100%;">
            Export PDF
        </button>
    </div>
</div>

<script>
    let results = [];

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        document.getElementById('luas_atap').value = urlParams.get('luas_atap') || 0;
        document.getElementById('sudut').value = urlParams.get('sudut') || 0;
        document.getElementById('panjang_starter').value = urlParams.get('panjang_starter') || 0;
        document.getElementById('panjang_jurai').value = urlParams.get('panjang_jurai') || 0;
        document.getElementById('panjang_flashing').value = urlParams.get('panjang_flashing') || 0;
        document.getElementById('opsi_dinding').value = urlParams.get('dinding') || 0;
        document.getElementById('opsi_kaca').value = urlParams.get('kaca') || 0;

        let sistemPemasangan = urlParams.get('sistem_pemasangan') || 'expose';
        let sudut = parseFloat(document.getElementById('sudut').value) || 0;
        
        if (sudut < 30) {
            sistemPemasangan = 'non-expose';
        }
        
        document.getElementById('sistem_pemasangan').value = sistemPemasangan;
        
        toggleFields();
        updateCoverage();
        checkForcedNonExpose();
    };

    function toggleFields() {
        let sistem = document.getElementById('sistem_pemasangan')?.value || '';
        let underlayerContainer = document.getElementById('underlayer_container');
        let lantaiKerjaContainer = document.getElementById('lantai_kerja_container');
        
        if (sistem === 'expose') {
            if (underlayerContainer) {
                underlayerContainer.style.display = 'none';
                underlayerContainer.classList.remove('show');
            }
            if (lantaiKerjaContainer) {
                lantaiKerjaContainer.style.display = 'none';
                lantaiKerjaContainer.classList.remove('show');
            }
        } else if (sistem === 'non-expose') {
            if (underlayerContainer) {
                underlayerContainer.style.display = 'flex';
                underlayerContainer.classList.add('show');
            }
            if (lantaiKerjaContainer) {
                lantaiKerjaContainer.style.display = 'flex';
                lantaiKerjaContainer.classList.add('show');
            }
        } else {
            if (underlayerContainer) {
                underlayerContainer.style.display = 'none';
                underlayerContainer.classList.remove('show');
            }
            if (lantaiKerjaContainer) {
                lantaiKerjaContainer.style.display = 'none';
                lantaiKerjaContainer.classList.remove('show');
            }
        }
    }

    function validateAndToggleFields() {
        let sudut = parseFloat(document.getElementById('sudut')?.value) || 0;
        let sistemSelect = document.getElementById('sistem_pemasangan');
        let selectedValue = sistemSelect.value;
        
        if (sudut < 30 && selectedValue === 'expose') {
            sistemSelect.value = 'non-expose';
            alert('⚠️ Sudut atap ' + sudut + '° (< 30°), tidak bisa menggunakan EXPOSE. Sistem otomatis NON-EXPOSE.');
        }
        
        toggleFields();
        updateCoverage();
        checkForcedNonExpose();
    }

    function checkForcedNonExpose() {
        let sudut = parseFloat(document.getElementById('sudut')?.value) || 0;
        let sistemSelect = document.getElementById('sistem_pemasangan');
        let warningEl = document.getElementById('sistem_warning');
        let warningText = document.getElementById('sistem_warning_text');
        let forcedBadge = document.getElementById('forced_badge');
        
        if (sudut < 30) {
            sistemSelect.value = 'non-expose';
            sistemSelect.disabled = true;
            sistemSelect.style.opacity = '0.7';
            sistemSelect.style.cursor = 'not-allowed';
            
            if (warningEl) {
                warningEl.style.display = 'flex';
                if (warningText) {
                    warningText.textContent = '⚠️ Sudut atap ' + sudut + '° (< 30°), sistem otomatis NON-EXPOSE (tidak bisa diubah)';
                }
            }
            
            if (forcedBadge) {
                forcedBadge.style.display = 'inline-block';
            }
            
            toggleFields();
        } else {
            sistemSelect.disabled = false;
            sistemSelect.style.opacity = '1';
            sistemSelect.style.cursor = 'default';
            
            if (warningEl) {
                warningEl.style.display = 'none';
            }
            
            if (forcedBadge) {
                forcedBadge.style.display = 'none';
            }
        }
    }

    function updateCoverage() {
        let sudut = parseFloat(document.getElementById('sudut')?.value) || 0;
        let sistem = document.getElementById('sistem_pemasangan')?.value || '';
        let coverageStatus = document.getElementById('coverage_status');
        let coverageDesc = document.getElementById('coverage_description');
        let recommendationEl = document.getElementById('coverage_recommendation');
        let recommendationText = document.getElementById('recommendation_text');
        
        let recommendedValue = null;
        let descText = '';
        
        if (sistem === 'expose') {
            if (sudut >= 30 && sudut <= 31) {
                recommendedValue = '8';
                descText = 'Rekomendasi: 8 daun/m² (Expose 30°)';
            } else if (sudut > 30) {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Expose >30°)';
            } else {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Sudut < 30° → Non-Expose)';
            }
        } else if (sistem === 'non-expose') {
            if (sudut > 30 && sudut <= 40) {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Non-Expose 31-40°)';
            } else if (sudut > 40) {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Non-Expose >40°, bisa pilih 6 atau 7)';
            } else if (sudut >= 15 && sudut <= 30) {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Non-Expose 15-30°)';
            } else {
                recommendedValue = '7';
                descText = 'Rekomendasi: 7 daun/m² (Sudut < 15°)';
            }
        } else {
            recommendedValue = null;
            descText = 'Pilih sistem pemasangan terlebih dahulu';
        }
        
        if (coverageStatus) {
            coverageStatus.textContent = '✎ Bebas';
        }
        if (coverageDesc) {
            coverageDesc.textContent = descText || 'Pilih sesuai kebutuhan';
        }
        
        if (recommendedValue && recommendationEl) {
            recommendationEl.style.display = 'block';
            if (recommendationText) {
                recommendationText.textContent = recommendedValue + ' daun/m²';
            }
        } else if (recommendationEl) {
            recommendationEl.style.display = 'none';
        }
    }

    function hitungMaterial() {
        let btn = event.target;
        let originalText = btn.innerHTML;
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
        
        let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
        
        let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
        let opsiKaca = parseFloat(document.getElementById('opsi_kaca')?.value) || 0;
        
        let totalLuas = parseFloat(document.getElementById('luas_atap').value) || 0;
        let sudut = parseFloat(document.getElementById('sudut').value) || 0;
        let totalStarter = parseFloat(document.getElementById('panjang_starter').value) || 0;
        let totalJurai = parseFloat(document.getElementById('panjang_jurai').value) || 0;
        let totalFlashing = parseFloat(document.getElementById('panjang_flashing').value) || 0;
        
        // Validasi Coverage
        let coverage = parseFloat(document.getElementById('coverage')?.value) || 0;
        if (!coverage || coverage <= 0) {
            alert('⚠️ Pilih Coverage (daun/m²) terlebih dahulu!');
            document.getElementById('coverage').focus();
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        // Validasi Jurai & Nok Bulat
        let juraiId = document.getElementById('jurai_dropdown')?.value || '';
        let nokBulatId = document.getElementById('nok_bulat_dropdown')?.value || '';
        
        if (!juraiId) {
            alert('⚠️ Pilih Jurai terlebih dahulu! (wajib)');
            document.getElementById('jurai_dropdown').focus();
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        if (!nokBulatId) {
            alert('⚠️ Pilih Nok Bulat terlebih dahulu! (wajib)');
            document.getElementById('nok_bulat_dropdown').focus();
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        if (totalLuas <= 0) {
            alert('Data luas atap tidak valid! Silakan hitung ulang dari halaman sebelumnya.');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        // Force non-expose jika sudut < 30
        let sistemPemasangan = document.getElementById('sistem_pemasangan')?.value || 'expose';
        if (sudut < 30) {
            sistemPemasangan = 'non-expose';
        }
        
        let data = {
            luas_atap: totalLuas,
            sudut: sudut,
            panjang_starter: totalStarter,
            panjang_jurai: totalJurai,
            panjang_flashing: totalFlashing,
            opsi_dinding: opsiDinding,
            opsi_kaca: opsiKaca,
            produk_atap_id: document.getElementById('produk_atap_id')?.value || '',
            jurai_id: juraiId,
            nok_bulat_id: nokBulatId,
            underlayer_id: document.getElementById('underlayer_id')?.value || '',
            rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
            lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
            sistem_pemasangan: sistemPemasangan,
            coverage: coverage,
            waste: wasteValue
        };
        
        console.log('DATA DIKIRIM:', data);
        
        fetch('{{ route("boq.palmex.hitung", ["model" => "piramid"]) }}', {
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
                document.getElementById('hasilMaterial').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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

        const aksesorisAreas = ['Starter', 'Jurai', 'Nok Bulat', 'Topcap', 'Rail', 'Wind', 'Metal Flashing', 'Screw'];
        const additionalAreas = ['Wall Flashing', 'Flashing Kaca'];
        const systemAreas = ['Underlayer', 'Lantai Kerja', 'Paku & Screw', 'Screw Plywood'];

        results.forEach(item => {
            const area = item.area || '';
            if (area === 'Atap Utama') {
                kelompok['Atap Utama'].push(item);
            } else if (additionalAreas.includes(area) && item.qty > 0) {
                kelompok['Additional'].push(item);
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

        const groupConfig = [
            { key: 'Atap Utama', label: 'ATAP UTAMA', cls: 'group-header-atap' },
            { key: 'Aksesoris', label: 'AKSESORIS', cls: 'group-header-aksesoris' },
            { key: 'Additional', label: 'ADDITIONAL', cls: 'group-header-additional' },
            { key: 'Sistem Pendukung', label: 'SISTEM PENDUKUNG', cls: 'group-header-sistem' }
        ];

        groupConfig.forEach(({ key, label, cls }) => {
            const items = kelompok[key];
            if (items.length === 0) return;

            html += `<div class="group-header ${cls}">📁 ${label}</div>`;
            html += `<table class="result-table">`;
            html += `<thead>
                <tr>
                    <th style="width:5%;text-align:center;">No</th>
                    <th style="width:40%;">Nama Produk</th>
                    <th style="width:20%;text-align:center;">Qty</th>
                    <th style="width:20%;">Satuan</th>
                    <th style="width:15%;text-align:right;">Total</th>
                </tr>
            </thead>`;
            html += `<tbody>`;
            items.forEach((item, index) => {
                grandTotal += item.total_harga || 0;
                html += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="product-name">${item.nama_produk}</td>
                    <td class="qty">${item.qty}</td>
                    <td class="unit">${item.satuan}</td>
                    <td class="text-right">Rp ${(item.total_harga || 0).toLocaleString()}</td>
                </tr>`;
            });
            html += `</tbody></table>`;
        });

        if (html === '') {
            html = '<div class="empty-state">Belum ada data material</div>';
        }

        container.innerHTML = html;
        document.getElementById('grandTotal').innerHTML = `Rp ${grandTotal.toLocaleString()}`;
        document.getElementById('totalKeseluruhan').innerHTML = `Rp ${grandTotal.toLocaleString()}`;
    }

function exportToPDF() {
    let data = {
        luas_atap: document.getElementById('luas_atap')?.value || 0,
        sudut: document.getElementById('sudut')?.value || 0,
        starter: document.getElementById('panjang_starter')?.value || 0,
        jurai: document.getElementById('panjang_jurai')?.value || 0,               // panjang jurai (angka)
        flashing: document.getElementById('panjang_flashing')?.value || 0,
        waste: document.getElementById('waste')?.value || 5,
        coverage: document.getElementById('coverage')?.value || 0,
        produk_atap: document.getElementById('produk_atap_id')?.selectedOptions[0]?.text || '',
        jurai_nama: document.getElementById('jurai_dropdown')?.selectedOptions[0]?.text || '',  // nama produk jurai
        nok_bulat: document.getElementById('nok_bulat_dropdown')?.selectedOptions[0]?.text || '',
            underlayer: document.getElementById('underlayer_id')?.selectedOptions[0]?.text || '',
            rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
            lantai_kerja: document.getElementById('lantai_kerja')?.selectedOptions[0]?.text || '',
            sistem_pemasangan: document.getElementById('sistem_pemasangan')?.value || 'expose',
            grand_total: document.getElementById('grandTotal')?.innerText || 'Rp 0',
            opsi_dinding: document.getElementById('opsi_dinding')?.value || 0,
            opsi_kaca: document.getElementById('opsi_kaca')?.value || 0,
            results: results
        };
        
        fetch('{{ route("boq.palmex.export-pdf", ["model" => "piramid"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(html => {
            let win = window.open();
            win.document.write(html);
            win.document.close();
        })
        .catch(error => {
            alert('Gagal export PDF: ' + error.message);
        });
    }
</script>
@endsection