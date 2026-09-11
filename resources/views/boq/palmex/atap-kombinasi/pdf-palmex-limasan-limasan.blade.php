<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOQ - Limasan + Limasan</title>
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
            
            /* Page break sebelum rincian perbagian */
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
    
    $badgeMap = [
        'Atap Utama' => 'badge-blue',
        'Underlayer' => 'badge-green',
        'Starter' => 'badge-orange',
        'Nok Atas' => 'badge-purple',
        'Jurai' => 'badge-purple',
        'Talang Jurai' => 'badge-purple',
        'Flashing' => 'badge-gray',
        'Metal Flashing' => 'badge-gray',
        'Shingle Stick' => 'badge-gray',
        'Paku & Screw' => 'badge-gray',
        'Wall Flashing' => 'badge-red',
        'Flashing Kaca' => 'badge-red',
    ];
    
    // ===== DECODE HASIL DARI JSON STRING =====
    $hasilBagian1 = [];
    if (isset($data['bagian1']['hasil'])) {
        if (is_string($data['bagian1']['hasil'])) {
            $hasilBagian1 = json_decode($data['bagian1']['hasil'], true);
            if (!is_array($hasilBagian1)) {
                $hasilBagian1 = [];
            }
        } elseif (is_array($data['bagian1']['hasil'])) {
            $hasilBagian1 = $data['bagian1']['hasil'];
        }
    }
    
    $hasilBagian2 = [];
    if (isset($data['bagian2']['hasil'])) {
        if (is_string($data['bagian2']['hasil'])) {
            $hasilBagian2 = json_decode($data['bagian2']['hasil'], true);
            if (!is_array($hasilBagian2)) {
                $hasilBagian2 = [];
            }
        } elseif (is_array($data['bagian2']['hasil'])) {
            $hasilBagian2 = $data['bagian2']['hasil'];
        }
    }
    
    // Gabungkan semua hasil
    $allResults = array_merge($hasilBagian1, $hasilBagian2);
    
    // ===== KELOMPOKKAN HASIL =====
    $kelompok = [
        'Atap Utama' => [],
        'Aksesoris' => [],
        'Additional' => [],
        'Sistem Pendukung' => []
    ];
    
    $aksesorisAreas = ['Starter', 'Nok Atas', 'Jurai', 'Flashing', 'Metal Flashing', 'Shingle Stick', 'Talang Jurai', 'Screw Plywood'];
    $additionalAreas = ['Wall Flashing', 'Flashing Kaca'];
    $sistemPendukungAreas = ['Underlayer', 'Lantai Kerja', 'Paku & Screw'];
    
    // Proses hasil dari total
    foreach($allResults as $item) {
        $area = $item['area'] ?? '';
        if ($area === 'Atap Utama') {
            $kelompok['Atap Utama'][] = $item;
        } elseif (in_array($area, $additionalAreas) && ($item['qty'] ?? 0) > 0) {
            $kelompok['Additional'][] = $item;
        } elseif (in_array($area, $sistemPendukungAreas)) {
            $kelompok['Sistem Pendukung'][] = $item;
        } elseif (in_array($area, $aksesorisAreas)) {
            $kelompok['Aksesoris'][] = $item;
        } else {
            $kelompok['Aksesoris'][] = $item;
        }
    }
    
    // Gabungkan item yang sama dalam kelompok
    function mergeItems($items) {
        $merged = [];
        foreach ($items as $item) {
            $key = $item['nama_produk'] ?? $item['nama'] ?? '';
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
            $merged[$key]['qty'] += (int)($item['qty'] ?? 0);
            $merged[$key]['total_harga'] += (int)($item['total_harga'] ?? 0);
        }
        return array_values($merged);
    }
    
    $kelompok['Atap Utama'] = mergeItems($kelompok['Atap Utama']);
    $kelompok['Aksesoris'] = mergeItems($kelompok['Aksesoris']);
    $kelompok['Additional'] = mergeItems($kelompok['Additional']);
    $kelompok['Sistem Pendukung'] = mergeItems($kelompok['Sistem Pendukung']);
    
    // Hitung total per kelompok
    $totalAtap = array_sum(array_column($kelompok['Atap Utama'], 'total_harga'));
    $totalAksesoris = array_sum(array_column($kelompok['Aksesoris'], 'total_harga'));
    $totalAdditional = array_sum(array_column($kelompok['Additional'], 'total_harga'));
    $totalSistem = array_sum(array_column($kelompok['Sistem Pendukung'], 'total_harga'));
    $grandTotal = $totalAtap + $totalAksesoris + $totalAdditional + $totalSistem;
    
    // Data per bagian
    $bagian1Luas = $data['bagian1']['data_perhitungan']['luas_atap'] ?? 0;
    $bagian1Sudut = $data['bagian1']['data_perhitungan']['sudut'] ?? 0;
    $bagian1Starter = $data['bagian1']['data_perhitungan']['starter'] ?? 0;
    $bagian1Jurai = $data['bagian1']['data_perhitungan']['jurai'] ?? 0;
    $bagian1NokAtas = $data['bagian1']['data_perhitungan']['nok_atas'] ?? 0;
    $bagian1Flashing = $data['bagian1']['data_perhitungan']['flashing'] ?? 0;
    $bagian1Total = $data['bagian1']['total'] ?? 0;
    
    $bagian2Luas = $data['bagian2']['data_perhitungan']['luas_atap'] ?? 0;
    $bagian2Sudut = $data['bagian2']['data_perhitungan']['sudut'] ?? 0;
    $bagian2Starter = $data['bagian2']['data_perhitungan']['starter'] ?? 0;
    $bagian2Jurai = $data['bagian2']['data_perhitungan']['jurai'] ?? 0;
    $bagian2NokAtas = $data['bagian2']['data_perhitungan']['nok_atas'] ?? 0;
    $bagian2Flashing = $data['bagian2']['data_perhitungan']['flashing'] ?? 0;
    $bagian2Total = $data['bagian2']['total'] ?? 0;
    
    $totalLuas = $bagian1Luas + $bagian2Luas;
    
    $nomorBoq = $data['nomor_boq'] ?? 'BOQ-202606-0001';
    
    // Opsi tambahan
    $opsiDinding1 = $data['bagian1']['opsi']['dinding'] ?? 0;
    $opsiKaca1 = $data['bagian1']['opsi']['kaca'] ?? 0;
    $opsiDinding2 = $data['bagian2']['opsi']['dinding'] ?? 0;
    $opsiKaca2 = $data['bagian2']['opsi']['kaca'] ?? 0;
    
    $waste1 = $data['waste_1'] ?? 5;
    $waste2 = $data['waste_2'] ?? 5;
    
    // Sistem pemasangan
    $sistemPemasangan1 = $data['bagian1']['sistem_pemasangan'] ?? 'expose';
    $sistemPemasangan2 = $data['bagian2']['sistem_pemasangan'] ?? 'expose';
    
    // Fungsi untuk membersihkan format angka dari string Rp
    function cleanRp($str) {
        return (int) preg_replace('/[^0-9]/', '', $str);
    }
    
    $bagian1TotalClean = cleanRp($bagian1Total);
    $bagian2TotalClean = cleanRp($bagian2Total);
    
    // Cek apakah ada item yang tersisa untuk ditampilkan
    $hasAnyItems = false;
    foreach ($kelompok as $group) {
        if (count($group) > 0) {
            $hasAnyItems = true;
            break;
        }
    }
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
        <span class="info-value">{{ number_format($bagian1Starter + $bagian2Starter, 2) }} m</span>
    </div>
    <div class="info-item">
        <span class="info-label">Total Panjang Flashing</span>
        <span class="info-value">{{ number_format($bagian1Flashing + $bagian2Flashing, 2) }} m</span>
    </div>
    <div class="info-item">
        <span class="info-label">Total Panjang Nok Atas</span>
        <span class="info-value">{{ number_format($bagian1NokAtas + $bagian2NokAtas, 2) }} m</span>
    </div>
    <div class="info-item">
        <span class="info-label">Total Panjang Jurai</span>
        <span class="info-value">{{ number_format($bagian1Jurai + $bagian2Jurai, 2) }} m</span>
    </div>
    <div class="info-item">
        <span class="info-label">Sudut Kemiringan</span>
        <span class="info-value">{{ number_format($bagian1Sudut, 2) }}° / {{ number_format($bagian2Sudut, 2) }}°</span>
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
                                <td class="text-right">{{ number_format($item['qty'] ?? 0, 0, ',', '.') }}</td>
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
    <!-- ===== 4. DETAIL PERBAGIAN (PAGE BREAK) ===== -->
    <!-- ============================================================ -->
    
    <div class="page-break-rincian"></div>
    
    <div class="section-title section-title-center" style="margin-top:0;">
        RINCIAN PERHITUNGAN PER BAGIAN
    </div>

    <!-- ============================================================ -->
    <!-- ===== BAGIAN 1: LIMASAN A ===== -->
    <!-- ============================================================ -->
    <div class="section-title section-title-kiri" style="font-size:10pt;margin-top:12px;padding:6px 12px;">
        PERHITUNGAN BAGIAN 1 - LIMASAN A
    </div>
    
    <div class="info-list" style="margin-bottom:8px;">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format($bagian1Luas, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian1Sudut, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($bagian1Starter, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Jurai</span>
            <span class="info-value">{{ number_format($bagian1Jurai, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Nok Atas</span>
            <span class="info-value">{{ number_format($bagian1NokAtas, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($bagian1Flashing, 2) }} m</span>
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
    
    @if(count($hasilBagian1) > 0)
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
                @foreach($hasilBagian1 as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td>{{ $item['area'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['qty'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                    <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL LIMASAN A: {{ formatRp($bagian1TotalClean) }}</div>
    @else
    <div style="text-align: center; padding: 10px; color: #94a3b8; font-size: 8pt;">
        Belum ada data material untuk Limasan A
    </div>
    @endif

    <!-- ============================================================ -->
    <!-- ===== BAGIAN 2: LIMASAN B ===== -->
    <!-- ============================================================ -->
    <div class="page-break-rincian"></div>
    
    <div class="section-title section-title-tengah" style="font-size:10pt;margin-top:12px;padding:6px 12px;">
        PERHITUNGAN BAGIAN 2 - LIMASAN B
    </div>
    
    <div class="info-list" style="margin-bottom:8px;">
        <div class="info-item">
            <span class="info-label">Luas Atap</span>
            <span class="info-value">{{ number_format($bagian2Luas, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format($bagian2Sudut, 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Starter</span>
            <span class="info-value">{{ number_format($bagian2Starter, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Jurai</span>
            <span class="info-value">{{ number_format($bagian2Jurai, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Nok Atas</span>
            <span class="info-value">{{ number_format($bagian2NokAtas, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Panjang Flashing</span>
            <span class="info-value">{{ number_format($bagian2Flashing, 2) }} m</span>
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
    
    @if(count($hasilBagian2) > 0)
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
                @foreach($hasilBagian2 as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td>{{ $item['area'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['qty'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-right">{{ isset($item['harga_satuan']) ? formatRp($item['harga_satuan']) : 'Rp 0' }}</td>
                    <td class="text-right">{{ isset($item['total_harga']) ? formatRp($item['total_harga']) : 'Rp 0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="subtotal-box">SUB TOTAL LIMASAN B: {{ formatRp($bagian2TotalClean) }}</div>
    @else
    <div style="text-align: center; padding: 10px; color: #94a3b8; font-size: 8pt;">
        Belum ada data material untuk Limasan B
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ PALMEX - Limasan + Limasan | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
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