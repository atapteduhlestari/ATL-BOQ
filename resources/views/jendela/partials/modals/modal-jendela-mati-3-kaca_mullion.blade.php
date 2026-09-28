<!-- Modal Jendela Mati 3 Kaca Mullion -->
<div id="modalJendelaMati3KacaMullion" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati3KacaMullion')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer3KMul" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen3KMul" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- ===== KACA KIRI (6 Panel) ===== -->
                            <div id="kiriAtasKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriAtasKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriTengahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriTengahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriBawahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kiriBawahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>

                            <!-- ===== KACA TENGAH (6 Panel) ===== -->
                            <div id="tengahAtasKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="tengahAtasKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="tengahTengahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="tengahTengahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="tengahBawahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="tengahBawahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>

                            <!-- ===== KACA KANAN (6 Panel) ===== -->
                            <div id="kananAtasKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananAtasKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananTengahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananTengahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananBawahKiri3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>
                            <div id="kananBawahKanan3KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;"></div>

                            <!-- ===== GARIS STRUKTUR ===== -->
                            <!-- Coupling 1 (Kiri) -->
                            <div id="coupling1_3KMul" style="position:absolute;top:0;bottom:0;width:4px;background:#555;z-index:2;"></div>
                            <!-- Coupling 2 (Kanan) -->
                            <div id="coupling2_3KMul" style="position:absolute;top:0;bottom:0;width:4px;background:#555;z-index:2;"></div>

                            <!-- Mullion Vertikal KIRI -->
                            <div id="mVKiri3KMul" style="position:absolute;top:0;bottom:0;width:2px;background:#555;z-index:2;"></div>
                            <!-- Mullion Vertikal TENGAH -->
                            <div id="mVTengah3KMul" style="position:absolute;top:0;bottom:0;width:2px;background:#555;z-index:2;"></div>
                            <!-- Mullion Vertikal KANAN -->
                            <div id="mVKanan3KMul" style="position:absolute;top:0;bottom:0;width:2px;background:#555;z-index:2;"></div>

                            <!-- Mullion Horizontal KIRI (Atas & Bawah) -->
                            <div id="mHKiriAtas3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            <div id="mHKiriBawah3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            
                            <!-- Mullion Horizontal TENGAH (Atas & Bawah) -->
                            <div id="mHTengahAtas3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            <div id="mHTengahBawah3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>

                            <!-- Mullion Horizontal KANAN (Atas & Bawah) -->
                            <div id="mHKananAtas3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            <div id="mHKananBawah3KMul" style="position:absolute;height:2px;background:#555;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper3KMul" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi3KMul" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar3KMul" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 3 Kaca Mullion</h2>
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
                                <span>Jendela Mati 3 Kaca Mullion adalah jendela <strong>tetap / non-opening</strong> dengan <strong>3 panel kaca</strong> dan <strong>mullion</strong> sebagai penambah kekuatan struktur</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati3mullion.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi3KMul" placeholder="Contoh: 120" required min="1" oninput="updateJendela3KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar3KMul" placeholder="Contoh: 80" required min="1" oninput="updateJendela3KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah3KMul" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela3KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna3KMul" onchange="updateWarna3KMul()">
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
function updateJendela3KMul() {
    let tinggi = parseInt(document.getElementById('inputTinggi3KMul').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar3KMul').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah3KMul').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi3KMul').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar3KMul').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer3KMul');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen3KMul');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        // --- BAGI LEBAR (3 Kaca Utama) ---
        let ruangBersihLebar = displayWidth - (borderThick * 2) - (gap * 2);
        // Ada 5 garis vertikal: 3 mullion + 2 coupling
        let totalGarisVert = (3 * 4) + (2 * 4); // 20px
        let lebarPerKaca = (ruangBersihLebar - totalGarisVert) / 3;
        let lebarPanel = (lebarPerKaca - 4) / 2; // 1 mullion vertikal per kaca

        // --- BAGI TINGGI (3 Baris) ---
        let ruangBersihTinggi = displayHeight - (borderThick * 2) - (gap * 2);
        // 3 kaca × 2 mullion horizontal = 6 garis horizontal
        let totalGarisHor = 6 * 4; // 24px
        let tinggiPanel = (ruangBersihTinggi - totalGarisHor) / 3;
        
        // --- POSISI X ---
        // Kaca KIRI
        let xKiri = gap;
        let xKiriPanelKiri = xKiri;
        let xMullionKiri = xKiri + lebarPanel;
        let xKiriPanelKanan = xMullionKiri + 4;
        
        // Coupling 1
        let xCoupling1 = xKiri + lebarPerKaca;
        
        // Kaca TENGAH
        let xTengah = xCoupling1 + 4;
        let xTengahPanelKiri = xTengah;
        let xMullionTengah = xTengah + lebarPanel+3;
        let xTengahPanelKanan = xMullionTengah + 4;
        
        // Coupling 2
        let xCoupling2 = xTengah + lebarPerKaca + 6;
        
        // Kaca KANAN
        let xKanan = xCoupling2 + 4;
        let xKananPanelKiri = xKanan;
        let xMullionKanan = xKanan + lebarPanel+6        
        let xKananPanelKanan = xMullionKanan + 4;
        
        // --- POSISI Y ---
        let yAtas = gap + 3;
        let yTengah = yAtas + tinggiPanel + 4;
        let yBawah = yTengah + tinggiPanel + 4;
        
        // --- AMBIL ELEMEN ---
        const get = (id) => document.getElementById(id);
        
        // Panel
        let kiriAtasKiri = get('kiriAtasKiri3KMul');
        let kiriAtasKanan = get('kiriAtasKanan3KMul');
        let kiriTengahKiri = get('kiriTengahKiri3KMul');
        let kiriTengahKanan = get('kiriTengahKanan3KMul');
        let kiriBawahKiri = get('kiriBawahKiri3KMul');
        let kiriBawahKanan = get('kiriBawahKanan3KMul');
        
        let tengahAtasKiri = get('tengahAtasKiri3KMul');
        let tengahAtasKanan = get('tengahAtasKanan3KMul');
        let tengahTengahKiri = get('tengahTengahKiri3KMul');
        let tengahTengahKanan = get('tengahTengahKanan3KMul');
        let tengahBawahKiri = get('tengahBawahKiri3KMul');
        let tengahBawahKanan = get('tengahBawahKanan3KMul');
        
        let kananAtasKiri = get('kananAtasKiri3KMul');
        let kananAtasKanan = get('kananAtasKanan3KMul');
        let kananTengahKiri = get('kananTengahKiri3KMul');
        let kananTengahKanan = get('kananTengahKanan3KMul');
        let kananBawahKiri = get('kananBawahKiri3KMul');
        let kananBawahKanan = get('kananBawahKanan3KMul');
        
        // Garis Vertikal
        let coupling1 = get('coupling1_3KMul');
        let coupling2 = get('coupling2_3KMul');
        let mVKiri = get('mVKiri3KMul');
        let mVTengah = get('mVTengah3KMul');
        let mVKanan = get('mVKanan3KMul');
        
        // Garis Horizontal
        let mHKiriAtas = get('mHKiriAtas3KMul');
        let mHKiriBawah = get('mHKiriBawah3KMul');
        let mHTengahAtas = get('mHTengahAtas3KMul');
        let mHTengahBawah = get('mHTengahBawah3KMul');
        let mHKananAtas = get('mHKananAtas3KMul');
        let mHKananBawah = get('mHKananBawah3KMul');
        
        // --- FUNGSI UPDATE PANEL ---
        function updatePanel(el, x, y, w, h) {
            el.style.width = w + 'px';
            el.style.height = h + 'px';
            el.style.top = y + 'px';
            el.style.left = x + 'px';
        }
        
        // --- UPDATE PANEL KIRI ---
        updatePanel(kiriAtasKiri, xKiriPanelKiri, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kiriAtasKanan, xKiriPanelKanan, yAtas, lebarPanel, tinggiPanel);
        updatePanel(kiriTengahKiri, xKiriPanelKiri, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kiriTengahKanan, xKiriPanelKanan, yTengah, lebarPanel, tinggiPanel);
        updatePanel(kiriBawahKiri, xKiriPanelKiri, yBawah, lebarPanel, tinggiPanel);
        updatePanel(kiriBawahKanan, xKiriPanelKanan, yBawah, lebarPanel, tinggiPanel);
        
        // --- UPDATE PANEL TENGAH ---
        updatePanel(tengahAtasKiri, xTengahPanelKiri, yAtas, lebarPanel, tinggiPanel);
        updatePanel(tengahAtasKanan, xTengahPanelKanan, yAtas, lebarPanel, tinggiPanel);
        updatePanel(tengahTengahKiri, xTengahPanelKiri, yTengah, lebarPanel, tinggiPanel);
        updatePanel(tengahTengahKanan, xTengahPanelKanan, yTengah, lebarPanel, tinggiPanel);
        updatePanel(tengahBawahKiri, xTengahPanelKiri, yBawah, lebarPanel, tinggiPanel);
        updatePanel(tengahBawahKanan, xTengahPanelKanan, yBawah, lebarPanel, tinggiPanel);
        
        // --- UPDATE PANEL KANAN ---
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
        updateVert(coupling1, xCoupling1);
        updateVert(coupling2, xCoupling2);
        updateVert(mVKiri, xMullionKiri);
        updateVert(mVTengah, xMullionTengah);
        updateVert(mVKanan, xMullionKanan);
        
        // --- UPDATE GARIS HORIZONTAL ---
        function updateHorKaca(el, x, y, w) {
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            el.style.width = w + 'px';
        }
       // Tambah lebar 10px, geser kiri 5px (agar sentris)
let offsetLebar = 22;
let offsetX = 5;

updateHorKaca(mHKiriAtas, xKiri - offsetX, yAtas + tinggiPanel, lebarPerKaca + offsetLebar);
updateHorKaca(mHKiriBawah, xKiri - offsetX, yTengah + tinggiPanel, lebarPerKaca + offsetLebar);

updateHorKaca(mHTengahAtas, xTengah - offsetX, yAtas + tinggiPanel, lebarPerKaca + offsetLebar);
updateHorKaca(mHTengahBawah, xTengah - offsetX, yTengah + tinggiPanel, lebarPerKaca + offsetLebar);

updateHorKaca(mHKananAtas, xKanan - offsetX, yAtas + tinggiPanel, lebarPerKaca + offsetLebar);
updateHorKaca(mHKananBawah, xKanan - offsetX, yTengah + tinggiPanel, lebarPerKaca + offsetLebar);
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper3KMul');
    let angkaLebar = document.getElementById('angkaLebar3KMul');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi3KMul').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi3KMul').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela3KMul();
});

function updateWarna3KMul() {
    let warna = document.getElementById('selectWarna3KMul').value;
    let kusen = document.getElementById('kusen3KMul');
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    
    // Semua garis vertikal & horizontal ikut berubah warna
    let garisIDs = [
        'coupling1_3KMul', 'coupling2_3KMul',
        'mVKiri3KMul', 'mVTengah3KMul', 'mVKanan3KMul',
        'mHKiriAtas3KMul', 'mHKiriBawah3KMul',
        'mHTengahAtas3KMul', 'mHTengahBawah3KMul',
        'mHKananAtas3KMul', 'mHKananBawah3KMul'
    ];
    garisIDs.forEach(id => {
        document.getElementById(id).style.backgroundColor = warnaKusen;
    });
}

function initJendelaMati3KMul() {
    setTimeout(updateJendela3KMul, 100);
}

const originalCloseModal3KMul = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal3KMul(modalId);
    if (modalId === 'modalJendelaMati3KacaMullion') {
        document.getElementById('inputTinggi3KMul').value = '';
        document.getElementById('inputLebar3KMul').value = '';
        document.getElementById('inputJumlah3KMul').value = 1;
        updateJendela3KMul();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati3KacaMullion');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela3KMul, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati3KacaMullion');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela3KMul();
});
</script>