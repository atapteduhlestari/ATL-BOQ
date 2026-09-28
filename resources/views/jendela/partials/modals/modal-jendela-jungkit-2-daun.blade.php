<!-- Modal Jendela Jungkit 2 Daun -->
<div id="modalJendelaJungkit2Daun" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaJungkit2Daun')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer_Jungkit2" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusen_Jungkit2" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">

                            <!-- COUPLING TENGAH -->
                            <div id="couplingTengah_Jungkit2" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- WRAPPER DAUN KIRI -->
                            <div id="daunWrapperKiri_Jungkit2" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKacaKiri_Jungkit2" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtasKiri_Jungkit2" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>

                            <!-- WRAPPER DAUN KANAN -->
                            <div id="daunWrapperKanan_Jungkit2" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKacaKanan_Jungkit2" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtasKanan_Jungkit2" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Label LEBAR -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI -->
                        <div id="tinggiWrapper_Jungkit2" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi_Jungkit2" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebar_Jungkit2" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP -->
                    <div id="tombolWrapper_Jungkit2" style="margin-top:50px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleJungkitBtn_Jungkit2" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;width:auto;" onclick="toggleJungkit_Jungkit2()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusJungkit_Jungkit2" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Jungkit 2 Daun</h2>
                    <p class="modal-form-sub">Hitung kebutuhan material</p>

                    <!-- Notes -->
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
                                <span>Jendela Jungkit 2 Daun adalah jendela <strong>top hung</strong> (buka ke atas) dengan <strong>2 panel kaca</strong> yang disatukan dengan <strong>coupling</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.jungkit2.hitung') }}" method="POST" id="form_Jungkit2">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="Panjang" id="inputTinggi_Jungkit2" placeholder="Contoh: 120" required min="1" oninput="hitungJungkit_Jungkit2()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar_Jungkit2" placeholder="Contoh: 80" required min="1" oninput="hitungJungkit_Jungkit2()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah_Jungkit2" placeholder="Contoh: 2" value="1" min="1" required oninput="hitungJungkit_Jungkit2()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna_Jungkit2" onchange="updateWarna_Jungkit2()">
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

                        <button type="submit" class="btn-primary" id="submit_Jungkit2">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isJungkitOpen_Jungkit2 = false;

function toggleJungkit_Jungkit2() {
    isJungkitOpen_Jungkit2 = !isJungkitOpen_Jungkit2;
    let wrapperKiri = document.getElementById('daunWrapperKiri_Jungkit2');
    let wrapperKanan = document.getElementById('daunWrapperKanan_Jungkit2');
    let btn = document.getElementById('toggleJungkitBtn_Jungkit2');
    let status = document.getElementById('statusJungkit_Jungkit2');

    if (!wrapperKiri || !wrapperKanan || !btn || !status) return;

    if (isJungkitOpen_Jungkit2) {
        wrapperKiri.style.transform = 'rotateX(-70deg)';
        wrapperKanan.style.transform = 'rotateX(-70deg)';
        btn.innerHTML = '🔒 Tutup Jendela';
        status.textContent = 'Terbuka';
        status.style.color = '#28a745';
    } else {
        wrapperKiri.style.transform = 'rotateX(0deg)';
        wrapperKanan.style.transform = 'rotateX(0deg)';
        btn.innerHTML = '🔓 Buka Jendela';
        status.textContent = 'Tertutup';
        status.style.color = '#555';
    }
}

function hitungJungkit_Jungkit2() {
    let inputTinggi = document.getElementById('inputTinggi_Jungkit2');
    let inputLebar = document.getElementById('inputLebar_Jungkit2');

    if (!inputTinggi || !inputLebar) {
        return;
    }

    let tinggi = parseInt(inputTinggi.value) || 0;
    let lebar = parseInt(inputLebar.value) || 0;
    
    let angkaTinggi = document.getElementById('angkaTinggi_Jungkit2');
    let angkaLebar = document.getElementById('angkaLebar_Jungkit2');
    
    if (angkaTinggi) angkaTinggi.textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    if (angkaLebar) angkaLebar.textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let container = document.getElementById('jendelaContainer_Jungkit2');
        if (!container) return;

        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen_Jungkit2');
        if (!kusen) return;
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        let couplingTengah = document.getElementById('couplingTengah_Jungkit2');
        let couplingLebar = 4;
        let lebarSatuDaun = (kacaWidth - couplingLebar) / 2;
        
        let wrapperKiri = document.getElementById('daunWrapperKiri_Jungkit2');
        let wrapperKanan = document.getElementById('daunWrapperKanan_Jungkit2');
        let daunKacaKiri = document.getElementById('daunKacaKiri_Jungkit2');
        let daunKacaKanan = document.getElementById('daunKacaKanan_Jungkit2');
        let engselKiri = document.getElementById('engselAtasKiri_Jungkit2');
        let engselKanan = document.getElementById('engselAtasKanan_Jungkit2');
        
        if (!wrapperKiri || !wrapperKanan) return;
        
        // COUPLING TENGAH (Posisi pas di tengah)
        if (couplingTengah) {
            couplingTengah.style.left = (displayWidth / 2) - 10 + 'px';
            couplingTengah.style.top = '0px';
            couplingTengah.style.height = displayHeight;
        }
        
        // DAUN KIRI
        wrapperKiri.style.width = lebarSatuDaun + 'px';
        wrapperKiri.style.height = kacaHeight + 'px';
        wrapperKiri.style.top = gap + 'px';
        wrapperKiri.style.left = gap + 'px';
        wrapperKiri.style.transformOrigin = 'top center';
        daunKacaKiri.style.width = lebarSatuDaun + 'px';
        daunKacaKiri.style.height = kacaHeight + 'px';
        
        // DAUN KANAN (Posisi pas di dalam frame, menempel di coupling)
        wrapperKanan.style.width = lebarSatuDaun + 'px';
        wrapperKanan.style.height = kacaHeight + 'px';
        wrapperKanan.style.top = gap + 'px';
        wrapperKanan.style.left = (gap + lebarSatuDaun + couplingLebar) + 'px';
        wrapperKanan.style.transformOrigin = 'top center';
        daunKacaKanan.style.width = lebarSatuDaun + 'px';
        daunKacaKanan.style.height = kacaHeight + 'px';
        
        // ENGSEL KIRI
        if (engselKiri) {
            engselKiri.style.display = 'block';
            engselKiri.style.top = gap + 'px';
            engselKiri.style.left = gap + 'px';
            engselKiri.style.width = lebarSatuDaun;
        }
        
        // ENGSEL KANAN
        if (engselKanan) {
            engselKanan.style.display = 'block';
            engselKanan.style.top = gap + 'px';
            engselKanan.style.left = (gap + lebarSatuDaun - 100 + couplingLebar) + 'px';
            engselKanan.style.width = lebarSatuDaun;
        }
    }
}

window.addEventListener('resize', function() {
    hitungJungkit_Jungkit2();
});

function updateWarna_Jungkit2() {
    let warna = document.getElementById('selectWarna_Jungkit2').value;
    let kusen = document.getElementById('kusen_Jungkit2');
    let couplingTengah = document.getElementById('couplingTengah_Jungkit2');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    if (kusen) kusen.style.borderColor = warnaKusen;
    if (couplingTengah) couplingTengah.style.backgroundColor = warnaKusen;
}

function initJendelaJungkit_Jungkit2() {
    setTimeout(hitungJungkit_Jungkit2, 100);
}

const originalCloseModal_Jungkit2 = window.closeModal;
window.closeModal = function(modalId) {
    if (typeof originalCloseModal_Jungkit2 === 'function') {
        originalCloseModal_Jungkit2(modalId);
    }
    if (modalId === 'modalJendelaJungkit2Daun') {
        let inputTinggi = document.getElementById('inputTinggi_Jungkit2');
        let inputLebar = document.getElementById('inputLebar_Jungkit2');
        let inputJumlah = document.getElementById('inputJumlah_Jungkit2');
        if (inputTinggi) inputTinggi.value = '';
        if (inputLebar) inputLebar.value = '';
        if (inputJumlah) inputJumlah.value = 1;
        hitungJungkit_Jungkit2();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaJungkit2Daun');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(hitungJungkit_Jungkit2, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaJungkit2Daun');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    hitungJungkit_Jungkit2();
});
</script>