<!-- Modal Jendela Mati 1 Kaca -->
<div id="modalJendelaMati1Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati1Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image Section -->
                <div class="modal-image">
                    <img src="{{ asset('images/jendela/jendela-1-kaca.png') }}" alt="Jendela Mati 1 Kaca">
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 1 Kaca</h2>
                    <p class="modal-form-sub">Hitung kebutuhan material</p>

                    <!-- Notes / Pemberitahuan -->
                    <div class="notes-container">
                        <div class="notes-title">
                            <span class="icon">ℹ️</span> Informasi Perhitungan
                        </div>
                        <ul class="notes-list">
                            <li>
                                <span class="bullet">•</span>
                                <span>Dimensi yang dimasukkan adalah ukuran <span class="highlight">bersih</span> lubang jendela (bukaan)</span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Hasil perhitungan akan menampilkan kebutuhan <span class="highlight">kaca</span></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Tambahkan <span class="highlight">toleransi 5-10%</span> untuk antisipasi waste material</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form id="formJendelaMati1Kaca" onsubmit="return false;">
                        <div style="margin-bottom: 12px;">
                            <label class="input-label">Panjang (cm)</label>
                            <input type="number" class="input-field" id="panjangMati1" placeholder="Contoh: 120" required min="1">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label class="input-label">Lebar (cm)</label>
                            <input type="number" class="input-field" id="lebarMati1" placeholder="Contoh: 80" required min="1">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" id="tebalKacaMati1" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" id="warnaKacaMati1">
                                <option value="clear">Clear (Bening)</option>
                                <option value="hitam">Hitam</option>
                                <option value="putih">Putih</option>
                                <option value="walnut">Walnut</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label class="input-label">Type Kaca</label>
                            <select class="input-field" id="typeKacaMati1">
                                <option value="clear">Clear (Bening)</option>
                                <option value="temper">Temper (Safety Glass)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary" onclick="hitungJendelaMati1()">
                            Hitung Kebutuhan Material
                        </button>
                    </form>

                    <!-- Hasil Perhitungan -->
                    <div id="hasilMati1" style="display: none; margin-top: 16px; padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #eef2f6;">
                        <h3 style="font-size: 12px; font-weight: 600; color: #1a1a2e; margin-bottom: 10px;">📋 Hasil Perhitungan</h3>
                        <div id="hasilContentMati1" style="font-size: 12px; color: #334155; line-height: 1.8;">
                            <!-- Hasil akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungJendelaMati1() {
    // Ambil nilai input
    const panjang = parseFloat(document.getElementById('panjangMati1').value);
    const lebar = parseFloat(document.getElementById('lebarMati1').value);
    const tebalKaca = parseFloat(document.getElementById('tebalKacaMati1').value) || 5;
    const warnaKaca = document.getElementById('warnaKacaMati1').value;
    const typeKaca = document.getElementById('typeKacaMati1').value;

    // Validasi input
    if (!panjang || !lebar || panjang <= 0 || lebar <= 0) {
        alert('Mohon masukkan panjang dan lebar yang valid!');
        return;
    }

    // Konversi ke meter
    const panjangM = panjang / 100;
    const lebarM = lebar / 100;
    const tebalKacaM = tebalKaca / 1000;

    // === PERHITUNGAN MATERIAL ===
    
    // 1. Luas Kaca (m²)
    const luasKaca = panjangM * lebarM;
    
    // 2. Volume Kaca (m³)
    const volumeKaca = luasKaca * tebalKacaM;
    
    // 3. Harga Kaca berdasarkan type dan warna
    let hargaKacaPerM2 = 0;
    let typeKacaLabel = '';
    let warnaKacaLabel = '';
    
    // Set label warna
    if (warnaKaca === 'clear') {
        warnaKacaLabel = 'Clear';
    } else if (warnaKaca === 'hitam') {
        warnaKacaLabel = 'Hitam';
    } else if (warnaKaca === 'putih') {
        warnaKacaLabel = 'Putih';
    } else if (warnaKaca === 'walnut') {
        warnaKacaLabel = 'Walnut';
    }
    
    // Harga berdasarkan type dan warna
    if (typeKaca === 'clear') {
        if (warnaKaca === 'clear') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 150000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 200000;
            } else {
                hargaKacaPerM2 = 250000;
            }
        } else if (warnaKaca === 'hitam') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 180000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 230000;
            } else {
                hargaKacaPerM2 = 280000;
            }
        } else if (warnaKaca === 'putih') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 170000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 220000;
            } else {
                hargaKacaPerM2 = 270000;
            }
        } else if (warnaKaca === 'walnut') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 200000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 250000;
            } else {
                hargaKacaPerM2 = 300000;
            }
        }
        typeKacaLabel = 'Clear Glass';
    } else if (typeKaca === 'temper') {
        if (warnaKaca === 'clear') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 350000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 450000;
            } else {
                hargaKacaPerM2 = 550000;
            }
        } else if (warnaKaca === 'hitam') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 380000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 480000;
            } else {
                hargaKacaPerM2 = 580000;
            }
        } else if (warnaKaca === 'putih') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 370000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 470000;
            } else {
                hargaKacaPerM2 = 570000;
            }
        } else if (warnaKaca === 'walnut') {
            if (tebalKaca <= 5) {
                hargaKacaPerM2 = 400000;
            } else if (tebalKaca <= 8) {
                hargaKacaPerM2 = 500000;
            } else {
                hargaKacaPerM2 = 600000;
            }
        }
        typeKacaLabel = 'Tempered Glass';
    }
    
    // 4. Total Harga Kaca
    const hargaKacaTotal = luasKaca * hargaKacaPerM2;

    // Tampilkan hasil
    const hasilDiv = document.getElementById('hasilMati1');
    const hasilContent = document.getElementById('hasilContentMati1');
    
    hasilContent.innerHTML = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px 16px;">
            <div><span style="color: #94a3b8;">Luas Kaca</span></div>
            <div><strong>${luasKaca.toFixed(2)}</strong> m²</div>
            
            <div><span style="color: #94a3b8;">Tebal Kaca</span></div>
            <div><strong>${tebalKaca}</strong> mm</div>
            
            <div><span style="color: #94a3b8;">Volume Kaca</span></div>
            <div><strong>${volumeKaca.toFixed(4)}</strong> m³</div>
            
            <div><span style="color: #94a3b8;">Warna Kaca</span></div>
            <div><strong>${warnaKacaLabel}</strong></div>
            
            <div><span style="color: #94a3b8;">Type Kaca</span></div>
            <div><strong>${typeKacaLabel}</strong></div>
            
            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e2e8f0; grid-column: span 2;">
                <span style="color: #94a3b8;">Estimasi Biaya</span>
            </div>
            <div style="grid-column: span 2; background: #f1f5f9; padding: 6px 10px; border-radius: 4px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px;">
                    <span>Kaca ${warnaKacaLabel} ${typeKacaLabel} (${tebalKaca}mm)</span>
                    <span><strong>Rp ${hargaKacaTotal.toLocaleString('id-ID')}</strong></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 4px; padding-top: 4px; border-top: 1px solid #e2e8f0; font-weight: 600;">
                    <span>Total Estimasi</span>
                    <span>Rp ${hargaKacaTotal.toLocaleString('id-ID')}</span>
                </div>
            </div>
        </div>
        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8;">
            * Perhitungan ini adalah estimasi. Harga material dapat berbeda di setiap daerah.
            <br>* Tambahkan toleransi 5-10% untuk pembelian material.
        </div>
    `;
    
    hasilDiv.style.display = 'block';
}
</script>