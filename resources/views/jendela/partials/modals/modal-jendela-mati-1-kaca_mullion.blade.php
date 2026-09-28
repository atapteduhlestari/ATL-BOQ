<!-- Modal Jendela Mati 1 Kaca Mullion (1 Vertikal + 2 Horizontal) -->
<div id="modalJendelaMati1KacaMullion" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati1KacaMullion')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer1KMul" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen1KMul" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun KIRI ATAS -->
                            <div id="daunKiriAtas1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Daun KANAN ATAS -->
                            <div id="daunKananAtas1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Daun KIRI TENGAH -->
                            <div id="daunKiriTengah1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Daun KANAN TENGAH -->
                            <div id="daunKananTengah1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Daun KIRI BAWAH -->
                            <div id="daunKiriBawah1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Daun KANAN BAWAH -->
                            <div id="daunKananBawah1KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Garis Mullion VERTIKAL (Tengah) -->
                            <div id="mullionVertikal1KMul" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                            <!-- Garis Mullion HORIZONTAL ATAS -->
                            <div id="mullionHorizontalAtas1KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- Garis Mullion HORIZONTAL BAWAH -->
                            <div id="mullionHorizontalBawah1KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper1KMul" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi1KMul" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar1KMul" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 1 Kaca Mullion</h2>
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
                    <form action="{{ route('boq.jendela.mati1mullion.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi1KMul" placeholder="Contoh: 120" required min="1" oninput="updateJendela1KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar1KMul" placeholder="Contoh: 80" required min="1" oninput="updateJendela1KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah1KMul" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela1KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna1KMul" onchange="updateWarna1KMul()">
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
function updateJendela1KMul() {
    let tinggi = parseInt(document.getElementById('inputTinggi1KMul').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar1KMul').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah1KMul').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi1KMul').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar1KMul').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer1KMul');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen1KMul');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi lebar menjadi 2 kolom, tinggi menjadi 3 baris
        let gap = 4; 
        
        let totalLebarKaca = displayWidth - (borderThick * 2) - (gap * 2) - 4; // -4 = 1 mullion vertikal
        let lebarPanel = totalLebarKaca / 2;
        
        let totalTinggiKaca = displayHeight - (borderThick * 2) - (gap * 2) - 8; // -8 = 2 mullion horizontal
        let tinggiPanel = totalTinggiKaca / 3;
        
        // Ambil semua elemen
        let kiriAtas = document.getElementById('daunKiriAtas1KMul');
        let kananAtas = document.getElementById('daunKananAtas1KMul');
        let kiriTengah = document.getElementById('daunKiriTengah1KMul');
        let kananTengah = document.getElementById('daunKananTengah1KMul');
        let kiriBawah = document.getElementById('daunKiriBawah1KMul');
        let kananBawah = document.getElementById('daunKananBawah1KMul');
        let mullionVertikal = document.getElementById('mullionVertikal1KMul');
        let mullionHAtas = document.getElementById('mullionHorizontalAtas1KMul');
        let mullionHBawah = document.getElementById('mullionHorizontalBawah1KMul');
        
        // Fungsi helper untuk update panel
        function updatePanel(el, top, left) {
            el.style.width = lebarPanel + 'px';
            el.style.height = tinggiPanel + 'px';
            el.style.top = top + 'px';
            el.style.left = left + 'px';
        }
        
        // Baris ATAS
        let topRow = gap;
        let leftCol1 = gap;
        let leftCol2 = gap + lebarPanel + 4 + gap;
        updatePanel(kiriAtas, topRow, leftCol1);
        updatePanel(kananAtas, topRow, leftCol2);
        
        // Baris TENGAH
        let topRow2 = gap + tinggiPanel + 4 + gap;
        updatePanel(kiriTengah, topRow2, leftCol1);
        updatePanel(kananTengah, topRow2, leftCol2);
        
        // Baris BAWAH
        let topRow3 = gap + (tinggiPanel * 2) + 8 + (gap * 2);
        updatePanel(kiriBawah, topRow3, leftCol1);
        updatePanel(kananBawah, topRow3, leftCol2);
        
        // Mullion Vertikal
        mullionVertikal.style.left = (displayWidth / 2) - 6 + 'px';
        mullionVertikal.style.height = displayHeight;
        mullionVertikal.style.top = '0px';
        
        // Mullion Horizontal Atas (sepertiga pertama)
        mullionHAtas.style.top = (displayHeight / 3) - 6 + 'px';
        mullionHAtas.style.width = displayWidth;
        mullionHAtas.style.left = '0px';
        
        // Mullion Horizontal Bawah (dua pertiga)
        mullionHBawah.style.top = ((displayHeight * 2) / 3) - 6 + 'px';
        mullionHBawah.style.width = displayWidth;
        mullionHBawah.style.left = '0px';
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper1KMul');
    let angkaLebar = document.getElementById('angkaLebar1KMul');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi1KMul').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi1KMul').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela1KMul();
});

function updateWarna1KMul() {
    let warna = document.getElementById('selectWarna1KMul').value;
    let kusen = document.getElementById('kusen1KMul');
    let mVertikal = document.getElementById('mullionVertikal1KMul');
    let mHAtas = document.getElementById('mullionHorizontalAtas1KMul');
    let mHBawah = document.getElementById('mullionHorizontalBawah1KMul');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    mVertikal.style.backgroundColor = warnaKusen;
    mHAtas.style.backgroundColor = warnaKusen;
    mHBawah.style.backgroundColor = warnaKusen;
}

function initJendelaMati1KMul() {
    setTimeout(updateJendela1KMul, 100);
}

const originalCloseModal1KMul = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal1KMul(modalId);
    if (modalId === 'modalJendelaMati1KacaMullion') {
        document.getElementById('inputTinggi1KMul').value = '';
        document.getElementById('inputLebar1KMul').value = '';
        document.getElementById('inputJumlah1KMul').value = 1;
        updateJendela1KMul();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati1KacaMullion');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela1KMul, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati1KacaMullion');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela1KMul();
});
</script>