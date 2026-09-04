@extends('layouts.app')

@section('title', 'BOQ EMARINROOF - Lengkung + 2 Sisi Miring')

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
    
    .header-main {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 12px;
        padding: 24px 28px;
        color: white;
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
    
    .row-insulation {
        background: #f0fdf4 !important;
    }
    .row-insulation td {
        background: #f0fdf4 !important;
    }
    
    /* ===== DATA GEOMETRIK - LIST DENGAN SUB BAGIAN & COLLAPSE ===== */
    .data-geometrik {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 4px 0;
    }
    
    .data-geometrik .sub-section {
        margin-bottom: 4px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .data-geometrik .sub-section .sub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 14px;
        background: #f7fafc;
        cursor: pointer;
        user-select: none;
        transition: background 0.2s;
    }
    
    .data-geometrik .sub-section .sub-header:hover {
        background: #edf2f7;
    }
    
    .data-geometrik .sub-section .sub-header .sub-title {
        font-size: 11px;
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .data-geometrik .sub-section .sub-header .toggle-icon {
        font-size: 12px;
        color: #94a3b8;
        transition: transform 0.3s ease;
    }
    
    .data-geometrik .sub-section .sub-header .toggle-icon.open {
        transform: rotate(180deg);
    }
    
    .data-geometrik .sub-section .sub-content {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
    }
    
    .data-geometrik .sub-section .sub-content.open {
        max-height: 500px;
    }
    
    .data-geometrik .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 14px;
        border-top: 1px solid #f1f4f9;
    }
    
    .data-geometrik .row:first-child {
        border-top: none;
    }
    
    .data-geometrik .row .label {
        font-size: 11px;
        font-weight: 500;
        color: #4a5568;
    }
    
    .data-geometrik .row .value {
        font-size: 12px;
        font-weight: 500;
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
        font-weight: 500;
        color: #1e293b;
        width: 70px;
        padding: 0;
        text-align: right;
    }
    
    .data-geometrik .total-section {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 2px solid #1a1a2e;
        padding-left: 20px;
    }
    
    .data-geometrik .total-section .total-title {
        font-size: 11px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 2px;
        padding: 4px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .data-geometrik .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 0;
        border-bottom: 1px solid #f1f4f9;
        padding-left: 20px;
    }
    
    .data-geometrik .total-row:last-child {
        border-bottom: none;
    }
    
    .data-geometrik .total-row .label {
        font-size: 11px;
        font-weight: 500;
        color: #4a5568;
    }
    
    .data-geometrik .total-row .value {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .data-geometrik .total-row .value .unit {
        font-weight: 400;
        color: #94a3b8;
        margin-left: 2px;
    }
    
    .data-geometrik .total-row .value input[readonly] {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
        width: 70px;
        padding: 0;
        text-align: right;
    }
    
    @media (max-width: 768px) {
        .data-geometrik .sub-section {
            padding-left: 0;
        }
        .data-geometrik .row {
            padding: 5px 10px;
        }
        .data-geometrik .sub-section .sub-header {
            padding: 6px 10px;
        }
        .data-geometrik .total-section {
            padding-left: 10px;
        }
        .data-geometrik .total-row {
            padding-left: 10px;
        }
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
        grid-template-columns: repeat(3, 1fr);
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
    
    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        background-color: #cbd5e1;
        border-radius: 12px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .toggle-slider .toggle-dot {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .toggle-switch.active .toggle-slider {
        background-color: #2563eb;
    }
    .toggle-switch.active .toggle-slider .toggle-dot {
        transform: translateX(20px);
    }
    .toggle-status {
        font-size: 11px;
        font-weight: 500;
        margin-left: 8px;
        color: #94a3b8;
        transition: all 0.3s ease;
    }
    .toggle-switch.active .toggle-status {
        color: #16a34a;
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
    
    .lebar-info-success {
        color: #16a34a;
        font-weight: 500;
    }
    .lebar-info-default {
        color: #9ca3af;
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

    .nok-auto-select {
        background: #f0fdf4 !important;
        border-color: #86efac !important;
    }
    
    .nok-auto-select:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1) !important;
    }
</style>

<div class="space-y-6">

    <!-- NOTES -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Petunjuk Pengisian
        </div>
        <ul class="notes-list">
            <li>
                <span class="bullet">•</span>
                <span>Cek lebih detail apakah ada atap yang bertemu langsung dengan <strong>dinding</strong>, <strong>cerobong asap</strong>, <strong>penangkal petir</strong> dll.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Jika bertemu dinding, cerobong asap, penangkal petir, silahkan input berapa panjang/area pertemuannya di bagian <strong>Opsi Tambahan</strong>.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Pilih jenis <strong>Nok</strong> (U / V / Bulat), maka <strong>Nok Tutup</strong> akan otomatis menyesuaikan.</span>
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
            <li>
                <span class="bullet">•</span>
                <span>Untuk atap yang banyak nok dan jurai disarankan menggunakan type <strong>1x4 atau 1x5</strong></span>
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
                <!-- Bagian 1 -->
                <div class="sub-section">
                    <div class="sub-header" onclick="toggleSection(this)">
                        <span class="sub-title">▶ Bagian 1 - Sisi Kiri (1 Kemiringan)</span>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="sub-content">
                        <div class="row">
                            <span class="label">Luas Atap</span>
                            <span class="value">
                                <input type="text" id="luas_atap_1" readonly>
                                <span class="unit">m²</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Sudut</span>
                            <span class="value">
                                <input type="text" id="sudut_1" readonly>
                                <span class="unit">°</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Starter</span>
                            <span class="value">
                                <input type="text" id="starter_1" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Flashing</span>
                            <span class="value">
                                <input type="text" id="flashing_1" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Nok & Jurai</span>
                            <span class="value">
                                <input type="text" id="nok_1" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2 -->
                <div class="sub-section">
                    <div class="sub-header" onclick="toggleSection(this)">
                        <span class="sub-title">▶ Bagian 2 - Tengah (Setengah Tabung)</span>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="sub-content">
                        <div class="row">
                            <span class="label">Luas Atap</span>
                            <span class="value">
                                <input type="text" id="luas_atap_2" readonly>
                                <span class="unit">m²</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Sudut</span>
                            <span class="value">
                                <input type="text" id="sudut_2" readonly>
                                <span class="unit">°</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Starter</span>
                            <span class="value">
                                <input type="text" id="starter_2" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Flashing</span>
                            <span class="value">
                                <input type="text" id="flashing_2" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Nok & Jurai</span>
                            <span class="value">
                                <input type="text" id="nok_2" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3 -->
                <div class="sub-section">
                    <div class="sub-header" onclick="toggleSection(this)">
                        <span class="sub-title">▶ Bagian 3 - Sisi Kanan (1 Kemiringan)</span>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="sub-content">
                        <div class="row">
                            <span class="label">Luas Atap</span>
                            <span class="value">
                                <input type="text" id="luas_atap_3" readonly>
                                <span class="unit">m²</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Sudut</span>
                            <span class="value">
                                <input type="text" id="sudut_3" readonly>
                                <span class="unit">°</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Starter</span>
                            <span class="value">
                                <input type="text" id="starter_3" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Flashing</span>
                            <span class="value">
                                <input type="text" id="flashing_3" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Nok & Jurai</span>
                            <span class="value">
                                <input type="text" id="nok_3" readonly>
                                <span class="unit">m</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- TOTAL -->
                <div class="total-section">
                    <div class="total-title">Total</div>
                    <div class="total-row">
                        <span class="label">Luas Atap</span>
                        <span class="value">
                            <input type="text" id="total_luas_atap" readonly>
                            <span class="unit">m²</span>
                        </span>
                    </div>
                    <div class="total-row">
                        <span class="label">Starter</span>
                        <span class="value">
                            <input type="text" id="total_starter" readonly>
                            <span class="unit">m</span>
                        </span>
                    </div>
                    <div class="total-row">
                        <span class="label">Flashing</span>
                        <span class="value">
                            <input type="text" id="total_flashing" readonly>
                            <span class="unit">m</span>
                        </span>
                    </div>
                    <div class="total-row">
                        <span class="label">Nok & Jurai</span>
                        <span class="value">
                            <input type="text" id="total_nok_jurai" readonly>
                            <span class="unit">m</span>
                        </span>
                    </div>
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
                <!-- TIPE ATAP DIHAPUS - LANGSUNG KE JENIS NOK -->
                
                <div class="row">
                    <span class="label">Jenis Nok</span>
                    <span class="value">
                        <select id="nok_dropdown" onchange="updateNokOptions()">
                            <option value="">Pilih Jenis Nok</option>
                            @foreach($nokOptions ?? [] as $nok)
                                <option value="{{ $nok->id }}" data-tipe="{{ $nok->productTipe->kode_tipe ?? 'U' }}">
                                    {{ $nok->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </span>
                </div>
                
                <div class="row">
                    <span class="label">Struktur Rangka</span>
                    <span class="value">
                        <select id="rangka">
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                            <option value="Kayu">Kayu</option>
                        </select>
                    </span>
                </div>
                
                <div class="row">
                    <span class="label">Lantai Kerja</span>
                    <span class="value">
                        <select id="lantai_kerja">
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
                    <span class="label">Waste</span>
                    <span class="value">
                        <div class="waste-inline">
                            <input type="number" id="waste" step="1" value="5">
                            <span>%</span>
                        </div>
                    </span>
                </div>
            </div>

            <input type="hidden" id="nok_tutup_id" value="">
            <input type="hidden" id="nok_tutup_display" value="">

            <div class="opsi-tambahan">
                <div class="opsi-title">Opsi Tambahan</div>
                <div class="opsi-grid">
                    <div class="opsi-item">
                        <label>Dinding</label>
                        <input type="number" id="opsi_dinding" step="0.1" min="0" placeholder="0" value="{{ $opsiDinding ?? 0 }}">
                        <span class="satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>Cerobong Asap</label>
                        <input type="number" id="opsi_cerobong" step="0.1" min="0" placeholder="0" value="{{ $opsiCerobong ?? 0 }}">
                        <span class="satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>Penangkal Petir</label>
                        <input type="number" id="opsi_penangkal" step="0.1" min="0" placeholder="0" value="{{ $opsiPenangkal ?? 0 }}">
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
                <p class="text-gray-200 text-[10px]">Lengkung 2 Sisi EMARINROOF (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="{{ route('boq.emarinroof.lengkung-2-sisi.export-pdf') }}" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Lengkung 2 Sisi EMARINROOF">
            <input type="hidden" name="brand" value="EMARINROOF">
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
            
            <input type="hidden" name="nok_atas" id="pdf_nok_atas">
            <input type="hidden" name="nok_tutup" id="pdf_nok_tutup">
            
            <input type="hidden" name="luas_atap_1" id="pdf_luas_atap_1">
            <input type="hidden" name="starter_1" id="pdf_starter_1">
            <input type="hidden" name="flashing_1" id="pdf_flashing_1">
            <input type="hidden" name="nok_1" id="pdf_nok_1">
            <input type="hidden" name="sudut_1" id="pdf_sudut_1">
            
            <input type="hidden" name="luas_atap_2" id="pdf_luas_atap_2">
            <input type="hidden" name="starter_2" id="pdf_starter_2">
            <input type="hidden" name="flashing_2" id="pdf_flashing_2">
            <input type="hidden" name="nok_2" id="pdf_nok_2">
            <input type="hidden" name="sudut_2" id="pdf_sudut_2">
            
            <input type="hidden" name="luas_atap_3" id="pdf_luas_atap_3">
            <input type="hidden" name="starter_3" id="pdf_starter_3">
            <input type="hidden" name="flashing_3" id="pdf_flashing_3">
            <input type="hidden" name="nok_3" id="pdf_nok_3">
            <input type="hidden" name="sudut_3" id="pdf_sudut_3">
            
            <button type="submit" class="btn-pdf btn-master-pdf">
                Export PDF
            </button>
        </form>
    </div>
</div>

<script>
    // Fungsi Toggle Collapse
    function toggleSection(header) {
        const content = header.nextElementSibling;
        const icon = header.querySelector('.toggle-icon');
        
        if (content.classList.contains('open')) {
            content.classList.remove('open');
            icon.classList.remove('open');
            header.querySelector('.sub-title').textContent = '▶ ' + header.querySelector('.sub-title').textContent.substring(2).trim();
        } else {
            content.classList.add('open');
            icon.classList.add('open');
            header.querySelector('.sub-title').textContent = '▼ ' + header.querySelector('.sub-title').textContent.substring(2).trim();
        }
    }

    // Set default semua tertutup
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sub-content').forEach(function(el) {
            el.classList.remove('open');
        });
        document.querySelectorAll('.toggle-icon').forEach(function(el) {
            el.classList.remove('open');
        });
        document.querySelectorAll('.sub-header .sub-title').forEach(function(el) {
            let text = el.textContent.trim();
            if (!text.startsWith('▶') && !text.startsWith('▼')) {
                el.textContent = '▶ ' + text;
            }
        });
    });

    const nokMapping = @json($nokMapping ?? []);

    function updateNokOptions() {
        const nokDropdown = document.getElementById('nok_dropdown');
        const nokId = nokDropdown.value;
        const nokTutupId = document.getElementById('nok_tutup_id');
        
        if (!nokId || !nokMapping[nokId]) {
            nokTutupId.value = '';
            return;
        }
        
        const data = nokMapping[nokId];
        
        if (data.nokTutupId) {
            nokTutupId.value = data.nokTutupId;
        } else {
            nokTutupId.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(updateNokOptions, 200);
    });

    let results = [];

    window.onload = function() {
        // Gunakan data dari PHP LANGSUNG
        const luasAtap1 = {{ $luasAtap1 ?? 0 }};
        const starter1 = {{ $starter1 ?? 0 }};
        const flashing1 = {{ $flashing1 ?? 0 }};
        const nok1 = {{ $nok1 ?? 0 }};
        const sudut1 = {{ $sudut1 ?? 0 }};
        
        const luasAtap2 = {{ $luasAtap2 ?? 0 }};
        const starter2 = {{ $starter2 ?? 0 }};
        const flashing2 = {{ $flashing2 ?? 0 }};
        const nok2 = {{ $nok2 ?? 0 }};
        const sudut2 = {{ $sudut2 ?? 0 }};
        
        const luasAtap3 = {{ $luasAtap3 ?? 0 }};
        const starter3 = {{ $starter3 ?? 0 }};
        const flashing3 = {{ $flashing3 ?? 0 }};
        const nok3 = {{ $nok3 ?? 0 }};
        const sudut3 = {{ $sudut3 ?? 0 }};
        
        // Bagian 1
        document.getElementById('luas_atap_1').value = luasAtap1;
        document.getElementById('starter_1').value = starter1;
        document.getElementById('flashing_1').value = flashing1;
        document.getElementById('nok_1').value = nok1;
        document.getElementById('sudut_1').value = sudut1;
        
        // Bagian 2
        document.getElementById('luas_atap_2').value = luasAtap2;
        document.getElementById('starter_2').value = starter2;
        document.getElementById('flashing_2').value = flashing2;
        document.getElementById('nok_2').value = nok2;
        document.getElementById('sudut_2').value = sudut2;
        
        // Bagian 3
        document.getElementById('luas_atap_3').value = luasAtap3;
        document.getElementById('starter_3').value = starter3;
        document.getElementById('flashing_3').value = flashing3;
        document.getElementById('nok_3').value = nok3;
        document.getElementById('sudut_3').value = sudut3;
        
        // Total
        let totalLuas = luasAtap1 + luasAtap2 + luasAtap3;
        let totalStarter = starter1 + starter2 + starter3;
        let totalFlashing = flashing1 + flashing2 + flashing3;
        let totalNok = nok1 + nok2 + nok3;
        
        document.getElementById('total_luas_atap').value = totalLuas;
        document.getElementById('total_starter').value = totalStarter;
        document.getElementById('total_flashing').value = totalFlashing;
        document.getElementById('total_nok_jurai').value = totalNok;
        
        document.getElementById('opsi_dinding').value = {{ $opsiDinding ?? 0 }};
        document.getElementById('opsi_cerobong').value = {{ $opsiCerobong ?? 0 }};
        document.getElementById('opsi_penangkal').value = {{ $opsiPenangkal ?? 0 }};
        
        setTimeout(updateNokOptions, 300);
        updatePdfData();
    };

    function hitungMaterial() {
        let btn = event.target;
        let originalText = btn.innerHTML;
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
        
        let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
        
        let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
        let opsiCerobong = parseFloat(document.getElementById('opsi_cerobong')?.value) || 0;
        let opsiPenangkal = parseFloat(document.getElementById('opsi_penangkal')?.value) || 0;
        
        let nokDropdown = document.getElementById('nok_dropdown');
        let nokId = nokDropdown ? nokDropdown.value : '';
        let nokTutupId = document.getElementById('nok_tutup_id')?.value || '';
        
        if (!nokId) {
            alert('Pilih Jenis Nok terlebih dahulu!');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        if (!nokTutupId) {
            alert('Nok Tutup tidak tersedia untuk tipe yang dipilih! Silakan pilih Nok lain.');
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
            opsi_kaca: 0,
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
        
        fetch('{{ route("boq.emarinroof.lengkung-2-sisi.hitung") }}', {
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

    function updatePdfData() {
        document.getElementById('pdf_hasil').value = JSON.stringify(results);
        document.getElementById('pdf_luas_atap').value = document.getElementById('total_luas_atap').value;
        document.getElementById('pdf_starter').value = document.getElementById('total_starter').value;
        document.getElementById('pdf_nok_jurai').value = document.getElementById('total_nok_jurai').value;
        document.getElementById('pdf_flashing').value = document.getElementById('total_flashing').value;
        document.getElementById('pdf_sudut').value = 30;
        document.getElementById('pdf_waste').value = document.getElementById('waste').value;
        document.getElementById('pdf_grand_total').value = document.getElementById('grandTotal').innerText;
        
        document.getElementById('pdf_opsi_kaca').value = 0;
        document.getElementById('pdf_opsi_dinding').value = document.getElementById('opsi_dinding')?.value || 0;
        document.getElementById('pdf_opsi_cerobong').value = document.getElementById('opsi_cerobong')?.value || 0;
        document.getElementById('pdf_opsi_penangkal').value = document.getElementById('opsi_penangkal')?.value || 0;
        
        document.getElementById('pdf_lantai_kerja').value = document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm';
        document.getElementById('pdf_rangka').value = document.getElementById('rangka')?.value || 'Baja Ringan';
        document.getElementById('pdf_detail_results').value = JSON.stringify(results);
        
        document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
        document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
        
        document.getElementById('pdf_nok_atas').value = document.getElementById('nok_dropdown')?.selectedOptions[0]?.text || '';
        document.getElementById('pdf_nok_tutup').value = document.getElementById('nok_tutup_id')?.value || '';
        
        document.getElementById('pdf_luas_atap_1').value = document.getElementById('luas_atap_1').value;
        document.getElementById('pdf_starter_1').value = document.getElementById('starter_1').value;
        document.getElementById('pdf_flashing_1').value = document.getElementById('flashing_1').value;
        document.getElementById('pdf_nok_1').value = document.getElementById('nok_1').value;
        document.getElementById('pdf_sudut_1').value = document.getElementById('sudut_1').value;
        
        document.getElementById('pdf_luas_atap_2').value = document.getElementById('luas_atap_2').value;
        document.getElementById('pdf_starter_2').value = document.getElementById('starter_2').value;
        document.getElementById('pdf_flashing_2').value = document.getElementById('flashing_2').value;
        document.getElementById('pdf_nok_2').value = document.getElementById('nok_2').value;
        document.getElementById('pdf_sudut_2').value = document.getElementById('sudut_2').value;
        
        document.getElementById('pdf_luas_atap_3').value = document.getElementById('luas_atap_3').value;
        document.getElementById('pdf_starter_3').value = document.getElementById('starter_3').value;
        document.getElementById('pdf_flashing_3').value = document.getElementById('flashing_3').value;
        document.getElementById('pdf_nok_3').value = document.getElementById('nok_3').value;
        document.getElementById('pdf_sudut_3').value = document.getElementById('sudut_3').value;
    }
</script>
@endsection