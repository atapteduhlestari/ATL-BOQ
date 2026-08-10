<!-- Modal Jendela Mati 2 Kaca -->
<div id="modalJendelaMati2Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati2Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer2K" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen2K" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- Daun Jendela KIRI -->
                            <div id="daunJendelaKiri2K" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>
                            
                            <!-- Daun Jendela KANAN -->
                            <div id="daunJendelaKanan2K" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>

                            <!-- Garis Coupling di Tengah -->
                            <div id="coupling2K" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper2K" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi2K" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar2K" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 2 Kaca</h2>
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
                                <span>Jendela Mati 2 Kaca adalah jendela <strong>tetap / non-opening</strong> dengan 2 panel kaca yang disatukan dengan <strong>coupling</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati2.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Panjang / Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi2K" placeholder="Contoh: 120" required min="1" oninput="updateJendela2K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar2K" placeholder="Contoh: 80" required min="1" oninput="updateJendela2K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah2K" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela2K()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna2K" onchange="updateWarna2K()">
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
function updateJendela2K() {
    let tinggi = parseInt(document.getElementById('inputTinggi2K').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar2K').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah2K').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi2K').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar2K').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer2K');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen2K');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // Bagi lebar menjadi 2 panel (2 kaca)
        let gap = 4; 
        
        // LEBAR PANEL KIRI & KANAN
        let totalKaca = displayWidth - (borderThick * 2) - (gap * 2) - 4; 
        let setengahLebar = totalKaca / 2;
        
        let daunKiri = document.getElementById('daunJendelaKiri2K');
        let daunKanan = document.getElementById('daunJendelaKanan2K');
        let coupling = document.getElementById('coupling2K');
        
        // Update Daun Kiri
        daunKiri.style.width = setengahLebar + 'px';
        daunKiri.style.height = (displayHeight - (borderThick * 2) - (gap * 2)) + 'px';
        daunKiri.style.top = gap + 'px';
        daunKiri.style.left = gap + 'px';
        
        // Update Daun Kanan
        daunKanan.style.width = setengahLebar + 'px';
        daunKanan.style.height = (displayHeight - (borderThick * 2) - (gap * 2)) + 'px';
        daunKanan.style.top = gap + 'px';
        // Posisi daun kanan = gap kiri + lebar daun kiri + tebal coupling + gap kanan
        daunKanan.style.left = (gap + setengahLebar + 4 + gap) + 'px';
        
        // Update Posisi Coupling 
        // Titik tengah layar adalah displayWidth / 2. Dikurangi 2 agar 4px terbagi rata.
        coupling.style.left = (displayWidth / 2) - 6 + 'px';
        coupling.style.height = (displayHeight);
        coupling.style.top = '0px';
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper2K');
    let angkaLebar = document.getElementById('angkaLebar2K');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi2K').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi2K').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela2K();
});

function updateWarna2K() {
    let warna = document.getElementById('selectWarna2K').value;
    let kusen = document.getElementById('kusen2K');
    let coupling = document.getElementById('coupling2K');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    // Update warna kusen DAN coupling sekaligus
    kusen.style.borderColor = warnaKusen;
    coupling.style.backgroundColor = warnaKusen;
}

function initJendelaMati2K() {
    setTimeout(updateJendela2K, 100);
}

const originalCloseModal2K = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal2K(modalId);
    if (modalId === 'modalJendelaMati2Kaca') {
        document.getElementById('inputTinggi2K').value = '';
        document.getElementById('inputLebar2K').value = '';
        document.getElementById('inputJumlah2K').value = 1;
        updateJendela2K();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati2Kaca');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela2K, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati2Kaca');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela2K();
});
</script>