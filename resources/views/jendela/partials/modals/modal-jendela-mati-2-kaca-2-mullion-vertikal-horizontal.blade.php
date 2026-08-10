<!-- Modal Jendela Mati 2 Kaca 2 Mullion Vertikal dan Horizontal -->
<div id="modalJendelaMati2Kaca2MullionVertikalHorizontal" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati2Kaca2MullionVertikalHorizontal')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer2K2MVH" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen2K2MVH" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- ===== KACA KIRI (6 Panel) ===== -->
                            <div id="kiriAtasKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriAtasKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriTengahKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriTengahKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriBawahKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriBawahKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>

                            <!-- ===== KACA KANAN (6 Panel) ===== -->
                            <div id="kananAtasKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananAtasKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananTengahKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananTengahKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananBawahKiri" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananBawahKanan" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>

                            <!-- ===== GARIS STRUKTUR ===== -->
                            <!-- Coupling di TENGAH -->
                            <div id="couplingTengah" style="position:absolute;top:0;bottom:0;width:4px;background:#555;z-index:2;"></div>

                            <!-- Mullion Vertikal KIRI -->
                            <div id="mVKiri" style="position:absolute;top:0;bottom:0;width:2px;background:#555;z-index:2;"></div>
                            <!-- Mullion Vertikal KANAN -->
                            <div id="mVKanan" style="position:absolute;top:0;bottom:0;width:2px;background:#555;z-index:2;"></div>

                            <!-- Mullion Horizontal KIRI (Atas & Bawah) -->
                            <div id="mHKiriAtas" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            <div id="mHKiriBawah" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            
                            <!-- Mullion Horizontal KANAN (Atas & Bawah) -->
                            <div id="mHKananAtas" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            <div id="mHKananBawah" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper2K2MVH" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi2K2MVH" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar2K2MVH" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 2 Kaca 2 Mullion Vertikal dan Horizontal</h2>
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
                                <span>Minimum ketebalan kaca yang digunakan adalah <strong>5 mm</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Ketebalan profile standar: <strong>Casement Series 60</strong> dan <strong>Sliding Series 60 / Series 88</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Jendela Mati 1 Kaca Mullion adalah jendela <strong>tetap / non-opening</strong> dengan <strong>1 panel kaca</strong> dan <strong>mullion</strong> sebagai penambah kekuatan struktur</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati2mullion2vertikalhorizontal.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="tinggi" id="inputTinggi2K2MVH" placeholder="Contoh: 120" required min="1" oninput="updateJendela2K2MVH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar2K2MVH" placeholder="Contoh: 80" required min="1" oninput="updateJendela2K2MVH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah2K2MVH" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela2K2MVH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna2K2MVH" onchange="updateWarna2K2MVH()">
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

                        <button type="submit" class="btn-primary">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateJendela2K2MVH() {
    let tinggi = parseInt(document.getElementById('inputTinggi2K2MVH').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar2K2MVH').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah2K2MVH').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi2K2MVH').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar2K2MVH').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer2K2MVH');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen2K2MVH');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        // --- BAGI LEBAR (2 Kaca Utama) ---
        let ruangBersihLebar = displayWidth - (borderThick * 2) - (gap * 2);
        // Ada 3 garis vertikal: mVKiri, coupling, mVKanan = 3 * 4 = 12px
        let totalGarisVert = 12; 
        let lebarPerKaca = (ruangBersihLebar - totalGarisVert) / 2;
        let lebarPanel = (lebarPerKaca - 4) / 2; // 1 mullion vertikal per kaca

        // --- BAGI TINGGI (3 Baris) ---
        let ruangBersihTinggi = displayHeight - (borderThick * 2) - (gap * 2);
        // 2 kaca × 2 mullion horizontal = 4 garis horizontal = 16px
        let totalGarisHor = 16; 
        let tinggiPanel = (ruangBersihTinggi - totalGarisHor) / 3;
        
        // --- POSISI X ---
        // Kaca KIRI
        let xKiri = gap;
        let xKiriPanelKiri = xKiri;
        let xMullionKiri = xKiri + lebarPanel;
        let xKiriPanelKanan = xMullionKiri + 4;
        
        // Coupling
        let xCoupling = xKiri + lebarPerKaca;
        
        // Kaca KANAN
        let xKanan = xCoupling + 4;
        let xKananPanelKiri = xKanan;
        let xMullionKanan = xKanan + lebarPanel + 5;
        let xKananPanelKanan = xMullionKanan + 4;
        
        // --- POSISI Y ---
        let yAtas = gap;
        let yTengah = yAtas + tinggiPanel + 4;
        let yBawah = yTengah + tinggiPanel + 4;
        
        // --- AMBIL ELEMEN ---
        const get = (id) => document.getElementById(id);
        
        // Panel KIRI
        let kiriAtasKiri = get('kiriAtasKiri');
        let kiriAtasKanan = get('kiriAtasKanan');
        let kiriTengahKiri = get('kiriTengahKiri');
        let kiriTengahKanan = get('kiriTengahKanan');
        let kiriBawahKiri = get('kiriBawahKiri');
        let kiriBawahKanan = get('kiriBawahKanan');
        
        // Panel KANAN
        let kananAtasKiri = get('kananAtasKiri');
        let kananAtasKanan = get('kananAtasKanan');
        let kananTengahKiri = get('kananTengahKiri');
        let kananTengahKanan = get('kananTengahKanan');
        let kananBawahKiri = get('kananBawahKiri');
        let kananBawahKanan = get('kananBawahKanan');
        
        // Garis Vertikal
        let coupling = get('couplingTengah');
        let mVKiri = get('mVKiri');
        let mVKanan = get('mVKanan');
        
        // Garis Horizontal
        let mHKiriAtas = get('mHKiriAtas');
        let mHKiriBawah = get('mHKiriBawah');
        let mHKananAtas = get('mHKananAtas');
        let mHKananBawah = get('mHKananBawah');
        
        // --- UPDATE PANEL ---
        function updatePanel(el, x, y, w, h) {
            el.style.width = w + 'px';
            el.style.height = h + 'px';
            el.style.top = y + 'px';
            el.style.left = x + 'px';
        }
        
        // KIRI
        updatePanel(kiriAtasKiri, xKiriPanelKiri, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kiriAtasKanan, xKiriPanelKanan, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kiriTengahKiri, xKiriPanelKiri, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kiriTengahKanan, xKiriPanelKanan, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kiriBawahKiri, xKiriPanelKiri, yBawah, lebarPanel, tinggiPanel);
        updatePanel(kiriBawahKanan, xKiriPanelKanan, yBawah, lebarPanel, tinggiPanel);
        
        // KANAN
        updatePanel(kananAtasKiri, xKananPanelKiri, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kananAtasKanan, xKananPanelKanan, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kananTengahKiri, xKananPanelKiri, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kananTengahKanan, xKananPanelKanan, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kananBawahKiri, xKananPanelKiri, yBawah, lebarPanel, tinggiPanel);
        updatePanel(kananBawahKanan, xKananPanelKanan, yBawah, lebarPanel, tinggiPanel);
        
        // --- UPDATE GARIS VERTIKAL ---
        function updateVert(el, x) {
            el.style.left = x + 'px';
            el.style.top = '0px';
            el.style.height = displayHeight;
        }
        updateVert(coupling, xCoupling);
        updateVert(mVKiri, xMullionKiri);
        updateVert(mVKanan, xMullionKanan);
        
        // --- UPDATE GARIS HORIZONTAL ---
        function updateHorKaca(el, x, y, w) {
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            el.style.width = w + 'px';
        }
        let offsetLebar = 22;
let offsetX = 5;
        // KIRI
        updateHorKaca(mHKiriAtas, xKiri - offsetX, yAtas + tinggiPanel, lebarPerKaca + offsetLebar);
        updateHorKaca(mHKiriBawah, xKiri - offsetX, yTengah + tinggiPanel, lebarPerKaca + offsetLebar);
        // KANAN
        updateHorKaca(mHKananAtas, xKanan - offsetX, yAtas + tinggiPanel, lebarPerKaca + offsetLebar);
        updateHorKaca(mHKananBawah, xKanan - offsetX, yTengah + tinggiPanel, lebarPerKaca + offsetLebar);
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper2K2MVH');
    let angkaLebar = document.getElementById('angkaLebar2K2MVH');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi2K2MVH').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi2K2MVH').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela2K2MVH();
});

function updateWarna2K2MVH() {
    let warna = document.getElementById('selectWarna2K2MVH').value;
    let kusen = document.getElementById('kusen2K2MVH');
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    
    // Semua garis vertikal & horizontal ikut berubah warna
    let garisIDs = [
        'couplingTengah',
        'mVKiri', 'mVKanan',
        'mHKiriAtas', 'mHKiriBawah',
        'mHKananAtas', 'mHKananBawah'
    ];
    garisIDs.forEach(id => {
        document.getElementById(id).style.backgroundColor = warnaKusen;
    });
}

function initJendelaMati2K2MVH() {
    setTimeout(updateJendela2K2MVH, 100);
}

const originalCloseModal2K2MVH = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal2K2MVH(modalId);
    if (modalId === 'modalJendelaMati2Kaca2MullionVertikalHorizontal') {
        document.getElementById('inputTinggi2K2MVH').value = '';
        document.getElementById('inputLebar2K2MVH').value = '';
        document.getElementById('inputJumlah2K2MVH').value = 1;
        updateJendela2K2MVH();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati2Kaca2MullionVertikalHorizontal');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela2K2MVH, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati2Kaca2MullionVertikalHorizontal');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela2K2MVH();
});
</script>