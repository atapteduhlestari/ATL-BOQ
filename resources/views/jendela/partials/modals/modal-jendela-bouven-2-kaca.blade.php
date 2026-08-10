<!-- Modal Jendela Bouven 2 Kaca -->
<div id="modalJendelaBouven2Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaBouven2Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainerBouven2" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusenBouven2" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun Jendela KIRI -->
                            <div id="daunKiriBouven2" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            
                            <!-- Daun Jendela KANAN -->
                            <div id="daunKananBouven2" style="position:absolute;border:2px solid transparent;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- Garis Coupling di Tengah -->
                            <div id="couplingBouven2" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI -->
                        <div id="tinggiWrapperBouven2" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggiBouven2" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebarBouven2" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Bouven 2 Kaca</h2>
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
                                <span>Lebar yang di input adalah lebar secara keseluruhan</span>
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
                    <form action="{{ route('boq.jendela.bouven2.hitung') }}" method="POST" id="formBouven2">
                        @csrf
                        
                        <div style="margin-bottom: 5px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="tinggi" id="inputTinggiBouven2" placeholder="Contoh: 60 (Max 70)" required min="1" oninput="updateBouven2()">
                            <div id="warningTinggiBouven2" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Tinggi maksimal 70 cm. Gunakan model Jendela Mati 2 Kaca biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 5px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebarBouven2" placeholder="Contoh: 100 (Max 240)" required min="1" oninput="updateBouven2()">
                            <div id="warningLebarBouven2" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Lebar maksimal 240 cm (2 panel × 120 cm). Gunakan model Jendela Mati 2 Kaca biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlahBouven2" placeholder="Contoh: 2" value="1" min="1" required oninput="updateBouven2()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarnaBouven2" onchange="updateWarnaBouven2()">
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

                        <button type="submit" class="btn-primary" id="submitBouven2">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateBouven2() {
    let tinggi = parseInt(document.getElementById('inputTinggiBouven2').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebarBouven2').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlahBouven2').value) || 1;
    
    // UPDATE ANGKA REALTIME
    document.getElementById('angkaTinggiBouven2').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebarBouven2').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    // VALIDASI BATAS MAKSIMAL
    let warningTinggi = document.getElementById('warningTinggiBouven2');
    let warningLebar = document.getElementById('warningLebarBouven2');
    let btnSubmit = document.getElementById('submitBouven2');
    let isError = false;

    // Cek Tinggi (Max 70)
    if (tinggi > 70) {
        warningTinggi.style.display = 'block';
        isError = true;
    } else {
        warningTinggi.style.display = 'none';
    }

    // Cek Lebar (Max 240, 2 panel × 120 cm)
    if (lebar > 240) {
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
    if (tinggi > 0 && lebar > 0 && tinggi <= 70 && lebar <= 240) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainerBouven2');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusenBouven2');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi 2 panel
        let gap = 4; 
        let totalKaca = displayWidth - (borderThick * 2) - (gap * 2) - 4;
        let setengahLebar = totalKaca / 2;
        
        let daunKiri = document.getElementById('daunKiriBouven2');
        let daunKanan = document.getElementById('daunKananBouven2');
        let coupling = document.getElementById('couplingBouven2');
        
        // Update Daun Kiri
        daunKiri.style.width = setengahLebar + 'px';
        daunKiri.style.height = (displayHeight - (borderThick * 2) - (gap * 2)) + 'px';
        daunKiri.style.top = gap + 'px';
        daunKiri.style.left = gap + 'px';
        
        // Update Daun Kanan
        daunKanan.style.width = setengahLebar + 'px';
        daunKanan.style.height = (displayHeight - (borderThick * 2) - (gap * 2)) + 'px';
        daunKanan.style.top = gap + 'px';
        daunKanan.style.left = (gap + setengahLebar + 4 + gap) + 'px';
        
        // Update Coupling
        coupling.style.left = (displayWidth / 2) - 6+ 'px';
        coupling.style.height = (displayHeight);
        coupling.style.top = '0px';
    }

    // RESPONSIVE MOBILE
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapperBouven2');
    let angkaLebar = document.getElementById('angkaLebarBouven2');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggiBouven2').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggiBouven2').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateBouven2();
});

function updateWarnaBouven2() {
    let warna = document.getElementById('selectWarnaBouven2').value;
    let kusen = document.getElementById('kusenBouven2');
    let coupling = document.getElementById('couplingBouven2');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    coupling.style.backgroundColor = warnaKusen;
}

function initJendelaBouven2() {
    setTimeout(updateBouven2, 100);
}

const originalCloseModalBouven2 = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModalBouven2(modalId);
    if (modalId === 'modalJendelaBouven2Kaca') {
        document.getElementById('inputTinggiBouven2').value = '';
        document.getElementById('inputLebarBouven2').value = '';
        document.getElementById('inputJumlahBouven2').value = 1;
        updateBouven2();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaBouven2Kaca');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateBouven2, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaBouven2Kaca');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateBouven2();
});
</script>