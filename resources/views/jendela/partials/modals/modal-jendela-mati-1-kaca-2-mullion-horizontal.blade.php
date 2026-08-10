<!-- Modal Jendela Mati 1 Kaca 2 Mullion Horizontal -->
<div id="modalJendelaMati1Kaca2MullionHorizontal" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati1Kaca2MullionHorizontal')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer1K2MH" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen1K2MH" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- ===== PANEL KACA (3 Baris) ===== -->
                            <!-- Panel ATAS -->
                            <div id="panelAtas1K2MH" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Panel TENGAH -->
                            <div id="panelTengah1K2MH" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Panel BAWAH -->
                            <div id="panelBawah1K2MH" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- ===== GARIS MULLION HORIZONTAL ===== -->
                            <!-- Mullion Horizontal ATAS -->
                            <div id="mullionHorAtas1K2MH" style="position:absolute;left:0;right:0;height:4px;background:#555;z-index:2;transition:all 0.5s ease;"></div>
                            
                            <!-- Mullion Horizontal BAWAH -->
                            <div id="mullionHorBawah1K2MH" style="position:absolute;left:0;right:0;height:4px;background:#555;z-index:2;transition:all 0.5s ease;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper1K2MH" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi1K2MH" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar1K2MH" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 1 Kaca 2 Mullion Horizontal</h2>
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
                    <form action="{{ route('boq.jendela.mati1mullion2horizontal.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="tinggi" id="inputTinggi1K2MH" placeholder="Contoh: 120" required min="1" oninput="updateJendela1K2MH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar1K2MH" placeholder="Contoh: 80" required min="1" oninput="updateJendela1K2MH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah1K2MH" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela1K2MH()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna1K2MH" onchange="updateWarna1K2MH()">
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
function updateJendela1K2MH() {
    let tinggi = parseInt(document.getElementById('inputTinggi1K2MH').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar1K2MH').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah1K2MH').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi1K2MH').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar1K2MH').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer1K2MH');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen1K2MH');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        // --- BAGI TINGGI (3 Baris) ---
        let ruangBersihTinggi = displayHeight - (borderThick * 2) - (gap * 2);
        // Ada 2 garis horizontal: Atas & Bawah = 2 * 4 = 8px
        let totalGarisHor = 8; 
        let tinggiPanel = (ruangBersihTinggi - totalGarisHor) / 3;
        
        // --- POSISI Y ---
        let yAtas = gap;
        let yTengah = yAtas + tinggiPanel + 4;
        let yBawah = yTengah + tinggiPanel + 4;
        
        // --- LEBAR PANEL ---
        let lebarPanel = displayWidth - (borderThick * 2) - (gap * 2);
        
        // --- AMBIL ELEMEN ---
        const get = (id) => document.getElementById(id);
        
        // Panel
        let panelAtas = get('panelAtas1K2MH');
        let panelTengah = get('panelTengah1K2MH');
        let panelBawah = get('panelBawah1K2MH');
        
        // Garis Horizontal
        let mullionAtas = get('mullionHorAtas1K2MH');
        let mullionBawah = get('mullionHorBawah1K2MH');
        
        // --- UPDATE PANEL ---
        panelAtas.style.width = lebarPanel + 'px';
        panelAtas.style.height = tinggiPanel + 'px';
        panelAtas.style.top = yAtas + 'px';
        panelAtas.style.left = gap + 'px';
        
        panelTengah.style.width = lebarPanel + 'px';
        panelTengah.style.height = tinggiPanel + 'px';
        panelTengah.style.top = yTengah + 'px';
        panelTengah.style.left = gap + 'px';
        
        panelBawah.style.width = lebarPanel + 'px';
        panelBawah.style.height = tinggiPanel + 'px';
        panelBawah.style.top = yBawah + 'px';
        panelBawah.style.left = gap + 'px';
        
        // --- UPDATE GARIS HORIZONTAL ---
        // Garis membentang penuh dari ujung kiri ke ujung kanan
        mullionAtas.style.top = (yAtas + tinggiPanel) + 'px';
        mullionAtas.style.left = '0px';
        mullionAtas.style.width = displayWidth;
        
        mullionBawah.style.top = (yTengah + tinggiPanel) + 'px';
        mullionBawah.style.left = '0px';
        mullionBawah.style.width = displayWidth;
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper1K2MH');
    let angkaLebar = document.getElementById('angkaLebar1K2MH');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi1K2MH').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi1K2MH').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela1K2MH();
});

function updateWarna1K2MH() {
    let warna = document.getElementById('selectWarna1K2MH').value;
    let kusen = document.getElementById('kusen1K2MH');
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    
    // Semua garis horizontal ikut berubah warna
    let garisIDs = [
        'mullionHorAtas1K2MH',
        'mullionHorBawah1K2MH'
    ];
    garisIDs.forEach(id => {
        document.getElementById(id).style.backgroundColor = warnaKusen;
    });
}

function initJendelaMati1K2MH() {
    setTimeout(updateJendela1K2MH, 100);
}

const originalCloseModal1K2MH = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal1K2MH(modalId);
    if (modalId === 'modalJendelaMati1Kaca2MullionHorizontal') {
        document.getElementById('inputTinggi1K2MH').value = '';
        document.getElementById('inputLebar1K2MH').value = '';
        document.getElementById('inputJumlah1K2MH').value = 1;
        updateJendela1K2MH();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati1Kaca2MullionHorizontal');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela1K2MH, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati1Kaca2MullionHorizontal');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela1K2MH();
});
</script>