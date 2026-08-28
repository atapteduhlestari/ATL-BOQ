@extends('layouts.app')

@section('title', 'BOQ ECOROOF - Piramid')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body, button, input, select, .text-sm, .text-xs, .text-lg, .text-xl, .text-3xl, .font-semibold, .font-medium, .font-bold {
        font-family: 'Poppins', sans-serif !important;
    }
    input, select, button {
        font-size: 13px !important;
    }
    label {
        font-size: 11px !important;
        letter-spacing: 0.3px;
    }
    h2, h3 {
        letter-spacing: -0.3px;
    }

    .required-star {
        color: #e53e3e;
        margin-left: 2px;
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

    .opsi-tambahan-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
    }
    
    @media (max-width: 768px) {
        .opsi-tambahan-grid {
            grid-template-columns: repeat(2, 1fr);
        }
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
        border-color: #14b8a6;
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
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

    .header-brand {
        background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #2dd4bf 100%);
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
    
    .header-brand .brand-icon.ecoroof {
        background: linear-gradient(135deg, #0f766e, #14b8a6);
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
        color: #0d9488;
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

    .grand-total-minimal {
        background: #0d9488;
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

    .material-selection-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    @media (max-width: 768px) {
        .material-selection-grid {
            grid-template-columns: 1fr;
        }
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
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon ecoroof">E</div>
                <div>
                    <div class="brand-name">BOQ - ECOROOF Atap</div>
                    <div class="brand-sub">Hitung kebutuhan material ECOROOF Piramid</div>
                </div>
            </div>
            <div class="brand-badge">ECOROOF</div>
        </div>
    </div>

    <!-- NOTES -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Petunjuk Pengisian BOQ ECOROOF - PIRAMID
        </div>
        <ul class="notes-list">
            <li>
                <span class="bullet">•</span>
                <span>Pastikan data luas atap, sudut kemiringan, dan panjang-panjang lainnya sudah benar.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Pilih jenis <strong>Tape Roof Nok & Jurai</strong> (U / V / Bulat), maka <strong>Nok Tutup</strong> akan otomatis menyesuaikan.</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span><strong>ECOROOF</strong> menggunakan sistem perhitungan yang telah disesuaikan dengan spesifikasi produk.</span>
            </li>
        </ul>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form id="boqForm">
                <!-- Data Perhitungan Luas -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-teal-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Data Perhitungan Luas Atap</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 rounded-xl p-4">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Luas Permukaan Atap</label>
                            <input type="number" id="luas_atap" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500" readonly placeholder="0 m²">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Sudut Kemiringan</label>
                            <input type="number" id="sudut" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500" readonly placeholder="0°">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Tepi Bawah Atap</label>
                            <input type="number" id="panjang_starter" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Jurai</label>
                            <input type="number" id="panjang_jurai" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Flashing</label>
                            <input type="number" id="panjang_flashing" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500" readonly placeholder="0 m">
                        </div>
                    </div>

                    <!-- OPSI TAMBAHAN -->
                    <div class="opsi-tambahan-grid">
                        <div class="opsi-item">
                            <label>
                                📐 Dinding
                                <span class="opsi-desc">(Panjang atap yang berbatasan dinding)</span>
                            </label>
                            <input type="number" id="opsi_dinding" step="0.1" min="0" placeholder="0" value="0">
                            <span class="opsi-satuan">meter</span>
                        </div>
                        <div class="opsi-item">
                            <label>
                                🏭 Cerobong Asap
                                <span class="opsi-desc">(jumlah cerobong asap)</span>
                            </label>
                            <input type="number" id="opsi_cerobong" step="1" min="0" placeholder="0" value="0">
                            <span class="opsi-satuan">unit</span>
                        </div>
                        <div class="opsi-item">
                            <label>
                                ⚡ Penangkal Petir
                                <span class="opsi-desc">(panjang instalasi penangkal petir)</span>
                            </label>
                            <input type="number" id="opsi_penangkal" step="0.1" min="0" placeholder="0" value="0">
                            <span class="opsi-satuan">meter</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pilihan Material -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-teal-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Pilihan Material</h3>
                    </div>
                    <div class="material-selection-grid">
                        <!-- DROPDOWN: TAPE ROOF NOK & JURAI (WAJIB) - TETAP TAPE ROOF NOK & JURAI -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                                TAPE ROOF NOK & JURAI <span class="required-star">*</span>
                            </label>
                            <select id="nok_dropdown" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white" required onchange="updateNokOptions()">
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

                        <!-- STRUKTUR RANGKA -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">STRUKTUR RANGKA</label>
                            <select id="rangka" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                @foreach($rangkaOptions as $option)
                                    <option value="{{ $option }}" {{ $option == 'Baja Ringan' ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- LANTAI KERJA -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">LANTAI KERJA</label>
                            <select id="lantai_kerja" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white">
                                @foreach($lantaiKerjaOptions as $option)
                                    <option value="{{ $option }}" {{ $option == 'Plywood 9 mm' ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- WASTE -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">WASTE (%)</label>
                            <input type="number" id="waste" step="1" value="5" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div class="flex gap-3">
                    <button type="button" onclick="hitungBOQ()" class="flex-1 bg-teal-700 hover:bg-teal-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                        Hitung Material
                    </button>
                    <button type="button" id="btnPDF" onclick="exportToPDF()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm font-medium hidden transition-all">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export PDF
                    </button>
                </div>
            </form>
            
            <!-- Hasil -->
            <div id="hasilBOQ" class="mt-8 hidden">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-teal-600 rounded-full"></div>
                    <h3 class="text-base font-semibold text-gray-800">Rincian Kebutuhan Material</h3>
                </div>
                <div class="rounded-xl border border-gray-200 overflow-hidden bg-white">
                    <div id="boqResultContainer" class="p-4"></div>
                </div>
            </div>
        </div>
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

    // ============================================================
    // FUNGSI HITUNG BOQ
    // ============================================================
    let currentResults = [];

    function renderTable() {
        let container = document.getElementById('boqResultContainer');
        if (!container) return;
        
        if (!currentResults || currentResults.length === 0) {
            container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
            return;
        }
        
        const groups = {
            'Atap Utama': { label: 'ATAP UTAMA', items: [] },
            'Aksesoris': { label: 'AKSESORIS', items: [] },
            'Additional': { label: 'TAMBAHAN', items: [] },
            'Sistem Pendukung': { label: 'SISTEM PENDUKUNG', items: [] }
        };
        
        const additionalAreas = ['Wall Flashing', 'Cerobong Asap', 'Penangkal Petir', 'Flashing Kaca'];
        const systemAreas = ['Lantai Kerja', 'Underlayer', 'Screw Plywood'];
        const aksesorisAreas = ['Starter', 'Tape Roof Nok', 'Tape Roof Nok & Jurai', 'Jurai', 'Nok Tutup', 'Metal Flashing', 'Paku & Screw'];
        
        currentResults.forEach(item => {
            if (item.area === 'Atap Utama') {
                groups['Atap Utama'].items.push(item);
            } else if (additionalAreas.includes(item.area)) {
                groups['Additional'].items.push(item);
            } else if (systemAreas.includes(item.area)) {
                groups['Sistem Pendukung'].items.push(item);
            } else if (aksesorisAreas.includes(item.area)) {
                groups['Aksesoris'].items.push(item);
            } else {
                groups['Aksesoris'].items.push(item);
            }
        });
        
        let html = '';
        let grandTotal = 0;
        const groupKeys = ['Atap Utama', 'Aksesoris', 'Additional', 'Sistem Pendukung'];
        
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
                let totalDisplay = item.total_harga > 0 ? 
                    `<span class="total">Rp ${item.total_harga.toLocaleString()}</span>` : 
                    `<span class="total-empty">-</span>`;
                
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
    }

    function hitungBOQ() {
        let btn = event.target;
        let originalText = btn.innerHTML;
        btn.innerHTML = 'Memproses...';
        btn.disabled = true;
        
        let nokDropdown = document.getElementById('nok_dropdown');
        let nokId = nokDropdown ? nokDropdown.value : '';
        let nokTutupId = document.getElementById('nok_tutup_id')?.value || '';
        
        if (!nokId) {
            alert('⚠️ Pilih Tape Roof Nok & Jurai terlebih dahulu!');
            document.getElementById('nok_dropdown').focus();
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
        
        let data = {
            luas_atap: parseFloat(document.getElementById('luas_atap')?.value) || 0,
            sudut: parseFloat(document.getElementById('sudut')?.value) || 0,
            panjang_starter: parseFloat(document.getElementById('panjang_starter')?.value) || 0,
            panjang_jurai: parseFloat(document.getElementById('panjang_jurai')?.value) || 0,
            panjang_flashing: parseFloat(document.getElementById('panjang_flashing')?.value) || 0,
            opsi_dinding: parseFloat(document.getElementById('opsi_dinding')?.value) || 0,
            opsi_cerobong: parseFloat(document.getElementById('opsi_cerobong')?.value) || 0,
            opsi_penangkal: parseFloat(document.getElementById('opsi_penangkal')?.value) || 0,
            nok_id: nokId,
            nok_tutup_id: nokTutupId,
            rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
            lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
            waste: parseFloat(document.getElementById('waste')?.value) || 5
        };
        
        console.log('DATA DIKIRIM:', data);
        
        if (data.luas_atap <= 0) {
            alert('Data luas atap tidak valid! Silakan hitung ulang dari halaman sebelumnya.');
            btn.innerHTML = originalText;
            btn.disabled = false;
            return;
        }
        
        fetch('{{ route("boq.ecoroof.hitung", ["model" => "piramid"]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentResults = data.results;
                renderTable();
                document.getElementById('hasilBOQ').classList.remove('hidden');
                document.getElementById('btnPDF').classList.remove('hidden');
                document.getElementById('hasilBOQ').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function exportToPDF() {
        let data = {
            luas_atap: document.getElementById('luas_atap')?.value || 0,
            sudut: document.getElementById('sudut')?.value || 0,
            panjang_starter: document.getElementById('panjang_starter')?.value || 0,
            panjang_jurai: document.getElementById('panjang_jurai')?.value || 0,
            panjang_flashing: document.getElementById('panjang_flashing')?.value || 0,
            waste: document.getElementById('waste')?.value || 5,
            nok_atas: document.getElementById('nok_dropdown')?.selectedOptions[0]?.text || '',
            nok_tutup: document.getElementById('nok_tutup_display')?.value || '',
            rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
            lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
            grand_total: document.querySelector('.grand-total-minimal .amount')?.innerText || 'Rp 0',
            opsi_dinding: document.getElementById('opsi_dinding')?.value || 0,
            opsi_cerobong: document.getElementById('opsi_cerobong')?.value || 0,
            opsi_penangkal: document.getElementById('opsi_penangkal')?.value || 0,
            results: currentResults
        };
        
        fetch('{{ route("boq.ecoroof.export-pdf", ["model" => "piramid"]) }}', {
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

    // ============================================================
    // LOAD DATA DARI URL PARAMS
    // ============================================================
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        document.getElementById('luas_atap').value = urlParams.get('luas_atap') || 0;
        document.getElementById('sudut').value = urlParams.get('sudut') || 0;
        document.getElementById('panjang_starter').value = urlParams.get('panjang_starter') || 0;
        document.getElementById('panjang_jurai').value = urlParams.get('panjang_jurai') || 0;
        document.getElementById('panjang_flashing').value = urlParams.get('panjang_flashing') || 0;
        document.getElementById('opsi_dinding').value = urlParams.get('dinding') || 0;
        document.getElementById('opsi_cerobong').value = urlParams.get('cerobong') || 0;
        document.getElementById('opsi_penangkal').value = urlParams.get('penangkal') || 0;
        
        // Update Nok options setelah data terisi
        setTimeout(updateNokOptions, 300);
    };
</script>
@endsection