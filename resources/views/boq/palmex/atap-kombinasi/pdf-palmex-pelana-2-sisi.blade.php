{{-- resources/views/boq/palmex/pelana-2-sisi/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOQ - Pelana + 2 Sisi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Poppins', sans-serif;
            background: #f1f4f9; 
            margin: 0;
            padding: 20px;
        }
        
        @media screen {
            body { 
                display: flex; 
                flex-direction: column;
                align-items: center; 
                min-height: 100vh; 
            }
            .report-paper { 
                width: 210mm;
                min-height: 297mm;
                background: white; 
                box-shadow: 0 2px 20px rgba(0,0,0,0.08); 
                border: 1px solid #e2e8f0; 
                margin: 0 auto 20px auto;
                padding: 12mm 10mm;
            }
        }
        
        @media print {
            @page {
                size: A4;
                margin: 10mm 12mm;
            }
            
            body { 
                background: white; 
                padding: 0;
                margin: 0;
            }
            
            .report-paper { 
                width: 100%;
                min-height: auto;
                box-shadow: none;
                border: none;
                page-break-after: always;
                margin: 0;
                padding: 0;
            }
            
            thead {
                display: table-header-group;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            .no-print {
                display: none !important;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            .table-header th {
                background-color: #1a1a2e !important;
                color: white !important;
            }
            
            .info-grid {
                background-color: #f8fafc !important;
            }
            
            .grand-total-box {
                background: #1a1a2e !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .subtotal-box {
                background: #f8fafc !important;
            }
            
            .group-header-atap {
                background: #e8edf5 !important;
            }
            .group-header-aksesoris {
                background: #e8f5ed !important;
            }
            .group-header-additional {
                background: #f5e8ed !important;
            }
        }

        .report-paper { 
            background: white; 
            box-sizing: border-box;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1a1a2e;
            padding-bottom: 8px;
            margin-bottom: 14px;
            width: 100%;
        }
        
        .logo-container {
            width: 80px;
            flex-shrink: 0;
        }
        
        .logo-container img {
            height: 45px;
            width: auto;
            display: block;
        }
        
        .company-address {
            flex: 1;
            text-align: center;
            font-weight: 500;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.5;
            padding: 0 10px;
            color: #334155;
        }
        
        .title-section {
            text-align: center;
            margin: 8px 0 12px 0;
        }
        
        .title-section h1 {
            font-size: 13pt;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        
        .title-section p {
            font-size: 8pt;
            color: #64748b;
            font-weight: 400;
        }
        
        .nomor-boq-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 6px;
        }

        .nomor-boq {
            font-size: 10pt;
            font-weight: 600;
            color: #1a1a2e;
            background: #f1f4f9;
            padding: 4px 16px;
            border-radius: 4px;
            display: inline-block;
            letter-spacing: 0.3px;
            border: 1px solid #e2e8f0;
        }

        .btn-copy-boq {
            background: #1a1a2e;
            color: white;
            border: none;
            padding: 4px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 8pt;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .btn-copy-boq:hover {
            background: #2d2d44;
        }

        .btn-copy-boq.copied {
            background: #2d6a4f;
        }

        @media print {
            .btn-copy-boq {
                display: none !important;
            }
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 14px;
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 6.5pt;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 8pt;
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
        }
        
        .section-title {
            font-size: 10pt;
            font-weight: 700;
            color: #1a1a2e;
            margin: 14px 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .section-title .sub {
            font-size: 7pt;
            font-weight: 400;
            color: #94a3b8;
            margin-left: 8px;
        }
        
        .group-header {
            font-size: 8pt;
            font-weight: 600;
            padding: 6px 10px;
            margin: 6px 0 4px 0;
            border-radius: 3px;
        }
        
        .group-header-atap {
            background: #e8edf5;
            color: #1a3a5c;
            border-left: 3px solid #2b6cb0;
        }
        
        .group-header-aksesoris {
            background: #e8f5ed;
            color: #1a5c3a;
            border-left: 3px solid #38a169;
        }
        
        .group-header-additional {
            background: #f5e8ed;
            color: #5c1a3a;
            border-left: 3px solid #e53e3e;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 4px;
        }
        
        th, td {
            padding: 4px 6px;
            text-align: left;
            border-bottom: 1px solid #f1f4f9;
        }
        
        .table-header th {
            background-color: #1a1a2e;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 6pt;
            letter-spacing: 0.5px;
            padding: 5px 6px;
            border: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 3px;
            font-size: 6pt;
            font-weight: 500;
        }
        .badge-blue { background: #e8edf5; color: #1a3a5c; }
        .badge-green { background: #e8f5ed; color: #1a5c3a; }
        .badge-orange { background: #f5ede8; color: #5c3a1a; }
        .badge-purple { background: #ede8f5; color: #3a1a5c; }
        .badge-red { background: #f5e8ed; color: #5c1a3a; }
        .badge-gray { background: #f1f4f9; color: #4a5568; }
        
        .subtotal-box {
            text-align: right;
            font-weight: 600;
            font-size: 8pt;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 4px;
            margin-top: 4px;
            border: 1px solid #e2e8f0;
        }
        
        .grand-total-box {
            background: #1a1a2e;
            color: white;
            border-radius: 4px;
            padding: 14px 20px;
            margin: 14px 0 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .grand-total-box .label {
            font-size: 10pt;
            font-weight: 600;
        }
        
        .grand-total-box .value {
            font-size: 14pt;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .footer {
            text-align: center;
            font-size: 6.5pt;
            color: #94a3b8;
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 10px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            font-size: 9pt;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-print {
            background: #1a1a2e;
            color: white;
        }
        
        .btn-print:hover {
            background: #2d2d44;
            transform: translateY(-1px);
        }
        
        .btn-copy-boq-global {
            background: #1a1a2e;
            color: white;
        }
        
        .btn-copy-boq-global:hover {
            background: #2d2d44;
            transform: translateY(-1px);
        }
        
        .btn-copy-boq-global.copied {
            background: #2d6a4f;
        }

        .toast-message {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1a1a2e;
            color: white;
            padding: 10px 24px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 9999;
            font-family: 'Poppins', sans-serif;
        }
        
        .toast-message.show {
            opacity: 1;
        }

        @media print {
            .action-buttons {
                display: none !important;
            }
            .toast-message {
                display: none !important;
            }
        }
    </style>
</head>
<body>

@php
  function formatRp($angka) {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}
    
    function safeFloat($val) {
        return (float) preg_replace('/[^0-9.]/', '', $val);
    }
    
    $badgeMap = [
        'Atap Utama' => 'badge-blue',
        'Underlayer' => 'badge-green',
        'Starter' => 'badge-orange',
        'Nok Atas' => 'badge-purple',
        'Nok & Jurai' => 'badge-purple',
        'Talang Jurai' => 'badge-purple',
        'Flashing' => 'badge-gray',
        'Metal Flashing' => 'badge-gray',
        'Shingle Stick' => 'badge-gray',
        'Paku & Screw' => 'badge-gray',
        'Screw' => 'badge-gray',
        'Screw Plywood' => 'badge-gray',
        'Wall Flashing' => 'badge-red',
        'Flashing Kaca' => 'badge-red',
        'Penangkal Petir' => 'badge-red',
        'Ventilasi Exhaust' => 'badge-red',
        'Wind' => 'badge-orange',
        'Rail' => 'badge-orange',
        'Topcap' => 'badge-orange',
        'Lantai Kerja' => 'badge-green',
    ];
    
    // ===== KELOMPOKKAN HASIL =====
    $kelompok = [
        'Atap Utama' => [],
        'Aksesoris' => [],
        'Additional' => []
    ];
    
    $aksesorisAreas = ['Starter', 'Nok Atas', 'Underlayer', 'Flashing', 'Paku & Screw', 'Screw', 'Screw Plywood', 'Metal Flashing', 'Shingle Stick', 'Talang Jurai', 'Lantai Kerja', 'Topcap', 'Wind', 'Rail'];
    $additionalAreas = ['Wall Flashing', 'Flashing Kaca', 'Penangkal Petir', 'Ventilasi Exhaust'];
    
    function mergeItemsPdf($items) {
        $merged = [];
        foreach ($items as $item) {
            $key = $item['nama_produk'] ?? $item['nama'] ?? '';
            if (empty($key)) continue;
            
            if (!isset($merged[$key])) {
                $merged[$key] = [
                    'nama_produk' => $key,
                    'area' => $item['area'] ?? '',
                    'qty' => 0,
                    'satuan' => $item['satuan'] ?? 'pcs',
                    'harga_satuan' => $item['harga_satuan'] ?? 0,
                    'total_harga' => 0
                ];
            }
            $merged[$key]['qty'] += (float)($item['qty'] ?? 0);
            $merged[$key]['total_harga'] += (float)($item['total_harga'] ?? 0);
        }
        return array_values($merged);
    }
    
    // Proses semua bagian
    $allResults = [];
    $bagianData = [];
    for ($i = 1; $i <= 3; $i++) {
        $key = "bagian{$i}";
        $hasil = $data[$key]['hasil'] ?? [];
        $bagianData[$key] = $hasil;
        $allResults = array_merge($allResults, $hasil);
    }
    
    foreach ($allResults as $item) {
        $area = $item['area'] ?? '';
        if ($area === 'Atap Utama') {
            $kelompok['Atap Utama'][] = $item;
        } elseif (in_array($area, $additionalAreas) && ((float)($item['qty'] ?? 0) > 0)) {
            $kelompok['Additional'][] = $item;
        } elseif (in_array($area, $aksesorisAreas)) {
            $kelompok['Aksesoris'][] = $item;
        } else {
            $kelompok['Aksesoris'][] = $item;
        }
    }
    
    $kelompok['Atap Utama'] = mergeItemsPdf($kelompok['Atap Utama']);
    $kelompok['Aksesoris'] = mergeItemsPdf($kelompok['Aksesoris']);
    $kelompok['Additional'] = mergeItemsPdf($kelompok['Additional']);
    
    $totalAtap = array_sum(array_column($kelompok['Atap Utama'], 'total_harga'));
    $totalAksesoris = array_sum(array_column($kelompok['Aksesoris'], 'total_harga'));
    $totalAdditional = array_sum(array_column($kelompok['Additional'], 'total_harga'));
    $grandTotal = $totalAtap + $totalAksesoris + $totalAdditional;
    
    $bagian1Data = $data['bagian1'] ?? [];
    $bagian2Data = $data['bagian2'] ?? [];
    $bagian3Data = $data['bagian3'] ?? [];
    
    $opsiDinding1 = safeFloat($data['bagian1']['opsi']['dinding'] ?? 0);
    $opsiKaca1 = safeFloat($data['bagian1']['opsi']['kaca'] ?? 0);
    $opsiDinding2 = safeFloat($data['bagian2']['opsi']['dinding'] ?? 0);
    $opsiKaca2 = safeFloat($data['bagian2']['opsi']['kaca'] ?? 0);
    $opsiDinding3 = safeFloat($data['bagian3']['opsi']['dinding'] ?? 0);
    $opsiKaca3 = safeFloat($data['bagian3']['opsi']['kaca'] ?? 0);
    
    $nomorBoq = $data['nomor_boq'] ?? 'BOQ-' . date('Ymd') . '-0001';
@endphp

<div class="toast-message" id="toastMessage">Nomor BOQ berhasil disalin!</div>

<div class="report-paper">
    
    <div class="header-container">
        <div class="logo-container">
            <img src="{{ asset('images/atl new logo.png') }}" alt="Logo Perusahaan">
        </div>
        <div class="company-address">
            PT. ATAP TEDUH LESTARI<br>
            JL GATOT SUBROTO KAV.53, JDC BUSINESS CENTER LT.6<br>
            PETAMBURAN TANAH ABANG JAKARTA PUSAT DKI JAKARTA
        </div>
        <div style="width: 80px;"></div>
    </div>

    <div class="title-section">
        <h1>BILL OF QUANTITY</h1>
        <p>{{ $data['judul'] ?? 'Pelana + 2 Sisi' }} | {{ $data['brand'] ?? 'PALMEX' }}</p>
        
        <div class="nomor-boq-wrapper">
            <span class="nomor-boq" id="nomorBoqText">
                {{ $nomorBoq }}
            </span>
            <button class="btn-copy-boq no-print" onclick="copyNomorBoq()">
                Copy
            </button>
        </div>
    </div>

    <!-- DATA PERHITUNGAN -->
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Total Luas Atap</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['luas_atap'] ?? 0) + safeFloat($bagian2Data['data_perhitungan']['luas_atap'] ?? 0) + safeFloat($bagian3Data['data_perhitungan']['luas_atap'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Starter Total</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['starter'] ?? 0) + safeFloat($bagian2Data['data_perhitungan']['starter'] ?? 0) + safeFloat($bagian3Data['data_perhitungan']['starter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Flashing Total</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['flashing'] ?? 0) + safeFloat($bagian2Data['data_perhitungan']['flashing'] ?? 0) + safeFloat($bagian3Data['data_perhitungan']['flashing'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Nok Atas (Pelana)</span>
            <span class="info-value">{{ number_format(safeFloat($bagian2Data['data_perhitungan']['nok_atas'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste_1'] ?? 5 }}% / {{ $data['waste_2'] ?? 5 }}% / {{ $data['waste_3'] ?? 5 }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sistem Pemasangan</span>
            <span class="info-value">{{ ucfirst($bagian1Data['sistem_pemasangan'] ?? 'Expose') }}</span>
        </div>
    </div>

    <!-- ===== RINCIAN MATERIAL - KELOMPOK ===== -->
    <div class="section-title">
        RINCIAN KEBUTUHAN MATERIAL
    </div>

    <!-- ATAP UTAMA -->
    @if(count($kelompok['Atap Utama']) > 0)
    <div class="group-header group-header-atap">🏠 ATAP UTAMA</div>
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($kelompok['Atap Utama'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL ATAP UTAMA: {{ formatRp($totalAtap) }}</div>
    @endif

    <!-- AKSESORIS -->
    @if(count($kelompok['Aksesoris']) > 0)
    <div class="group-header group-header-aksesoris">🔧 AKSESORIS</div>
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($kelompok['Aksesoris'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL AKSESORIS: {{ formatRp($totalAksesoris) }}</div>
    @endif

    <!-- ADDITIONAL -->
    @if(count($kelompok['Additional']) > 0)
    <div class="group-header group-header-additional">➕ ADDITIONAL</div>
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($kelompok['Additional'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL ADDITIONAL: {{ formatRp($totalAdditional) }}</div>
    @endif

    <!-- GRAND TOTAL -->
    <div class="grand-total-box">
        <span class="label">🏆 GRAND TOTAL (Kiri + Pelana + Kanan)</span>
        <span class="value">{{ formatRp($grandTotal) }}</span>
    </div>

    <!-- DETAIL BAGIAN 1: KIRI -->
    @if(count($bagian1Data['hasil'] ?? []) > 0)
    <div class="section-title">
        DETAIL BAGIAN KIRI
        <span class="sub">Kemiringan {{ $bagian1Data['data_perhitungan']['sudut'] ?? 0 }}°</span>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['luas_atap'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Starter</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['starter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Flashing</span>
            <span class="info-value">{{ number_format(safeFloat($bagian1Data['data_perhitungan']['flashing'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste_1'] ?? 5 }}%</span>
        </div>
        @if($opsiDinding1 > 0 || $opsiKaca1 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding1 > 0)Dinding: {{ number_format($opsiDinding1, 2) }} m @endif
                @if($opsiDinding1 > 0 && $opsiKaca1 > 0) | @endif
                @if($opsiKaca1 > 0)Kaca: {{ number_format($opsiKaca1, 2) }} m @endif
            </span>
        </div>
        @endif
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian1Data['hasil'] ?? [] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL KIRI: {{ formatRp(preg_replace('/[^0-9]/', '', $bagian1Data['total'] ?? '0')) }}</div>
    @endif

    <!-- DETAIL BAGIAN 2: TENGAH (PELANA) -->
    @if(count($bagian2Data['hasil'] ?? []) > 0)
    <div class="section-title">
        DETAIL BAGIAN TENGAH (PELANA)
        <span class="sub">Kemiringan {{ $bagian2Data['data_perhitungan']['sudut'] ?? 0 }}°</span>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format(safeFloat($bagian2Data['data_perhitungan']['luas_atap'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Starter</span>
            <span class="info-value">{{ number_format(safeFloat($bagian2Data['data_perhitungan']['starter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Nok Atas</span>
            <span class="info-value">{{ number_format(safeFloat($bagian2Data['data_perhitungan']['nok_atas'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Flashing</span>
            <span class="info-value">{{ number_format(safeFloat($bagian2Data['data_perhitungan']['flashing'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste_2'] ?? 5 }}%</span>
        </div>
        @if($opsiDinding2 > 0 || $opsiKaca2 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding2 > 0)Dinding: {{ number_format($opsiDinding2, 2) }} m @endif
                @if($opsiDinding2 > 0 && $opsiKaca2 > 0) | @endif
                @if($opsiKaca2 > 0)Kaca: {{ number_format($opsiKaca2, 2) }} m @endif
            </span>
        </div>
        @endif
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian2Data['hasil'] ?? [] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL PELANA: {{ formatRp(preg_replace('/[^0-9]/', '', $bagian2Data['total'] ?? '0')) }}</div>
    @endif

    <!-- DETAIL BAGIAN 3: KANAN -->
    @if(count($bagian3Data['hasil'] ?? []) > 0)
    <div class="section-title">
        DETAIL BAGIAN KANAN
        <span class="sub">Kemiringan {{ $bagian3Data['data_perhitungan']['sudut'] ?? 0 }}°</span>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format(safeFloat($bagian3Data['data_perhitungan']['luas_atap'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Starter</span>
            <span class="info-value">{{ number_format(safeFloat($bagian3Data['data_perhitungan']['starter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Flashing</span>
            <span class="info-value">{{ number_format(safeFloat($bagian3Data['data_perhitungan']['flashing'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste_3'] ?? 5 }}%</span>
        </div>
        @if($opsiDinding3 > 0 || $opsiKaca3 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding3 > 0)Dinding: {{ number_format($opsiDinding3, 2) }} m @endif
                @if($opsiDinding3 > 0 && $opsiKaca3 > 0) | @endif
                @if($opsiKaca3 > 0)Kaca: {{ number_format($opsiKaca3, 2) }} m @endif
            </span>
        </div>
        @endif
    </div>
    
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="27%">Nama Produk</th>
                    <th width="12%">Area</th>
                    <th width="8%" class="text-right">Qty</th>
                    <th width="8%" class="text-right">Satuan</th>
                    <th width="14%" class="text-right">Harga</th>
                    <th width="14%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian3Data['hasil'] ?? [] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge {{ $badgeMap[$item['area']] ?? 'badge-gray' }}">{{ $item['area'] }}</span></td>
                    <td class="text-right">{{ number_format((float)($item['qty'] ?? 0), 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['harga_satuan'] ?? 0) }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL KANAN: {{ formatRp(preg_replace('/[^0-9]/', '', $bagian3Data['total'] ?? '0')) }}</div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ PALMEX | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</div>

<div class="action-buttons no-print">
    <button onclick="window.print()" class="btn-action btn-print">
        🖨️ Cetak
    </button>
    <button onclick="copyNomorBoq()" class="btn-action btn-copy-boq-global" id="btnCopyBoqGlobal">
        📋 Copy Nomor BOQ
    </button>
</div>

<script>
function copyNomorBoq() {
    const textEl = document.getElementById('nomorBoqText');
    if (!textEl) return;
    
    let text = textEl.innerText.trim();
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Nomor BOQ berhasil disalin!');
            showCopyFeedback();
        }).catch(function() {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showToast('Nomor BOQ berhasil disalin!');
        showCopyFeedback();
    } catch (err) {
        showToast('Gagal menyalin: ' + err);
    }
    document.body.removeChild(textarea);
}

function showCopyFeedback() {
    const btn = document.querySelector('.btn-copy-boq');
    const btnGlobal = document.getElementById('btnCopyBoqGlobal');
    
    if (btn) {
        const originalText = btn.innerText;
        btn.innerText = 'Copied!';
        btn.classList.add('copied');
        setTimeout(function() {
            btn.innerText = originalText;
            btn.classList.remove('copied');
        }, 2000);
    }
    
    if (btnGlobal) {
        const originalText = btnGlobal.innerText;
        btnGlobal.innerText = 'Copied!';
        btnGlobal.classList.add('copied');
        setTimeout(function() {
            btnGlobal.innerText = originalText;
            btnGlobal.classList.remove('copied');
        }, 2000);
    }
}

function showToast(message) {
    const toast = document.getElementById('toastMessage');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(function() {
        toast.classList.remove('show');
    }, 3000);
}
</script>

</body>
</html>