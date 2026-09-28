<!-- Modal Jendela Bouven 4 Kaca -->
<div id="modalJendelaBouven4Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaBouven4Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainerBouven4" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusenBouven4" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun KACA ke-1 (Paling Kiri) -->
                            <div id="daun1Bouven4" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Daun KACA ke-2 (Kiri-Tengah) -->
                            <div id="daun2Bouven4" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Daun KACA ke-3 (Kanan-Tengah) -->
                            <div id="daun3Bouven4" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Daun KACA ke-4 (Paling Kanan) -->
                            <div id="daun4Bouven4" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Coupling 1 (Kiri) -->
                            <div id="coupling1Bouven4" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                            <!-- Coupling 2 (Tengah) -->
                            <div id="coupling2Bouven4" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- Coupling 3 (Kanan) -->
                            <div id="coupling3Bouven4" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI -->
                        <div id="tinggiWrapperBouven4" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggiBouven4" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebarBouven4" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Bouven 4 Kaca</h2>
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
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.bouven4.hitung') }}" method="POST" id="formBouven4">
                        @csrf
                        
                        <div style="margin-bottom: 5px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggiBouven4" placeholder="Contoh: 60 (Max 70)" required min="1" oninput="updateBouven4()">
                            <div id="warningTinggiBouven4" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Tinggi maksimal 70 cm. Gunakan model Jendela Mati 4 Kaca biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 5px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebarBouven4" placeholder="Contoh: 100 (Max 120)" required min="1" oninput="updateBouven4()">
                            <div id="warningLebarBouven4" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Lebar maksimal 120 cm. Gunakan model Jendela Mati 4 Kaca biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlahBouven4" placeholder="Contoh: 2" value="1" min="1" required oninput="updateBouven4()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarnaBouven4" onchange="updateWarnaBouven4()">
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

                        <button type="submit" class="btn-primary" id="submitBouven4">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateBouven4() {
    let tinggi = parseInt(document.getElementById('inputTinggiBouven4').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebarBouven4').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlahBouven4').value) || 1;
    
    // UPDATE ANGKA REALTIME
    document.getElementById('angkaTinggiBouven4').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebarBouven4').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    // VALIDASI BATAS MAKSIMAL
    let warningTinggi = document.getElementById('warningTinggiBouven4');
    let warningLebar = document.getElementById('warningLebarBouven4');
    let btnSubmit = document.getElementById('submitBouven4');
    let isError = false;

    // Cek Tinggi (Max 70)
    if (tinggi > 70) {
        warningTinggi.style.display = 'block';
        isError = true;
    } else {
        warningTinggi.style.display = 'none';
    }

    // Cek Lebar (Max 120)
    if (lebar > 120) {
        warningLebar.style.display = 'block';
        isError = true;
    } else {
        warningLebar.style.display = 'none';
    }

    // Disable tombol jika ada error
    btnSubmit.disabled = isError;
    btnSubmit.style.opacity = isError ? '0.5' : '1';
    btnSubmit.style.cursor = isError ? 'not-allowed' : 'pointer';

    // Update ukuran jendela di animasi (hanya jika dalam batas)
    if (tinggi > 0 && lebar > 0 && tinggi <= 70 && lebar <= 120) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainerBouven4');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusenBouven4');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi 4 panel
        let gap = 4; 
        let totalKaca = displayWidth - (borderThick * 2) - (gap * 2) - 12; // -12 untuk 3 coupling
        let seperempatLebar = totalKaca / 4;
        
        let daun1 = document.getElementById('daun1Bouven4');
        let daun2 = document.getElementById('daun2Bouven4');
        let daun3 = document.getElementById('daun3Bouven4');
        let daun4 = document.getElementById('daun4Bouven4');
        let coupling1 = document.getElementById('coupling1Bouven4');
        let coupling2 = document.getElementById('coupling2Bouven4');
        let coupling3 = document.getElementById('coupling3Bouven4');
        
        let tinggiDaun = displayHeight - (borderThick * 2) - (gap * 2);
        
        // Update Daun 1
        daun1.style.width = seperempatLebar + 'px';
        daun1.style.height = tinggiDaun + 'px';
        daun1.style.top = gap + 'px';
        daun1.style.left = gap + 'px';
        
        // Update Daun 2
        daun2.style.width = seperempatLebar + 'px';
        daun2.style.height = tinggiDaun + 'px';
        daun2.style.top = gap + 'px';
        daun2.style.left = (gap + seperempatLebar + 4 + gap) + 'px';
        
        // Update Daun 3
        daun3.style.width = seperempatLebar + 'px';
        daun3.style.height = tinggiDaun + 'px';
        daun3.style.top = gap + 'px';
        daun3.style.left = (gap + seperempatLebar + 4 + gap + seperempatLebar + 4 + gap) + 'px';
        
        // Update Daun 4
        daun4.style.width = seperempatLebar + 'px';
        daun4.style.height = tinggiDaun + 'px';
        daun4.style.top = gap + 'px';
        daun4.style.left = (gap + seperempatLebar + 4 + gap + seperempatLebar + 4 + gap + seperempatLebar + 4 + gap) + 'px';
        
        // Update Coupling 1 (seperempat)
        coupling1.style.left = (displayWidth / 4) - 6 + 'px';
        coupling1.style.height = displayHeight;
        coupling1.style.top = '0px';
        
        // Update Coupling 2 (setengah)
        coupling2.style.left = (displayWidth / 2) - 6 + 'px';
        coupling2.style.height = displayHeight;
        coupling2.style.top = '0px';
        
        // Update Coupling 3 (tiga perempat)
        coupling3.style.left = ((displayWidth * 3) / 4) - 6 + 'px';
        coupling3.style.height = displayHeight;
        coupling3.style.top = '0px';
    }

    // RESPONSIVE MOBILE
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapperBouven4');
    let angkaLebar = document.getElementById('angkaLebarBouven4');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggiBouven4').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggiBouven4').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateBouven4();
});

function updateWarnaBouven4() {
    let warna = document.getElementById('selectWarnaBouven4').value;
    let kusen = document.getElementById('kusenBouven4');
    let coupling1 = document.getElementById('coupling1Bouven4');
    let coupling2 = document.getElementById('coupling2Bouven4');
    let coupling3 = document.getElementById('coupling3Bouven4');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    coupling1.style.backgroundColor = warnaKusen;
    coupling2.style.backgroundColor = warnaKusen;
    coupling3.style.backgroundColor = warnaKusen;
}

function initJendelaBouven4() {
    setTimeout(updateBouven4, 100);
}

const originalCloseModalBouven4 = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModalBouven4(modalId);
    if (modalId === 'modalJendelaBouven4Kaca') {
        document.getElementById('inputTinggiBouven4').value = '';
        document.getElementById('inputLebarBouven4').value = '';
        document.getElementById('inputJumlahBouven4').value = 1;
        updateBouven4();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaBouven4Kaca');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateBouven4, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaBouven4Kaca');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateBouven4();
});
</script>