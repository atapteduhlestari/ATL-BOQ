@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* ===== STYLE SAMA PERSIS DENGAN ATAP GERGAJI ===== */
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
    
    .badge-section {
        background: #edf2f7;
        color: #4a5568;
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
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
    
    /* Opsi Tambahan */
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

    /* Notification toast */
    .notification-toast {
        animation: slideDown 0.3s ease-out;
        z-index: 9999;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
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
</style>

<div class="space-y-6">
    <!-- Header Brand -->
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon palmex">P</div>
                <div>
                    <div class="brand-name">BOQ - PALMEX Lengkung + 2 Sisi Miring</div>
                    <div class="brand-sub">Hitung kebutuhan material atap lengkung dengan 2 sisi miring</div>
                </div>
            </div>
            <div class="brand-badge">PALMEX</div>
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
                <span>• Pemakaian underlayer <strong>self adhesive</strong> (IKO Stormshield)</span>
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
                <span>Untuk Bagian Tengah (Lengkung), <strong>Nok Atas WAJIB</strong> dipilih dari dropdown.</span>
            </li>
        </ul>
    </div>

    <!-- ===== SECTION 1: DATA GEOMETRIK ===== -->
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('geometrik')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">Data Geometrik</h3>
                    <p class="section-subtitle">Data luas atap dan kemiringan dari perhitungan sebelumnya</p>
                </div>
                <span class="toggle-icon" id="geometrik_icon">▼</span>
            </div>
        </div>
        <div class="section-body" id="geometrik_body">
            
            <!-- Bagian Kiri -->
            <div class="sub-section-title">
                Bagian Kiri - 1 Sisi Kemiringan
            </div>
            <div class="data-geometrik">
                <div class="row">
                    <span class="label">Luas Atap</span>
                    <span class="value">
                        <input type="text" id="luas_atap_1" readonly value="0">
                        <span class="unit">m²</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sudut Kemiringan</span>
                    <span class="value">
                        <input type="text" id="sudut_1" readonly value="0">
                        <span class="unit">°</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Starter</span>
                    <span class="value">
                        <input type="text" id="starter_1" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Flashing</span>
                    <span class="value">
                        <input type="text" id="flashing_1" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
            </div>

            <!-- Bagian Tengah -->
            <div class="sub-section-title" style="margin-top:16px;">
                Bagian Tengah - Lengkung
            </div>
            <div class="data-geometrik">
                <div class="row">
                    <span class="label">Luas Atap</span>
                    <span class="value">
                        <input type="text" id="luas_atap_2" readonly value="0">
                        <span class="unit">m²</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Tinggi</span>
                    <span class="value">
                        <input type="text" id="tinggi_2" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Starter</span>
                    <span class="value">
                        <input type="text" id="starter_2" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Nok Atas</span>
                    <span class="value">
                        <input type="text" id="nok_atas_2" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Flashing</span>
                    <span class="value">
                        <input type="text" id="flashing_2" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
            </div>

            <!-- Bagian Kanan -->
            <div class="sub-section-title" style="margin-top:16px;">
                Bagian Kanan - 1 Sisi Kemiringan
            </div>
            <div class="data-geometrik">
                <div class="row">
                    <span class="label">Luas Atap</span>
                    <span class="value">
                        <input type="text" id="luas_atap_3" readonly value="0">
                        <span class="unit">m²</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sudut Kemiringan</span>
                    <span class="value">
                        <input type="text" id="sudut_3" readonly value="0">
                        <span class="unit">°</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Starter</span>
                    <span class="value">
                        <input type="text" id="starter_3" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Total Panjang Flashing</span>
                    <span class="value">
                        <input type="text" id="flashing_3" readonly value="0">
                        <span class="unit">m</span>
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== SECTION 2: PILIH MATERIAL ===== -->
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('material')">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">Pilih Material</h3>
                    <p class="section-subtitle">Pilih material dan aksesoris atap per bagian</p>
                </div>
                <span class="toggle-icon" id="material_icon">▼</span>
            </div>
        </div>
        <div class="section-body" id="material_body">

            <!-- Bagian Kiri -->
            <div class="sub-section-title">
                Bagian Kiri - 1 Sisi Kemiringan
            </div>
            <div class="pilih-material">
                <div class="row">
                    <span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="produk_atap_1" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sistem Pemasangan</span>
                    <span class="value">
                        <select id="sistem_pemasangan_1" onchange="toggleFields(1)">
                            <option value="expose">Expose</option>
                            <option value="non-expose">Non-Expose</option>
                        </select>
                    </span>
                </div>
                <div class="row" id="underlayer_container_1" style="display:none;">
                    <span class="label">Underlayer</span>
                    <span class="value">
                        <select id="underlayer_1">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_1 ?? [] as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row" id="lantai_kerja_container_1" style="display:none;">
                    <span class="label">Lantai Kerja</span>
                    <span class="value">
                        <select id="lantai_kerja_1">
                            <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                            <option value="Plywood 12 mm">Plywood 12 mm</option>
                            <option value="Plywood 15 mm">Plywood 15 mm</option>
                            <option value="GRC 9 mm">GRC 9 mm</option>
                            <option value="GRC 12 mm">GRC 12 mm</option>
                            <option value="GRC 15 mm">GRC 15 mm</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka_1">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Coverage <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="coverage_1" required>
                            <option value="">Pilih Coverage</option>
                            <option value="8">8 daun / m²</option>
                            <option value="7">7 daun / m²</option>
                            <option value="6">6 daun / m²</option>
                        </select>
                    </span>
                </div>
            </div>

            <!-- Bagian Tengah -->
            <div class="sub-section-title" style="margin-top:16px;">
                Bagian Tengah - Lengkung
            </div>
            <div class="pilih-material">
                <div class="row">
                    <span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="produk_atap_2" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Nok Atas <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="nok_atas_dropdown" required>
                            <option value="">Pilih Nok Atas</option>
                            @foreach($nokAtasOptions ?? [] as $nok)
                                <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sistem Pemasangan</span>
                    <span class="value">
                        <select id="sistem_pemasangan_2" onchange="toggleFields(2)">
                            <option value="expose">Expose</option>
                            <option value="non-expose">Non-Expose</option>
                        </select>
                    </span>
                </div>
                <div class="row" id="underlayer_container_2" style="display:none;">
                    <span class="label">Underlayer</span>
                    <span class="value">
                        <select id="underlayer_2">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_2 ?? [] as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row" id="lantai_kerja_container_2" style="display:none;">
                    <span class="label">Lantai Kerja</span>
                    <span class="value">
                        <select id="lantai_kerja_2">
                            <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                            <option value="Plywood 12 mm">Plywood 12 mm</option>
                            <option value="Plywood 15 mm">Plywood 15 mm</option>
                            <option value="GRC 9 mm">GRC 9 mm</option>
                            <option value="GRC 12 mm">GRC 12 mm</option>
                            <option value="GRC 15 mm">GRC 15 mm</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka_2">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Coverage <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="coverage_2" required>
                            <option value="">Pilih Coverage</option>
                            <option value="8">8 daun / m²</option>
                            <option value="7">7 daun / m²</option>
                            <option value="6">6 daun / m²</option>
                        </select>
                    </span>
                </div>
            </div>

            <!-- Bagian Kanan -->
            <div class="sub-section-title" style="margin-top:16px;">
                Bagian Kanan - 1 Sisi Kemiringan
            </div>
            <div class="pilih-material">
                <div class="row">
                    <span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="produk_atap_3" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sistem Pemasangan</span>
                    <span class="value">
                        <select id="sistem_pemasangan_3" onchange="toggleFields(3)">
                            <option value="expose">Expose</option>
                            <option value="non-expose">Non-Expose</option>
                        </select>
                    </span>
                </div>
                <div class="row" id="underlayer_container_3" style="display:none;">
                    <span class="label">Underlayer</span>
                    <span class="value">
                        <select id="underlayer_3">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_3 ?? [] as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="row" id="lantai_kerja_container_3" style="display:none;">
                    <span class="label">Lantai Kerja</span>
                    <span class="value">
                        <select id="lantai_kerja_3">
                            <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                            <option value="Plywood 12 mm">Plywood 12 mm</option>
                            <option value="Plywood 15 mm">Plywood 15 mm</option>
                            <option value="GRC 9 mm">GRC 9 mm</option>
                            <option value="GRC 12 mm">GRC 12 mm</option>
                            <option value="GRC 15 mm">GRC 15 mm</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka_3">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Coverage <span class="required-star">*</span></span>
                    <span class="value">
                        <select id="coverage_3" required>
                            <option value="">Pilih Coverage</option>
                            <option value="8">8 daun / m²</option>
                            <option value="7">7 daun / m²</option>
                            <option value="6">6 daun / m²</option>
                        </select>
                    </span>
                </div>
            </div>

            <!-- Waste -->
            <div class="sub-section-title" style="margin-top:16px;">
                Waste
            </div>
            <div class="pilih-material">
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

            <!-- Opsi Tambahan -->
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
                </div>
            </div>

            <!-- Tombol Hitung -->
            <button onclick="hitungSemuaBagian()" class="btn-primary btn-master mt-4" style="padding:14px 24px;font-size:14px;font-weight:600;">
                🚀 Hitung Kebutuhan Material
            </button>

        </div>
    </div>

    <!-- ===== HASIL ===== -->
    <div id="hasilSemua" class="hidden">
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">Rincian Kebutuhan Material</h3>
                        <p class="section-subtitle">Hasil perhitungan semua bagian</p>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <!-- Tabel Material -->
                <div class="table-container" id="tableSemua"></div>
                
                <!-- Sub Total per Bagian -->
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;">
                    <div class="data-box" style="background:#f7fafc;border-radius:8px;padding:10px 14px;border:1px solid #edf2f7;">
                        <label style="font-size:9px;text-transform:uppercase;letter-spacing:0.5px;color:#a0aec0;font-weight:600;display:block;margin-bottom:2px;">Bagian Kiri</label>
                        <div style="font-size:16px;font-weight:600;color:#2d3748;" id="subTotal1">Rp 0</div>
                    </div>
                    <div class="data-box" style="background:#f7fafc;border-radius:8px;padding:10px 14px;border:1px solid #edf2f7;">
                        <label style="font-size:9px;text-transform:uppercase;letter-spacing:0.5px;color:#a0aec0;font-weight:600;display:block;margin-bottom:2px;">Bagian Tengah</label>
                        <div style="font-size:16px;font-weight:600;color:#2d3748;" id="subTotal2">Rp 0</div>
                    </div>
                    <div class="data-box" style="background:#f7fafc;border-radius:8px;padding:10px 14px;border:1px solid #edf2f7;">
                        <label style="font-size:9px;text-transform:uppercase;letter-spacing:0.5px;color:#a0aec0;font-weight:600;display:block;margin-bottom:2px;">Bagian Kanan</label>
                        <div style="font-size:16px;font-weight:600;color:#2d3748;" id="subTotal3">Rp 0</div>
                    </div>
                </div>
                
                <!-- Grand Total -->
                <div class="grand-total-minimal">
                    <span class="label">GRAND TOTAL</span>
                    <span class="amount" id="grandTotalSemua">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TOTAL KESELURUHAN + PDF ===== -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
                <p class="text-gray-400 text-[10px]">Kiri + Tengah + Kanan</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/palmex/atap-kombinasi/lengkung-2-sisi/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Lengkung + 2 Sisi Miring">
            <input type="hidden" name="brand" value="PALMEX">
            
            <input type="hidden" name="hasil_semua" id="pdf_hasil_semua">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            
            <button type="submit" class="btn-pdf btn-master-pdf">
                📄 Export PDF
            </button>
        </form>
    </div>
</div>

<script>
let results1 = [], results2 = [], results3 = [];

function toggleSection(section) {
    let body = document.getElementById(section + '_body');
    let icon = document.getElementById(section + '_icon');
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

function showNotification(message, type = 'info') {
    let existing = document.querySelector('.notification-toast');
    if (existing) existing.remove();
    
    let toast = document.createElement('div');
    toast.className = 'notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md';
    
    if (type === 'warning') {
        toast.className += ' bg-yellow-50 border border-yellow-400 text-yellow-800';
    } else if (type === 'error') {
        toast.className += ' bg-red-50 border border-red-400 text-red-800';
    } else {
        toast.className += ' bg-blue-50 border border-blue-400 text-blue-800';
    }
    
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <span class="text-lg">${type === 'warning' ? '⚠️' : type === 'error' ? '❌' : 'ℹ️'}</span>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => { if (toast.parentElement) toast.remove(); }, 5000);
}

function toggleFields(bagian) {
    let sistem = document.getElementById('sistem_pemasangan_' + bagian).value;
    let sudut = parseFloat(document.getElementById('sudut_' + bagian)?.value) || 0;
    let underlayerContainer = document.getElementById('underlayer_container_' + bagian);
    let lantaiKerjaContainer = document.getElementById('lantai_kerja_container_' + bagian);
    let sistemSelect = document.getElementById('sistem_pemasangan_' + bagian);
    
    if (bagian !== 2 && sudut < 30) {
        sistemSelect.value = 'non-expose';
        sistem = 'non-expose';
        sistemSelect.disabled = true;
        sistemSelect.style.opacity = '0.7';
        sistemSelect.style.backgroundColor = '#f7fafc';
    } else {
        sistemSelect.disabled = false;
        sistemSelect.style.opacity = '1';
        sistemSelect.style.backgroundColor = 'white';
    }
    
    if (sistem === 'expose') {
        if (underlayerContainer) underlayerContainer.style.display = 'none';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'none';
    } else {
        if (underlayerContainer) underlayerContainer.style.display = 'flex';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'flex';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    setVal('luas_atap_1', urlParams.get('luas_atap_1') || 0);
    setVal('sudut_1', urlParams.get('sudut_1') || 0);
    setVal('starter_1', urlParams.get('starter_1') || 0);
    setVal('flashing_1', urlParams.get('flashing_1') || 0);
    setVal('opsi_dinding', urlParams.get('dinding') || 0);
    setVal('opsi_kaca', urlParams.get('kaca') || 0);
    
    setVal('luas_atap_2', urlParams.get('luas_atap_2') || 0);
    setVal('tinggi_2', urlParams.get('tinggi') || 0);
    setVal('starter_2', urlParams.get('starter_2') || 0);
    setVal('nok_atas_2', urlParams.get('nok_atas_2') || urlParams.get('panjang_b') || 0);
    setVal('flashing_2', urlParams.get('flashing_2') || 0);
    
    setVal('luas_atap_3', urlParams.get('luas_atap_3') || 0);
    setVal('sudut_3', urlParams.get('sudut_3') || 0);
    setVal('starter_3', urlParams.get('starter_3') || 0);
    setVal('flashing_3', urlParams.get('flashing_3') || 0);
    
    document.getElementById('sistem_pemasangan_1').value = 'expose';
    document.getElementById('sistem_pemasangan_2').value = 'expose';
    document.getElementById('sistem_pemasangan_3').value = 'expose';
    
    toggleFields(1);
    toggleFields(2);
    toggleFields(3);
    
    ['1', '2', '3'].forEach(b => {
        document.getElementById('sistem_pemasangan_' + b).addEventListener('change', function() {
            toggleFields(parseInt(b));
        });
    });
});

// ===== RENDER TABLE HASIL SAMA PERSIS DENGAN ATAP GERGAJI =====
function renderResultTable(results, containerId) {
    let container = document.getElementById(containerId);
    if (!container) return;
    
    if (!results || results.length === 0) {
        container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
        return;
    }
    
    // Kelompokkan seperti di atap gergaji
    let kelompok = {
        'Atap Utama': [],
        'Aksesoris': [],
        'Additional': [],
        'Sistem Pendukung': []
    };

    const aksesorisAreas = ['Starter', 'Nok Atas', 'Nok Jurai', 'Topcap', 'Screw', 'Rail', 'Wind', 'Metal Flashing', 'Talang Jurai', 'Flashing', 'Paku & Screw', 'Shingle Stick', 'Underlayer', 'Lantai Kerja'];
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca'];

    results.forEach(item => {
        const area = item.area || '';
        if (area === 'Atap Utama') {
            kelompok['Atap Utama'].push(item);
        } else if (additionalAreas.includes(area) && item.qty > 0) {
            kelompok['Additional'].push(item);
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

    groupConfig.forEach(({ key, label, cls }) => {
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
        items.forEach((item, index) => {
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

function hitungSemuaBagian() {
    let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding').value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca').value) || 0;
    
    let bagianData = [
        { 
            id: 1, 
            luas: parseFloat(document.getElementById('luas_atap_1').value) || 0,
            sudut: parseFloat(document.getElementById('sudut_1').value) || 0,
            starter: parseFloat(document.getElementById('starter_1').value) || 0,
            flashing: parseFloat(document.getElementById('flashing_1').value) || 0,
            produk: document.getElementById('produk_atap_1'),
            underlayer: document.getElementById('underlayer_1'),
            lantaiKerja: document.getElementById('lantai_kerja_1'),
            sistem: document.getElementById('sistem_pemasangan_1'),
            coverage: document.getElementById('coverage_1'),
            rangka: document.getElementById('rangka_1'),
            jenis: 'pelana',
            tinggi: 0,
            nokAtas: 0,
            nokAtasId: ''
        },
        {
            id: 2,
            luas: parseFloat(document.getElementById('luas_atap_2').value) || 0,
            sudut: 0,
            starter: parseFloat(document.getElementById('starter_2').value) || 0,
            flashing: parseFloat(document.getElementById('flashing_2').value) || 0,
            produk: document.getElementById('produk_atap_2'),
            underlayer: document.getElementById('underlayer_2'),
            lantaiKerja: document.getElementById('lantai_kerja_2'),
            sistem: document.getElementById('sistem_pemasangan_2'),
            coverage: document.getElementById('coverage_2'),
            rangka: document.getElementById('rangka_2'),
            jenis: 'lengkung',
            tinggi: parseFloat(document.getElementById('tinggi_2').value) || 0,
            nokAtas: parseFloat(document.getElementById('nok_atas_2').value) || 0,
            nokAtasId: document.getElementById('nok_atas_dropdown').value || ''
        },
        {
            id: 3,
            luas: parseFloat(document.getElementById('luas_atap_3').value) || 0,
            sudut: parseFloat(document.getElementById('sudut_3').value) || 0,
            starter: parseFloat(document.getElementById('starter_3').value) || 0,
            flashing: parseFloat(document.getElementById('flashing_3').value) || 0,
            produk: document.getElementById('produk_atap_3'),
            underlayer: document.getElementById('underlayer_3'),
            lantaiKerja: document.getElementById('lantai_kerja_3'),
            sistem: document.getElementById('sistem_pemasangan_3'),
            coverage: document.getElementById('coverage_3'),
            rangka: document.getElementById('rangka_3'),
            jenis: 'pelana',
            tinggi: 0,
            nokAtas: 0,
            nokAtasId: ''
        }
    ];
    
    for (let b of bagianData) {
        if (!b.produk.value) {
            alert('⚠️ Pilih produk atap utama untuk Bagian ' + b.id + '!');
            return;
        }
        if (!b.coverage.value) {
            alert('⚠️ Pilih Coverage untuk Bagian ' + b.id + '!');
            return;
        }
        if (b.id === 2 && !b.nokAtasId) {
            alert('⚠️ Pilih Nok Atas untuk Bagian Tengah (wajib)!');
            return;
        }
        if (b.sistem.value === 'non-expose' && !b.underlayer.value) {
            alert('⚠️ Pilih Underlayer untuk Bagian ' + b.id + ' (Non-Expose)!');
            return;
        }
    }
    
    let btn = event?.target;
    let originalText = btn ? btn.innerHTML : 'Menghitung...';
    if (btn) {
        btn.innerHTML = '⏳ Menghitung...';
        btn.disabled = true;
    }
    
    let promises = bagianData.map(b => {
        let data = {
            luas_atap: b.luas,
            sudut: b.sudut,
            panjang_starter: b.starter,
            panjang_jurai: 0,
            panjang_nok_atas: b.nokAtas,
            panjang_flashing: b.flashing,
            panjang_talang_jurai: 0,
            panjang_wall_flashing: opsiDinding,
            opsi_kaca: opsiKaca,
            opsi_exhaust: 0,
            sistem_pemasangan: b.sistem.value,
            produk_atap_id: b.produk.value,
            nok_atas_id: b.nokAtasId,
            underlayer_id: b.underlayer ? b.underlayer.value : '',
            rangka: b.rangka ? b.rangka.value : 'Baja Ringan',
            lantai_kerja: b.lantaiKerja ? b.lantaiKerja.value : 'Plywood 9 mm',
            waste: wasteValue,
            coverage: parseFloat(b.coverage.value),
            jenis: b.jenis,
            tinggi: b.tinggi
        };
        
        return fetch('/boq/palmex/atap-kombinasi/lengkung-2-sisi/hitung', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(data)
        }).then(res => res.json());
    });
    
    Promise.all(promises)
        .then(results => {
            results1 = results[0].results || [];
            results2 = results[1].results || [];
            results3 = results[2].results || [];
            
            let total1 = results1.reduce((s, i) => s + (i.total_harga || 0), 0);
            let total2 = results2.reduce((s, i) => s + (i.total_harga || 0), 0);
            let total3 = results3.reduce((s, i) => s + (i.total_harga || 0), 0);
            let grandTotal = total1 + total2 + total3;
            
            document.getElementById('subTotal1').textContent = 'Rp ' + total1.toLocaleString();
            document.getElementById('subTotal2').textContent = 'Rp ' + total2.toLocaleString();
            document.getElementById('subTotal3').textContent = 'Rp ' + total3.toLocaleString();
            document.getElementById('grandTotalSemua').textContent = 'Rp ' + grandTotal.toLocaleString();
            document.getElementById('totalKeseluruhan').textContent = 'Rp ' + grandTotal.toLocaleString();
            
            // Render semua hasil dengan tabel SAMA PERSIS atap gergaji
            let allResults = [...results1, ...results2, ...results3];
            renderResultTable(allResults, 'tableSemua');
            
            document.getElementById('hasilSemua').classList.remove('hidden');
            
            document.getElementById('pdf_hasil_semua').value = JSON.stringify(allResults);
            document.getElementById('pdf_grand_total').value = grandTotal;
            
            showNotification('✅ Semua bagian berhasil dihitung!', 'info');
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
</script>
@endsection