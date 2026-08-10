<!-- Modal Jendela Mati 3 Kaca -->
<div id="modalJendelaMati3Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati3Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer3K" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen3K" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun Jendela KIRI -->
                            <div id="daunJendelaKiri3K" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>
                            
                            <!-- Daun Jendela TENGAH -->
                            <div id="daunJendelaTengah3K" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Daun Jendela KANAN -->
                            <div id="daunJendelaKanan3K" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Garis Coupling 1 (Kiri) -->
                            <div id="coupling3K_1" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                            <!-- Garis Coupling 2 (Kanan) -->
                            <div id="coupling3K_2" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper3K" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi3K" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar3K" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 3 Kaca</h2>
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
                                <span>Jendela Mati 3 Kaca adalah jendela <strong>tetap / non-opening</strong> dengan 3 panel kaca yang disatukan dengan <strong>coupling</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati3.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Panjang / Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi3K" placeholder="Contoh: 120" required min="1" oninput="updateJendela3K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar3K" placeholder="Contoh: 80" required min="1" oninput="updateJendela3K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah3K" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela3K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna3K" onchange="updateWarna3K()">
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
function updateJendela3K() {
    let tinggi = parseInt(document.getElementById('inputTinggi3K').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar3K').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah3K').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi3K').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar3K').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer3K');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen3K');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi lebar menjadi 3 panel (3 kaca)
        let gap = 4; 
        
        // LEBAR PANEL
        // Dikurangi: kusen kiri+kanan, gap kiri, gap kanan, 2 coupling (masing-masing 4px)
        let totalKaca = displayWidth - (borderThick * 2) - (gap * 2) - (2 * 4); 
        let sepertigaLebar = totalKaca / 3;
        
        let daunKiri = document.getElementById('daunJendelaKiri3K');
        let daunTengah = document.getElementById('daunJendelaTengah3K');
        let daunKanan = document.getElementById('daunJendelaKanan3K');
        let coupling1 = document.getElementById('coupling3K_1');
        let coupling2 = document.getElementById('coupling3K_2');
        
        let tinggiDaun = (displayHeight - (borderThick * 2) - (gap * 2));
        
        // Update Daun Kiri
        daunKiri.style.width = sepertigaLebar + 'px';
        daunKiri.style.height = tinggiDaun + 'px';
        daunKiri.style.top = gap + 'px';
        daunKiri.style.left = gap + 'px';
        
        // Update Daun Tengah
        daunTengah.style.width = sepertigaLebar + 'px';
        daunTengah.style.height = tinggiDaun + 'px';
        daunTengah.style.top = gap + 'px';
        daunTengah.style.left = (gap + sepertigaLebar + 4 + gap) + 'px'; // Kiri + lebar kiri + coupling1 + gap
        
        // Update Daun Kanan
        daunKanan.style.width = sepertigaLebar + 'px';
        daunKanan.style.height = tinggiDaun + 'px';
        daunKanan.style.top = gap + 'px';
        daunKanan.style.left = (gap + sepertigaLebar + 4 + gap + sepertigaLebar + 4 + gap) + 'px'; // ... + lebar tengah + coupling2 + gap
        
        // Update Posisi Coupling 1 (sepertiga pertama)
        coupling1.style.left = (displayWidth / 3) - 6 + 'px';
        coupling1.style.height = (displayHeight);
        coupling1.style.top = '0px';
        
        // Update Posisi Coupling 2 (dua pertiga)
        coupling2.style.left = ((displayWidth * 2) / 3) - 6 + 'px';
        coupling2.style.height = (displayHeight);
        coupling2.style.top = '0px';
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper3K');
    let angkaLebar = document.getElementById('angkaLebar3K');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi3K').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi3K').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela3K();
});

function updateWarna3K() {
    let warna = document.getElementById('selectWarna3K').value;
    let kusen = document.getElementById('kusen3K');
    let coupling1 = document.getElementById('coupling3K_1');
    let coupling2 = document.getElementById('coupling3K_2');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    coupling1.style.backgroundColor = warnaKusen;
    coupling2.style.backgroundColor = warnaKusen;
}

function initJendelaMati3K() {
    setTimeout(updateJendela3K, 100);
}

const originalCloseModal3K = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal3K(modalId);
    if (modalId === 'modalJendelaMati3Kaca') {
        document.getElementById('inputTinggi3K').value = '';
        document.getElementById('inputLebar3K').value = '';
        document.getElementById('inputJumlah3K').value = 1;
        updateJendela3K();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati3Kaca');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela3K, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati3Kaca');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela3K();
});
</script>