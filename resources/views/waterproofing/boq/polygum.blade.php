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
    
    .btn-primary {
        background: #0f3460;
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
        background: #1a1a2e;
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
    
    .total-box {
        background: #1a1a2e;
        border-radius: 12px;
        padding: 20px 24px;
        color: white;
    }

    /* ===== GROUP HEADER ===== */
    .group-header {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 12px;
        margin: 12px 0 4px 0;
        border-radius: 4px;
        background: #f1f4f9;
        color: #1a1a2e;
        border-left: 3px solid #1a1a2e;
    }
    
    .group-header-utama {
        background: #e8edf5;
        border-left-color: #0f3460;
        color: #1a3a5c;
    }
    
    .group-header-upstand {
        background: #f5ede8;
        border-left-color: #e67e22;
        color: #5c3a1a;
    }
    
    .group-header-aksesoris {
        background: #e8f5ed;
        border-left-color: #38a169;
        color: #1a5c3a;
    }

    /* ===== DATA GEOMETRIK ===== */
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
    
    .data-geometrik .row .label {
        font-size: 12px;
        font-weight: 500;
        color: #4a5568;
    }
    
    .data-geometrik .row .value {
        font-size: 12px;
        font-weight: 600;
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
        font-weight: 600;
        color: #1a1a2e;
        width: 70px;
        padding: 0;
        text-align: right;
    }

    /* ===== PILIH MATERIAL ===== */
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

    .hidden { display: none !important; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap-3 { gap: 12px; }
    .gap-4 { gap: 16px; }
    .mt-1 { margin-top: 4px; }
    .mt-4 { margin-top: 16px; }
    .mb-1 { margin-bottom: 4px; }
    .mb-2 { margin-bottom: 8px; }
    .border-t { border-top: 1px solid #e2e8f0; }
    .pt-4 { padding-top: 16px; }
    .text-right { text-align: right; }
    .text-xs { font-size: 12px; }
    .text-xl { font-size: 20px; }
    .font-semibold { font-weight: 600; }
    .text-gray-700 { color: #374151; }
    .text-gray-400 { color: #9ca3af; }
    .space-y-6 > * + * { margin-top: 24px; }

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
    }
</style>

<div class="space-y-6">

    <!-- Data Perhitungan -->
    <div class="section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Data Perhitungan</h3>
                <p class="section-subtitle">Data dari perhitungan sebelumnya</p>
            </div>
        </div>
        <div class="section-body">
            <div class="data-geometrik">
                <div class="row">
                    <span class="label">Luas Area</span>
                    <span class="value">
                        <input type="text" value="{{ number_format($luas, 2) }}" readonly>
                        <span class="unit">m²</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Waste</span>
                    <span class="value">
                        <input type="text" value="{{ $waste }}" readonly>
                        <span class="unit">%</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Panjang Perimeter</span>
                    <span class="value">
                        <input type="text" value="{{ number_format($panjangPerimeter, 2) }}" readonly>
                        <span class="unit">m</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Tinggi Perimeter</span>
                    <span class="value">
                        <input type="text" value="{{ number_format($tinggiPerimeter, 2) }}" readonly>
                        <span class="unit">cm</span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Sudut Kemiringan</span>
                    <span class="value">
                        <input type="text" value="{{ number_format($sudut, 2) }}" readonly>
                        <span class="unit">°</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilih Produk & Hitung -->
    <div class="section-card">
        <div class="section-header">
            <div>
                <h3 class="section-title">Pilih Produk & Hitung Material</h3>
                <p class="section-subtitle">Pilih produk waterproofing Duo yang akan digunakan</p>
            </div>
        </div>
        <div class="section-body">
            <div class="pilih-material">
                <div class="row">
                    <span class="label">Produk Waterproofing</span>
                    <span class="value">
                        <select id="produk_waterproofing" onchange="hitungMaterial()">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </span>
                </div>
            </div>
            
            <button onclick="hitungMaterial()" class="btn-primary" style="margin-top:16px;">
                Hitung Material
            </button>
            
            <div id="hasilMaterial" class="mt-4">
                <div class="border-t pt-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Rincian Material</h4>
                    <div id="tableMaterial"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export PDF -->
    <div class="total-box">
        <form action="{{ route('waterproofing.polygum.export-pdf') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="luas" value="{{ $luas }}">
            <input type="hidden" name="waste" value="{{ $waste }}">
            <input type="hidden" name="panjang_perimeter" value="{{ $panjangPerimeter }}">
            <input type="hidden" name="tinggi_perimeter" value="{{ $tinggiPerimeter }}">
            <input type="hidden" name="sudut" value="{{ $sudut }}">
            <input type="hidden" name="brand_id" value="{{ $brandId }}">
            <input type="hidden" name="produk_id" id="pdf_produk_id">
            <input type="hidden" name="hasil" id="pdf_hasil">
            <button type="submit" class="btn-pdf">Export PDF</button>
        </form>
    </div>
</div>

<script>
let results = [];

window.onload = function() {
    results = @json($results ?? []);
    if (results && results.length > 0) {
        renderTable(results);
    }
    updatePdfData();
};

function hitungMaterial() {
    let produkId = document.getElementById('produk_waterproofing').value;
    
    if (!produkId) {
        alert('Pilih produk waterproofing terlebih dahulu!');
        return;
    }

    let data = {
        luas: {{ $luas }},
        waste: {{ $waste }},
        panjang_perimeter: {{ $panjangPerimeter }},
        tinggi_perimeter: {{ $tinggiPerimeter }},
        sudut: {{ $sudut }},
        brand_id: {{ $brandId ?? 'null' }},
        produk_id: produkId
    };

    fetch('{{ route("waterproofing.polygum.hitung") }}', {
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
            results = data.results;
            renderTable(results);
            updatePdfData();
        }
    })
    .catch(error => console.error('Error:', error));
}

function renderTable(results) {
    let container = document.getElementById('tableMaterial');
    
    // Kelompokkan berdasarkan area
    let kelompok = {
        'Area Utama': [],
        'Upstand': [],
        'Aksesoris': []
    };

    // Area yang termasuk Upstand (pertemuan dengan dinding/vertikal)
    const upstandAreas = ['Upstand', 'Wall Flashing', 'Dinding', 'Perimeter', 'Cerobong Asap', 'Penangkal Petir'];
    
    // Area yang termasuk Aksesoris — PELAPIS DASAR DITAMBAHKAN DI SINI
    const aksesorisAreas = [
        'Pelapis Dasar',   // ← tambahan
        'Starter', 
        'Tape Roof', 
        'Tape Roof Nok', 
        'Jurai', 
        'Nok', 
        'Nok Tutup', 
        'Nok 3 Arah', 
        'Metal Flashing', 
        'Paku', 
        'Screw', 
        'Sealant', 
        'Filler', 
        'Insulasi', 
        'Aksesoris'
    ];

    results.forEach(item => {
        const area = (item.area || '').toLowerCase();
        let matched = false;
        
        for (let a of upstandAreas) {
            if (area.includes(a.toLowerCase())) {
                kelompok['Upstand'].push(item);
                matched = true;
                break;
            }
        }
        
        if (!matched) {
            for (let a of aksesorisAreas) {
                if (area.includes(a.toLowerCase())) {
                    kelompok['Aksesoris'].push(item);
                    matched = true;
                    break;
                }
            }
        }
        
        if (!matched) {
            kelompok['Area Utama'].push(item);
        }
    });

    let html = '';

    const groupConfig = [
        { key: 'Area Utama', label: 'AREA UTAMA', cls: 'group-header-utama' },
        { key: 'Upstand', label: 'UPSTAND', cls: 'group-header-upstand' },
        { key: 'Aksesoris', label: 'AKSESORIS', cls: 'group-header-aksesoris' }
    ];

    groupConfig.forEach(({ key, label, cls }) => {
        const items = kelompok[key];
        if (items.length === 0) return;

        html += `<div class="group-header ${cls}">${label}</div>`;
        html += `<div class="table-container">`;
        html += `<table>`;
        html += `<thead>
            <tr>
                <th style="width:8%;text-align:center;">No</th>
                <th style="width:62%;">Produk</th>
                <th style="width:15%;text-align:right;">Qty</th>
                <th style="width:15%;text-align:right;">Satuan</th>
            </tr>
        </thead>`;
        html += `<tbody>`;
        items.forEach((item, index) => {
            html += `<tr>
                <td style="text-align:center;">${index + 1}</td>
                <td>${item.nama_produk}</td>
                <td style="text-align:right;">${item.qty}</td>
                <td style="text-align:right;">${item.satuan}</td>
            </tr>`;
        });
        html += `</tbody></table></div>`;
    });

    if (html === '') {
        html = '<div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px;">Belum ada data material</div>';
    }

    container.innerHTML = html;
}

function updatePdfData() {
    let produkSelect = document.getElementById('produk_waterproofing');
    document.getElementById('pdf_produk_id').value = produkSelect ? produkSelect.value : '';
    document.getElementById('pdf_hasil').value = JSON.stringify(results);
}
</script>
@endsection