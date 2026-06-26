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
                <h2 class="text-xl font-semibold mb-1">BOQ - Pelana X</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material atap kombinasi 3 Pelana bersusun</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">IKO - ATAP</span>
            </div>
        </div>
    </div>

    <!-- Data Perhitungan (Readonly) -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">📐 Data Perhitungan</h3>
                    <p class="section-subtitle">Data luas atap dan kemiringan dari perhitungan sebelumnya</p>
                </div>
                <span class="badge-section">READONLY</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="data-box">
                    <label>Luas Atap Total</label>
                    <div class="value">
                        <input type="number" id="luas_atap_total" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                    </div>
                </div>
                <div class="data-box">
                    <label>Sudut</label>
                    <div class="value">
                        <input type="number" id="sudut" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">°</span>
                    </div>
                </div>
                <div class="data-box">
                    <label>Starter</label>
                    <div class="value">
                        <input type="number" id="starter" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                    </div>
                </div>
                <div class="data-box">
                    <label>Nok & Jurai</label>
                    <div class="value">
                        <input type="number" id="nok_jurai" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                    </div>
                </div>
                <div class="data-box">
                    <label>Flashing</label>
                    <div class="value">
                        <input type="number" id="flashing" class="input-field" style="border: none; padding: 0; background: transparent;" readonly>
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Talang Jurai & Wall Flashing -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">🔧 Aksesoris Tambahan</h3>
                    <p class="section-subtitle">Masukkan panjang aksesoris tambahan</p>
                </div>
                <span class="badge-section">OPSIONAL</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">📏 Talang Jurai</label>
                    <input type="number" id="talang_jurai" step="0.1" value="{{ request()->get('talang_jurai', 0) }}" class="input-field">
                    <span style="font-size: 10px; color: #a0aec0;">meter</span>
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">🧱 Wall Flashing</label>
                    <input type="number" id="wall_flashing" step="0.1" value="0" class="input-field">
                    <span style="font-size: 10px; color: #a0aec0;">meter</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Produk & Hitung Material -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">🏷️ Pilih Produk & Hitung Material</h3>
                    <p class="section-subtitle">Pilih produk atap dan underlayer yang akan digunakan</p>
                </div>
                <span class="badge-section">UTAMA</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Produk Atap Utama</label>
                    <select id="produk_atap" class="input-field">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Underlayer</label>
                    <select id="underlayer" class="input-field">
                        <option value="">Pilih Underlayer</option>
                        @foreach($underlayers_1 as $underlayer)
                            <option value="{{ $underlayer->id }}">{{ $underlayer->nama_produk }}</option>
                        @endforeach
                    </select>
                    @if($sudut_1 <= 30 && $sudut_1 > 0)
                        <p class="warning-text">⚠️ Kemiringan sudut {{ $sudut_1 }}° (≤ 30°), disarankan menggunakan underlayer khusus.</p>
                    @endif
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Waste (%)</label>
                <div class="waste-input">
                    <input type="number" id="waste" step="1" value="5" class="input-field" style="width: 100px;">
                    <span>%</span>
                </div>
            </div>
            
            <button onclick="hitungMaterial()" class="btn-primary">
                🏠 Hitung Material
            </button>
            
            <div id="hasilMaterial" class="mt-4 hidden">
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material</h4>
                    <div class="table-container" id="tableMaterial"></div>
                    <div class="text-right mt-3 font-semibold text-gray-800 text-base" id="grandTotal">Rp 0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL KESELURUHAN -->
    <div class="total-box">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-sm font-semibold text-white">Total Keseluruhan</h4>
                <p class="text-gray-400 text-[10px]">Pelana X (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="/boq/atap-kombinasi/pelana-x/export-pdf" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Pelana X">
            <input type="hidden" name="brand" value="IKO - ATAP">
            <input type="hidden" name="luas_atap" id="pdf_luas_atap">
            <input type="hidden" name="sudut" id="pdf_sudut">
            <input type="hidden" name="starter" id="pdf_starter">
            <input type="hidden" name="nok_jurai" id="pdf_nok_jurai">
            <input type="hidden" name="talang_jurai" id="pdf_talang_jurai">
            <input type="hidden" name="wall_flashing" id="pdf_wall_flashing">
            <input type="hidden" name="waste" id="pdf_waste">
            <input type="hidden" name="hasil" id="pdf_hasil">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            <input type="hidden" name="tanggal" id="pdf_tanggal">
            <input type="hidden" name="waktu" id="pdf_waktu">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF
            </button>
        </form>
    </div>
</div>

<script>
let results = [];

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Luas
    let luas1 = parseFloat(urlParams.get('luas_atap_1')) || 0;
    let luas2 = parseFloat(urlParams.get('luas_atap_2')) || 0;
    let luas3 = parseFloat(urlParams.get('luas_atap_3')) || 0;
    
    // Starter
    let starter1 = parseFloat(urlParams.get('starter_1')) || 0;
    let starter2 = parseFloat(urlParams.get('starter_2')) || 0;
    let starter3 = parseFloat(urlParams.get('starter_3')) || 0;
    
    // Lebar B (tengah) dan sudut
    let lebarB = parseFloat(urlParams.get('lebar_2')) || 0;
    let sudut = parseFloat(urlParams.get('sudut_2')) || 0;
    
    // Nok
    let nok1 = parseFloat(urlParams.get('nok_1')) || 0;
    let nok2 = parseFloat(urlParams.get('nok_2')) || 0;
    let nok3 = parseFloat(urlParams.get('nok_3')) || 0;
    
    // Talang Jurai & Wall Flashing dari URL
    let talangJurai = parseFloat(urlParams.get('talang_jurai')) || 0;
    let wallFlashing = parseFloat(urlParams.get('wall_flashing')) || 0;
    
    // Hitung TOTAL STARTER = (starter1+starter2+starter3) - ((lebarB x 4) / cos(sudut))
    let radSudut = sudut * Math.PI / 180;
    let cosSudut = Math.cos(radSudut);
    let pengurang = (lebarB * 4) / cosSudut;
    let totalStarter = (starter1 + starter2 + starter3) - pengurang;
    
    // Set nilai ke input
    document.getElementById('luas_atap_total').value = luas1 + luas2 + luas3;
    document.getElementById('sudut').value = sudut;
    document.getElementById('starter').value = totalStarter.toFixed(2);
    document.getElementById('nok_jurai').value = nok1 + nok2 + nok3;
    document.getElementById('flashing').value = totalStarter.toFixed(2);
    
    // Set nilai talang jurai & wall flashing
    document.getElementById('talang_jurai').value = talangJurai;
    document.getElementById('wall_flashing').value = wallFlashing;
};

function hitungMaterial() {
    let wasteValue = parseFloat(document.getElementById('waste').value) || 5;
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap_total').value),
        sudut: parseFloat(document.getElementById('sudut').value),
        panjang_starter: parseFloat(document.getElementById('starter').value),
        panjang_nok_jurai: parseFloat(document.getElementById('nok_jurai').value),
        panjang_flashing: parseFloat(document.getElementById('starter').value),
        panjang_talang_jurai: parseFloat(document.getElementById('talang_jurai').value) || 0,
        panjang_wall_flashing: parseFloat(document.getElementById('wall_flashing').value) || 0,
        produk_atap_id: document.getElementById('produk_atap').value,
        underlayer_id: document.getElementById('underlayer').value,
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
            results = data.results;
            renderTable(results);
            document.getElementById('hasilMaterial').classList.remove('hidden');
            updatePdfData();
        }
    });
}

function renderTable(results) {
    let container = document.getElementById('tableMaterial');
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
    document.getElementById('grandTotal').innerHTML = `Rp ${total.toLocaleString()}`;
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${total.toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_luas_atap').value = document.getElementById('luas_atap_total').value;
    document.getElementById('pdf_sudut').value = document.getElementById('sudut').value;
    document.getElementById('pdf_starter').value = document.getElementById('starter').value;
    document.getElementById('pdf_nok_jurai').value = document.getElementById('nok_jurai').value;
    document.getElementById('pdf_talang_jurai').value = document.getElementById('talang_jurai').value;
    document.getElementById('pdf_wall_flashing').value = document.getElementById('wall_flashing').value;
    document.getElementById('pdf_waste').value = document.getElementById('waste').value;
    document.getElementById('pdf_hasil').value = JSON.stringify(results);
    document.getElementById('pdf_grand_total').value = document.getElementById('grandTotal').innerText;
    document.getElementById('pdf_tanggal').value = new Date().toLocaleDateString('id-ID');
    document.getElementById('pdf_waktu').value = new Date().toLocaleTimeString('id-ID');
}
</script>
@endsection