<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOQ - MAHAFLAT Pelana 2 Kemiringan</title>
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
                background-color: #1a3c6e !important;
                color: white !important;
            }
            
            .info-grid {
                background-color: #f8fafc !important;
            }
            
            .grand-total-box {
                background: #1a3c6e !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .section-title {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
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
            border-bottom: 2px solid #1a3c6e;
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
            color: #1a3c6e;
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
            color: #1a3c6e;
            background: #f1f4f9;
            padding: 4px 16px;
            border-radius: 4px;
            display: inline-block;
            letter-spacing: 0.3px;
            border: 1px solid #e2e8f0;
        }

        .btn-copy-boq {
            background: #1a3c6e;
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
            background: #2d5a8c;
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
            grid-template-columns: repeat(2, 1fr);
            gap: 6px 12px;
            margin-bottom: 14px;
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid #f1f4f9;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-size: 6.5pt;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            font-size: 8pt;
            font-weight: 600;
            color: #1e293b;
        }
        
        .section-title {
            font-size: 9pt;
            font-weight: 600;
            color: #1a3c6e;
            margin: 12px 0 6px 0;
            padding: 4px 10px;
            background: #f8fafc;
            border-radius: 3px;
            border-left: 3px solid #1a3c6e;
        }
        
        .group-title {
            font-size: 8pt;
            font-weight: 500;
            color: #1a3c6e;
            margin: 10px 0 4px 0;
            padding: 3px 10px;
            background: #f1f4f9;
            border-radius: 3px;
            border-left: 3px solid #64748b;
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
            background-color: #1a3c6e;
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
            background: #1a3c6e;
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
            background: #1a3c6e;
            color: white;
        }
        
        .btn-print:hover {
            background: #2d5a8c;
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
            background: #1a3c6e;
            color: white;
        }
        
        .btn-copy-boq-global:hover {
            background: #2d5a8c;
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
            background: #1a3c6e;
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
        
        /* Styling khusus untuk Pelana 2 Kemiringan */
        .sub-section {
            background: #f1f5f9;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 4px;
            border-left: 3px solid #1a3c6e;
        }
        
        .sub-section .sub-title {
            font-size: 7pt;
            font-weight: 600;
            color: #1a3c6e;
        }
        
        .sub-section .sub-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2px 12px;
            margin-top: 4px;
        }
        
        .sub-section .sub-item {
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
        }
        
        .sub-section .sub-item .label {
            color: #94a3b8;
        }
        
        .sub-section .sub-item .value {
            font-weight: 500;
            color: #1e293b;
        }
        
        .badge-kiri {
            display: inline-block;
            padding: 1px 10px;
            border-radius: 10px;
            font-size: 6pt;
            font-weight: 600;
            background: #dbeafe;
            color: #1a3c6e;
        }
        
        .badge-tengah {
            display: inline-block;
            padding: 1px 10px;
            border-radius: 10px;
            font-size: 6pt;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-kanan {
            display: inline-block;
            padding: 1px 10px;
            border-radius: 10px;
            font-size: 6pt;
            font-weight: 600;
            background: #d1fae5;
            color: #065f46;
        }
        
        .grid-3-col {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }
        
        @media print {
            .grid-3-col {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>

@php
    function formatRp($angka) {
        return 'Rp ' . number_format((int)$angka, 0, ',', '.');
    }
    
    $nomorBoq = $data['nomor_boq'] ?? 'BOQ-202606-0001';
    $model = $data['model'] ?? 'pelana-2-kemiringan';
    
    $modelNames = [
        'pelana' => 'Pelana',
        'limasan' => 'Limasan',
        'piramid' => 'Piramid',
        'satu-kemiringan' => 'Satu Kemiringan',
        'kerucut' => 'Kerucut',
        'dome' => 'Dome',
        'gergaji' => 'Gergaji / Sawtooth',
        'limas-pelana' => 'Limas + Pelana',
        'limasan-limasan' => 'Limasan + Limasan',
        'limasan-trapesium' => 'Limasan + Trapesium',
        'limasan-x' => 'Limasan X',
        'pelana-2-kemiringan' => 'Pelana 2 Kemiringan'
    ];
    
    $modelName = $modelNames[$model] ?? 'Pelana 2 Kemiringan';
    
    // Hitung grand total dari results
    $grandTotal = 0;
    foreach($data['results'] ?? [] as $item) {
        $total = $item['total_harga'] ?? 0;
        $grandTotal += $total;
    }
    
    $results = $data['results'] ?? [];
    
    // Kelompokkan berdasarkan area
    $groups = [
        'Atap Utama' => ['label' => 'KELOMPOK ATAP UTAMA', 'items' => []],
        'Aksesoris' => ['label' => 'AKSESORIS ATAP', 'items' => []],
        'Additional' => ['label' => 'ADDITIONAL', 'items' => []],
        'Sistem Pendukung' => ['label' => 'SISTEM PENDUKUNG', 'items' => []]
    ];
    
    $additionalAreas = ['Wall Flashing', 'Cerobong Asap', 'Penangkal Petir'];
    $systemAreas = ['Lantai Kerja', 'Underlayer', 'Screw Plywood'];
    $aksesorisAreas = ['Starter', 'Mahaflat Nok & Jurai', 'Nok Tutup', 'Metal Flashing', 'Paku & Screw'];
    
    foreach ($results as $item) {
        $area = $item['area'] ?? '';
        if ($area === 'Atap Utama') {
            $groups['Atap Utama']['items'][] = $item;
        } elseif (in_array($area, $additionalAreas)) {
            $groups['Additional']['items'][] = $item;
        } elseif (in_array($area, $systemAreas)) {
            $groups['Sistem Pendukung']['items'][] = $item;
        } elseif (in_array($area, $aksesorisAreas)) {
            $groups['Aksesoris']['items'][] = $item;
        } else {
            $groups['Aksesoris']['items'][] = $item;
        }
    }
    
    // Ambil data parameter
    $luasAtap = $data['luas_atap'] ?? 0;
    $starter = $data['starter'] ?? 0;
    $nokJurai = $data['nok_jurai'] ?? 0;
    $flashing = $data['flashing'] ?? 0;
    $sudut = $data['sudut'] ?? 30;
    $waste = $data['waste'] ?? 5;
    $rangka = $data['rangka'] ?? 'Baja Ringan';
    $lantaiKerja = $data['lantai_kerja'] ?? 'Plywood 9 mm';
    
    // Data per bagian
    $luasAtap1 = $data['luas_atap_1'] ?? 0;
    $starter1 = $data['starter_1'] ?? 0;
    $flashing1 = $data['flashing_1'] ?? 0;
    $nok1 = $data['nok_1'] ?? 0;
    $sudut1 = $data['sudut_1'] ?? 0;
    
    $luasAtap2 = $data['luas_atap_2'] ?? 0;
    $starter2 = $data['starter_2'] ?? 0;
    $flashing2 = $data['flashing_2'] ?? 0;
    $nok2 = $data['nok_2'] ?? 0;
    $sudut2 = $data['sudut_2'] ?? 0;
    
    $luasAtap3 = $data['luas_atap_3'] ?? 0;
    $starter3 = $data['starter_3'] ?? 0;
    $flashing3 = $data['flashing_3'] ?? 0;
    $nok3 = $data['nok_3'] ?? 0;
    $sudut3 = $data['sudut_3'] ?? 0;
    
    $totalNokJurai = $data['total_nok_jurai'] ?? 0;
    
    // Opsi tambahan
    $opsiDinding = $data['opsi_dinding'] ?? 0;
    $opsiCerobong = $data['opsi_cerobong'] ?? 0;
    $opsiPenangkal = $data['opsi_penangkal'] ?? 0;
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
        <p>MAHAFLAT {{ $modelName }}</p>
        
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
    <div class="section-title">
        DATA PERHITUNGAN LUAS ATAP
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Total Luas Atap</span>
            <span class="info-value">{{ number_format((float)$luasAtap, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $waste }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Starter</span>
            <span class="info-value">{{ number_format((float)$starter, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Nok & Jurai</span>
            <span class="info-value">{{ number_format((float)$nokJurai, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Flashing</span>
            <span class="info-value">{{ number_format((float)$flashing, 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Rangka</span>
            <span class="info-value">{{ $rangka }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Lantai Kerja</span>
            <span class="info-value">{{ $lantaiKerja }}</span>
        </div>
        @if($opsiDinding > 0)
        <div class="info-item">
            <span class="info-label">Dinding</span>
            <span class="info-value">{{ number_format((float)$opsiDinding, 2) }} m</span>
        </div>
        @endif
        @if($opsiCerobong > 0)
        <div class="info-item">
            <span class="info-label">Cerobong Asap</span>
            <span class="info-value">{{ $opsiCerobong }} unit</span>
        </div>
        @endif
        @if($opsiPenangkal > 0)
        <div class="info-item">
            <span class="info-label">Penangkal Petir</span>
            <span class="info-value">{{ number_format((float)$opsiPenangkal, 2) }} m</span>
        </div>
        @endif
    </div>

    <!-- DETAIL PER BAGIAN -->
    <div class="section-title">
        DETAIL PER BAGIAN (3 BAGIAN)
    </div>
    
    <div class="grid-3-col">
        <!-- Bagian 1: Kiri -->
        <div class="sub-section">
            <div class="sub-title">
                <span class="badge-kiri">KIRI</span> Bagian 1 - Kiri
            </div>
            <div class="sub-grid">
                <div class="sub-item">
                    <span class="label">Luas</span>
                    <span class="value">{{ number_format((float)$luasAtap1, 2) }} m²</span>
                </div>
                <div class="sub-item">
                    <span class="label">Sudut</span>
                    <span class="value">{{ number_format((float)$sudut1, 2) }}°</span>
                </div>
                <div class="sub-item">
                    <span class="label">Starter</span>
                    <span class="value">{{ number_format((float)$starter1, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Flashing</span>
                    <span class="value">{{ number_format((float)$flashing1, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Nok & Jurai</span>
                    <span class="value">{{ number_format((float)$nok1, 2) }} m</span>
                </div>
            </div>
        </div>
        
        <!-- Bagian 2: Tengah (Pelana) -->
        <div class="sub-section">
            <div class="sub-title">
                <span class="badge-tengah">TENGAH</span> Bagian 2 - Pelana
            </div>
            <div class="sub-grid">
                <div class="sub-item">
                    <span class="label">Luas</span>
                    <span class="value">{{ number_format((float)$luasAtap2, 2) }} m²</span>
                </div>
                <div class="sub-item">
                    <span class="label">Sudut</span>
                    <span class="value">{{ number_format((float)$sudut2, 2) }}°</span>
                </div>
                <div class="sub-item">
                    <span class="label">Starter</span>
                    <span class="value">{{ number_format((float)$starter2, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Flashing</span>
                    <span class="value">{{ number_format((float)$flashing2, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Nok & Jurai</span>
                    <span class="value">{{ number_format((float)$nok2, 2) }} m</span>
                </div>
            </div>
        </div>
        
        <!-- Bagian 3: Kanan -->
        <div class="sub-section">
            <div class="sub-title">
                <span class="badge-kanan">KANAN</span> Bagian 3 - Kanan
            </div>
            <div class="sub-grid">
                <div class="sub-item">
                    <span class="label">Luas</span>
                    <span class="value">{{ number_format((float)$luasAtap3, 2) }} m²</span>
                </div>
                <div class="sub-item">
                    <span class="label">Sudut</span>
                    <span class="value">{{ number_format((float)$sudut3, 2) }}°</span>
                </div>
                <div class="sub-item">
                    <span class="label">Starter</span>
                    <span class="value">{{ number_format((float)$starter3, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Flashing</span>
                    <span class="value">{{ number_format((float)$flashing3, 2) }} m</span>
                </div>
                <div class="sub-item">
                    <span class="label">Nok & Jurai</span>
                    <span class="value">{{ number_format((float)$nok3, 2) }} m</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RINCIAN MATERIAL -->
    <div class="section-title">
        RINCIAN KEBUTUHAN MATERIAL
    </div>
    
    <div class="table-wrapper">
        @php
            $groupKeys = ['Atap Utama', 'Aksesoris', 'Additional', 'Sistem Pendukung'];
        @endphp
        
        @foreach($groupKeys as $key)
            @php $group = $groups[$key]; @endphp
            @if(count($group['items']) > 0)
                <div class="group-title">
                    {{ $group['label'] }}
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
                        @foreach($group['items'] as $item)
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
    </div>

    <!-- GRAND TOTAL -->
    <div class="grand-total-box">
        <span class="label">🏆 GRAND TOTAL</span>
        <span class="value">{{ formatRp($grandTotal) }}</span>
    </div>

    <!-- KETERANGAN TAMBAHAN -->
    <div style="margin-top: 12px; padding: 8px 12px; background: #f0f7ff; border-radius: 4px; border-left: 3px solid #1a3c6e; font-size: 6.5pt; color: #475569;">
        <strong style="color: #1a3c6e;">Keterangan:</strong>
        <ul style="margin-top: 4px; padding-left: 16px; list-style: none;">
            <li>• Perhitungan ini berdasarkan input luas atap dan parameter yang dimasukkan.</li>
            <li>• Harga satuan berdasarkan price list MAHAFLAT yang berlaku.</li>
            <li>• Waste material sudah termasuk dalam perhitungan ({{ $waste }}%).</li>
            <li>• Model Pelana 2 Kemiringan terdiri dari 3 bagian: Kiri, Tengah (Pelana), dan Kanan.</li>
            <li>• Nok Tutup = 4 unit (setiap ujung atap).</li>
            <li>• BoQ ini bersifat estimasi dan dapat berubah sesuai kondisi lapangan.</li>
        </ul>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ MAHAFLAT | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
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