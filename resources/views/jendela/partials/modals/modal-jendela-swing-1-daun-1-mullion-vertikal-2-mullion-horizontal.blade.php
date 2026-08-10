<!-- Modal Jendela Swing 1 Daun 1 Mullion Vertikal 2 Mullion Horizontal -->
<div id="modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer_Swing1V2H" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusen_Swing1V2H" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- WRAPPER DAUN JENDELA -->
                            <div id="daunWrapper_Swing1V2H" style="position:absolute;transform-origin:left center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                
                                <!-- LAPISAN KACA UTUH -->
                                <div id="daunKaca_Swing1V2H" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>

                                <!-- PANEL KIRI ATAS -->
                                <div id="daunKiriAtas_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- PANEL KANAN ATAS -->
                                <div id="daunKananAtas_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>

                                <!-- PANEL KIRI TENGAH -->
                                <div id="daunKiriTengah_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- PANEL KANAN TENGAH -->
                                <div id="daunKananTengah_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>

                                <!-- PANEL KIRI BAWAH -->
                                <div id="daunKiriBawah_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>
                                
                                <!-- PANEL KANAN BAWAH -->
                                <div id="daunKananBawah_Swing1V2H" style="position:absolute;border:2px solid transparent;background:transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;z-index:1;"></div>

                                <!-- MULLION VERTIKAL (Tengah) -->
                                <div id="mullionVertikal_Swing1V2H" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                
                                <!-- MULLION HORIZONTAL ATAS -->
                                <div id="mullionHAtas_Swing1V2H" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                
                                <!-- MULLION HORIZONTAL BAWAH -->
                                <div id="mullionHBawah_Swing1V2H" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                                
                            </div>

                            <!-- Indikator Engsel (KIRI) -->
                            <div id="engselKiri_Swing1V2H" style="position:absolute;top:0;bottom:0;width:0;border-left:3px dashed #aaa;z-index:2;display:none;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI -->
                        <div id="tinggiWrapper_Swing1V2H" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi_Swing1V2H" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebar_Swing1V2H" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP -->
                    <div id="tombolWrapper_Swing1V2H" style="margin-top:50px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleSwingBtn_Swing1V2H" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;width:auto;" onclick="toggleSwing_Swing1V2H()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusSwing_Swing1V2H" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Swing 1 Daun 1 Mullion Vertikal 2 Mullion Horizontal</h2>
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
                                <span>Jendela Swing 1 Daun adalah jendela <strong>buka dengan engsel</strong> (casement) dengan <strong>1 panel kaca</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.swing1mullionvertikal2mullionhorizontal.hitung') }}" method="POST" id="form_Swing1V2H">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="tinggi" id="inputTinggi_Swing1V2H" placeholder="Contoh: 120" required min="1" oninput="hitungSwing_Swing1V2H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar_Swing1V2H" placeholder="Contoh: 80" required min="1" oninput="hitungSwing_Swing1V2H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah_Swing1V2H" placeholder="Contoh: 2" value="1" min="1" required oninput="hitungSwing_Swing1V2H()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna_Swing1V2H" onchange="updateWarna_Swing1V2H()">
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

                        <button type="submit" class="btn-primary" id="submit_Swing1V2H">
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
let isSwingOpen_Swing1V2H = false;

function toggleSwing_Swing1V2H() {
    isSwingOpen_Swing1V2H = !isSwingOpen_Swing1V2H;
    let wrapper = document.getElementById('daunWrapper_Swing1V2H');
    let btn = document.getElementById('toggleSwingBtn_Swing1V2H');
    let status = document.getElementById('statusSwing_Swing1V2H');

    if (!wrapper || !btn || !status) return;

    if (isSwingOpen_Swing1V2H) {
        wrapper.style.transform = 'rotateY(-70deg)';
        btn.innerHTML = '🔒 Tutup Jendela';
        status.textContent = 'Terbuka';
        status.style.color = '#28a745';
    } else {
        wrapper.style.transform = 'rotateY(0deg)';
        btn.innerHTML = '🔓 Buka Jendela';
        status.textContent = 'Tertutup';
        status.style.color = '#555';
    }
}

function hitungSwing_Swing1V2H() {
    let inputTinggi = document.getElementById('inputTinggi_Swing1V2H');
    let inputLebar = document.getElementById('inputLebar_Swing1V2H');

    if (!inputTinggi || !inputLebar) {
        return;
    }

    let tinggi = parseInt(inputTinggi.value) || 0;
    let lebar = parseInt(inputLebar.value) || 0;
    
    let angkaTinggi = document.getElementById('angkaTinggi_Swing1V2H');
    let angkaLebar = document.getElementById('angkaLebar_Swing1V2H');
    
    if (angkaTinggi) angkaTinggi.textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    if (angkaLebar) angkaLebar.textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let container = document.getElementById('jendelaContainer_Swing1V2H');
        if (!container) return;

        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen_Swing1V2H');
        if (!kusen) return;
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        let wrapper = document.getElementById('daunWrapper_Swing1V2H');
        let daunKaca = document.getElementById('daunKaca_Swing1V2H');
        if (!wrapper || !daunKaca) return;
        
        wrapper.style.width = kacaWidth + 'px';
        wrapper.style.height = kacaHeight + 'px';
        wrapper.style.top = gap + 'px';
        wrapper.style.left = gap + 'px';
        wrapper.style.transformOrigin = 'left center';
        
        daunKaca.style.width = kacaWidth + 'px';
        daunKaca.style.height = kacaHeight + 'px';
        
        // Bagi 3 baris (2 mullion horizontal) dan 2 kolom (1 mullion vertikal)
        let totalLebarKaca = kacaWidth - 4; // -4 untuk vertikal
        let lebarPanel = totalLebarKaca / 2;
        
        let totalTinggiKaca = kacaHeight - 8; // -8 untuk 2 mullion horizontal
        let tinggiPanel = totalTinggiKaca / 3;
        
        let kiriAtas = document.getElementById('daunKiriAtas_Swing1V2H');
        let kananAtas = document.getElementById('daunKananAtas_Swing1V2H');
        let kiriTengah = document.getElementById('daunKiriTengah_Swing1V2H');
        let kananTengah = document.getElementById('daunKananTengah_Swing1V2H');
        let kiriBawah = document.getElementById('daunKiriBawah_Swing1V2H');
        let kananBawah = document.getElementById('daunKananBawah_Swing1V2H');
        let mVertikal = document.getElementById('mullionVertikal_Swing1V2H');
        let mHAtas = document.getElementById('mullionHAtas_Swing1V2H');
        let mHBawah = document.getElementById('mullionHBawah_Swing1V2H');
        let engselKiri = document.getElementById('engselKiri_Swing1V2H');
        
        if (!kiriAtas || !kananAtas || !kiriTengah || !kananTengah || !kiriBawah || !kananBawah) return;
        
        // BARIS ATAS
        kiriAtas.style.width = lebarPanel + 'px';
        kiriAtas.style.height = tinggiPanel + 'px';
        kiriAtas.style.top = '0px';
        kiriAtas.style.left = '0px';
        
        kananAtas.style.width = lebarPanel + 'px';
        kananAtas.style.height = tinggiPanel + 'px';
        kananAtas.style.top = '0px';
        kananAtas.style.left = (lebarPanel + 4) + 'px';
        
        // BARIS TENGAH
        kiriTengah.style.width = lebarPanel + 'px';
        kiriTengah.style.height = tinggiPanel + 'px';
        kiriTengah.style.top = (tinggiPanel + 4) + 'px';
        kiriTengah.style.left = '0px';
        
        kananTengah.style.width = lebarPanel + 'px';
        kananTengah.style.height = tinggiPanel + 'px';
        kananTengah.style.top = (tinggiPanel + 4) + 'px';
        kananTengah.style.left = (lebarPanel + 4) + 'px';
        
        // BARIS BAWAH
        kiriBawah.style.width = lebarPanel + 'px';
        kiriBawah.style.height = tinggiPanel + 'px';
        kiriBawah.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kiriBawah.style.left = '0px';
        
        kananBawah.style.width = lebarPanel + 'px';
        kananBawah.style.height = tinggiPanel + 'px';
        kananBawah.style.top = (tinggiPanel + 4 + tinggiPanel + 4) + 'px';
        kananBawah.style.left = (lebarPanel + 4) + 'px';
        
        // MULLION VERTIKAL
        if (mVertikal) {
            mVertikal.style.left = (kacaWidth / 2) - 2 + 'px';
            mVertikal.style.top = '0px';
            mVertikal.style.height = kacaHeight + 'px';
        }
        
        // MULLION HORIZONTAL ATAS (setelah baris atas)
        if (mHAtas) {
            mHAtas.style.top = (tinggiPanel) + 'px';
            mHAtas.style.left = '0px';
            mHAtas.style.width = kacaWidth + 'px';
        }
        
        // MULLION HORIZONTAL BAWAH (setelah baris tengah)
        if (mHBawah) {
            mHBawah.style.top = (tinggiPanel + 4 + tinggiPanel) + 'px';
            mHBawah.style.left = '0px';
            mHBawah.style.width = kacaWidth + 'px';
        }
        
        // ENGSEL
        if (engselKiri) {
            engselKiri.style.display = 'block';
            engselKiri.style.left = (gap + 2) + 'px';
            engselKiri.style.top = (displayHeight * 0.2) + 'px';
            engselKiri.style.height = (displayHeight * 0.6) + 'px';
        }
    }
}

window.addEventListener('resize', function() {
    hitungSwing_Swing1V2H();
});

function updateWarna_Swing1V2H() {
    let warna = document.getElementById('selectWarna_Swing1V2H').value;
    let kusen = document.getElementById('kusen_Swing1V2H');
    let mVertikal = document.getElementById('mullionVertikal_Swing1V2H');
    let mHAtas = document.getElementById('mullionHAtas_Swing1V2H');
    let mHBawah = document.getElementById('mullionHBawah_Swing1V2H');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    if (kusen) kusen.style.borderColor = warnaKusen;
    if (mVertikal) mVertikal.style.backgroundColor = warnaKusen;
    if (mHAtas) mHAtas.style.backgroundColor = warnaKusen;
    if (mHBawah) mHBawah.style.backgroundColor = warnaKusen;
}

function initJendelaSwing_Swing1V2H() {
    setTimeout(hitungSwing_Swing1V2H, 100);
}

const originalCloseModal_Swing1V2H = window.closeModal;
window.closeModal = function(modalId) {
    if (typeof originalCloseModal_Swing1V2H === 'function') {
        originalCloseModal_Swing1V2H(modalId);
    }
    if (modalId === 'modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal') {
        let inputTinggi = document.getElementById('inputTinggi_Swing1V2H');
        let inputLebar = document.getElementById('inputLebar_Swing1V2H');
        let inputJumlah = document.getElementById('inputJumlah_Swing1V2H');
        if (inputTinggi) inputTinggi.value = '';
        if (inputLebar) inputLebar.value = '';
        if (inputJumlah) inputJumlah.value = 1;
        hitungSwing_Swing1V2H();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(hitungSwing_Swing1V2H, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    hitungSwing_Swing1V2H();
});
</script>