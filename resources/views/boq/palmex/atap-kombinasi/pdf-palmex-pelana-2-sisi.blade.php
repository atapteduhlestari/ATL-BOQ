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
            
            .grand-total-box {
                background: #1a1a2e !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .section-title {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .group-title {
                background-color: #f1f4f9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .info-list .info-item {
                border-bottom: 1px solid #f1f4f9 !important;
            }
            
            .page-break-rincian {
                page-break-before: always;
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
            background: #0f3460;
        }

        .btn-copy-boq.copied {
            background: #2d6a4f;
        }

        @media print {
            .btn-copy-boq {
                display: none !important;
            }
        }
        
        /* LIST STYLE - Dengan Indent */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 14px;
            padding: 0;
            padding-left: 20px;
        }
        
        .info-list .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #f1f4f9;
        }
        
        .info-list .info-item:last-child {
            border-bottom: none;
        }
        
        .info-list .info-label {
            font-size: 8pt;
            font-weight: 500;
            color: #4a5568;
        }
        
        .info-list .info-value {
            font-size: 8pt;
            font-weight: 600;
            color: #1e293b;
        }
        
        .section-title {
            font-size: 9pt;
            font-weight: 600;
            color: #1a1a2e;
            margin: 12px 0 6px 0;
            padding: 4px 10px;
            background: #f8fafc;
            border-radius: 3px;
            border-left: 3px solid #1a1a2e;
        }
        
        .section-title-kiri {
            border-left-color: #1e40af;
        }
        
        .section-title-tengah {
            border-left-color: #9d174d;
        }
        
        .section-title-kanan {
            border-left-color: #065f46;
        }
        
        .section-title-center {
            text-align: center;
            border-left: none;
            background: transparent;
        }
        
        .group-title {
            font-size: 8pt;
            font-weight: 600;
            padding: 6px 10px;
            margin: 6px 0 4px 0;
            border-radius: 3px;
        }
        
        .group-title-atap {
            background: #e8edf5;
            color: #1a3a5c;
            border-left: 3px solid #0f3460;
        }
        
        .group-title-aksesoris {
            background: #e8f5ed;
            color: #1a5c3a;
            border-left: 3px solid #38a169;
        }
        
        .group-title-additional {
            background: #f5ede8;
            color: #5c3a1a;
            border-left: 3px solid #e67e22;
        }
        
        .group-title-sistem {
            background: #f3e8ff;
            color: #4c1d95;
            border-left: 3px solid #7c3aed;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 6px;
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
        
        .subtotal-box {
            text-align: right;
            font-weight: 600;
            font-size: 8pt;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 4px;
            margin-top: 4px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
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
            background: #0f3460;
            transform: translateY(-1px);
        }
        
        .btn-back {
            background: #64748b;
            color: white;
        }
        
        .btn-back:hover {
            background: #475569;
            transform: translateY(-1px);
        }
        
        .btn-copy-boq-global {
            background: #1a1a2e;
            color: white;
        }
        
        .btn-copy-boq-global:hover {
            background: #0f3460;
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
    
    // ===== DECODE HASIL DARI JSON STRING =====
    $bagian1Data = [
        'hasil' => $data['bagian1']['hasil'] ?? [],
        'data_perhitungan' => $data['bagian1']['data_perhitungan'] ?? [],
        'total' => $data['bagian1']['total'] ?? 0,
        'opsi' => $data['bagian1']['opsi'] ?? ['dinding' => 0, 'kaca' => 0],
        'sistem_pemasangan' => $data['bagian1']['sistem_pemasangan'] ?? 'expose'
    ];
    
    $bagian2Data = [
        'hasil' => $data['bagian2']['hasil'] ?? [],
        'data_perhitungan' => $data['bagian2']['data_perhitungan'] ?? [],
        'total' => $data['bagian2']['total'] ?? 0,
        'opsi' => $data['bagian2']['opsi'] ?? ['dinding' => 0, 'kaca' => 0],
        'sistem_pemasangan' => $data['bagian2']['sistem_pemasangan'] ?? 'expose'
    ];
    
    $bagian3Data = [
        'hasil' => $data['bagian3']['hasil'] ?? [],
        'data_perhitungan' => $data['bagian3']['data_perhitungan'] ?? [],
        'total' => $data['bagian3']['total'] ?? 0,
        'opsi' => $data['bagian3']['opsi'] ?? ['dinding' => 0, 'kaca' => 0],
        'sistem_pemasangan' => $data['bagian3']['sistem_pemasangan'] ?? 'expose'
    ];
    
    // Gabungkan semua hasil
    $allResults = array_merge($bagian1Data['hasil'], $bagian2Data['hasil'], $bagian3Data['hasil']);
    
    // ===== KELOMPOKKAN HASIL =====
    $kelompok = [
        'Atap Utama' => [],
        'Aksesoris' => [],
        'Additional' => [],
        'Sistem Pendukung' => []
    ];
    
    $aksesorisAreas = ['Starter', 'Nok Atas', 'Flashing', 'Screw', 'Screw Plywood', 'Metal Flashing', 'Shingle Stick', 'Talang Jurai', 'Topcap', 'Wind', 'Rail'];
    $additionalAreas = ['Wall Flashing', 'Flashing Kaca', 'Penangkal Petir', 'Ventilasi Exhaust'];
    $sistemPendukungAreas = ['Underlayer', 'Lantai Kerja', 'Paku & Screw'];
    
    foreach($allResults as $item) {
        $area = $item['area'] ?? '';
        if ($area === 'Atap Utama') {
            $kelompok['Atap Utama'][] = $item;
        } elseif (in_array($area, $additionalAreas) && ((float)($item['qty'] ?? 0) > 0)) {
            $kelompok['Additional'][] = $item;
        } elseif (in_array($area, $sistemPendukungAreas)) {
            $kelompok['Sistem Pendukung'][] = $item;
        } elseif (in_array($area, $aksesorisAreas)) {
            $kelompok['Aksesoris'][] = $item;
        } else {
            $kelompok['Aksesoris'][] = $item;
        }
    }
    
    // Fungsi merge items
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
    
    $kelompok['Atap Utama'] = mergeItemsPdf($kelompok['Atap Utama']);
    $kelompok['Aksesoris'] = mergeItemsPdf($kelompok['Aksesoris']);
    $kelompok['Additional'] = mergeItemsPdf($kelompok['Additional']);
    $kelompok['Sistem Pendukung'] = mergeItemsPdf($kelompok['Sistem Pendukung']);
    
    $totalAtap = array_sum(array_column($kelompok['Atap Utama'], 'total_harga'));
    $totalAksesoris = array_sum(array_column($kelompok['Aksesoris'], 'total_harga'));
    $totalAdditional = array_sum(array_column($kelompok['Additional'], 'total_harga'));
    $totalSistem = array_sum(array_column($kelompok['Sistem Pendukung'], 'total_harga'));
    $grandTotal = $totalAtap + $totalAksesoris + $totalAdditional + $totalSistem;
    
    $totalLuas = safeFloat($bagian1Data['data_perhitungan']['luas_atap'] ?? 0) + 
                 safeFloat($bagian2Data['data_perhitungan']['luas_atap'] ?? 0) + 
                 safeFloat($bagian3Data['data_perhitungan']['luas_atap'] ?? 0);
    
    $totalStarter = safeFloat($bagian1Data['data_perhitungan']['starter'] ?? 0) + 
                    safeFloat($bagian2Data['data_perhitungan']['starter'] ?? 0) + 
                    safeFloat($bagian3Data['data_perhitungan']['starter'] ?? 0);
    
    $totalFlashing = safeFloat($bagian1Data['data_perhitungan']['flashing'] ?? 0) + 
                     safeFloat($bagian2Data['data_perhitungan']['flashing'] ?? 0) + 
                     safeFloat($bagian3Data['data_perhitungan']['flashing'] ?? 0);
    
    $totalNokAtas = safeFloat($bagian2Data['data_perhitungan']['nok_atas'] ?? 0);
    
    $nomorBoq = $data['nomor_boq'] ?? 'BOQ-202606-0001';
    
    // ===== SISTEM PEMASANGAN - DIAMBIL DARI MASING-MASING BAGIAN =====
    $sistemPemasangan1 = $bagian1Data['sistem_pemasangan'] ?? 'expose';
    $sistemPemasangan2 = $bagian2Data['sistem_pemasangan'] ?? 'expose';
    $sistemPemasangan3 = $bagian3Data['sistem_pemasangan'] ?? 'expose';
    
    $waste1 = $data['waste_1'] ?? 5;
    $waste2 = $data['waste_2'] ?? 5;
    $waste3 = $data['waste_3'] ?? 5;
    
    $opsiDinding1 = safeFloat($bagian1Data['opsi']['dinding'] ?? 0);
    $opsiKaca1 = safeFloat($bagian1Data['opsi']['kaca'] ?? 0);
    $opsiDinding2 = safeFloat($bagian2Data['opsi']['dinding'] ?? 0);
    $opsiKaca2 = safeFloat($bagian2Data['opsi']['kaca'] ?? 0);
    $opsiDinding3 = safeFloat($bagian3Data['opsi']['dinding'] ?? 0);
    $opsiKaca3 = safeFloat($bagian3Data['opsi']['kaca'] ?? 0);
    
    $hasAnyItems = false;
    foreach ($kelompok as $group) {
        if (count($group) > 0) {
            $hasAnyItems = true;
            break;
        }
    }
    
    function cleanRp($str) {
        return (int) preg_replace('/[^0-9]/', '', $str);
    }
    
    $totalBagian1 = cleanRp($bagian1Data['total'] ?? 0);
    $totalBagian2 = cleanRp($bagian2Data['total'] ?? 0);
    $totalBagian3 = cleanRp($bagian3Data['total'] ?? 0);
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
        <p>{{ $data['brand'] ?? 'PALMEX' }}</p>
        
        <div class="nomor-boq-wrapper">
            <span class="nomor-boq" id="nomorBoqText">
                {{ $nomorBoq }}
            </span>
            <button class="btn-copy-boq no-print" onclick="copyNomorBoq()">
                Copy
            </button>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== 1. DATA GEOMETRIK ===== -->
    <!-- ============================================================ -->
    <div class="section-title">
        DATA GEOMETRIK
    </div>
    
    <div class="info-list">
        <div class="info-item">
            <span class="info-label">Total Luas Atap</span>
            <span class="info-value">{{ number_format($totalLuas, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($totalStarter, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($totalFlashing, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Nok Atas (Pelana)</span>
            <span class="info-value">{{ number_format($totalNokAtas, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian1Data['data_perhitungan']['sudut'] ?? 0, 2) }}° / {{ number_format($bagian2Data['data_perhitungan']['sudut'] ?? 0, 2) }}° / {{ number_format($bagian3Data['data_perhitungan']['sudut'] ?? 0, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sistem Pemasangan</span>
            <span class="info-value">{{ ucfirst($sistemPemasangan1) }} / {{ ucfirst($sistemPemasangan2) }} / {{ ucfirst($sistemPemasangan3) }}</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ===== 2. KEBUTUHAN MATERIAL KESELURUHAN ===== -->
    <!-- ============================================================ -->
    <div class="section-title">
        KEBUTUHAN MATERIAL KESELURUHAN
    </div>
    
    <div class="table-wrapper">
        @if(!$hasAnyItems)
            <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 9pt;">
                Belum ada data material
            </div>
        @else
            @php
                $groupConfig = [
                    ['key' => 'Atap Utama', 'label' => '📁 ATAP UTAMA', 'cls' => 'group-title-atap'],
                    ['key' => 'Aksesoris', 'label' => '📁 AKSESORIS', 'cls' => 'group-title-aksesoris'],
                    ['key' => 'Additional', 'label' => '📁 ADDITIONAL', 'cls' => 'group-title-additional'],
                    ['key' => 'Sistem Pendukung', 'label' => '📁 SISTEM PENDUKUNG', 'cls' => 'group-title-sistem']
                ];
            @endphp
            
            @foreach($groupConfig as $config)
                @php $items = $kelompok[$config['key']] ?? []; @endphp
                @if(count($items) > 0)
                    <div class="group-title {{ $config['cls'] }}">
                        {{ $config['label'] }}
                    </div>
                    <table>
                        <thead class="table-header">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Nama Produk</th>
                                <th width="15%">Area</th>
                                <th width="10%" class="text-right">Qty</th>
                                <th width="10%" class="text-right">Satuan</th>
                                <th width="15%" class="text-right">Harga</th>
                                <th width="15%" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($items as $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $item['nama_produk'] ?? '-' }}</td>
                                <td>{{ $item['area'] ?? '-' }}</td>
                                <td class="text-right">{{ number_format($item['qty'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                                <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                                <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
        @endif
    </div>

    <!-- ============================================================ -->
    <!-- ===== 3. TOTAL KESELURUHAN ===== -->
    <!-- ============================================================ -->
    <div class="grand-total-box">
        <span class="label">Total Keseluruhan</span>
        <span class="value">{{ formatRp($grandTotal) }}</span>
    </div>

    <!-- ============================================================ -->
    <!-- ===== 4. DETAIL PERBAGIAN ===== -->
    <!-- ============================================================ -->
    
    <div class="page-break-rincian"></div>
    
    <div class="section-title section-title-center" style="margin-top:0;">
        RINCIAN PERHITUNGAN PER BAGIAN
    </div>

    <!-- ============================================================ -->
    <!-- ===== BAGIAN 1: KIRI ===== -->
    <!-- ============================================================ -->
    <div class="section-title section-title-kiri" style="font-size:10pt;margin-top:12px;padding:6px 12px;">
        PERHITUNGAN BAGIAN KIRI - 1 Sisi Kemiringan
    </div>
    
    <div class="info-list" style="margin-bottom:8px;">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format($bagian1Data['data_perhitungan']['luas_atap'] ?? 0, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian1Data['data_perhitungan']['sudut'] ?? 0, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($bagian1Data['data_perhitungan']['starter'] ?? 0, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($bagian1Data['data_perhitungan']['flashing'] ?? 0, 2) }} m</span>
        </div>
        @if($opsiDinding1 > 0 || $opsiKaca1 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding1 > 0)Dinding: {{ number_format($opsiDinding1, 2) }}m @endif
                @if($opsiDinding1 > 0 && $opsiKaca1 > 0) | @endif
                @if($opsiKaca1 > 0)Kaca: {{ number_format($opsiKaca1, 2) }}m @endif
            </span>
        </div>
        @endif
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $waste1 }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sistem Pemasangan</span>
            <span class="info-value">{{ ucfirst($sistemPemasangan1) }}</span>
        </div>
    </div>

    <div style="font-size:8pt;font-weight:600;margin:6px 0 4px 0;padding:4px 10px;background:#e8edf5;border-radius:3px;border-left:3px solid #1e40af;">
        DETAIL KEBUTUHAN MATERIAL
    </div>
    
    @if(count($bagian1Data['hasil']) > 0)
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Produk</th>
                    <th width="15%">Area</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="10%" class="text-right">Satuan</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian1Data['hasil'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td>{{ $item['area'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['qty'] ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                    <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL KIRI: {{ formatRp($totalBagian1) }}</div>
    @else
    <div style="text-align: center; padding: 10px; color: #94a3b8; font-size: 8pt;">
        Belum ada data material untuk bagian kiri
    </div>
    @endif

    <!-- ============================================================ -->
    <!-- ===== BAGIAN 2: TENGAH (PELANA) ===== -->
    <!-- ============================================================ -->
    <div class="page-break-rincian"></div>
    
    <div class="section-title section-title-tengah" style="font-size:10pt;margin-top:12px;padding:6px 12px;">
        PERHITUNGAN BAGIAN TENGAH - Pelana
    </div>
    
    <div class="info-list" style="margin-bottom:8px;">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format($bagian2Data['data_perhitungan']['luas_atap'] ?? 0, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian2Data['data_perhitungan']['sudut'] ?? 0, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($bagian2Data['data_perhitungan']['starter'] ?? 0, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Nok Atas</span>
            <span class="info-value">{{ number_format($bagian2Data['data_perhitungan']['nok_atas'] ?? 0, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($bagian2Data['data_perhitungan']['flashing'] ?? 0, 2) }} m</span>
        </div>
        @if($opsiDinding2 > 0 || $opsiKaca2 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding2 > 0)Dinding: {{ number_format($opsiDinding2, 2) }}m @endif
                @if($opsiDinding2 > 0 && $opsiKaca2 > 0) | @endif
                @if($opsiKaca2 > 0)Kaca: {{ number_format($opsiKaca2, 2) }}m @endif
            </span>
        </div>
        @endif
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $waste2 }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sistem Pemasangan</span>
            <span class="info-value">{{ ucfirst($sistemPemasangan2) }}</span>
        </div>
    </div>

    <div style="font-size:8pt;font-weight:600;margin:6px 0 4px 0;padding:4px 10px;background:#fce7f3;border-radius:3px;border-left:3px solid #9d174d;">
        DETAIL KEBUTUHAN MATERIAL
    </div>
    
    @if(count($bagian2Data['hasil']) > 0)
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Produk</th>
                    <th width="15%">Area</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="10%" class="text-right">Satuan</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian2Data['hasil'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td>{{ $item['area'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['qty'] ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                    <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL PELANA: {{ formatRp($totalBagian2) }}</div>
    @else
    <div style="text-align: center; padding: 10px; color: #94a3b8; font-size: 8pt;">
        Belum ada data material untuk bagian tengah
    </div>
    @endif

    <!-- ============================================================ -->
    <!-- ===== BAGIAN 3: KANAN ===== -->
    <!-- ============================================================ -->
    <div class="page-break-rincian"></div>
    
    <div class="section-title section-title-kanan" style="font-size:10pt;margin-top:12px;padding:6px 12px;">
        PERHITUNGAN BAGIAN KANAN - 1 Sisi Kemiringan
    </div>
    
    <div class="info-list" style="margin-bottom:8px;">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format($bagian3Data['data_perhitungan']['luas_atap'] ?? 0, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian3Data['data_perhitungan']['sudut'] ?? 0, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($bagian3Data['data_perhitungan']['starter'] ?? 0, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($bagian3Data['data_perhitungan']['flashing'] ?? 0, 2) }} m</span>
        </div>
        @if($opsiDinding3 > 0 || $opsiKaca3 > 0)
        <div class="info-item">
            <span class="info-label">Opsi Tambahan</span>
            <span class="info-value">
                @if($opsiDinding3 > 0)Dinding: {{ number_format($opsiDinding3, 2) }}m @endif
                @if($opsiDinding3 > 0 && $opsiKaca3 > 0) | @endif
                @if($opsiKaca3 > 0)Kaca: {{ number_format($opsiKaca3, 2) }}m @endif
            </span>
        </div>
        @endif
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $waste3 }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sistem Pemasangan</span>
            <span class="info-value">{{ ucfirst($sistemPemasangan3) }}</span>
        </div>
    </div>

    <div style="font-size:8pt;font-weight:600;margin:6px 0 4px 0;padding:4px 10px;background:#d1fae5;border-radius:3px;border-left:3px solid #065f46;">
        DETAIL KEBUTUHAN MATERIAL
    </div>
    
    @if(count($bagian3Data['hasil']) > 0)
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Produk</th>
                    <th width="15%">Area</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="10%" class="text-right">Satuan</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bagian3Data['hasil'] as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td>{{ $item['area'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['qty'] ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                    <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL KANAN: {{ formatRp($totalBagian3) }}</div>
    @else
    <div style="text-align: center; padding: 10px; color: #94a3b8; font-size: 8pt;">
        Belum ada data material untuk bagian kanan
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ PALMEX - Pelana + 2 Sisi | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
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