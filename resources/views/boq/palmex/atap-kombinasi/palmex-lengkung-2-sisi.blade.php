@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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

    .sub-section-title {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 12px 0 6px 0;
        padding: 4px 10px;
        background: #f8fafc;
        border-radius: 3px;
        border-left: 3px solid #1a1a2e;
        cursor: pointer;
        user-select: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sub-section-title:hover {
        background: #f1f4f9;
    }
    .sub-section-title .sub-toggle {
        font-size: 12px;
        transition: transform 0.3s;
    }
    .sub-section-title .sub-toggle.collapsed {
        transform: rotate(-90deg);
    }

    .sub-section-body {
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .sub-section-body.collapsed {
        max-height: 0 !important;
        padding: 0 !important;
        opacity: 0;
    }
    
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

    .bagian-wrapper {
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
    }
    .bagian-wrapper:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .bagian-header {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 8px;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 6px;
        border-left: 3px solid #0f3460;
        cursor: pointer;
        user-select: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    .bagian-header:hover {
        background: #f1f4f9;
    }
    .bagian-header .bagian-toggle {
        font-size: 12px;
        transition: transform 0.3s;
    }
    .bagian-header .bagian-toggle.collapsed {
        transform: rotate(-90deg);
    }

    .bagian-body {
        overflow: hidden;
        transition: all 0.3s ease;
        padding: 0 4px;
    }
    .bagian-body.collapsed {
        max-height: 0 !important;
        padding: 0 !important;
        opacity: 0;
    }

    .bagian-wrapper .btn-hitung-bagian {
        background: #0f3460;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
        width: 100%;
    }
    .bagian-wrapper .btn-hitung-bagian:hover {
        background: #1a1a2e;
    }
    .bagian-wrapper .btn-hitung-bagian:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .bagian-wrapper .bagian-hasil {
        margin-top: 12px;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
    }
    .bagian-wrapper .bagian-hasil .subtotal {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        text-align: right;
    }
    .bagian-wrapper .bagian-hasil .subtotal .label {
        font-weight: 400;
        color: #718096;
        font-size: 12px;
    }

    .bagian-header.bagian-kiri { border-left-color: #1e40af; }
    .bagian-header.bagian-tengah { border-left-color: #9d174d; }
    .bagian-header.bagian-kanan { border-left-color: #065f46; }
</style>

<div class="space-y-6">

    <!-- ===== HEADER BRAND ===== -->
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

    <!-- ===== NOTES ===== -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Petunjuk Pengisian BOQ
        </div>
        <ul class="notes-list">
            <li><span class="bullet">•</span> <span>Cek lebih detail apakah atap yang akan dipasang itu <strong>Expose</strong> atau <strong>Non-Expose</strong>.</span></li>
            <li><span class="bullet">•</span> <span><strong>Expose</strong> = Kemiringan minimal <span class="highlight">30°</span> (≥ 30 derajat)</span></li>
            <li><span class="bullet">•</span> <span><strong>Non-Expose</strong> = Kemiringan minimal <span class="highlight">15°</span> (≥ 15 derajat)</span></li>
            <li><span class="bullet">•</span> <span><strong style="color: #dc2626;">⚠️ PERHATIAN PENTING:</strong> Jika sudut atap < 30°, sistem akan <strong style="color: #dc2626;">OTOMATIS</strong> menggunakan <strong style="color: #dc2626;">NON-EXPOSE</strong></span></li>
            <li><span class="bullet">•</span> <span><strong>Coverage (daun/m²) dapat dipilih sendiri:</strong></span></li>
            <li style="padding-left:28px;"><span>• Untuk Expose, disarankan pilih <strong>7 atau 8 daun/m²</strong></span></li>
            <li style="padding-left:28px;"><span>• Untuk Non-Expose, disarankan pilih <strong>7 daun/m²</strong></span></li>
            <li><span class="bullet">•</span> <span>Jika atap bertemu dinding, input di <strong>"Opsi Tambahan"</strong> → Dinding</span></li>
            <li><span class="bullet">•</span> <span>Jika atap bertemu kaca, input di <strong>"Opsi Tambahan"</strong> → Kaca</span></li>
            <li><span class="bullet">•</span> <span>Untuk Bagian Tengah (Lengkung), <strong>Nok Atas WAJIB</strong> dipilih.</span></li>
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
            
            <!-- Bagian Kiri -->
            <div class="sub-section-title" style="border-left-color:#1e40af;" onclick="toggleSubSection('sub_geometrik_1')">
                Bagian Kiri - 1 Sisi Kemiringan
                <span class="sub-toggle" id="sub_geometrik_1_icon">▶</span>
            </div>
            <div class="sub-section-body collapsed" id="sub_geometrik_1_body">
                <div class="data-geometrik">
                    <div class="row"><span class="label">Luas Atap</span><span class="value"><input type="text" id="luas_atap_1" readonly value="0"><span class="unit">m²</span></span></div>
                    <div class="row"><span class="label">Sudut Kemiringan</span><span class="value"><input type="text" id="sudut_1" readonly value="0"><span class="unit">°</span></span></div>
                    <div class="row"><span class="label">Total Panjang Starter</span><span class="value"><input type="text" id="starter_1" readonly value="0"><span class="unit">m</span></span></div>
                    <div class="row"><span class="label">Total Panjang Flashing</span><span class="value"><input type="text" id="flashing_1" readonly value="0"><span class="unit">m</span></span></div>
                </div>
            </div>

            <!-- Bagian Tengah -->
            <div class="sub-section-title" style="margin-top:16px;border-left-color:#9d174d;" onclick="toggleSubSection('sub_geometrik_2')">
                Bagian Tengah - Lengkung
                <span class="sub-toggle" id="sub_geometrik_2_icon">▶</span>
            </div>
            <div class="sub-section-body collapsed" id="sub_geometrik_2_body">
                <div class="data-geometrik">
                    <div class="row"><span class="label">Luas Atap</span><span class="value"><input type="text" id="luas_atap_2" readonly value="0"><span class="unit">m²</span></span></div>
                    <div class="row"><span class="label">Tinggi</span><span class="value"><input type="text" id="tinggi_2" readonly value="0"><span class="unit">m</span></span></div>
                    <div class="row"><span class="label">Total Panjang Starter</span><span class="value"><input type="text" id="starter_2" readonly value="0"><span class="unit">m</span></span></div>
                    <div class="row"><span class="label">Total Panjang Nok Atas</span><span class="value"><input type="text" id="nok_atas_2" readonly value="0"><span class="unit">m</span></span></div>
                    <div class="row"><span class="label">Total Panjang Flashing</span><span class="value"><input type="text" id="flashing_2" readonly value="0"><span class="unit">m</span></span></div>
                </div>
            </div>

            <!-- Bagian Kanan -->
            <div class="sub-section-title" style="margin-top:16px;border-left-color:#065f46;" onclick="toggleSubSection('sub_geometrik_3')">
                Bagian Kanan - 1 Sisi Kemiringan
                <span class="sub-toggle" id="sub_geometrik_3_icon">▶</span>
            </div>
            <div class="sub-section-body collapsed" id="sub_geometrik_3_body">
                <div class="data-geometrik">
                    <div class="row"><span class="label">Luas Atap</span><span class="value"><input type="text" id="luas_atap_3" readonly value="0"><span class="unit">m²</span></span></div>
                    <div class="row"><span class="label">Sudut Kemiringan</span><span class="value"><input type="text" id="sudut_3" readonly value="0"><span class="unit">°</span></span></div>
                    <div class="row"><span class="label">Total Panjang Starter</span><span class="value"><input type="text" id="starter_3" readonly value="0"><span class="unit">m</span></span></div>
                    <div class="row"><span class="label">Total Panjang Flashing</span><span class="value"><input type="text" id="flashing_3" readonly value="0"><span class="unit">m</span></span></div>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== SECTION 2: PILIH MATERIAL ===== -->
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('material')">
            <div>
                <h3 class="section-title">Pilih Material & Hitung</h3>
                <p class="section-subtitle">Setiap bagian dihitung secara terpisah</p>
            </div>
            <span class="toggle-icon" id="material_icon">▼</span>
        </div>
        <div class="section-body" id="material_body">

            <!-- ===== BAGIAN KIRI ===== -->
            <div class="bagian-wrapper" id="bagian_1_wrapper">
                <div class="bagian-header bagian-kiri" onclick="toggleBagian('1')">
                    <span>Bagian Kiri - 1 Sisi Kemiringan</span>
                    <span class="bagian-toggle" id="bagian_1_toggle">▶</span>
                </div>
                <div class="bagian-body collapsed" id="bagian_1_body">
                    <div class="pilih-material">
                        <div class="row"><span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                            <span class="value"><select id="produk_atap_1" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products ?? [] as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Sistem Pemasangan</span>
                            <span class="value"><select id="sistem_pemasangan_1" onchange="toggleFields(1)">
                                <option value="expose">Expose</option>
                                <option value="non-expose">Non-Expose</option>
                            </select></span>
                        </div>
                        <div class="row" id="underlayer_container_1" style="display:none;"><span class="label">Underlayer</span>
                            <span class="value"><select id="underlayer_1">
                                <option value="">Pilih Underlayer</option>
                                @foreach($underlayers_1 ?? [] as $underlayer)
                                    <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row" id="lantai_kerja_container_1" style="display:none;"><span class="label">Lantai Kerja</span>
                            <span class="value"><select id="lantai_kerja_1">
                                <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                                <option value="Plywood 12 mm">Plywood 12 mm</option>
                                <option value="Plywood 15 mm">Plywood 15 mm</option>
                                <option value="GRC 9 mm">GRC 9 mm</option>
                                <option value="GRC 12 mm">GRC 12 mm</option>
                                <option value="GRC 15 mm">GRC 15 mm</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Struktur Rangka</span>
                            <span class="value"><select id="rangka_1">
                                <option value="Kayu">Kayu</option>
                                <option value="Baja Ringan" selected>Baja Ringan</option>
                                <option value="Baja Berat">Baja Berat</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Coverage <span class="required-star">*</span></span>
                            <span class="value"><select id="coverage_1" required>
                                <option value="">Pilih Coverage</option>
                                <option value="8">8 daun / m²</option>
                                <option value="7">7 daun / m²</option>
                                <option value="6">6 daun / m²</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Waste <span class="required-star">*</span></span>
                            <span class="value"><div class="waste-inline"><input type="number" id="waste_1" step="1" value="5"><span>%</span></div></span>
                        </div>
                    </div>

                    <div class="opsi-tambahan" style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
                        <div class="opsi-title" style="font-size:12px;margin-bottom:6px;">Opsi Tambahan</div>
                        <div class="opsi-grid" style="padding-left:24px;">
                            <div class="opsi-item">
                                <label>Dinding <span class="opsi-desc">(panjang atap berbatasan dinding)</span></label>
                                <input type="number" id="opsi_dinding_1" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                            <div class="opsi-item">
                                <label>Kaca <span class="opsi-desc">(panjang area kaca/genteng kaca)</span></label>
                                <input type="number" id="opsi_kaca_1" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                        </div>
                    </div>

                    <button onclick="hitungBagian(1)" class="btn-hitung-bagian" id="btn_hitung_1">
                        🚀 Hitung Bagian Kiri
                    </button>

                    <div class="bagian-hasil" id="hasil_bagian_1" style="display:none;">
                        <div class="table-container" id="table_bagian_1"></div>
                        <div class="subtotal">
                            <span class="label">Sub Total :  </span>
                            <span id="subtotal_bagian_1">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== BAGIAN TENGAH ===== -->
            <div class="bagian-wrapper" id="bagian_2_wrapper">
                <div class="bagian-header bagian-tengah" onclick="toggleBagian('2')">
                    <span>Bagian Tengah - Lengkung</span>
                    <span class="bagian-toggle" id="bagian_2_toggle">▶</span>
                </div>
                <div class="bagian-body collapsed" id="bagian_2_body">
                    <div class="pilih-material">
                        <div class="row"><span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                            <span class="value"><select id="produk_atap_2" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products ?? [] as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Nok Atas <span class="required-star">*</span></span>
                            <span class="value"><select id="nok_atas_dropdown" required>
                                <option value="">Pilih Nok Atas</option>
                                @foreach($nokAtasOptions ?? [] as $nok)
                                    <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Sistem Pemasangan</span>
                            <span class="value"><select id="sistem_pemasangan_2" onchange="toggleFields(2)">
                                <option value="expose">Expose</option>
                                <option value="non-expose">Non-Expose</option>
                            </select></span>
                        </div>
                        <div class="row" id="underlayer_container_2" style="display:none;"><span class="label">Underlayer</span>
                            <span class="value"><select id="underlayer_2">
                                <option value="">Pilih Underlayer</option>
                                @foreach($underlayers_2 ?? [] as $underlayer)
                                    <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row" id="lantai_kerja_container_2" style="display:none;"><span class="label">Lantai Kerja</span>
                            <span class="value"><select id="lantai_kerja_2">
                                <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                                <option value="Plywood 12 mm">Plywood 12 mm</option>
                                <option value="Plywood 15 mm">Plywood 15 mm</option>
                                <option value="GRC 9 mm">GRC 9 mm</option>
                                <option value="GRC 12 mm">GRC 12 mm</option>
                                <option value="GRC 15 mm">GRC 15 mm</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Struktur Rangka</span>
                            <span class="value"><select id="rangka_2">
                                <option value="Kayu">Kayu</option>
                                <option value="Baja Ringan" selected>Baja Ringan</option>
                                <option value="Baja Berat">Baja Berat</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Coverage <span class="required-star">*</span></span>
                            <span class="value"><select id="coverage_2" required>
                                <option value="">Pilih Coverage</option>
                                <option value="8">8 daun / m²</option>
                                <option value="7">7 daun / m²</option>
                                <option value="6">6 daun / m²</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Waste <span class="required-star">*</span></span>
                            <span class="value"><div class="waste-inline"><input type="number" id="waste_2" step="1" value="5"><span>%</span></div></span>
                        </div>
                    </div>

                    <div class="opsi-tambahan" style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
                        <div class="opsi-title" style="font-size:12px;margin-bottom:6px;">Opsi Tambahan</div>
                        <div class="opsi-grid" style="padding-left:24px;">
                            <div class="opsi-item">
                                <label>Dinding <span class="opsi-desc">(panjang atap berbatasan dinding)</span></label>
                                <input type="number" id="opsi_dinding_2" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                            <div class="opsi-item">
                                <label>Kaca <span class="opsi-desc">(panjang area kaca/genteng kaca)</span></label>
                                <input type="number" id="opsi_kaca_2" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                        </div>
                    </div>

                    <button onclick="hitungBagian(2)" class="btn-hitung-bagian" id="btn_hitung_2">
                        🚀 Hitung Bagian Tengah
                    </button>

                    <div class="bagian-hasil" id="hasil_bagian_2" style="display:none;">
                        <div class="table-container" id="table_bagian_2"></div>
                        <div class="subtotal">
                            <span class="label">Sub Total :  </span>
                            <span id="subtotal_bagian_2">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== BAGIAN KANAN ===== -->
            <div class="bagian-wrapper" id="bagian_3_wrapper">
                <div class="bagian-header bagian-kanan" onclick="toggleBagian('3')">
                    <span>Bagian Kanan - 1 Sisi Kemiringan</span>
                    <span class="bagian-toggle" id="bagian_3_toggle">▶</span>
                </div>
                <div class="bagian-body collapsed" id="bagian_3_body">
                    <div class="pilih-material">
                        <div class="row"><span class="label">Produk Atap Utama <span class="required-star">*</span></span>
                            <span class="value"><select id="produk_atap_3" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products ?? [] as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Sistem Pemasangan</span>
                            <span class="value"><select id="sistem_pemasangan_3" onchange="toggleFields(3)">
                                <option value="expose">Expose</option>
                                <option value="non-expose">Non-Expose</option>
                            </select></span>
                        </div>
                        <div class="row" id="underlayer_container_3" style="display:none;"><span class="label">Underlayer</span>
                            <span class="value"><select id="underlayer_3">
                                <option value="">Pilih Underlayer</option>
                                @foreach($underlayers_3 ?? [] as $underlayer)
                                    <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                                @endforeach
                            </select></span>
                        </div>
                        <div class="row" id="lantai_kerja_container_3" style="display:none;"><span class="label">Lantai Kerja</span>
                            <span class="value"><select id="lantai_kerja_3">
                                <option value="Plywood 9 mm" selected>Plywood 9 mm</option>
                                <option value="Plywood 12 mm">Plywood 12 mm</option>
                                <option value="Plywood 15 mm">Plywood 15 mm</option>
                                <option value="GRC 9 mm">GRC 9 mm</option>
                                <option value="GRC 12 mm">GRC 12 mm</option>
                                <option value="GRC 15 mm">GRC 15 mm</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Struktur Rangka</span>
                            <span class="value"><select id="rangka_3">
                                <option value="Kayu">Kayu</option>
                                <option value="Baja Ringan" selected>Baja Ringan</option>
                                <option value="Baja Berat">Baja Berat</option>
                                <option value="Beton">Beton</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Coverage <span class="required-star">*</span></span>
                            <span class="value"><select id="coverage_3" required>
                                <option value="">Pilih Coverage</option>
                                <option value="8">8 daun / m²</option>
                                <option value="7">7 daun / m²</option>
                                <option value="6">6 daun / m²</option>
                            </select></span>
                        </div>
                        <div class="row"><span class="label">Waste <span class="required-star">*</span></span>
                            <span class="value"><div class="waste-inline"><input type="number" id="waste_3" step="1" value="5"><span>%</span></div></span>
                        </div>
                    </div>

                    <div class="opsi-tambahan" style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
                        <div class="opsi-title" style="font-size:12px;margin-bottom:6px;">Opsi Tambahan</div>
                        <div class="opsi-grid" style="padding-left:24px;">
                            <div class="opsi-item">
                                <label>Dinding <span class="opsi-desc">(panjang atap berbatasan dinding)</span></label>
                                <input type="number" id="opsi_dinding_3" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                            <div class="opsi-item">
                                <label>Kaca <span class="opsi-desc">(panjang area kaca/genteng kaca)</span></label>
                                <input type="number" id="opsi_kaca_3" step="0.1" min="0" placeholder="0" value="0">
                                <span class="satuan">meter</span>
                            </div>
                        </div>
                    </div>

                    <button onclick="hitungBagian(3)" class="btn-hitung-bagian" id="btn_hitung_3">
                        🚀 Hitung Bagian Kanan
                    </button>

                    <div class="bagian-hasil" id="hasil_bagian_3" style="display:none;">
                        <div class="table-container" id="table_bagian_3"></div>
                        <div class="subtotal">
                            <span class="label">Sub Total :  </span>
                            <span id="subtotal_bagian_3">Rp 0</span>
                        </div>
                    </div>
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
        
        <form action="/boq/palmex/atap-kombinasi/lengkung-2-sisi/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="jenis" value="lengkung-2-sisi">
            <input type="hidden" name="judul" value="BOQ - Lengkung + 2 Sisi Miring">
            <input type="hidden" name="brand" value="PALMEX">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            
            <!-- BAGIAN 1 - KIRI -->
            <input type="hidden" name="bagian1[data_perhitungan][luas_atap]" id="pdf_luas_atap_1">
            <input type="hidden" name="bagian1[data_perhitungan][sudut]" id="pdf_sudut_1">
            <input type="hidden" name="bagian1[data_perhitungan][starter]" id="pdf_starter_1">
            <input type="hidden" name="bagian1[data_perhitungan][flashing]" id="pdf_flashing_1">
            <input type="hidden" name="bagian1[total]" id="pdf_total_1">
            <input type="hidden" name="bagian1[hasil]" id="pdf_hasil_1">
            
            <!-- BAGIAN 2 - TENGAH -->
            <input type="hidden" name="bagian2[data_perhitungan][luas_atap]" id="pdf_luas_atap_2">
            <input type="hidden" name="bagian2[data_perhitungan][tinggi]" id="pdf_tinggi_2">
            <input type="hidden" name="bagian2[data_perhitungan][starter]" id="pdf_starter_2">
            <input type="hidden" name="bagian2[data_perhitungan][nok_atas]" id="pdf_nok_atas_2">
            <input type="hidden" name="bagian2[data_perhitungan][flashing]" id="pdf_flashing_2">
            <input type="hidden" name="bagian2[total]" id="pdf_total_2">
            <input type="hidden" name="bagian2[hasil]" id="pdf_hasil_2">
            
            <!-- BAGIAN 3 - KANAN -->
            <input type="hidden" name="bagian3[data_perhitungan][luas_atap]" id="pdf_luas_atap_3">
            <input type="hidden" name="bagian3[data_perhitungan][sudut]" id="pdf_sudut_3">
            <input type="hidden" name="bagian3[data_perhitungan][starter]" id="pdf_starter_3">
            <input type="hidden" name="bagian3[data_perhitungan][flashing]" id="pdf_flashing_3">
            <input type="hidden" name="bagian3[total]" id="pdf_total_3">
            <input type="hidden" name="bagian3[hasil]" id="pdf_hasil_3">
            
            <!-- WASTE -->
            <input type="hidden" name="waste_1" id="pdf_waste_1">
            <input type="hidden" name="waste_2" id="pdf_waste_2">
            <input type="hidden" name="waste_3" id="pdf_waste_3">
            
            <button type="submit" class="btn-pdf btn-master-pdf" id="btnExportPDF" disabled>📄 Export PDF</button>
        </form>
    </div>
</div>

<script>
let results1 = [], results2 = [], results3 = [];
let total1 = 0, total2 = 0, total3 = 0;

// ===== TOGGLE SECTION =====
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

// ===== TOGGLE SUB SECTION =====
function toggleSubSection(section) {
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

// ===== TOGGLE BAGIAN =====
function toggleBagian(bagian) {
    let body = document.getElementById('bagian_' + bagian + '_body');
    let toggle = document.getElementById('bagian_' + bagian + '_toggle');
    
    if (!body || !toggle) return;
    
    if (body.classList.contains('collapsed')) {
        body.classList.remove('collapsed');
        toggle.textContent = '▼';
        toggle.classList.remove('collapsed');
    } else {
        body.classList.add('collapsed');
        toggle.textContent = '▶';
        toggle.classList.add('collapsed');
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

function toggleFields(bagian) {
    let sistemEl = document.getElementById('sistem_pemasangan_' + bagian);
    if (!sistemEl) return;
    
    let sistem = sistemEl.value;
    let sudutEl = document.getElementById('sudut_' + bagian);
    let sudut = sudutEl ? parseFloat(sudutEl.value) || 0 : 0;
    
    let underlayerContainer = document.getElementById('underlayer_container_' + bagian);
    let lantaiKerjaContainer = document.getElementById('lantai_kerja_container_' + bagian);
    let sistemSelect = document.getElementById('sistem_pemasangan_' + bagian);
    
    if (!sistemSelect) return;
    
    // ===== UNTUK BAGIAN TENGAH (LENGKUNG) FORCE NON-EXPOSE =====
    if (bagian === 2) {
        sistemSelect.value = 'non-expose';
        sistem = 'non-expose';
        sistemSelect.disabled = true;
        sistemSelect.style.opacity = '0.7';
        sistemSelect.style.backgroundColor = '#f7fafc';
    } else {
        if (sudut < 30) {
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
    }
    
    // ===== SEMBUNYIKAN ATAU TAMPILKAN UNDERLAYER & LANTAI KERJA =====
    if (sistem === 'expose') {
        if (underlayerContainer) {
            underlayerContainer.style.display = 'none';
            // Reset value underlayer
            let underlayerSelect = document.getElementById('underlayer_' + bagian);
            if (underlayerSelect) underlayerSelect.value = '';
        }
        if (lantaiKerjaContainer) {
            lantaiKerjaContainer.style.display = 'none';
        }
    } else {
        if (underlayerContainer) {
            underlayerContainer.style.display = 'flex';
        }
        if (lantaiKerjaContainer) {
            lantaiKerjaContainer.style.display = 'flex';
        }
    }
}

// ===== RENDER TABLE HASIL =====
function renderResultTable(results, containerId) {
    let container = document.getElementById(containerId);
    if (!container) return;
    
    if (!results || results.length === 0) {
        container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
        return;
    }
    
    // ===== AMBIL SISTEM PEMASANGAN DARI CONTAINER ID =====
    let bagian = containerId.replace('table_bagian_', '');
    let sistemSelect = document.getElementById('sistem_pemasangan_' + bagian);
    let sistemPemasangan = sistemSelect ? sistemSelect.value : 'expose';
    
    let kelompok = {
        'Atap Utama': [],
        'Aksesoris': [],
        'Additional': [],
        'Sistem Pendukung': []
    };

    const aksesorisAreas = ['Starter', 'Nok Atas', 'Nok Jurai', 'Topcap', 'Screw', 'Rail', 'Wind', 'Metal Flashing', 'Talang Jurai', 'Flashing', 'Paku & Screw', 'Shingle Stick'];
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca'];
    const sistemPendukungAreas = ['Underlayer', 'Lantai Kerja', 'Paku & Screw'];

    results.forEach(function(item) {
        const area = item.area || '';
        
        // ===== FILTER: Jika Expose, SEMBUNYIKAN Underlayer dan Lantai Kerja =====
        if (sistemPemasangan === 'expose' && (area === 'Underlayer' || area === 'Lantai Kerja')) {
            return; // skip item, tidak ditampilkan
        }
        
        if (area === 'Atap Utama') {
            kelompok['Atap Utama'].push(item);
        } else if (additionalAreas.includes(area) && item.qty > 0) {
            kelompok['Additional'].push(item);
        } else if (sistemPendukungAreas.includes(area)) {
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

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    document.querySelectorAll('.sub-section-body').forEach(function(el) {
        el.classList.add('collapsed');
    });
    document.querySelectorAll('.sub-toggle').forEach(function(el) {
        el.classList.add('collapsed');
        el.textContent = '▶';
    });
    
    document.querySelectorAll('.bagian-body').forEach(function(el) {
        el.classList.add('collapsed');
    });
    document.querySelectorAll('.bagian-toggle').forEach(function(el) {
        el.classList.add('collapsed');
        el.textContent = '▶';
    });
    
    setVal('luas_atap_1', urlParams.get('luas_atap_1') || 0);
    setVal('sudut_1', urlParams.get('sudut_1') || 0);
    setVal('starter_1', urlParams.get('starter_1') || 0);
    setVal('flashing_1', urlParams.get('flashing_1') || 0);
    
    setVal('luas_atap_2', urlParams.get('luas_atap_2') || 0);
    setVal('tinggi_2', urlParams.get('tinggi') || 0);
    setVal('starter_2', urlParams.get('starter_2') || 0);
    setVal('nok_atas_2', urlParams.get('nok_atas_2') || urlParams.get('panjang_b') || 0);
    setVal('flashing_2', urlParams.get('flashing_2') || 0);
    
    setVal('luas_atap_3', urlParams.get('luas_atap_3') || 0);
    setVal('sudut_3', urlParams.get('sudut_3') || 0);
    setVal('starter_3', urlParams.get('starter_3') || 0);
    setVal('flashing_3', urlParams.get('flashing_3') || 0);
    
    var sistem1 = document.getElementById('sistem_pemasangan_1');
    var sistem2 = document.getElementById('sistem_pemasangan_2');
    var sistem3 = document.getElementById('sistem_pemasangan_3');
    
    if (sistem1) sistem1.value = 'expose';
    if (sistem2) sistem2.value = 'expose';
    if (sistem3) sistem3.value = 'expose';
    
    toggleFields(1);
    toggleFields(2);
    toggleFields(3);
    
    ['1', '2', '3'].forEach(function(b) {
        var el = document.getElementById('sistem_pemasangan_' + b);
        if (el) {
            el.addEventListener('change', function() {
                toggleFields(parseInt(b));
            });
        }
    });
});

function hitungBagian(bagian) {
    var wasteEl = document.getElementById('waste_' + bagian);
    var opsiDindingEl = document.getElementById('opsi_dinding_' + bagian);
    var opsiKacaEl = document.getElementById('opsi_kaca_' + bagian);
    var luasEl = document.getElementById('luas_atap_' + bagian);
    var sudutEl = document.getElementById('sudut_' + bagian);
    var starterEl = document.getElementById('starter_' + bagian);
    var flashingEl = document.getElementById('flashing_' + bagian);
    
    var wasteValue = wasteEl ? parseFloat(wasteEl.value) || 5 : 5;
    var opsiDinding = opsiDindingEl ? parseFloat(opsiDindingEl.value) || 0 : 0;
    var opsiKaca = opsiKacaEl ? parseFloat(opsiKacaEl.value) || 0 : 0;
    var luas = luasEl ? parseFloat(luasEl.value) || 0 : 0;
    var sudut = sudutEl ? parseFloat(sudutEl.value) || 0 : 0;
    var starter = starterEl ? parseFloat(starterEl.value) || 0 : 0;
    var flashing = flashingEl ? parseFloat(flashingEl.value) || 0 : 0;
    
    var produk = document.getElementById('produk_atap_' + bagian);
    var underlayer = document.getElementById('underlayer_' + bagian);
    var lantaiKerja = document.getElementById('lantai_kerja_' + bagian);
    var sistem = document.getElementById('sistem_pemasangan_' + bagian);
    var coverage = document.getElementById('coverage_' + bagian);
    var rangka = document.getElementById('rangka_' + bagian);
    
    var jenis = (bagian === 2) ? 'lengkung' : 'pelana';
    var tinggi = 0;
    var nokAtas = 0;
    var nokAtasId = '';
    
    if (bagian === 2) {
        var tinggiEl = document.getElementById('tinggi_2');
        var nokAtasEl = document.getElementById('nok_atas_2');
        var nokAtasDropdown = document.getElementById('nok_atas_dropdown');
        
        tinggi = tinggiEl ? parseFloat(tinggiEl.value) || 0 : 0;
        nokAtas = nokAtasEl ? parseFloat(nokAtasEl.value) || 0 : 0;
        nokAtasId = nokAtasDropdown ? nokAtasDropdown.value || '' : '';
    }
    
    if (!produk || !produk.value) {
        alert('⚠️ Pilih produk atap utama untuk Bagian ' + bagian + '!');
        return;
    }
    if (!coverage || !coverage.value) {
        alert('⚠️ Pilih Coverage untuk Bagian ' + bagian + '!');
        return;
    }
    if (bagian === 2 && !nokAtasId) {
        alert('⚠️ Pilih Nok Atas untuk Bagian Tengah (wajib)!');
        var nokDropdown = document.getElementById('nok_atas_dropdown');
        if (nokDropdown) {
            nokDropdown.focus();
            nokDropdown.style.borderColor = '#e53e3e';
            setTimeout(function() { nokDropdown.style.borderColor = ''; }, 3000);
        }
        return;
    }
    if (sistem && sistem.value === 'non-expose' && (!underlayer || !underlayer.value)) {
        alert('⚠️ Pilih Underlayer untuk Bagian ' + bagian + ' (Non-Expose)!');
        return;
    }
    
    var btn = document.getElementById('btn_hitung_' + bagian);
    if (!btn) {
        alert('Tombol hitung tidak ditemukan!');
        return;
    }
    
    var originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Menghitung...';
    btn.disabled = true;
    
    var data = {
        luas_atap: luas,
        sudut: sudut,
        panjang_starter: starter,
        panjang_jurai: 0,
        panjang_nok_atas: nokAtas,
        panjang_flashing: flashing,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: opsiDinding,
        opsi_kaca: opsiKaca,
        opsi_exhaust: 0,
        sistem_pemasangan: sistem ? sistem.value : 'expose',
        produk_atap_id: produk ? produk.value : '',
        nok_atas_id: nokAtasId,
        underlayer_id: underlayer ? underlayer.value : '',
        rangka: rangka ? rangka.value : 'Baja Ringan',
        lantai_kerja: lantaiKerja ? lantaiKerja.value : 'Plywood 9 mm',
        waste: wasteValue,
        coverage: coverage ? parseFloat(coverage.value) : 7,
        jenis: jenis,
        tinggi: tinggi
    };
    
    fetch('/boq/palmex/atap-kombinasi/lengkung-2-sisi/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(function(res) {
        return res.json();
    })
    .then(function(result) {
        var hasil = result.results || [];
        var total = hasil.reduce(function(s, i) { return s + (i.total_harga || 0); }, 0);
        
        // ===== SIMPAN DATA PER BAGIAN & ISI HIDDEN INPUT =====
        if (bagian === 1) { 
            results1 = hasil; 
            total1 = total;
            document.getElementById('pdf_luas_atap_1').value = luas;
            document.getElementById('pdf_sudut_1').value = sudut;
            document.getElementById('pdf_starter_1').value = starter;
            document.getElementById('pdf_flashing_1').value = flashing;
            document.getElementById('pdf_total_1').value = total;
            document.getElementById('pdf_hasil_1').value = JSON.stringify(hasil);
            document.getElementById('pdf_waste_1').value = wasteValue;
        }
        else if (bagian === 2) { 
            results2 = hasil; 
            total2 = total;
            document.getElementById('pdf_luas_atap_2').value = luas;
            document.getElementById('pdf_tinggi_2').value = tinggi;
            document.getElementById('pdf_starter_2').value = starter;
            document.getElementById('pdf_nok_atas_2').value = nokAtas;
            document.getElementById('pdf_flashing_2').value = flashing;
            document.getElementById('pdf_total_2').value = total;
            document.getElementById('pdf_hasil_2').value = JSON.stringify(hasil);
            document.getElementById('pdf_waste_2').value = wasteValue;
        }
        else if (bagian === 3) { 
            results3 = hasil; 
            total3 = total;
            document.getElementById('pdf_luas_atap_3').value = luas;
            document.getElementById('pdf_sudut_3').value = sudut;
            document.getElementById('pdf_starter_3').value = starter;
            document.getElementById('pdf_flashing_3').value = flashing;
            document.getElementById('pdf_total_3').value = total;
            document.getElementById('pdf_hasil_3').value = JSON.stringify(hasil);
            document.getElementById('pdf_waste_3').value = wasteValue;
        }
        
        // Tampilkan hasil di UI
        var hasilDiv = document.getElementById('hasil_bagian_' + bagian);
        var tableDiv = document.getElementById('table_bagian_' + bagian);
        var subtotalSpan = document.getElementById('subtotal_bagian_' + bagian);
        
        renderResultTable(hasil, 'table_bagian_' + bagian);
        if (subtotalSpan) subtotalSpan.textContent = 'Rp ' + total.toLocaleString();
        if (hasilDiv) hasilDiv.style.display = 'block';
        
        // Update Grand Total
        var grandTotal = total1 + total2 + total3;
        var totalEl = document.getElementById('totalKeseluruhan');
        if (totalEl) totalEl.textContent = 'Rp ' + grandTotal.toLocaleString();
        
        // ISI GRAND TOTAL DI HIDDEN INPUT
        document.getElementById('pdf_grand_total').value = grandTotal;
        
        // Enable PDF button jika semua bagian sudah dihitung
        var btnPDF = document.getElementById('btnExportPDF');
        if (btnPDF && results1.length > 0 && results2.length > 0 && results3.length > 0) {
            btnPDF.disabled = false;
        }
        
        btn.innerHTML = originalText;
        btn.disabled = false;
    })
    .catch(function(err) {
        console.error(err);
        alert('Terjadi kesalahan server: ' + err.message);
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
@endsection