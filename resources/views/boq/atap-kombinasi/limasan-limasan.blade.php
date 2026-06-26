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
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-main">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-1">BOQ - Limasan + Limasan</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material atap kombinasi Limasan + Limasan</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">IKO - ATAP</span>
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        
        <!-- BAGIAN 1: LIMASAN A -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🏠 Bagian Atas - Atap Limasan A</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk bagian atas (Limasan A)</p>
                    </div>
                    <span class="badge-section">BAGIAN 1</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
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
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="nok_1" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
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
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap_1" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
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
                </div>
                
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste (%)</label>
                    <div class="waste-input">
                        <input type="number" id="waste_1" step="1" value="5" class="input-field" style="width: 100px;">
                        <span>%</span>
                    </div>
                </div>
                
                <button onclick="hitungBagian(1)" class="btn-primary">
                    Hitung Material Limasan A
                </button>
                
                <div id="hasilBagian1" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Limasan A</h4>
                        <div class="table-container" id="tableBagian1"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal1">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- BAGIAN 2: LIMASAN B -->
        <div class="section-card">
            <div class="section-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="section-title">🏠 Bagian Bawah - Atap Limasan B</h3>
                        <p class="section-subtitle">Masukkan data perhitungan untuk bagian bawah (Limasan B)</p>
                    </div>
                    <span class="badge-section">BAGIAN 2</span>
                </div>
            </div>
            <div class="section-body">
                <!-- Data Perhitungan -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                    <div class="data-box">
                        <label>Luas Atap</label>
                        <div class="value">
                            <input type="number" id="luas_atap_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                        </div>
                    </div>
                    <div class="data-box">
                        <label>Sudut</label>
                        <div class="value">
                            <input type="number" id="sudut_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                            <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
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
                        <label>Nok & Jurai</label>
                        <div class="value">
                            <input type="number" id="nok_2" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
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
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                        <select id="produk_atap_2" class="input-field">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                        <select id="underlayer_2" class="input-field">
                            <option value="">Pilih Underlayer</option>
                            @foreach($underlayers_2 as $underlayer)
                                <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if($sudut_2 <= 30 && $sudut_2 > 0)
                            <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut_2 }}° (≤ 30°), disarankan menggunakan underlayer khusus ini.</p>
                        @endif
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste (%)</label>
                    <div class="waste-input">
                        <input type="number" id="waste_2" step="1" value="5" class="input-field" style="width: 100px;">
                        <span>%</span>
                    </div>
                </div>
                
                <button onclick="hitungBagian(2)" class="btn-secondary">
                    Hitung Material Limasan B
                </button>
                
                <div id="hasilBagian2" class="mt-4 hidden">
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material Limasan B</h4>
                        <div class="table-container" id="tableBagian2"></div>
                        <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal2">Rp 0</div>
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
                <p class="text-gray-400 text-[10px]">Limasan A + Limasan B (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/atap-kombinasi/limasan-limasan/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Limasan + Limasan">
            <input type="hidden" name="brand" value="IKO - ATAP">
            
            <input type="hidden" name="waste_1" id="pdf_waste_1">
            <input type="hidden" name="waste_2" id="pdf_waste_2">
            
            <input type="hidden" name="bagian1[data_perhitungan][luas_atap]" id="pdf_luas_1">
            <input type="hidden" name="bagian1[data_perhitungan][sudut]" id="pdf_sudut_1">
            <input type="hidden" name="bagian1[data_perhitungan][starter]" id="pdf_starter_1">
            <input type="hidden" name="bagian1[data_perhitungan][nok_jurai]" id="pdf_nok_1">
            <input type="hidden" name="bagian1[data_perhitungan][flashing]" id="pdf_flashing_1">
            <input type="hidden" name="bagian1[hasil]" id="pdf_hasil_1">
            <input type="hidden" name="bagian1[total]" id="pdf_total_1">
            
            <input type="hidden" name="bagian2[data_perhitungan][luas_atap]" id="pdf_luas_2">
            <input type="hidden" name="bagian2[data_perhitungan][sudut]" id="pdf_sudut_2">
            <input type="hidden" name="bagian2[data_perhitungan][starter]" id="pdf_starter_2">
            <input type="hidden" name="bagian2[data_perhitungan][nok_jurai]" id="pdf_nok_2">
            <input type="hidden" name="bagian2[data_perhitungan][flashing]" id="pdf_flashing_2">
            <input type="hidden" name="bagian2[hasil]" id="pdf_hasil_2">
            <input type="hidden" name="bagian2[total]" id="pdf_total_2">
            
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
let results1 = [], results2 = [];

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    document.getElementById('luas_atap_1').value = urlParams.get('luas_atap_1') || 0;
    document.getElementById('sudut_1').value = urlParams.get('sudut_1') || 0;
    document.getElementById('starter_1').value = urlParams.get('starter_1') || 0;
    document.getElementById('nok_1').value = urlParams.get('nok_1') || 0;
    document.getElementById('flashing_1').value = urlParams.get('flashing_1') || 0;
    
    document.getElementById('luas_atap_2').value = urlParams.get('luas_atap_2') || 0;
    document.getElementById('sudut_2').value = urlParams.get('sudut_2') || 0;
    document.getElementById('starter_2').value = urlParams.get('starter_2') || 0;
    document.getElementById('nok_2').value = urlParams.get('nok_2') || 0;
    document.getElementById('flashing_2').value = urlParams.get('flashing_2') || 0;
    
    updatePdfData();
};

function hitungBagian(bagian) {
    let wasteValue = parseFloat(document.getElementById(`waste_${bagian}`).value) || 5;
    let data = {
        luas_atap: parseFloat(document.getElementById(`luas_atap_${bagian}`).value),
        sudut: parseFloat(document.getElementById(`sudut_${bagian}`).value),
        panjang_starter: parseFloat(document.getElementById(`starter_${bagian}`).value),
        panjang_nok_jurai: parseFloat(document.getElementById(`nok_${bagian}`).value),
        panjang_flashing: parseFloat(document.getElementById(`flashing_${bagian}`).value),
        panjang_talang_jurai: 0,
        panjang_wall_flashing: 0,
        produk_atap_id: document.getElementById(`produk_atap_${bagian}`).value,
        underlayer_id: document.getElementById(`underlayer_${bagian}`).value,
        rangka: 'Baja Ringan',
        lantai_kerja: 'Plywood 9 mm',
        waste: wasteValue
    };
    
    fetch('{{ route("boq.iko-atap.hitung") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (bagian === 1) {
                results1 = data.results;
                renderTable('1', results1, document.getElementById('grandTotal1'));
            } else {
                results2 = data.results;
                renderTable('2', results2, document.getElementById('grandTotal2'));
            }
            document.getElementById(`hasilBagian${bagian}`).classList.remove('hidden');
            updateTotal();
            updatePdfData();
        }
    });
}

function renderTable(bagian, results, grandTotalEl) {
    let container = document.getElementById(`tableBagian${bagian}`);
    let html = `<table><thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
    let total = 0;
    results.forEach(item => {
        total += item.total_harga;
        html += `<tr>
            <td><strong>${item.nama_produk}</strong></td>
            <td><span class="badge-area">${item.area}</span></td>
            <td style="text-align:right; font-weight:500;">${item.qty}</td>
            <td style="text-align:right; color:#718096;">${item.satuan}</td>
            <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${item.total_harga.toLocaleString()}</td>
        </tr>`;
    });
    html += `</tbody></table>`;
    container.innerHTML = html;
    grandTotalEl.innerHTML = `Rp ${total.toLocaleString()}`;
}

function updateTotal() {
    let total1 = results1.reduce((s, i) => s + i.total_harga, 0);
    let total2 = results2.reduce((s, i) => s + i.total_harga, 0);
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${(total1 + total2).toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_waste_1').value = document.getElementById('waste_1').value;
    document.getElementById('pdf_waste_2').value = document.getElementById('waste_2').value;
    
    document.getElementById('pdf_luas_1').value = document.getElementById('luas_atap_1').value;
    document.getElementById('pdf_sudut_1').value = document.getElementById('sudut_1').value;
    document.getElementById('pdf_starter_1').value = document.getElementById('starter_1').value;
    document.getElementById('pdf_nok_1').value = document.getElementById('nok_1').value;
    document.getElementById('pdf_flashing_1').value = document.getElementById('flashing_1').value;
    document.getElementById('pdf_hasil_1').value = JSON.stringify(results1);
    document.getElementById('pdf_total_1').value = document.getElementById('grandTotal1').innerText;
    
    document.getElementById('pdf_luas_2').value = document.getElementById('luas_atap_2').value;
    document.getElementById('pdf_sudut_2').value = document.getElementById('sudut_2').value;
    document.getElementById('pdf_starter_2').value = document.getElementById('starter_2').value;
    document.getElementById('pdf_nok_2').value = document.getElementById('nok_2').value;
    document.getElementById('pdf_flashing_2').value = document.getElementById('flashing_2').value;
    document.getElementById('pdf_hasil_2').value = JSON.stringify(results2);
    document.getElementById('pdf_total_2').value = document.getElementById('grandTotal2').innerText;
    
    document.getElementById('pdf_grand_total').value = document.getElementById('totalKeseluruhan').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection