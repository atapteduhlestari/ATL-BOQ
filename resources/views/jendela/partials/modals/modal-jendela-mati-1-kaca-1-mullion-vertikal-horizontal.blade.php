<!-- Modal Jendela Mati 1 Kaca 1 Mullion Vertikal dan Horizontal -->
<div id="modalJendelaMati1Kaca1MullionVertikalHorizontal" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati1Kaca1MullionVertikalHorizontal')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer1KM" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen1KM" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun Jendela KIRI ATAS -->
                            <div id="daunKiriAtas1KM" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>
                            
                            <!-- Daun Jendela KANAN ATAS -->
                            <div id="daunKananAtas1KM" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Daun Jendela KIRI BAWAH -->
                            <div id="daunKiriBawah1KM" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Daun Jendela KANAN BAWAH -->
                            <div id="daunKananBawah1KM" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Garis Mullion VERTIKAL (Tengah) -->
                            <div id="mullionVertikal1KM" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                            <!-- Garis Mullion HORIZONTAL (Tengah) -->
                            <div id="mullionHorizontal1KM" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper1KM" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi1KM" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar1KM" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 1 Kaca 1 Mullion Vertikal dan Horizontal</h2>
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
                                <span>Jendela Mati 1 Kaca Mullion adalah jendela <strong>tetap / non-opening</strong> dengan <strong>1 panel kaca</strong> dan <strong>mullion</strong> sebagai penambah kekuatan struktur</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati1mullion1vertikalhorizontal.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi1KM" placeholder="Contoh: 120" required min="1" oninput="updateJendela1KM()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar1KM" placeholder="Contoh: 80" required min="1" oninput="updateJendela1KM()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah1KM" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela1KM()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna1KM" onchange="updateWarna1KM()">
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
function updateJendela1KM() {
    let tinggi = parseInt(document.getElementById('inputTinggi1KM').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar1KM').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah1KM').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi1KM').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar1KM').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer1KM');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen1KM');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi lebar menjadi 2 kolom (kiri & kanan), dan tinggi menjadi 2 baris (atas & bawah)
        let gap = 4; 
        
        // Hitung lebar 1 panel kaca (kiri/kanan)
        let totalLebarKaca = displayWidth - (borderThick * 2) - (gap * 2) - 4; // 4 = lebar mullion vertikal
        let lebarPanel = totalLebarKaca / 2;
        
        // Hitung tinggi 1 panel kaca (atas/bawah)
        let totalTinggiKaca = displayHeight - (borderThick * 2) - (gap * 2) - 4; // 4 = lebar mullion horizontal
        let tinggiPanel = totalTinggiKaca / 2;
        
        let daunKiriAtas = document.getElementById('daunKiriAtas1KM');
        let daunKananAtas = document.getElementById('daunKananAtas1KM');
        let daunKiriBawah = document.getElementById('daunKiriBawah1KM');
        let daunKananBawah = document.getElementById('daunKananBawah1KM');
        let mullionVertikal = document.getElementById('mullionVertikal1KM');
        let mullionHorizontal = document.getElementById('mullionHorizontal1KM');
        
        // Update Daun KIRI ATAS
        daunKiriAtas.style.width = lebarPanel + 'px';
        daunKiriAtas.style.height = tinggiPanel + 'px';
        daunKiriAtas.style.top = gap + 'px';
        daunKiriAtas.style.left = gap + 'px';
        
        // Update Daun KANAN ATAS
        daunKananAtas.style.width = lebarPanel + 'px';
        daunKananAtas.style.height = tinggiPanel + 'px';
        daunKananAtas.style.top = gap + 'px';
        daunKananAtas.style.left = (gap + lebarPanel + 4 + gap) + 'px'; // kiri + lebar kiri + mullion + gap
        
        // Update Daun KIRI BAWAH
        daunKiriBawah.style.width = lebarPanel + 'px';
        daunKiriBawah.style.height = tinggiPanel + 'px';
        daunKiriBawah.style.top = (gap + tinggiPanel + 4 + gap) + 'px'; // atas + tinggi atas + mullion + gap
        daunKiriBawah.style.left = gap + 'px';
        
        // Update Daun KANAN BAWAH
        daunKananBawah.style.width = lebarPanel + 'px';
        daunKananBawah.style.height = tinggiPanel + 'px';
        daunKananBawah.style.top = (gap + tinggiPanel + 4 + gap) + 'px';
        daunKananBawah.style.left = (gap + lebarPanel + 4 + gap) + 'px';
        
        // Update Posisi Mullion VERTIKAL (Tengah-tengah lebar)
        mullionVertikal.style.left = (displayWidth / 2) - 6 + 'px';
        mullionVertikal.style.height = (displayHeight);
        mullionVertikal.style.top = '0px';
        
        // Update Posisi Mullion HORIZONTAL (Tengah-tengah tinggi)
        mullionHorizontal.style.top = (displayHeight / 2) - 6 + 'px';
        mullionHorizontal.style.width = (displayWidth);
        mullionHorizontal.style.left = '0px';
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper1KM');
    let angkaLebar = document.getElementById('angkaLebar1KM');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi1KM').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi1KM').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela1KM();
});

function updateWarna1KM() {
    let warna = document.getElementById('selectWarna1KM').value;
    let kusen = document.getElementById('kusen1KM');
    let mullionVertikal = document.getElementById('mullionVertikal1KM');
    let mullionHorizontal = document.getElementById('mullionHorizontal1KM');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    mullionVertikal.style.backgroundColor = warnaKusen;
    mullionHorizontal.style.backgroundColor = warnaKusen;
}

function initJendelaMati1KM() {
    setTimeout(updateJendela1KM, 100);
}

const originalCloseModal1KM = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal1KM(modalId);
    if (modalId === 'modalJendelaMati1Kaca1MullionVertikalHorizontal') {
        document.getElementById('inputTinggi1KM').value = '';
        document.getElementById('inputLebar1KM').value = '';
        document.getElementById('inputJumlah1KM').value = 1;
        updateJendela1KM();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati1Kaca1MullionVertikalHorizontal');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela1KM, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati1Kaca1MullionVertikalHorizontal');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela1KM();
});
</script>