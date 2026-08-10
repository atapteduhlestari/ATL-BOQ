@extends('layouts.app')

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
    .delete-row {
        cursor: pointer;
        transition: all 0.2s;
    }
    .delete-row:hover {
        transform: scale(1.1);
    }

    .required-star {
        color: #e53e3e;
        margin-left: 2px;
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
    
    .notes-container .notes-list li .warning-text {
        color: #991b1b;
        font-weight: 600;
    }

    /* Opsi Tambahan */
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

    /* STYLING HASIL PERHITUNGAN - TABLE MINIMALIS */
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
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon palmex">P</div>
                <div>
                    <div class="brand-name">BOQ - PALMEX Kerucut</div>
                    <div class="brand-sub">Hitung kebutuhan material atap PALMEX model Kerucut</div>
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
                <span><strong>Nok Bulat</strong> WAJIB dipilih dari dropdown.</span>
            </li>
        </ul>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form id="boqForm">
                <!-- Data Perhitungan Luas (READONLY dari URL) -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Data Perhitungan Luas Atap</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 rounded-xl p-4">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Luas Permukaan Atap</label>
                            <input type="number" id="luas_atap" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-green-500 focus:border-green-500" readonly placeholder="0 m²">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Sudut Kemiringan</label>
                            <input type="number" id="sudut" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-green-500 focus:border-green-500" readonly placeholder="0°">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Tepi Bawah Atap</label>
                            <input type="number" id="panjang_starter" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-green-500 focus:border-green-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Flashing</label>
                            <input type="number" id="panjang_flashing" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:ring-1 focus:ring-green-500 focus:border-green-500" readonly placeholder="0 m">
                        </div>
                    </div>

                    <!-- ===== OPSI TAMBAHAN ===== -->
                    <div class="opsi-tambahan-grid">
                        <div class="opsi-item">
                            <label>
                                📐 Dinding
                                <span class="opsi-desc">(Panjang atap yang berbatasan dinding)</span>
                            </label>
                            <input type="number" 
                                   id="opsi_dinding" 
                                   step="0.1" 
                                   min="0"
                                   placeholder="0"
                                   value="0">
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
                                   value="0">
                            <span class="opsi-satuan">meter</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pilihan Produk -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Pilihan Material</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Produk Atap Utama -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">PRODUK ATAP UTAMA</label>
                            <select id="produk_atap_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ===== DROPDOWN NOK BULAT (WAJIB) ===== -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">
                                NOK BULAT <span class="required-star">*</span>
                            </label>
                            <select id="nok_bulat_dropdown" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white" required>
                                <option value="">Pilih Nok Bulat</option>
                                @foreach($nokBulatOptions ?? [] as $nok)
                                    <option value="{{ $nok->id }}">{{ $nok->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SISTEM PEMASANGAN -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">SISTEM PEMASANGAN</label>
                            <select id="sistem_pemasangan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white" onchange="toggleFields()">
                                <option value="">Pilih Sistem Pemasangan</option>
                                <option value="expose">Expose (Terlihat)</option>
                                <option value="non-expose">Non-Expose (Tidak Terlihat)</option>
                            </select>
                        </div>

                        <!-- UNDERLAYER -->
                        <div id="underlayer_container">
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">UNDERLAYER</label>
                            <select id="underlayer_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white">
                                <option value="">Pilih Underlayer</option>
                                @foreach($underlayers as $underlayer)
                                    <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ===== STRUKTUR RANGKA ===== -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">STRUKTUR RANGKA</label>
                            <select id="rangka" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white">
                                @foreach($rangkaOptions as $option)
                                    <option value="{{ $option }}" {{ $option == 'Baja Ringan' ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- LANTAI KERJA -->
                        <div id="lantai_kerja_container">
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">LANTAI KERJA</label>
                            <select id="lantai_kerja" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white">
                                @foreach($lantaiKerjaOptions as $option)
                                    <option value="{{ $option }}" {{ $option == 'Plywood 9 mm' ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- WASTE -->
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">WASTE (%)</label>
                            <input type="number" id="waste" step="1" value="5" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Hitung -->
                <div class="flex gap-3">
                    <button type="button" onclick="hitungBOQ()" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
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
            
            <!-- Hasil Perhitungan -->
            <div id="hasilBOQ" class="mt-8 hidden">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-gray-900 rounded-full"></div>
                    <h3 class="text-base font-semibold text-gray-800">Rincian Kebutuhan Material</h3>
                </div>
                <div class="rounded-xl border border-gray-200 overflow-hidden bg-white">
                    <div id="boqResultContainer" class="p-4">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentResults = [];

function updateGrandTotal(total) {
    let grandTotalEl = document.getElementById('grandTotal');
    if (grandTotalEl) {
        grandTotalEl.innerHTML = `Rp ${(total || 0).toLocaleString()}`;
    }
}

function renderTable() {
    let container = document.getElementById('boqResultContainer');
    
    if (!container) {
        console.error('Container boqResultContainer tidak ditemukan!');
        return;
    }
    
    if (!currentResults || currentResults.length === 0) {
        container.innerHTML = `<div class="empty-state">Belum ada data material</div>`;
        return;
    }
    
    // Kelompokkan berdasarkan area
    const groups = {
        'Atap Utama': { label: 'KELOMPOK ATAP UTAMA', items: [] },
        'Aksesoris': { label: 'AKSESORIS', items: [] },
        'Additional': { label: 'ADDITIONAL', items: [] },
        'Sistem Pendukung': { label: 'SISTEM PENDUKUNG', items: [] }
    };
    
    const additionalAreas = ['Wall Flashing', 'Flashing Kaca'];
    const systemAreas = ['Underlayer', 'Lantai Kerja', 'Paku & Screw'];
    const aksesorisAreas = ['Starter', 'Nok Bulat', 'Screw', 'Rail', 'Wind', 'Metal Flashing'];
    
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
            let totalDisplay = '';
            if (item.total_harga > 0) {
                totalDisplay = `<span class="total">Rp ${item.total_harga.toLocaleString()}</span>`;
            } else {
                totalDisplay = `<span class="total-empty">-</span>`;
            }
            
            html += `<tr>
                <td class="product-name">${item.nama_produk || '-'}</td>
                <td><span class="area-tag">${item.area || '-'}</span></td>
                <td class="qty text-center">${(item.qty || 0).toLocaleString()}</td>
                <td class="unit">${item.satuan || '-'}</td>
                <td class="text-right">${totalDisplay}</td>
            </tr>`;
        });
        
        html += `</tbody></table>`;
        html += `</div>`;
    });
    
    // Grand Total
    html += `<div class="grand-total-minimal">
        <span class="label">Grand Total</span>
        <span class="amount">Rp ${grandTotal.toLocaleString()}</span>
    </div>`;
    
    container.innerHTML = html;
}

function deleteRow(index) {
    if (confirm('Hapus item ini?')) {
        currentResults.splice(index, 1);
        renderTable();
        if (currentResults.length === 0) {
            document.getElementById('hasilBOQ').classList.add('hidden');
            document.getElementById('btnPDF').classList.add('hidden');
        }
    }
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    document.getElementById('luas_atap').value = urlParams.get('luas_atap') || 0;
    document.getElementById('sudut').value = urlParams.get('sudut') || 0;
    document.getElementById('panjang_starter').value = urlParams.get('panjang_starter') || 0;
    document.getElementById('panjang_flashing').value = urlParams.get('panjang_flashing') || 0;
    document.getElementById('opsi_dinding').value = urlParams.get('dinding') || 0;
    document.getElementById('opsi_kaca').value = urlParams.get('kaca') || 0;

    // Set sistem pemasangan dari URL atau default
    let sistemPemasangan = urlParams.get('sistem_pemasangan') || 'expose';
    document.getElementById('sistem_pemasangan').value = sistemPemasangan;
    
    toggleFields();
};

function sortResults(results) {
    const urutan = [
        'Atap Utama',
        'Starter',
        'Nok Bulat',
        'Screw',
        'Rail',
        'Wind',
        'Metal Flashing',
        'Underlayer',
        'Lantai Kerja',
        'Paku & Screw',
        'Wall Flashing',
        'Flashing Kaca'
    ];
    
    return results.sort((a, b) => {
        let indexA = urutan.indexOf(a.area);
        let indexB = urutan.indexOf(b.area);
        if (indexA === -1) indexA = urutan.length;
        if (indexB === -1) indexB = urutan.length;
        return indexA - indexB;
    });
}

function hitungBOQ() {
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="inline w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Memproses...';
    btn.disabled = true;
    
    let opsiDinding = parseFloat(document.getElementById('opsi_dinding')?.value) || 0;
    let opsiKaca = parseFloat(document.getElementById('opsi_kaca')?.value) || 0;
    
    // ===== AMBIL DARI DROPDOWN =====
    let nokBulatDropdown = document.getElementById('nok_bulat_dropdown');
    let nokBulatId = nokBulatDropdown ? nokBulatDropdown.value : '';
    
    let rangka = document.getElementById('rangka')?.value || 'Baja Ringan';
    let lantaiKerja = document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm';
    
    // ===== VALIDASI WAJIB =====
    if (!nokBulatId) {
        alert('⚠️ Pilih Nok Bulat terlebih dahulu! (wajib)');
        document.getElementById('nok_bulat_dropdown').focus();
        btn.innerHTML = originalText;
        btn.disabled = false;
        return;
    }
    
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap')?.value) || 0,
        sudut: parseFloat(document.getElementById('sudut')?.value) || 0,
        panjang_starter: parseFloat(document.getElementById('panjang_starter')?.value) || 0,
        panjang_jurai: 0,
        panjang_nok: 0,
        panjang_flashing: parseFloat(document.getElementById('panjang_flashing')?.value) || 0,
        opsi_dinding: opsiDinding,
        opsi_kaca: opsiKaca,
        produk_atap_id: document.getElementById('produk_atap_id')?.value || '',
        nok_bulat_id: nokBulatId,
        underlayer_id: document.getElementById('underlayer_id')?.value || '',
        rangka: rangka,
        lantai_kerja: lantaiKerja,
        sistem_pemasangan: document.getElementById('sistem_pemasangan')?.value || 'expose',
        waste: parseFloat(document.getElementById('waste')?.value) || 5
    };
    
    if (!data.produk_atap_id) {
        alert('Pilih Produk Atap Utama terlebih dahulu!');
        btn.innerHTML = originalText;
        btn.disabled = false;
        return;
    }
    
    if (data.luas_atap <= 0) {
        alert('Data luas atap tidak valid! Silakan hitung ulang dari halaman sebelumnya.');
        btn.innerHTML = originalText;
        btn.disabled = false;
        return;
    }
    
    fetch('/boq/palmex/kerucut/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentResults = sortResults(data.results);
            renderTable();
            document.getElementById('hasilBOQ').classList.remove('hidden');
            document.getElementById('hasilBOQ').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            document.getElementById('btnPDF').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => { 
        console.error('Error:', error);
        alert('Error: ' + error.message); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function toggleFields() {
    let sistem = document.getElementById('sistem_pemasangan')?.value || '';
    let underlayerContainer = document.getElementById('underlayer_container');
    let lantaiKerjaContainer = document.getElementById('lantai_kerja_container');
    
    if (sistem === 'expose') {
        if (underlayerContainer) underlayerContainer.style.display = 'none';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'none';
    } else if (sistem === 'non-expose') {
        if (underlayerContainer) underlayerContainer.style.display = 'block';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'block';
    } else {
        if (underlayerContainer) underlayerContainer.style.display = 'block';
        if (lantaiKerjaContainer) lantaiKerjaContainer.style.display = 'block';
    }
}

function exportToPDF() {
    // Cari qty plywood & screw dari currentResults
    let qtyPlywood = 0;
    let qtyScrew = 0;
    let screwName = '';
    
    currentResults.forEach(item => {
        if (item.area === 'Lantai Kerja') {
            qtyPlywood = item.qty || 0;
        }
        if (item.area === 'Paku & Screw') {
            qtyScrew = item.qty || 0;
            screwName = item.nama_produk || '';
        }
    });
    
    let data = {
        luas_atap: document.getElementById('luas_atap')?.value || 0,
        sudut: document.getElementById('sudut')?.value || 0,
        panjang_starter: document.getElementById('panjang_starter')?.value || 0,
        panjang_flashing: document.getElementById('panjang_flashing')?.value || 0,
        waste: document.getElementById('waste')?.value || 5,
        produk_atap: document.getElementById('produk_atap_id')?.selectedOptions[0]?.text || '',
        nok_bulat: document.getElementById('nok_bulat_dropdown')?.selectedOptions[0]?.text || '',
        underlayer: document.getElementById('underlayer_id')?.selectedOptions[0]?.text || '',
        rangka: document.getElementById('rangka')?.value || 'Baja Ringan',
        lantai_kerja: document.getElementById('lantai_kerja')?.value || 'Plywood 9 mm',
        sistem_pemasangan: document.getElementById('sistem_pemasangan')?.value || 'expose',
        qty_plywood: qtyPlywood,
        qty_screw: qtyScrew,
        screw_name: screwName,
        grand_total: document.getElementById('grandTotal')?.innerText || 'Rp 0',
        opsi_dinding: document.getElementById('opsi_dinding')?.value || 0,
        opsi_kaca: document.getElementById('opsi_kaca')?.value || 0,
        results: currentResults.map(item => ({
            id: item.id,
            product_id: item.product_id,
            nama: item.nama_produk,
            area: item.area,
            qty: item.qty,
            satuan: item.satuan,
            harga: item.harga_satuan,
            total: item.total_harga
        }))
    };
    
    fetch('/boq/palmex/kerucut/export-pdf', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(html => {
        let win = window.open();
        win.document.write(html);
        win.document.close();
    });
}
</script>
@endsection