<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['judul'] ?? 'BOQ - Jendela Mati 1 Kaca' }}</title>
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
            
            .total-qty-cell {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .group-header {
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
            grid-template-columns: repeat(4, 1fr);
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
        
        .group-header {
            font-size: 8pt;
            font-weight: 600;
            padding: 6px 10px;
            margin: 6px 0 4px 0;
            border-radius: 3px;
            background: #e8edf5;
            color: #1a3a5c;
            border-left: 3px solid #2b6cb0;
        }
        
        .group-header-reinforcement {
            background: #e8f5ed;
            color: #1a5c3a;
            border-left-color: #38a169;
        }
        
        .group-header-kaca {
            background: #f5ede8;
            color: #5c3a1a;
            border-left-color: #e67e22;
        }
        
        .group-header-screw {
            background: #f5e8ed;
            color: #5c1a3a;
            border-left-color: #e53e3e;
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
        
        .btn-back {
            background: #64748b;
            color: white;
        }
        
        .btn-back:hover {
            background: #475569;
            transform: translateY(-1px);
        }

        @media print {
            .action-buttons {
                display: none !important;
            }
        }
        
        .total-qty-cell {
            font-weight: 700;
            color: #2b6cb0;
        }

        .kode-produk {
            font-size: 6.5pt;
            color: #64748b;
            font-weight: 400;
            font-family: 'Courier New', monospace;
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
            .toast-message {
                display: none !important;
            }
        }
    </style>
</head>
<body>

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
        <p>{{ $data['judul'] ?? 'BOQ - Jendela Mati 1 Kaca' }}</p>
        
        <div class="nomor-boq-wrapper">
            <span class="nomor-boq" id="nomorBoqText">
                {{ $data['nomor_boq'] ?? 'BOQ-' . date('Ymd') . '-0001' }}
            </span>
            <button class="btn-copy-boq no-print" onclick="copyNomorBoq()">
                📋 Copy
            </button>
        </div>
    </div>

    <!-- DATA PERHITUNGAN -->
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Ukuran</span>
            <span class="info-value">{{ number_format($data['panjang'] ?? 0, 0) }} x {{ number_format($data['lebar'] ?? 0, 0) }} cm</span>
        </div>
        <div class="info-item">
            <span class="info-label">Jumlah Unit</span>
            <span class="info-value">{{ number_format($data['jumlah'] ?? 0, 0) }} unit</span>
        </div>
        <div class="info-item">
            <span class="info-label">Luas Kaca Total</span>
            <span class="info-value">{{ number_format($data['luas_kaca_total'] ?? 0, 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Warna / Type Kaca</span>
            <span class="info-value">{{ $data['warna'] ?? 'Clear' }} / {{ $data['type_kaca'] ?? 'Clear' }}</span>
        </div>
    </div>

    <!-- RINCIAN MATERIAL -->
    <div class="section-title">RINCIAN KEBUTUHAN MATERIAL</div>

    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="30%">Komponen</th>
                    <th width="15%">Kode Produk</th>
                    <th width="12%" class="text-center">Qty</th>
                    <th width="13%">Satuan</th>
                    <th width="25%" class="text-center">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grouped = $data['grouped'] ?? [];
                    $areaLabels = $data['areaLabels'] ?? [];
                    $areaOrder = ['profile', 'reinforcement', 'kaca', 'screw-reinforcement','setting-block', 'lem'];
                    $areaClasses = [
                        'profile' => 'group-header',
                        'reinforcement' => 'group-header-reinforcement',
                        'kaca' => 'group-header-kaca',
                        'screw' => 'group-header-screw',
                    ];
                @endphp

                @foreach($areaOrder as $areaKey)
                    @if(isset($grouped[$areaKey]))
                        @php 
                            $items = $grouped[$areaKey];
                            $totalFrameQty = 0;
                            $totalGlazeBeadQty = 0;
                            $frameItems = [];
                            $glazeBeadItems = [];
                            $otherItems = [];
                            
                            foreach ($items as $item) {
                                $namaLower = strtolower($item->nama_produk);
                                if (str_contains($namaLower, 'frame')) {
                                    $frameItems[] = $item;
                                    $totalFrameQty += $item->qty ?? 0;
                                } elseif (str_contains($namaLower, 'glaze bead')) {
                                    $glazeBeadItems[] = $item;
                                    $totalGlazeBeadQty += $item->qty ?? 0;
                                } else {
                                    $otherItems[] = $item;
                                }
                            }
                        @endphp
                        
                        <tr>
                            <td colspan="6" class="{{ $areaClasses[$areaKey] ?? 'group-header' }}">
                                📁 {{ $areaLabels[$areaKey] ?? strtoupper($areaKey) }}
                            </td>
                        </tr>
                        
                        @php $no = 1; @endphp
                        
                        <!-- FRAME Items -->
                        @if(count($frameItems) > 0)
                            @php $frameCount = count($frameItems); @endphp
                            @foreach($frameItems as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                    <td class="text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                    <td>{{ $item->unit->unit_name ?? 'unit' }}</td>
                                    @if($index === 0)
                                        <td class="text-center total-qty-cell" rowspan="{{ $frameCount }}">
                                            {{ number_format($totalFrameQty, 2) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        
                        <!-- GLAZE BEAD Items -->
                        @if(count($glazeBeadItems) > 0)
                            @php $glazeCount = count($glazeBeadItems); @endphp
                            @foreach($glazeBeadItems as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                    <td class="text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                    <td>{{ $item->unit->unit_name ?? 'unit' }}</td>
                                    @if($index === 0)
                                        <td class="text-center total-qty-cell" rowspan="{{ $glazeCount }}">
                                            {{ number_format($totalGlazeBeadQty, 2) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        
                        <!-- Other Items -->
                        @foreach($otherItems as $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                <td class="text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                <td>{{ $item->unit->unit_name ?? 'unit' }}</td>
                                <td class="text-center">-</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- GRAND TOTAL -->
    <div class="grand-total-box">
        <span class="label">GRAND TOTAL</span>
        <span class="value">-</span>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</div>

<div class="action-buttons no-print">
    <button onclick="window.print()" class="btn-action btn-print">
        🖨️ Cetak
    </button>
    <button onclick="copyNomorBoq()" class="btn-action btn-print" style="background: #2d6a4f;">
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
    } catch (err) {
        showToast('Gagal menyalin: ' + err);
    }
    document.body.removeChild(textarea);
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