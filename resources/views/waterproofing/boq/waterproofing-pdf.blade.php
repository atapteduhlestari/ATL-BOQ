<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BOQ - Waterproofing</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f1f4f9; padding: 20px; }
        
        @media screen {
            body { display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
            .report-paper { width: 210mm; min-height: 297mm; background: white; box-shadow: 0 2px 20px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; margin: 0 auto 20px auto; padding: 12mm 10mm; }
        }
        
        @media print {
            @page { size: A4; margin: 10mm 12mm; }
            body { background: white; padding: 0; margin: 0; }
            .report-paper { width: 100%; min-height: auto; box-shadow: none; border: none; margin: 0; padding: 0; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
            .no-print { display: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .table-header th { background-color: #1a1a2e !important; color: white !important; }
            .grand-total-box { background: #1a1a2e !important; }
        }
        
        .header-container { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #1a1a2e; padding-bottom: 8px; margin-bottom: 14px; }
        .logo-container { width: 80px; flex-shrink: 0; }
        .logo-container img { height: 45px; width: auto; display: block; }
        .company-address { flex: 1; text-align: center; font-weight: 500; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1.5; padding: 0 10px; color: #334155; }
        
        .title-section { text-align: center; margin: 8px 0 12px 0; }
        .title-section h1 { font-size: 13pt; font-weight: 700; color: #1a1a2e; margin-bottom: 2px; letter-spacing: 0.5px; }
        .title-section p { font-size: 8pt; color: #64748b; }
        
        .nomor-boq-wrapper { display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 6px; }
        .nomor-boq { font-size: 10pt; font-weight: 600; color: #1a1a2e; background: #f1f4f9; padding: 4px 16px; border-radius: 4px; border: 1px solid #e2e8f0; }
        .btn-copy-boq { background: #1a1a2e; color: white; border: none; padding: 4px 14px; border-radius: 4px; cursor: pointer; font-size: 8pt; }
        @media print { .btn-copy-boq { display: none !important; } }
        
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px 12px; margin-bottom: 14px; background: #f8fafc; padding: 10px 14px; border-radius: 4px; border: 1px solid #e2e8f0; }
        .info-item { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #f1f4f9; }
        .info-item:last-child { border-bottom: none; }
        .info-label { font-size: 6.5pt; font-weight: 600; color: #94a3b8; text-transform: uppercase; }
        .info-value { font-size: 8pt; font-weight: 600; color: #1e293b; }
        
        .section-title { font-size: 10pt; font-weight: 700; color: #1a1a2e; margin: 14px 0 8px 0; padding-bottom: 4px; border-bottom: 2px solid #e2e8f0; }
        
        table { width: 100%; border-collapse: collapse; font-size: 7pt; margin-bottom: 8px; }
        th, td { padding: 4px 6px; text-align: left; border-bottom: 1px solid #f1f4f9; }
        .table-header th { background-color: #1a1a2e; color: white; font-weight: 500; text-transform: uppercase; font-size: 6pt; padding: 5px 6px; border: none; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .badge { display: inline-block; padding: 1px 8px; border-radius: 3px; font-size: 6pt; font-weight: 500; }
        .badge-blue { background: #e8edf5; color: #1a3a5c; }
        .badge-gray { background: #f1f4f9; color: #4a5568; }
        
        .grand-total-box { background: #1a1a2e; color: white; border-radius: 4px; padding: 14px 20px; margin: 14px 0 10px 0; display: flex; justify-content: space-between; align-items: center; }
        .grand-total-box .label { font-size: 10pt; font-weight: 600; }
        .grand-total-box .value { font-size: 14pt; font-weight: 700; letter-spacing: 0.5px; }
        
        .footer { text-align: center; font-size: 6.5pt; color: #94a3b8; margin-top: 14px; padding-top: 8px; border-top: 1px solid #e2e8f0; }
        .action-buttons { text-align: center; margin-top: 20px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .btn-action { padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; font-size: 9pt; font-family: 'Poppins', sans-serif; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-print { background: #1a1a2e; color: white; }
        .btn-print:hover { background: #2d2d44; }
        .btn-back { background: #64748b; color: white; }
        .btn-back:hover { background: #475569; }
        
        .toast-message { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: #1a1a2e; color: white; padding: 10px 24px; border-radius: 4px; font-size: 9pt; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,0.2); opacity: 0; transition: opacity 0.3s ease; z-index: 9999; font-family: 'Poppins', sans-serif; }
        .toast-message.show { opacity: 1; }
        @media print { .action-buttons, .toast-message { display: none !important; } }
    </style>
</head>
<body>

@php
    function formatRp($angka) {
        return 'Rp ' . number_format((int)$angka, 0, ',', '.');
    }
    
    $nomorBoq = $data['nomor_boq'] ?? 'BOQ-202606-0001';
    $results = $data['hasil'] ?? [];
    $grandTotal = $data['grand_total'] ?? 0;
    
    if (is_string($grandTotal)) {
        $grandTotal = (int) preg_replace('/[^0-9]/', '', $grandTotal);
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
        <p>{{ $data['judul'] ?? 'Waterproofing' }}</p>
        
        <div class="nomor-boq-wrapper">
            <span class="nomor-boq" id="nomorBoqText">{{ $nomorBoq }}</span>
            <button class="btn-copy-boq no-print" onclick="copyNomorBoq()">Copy</button>
        </div>
    </div>

    <!-- DATA PERHITUNGAN -->
    <div class="section-title">DATA PERHITUNGAN WATERPROOFING</div>
    
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Luas Area</span>
            <span class="info-value">{{ number_format((float)($data['luas'] ?? 0), 2) }} m²</span>
        </div>
        <div class="info-item">
            <span class="info-label">Waste</span>
            <span class="info-value">{{ $data['waste'] ?? 0 }}%</span>
        </div>
        <div class="info-item">
            <span class="info-label">Panjang Perimeter</span>
            <span class="info-value">{{ number_format((float)($data['panjang_perimeter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tinggi Perimeter</span>
            <span class="info-value">{{ number_format((float)($data['tinggi_perimeter'] ?? 0), 2) }} m</span>
        </div>
        <div class="info-item">
            <span class="info-label">Sudut Kemiringan</span>
            <span class="info-value">{{ number_format((float)($data['sudut'] ?? 0), 2) }}°</span>
</div>
    </div>

    <!-- RINCIAN MATERIAL -->
    <div class="section-title">RINCIAN KEBUTUHAN MATERIAL</div>
    
    <div class="table-wrapper">
        <table>
            <thead class="table-header">
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Produk</th>
                    <th width="20%">Area</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="10%" class="text-right">Satuan</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($results as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item['nama_produk'] ?? '-' }}</td>
                    <td><span class="badge badge-blue">{{ $item['area'] ?? 'Waterproofing' }}</span></td>
                    <td class="text-right">{{ $item['qty'] ?? 0 }}</td>
                    <td class="text-right">{{ $item['satuan'] ?? 'pcs' }}</td>
                    <td class="text-right">{{ formatRp($item['total_harga'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- GRAND TOTAL -->
    <div class="grand-total-box">
        <span class="label">🏆 GRAND TOTAL</span>
        <span class="value">{{ formatRp($grandTotal) }}</span>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat oleh sistem BOQ | Dicetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</div>

<div class="action-buttons no-print">
    <button onclick="window.print()" class="btn-action btn-print">🖨️ Cetak</button>
    <button onclick="copyNomorBoq()" class="btn-action btn-print">📋 Copy Nomor BOQ</button>
    <button onclick="window.close()" class="btn-action btn-back">✕ Tutup</button>
</div>

<script>
function copyNomorBoq() {
    const text = document.getElementById('nomorBoqText').innerText.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => showToast('Nomor BOQ berhasil disalin!'));
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Nomor BOQ berhasil disalin!');
    }
}

function showToast(message) {
    const toast = document.getElementById('toastMessage');
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>

</body>
</html>