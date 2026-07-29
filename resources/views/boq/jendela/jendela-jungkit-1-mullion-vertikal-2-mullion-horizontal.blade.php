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
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
    
    .badge-section {
        background: #edf2f7;
        color: #4a5568;
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }
    
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
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
    
    .header-brand {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 16px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .header-brand::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    
    .header-brand .brand-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }
    
    .header-brand .brand-icon.jendela {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }
    
    .header-brand .brand-name {
        font-size: 20px;
        font-weight: 600;
        color: white;
        letter-spacing: -0.3px;
    }
    
    .header-brand .brand-sub {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        font-weight: 400;
        margin-top: 2px;
    }
    
    .header-brand .brand-badge {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 500;
        color: rgba(255,255,255,0.7);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .notes-container {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    
    .notes-container .notes-title {
        font-size: 13px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .notes-container .notes-title .icon {
        font-size: 18px;
    }
    
    .notes-container .notes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .notes-container .notes-list li {
        font-size: 12px;
        color: #78350f;
        padding: 4px 0;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.5;
    }
    
    .notes-container .notes-list li .bullet {
        color: #d97706;
        font-weight: 700;
    }
    
    .notes-container .notes-list li .highlight {
        background: #fef3c7;
        padding: 0 6px;
        border-radius: 4px;
        font-weight: 500;
        color: #92400e;
    }

    .grid-4col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    @media (max-width: 768px) {
        .grid-4col {
            grid-template-columns: 1fr 1fr;
        }
    }

    .required-star {
        color: #e53e3e;
        margin-left: 2px;
    }

    .result-table {
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
    }

    .result-table th {
        text-align: left;
        padding: 8px 12px;
        font-weight: 500;
        color: #94a3b8;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
    }

    .result-table td {
        padding: 7px 12px;
        border-bottom: 1px solid #f1f4f9;
        color: #1a1a2e;
    }

    .result-table tr:last-child td {
        border-bottom: none;
    }

    .result-table .product-name {
        font-weight: 500;
    }

    .result-table .qty {
        font-weight: 600;
        text-align: center;
    }

    .result-table .unit {
        color: #94a3b8;
        font-size: 11px;
        text-align: left;
    }

    .result-table .text-center {
        text-align: center;
    }

    .result-table .text-right {
        text-align: right;
    }

    .group-header {
        font-size: 12px;
        font-weight: 600;
        padding: 8px 12px;
        margin: 8px 0 4px 0;
        border-radius: 4px;
        background: #f1f4f9;
        color: #1a1a2e;
        border-left: 3px solid #1a1a2e;
    }

    .group-header-profile {
        background: #e8edf5;
        border-left-color: #2b6cb0;
        color: #1a3a5c;
    }

    .group-header-reinforcement {
        background: #e8f5ed;
        border-left-color: #38a169;
        color: #1a5c3a;
    }

    .group-header-kaca {
        background: #f5ede8;
        border-left-color: #e67e22;
        color: #5c3a1a;
    }

    .group-header-screw {
        background: #f5e8ed;
        border-left-color: #e53e3e;
        color: #5c1a3a;
    }

    .group-header-hardware {
        background: #f0e6f5;
        border-left-color: #9b59b6;
        color: #4a1a5c;
    }

    .grand-total-minimal {
        background: #1a1a2e;
        border-radius: 8px;
        padding: 14px 20px;
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .grand-total-minimal .label {
        color: rgba(255,255,255,0.6);
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .grand-total-minimal .amount {
        color: white;
        font-size: 20px;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 13px;
    }
    
    .kode-produk {
        font-size: 10px;
        color: #64748b;
        font-weight: 400;
        font-family: 'Courier New', monospace;
    }

    .mullion-info {
        background: #f0f7ff;
        padding: 12px 16px;
        border-radius: 8px;
        border-left: 3px solid #2b6cb0;
        margin-top: 16px;
    }
    
    .mullion-info .title {
        font-size: 12px;
        font-weight: 600;
        color: #2b6cb0;
        margin-bottom: 6px;
    }
    
    .mullion-info .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        font-size: 12px;
    }
    
    .mullion-info .grid .label {
        color: #64748b;
    }
    
    .mullion-info .grid .value {
        font-weight: 600;
    }

    .badge-hardware {
        display: inline-block;
        background: #f0e6f5;
        color: #4a1a5c;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }

    .badge-friction {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }

    .badge-transmitter {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }

    .badge-handle {
        display: inline-block;
        background: #d1fae5;
        color: #065f46;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }

    .badge-mullion {
        display: inline-block;
        background: #ede9fe;
        color: #5b21b6;
        font-size: 9px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="header-brand">
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="brand-icon jendela">J</div>
                <div>
                    <div class="brand-name">BOQ - Jendela Jungkit 1 Daun (Mullion)</div>
                    <div class="brand-sub">Hitung kebutuhan material jendela jungkit 1 daun dengan mullion</div>
                </div>
            </div>
            <div class="brand-badge">JENDELA + MULLION</div>
        </div>
    </div>

    <!-- Notes / Pemberitahuan -->
    <div class="notes-container">
        <div class="notes-title">
            <span class="icon">📋</span> Informasi Perhitungan
        </div>
        <ul class="notes-list">
            <li>
                <span class="bullet">•</span>
                <span>Dimensi yang dimasukkan adalah ukuran <span class="highlight">bersih</span> lubang jendela (bukaan)</span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Hasil perhitungan akan menampilkan kebutuhan <span class="highlight">Profile, Reinforcement, Kaca, Screw, dan Hardware</span></span>
            </li>
            <li>
                <span class="bullet">•</span>
                <span>Mullion adalah <span class="highlight">penyangga vertikal/horizontal</span> pada jendela</span>
            </li>
        </ul>
    </div>

    <!-- Form Input -->
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">📐 Data Jendela</h3>
                    <p class="section-subtitle">Masukkan dimensi dan spesifikasi jendela</p>
                </div>
                <span class="badge-section">INPUT</span>
            </div>
        </div>
        <div class="section-body">
            <form action="{{ route('boq.jendela.jungkit1mullionvertikal2mullionhorizontal.hitung') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="mullion">
                
                <div class="grid-4col">
                    <div>
                        <label class="input-label">Panjang (cm) <span class="required-star">*</span></label>
                        <input type="number" class="input-field" name="panjang" placeholder="Contoh: 120" required min="1" value="{{ old('panjang', $panjang ?? '') }}">
                    </div>

                    <div>
                        <label class="input-label">Lebar (cm) <span class="required-star">*</span></label>
                        <input type="number" class="input-field" name="lebar" placeholder="Contoh: 80" required min="1" value="{{ old('lebar', $lebar ?? '') }}">
                    </div>

                    <div>
                        <label class="input-label">Tebal Kaca (mm)</label>
                        <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="{{ old('tebal_kaca', $tebal_kaca ?? 5) }}" min="1">
                    </div>

                    <div>
                        <label class="input-label">Jumlah Unit <span class="required-star">*</span></label>
                        <input type="number" class="input-field" name="jumlah" placeholder="Contoh: 2" value="{{ old('jumlah', $jumlah ?? 1) }}" min="1" required>
                    </div>
                </div>

                <div class="grid-4col" style="margin-top: 12px;">
                    <div>
                        <label class="input-label">Warna Profile <span class="required-star">*</span></label>
                        <select class="input-field" name="warna" required>
                            <option value="Clear" {{ (old('warna', $warna ?? '') == 'Clear') ? 'selected' : '' }}>Clear (Bening)</option>
                            <option value="Hitam" {{ (old('warna', $warna ?? '') == 'Hitam') ? 'selected' : '' }}>Hitam</option>
                            <option value="Putih" {{ (old('warna', $warna ?? '') == 'Putih') ? 'selected' : '' }}>Putih</option>
                            <option value="Walnut" {{ (old('warna', $warna ?? '') == 'Walnut') ? 'selected' : '' }}>Walnut</option>
                        </select>
                    </div>

                    <div>
                        <label class="input-label">Type Kaca <span class="required-star">*</span></label>
                        <select class="input-field" name="type_kaca" required>
                            <option value="Clear" {{ (old('type_kaca', $type_kaca ?? '') == 'Clear') ? 'selected' : '' }}>Clear (Bening)</option>
                            <option value="Temper" {{ (old('type_kaca', $type_kaca ?? '') == 'Temper') ? 'selected' : '' }}>Temper (Safety Glass)</option>
                        </select>
                    </div>

                    <div>
                        <label class="input-label">Pilihan Handle</label>
                        <select class="input-field" name="handle_id">
                            <option value="">Pilih Handle</option>
                            @foreach($aksesoris as $item)
                                @if($item->area && ($item->area->slug == 'window-hardware-handle-rambuncis' || $item->area->slug == 'window-hardware-handle-multilock'))
                                    <option value="{{ $item->id }}" {{ (old('handle_id', $handle_id ?? '') == $item->id) ? 'selected' : '' }}>
                                        {{ $item->nama_produk }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <!-- Kosong untuk spacing -->
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 16px;">
                    Hitung Kebutuhan Material
                </button>
            </form>
        </div>
    </div>

    <!-- Hasil Perhitungan -->
    @isset($produk)
    <div class="section-card">
        <div class="section-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="section-title">📊 Hasil Perhitungan</h3>
                    <p class="section-subtitle">Rincian kebutuhan material Jendela Jungkit 1 Daun dengan Mullion</p>
                </div>
                <span class="badge-section">RESULT</span>
            </div>
        </div>
        <div class="section-body">
            <!-- Tabel Material Dikelompokkan berdasarkan AREA -->
            <div class="table-container">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th style="width:5%;text-align:center;">No</th>
                            <th style="width:25%;">Komponen</th>
                            <th style="width:15%;">Kode Produk</th>
                            <th style="width:12%;">Satuan</th>
                            <th style="width:15%;text-align:center;">Qty</th>
                            <th style="width:18%;text-align:center;">Total Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Kelompokkan berdasarkan area slug
                            $grouped = [];
                            foreach ($aksesoris as $item) {
                                // Skip item dengan qty 0
                                if ($item->qty <= 0) {
                                    continue;
                                }
                                
                                // Skip transmitter dan friction stay yang tidak terpilih (qty 0)
                                if ($item->area && ($item->area->slug == 'window-hardware-transmitter' || $item->area->slug == 'window-hardware-friction-stay') && $item->qty <= 0) {
                                    continue;
                                }
                                if ($item->area && ($item->area->slug == 'window-hardware-handle-rambuncis' || $item->area->slug == 'window-hardware-handle-multilock') && $item->qty <= 0) {
                                    continue;
                                }
                                
                                $areaSlug = $item->area ? $item->area->slug : 'lainnya';
                                
                                if (str_starts_with($areaSlug, 'profile')) {
                                    $groupKey = 'profile';
                                } elseif ($areaSlug == 'setting-block') {
                                    $groupKey = 'kaca';
                                } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash' || $areaSlug == 'reinforcement-mullion') {
                                    $groupKey = 'reinforcement';
                                } elseif (str_starts_with($areaSlug, 'window-hardware')) {
                                    $groupKey = 'hardware';
                                } elseif ($areaSlug == 'screw-reinforcement' || $areaSlug == 'screw-transmitter' || $areaSlug == 'screw-friction-stay' || $areaSlug == 'screw-handle') {
                                    $groupKey = 'screw';
                                } else {
                                    $groupKey = $areaSlug;
                                }
                                
                                if (!isset($grouped[$groupKey])) {
                                    $grouped[$groupKey] = [];
                                }
                                $grouped[$groupKey][] = $item;
                            }
                            
                            // Urutan area yang diinginkan
                            $areaOrder = ['profile', 'reinforcement', 'kaca', 'hardware', 'screw'];
                            
                            // Label dan class untuk setiap area
                            $areaLabels = [
                                'profile' => 'PROFILE',
                                'reinforcement' => 'REINFORCEMENT',
                                'kaca' => 'KACA',
                                'hardware' => 'HARDWARE',
                                'screw' => 'SCREW',
                            ];
                            
                            $areaClasses = [
                                'profile' => 'group-header-profile',
                                'reinforcement' => 'group-header-reinforcement',
                                'kaca' => 'group-header-kaca',
                                'hardware' => 'group-header-hardware',
                                'screw' => 'group-header-screw',
                            ];
                        @endphp

                        @foreach($areaOrder as $areaKey)
                            @if(isset($grouped[$areaKey]))
                                @php 
                                    $items = $grouped[$areaKey];
                                    $totalFrameQty = 0;
                                    $totalGlazeBeadQty = 0;
                                    $totalSashQty = 0;
                                    $totalMullionQty = 0;
                                    $totalHardwareQty = 0;
                                    $frameItems = [];
                                    $glazeBeadItems = [];
                                    $sashItems = [];
                                    $mullionItems = [];
                                    $hardwareItems = [];
                                    $otherItems = [];
                                    
                                    // Pisahkan frame, glaze bead, sash, mullion, hardware, dan lainnya
                                    foreach ($items as $item) {
                                        $namaLower = strtolower($item->nama_produk);
                                        $areaSlug = $item->area ? $item->area->slug : '';
                                        
                                        // Deteksi mullion berdasarkan area slug
                                        if ($areaSlug == 'profile-mullion-vertikal' || $areaSlug == 'profile-mullion-horizontal') {
                                            $mullionItems[] = $item;
                                            $totalMullionQty += $item->qty ?? 0;
                                        } elseif (str_starts_with($areaSlug, 'window-hardware-')) {
                                            $hardwareItems[] = $item;
                                            $totalHardwareQty += $item->qty ?? 0;
                                        } elseif (str_contains($namaLower, 'frame')) {
                                            $frameItems[] = $item;
                                            $totalFrameQty += $item->qty ?? 0;
                                        } elseif (str_contains($namaLower, 'glaze bead')) {
                                            $glazeBeadItems[] = $item;
                                            $totalGlazeBeadQty += $item->qty ?? 0;
                                        } elseif (str_contains($namaLower, 'sash')) {
                                            $sashItems[] = $item;
                                            $totalSashQty += $item->qty ?? 0;
                                        } else {
                                            $otherItems[] = $item;
                                        }
                                    }
                                @endphp
                                <!-- Group Header -->
                                <tr>
                                    <td colspan="6" class="group-header {{ $areaClasses[$areaKey] ?? '' }}">
                                        📁 {{ $areaLabels[$areaKey] ?? strtoupper($areaKey) }}
                                    </td>
                                </tr>
                                @php $no = 1; @endphp
                                
                                <!-- MULLION Items -->
                                @if(count($mullionItems) > 0)
                                    @php $mullionCount = count($mullionItems); @endphp
                                    @foreach($mullionItems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="product-name">
                                                {{ $item->nama_produk }}
                                            </td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            @if($index === 0)
                                                <td class="text-center" rowspan="{{ $mullionCount }}">
                                                    {{ number_format($totalMullionQty, 2) }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                
                                <!-- FRAME Items -->
                                @if(count($frameItems) > 0)
                                    @php $frameCount = count($frameItems); @endphp
                                    @foreach($frameItems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="product-name">{{ $item->nama_produk }}</td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            @if($index === 0)
                                                <td class="text-center" rowspan="{{ $frameCount }}">
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
                                            <td class="product-name">{{ $item->nama_produk }}</td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            @if($index === 0)
                                                <td class="text-center" rowspan="{{ $glazeCount }}">
                                                    {{ number_format($totalGlazeBeadQty, 2) }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                
                                <!-- SASH Items -->
                                @if(count($sashItems) > 0)
                                    @php $sashCount = count($sashItems); @endphp
                                    @foreach($sashItems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="product-name">{{ $item->nama_produk }}</td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            @if($index === 0)
                                                <td class="text-center" rowspan="{{ $sashCount }}">
                                                    {{ number_format($totalSashQty, 2) }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                
                                <!-- HARDWARE Items (termasuk friction stay, transmitter, handle) -->
                                @if($areaKey == 'hardware')
                                    @foreach($hardwareItems as $item)
                                        @php
                                            $areaSlug = $item->area ? $item->area->slug : '';
                                            $badgeClass = 'badge-hardware';
                                            $badgeText = 'HARDWARE';
                                            if ($areaSlug == 'window-hardware-friction-stay') {
                                                $badgeClass = 'badge-friction';
                                                $badgeText = 'FRICTION STAY';
                                            } elseif ($areaSlug == 'window-hardware-transmitter') {
                                                $badgeClass = 'badge-transmitter';
                                                $badgeText = 'TRANSMITTER';
                                            } elseif ($areaSlug == 'window-hardware-handle-rambuncis' || $areaSlug == 'window-hardware-handle-multilock') {
                                                $badgeClass = 'badge-handle';
                                                $badgeText = 'HANDLE';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="product-name">
                                                {{ $item->nama_produk }}
                                            </td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                                <!-- Other Items (non-frame, non-glaze bead, non-sash, non-mullion, non-hardware) -->
                                @if($areaKey != 'hardware')
                                    @foreach($otherItems as $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="product-name">{{ $item->nama_produk }}</td>
                                            <td><span class="kode-produk">{{ $item->kode_produk ?? '-' }}</span></td>
                                            <td class="unit">{{ $item->unit->unit_name ?? 'unit' }}</td>
                                            <td class="qty text-center">{{ number_format($item->qty ?? 0, 2) }}</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach

                        @if(empty($grouped))
                            <tr>
                                <td colspan="6" class="empty-state">
                                    Tidak ada aksesoris yang terdaftar untuk produk ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Tombol PDF & Kembali -->
            <div style="margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap;">
                <form action="{{ route('boq.jendela.jungkit1mullionvertikal2mullionhorizontal.export-pdf') }}" method="POST" target="_blank" style="flex:1;">
                    @csrf
                    <input type="hidden" name="judul" value="BOQ - Jendela Jungkit 1 Daun (Mullion)">
                    <input type="hidden" name="panjang" value="{{ $panjang ?? 0 }}">
                    <input type="hidden" name="lebar" value="{{ $lebar ?? 0 }}">
                    <input type="hidden" name="jumlah" value="{{ $jumlah ?? 0 }}">
                    <input type="hidden" name="tebal_kaca" value="{{ $tebal_kaca ?? 5 }}">
                    <input type="hidden" name="warna" value="{{ $warna ?? 'Clear' }}">
                    <input type="hidden" name="type_kaca" value="{{ $type_kaca ?? 'Clear' }}">
                    <input type="hidden" name="handle_id" value="{{ $handle_id ?? '' }}">
                    <input type="hidden" name="type" value="mullion">
                    
                    <button type="submit" class="btn-pdf">
                        📄 Export PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endisset
</div>
@endsection
