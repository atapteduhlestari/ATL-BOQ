<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOQ - MASTER ROOF Pelana + Dinding</title>
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
        
        .group-title {
            font-size: 8pt;
            font-weight: 500;
            color: #1a1a2e;
            margin: 10px 0 4px 0;
            padding: 3px 10px;
            background: #f1f4f9;
            border-radius: 3px;
            border-left: 3px solid #0f3460;
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
        return 'Rp ' . number_format((int)$angka, 0, ',', '.');
    }
    
    $nomorBoq = $data['nomor_boq'] ?? 'MR-BOQ-202606-0001';
    $model = $data['model'] ?? '';
    
    $modelName = '';
    
    // Hitung grand total dari results
    $grandTotal = 0;
    foreach($data['results'] ?? [] as $item) {
        $total = $item['total_harga'] ?? 0;
        $grandTotal += $total;
    }
    
    $results = $data['results'] ?? [];
    
    // Kelompokkan berdasarkan area
    $groups = [
        'Atap Utama' => ['label' => 'ATAP UTAMA', 'items' => []],
        'Aksesoris' => ['label' => 'AKSESORIS', 'items' => []],
        'Additional' => ['label' => 'ADDITIONAL', 'items' => []],
        'Sistem Pendukung' => ['label' => 'SISTEM PENDUKUNG', 'items' => []]
    ];
    
    $additionalAreas = ['Wall Flashing', 'Cerobong Asap', 'Penangkal Petir', 'Flashing Kaca'];
    $systemAreas = ['Lantai Kerja', 'Underlayer', 'Screw Plywood'];
    $aksesorisAreas = ['Starter', 'Tape Roof Nok & Jurai', 'Nok Tutup', 'Metal Flashing', 'Paku & Screw'];
    
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
        <p>MASTER ROOF {{ $modelName }}</p>
        
        <div class="nomor-boq-wrapper">
            <span class="nomor-boq" id="nomorBoqText">
                {{ $nomorBoq }}
            </span>
            <button class="btn-copy-boq no-print" onclick="copyNomorBoq()">
                Copy
            </button>
        </div>
    </div>

    <!-- DATA GEOMETRIK - LIST DENGAN INDENT -->
    <div class="section-title">
        DATA GEOMETRIK
    </div>
    
    <div class="info-list">
        <div class="info-item">
            <span class="info-label">Luas Permukaan Atap</span>
            <span class="info-value">{{ number_format((float)($data['luas_atap'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Panjang Starter</span>
            <span class="info-value">{{ number_format((float)($data['starter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Panjang Flashing</span>
            <span class="info-value">{{ number_format((float)($data['flashing'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format((float)($data['sudut'] ?? 0), 2) }}°</span>
        </div>
        <div class="info-item">
            <span class="info-label">Panjang Nok dan Jurai</span>
            <span class="info-value">{{ number_format((float)($data['nok_jurai'] ?? 0), 2) }} m</span>
        </div>
    </div>

    <!-- PILIHAN MATERIAL - LIST DENGAN INDENT -->
    <div class="section-title">
        PILIHAN MATERIAL
    </div>

    <div class="info-list">
        <div class="info-item">
            <span class="info-label">Tipe Atap</span>
            <span class="info-value">{{ $data['jenis_atap'] ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Struktur Rangka</span>
            <span class="info-value">{{ $data['rangka'] ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Lantai Kerja</span>
            <span class="info-value">{{ $data['lantai_kerja'] ?? '-' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste'] ?? 0 }}%</span>
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
        <span class="label">GRAND TOTAL</span>
        <span class="value">{{ formatRp($grandTotal) }}</span>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ MASTER ROOF - Pelana + Dinding | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</div>

<div class="action-buttons no-print">
    <button onclick="window.print()" class="btn-action btn-print">
        🖨️ Cetak
    </button>
    <button onclick="window.location.href='{{ route('boq.masterroof.pelana-dinding.export-pdf') }}'" class="btn-action btn-print" style="background: #2563eb;">
        ⬇️ Download PDF
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