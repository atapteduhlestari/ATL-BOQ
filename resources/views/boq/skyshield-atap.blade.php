@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* ===== STYLE SAMA PERSIS DENGAN KODE 2 ===== */
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
        cursor: pointer;
        user-select: none;
        transition: background 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-header:hover {
        background: #f1f4f9;
    }
    .section-header .toggle-icon {
        font-size: 14px;
        transition: transform 0.3s;
    }
    .section-header .toggle-icon.collapsed {
        transform: rotate(-90deg);
    }
    
    .section-body {
        padding: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .section-body.collapsed {
        max-height: 0 !important;
        padding: 0 20px;
        opacity: 0;
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
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background: #1a202c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 55, 72, 0.2);
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
        transition: all 0.2s;
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
        background: #f3e8ff;
        border-left-color: #7c3aed;
        color: #4c1d95;
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

    /* Data Geometrik Style - ROW */
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
    
    .data-geometrik .label {
        font-size: 12px;
        font-weight: 500;
        color: #4a5568;
        min-width: 180px;
    }
    
    .data-geometrik .value {
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        flex: 1;
        text-align: right;
    }
    
    .data-geometrik .value .unit {
        font-weight: 400;
        color: #94a3b8;
        margin-left: 2px;
    }
    
    .data-geometrik .value input[readonly] {
        border: none;
        background: transparent;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        width: 100px;
        padding: 0;
        text-align: right;
    }

    /* Pilih Material Style - ROW dengan INDENT */
    .pilih-material {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 4px 0;
    }
    
    .pilih-material .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0 6px 24px;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .pilih-material .row:last-child {
        border-bottom: none;
    }
    
    .pilih-material .label {
        font-size: 12px;
        font-weight: 500;
        color: #4a5568;
        min-width: 160px;
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
        max-width: 300px;
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

    /* Sub section title */
    .sub-section-title {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 12px 0 6px 0;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 3px;
        border-left: 3px solid #1a1a2e;
    }

    .sub-section-title .badge {
        font-weight: 400;
        font-size: 10px;
        color: #94a3b8;
        margin-left: 8px;
    }
    
    /* Opsi Tambahan - 2 Kolom */
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
    
    .opsi-tambahan .opsi-item label .opsi-desc {
        font-weight: 400;
        color: #a0aec0;
        font-size: 9px;
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
            padding-left: 12px;
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
        .data-geometrik .row {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }
        .data-geometrik .value {
            text-align: left;
            width: 100%;
        }
        .data-geometrik .label {
            min-width: auto;
        }
        .pilih-material .label {
            min-width: auto;
        }
    }
    
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap-3 { gap: 12px; }
    .gap-4 { gap: 16px; }
    .gap-6 { gap: 24px; }
    .mt-1 { margin-top: 4px; }
    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .mt-4 { margin-top: 16px; }
    .mt-5 { margin-top: 20px; }
    .mb-1 { margin-bottom: 4px; }
    .mb-1-5 { margin-bottom: 6px; }
    .mb-2 { margin-bottom: 8px; }
    .mb-4 { margin-bottom: 16px; }
    .mb-5 { margin-bottom: 20px; }
    .hidden { display: none; }
    .border-t { border-top: 1px solid #e2e8f0; }
    .pt-4 { padding-top: 16px; }
    .text-right { text-align: right; }
    .text-base { font-size: 16px; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-gray-700 { color: #374151; }
    .text-gray-500 { color: #6b7280; }
    .text-gray-400 { color: #9ca3af; }
    .text-gray-200 { color: #e5e7eb; }
    .text-xs { font-size: 12px; }
    .text-[10px] { font-size: 10px; }
    .text-xl { font-size: 20px; }
    .uppercase { text-transform: uppercase; }
    .tracking-wide { letter-spacing: 0.5px; }
    .block { display: block; }
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

    /* Header Brand */
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
    
    .header-brand .brand-icon.skyshield {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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

    /* Required star */
    .required-star {
        color: #e53e3e;
        margin-left: 2px;
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
        font-family: 'Poppins', sans-serif;
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

    select.input-field {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    select.input-field:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f7fafc !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .flex-col {
            flex-direction: column;
        }
        .items-start {
            align-items: flex-start;
        }
        .gap-4 {
            gap: 16px;
        }
    }

    .flex-col { flex-direction: column; }
    .items-start { align-items: flex-start; }
    .relative { position: relative; }
    .z-10 { z-index: 10; }
    .shrink-0 { flex-shrink: 0; }

    /* Sistem warning */
    .sistem_warning {
        transition: all 0.3s ease;
        font-size: 11px;
    }

    /* Bagian hasil */
    .bagian-hasil {
        margin-top: 16px;
        border-top: 1px solid #e2e8f0;
        padding-top: 16px;
    }
    .bagian-hasil .subtotal {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        text-align: right;
    }
    .bagian-hasil .subtotal .label {
        font-weight: 400;
        color: #718096;
        font-size: 12px;
    }

    /* HAPUS notifikasi toast */
    .notification-toast {
        display: none !important;
    }
</style>

<div class="space-y-6">
  
    <!-- ===== NOTES ===== -->
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
                <span>• Jarak usuk per <strong>61 cm</strong> pakai <strong>Plywood minimal 12 mm</strong>, tidak disarankan pakai 9 mm</span>
            </li>
            <li style="padding-left: 28px;">
                <span>• Jarak usuk per <strong>40.5 cm</strong> pakai <strong>Plywood minimal 9 mm</strong></span>
            </li>
              <li style="padding-left: 28px;">
                <span>• Ukuran Flashing menyesuaikan dengan ukuran Lantai Kerja</strong></span>
            </li>
            <li style="padding-left: 32px;">
                <span>• Pemakaian underlayer <strong>self adhesives</strong> direkomendasikan</span>
            </li>
        </ul>
    </div>

    <!-- ===== SECTION 1: DATA GEOMETRIK ===== -->
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('geometrik')">
            <div>
                <h3 class="section-title">Data Geometrik</h3>
                <p class="section-subtitle">Data luas atap dan kemiringan dari perhitungan sebelumnya</p>
            </div>
            <span class="toggle-icon collapsed" id="geometrik_icon">▼</span>
        </div>
        <div class="section-body collapsed" id="geometrik_body">
            
            <!-- Data Geometrik - TANPA HEADER -->
            <div class="data-geometrik">
                <div class="row"><span class="label">Luas Atap</span><span class="value"><input type="text" id="luas_atap" readonly value="0"><span class="unit">m²</span></span></div>
                <div class="row"><span class="label">Sudut Kemiringan</span><span class="value"><input type="text" id="sudut" readonly value="0"><span class="unit">°</span></span></div>
                <div class="row"><span class="label">Panjang Starter</span><span class="value"><input type="text" id="panjang_starter" readonly value="0"><span class="unit">m</span></span></div>
                <div class="row"><span class="label">Nok & Jurai</span><span class="value"><input type="text" id="panjang_nok_jurai" readonly value="0"><span class="unit">m</span></span></div>
                <div class="row"><span class="label">Flashing</span><span class="value"><input type="text" id="panjang_flashing" readonly value="0"><span class="unit">m</span></span></div>
            </div>

        </div>
    </div>

    <!-- ===== SECTION 2: PILIH MATERIAL ===== -->
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('material')">
            <div>
                <h3 class="section-title">Pilih Material</h3>
                <p class="section-subtitle">Pilih material dan aksesoris atap</p>
            </div>
            <span class="toggle-icon" id="material_icon">▼</span>
        </div>
        <div class="section-body" id="material_body">

            <!-- Pilih Material - LANGSUNG TANPA SUB-SECTION TITLE -->
            <div class="pilih-material">
                <div class="row">
                    <span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="produk_atap_id" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
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
                <div class="row">
                    <span class="label">Starter</span>
                    <span class="value">
                        <select id="starter_produk_id">
                            <option value="">Pilih Starter</option>
                            @foreach($starters as $starter)
                                <option value="{{ $starter->id }}">{{ $starter->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
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
                <div class="row">
                    <span class="label">Waste (%)</span>
                    <span class="value">
                        <div class="waste-inline">
                            <input type="number" id="waste" step="1" value="5">
                            <span>%</span>
                        </div>
                    </span>
                </div>
            </div>

            <!-- Opsi Tambahan - 2 Kolom -->
            <div class="opsi-tambahan">
                <div class="opsi-title">Opsi Tambahan</div>
                <div class="opsi-grid">
                    <div class="opsi-item">
                        <label>
                            Dinding
                            <span class="opsi-desc">(panjang atap yang berbatasan dinding)</span>
                        </label>
                        <input type="number" 
                               id="opsi_dinding" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="0">
                        <span class="satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            Kaca
                            <span class="opsi-desc">(panjang area kaca/genteng kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="0">
                        <span class="satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            Penangkal Petir
                            <span class="opsi-desc">(jumlah titik)</span>
                        </label>
                        <input type="number" 
                               id="opsi_penangkal" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="0">
                        <span class="satuan">titik</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            Exhaust
                            <span class="opsi-desc">(jumlah titik ventilasi)</span>
                        </label>
                        <input type="number" 
                               id="opsi_exhaust" 
                               step="1" 
                               min="0"
                               placeholder="0"
                               value="0">
                        <span class="satuan">titik</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Hitung -->
            <button onclick="hitungBOQ()" class="btn-primary btn-master mt-4" style="padding:14px 24px;font-size:14px;font-weight:600;">
                🚀 Hitung Kebutuhan Material
            </button>

            <!-- ===== HASIL MATERIAL ===== -->
            <div class="bagian-hasil" id="hasilBOQ" style="display:none;">
                <div class="table-container" id="boqResultContainer"></div>
                <div class="subtotal">
                    <span class="label">Grand Total :  </span>
                    <span id="grandTotal">Rp 0</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== TOTAL KESELURUHAN + PDF ===== -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
            </div>
            <div class="text-right">
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <button onclick="exportToPDF()" class="btn-pdf btn-master-pdf" id="btnPDF" style="display:none;">
            📄 Export PDF
        </button>
    </div>
</div>

<script>
let currentResults = [];

function toggleSection(section) {
    let body = document.getElementById(section + '_body');
    let icon = document.getElementById(section + '_icon');
    if (!body || !icon) return;
    if (body.classList.contains('collapsed')) {
        body.classList.remove('collapsed');
        icon.textContent = '▼';
        icon.classList.remove('collapsed');
    } else {
        body.classList.add('collapsed');
        icon.textContent = '▶';
        icon.classList.add('collapsed');
    }
}

function getVal(id) {
    let el = document.getElementById(id);
    return el ? el.value : 0;
}

function setVal(id, val) {
    let el = document.getElementById(id);
    if (el) el.value = val;
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    setVal('luas_atap', urlParams.get('luas_atap') || 0);
    setVal('sudut', urlParams.get('sudut') || 0);
    setVal('panjang_starter', urlParams.get('panjang_starter') || 0);
    setVal('panjang_nok_jurai', urlParams.get('panjang_nok_jurai') || 0);
    setVal('panjang_flashing', urlParams.get('panjang_flashing') || 0);
    setVal('panjang_talang_jurai', urlParams.get('panjang_talang_jurai') || 0);
    
    setVal('opsi_dinding', urlParams.get('dinding') || 0);
    setVal('opsi_kaca', urlParams.get('kaca') || 0);
    setVal('opsi_penangkal', urlParams.get('penangkal') || 0);
    setVal('opsi_exhaust', urlParams.get('exhaust') || 0);
});

// ===== RENDER TABLE HASIL SAMA PERSIS DENGAN KODE 2 =====
function renderResultTable(results, containerId) {
    let container = document.getElementById(containerId);
    if (!container) return;
    
    if (!results || results.length === 0) {
        container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
        return 0;
    }
    
    let kelompok = {
        'Atap Utama': [],
        'Aksesoris': [],
        'Additional': [],
        'Sistem Pendukung': []
    };

    const aksesorisAreas = ['Starter', 'Topcap', 'Underlayer', 'Screw', 'Rail', 'Wind', 'Metal Flashing', 'Flashing', 'Paku & Screw', 'Shingle Stick', 'Nok & Jurai', 'Talang Jurai'];
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca', 'Penangkal Petir', 'Ventilasi Exhaust'];
    const sistemPendukungAreas = ['Lantai Kerja', 'Paku & Screw'];

    results.forEach(function(item) {
        const area = item.area || '';
        if (area === 'Atap Utama') {
            kelompok['Atap Utama'].push(item);
        } else if (additionalAreas.includes(area) && item.qty > 0) {
            kelompok['Additional'].push(item);
        } else if (sistemPendukungAreas.includes(area) || area.toLowerCase().includes('lantai') || area.toLowerCase().includes('kerja')) {
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
        { key: 'Atap Utama', label: '📁 ATAP UTAMA', cls: 'group-header-atap' },
        { key: 'Aksesoris', label: '📁 AKSESORIS', cls: 'group-header-aksesoris' },
        { key: 'Additional', label: '📁 ADDITIONAL', cls: 'group-header-additional' },
        { key: 'Sistem Pendukung', label: '📁 SISTEM PENDUKUNG', cls: 'group-header-sistem' }
    ];

    groupConfig.forEach(function(config) {
        const key = config.key;
        const label = config.label;
        const cls = config.cls;
        const items = kelompok[key];
        if (items.length === 0) return;

        html += `<div class="group-header ${cls}">${label}</div>`;
        html += `<table class="result-table">`;
        html += `<thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th style="width:35%;">Nama Produk</th>
                <th style="width:15%;text-align:center;">Qty</th>
                <th style="width:15%;">Satuan</th>
                <th style="width:30%;text-align:right;">Total</th>
            </tr>
        </thead>`;
        html += `<tbody>`;
        items.forEach(function(item, index) {
            grandTotal += item.total_harga || 0;
            html += `<tr>
                <td class="text-center">${index + 1}</td>
                <td class="product-name">${item.nama_produk}</td>
                <td class="qty">${(item.qty || 0).toLocaleString()}</td>
                <td class="unit">${item.satuan || '-'}</td>
                <td class="text-right">Rp ${(item.total_harga || 0).toLocaleString()}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    });

    if (html === '') {
        html = '<div class="empty-state">Belum ada data material</div>';
    }

    container.innerHTML = html;
    return grandTotal;
}

function hitungBOQ() {
    let btn = event?.target;
    if (!btn) {
        btn = document.querySelector('.btn-master');
    }
    
    let originalText = btn ? btn.innerHTML : 'Menghitung...';
    if (btn) {
        btn.innerHTML = '⏳ Menghitung...';
        btn.disabled = true;
    }
    
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca')?.value) || 0;
    let opsiPenangkal = parseInt(document.getElementById('opsi_penangkal')?.value) || 0;
    let opsiExhaust = parseInt(document.getElementById('opsi_exhaust')?.value) || 0;
    
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap')?.value) || 0,
        sudut: parseFloat(document.getElementById('sudut')?.value) || 0,
        panjang_starter: parseFloat(document.getElementById('panjang_starter')?.value) || 0,
        panjang_nok_jurai: parseFloat(document.getElementById('panjang_nok_jurai')?.value) || 0,
        panjang_talang_jurai: parseFloat(document.getElementById('panjang_talang_jurai')?.value) || 0,
        panjang_flashing: parseFloat(document.getElementById('panjang_flashing')?.value) || 0,
        panjang_wall_flashing: opsiDinding,
        produk_atap_id: document.getElementById('produk_atap_id')?.value || '',
        underlayer_id: document.getElementById('underlayer_id')?.value || '',
        starter_produk_id: document.getElementById('starter_produk_id')?.value || '',
        rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
        lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
        waste: parseFloat(document.getElementById('waste')?.value) || 5,
        opsi_dinding: opsiDinding,
        opsi_kaca: opsiKaca,
        opsi_penangkal: opsiPenangkal,
        opsi_exhaust: opsiExhaust
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih Produk Atap Utama terlebih dahulu!');
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
        return;
    }
    
    if (data.luas_atap <= 0) {
        alert('Data luas atap tidak valid! Silakan hitung ulang dari halaman sebelumnya.');
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
        return;
    }
    
    fetch('/boq/skyshield/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            currentResults = data.results;
            let grandTotal = renderResultTable(currentResults, 'boqResultContainer');
            
            let hasilEl = document.getElementById('hasilBOQ');
            if (hasilEl) {
                hasilEl.style.display = 'block';
            }
            
            let grandTotalEl = document.getElementById('grandTotal');
            if (grandTotalEl) {
                grandTotalEl.textContent = 'Rp ' + grandTotal.toLocaleString();
            }
            
            let totalEl = document.getElementById('totalKeseluruhan');
            if (totalEl) {
                totalEl.textContent = 'Rp ' + grandTotal.toLocaleString();
            }
            
            let btnPDF = document.getElementById('btnPDF');
            if (btnPDF) {
                btnPDF.style.display = 'block';
            }
            
            updatePdfData(grandTotal);
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    })
    .finally(function() {
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
}

function updatePdfData(grandTotal) {
    // Helper untuk set nilai dengan aman
    function setPdfValue(id, value) {
        var el = document.getElementById(id);
        if (el) el.value = value;
    }
    
    function getInputValue(id) {
        var el = document.getElementById(id);
        return el ? el.value : 0;
    }
    
    // Set semua nilai PDF
    setPdfValue('pdf_hasil', JSON.stringify(currentResults));
    setPdfValue('pdf_luas_atap', getInputValue('luas_atap'));
    setPdfValue('pdf_sudut', getInputValue('sudut'));
    setPdfValue('pdf_starter', getInputValue('panjang_starter'));
    setPdfValue('pdf_nok_jurai', getInputValue('panjang_nok_jurai'));
    setPdfValue('pdf_flashing', getInputValue('panjang_flashing'));
    setPdfValue('pdf_talang_jurai', getInputValue('panjang_talang_jurai'));
    setPdfValue('pdf_waste', getInputValue('waste') || 5);
    setPdfValue('pdf_grand_total', grandTotal || 0);
    setPdfValue('pdf_tanggal', new Date().toLocaleDateString('id-ID'));
    setPdfValue('pdf_waktu', new Date().toLocaleTimeString('id-ID'));
    
    setPdfValue('pdf_opsi_dinding', getInputValue('opsi_dinding'));
    setPdfValue('pdf_opsi_kaca', getInputValue('opsi_kaca'));
    setPdfValue('pdf_opsi_penangkal', getInputValue('opsi_penangkal'));
    setPdfValue('pdf_opsi_exhaust', getInputValue('opsi_exhaust'));
}

function exportToPDF() {
    // ===== AMBIL DATA =====
    let data = {
        luas_atap: document.getElementById('luas_atap')?.value || 0,
        sudut: document.getElementById('sudut')?.value || 0,
        panjang_starter: document.getElementById('panjang_starter')?.value || 0,
        panjang_nok_jurai: document.getElementById('panjang_nok_jurai')?.value || 0,
        panjang_flashing: document.getElementById('panjang_flashing')?.value || 0,
        talang_jurai: document.getElementById('panjang_talang_jurai')?.value || 0,
        wall_flashing: document.getElementById('opsi_dinding')?.value || 0,
        waste: document.getElementById('waste')?.value || 5,
        produk_atap: document.getElementById('produk_atap_id')?.selectedOptions[0]?.text || '',
        underlayer: document.getElementById('underlayer_id')?.selectedOptions[0]?.text || '',
        starter: document.getElementById('starter_produk_id')?.selectedOptions[0]?.text || '',
        rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
        lantai_kerja: document.getElementById('lantai_kerja')?.selectedOptions[0]?.text || '',
        grand_total: document.getElementById('grandTotal')?.innerText || 'Rp 0',
        opsi_dinding: document.getElementById('opsi_dinding')?.value || 0,
        opsi_kaca: document.getElementById('opsi_kaca')?.value || 0,
        opsi_penangkal: document.getElementById('opsi_penangkal')?.value || 0,
        opsi_exhaust: document.getElementById('opsi_exhaust')?.value || 0,
        results: currentResults.map(function(item) {
            return {
                id: item.id,
                product_id: item.product_id,
                nama: item.nama_produk,
                area: item.area,
                qty: item.qty,
                satuan: item.satuan,
                harga: item.harga_satuan,
                total: item.total_harga
            };
        })
    };
    
    // ===== TAMPILKAN LOADING =====
    let btn = document.getElementById('btnPDF');
    let originalText = btn ? btn.innerHTML : 'Export PDF';
    if (btn) {
        btn.innerHTML = '⏳ Memproses...';
        btn.disabled = true;
    }
    
    // ===== KIRIM REQUEST =====
    fetch('/boq/skyshield/export-pdf', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: JSON.stringify(data)
    })
    .then(function(response) {
        return response.text();
    })
    .then(function(html) {
        let win = window.open('', '_blank');
        if (win) {
            win.document.write(html);
            win.document.close();
            win.focus();
        } else {
            alert('Popup blocked! Please allow popups for this site.');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat export PDF: ' + error.message);
    })
    .finally(function() {
        if (btn) {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
}
</script>
@endsection