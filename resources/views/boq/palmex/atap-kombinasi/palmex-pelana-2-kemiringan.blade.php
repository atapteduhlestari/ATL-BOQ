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

    @media (max-width: 768px) {
        .grid-cols-5 {
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

    /* NOTIFICATION TOAST */
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

    /* SISTEM WARNING */
    .sistem_warning {
        transition: all 0.3s ease;
        font-size: 11px;
    }

    .sistem_warning .warning-icon {
        font-size: 14px;
    }

    /* DISABLED SELECT STYLE */
    select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f7fafc !important;
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

    .coverage-info .badge.expose {
        background: #dbeafe;
        color: #1e40af;
    }

    .coverage-info .badge.non-expose {
        background: #fef3c7;
        color: #92400e;
    }

    .coverage-info .badge.auto {
        background: #d1fae5;
        color: #065f46;
    }

    /* REKOMENDASI COVERAGE */
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
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon palmex">P</div>
                <div>
                    <div class="brand-name">BOQ - PALMEX Pelana 2 Kemiringan</div>
                    <div class="brand-sub">Hitung kebutuhan material atap kombinasi Pelana 2 Kemiringan</div>
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
                    <span>Cek lebih detail apakah ada atap yang bertemu langsung dengan <strong>dinding</strong> atau <strong>kaca</strong>.</span>
                </li>
                <li>
                    <span class="bullet">•</span>
                    <span>Jika bertemu dinding atau kaca, silahkan input berapa panjang/area pertemuannya di bagian <strong>"Opsi Tambahan"</strong> masing-masing bagian atap.</span>
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
                    <span><strong>Nok Atas</strong> WAJIB dipilih dari dropdown untuk bagian Pelana (Tengah).</span>
                </li>
            </ul>
        </div>
        
        <!-- BAGIAN 1: KIRI -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🏠 Bagian 1 - Kiri (1 Kemiringan)</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk bagian Kiri</p>
                    </div>
                    <span class="badge-section">BAGIAN 1</span>
                </div>
            </div>
            <div class="section-body">
                <!-- ===== SISTEM PEMASANGAN BAGIAN 1 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                        <span class="sistem-label-wrapper">
                            Sistem Pemasangan
                            <span id="forced_badge_1" class="forced-badge hidden">FORCED NON-EXPOSE</span>
                        </span>
                    </label>
                    <select id="sistem_pemasangan_1" class="input-field" onchange="toggleFields(1)">
                        <option value="expose">Expose</option>
                        <option value="non-expose">Non-Expose</option>
                    </select>
                    <!-- ===== WARNING SISTEM ===== -->
                    <div id="sistem_warning_1" class="hidden mt-2 bg-yellow-50 border border-yellow-300 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2">
                        <span id="sistem_warning_text_1">Sudut atap < 30°, sistem otomatis NON-EXPOSE (tidak bisa diubah)</span>
                    </div>
                </div>

                <!-- ===== COVERAGE BAGIAN 1 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                        COVERAGE <span class="required-star">*</span>
                        <span id="coverage_info_text_1" class="font-normal text-gray-400 text-[9px]"></span>
                    </label>
                    <select id="coverage_1" class="input-field" required>
                        <option value="">Pilih Coverage</option>
                        <option value="8">8 daun / m²</option>
                        <option value="7">7 daun / m²</option>
                        <option value="6">6 daun / m²</option>
                    </select>
                    <div class="coverage-info">
                        <span id="coverage_status_1" class="badge">✎ Bebas</span>
                        <span id="coverage_description_1" class="text-gray-500">Pilih sesuai kebutuhan</span>
                    </div>
                    <div id="coverage_recommendation_1" class="coverage-recommendation hidden">
                        💡 Rekomendasi: <strong id="recommendation_text_1">7 daun/m²</strong>
                    </div>
                </div>

                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $luas_atap_1 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut_1 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $starter_1 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="display: none;">
                        <label>Jurai</label>
                        <div class="value">
                            <input type="number" id="jurai_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="0">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="display: none;">
                        <label>Nok Atas</label>
                        <div class="value">
                            <input type="number" id="nok_atas_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="0">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $flashing_1 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
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
                </div>

                <!-- Pilihan Material Bagian 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap_1" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="underlayer_container_1">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer_1" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_1 as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if($sudut_1 <= 30 && $sudut_1 > 0)
                            <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut_1 }}° (≤ 30°), disarankan menggunakan underlayer khusus ini.</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="rangka_1" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <div id="lantai_kerja_container_1">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Lantai Kerja</label>
                        <select id="lantai_kerja_1" class="input-field">
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
                            <input type="number" id="waste_1" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungBagian(1)" class="btn-primary">
                    Hitung Material Kiri
                </button>
                
                <div id="hasilBagian1" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Kiri</h4>
                        <div class="table-container" id="tableBagian1"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal1">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- BAGIAN 2: TENGAH (PELANA) -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">📐 Bagian 2 - Tengah (Pelana)</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk bagian Tengah (Pelana)</p>
                    </div>
                    <span class="badge-section">BAGIAN 2</span>
                </div>
            </div>
            <div class="section-body">
                <!-- ===== SISTEM PEMASANGAN BAGIAN 2 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                        <span class="sistem-label-wrapper">
                            Sistem Pemasangan
                            <span id="forced_badge_2" class="forced-badge hidden">FORCED NON-EXPOSE</span>
                        </span>
                    </label>
                    <select id="sistem_pemasangan_2" class="input-field" onchange="toggleFields(2)">
                        <option value="expose">Expose</option>
                        <option value="non-expose">Non-Expose</option>
                    </select>
                    <!-- ===== WARNING SISTEM ===== -->
                    <div id="sistem_warning_2" class="hidden mt-2 bg-yellow-50 border border-yellow-300 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2">
                        <span id="sistem_warning_text_2">Sudut atap < 30°, sistem otomatis NON-EXPOSE (tidak bisa diubah)</span>
                    </div>
                </div>

                <!-- ===== COVERAGE BAGIAN 2 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                        COVERAGE <span class="required-star">*</span>
                        <span id="coverage_info_text_2" class="font-normal text-gray-400 text-[9px]"></span>
                    </label>
                    <select id="coverage_2" class="input-field" required>
                        <option value="">Pilih Coverage</option>
                        <option value="8">8 daun / m²</option>
                        <option value="7">7 daun / m²</option>
                        <option value="6">6 daun / m²</option>
                    </select>
                    <div class="coverage-info">
                        <span id="coverage_status_2" class="badge">✎ Bebas</span>
                        <span id="coverage_description_2" class="text-gray-500">Pilih sesuai kebutuhan</span>
                    </div>
                    <div id="coverage_recommendation_2" class="coverage-recommendation hidden">
                        💡 Rekomendasi: <strong id="recommendation_text_2">7 daun/m²</strong>
                    </div>
                </div>

                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $luas_atap_2 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut_2 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $starter_2 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="display: none;">
                        <label>Jurai</label>
                        <div class="value">
                            <input type="number" id="jurai_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="0">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Nok Atas</label>
                        <div class="value">
                            <input type="number" id="nok_atas_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $nok_atas_2 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $flashing_2 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>

                <!-- ===== OPSI TAMBAHAN BAGIAN 2 ===== -->
                <div class="opsi-tambahan-grid">
                    <div class="opsi-item">
                        <label>
                            📐 Dinding
                            <span class="opsi-desc">(panjang atap yang berbatasan dinding)</span>
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
                            <span class="opsi-desc">(panjang area kaca/genteng kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca_2" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiKaca2 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                </div>

                <!-- Pilihan Material Bagian 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap_2" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ===== DROPDOWN NOK ATAS (WAJIB) ===== -->
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                            Nok Atas <span class="required-star">*</span>
                        </label>
                        <select id="nok_atas_dropdown_2" class="input-field" required>
                            <option value="">Pilih Nok Atas</option>
                            @foreach($nokAtasOptions ?? [] as $nok)
                                <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="underlayer_container_2">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer_2" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_2 as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="rangka_2" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <div id="lantai_kerja_container_2">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Lantai Kerja</label>
                        <select id="lantai_kerja_2" class="input-field">
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
                            <input type="number" id="waste_2" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungBagian(2)" class="btn-secondary">
                    Hitung Material Pelana
                </button>
                
                <div id="hasilBagian2" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Pelana</h4>
                        <div class="table-container" id="tableBagian2"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal2">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- BAGIAN 3: KANAN -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">📐 Bagian 3 - Kanan (1 Kemiringan)</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk bagian Kanan</p>
                    </div>
                    <span class="badge-section">BAGIAN 3</span>
                </div>
            </div>
            <div class="section-body">
                <!-- ===== SISTEM PEMASANGAN BAGIAN 3 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                        <span class="sistem-label-wrapper">
                            Sistem Pemasangan
                            <span id="forced_badge_3" class="forced-badge hidden">FORCED NON-EXPOSE</span>
                        </span>
                    </label>
                    <select id="sistem_pemasangan_3" class="input-field" onchange="toggleFields(3)">
                        <option value="expose">Expose</option>
                        <option value="non-expose">Non-Expose</option>
                    </select>
                    <!-- ===== WARNING SISTEM ===== -->
                    <div id="sistem_warning_3" class="hidden mt-2 bg-yellow-50 border border-yellow-300 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2">
                        <span id="sistem_warning_text_3">Sudut atap < 30°, sistem otomatis NON-EXPOSE (tidak bisa diubah)</span>
                    </div>
                </div>

                <!-- ===== COVERAGE BAGIAN 3 ===== -->
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                        COVERAGE <span class="required-star">*</span>
                        <span id="coverage_info_text_3" class="font-normal text-gray-400 text-[9px]"></span>
                    </label>
                    <select id="coverage_3" class="input-field" required>
                        <option value="">Pilih Coverage</option>
                        <option value="8">8 daun / m²</option>
                        <option value="7">7 daun / m²</option>
                        <option value="6">6 daun / m²</option>
                    </select>
                    <div class="coverage-info">
                        <span id="coverage_status_3" class="badge">✎ Bebas</span>
                        <span id="coverage_description_3" class="text-gray-500">Pilih sesuai kebutuhan</span>
                    </div>
                    <div id="coverage_recommendation_3" class="coverage-recommendation hidden">
                        💡 Rekomendasi: <strong id="recommendation_text_3">7 daun/m²</strong>
                    </div>
                </div>

                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $luas_atap_3 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $sudut_3 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Starter</label>
                        <div class="value">
                            <input type="number" id="starter_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $starter_3 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="display: none;">
                        <label>Jurai</label>
                        <div class="value">
                            <input type="number" id="jurai_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="0">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box" style="display: none;">
                        <label>Nok Atas</label>
                        <div class="value">
                            <input type="number" id="nok_atas_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="0">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Flashing</label>
                        <div class="value">
                            <input type="number" id="flashing_3" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $flashing_3 ?? 0 }}">
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                        </div>
                    </div>
                </div>

                <!-- ===== OPSI TAMBAHAN BAGIAN 3 ===== -->
                <div class="opsi-tambahan-grid">
                    <div class="opsi-item">
                        <label>
                            📐 Dinding
                            <span class="opsi-desc">(panjang atap yang berbatasan dinding)</span>
                        </label>
                        <input type="number" 
                               id="opsi_dinding_3" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiDinding3 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                    <div class="opsi-item">
                        <label>
                            🪟 Kaca
                            <span class="opsi-desc">(panjang area kaca/genteng kaca)</span>
                        </label>
                        <input type="number" 
                               id="opsi_kaca_3" 
                               step="0.1" 
                               min="0"
                               placeholder="0"
                               value="{{ $opsiKaca3 ?? 0 }}">
                        <span class="opsi-satuan">meter</span>
                    </div>
                </div>

                <!-- Pilihan Material Bagian 3 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-5">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap_3" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="underlayer_container_3">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer_3" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_3 as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Struktur Rangka</label>
                        <select id="rangka_3" class="input-field">
                            <option value="Kayu">Kayu</option>
                            <option value="Baja Ringan" selected>Baja Ringan</option>
                            <option value="Baja Berat">Baja Berat</option>
                            <option value="Beton">Beton</option>
                        </select>
                    </div>
                    <div id="lantai_kerja_container_3">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Lantai Kerja</label>
                        <select id="lantai_kerja_3" class="input-field">
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
                            <input type="number" id="waste_3" step="1" value="5" class="input-field" style="width: 100px;">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                
                <button onclick="hitungBagian(3)" class="btn-secondary">
                    Hitung Material Kanan
                </button>
                
                <div id="hasilBagian3" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Kanan</h4>
                        <div class="table-container" id="tableBagian3"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal3">Rp 0</div>
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
                <p class="text-gray-400 text-[10px]">Kiri + Pelana + Kanan (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/palmex/atap-kombinasi/pelana-2-kemiringan/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Pelana 2 Kemiringan">
            <input type="hidden" name="brand" value="PALMEX">
            
            <input type="hidden" name="waste_1" id="pdf_waste_1">
            <input type="hidden" name="waste_2" id="pdf_waste_2">
            <input type="hidden" name="waste_3" id="pdf_waste_3">
            
            <!-- BAGIAN 1 (KIRI) -->
            <input type="hidden" name="bagian1[data_perhitungan][luas_atap]" id="pdf_luas_1">
            <input type="hidden" name="bagian1[data_perhitungan][sudut]" id="pdf_sudut_1">
            <input type="hidden" name="bagian1[data_perhitungan][starter]" id="pdf_starter_1">
            <input type="hidden" name="bagian1[data_perhitungan][flashing]" id="pdf_flashing_1">
            <input type="hidden" name="bagian1[hasil]" id="pdf_hasil_1">
            <input type="hidden" name="bagian1[total]" id="pdf_total_1">
            <input type="hidden" name="bagian1[opsi][dinding]" id="pdf_opsi_dinding_1">
            <input type="hidden" name="bagian1[opsi][kaca]" id="pdf_opsi_kaca_1">
            <input type="hidden" name="bagian1[coverage]" id="pdf_coverage_1">
            
            <!-- BAGIAN 2 (PELANA) -->
            <input type="hidden" name="bagian2[data_perhitungan][luas_atap]" id="pdf_luas_2">
            <input type="hidden" name="bagian2[data_perhitungan][sudut]" id="pdf_sudut_2">
            <input type="hidden" name="bagian2[data_perhitungan][starter]" id="pdf_starter_2">
            <input type="hidden" name="bagian2[data_perhitungan][nok_atas]" id="pdf_nok_atas_2">
            <input type="hidden" name="bagian2[data_perhitungan][flashing]" id="pdf_flashing_2">
            <input type="hidden" name="bagian2[hasil]" id="pdf_hasil_2">
            <input type="hidden" name="bagian2[total]" id="pdf_total_2">
            <input type="hidden" name="bagian2[opsi][dinding]" id="pdf_opsi_dinding_2">
            <input type="hidden" name="bagian2[opsi][kaca]" id="pdf_opsi_kaca_2">
            <input type="hidden" name="bagian2[nok_atas_id]" id="pdf_nok_atas_id_2">
            <input type="hidden" name="bagian2[coverage]" id="pdf_coverage_2">
            
            <!-- BAGIAN 3 (KANAN) -->
            <input type="hidden" name="bagian3[data_perhitungan][luas_atap]" id="pdf_luas_3">
            <input type="hidden" name="bagian3[data_perhitungan][sudut]" id="pdf_sudut_3">
            <input type="hidden" name="bagian3[data_perhitungan][starter]" id="pdf_starter_3">
            <input type="hidden" name="bagian3[data_perhitungan][flashing]" id="pdf_flashing_3">
            <input type="hidden" name="bagian3[hasil]" id="pdf_hasil_3">
            <input type="hidden" name="bagian3[total]" id="pdf_total_3">
            <input type="hidden" name="bagian3[opsi][dinding]" id="pdf_opsi_dinding_3">
            <input type="hidden" name="bagian3[opsi][kaca]" id="pdf_opsi_kaca_3">
            <input type="hidden" name="bagian3[coverage]" id="pdf_coverage_3">
            
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF Custom
            </button>
        </form>
    </div>
</div>

<script>
let results1 = [], results2 = [], results3 = [];

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
    
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 5000);
}

// ===== FUNGSI UPDATE COVERAGE (HANYA REKOMENDASI, TIDAK LOCK) =====
function updateCoverage(bagian) {
    let sudut = parseFloat(document.getElementById(`sudut_${bagian}`)?.value) || 0;
    let sistem = document.getElementById(`sistem_pemasangan_${bagian}`)?.value || '';
    let coverageSelect = document.getElementById(`coverage_${bagian}`);
    let coverageStatus = document.getElementById(`coverage_status_${bagian}`);
    let coverageDesc = document.getElementById(`coverage_description_${bagian}`);
    let coverageInfoText = document.getElementById(`coverage_info_text_${bagian}`);
    let recommendationEl = document.getElementById(`coverage_recommendation_${bagian}`);
    let recommendationText = document.getElementById(`recommendation_text_${bagian}`);
    
    let recommendedValue = null;
    let descText = '';
    let infoText = '';
    
    // Tentukan rekomendasi berdasarkan sudut & sistem (tapi tidak memaksa)
    if (sistem === 'expose') {
        if (sudut >= 30 && sudut <= 31) {
            recommendedValue = '8';
            descText = 'Rekomendasi: 8 daun/m² (Expose 30°)';
            infoText = ' (Expose 30° → disarankan 8 daun/m²)';
        } else if (sudut > 30) {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Expose >30°)';
            infoText = ' (Expose >30° → disarankan 7 daun/m²)';
        } else {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Sudut < 30° → Non-Expose)';
            infoText = ' (Sudut < 30° → disarankan 7 daun/m²)';
        }
    } else if (sistem === 'non-expose') {
        if (sudut > 30 && sudut <= 40) {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Non-Expose 31-40°)';
            infoText = ' (Non-Expose 31-40° → disarankan 7 daun/m²)';
        } else if (sudut > 40) {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Non-Expose >40°, bisa pilih 6 atau 7)';
            infoText = ' (Non-Expose >40° → bisa pilih 6 atau 7)';
        } else if (sudut >= 15 && sudut <= 30) {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Non-Expose 15-30°)';
            infoText = ' (Non-Expose 15-30° → disarankan 7 daun/m²)';
        } else {
            recommendedValue = '7';
            descText = 'Rekomendasi: 7 daun/m² (Sudut < 15°)';
            infoText = ' (Sudut < 15° → disarankan 7 daun/m²)';
        }
    } else {
        recommendedValue = null;
        descText = 'Pilih sistem pemasangan terlebih dahulu';
        infoText = '';
    }
    
    // Update status - TIDAK MEMAKSA/MELOCK nilai
    if (coverageStatus) {
        coverageStatus.className = 'badge';
        coverageStatus.textContent = '✎ Bebas';
    }
    if (coverageDesc) {
        coverageDesc.textContent = descText || 'Pilih sesuai kebutuhan';
    }
    if (coverageInfoText) {
        coverageInfoText.textContent = infoText || '';
    }
    
    // Tampilkan rekomendasi (tapi tidak memaksa)
    if (recommendedValue && recommendationEl) {
        recommendationEl.classList.remove('hidden');
        if (recommendationText) {
            recommendationText.textContent = recommendedValue + ' daun/m²';
        }
    } else if (recommendationEl) {
        recommendationEl.classList.add('hidden');
    }
}

function toggleFields(bagian) {
    let sistem = document.getElementById(`sistem_pemasangan_${bagian}`).value;
    let sudut = parseFloat(document.getElementById(`sudut_${bagian}`)?.value) || 0;
    let underlayerContainer = document.getElementById(`underlayer_container_${bagian}`);
    let lantaiKerjaContainer = document.getElementById(`lantai_kerja_container_${bagian}`);
    let warningEl = document.getElementById(`sistem_warning_${bagian}`);
    let warningText = document.getElementById(`sistem_warning_text_${bagian}`);
    let sistemSelect = document.getElementById(`sistem_pemasangan_${bagian}`);
    let forcedBadge = document.getElementById(`forced_badge_${bagian}`);
    
    if (sudut < 30) {
        sistemSelect.value = 'non-expose';
        sistem = 'non-expose';
        
        if (warningEl) {
            warningEl.classList.remove('hidden');
            if (warningText) {
                warningText.textContent = '⚠️ Sudut atap ' + sudut + '° (< 30°), sistem otomatis NON-EXPOSE (tidak bisa diubah)';
            }
        }
        
        if (forcedBadge) {
            forcedBadge.classList.remove('hidden');
        }
        
        sistemSelect.disabled = true;
        sistemSelect.style.cursor = 'not-allowed';
        sistemSelect.style.opacity = '0.7';
        sistemSelect.style.backgroundColor = '#f7fafc';
    } else {
        if (warningEl) {
            warningEl.classList.add('hidden');
        }
        
        if (forcedBadge) {
            forcedBadge.classList.add('hidden');
        }
        
        sistemSelect.disabled = false;
        sistemSelect.style.cursor = 'default';
        sistemSelect.style.opacity = '1';
        sistemSelect.style.backgroundColor = 'white';
    }
    
    if (sistem === 'expose') {
        if (underlayerContainer) underlayerContainer.style.display = 'none';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'none';
    } else {
        if (underlayerContainer) underlayerContainer.style.display = 'block';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'block';
    }
    
    updateCoverage(bagian);
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    document.getElementById('luas_atap_1').value = urlParams.get('luas_atap_1') || 0;
    document.getElementById('sudut_1').value = urlParams.get('sudut_1') || 0;
    document.getElementById('starter_1').value = urlParams.get('starter_1') || 0;
    document.getElementById('flashing_1').value = urlParams.get('flashing_1') || 0;
    
    document.getElementById('luas_atap_2').value = urlParams.get('luas_atap_2') || 0;
    document.getElementById('sudut_2').value = urlParams.get('sudut_2') || 0;
    document.getElementById('starter_2').value = urlParams.get('starter_2') || 0;
    document.getElementById('nok_atas_2').value = urlParams.get('nok_atas_2') || 0;
    document.getElementById('flashing_2').value = urlParams.get('flashing_2') || 0;
    
    document.getElementById('luas_atap_3').value = urlParams.get('luas_atap_3') || 0;
    document.getElementById('sudut_3').value = urlParams.get('sudut_3') || 0;
    document.getElementById('starter_3').value = urlParams.get('starter_3') || 0;
    document.getElementById('flashing_3').value = urlParams.get('flashing_3') || 0;
    
    document.getElementById('opsi_dinding_1').value = urlParams.get('dinding_1') || 0;
    document.getElementById('opsi_kaca_1').value = urlParams.get('kaca_1') || 0;
    document.getElementById('opsi_dinding_2').value = urlParams.get('dinding_2') || 0;
    document.getElementById('opsi_kaca_2').value = urlParams.get('kaca_2') || 0;
    document.getElementById('opsi_dinding_3').value = urlParams.get('dinding_3') || 0;
    document.getElementById('opsi_kaca_3').value = urlParams.get('kaca_3') || 0;
    
    document.getElementById('sistem_pemasangan_1').value = 'expose';
    document.getElementById('sistem_pemasangan_2').value = 'expose';
    document.getElementById('sistem_pemasangan_3').value = 'expose';
    
    toggleFields(1);
    toggleFields(2);
    toggleFields(3);
    
    // Event listeners
    document.getElementById('sistem_pemasangan_1').addEventListener('change', function() {
        toggleFields(1);
    });
    document.getElementById('sistem_pemasangan_2').addEventListener('change', function() {
        toggleFields(2);
    });
    document.getElementById('sistem_pemasangan_3').addEventListener('change', function() {
        toggleFields(3);
    });
    
    document.getElementById('coverage_1').addEventListener('change', function() {
        let coverageStatus = document.getElementById('coverage_status_1');
        if (coverageStatus && coverageStatus.textContent !== '✎ Manual') {
            coverageStatus.className = 'badge non-expose';
            coverageStatus.textContent = '✎ Manual';
        }
    });
    document.getElementById('coverage_2').addEventListener('change', function() {
        let coverageStatus = document.getElementById('coverage_status_2');
        if (coverageStatus && coverageStatus.textContent !== '✎ Manual') {
            coverageStatus.className = 'badge non-expose';
            coverageStatus.textContent = '✎ Manual';
        }
    });
    document.getElementById('coverage_3').addEventListener('change', function() {
        let coverageStatus = document.getElementById('coverage_status_3');
        if (coverageStatus && coverageStatus.textContent !== '✎ Manual') {
            coverageStatus.className = 'badge non-expose';
            coverageStatus.textContent = '✎ Manual';
        }
    });
    
    updatePdfData();
};

function renderResultTable(bagian, results, grandTotalEl) {
    let container = document.getElementById(`tableBagian${bagian}`);
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
    const aksesorisAreas = ['Starter', 'Nok Atas', 'Underlayer', 'Lantai Kerja', 'Screw Plywood', 'Topcap', 'Screw', 'Rail', 'Wind', 'Metal Flashing', 'Flashing', 'Paku & Screw', 'Shingle Stick'];
    
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
        <span class="label">Sub Total Bagian ${bagian}</span>
        <span class="amount">Rp ${grandTotal.toLocaleString()}</span>
    </div>`;
    
    container.innerHTML = html;
    if (grandTotalEl) grandTotalEl.innerHTML = 'Rp ' + grandTotal.toLocaleString();
}

function hitungBagian(bagian) {
    let isBagian1 = (bagian === 1);
    let isBagian2 = (bagian === 2);
    let wasteValue = parseFloat(document.getElementById(`waste_${bagian}`).value) || 5;
    let coverage = parseFloat(document.getElementById(`coverage_${bagian}`).value) || 0;
    
    let opsiDinding = parseFloat(document.getElementById(`opsi_dinding_${bagian}`).value) || 0;
    let opsiKaca = parseFloat(document.getElementById(`opsi_kaca_${bagian}`).value) || 0;
    
    let sudut = parseFloat(document.getElementById(`sudut_${bagian}`).value) || 0;
    let sistemPemasangan = document.getElementById(`sistem_pemasangan_${bagian}`).value || 'expose';
    let rangka = document.getElementById(`rangka_${bagian}`)?.value || 'Baja Ringan';
    let lantaiKerja = document.getElementById(`lantai_kerja_${bagian}`)?.value || 'Plywood 9 mm';
    
    // ===== VALIDASI SUDUT =====
    if (sudut < 30 && sistemPemasangan === 'expose') {
        sistemPemasangan = 'non-expose';
        document.getElementById(`sistem_pemasangan_${bagian}`).value = 'non-expose';
        toggleFields(bagian);
        showNotification('⚠️ Sudut ' + sudut + '° < 30°, sistem dipaksa NON-EXPOSE', 'warning');
    }
    
    // ===== VALIDASI COVERAGE =====
    if (!coverage || coverage <= 0) {
        alert('⚠️ Pilih Coverage (daun/m²) terlebih dahulu!');
        document.getElementById(`coverage_${bagian}`).focus();
        return;
    }
    
    // ===== AMBIL NOK ATAS DARI DROPDOWN (HANYA BAGIAN 2) =====
    let nokAtasId = '';
    if (isBagian2) {
        let nokAtasDropdown = document.getElementById('nok_atas_dropdown_2');
        nokAtasId = nokAtasDropdown ? nokAtasDropdown.value : '';
    }
    
    // ===== VALIDASI WAJIB UNTUK BAGIAN 2 =====
    if (isBagian2 && !nokAtasId) {
        alert('⚠️ Pilih Nok Atas terlebih dahulu! (wajib)');
        document.getElementById('nok_atas_dropdown_2').focus();
        return;
    }
    
    let data = {
        luas_atap: parseFloat(document.getElementById(`luas_atap_${bagian}`).value) || 0,
        sudut: sudut,
        panjang_starter: parseFloat(document.getElementById(`starter_${bagian}`).value) || 0,
        panjang_flashing: parseFloat(document.getElementById(`flashing_${bagian}`).value) || 0,
        panjang_talang_jurai: 0,
        panjang_wall_flashing: opsiDinding,
        produk_atap_id: document.getElementById(`produk_atap_${bagian}`).value,
        nok_atas_id: nokAtasId,
        underlayer_id: document.getElementById(`underlayer_${bagian}`).value,
        rangka: rangka,
        lantai_kerja: lantaiKerja,
        waste: wasteValue,
        coverage: coverage,
        opsi_dinding: opsiDinding,
        opsi_kaca: opsiKaca,
        sistem_pemasangan: sistemPemasangan
    };
    
    if (isBagian1) {
        data.panjang_jurai = 0;
        data.panjang_nok_atas = 0;
    } else if (isBagian2) {
        data.panjang_jurai = 0;
        data.panjang_nok_atas = parseFloat(document.getElementById(`nok_atas_${bagian}`).value) || 0;
    } else {
        data.panjang_jurai = 0;
        data.panjang_nok_atas = 0;
    }
    
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
    
    let btn = event?.target || document.querySelector(`#hasilBagian${bagian} .btn-primary, #hasilBagian${bagian} .btn-secondary`);
    let originalText = btn ? btn.innerHTML : 'Menghitung...';
    if (btn) {
        btn.innerHTML = 'Menghitung...';
        btn.disabled = true;
    }
    
    fetch('/boq/palmex/atap-kombinasi/pelana-2-kemiringan/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (isBagian1) {
                results1 = data.results;
                renderResultTable('1', results1, document.getElementById('grandTotal1'));
            } else if (isBagian2) {
                results2 = data.results;
                renderResultTable('2', results2, document.getElementById('grandTotal2'));
            } else {
                results3 = data.results;
                renderResultTable('3', results3, document.getElementById('grandTotal3'));
            }
            document.getElementById(`hasilBagian${bagian}`).classList.remove('hidden');
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
    let total1 = results1.reduce((s, i) => s + (i.total_harga || 0), 0);
    let total2 = results2.reduce((s, i) => s + (i.total_harga || 0), 0);
    let total3 = results3.reduce((s, i) => s + (i.total_harga || 0), 0);
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${(total1 + total2 + total3).toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_waste_1').value = document.getElementById('waste_1').value;
    document.getElementById('pdf_waste_2').value = document.getElementById('waste_2').value;
    document.getElementById('pdf_waste_3').value = document.getElementById('waste_3').value;
    
    document.getElementById('pdf_luas_1').value = document.getElementById('luas_atap_1').value;
    document.getElementById('pdf_sudut_1').value = document.getElementById('sudut_1').value;
    document.getElementById('pdf_starter_1').value = document.getElementById('starter_1').value;
    document.getElementById('pdf_flashing_1').value = document.getElementById('flashing_1').value;
    document.getElementById('pdf_hasil_1').value = JSON.stringify(results1);
    document.getElementById('pdf_total_1').value = document.getElementById('grandTotal1').innerText;
    document.getElementById('pdf_opsi_dinding_1').value = document.getElementById('opsi_dinding_1').value || 0;
    document.getElementById('pdf_opsi_kaca_1').value = document.getElementById('opsi_kaca_1').value || 0;
    document.getElementById('pdf_coverage_1').value = document.getElementById('coverage_1').value || '';
    
    document.getElementById('pdf_luas_2').value = document.getElementById('luas_atap_2').value;
    document.getElementById('pdf_sudut_2').value = document.getElementById('sudut_2').value;
    document.getElementById('pdf_starter_2').value = document.getElementById('starter_2').value;
    document.getElementById('pdf_nok_atas_2').value = document.getElementById('nok_atas_2').value;
    document.getElementById('pdf_flashing_2').value = document.getElementById('flashing_2').value;
    document.getElementById('pdf_hasil_2').value = JSON.stringify(results2);
    document.getElementById('pdf_total_2').value = document.getElementById('grandTotal2').innerText;
    document.getElementById('pdf_opsi_dinding_2').value = document.getElementById('opsi_dinding_2').value || 0;
    document.getElementById('pdf_opsi_kaca_2').value = document.getElementById('opsi_kaca_2').value || 0;
    document.getElementById('pdf_nok_atas_id_2').value = document.getElementById('nok_atas_dropdown_2').value || '';
    document.getElementById('pdf_coverage_2').value = document.getElementById('coverage_2').value || '';
    
    document.getElementById('pdf_luas_3').value = document.getElementById('luas_atap_3').value;
    document.getElementById('pdf_sudut_3').value = document.getElementById('sudut_3').value;
    document.getElementById('pdf_starter_3').value = document.getElementById('starter_3').value;
    document.getElementById('pdf_flashing_3').value = document.getElementById('flashing_3').value;
    document.getElementById('pdf_hasil_3').value = JSON.stringify(results3);
    document.getElementById('pdf_total_3').value = document.getElementById('grandTotal3').innerText;
    document.getElementById('pdf_opsi_dinding_3').value = document.getElementById('opsi_dinding_3').value || 0;
    document.getElementById('pdf_opsi_kaca_3').value = document.getElementById('opsi_kaca_3').value || 0;
    document.getElementById('pdf_coverage_3').value = document.getElementById('coverage_3').value || '';
    
    document.getElementById('pdf_grand_total').value = document.getElementById('totalKeseluruhan').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection