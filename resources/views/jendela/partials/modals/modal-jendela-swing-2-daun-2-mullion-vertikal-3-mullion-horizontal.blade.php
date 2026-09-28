<!-- Modal Jendela Swing 2 Daun 2 Mullion Vertikal 3 Mullion Horizontal -->
<div id="modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer_Swing2V3H" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusen_Swing2V3H" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">

                            <!-- COUPLING TENGAH (Menempel full ke kusen) -->
                            <div id="couplingTengah_Swing2V3H" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- WRAPPER DAUN KIRI -->
                            <div id="daunWrapperKiri_Swing2V3H" style="position:absolute;transform-origin:left center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKacaKiri_Swing2V3H" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                
                                <!-- Panel Baris 1 -->
                                <div id="daunKiri1Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan1Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 2 -->
                                <div id="daunKiri2Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan2Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 3 -->
                                <div id="daunKiri3Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan3Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 4 -->
                                <div id="daunKiri4Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan4Kiri_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>

                                <div id="mullionVertikalKiri_Swing2V3H" style="position:absolute;top:0;bottom:0;width:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH1Kiri_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH2Kiri_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH3Kiri_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="engselKiriDaunKiri_Swing2V3H" style="position:absolute;top:0;bottom:0;width:0;border-left:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>

                            <!-- WRAPPER DAUN KANAN -->
                            <div id="daunWrapperKanan_Swing2V3H" style="position:absolute;transform-origin:right center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKacaKanan_Swing2V3H" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                
                                <!-- Panel Baris 1 -->
                                <div id="daunKiri1Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan1Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 2 -->
                                <div id="daunKiri2Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan2Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 3 -->
                                <div id="daunKiri3Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan3Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- Panel Baris 4 -->
                                <div id="daunKiri4Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                <div id="daunKanan4Kanan_Swing2V3H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>

                                <div id="mullionVertikalKanan_Swing2V3H" style="position:absolute;top:0;bottom:0;width:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH1Kanan_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH2Kanan_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="mullionH3Kanan_Swing2V3H" style="position:absolute;left:0;right:0;height:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                <div id="engselKananDaunKanan_Swing2V3H" style="position:absolute;top:0;bottom:0;width:0;border-right:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI -->
                        <div id="tinggiWrapper_Swing2V3H" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi_Swing2V3H" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebar_Swing2V3H" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP -->
                    <div id="tombolWrapper_Swing2V3H" style="margin-top:50px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleSwingBtn_Swing2V3H" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;width:auto;" onclick="toggleSwing_Swing2V3H()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusSwing_Swing2V3H" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Swing 2 Daun 2 Mullion Vertikal 3 Mullion Horizontal</h2>
                    <p class="modal-form-sub">Hitung kebutuhan material</p>

                    <!-- Notes / Informasi -->
                    <div class="modal-note">
                        <div class="note-title">
                            <span class="icon">📋</span>
                            <span class="label">Informasi Penting</span>
                        </div>
                        <ul class="note-list">
                            <li>
                                <span class="bullet">•</span>
                                <span>Dimensi yang dimasukkan adalah ukuran <strong>bersih</strong> lubang jendela (bukaan) dalam satuan <strong>cm</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Ukuran lebar maksimal <strong>1 panel kaca</strong> adalah <strong>580 cm</strong>. Jika lebih dari 580 cm, maka akan dibagi menjadi <strong>2 panel kaca</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Maksimal tinggi jendela yang menggunakan <strong>friction stay</strong> adalah <strong>1800 mm</strong> (diatas itu menggunakan engsel)</span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Jendela diatas <strong>1800 mm</strong> menggunakan <strong>engsel</strong> (tidak friction stay) + <strong>Peg Stay</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Untuk tinggi jendela <strong>1800 mm</strong> (top hung) dengan friction stay diperlukan penambahan <strong>Peg Stay</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Minimum ketebalan kaca yang digunakan adalah <strong>5 mm</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Ketebalan profile standar: <strong>Casement Series 60</strong> dan <strong>Sliding Series 60 / Series 88</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Jendela Swing 2 Daun adalah jendela <strong>buka dengan engsel</strong> (casement) dengan <strong>2 panel kaca</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.swing2mullionvertikal3mullionhorizontal.hitung') }}" method="POST" id="form_Swing2V3H">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi_Swing2V3H" placeholder="Contoh: 120" required min="1" oninput="hitungSwing_Swing2V3H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar_Swing2V3H" placeholder="Contoh: 80" required min="1" oninput="hitungSwing_Swing2V3H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah_Swing2V3H" placeholder="Contoh: 2" value="1" min="1" required oninput="hitungSwing_Swing2V3H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna_Swing2V3H" onchange="updateWarna_Swing2V3H()">
                                <option value="Hitam">Hitam</option>
                                <option value="Putih">Putih</option>
                                <option value="Walnut">Walnut</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label class="input-label">Type Kaca</label>
                            <select class="input-field" name="type_kaca">
                                <option value="Clear">Clear (Bening)</option>
                                <option value="Temper">Temper (Safety Glass)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary" id="submit_Swing2V3H">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variabel unik
let isSwingOpen_Swing2V3H = false;

function toggleSwing_Swing2V3H() {
    isSwingOpen_Swing2V3H = !isSwingOpen_Swing2V3H;
    let wrapperKiri = document.getElementById('daunWrapperKiri_Swing2V3H');
    let wrapperKanan = document.getElementById('daunWrapperKanan_Swing2V3H');
    let btn = document.getElementById('toggleSwingBtn_Swing2V3H');
    let status = document.getElementById('statusSwing_Swing2V3H');

    if (!wrapperKiri || !wrapperKanan || !btn || !status) return;

    if (isSwingOpen_Swing2V3H) {
        // Buka: Daun kiri ke kiri, daun kanan ke kanan
        wrapperKiri.style.transform = 'rotateY(-70deg)';
        wrapperKanan.style.transform = 'rotateY(70deg)';
        btn.innerHTML = '🔒 Tutup Jendela';
        status.textContent = 'Terbuka';
        status.style.color = '#28a745';
    } else {
        // Tutup: Kembali ke posisi semula
        wrapperKiri.style.transform = 'rotateY(0deg)';
        wrapperKanan.style.transform = 'rotateY(0deg)';
        btn.innerHTML = '🔓 Buka Jendela';
        status.textContent = 'Tertutup';
        status.style.color = '#555';
    }
}

function hitungSwing_Swing2V3H() {
    let inputTinggi = document.getElementById('inputTinggi_Swing2V3H');
    let inputLebar = document.getElementById('inputLebar_Swing2V3H');

    if (!inputTinggi || !inputLebar) {
        return;
    }

    let tinggi = parseInt(inputTinggi.value) || 0;
    let lebar = parseInt(inputLebar.value) || 0;
    
    let angkaTinggi = document.getElementById('angkaTinggi_Swing2V3H');
    let angkaLebar = document.getElementById('angkaLebar_Swing2V3H');
    
    if (angkaTinggi) angkaTinggi.textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    if (angkaLebar) angkaLebar.textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let container = document.getElementById('jendelaContainer_Swing2V3H');
        if (!container) return;

        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen_Swing2V3H');
        if (!kusen) return;
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        // COUPLING TENGAH (DITEMPELKAN KE KUSEN, TIDAK ADA GAP)
        let couplingTengah = document.getElementById('couplingTengah_Swing2V3H');
        let couplingLebar = 4; 

        // LEBAR SATU DAUN DIMULAI DARI SAMPING KIRI KUSEN SAMPAI COUPLING
        let lebarSatuDaun = (displayWidth - (borderThick * 2) - couplingLebar) / 2;
        
        // Dalam 1 daun: bagi 2 kolom (1 mullion vertikal per daun)
        let lebarPanel = (lebarSatuDaun - 4) / 2; // -4 untuk mullion vertikal dalam daun
        
        // Dalam 1 daun: bagi 4 baris (3 mullion horizontal per daun)
        let totalTinggiKaca = kacaHeight - 12; // -12 untuk 3 mullion horizontal per daun
        let tinggiPanel = totalTinggiKaca / 4;
        
        // Ambil elemen
        let wrapperKiri = document.getElementById('daunWrapperKiri_Swing2V3H');
        let wrapperKanan = document.getElementById('daunWrapperKanan_Swing2V3H');
        let engselKiri = document.getElementById('engselKiriDaunKiri_Swing2V3H');
        let engselKanan = document.getElementById('engselKananDaunKanan_Swing2V3H');
        
        // DAUN KIRI
        let daunKacaKiri = document.getElementById('daunKacaKiri_Swing2V3H');
        let mVertikalKiri = document.getElementById('mullionVertikalKiri_Swing2V3H');
        let mH1Kiri = document.getElementById('mullionH1Kiri_Swing2V3H');
        let mH2Kiri = document.getElementById('mullionH2Kiri_Swing2V3H');
        let mH3Kiri = document.getElementById('mullionH3Kiri_Swing2V3H');
        let kiri1Kiri = document.getElementById('daunKiri1Kiri_Swing2V3H');
        let kanan1Kiri = document.getElementById('daunKanan1Kiri_Swing2V3H');
        let kiri2Kiri = document.getElementById('daunKiri2Kiri_Swing2V3H');
        let kanan2Kiri = document.getElementById('daunKanan2Kiri_Swing2V3H');
        let kiri3Kiri = document.getElementById('daunKiri3Kiri_Swing2V3H');
        let kanan3Kiri = document.getElementById('daunKanan3Kiri_Swing2V3H');
        let kiri4Kiri = document.getElementById('daunKiri4Kiri_Swing2V3H');
        let kanan4Kiri = document.getElementById('daunKanan4Kiri_Swing2V3H');
        
        // DAUN KANAN
        let daunKacaKanan = document.getElementById('daunKacaKanan_Swing2V3H');
        let mVertikalKanan = document.getElementById('mullionVertikalKanan_Swing2V3H');
        let mH1Kanan = document.getElementById('mullionH1Kanan_Swing2V3H');
        let mH2Kanan = document.getElementById('mullionH2Kanan_Swing2V3H');
        let mH3Kanan = document.getElementById('mullionH3Kanan_Swing2V3H');
        let kiri1Kanan = document.getElementById('daunKiri1Kanan_Swing2V3H');
        let kanan1Kanan = document.getElementById('daunKanan1Kanan_Swing2V3H');
        let kiri2Kanan = document.getElementById('daunKiri2Kanan_Swing2V3H');
        let kanan2Kanan = document.getElementById('daunKanan2Kanan_Swing2V3H');
        let kiri3Kanan = document.getElementById('daunKiri3Kanan_Swing2V3H');
        let kanan3Kanan = document.getElementById('daunKanan3Kanan_Swing2V3H');
        let kiri4Kanan = document.getElementById('daunKiri4Kanan_Swing2V3H');
        let kanan4Kanan = document.getElementById('daunKanan4Kanan_Swing2V3H');
        
        if (!wrapperKiri || !wrapperKanan) return;
        
        // POSISI COUPLING (Di tengah kusen, tanpa gap)
        if (couplingTengah) {
            couplingTengah.style.left = (displayWidth / 2) - 8 + 'px';
            couplingTengah.style.top = '0px';
            couplingTengah.style.height = displayHeight;
        }
        
        // SET DAUN KIRI
        wrapperKiri.style.width = lebarSatuDaun + 'px';
        wrapperKiri.style.height = kacaHeight + 'px';
        wrapperKiri.style.top = gap + 'px';
        wrapperKiri.style.left = gap + 'px';
        wrapperKiri.style.transformOrigin = 'left center';
        
        daunKacaKiri.style.width = lebarSatuDaun + 'px';
        daunKacaKiri.style.height = kacaHeight + 'px';
        
        // MULLION HORIZONTAL KIRI
        if (mH1Kiri) { mH1Kiri.style.top = (tinggiPanel) + 'px'; mH1Kiri.style.left = '0px'; mH1Kiri.style.width = lebarSatuDaun + 'px'; }
        if (mH2Kiri) { mH2Kiri.style.top = (tinggiPanel + 4 + tinggiPanel) + 'px'; mH2Kiri.style.left = '0px'; mH2Kiri.style.width = lebarSatuDaun + 'px'; }
        if (mH3Kiri) { mH3Kiri.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel) + 'px'; mH3Kiri.style.left = '0px'; mH3Kiri.style.width = lebarSatuDaun + 'px'; }
        
        // PANEL DAUN KIRI (Baris 1)
        kiri1Kiri.style.width = lebarPanel + 'px';
        kiri1Kiri.style.height = tinggiPanel + 'px';
        kiri1Kiri.style.top = '0px';
        kiri1Kiri.style.left = '0px';
        kanan1Kiri.style.width = lebarPanel + 'px';
        kanan1Kiri.style.height = tinggiPanel + 'px';
        kanan1Kiri.style.top = '0px';
        kanan1Kiri.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KIRI (Baris 2)
        kiri2Kiri.style.width = lebarPanel + 'px';
        kiri2Kiri.style.height = tinggiPanel + 'px';
        kiri2Kiri.style.top = (tinggiPanel + 4) + 'px';
        kiri2Kiri.style.left = '0px';
        kanan2Kiri.style.width = lebarPanel + 'px';
        kanan2Kiri.style.height = tinggiPanel + 'px';
        kanan2Kiri.style.top = (tinggiPanel + 4) + 'px';
        kanan2Kiri.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KIRI (Baris 3)
        kiri3Kiri.style.width = lebarPanel + 'px';
        kiri3Kiri.style.height = tinggiPanel + 'px';
        kiri3Kiri.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kiri3Kiri.style.left = '0px';
        kanan3Kiri.style.width = lebarPanel + 'px';
        kanan3Kiri.style.height = tinggiPanel + 'px';
        kanan3Kiri.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kanan3Kiri.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KIRI (Baris 4)
        kiri4Kiri.style.width = lebarPanel + 'px';
        kiri4Kiri.style.height = tinggiPanel + 'px';
        kiri4Kiri.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kiri4Kiri.style.left = '0px';
        kanan4Kiri.style.width = lebarPanel + 'px';
        kanan4Kiri.style.height = tinggiPanel + 'px';
        kanan4Kiri.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kanan4Kiri.style.left = (lebarPanel + 4) + 'px';
        
        // MULLION VERTIKAL KIRI
        if (mVertikalKiri) {
            mVertikalKiri.style.left = (lebarSatuDaun / 2) - 2 + 'px';
            mVertikalKiri.style.top = '0px';
            mVertikalKiri.style.height = kacaHeight + 'px';
        }
        
        // SET DAUN KANAN
        wrapperKanan.style.width = lebarSatuDaun + 'px';
        wrapperKanan.style.height = kacaHeight + 'px';
        wrapperKanan.style.top = gap + 'px';
        wrapperKanan.style.left = (gap + lebarSatuDaun - 4) + 'px';
        wrapperKanan.style.transformOrigin = 'right center';
        
        daunKacaKanan.style.width = lebarSatuDaun + 'px';
        daunKacaKanan.style.height = kacaHeight + 'px';
        
        // MULLION HORIZONTAL KANAN
        if (mH1Kanan) { mH1Kanan.style.top = (tinggiPanel) + 'px'; mH1Kanan.style.left = '0px'; mH1Kanan.style.width = lebarSatuDaun + 'px'; }
        if (mH2Kanan) { mH2Kanan.style.top = (tinggiPanel + 4 + tinggiPanel) + 'px'; mH2Kanan.style.left = '0px'; mH2Kanan.style.width = lebarSatuDaun + 'px'; }
        if (mH3Kanan) { mH3Kanan.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel) + 'px'; mH3Kanan.style.left = '0px'; mH3Kanan.style.width = lebarSatuDaun + 'px'; }
        
        // PANEL DAUN KANAN (Baris 1)
        kiri1Kanan.style.width = lebarPanel + 'px';
        kiri1Kanan.style.height = tinggiPanel + 'px';
        kiri1Kanan.style.top = '0px';
        kiri1Kanan.style.left = '0px';
        kanan1Kanan.style.width = lebarPanel + 'px';
        kanan1Kanan.style.height = tinggiPanel + 'px';
        kanan1Kanan.style.top = '0px';
        kanan1Kanan.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KANAN (Baris 2)
        kiri2Kanan.style.width = lebarPanel + 'px';
        kiri2Kanan.style.height = tinggiPanel + 'px';
        kiri2Kanan.style.top = (tinggiPanel + 4) + 'px';
        kiri2Kanan.style.left = '0px';
        kanan2Kanan.style.width = lebarPanel + 'px';
        kanan2Kanan.style.height = tinggiPanel + 'px';
        kanan2Kanan.style.top = (tinggiPanel + 4) + 'px';
        kanan2Kanan.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KANAN (Baris 3)
        kiri3Kanan.style.width = lebarPanel + 'px';
        kiri3Kanan.style.height = tinggiPanel + 'px';
        kiri3Kanan.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kiri3Kanan.style.left = '0px';
        kanan3Kanan.style.width = lebarPanel + 'px';
        kanan3Kanan.style.height = tinggiPanel + 'px';
        kanan3Kanan.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kanan3Kanan.style.left = (lebarPanel + 4) + 'px';
        
        // PANEL DAUN KANAN (Baris 4)
        kiri4Kanan.style.width = lebarPanel + 'px';
        kiri4Kanan.style.height = tinggiPanel + 'px';
        kiri4Kanan.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kiri4Kanan.style.left = '0px';
        kanan4Kanan.style.width = lebarPanel + 'px';
        kanan4Kanan.style.height = tinggiPanel + 'px';
        kanan4Kanan.style.top = (tinggiPanel + 4 + tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kanan4Kanan.style.left = (lebarPanel + 4) + 'px';
        
        // MULLION VERTIKAL KANAN
        if (mVertikalKanan) {
            mVertikalKanan.style.left = (lebarSatuDaun / 2) - 2 + 'px';
            mVertikalKanan.style.top = '0px';
            mVertikalKanan.style.height = kacaHeight + 'px';
        }
        
        // ENGSEL KIRI
        if (engselKiri) {
            engselKiri.style.display = 'block';
            engselKiri.style.left = '0px';
            engselKiri.style.top = (displayHeight * 0.2) + 'px';
            engselKiri.style.height = (displayHeight * 0.6) + 'px';
        }
        
        // ENGSEL KANAN
        if (engselKanan) {
            engselKanan.style.display = 'block';
            engselKanan.style.right = '0px';
            engselKanan.style.top = (displayHeight * 0.2) + 'px';
            engselKanan.style.height = (displayHeight * 0.6) + 'px';
        }
    }
}

window.addEventListener('resize', function() {
    hitungSwing_Swing2V3H();
});

function updateWarna_Swing2V3H() {
    let warna = document.getElementById('selectWarna_Swing2V3H').value;
    let kusen = document.getElementById('kusen_Swing2V3H');
    let couplingTengah = document.getElementById('couplingTengah_Swing2V3H');
    let mH1Kiri = document.getElementById('mullionH1Kiri_Swing2V3H');
    let mH2Kiri = document.getElementById('mullionH2Kiri_Swing2V3H');
    let mH3Kiri = document.getElementById('mullionH3Kiri_Swing2V3H');
    let mH1Kanan = document.getElementById('mullionH1Kanan_Swing2V3H');
    let mH2Kanan = document.getElementById('mullionH2Kanan_Swing2V3H');
    let mH3Kanan = document.getElementById('mullionH3Kanan_Swing2V3H');
    let mVertikalKiri = document.getElementById('mullionVertikalKiri_Swing2V3H');
    let mVertikalKanan = document.getElementById('mullionVertikalKanan_Swing2V3H');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    if (kusen) kusen.style.borderColor = warnaKusen;
    if (couplingTengah) couplingTengah.style.backgroundColor = warnaKusen;
    if (mH1Kiri) mH1Kiri.style.backgroundColor = warnaKusen;
    if (mH2Kiri) mH2Kiri.style.backgroundColor = warnaKusen;
    if (mH3Kiri) mH3Kiri.style.backgroundColor = warnaKusen;
    if (mH1Kanan) mH1Kanan.style.backgroundColor = warnaKusen;
    if (mH2Kanan) mH2Kanan.style.backgroundColor = warnaKusen;
    if (mH3Kanan) mH3Kanan.style.backgroundColor = warnaKusen;
    if (mVertikalKiri) mVertikalKiri.style.backgroundColor = warnaKusen;
    if (mVertikalKanan) mVertikalKanan.style.backgroundColor = warnaKusen;
}

function initJendelaSwing_Swing2V3H() {
    setTimeout(hitungSwing_Swing2V3H, 100);
}

const originalCloseModal_Swing2V3H = window.closeModal;
window.closeModal = function(modalId) {
    if (typeof originalCloseModal_Swing2V3H === 'function') {
        originalCloseModal_Swing2V3H(modalId);
    }
    if (modalId === 'modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal') {
        let inputTinggi = document.getElementById('inputTinggi_Swing2V3H');
        let inputLebar = document.getElementById('inputLebar_Swing2V3H');
        let inputJumlah = document.getElementById('inputJumlah_Swing2V3H');
        if (inputTinggi) inputTinggi.value = '';
        if (inputLebar) inputLebar.value = '';
        if (inputJumlah) inputJumlah.value = 1;
        hitungSwing_Swing2V3H();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(hitungSwing_Swing2V3H, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    hitungSwing_Swing2V3H();
});
</script>