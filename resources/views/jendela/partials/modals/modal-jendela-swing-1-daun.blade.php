<!-- Modal Jendela Swing 1 Daun -->
<div id="modalJendelaSwing1Daun" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaSwing1Daun')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainerSwing1" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusenSwing1" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- WRAPPER DAUN JENDELA (Untuk Animasi Buka/Tutup) -->
                            <div id="daunWrapperSwing1" style="position:absolute;transform-origin:left center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                
                                <!-- LAPISAN KACA UTUH (DAUN KACA) -->
                                <!-- Ini background kaca biru transparan yang ada gap dari kusen -->
                                <div id="daunKacaSwing1" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>

                            </div>

                            <!-- Indikator Engsel (KIRI) -->
                            <div id="engselKiriSwing1" style="position:absolute;top:0;bottom:0;width:0;border-left:3px dashed #aaa;z-index:2;display:none;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI -->
                        <div id="tinggiWrapperSwing1" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggiSwing1" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebarSwing1" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP (Sekarang ada di bawah, tidak menimpa) -->
                    <div id="tombolWrapperSwing" style="margin-top:50px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleSwingBtn" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;" onclick="toggleSwing()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusSwing" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Swing 1 Daun</h2>
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
                    <form action="{{ route('boq.jendela.swing1.hitung') }}" method="POST" id="formSwing1">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggiSwing1" placeholder="Contoh: 120" required min="1" oninput="updateSwing1()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebarSwing1" placeholder="Contoh: 80" required min="1" oninput="updateSwing1()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlahSwing1" placeholder="Contoh: 2" value="1" min="1" required oninput="updateSwing1()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarnaSwing1" onchange="updateWarnaSwing1()">
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

                        <button type="submit" class="btn-primary" id="submitSwing1">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isSwingOpen = false;

function toggleSwing() {
    isSwingOpen = !isSwingOpen;
    let wrapper = document.getElementById('daunWrapperSwing1');
    let btn = document.getElementById('toggleSwingBtn');
    let status = document.getElementById('statusSwing');

    if (isSwingOpen) {
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

function updateSwing1() {
    let tinggi = parseInt(document.getElementById('inputTinggiSwing1').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebarSwing1').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlahSwing1').value) || 1;
    
    // UPDATE ANGKA REALTIME
    document.getElementById('angkaTinggiSwing1').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebarSwing1').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainerSwing1');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusenSwing1');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        // GAP / JARAK KACA DARI KUSEN
        let gap = 4; 
        
        // UKURAN KACA UTUH (DAUN JENDELA) - ADA GAP DARI KUSEN
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        let wrapper = document.getElementById('daunWrapperSwing1');
        let daunKaca = document.getElementById('daunKacaSwing1');
        let engselKiri = document.getElementById('engselKiriSwing1');
        
        // POSISI WRAPPER KACA
        wrapper.style.width = kacaWidth + 'px';
        wrapper.style.height = kacaHeight + 'px';
        wrapper.style.top = gap + 'px';
        wrapper.style.left = gap + 'px';
        wrapper.style.transformOrigin = 'left center';
        
        // LAPISAN KACA UTUH
        daunKaca.style.width = kacaWidth + 'px';
        daunKaca.style.height = kacaHeight + 'px';
        
        // ENGSEL KIRI (Menempel di pinggir kaca, bukan kusen)
        engselKiri.style.display = 'block';
        engselKiri.style.left = (gap + 2) + 'px';
        engselKiri.style.top = (displayHeight * 0.2) + 'px';
        engselKiri.style.height = (displayHeight * 0.6) + 'px';
    }

    // RESPONSIVE MOBILE
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapperSwing1');
    let angkaLebar = document.getElementById('angkaLebarSwing1');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggiSwing1').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggiSwing1').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateSwing1();
});

function updateWarnaSwing1() {
    let warna = document.getElementById('selectWarnaSwing1').value;
    let kusen = document.getElementById('kusenSwing1');
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    kusen.style.borderColor = warnaKusen;
}

function initJendelaSwing1() {
    setTimeout(updateSwing1, 100);
}

const originalCloseModalSwing1 = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModalSwing1(modalId);
    if (modalId === 'modalJendelaSwing1Daun') {
        document.getElementById('inputTinggiSwing1').value = '';
        document.getElementById('inputLebarSwing1').value = '';
        document.getElementById('inputJumlahSwing1').value = 1;
        updateSwing1();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaSwing1Daun');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateSwing1, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaSwing1Daun');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateSwing1();
});
</script>