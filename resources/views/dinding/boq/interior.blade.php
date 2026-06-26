@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
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
                <h2 class="text-xl font-semibold mb-1">BOQ - Dinding Interior</h2>
                <p class="text-gray-400 text-xs">Hitung kebutuhan material dinding interior</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-full px-3 py-1.5">
                <span class="text-[10px] font-medium text-gray-300">DINDING</span>
            </div>
        </div>
    </div>

    <!-- Data Perhitungan -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">📐 Data Perhitungan</h3>
                    <p class="section-subtitle">Data luas dinding dari perhitungan sebelumnya</p>
                </div>
                <span class="badge-section">READONLY</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="data-box">
                    <label>Luas Dinding</label>
                    <div class="value">
                        <input type="number" id="luas_dinding" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $luasDinding }}">
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">m²</span>
                    </div>
                </div>
                <div class="data-box">
                    <label>Waste</label>
                    <div class="value">
                        <input type="number" id="waste" class="input-field" style="border: none; padding: 0; background: transparent;" readonly value="{{ $waste }}">
                        <span style="font-size: 11px; color: #a0aec0; font-weight: 400;">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilihan Rangka -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">🏗️ Pilihan Rangka</h3>
                    <p class="section-subtitle">Pilih jenis rangka yang digunakan</p>
                </div>
                <span class="badge-section">UTAMA</span>
            </div>
        </div>
        <div class="section-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-medium text-gray-500 mb-1.5">Jenis Rangka</label>
                    <select id="rangka" class="input-field" onchange="hitungMaterial()">
                        <option value="Baja Ringan" {{ $rangka == 'Baja Ringan' ? 'selected' : '' }}>Baja Ringan</option>
                        <option value="Baja Berat" {{ $rangka == 'Baja Berat' ? 'selected' : '' }}>Baja Berat</option>
                    </select>
                </div>
            </div>
            
            <button onclick="hitungMaterial()" class="btn-primary" style="margin-top: 12px;">
                🧱 Hitung Ulang Material
            </button>
            
            <div id="hasilMaterial" class="mt-4">
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
                <p class="text-gray-400 text-[10px]">Dinding Interior (termasuk waste)</p>
            </div>
            <div class="text-right">
                <p class="label">Grand Total</p>
                <p class="amount" id="totalKeseluruhan">Rp 0</p>
            </div>
        </div>
        
        <form action="{{ route('dinding.export-pdf') }}" method="POST" target="_blank" class="mt-4">
            @csrf
            <input type="hidden" name="judul" value="BOQ - Dinding Interior">
            <input type="hidden" name="jenis" value="Interior">
            <input type="hidden" name="luas_dinding" id="pdf_luas_dinding" value="{{ $luasDinding }}">
            <input type="hidden" name="waste" id="pdf_waste" value="{{ $waste }}">
            <input type="hidden" name="rangka" id="pdf_rangka">
            <input type="hidden" name="hasil" id="pdf_hasil">
            <input type="hidden" name="grand_total" id="pdf_grand_total">
            
            <button type="submit" class="btn-pdf">
                📄 Export PDF
            </button>
        </form>
    </div>
</div>

<script>
let results = [];

window.onload = function() {
    hitungMaterial();
};

function hitungMaterial() {
    let luasDinding = parseFloat(document.getElementById('luas_dinding').value) || 0;
    let waste = parseFloat(document.getElementById('waste').value) || 5;
    let rangka = document.getElementById('rangka').value;

    let data = {
        luas_dinding: luasDinding,
        waste: waste,
        rangka: rangka
    };

    fetch('{{ route("dinding.hitung-interior") }}', {
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
            updatePdfData();
        }
    })
    .catch(error => console.error('Error:', error));
}

function renderTable(results) {
    let container = document.getElementById('tableMaterial');
    let html = `<table><thead><tr><th>Produk</th><th>Area</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Satuan</th><th style="text-align:right;">Total</th></tr></thead><tbody>`;
    let total = 0;
    for (let i = 0; i < results.length; i++) {
        let item = results[i];
        total += item.total_harga;
        html += `<tr>
            <td><strong>${item.nama_produk}</strong></td>
            <td><span class="badge-area">${item.area}</span></td>
            <td style="text-align:right; font-weight:500;">${item.qty}</td>
            <td style="text-align:right; color:#718096;">${item.satuan}</td>
            <td style="text-align:right; font-weight:600; color:#2d3748;">Rp ${item.total_harga.toLocaleString()}</td>
        </tr>`;
    }
    html += `</tbody></table>`;
    container.innerHTML = html;
    document.getElementById('grandTotal').innerHTML = `Rp ${total.toLocaleString()}`;
    document.getElementById('totalKeseluruhan').innerHTML = `Rp ${total.toLocaleString()}`;
}

function updatePdfData() {
    document.getElementById('pdf_rangka').value = document.getElementById('rangka').value;
    document.getElementById('pdf_hasil').value = JSON.stringify(results);
    document.getElementById('pdf_grand_total').value = document.getElementById('grandTotal').innerText;
}
</script>
@endsection