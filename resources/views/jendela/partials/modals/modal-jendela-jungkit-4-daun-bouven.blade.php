<!-- Modal Jendela Jungkit 4 Daun Bouven -->
<div id="modalJendelaJungkit4DaunBouven" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaJungkit4DaunBouven')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer_Jungkit4BouvenUnik" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusen_Jungkit4BouvenUnik" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">

                            <!-- COUPLING 1 -->
                            <div id="coupling1_Jungkit4BouvenUnik" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            <!-- COUPLING 2 -->
                            <div id="coupling2_Jungkit4BouvenUnik" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            <!-- COUPLING 3 -->
                            <div id="coupling3_Jungkit4BouvenUnik" style="position:absolute;top:0;bottom:0;width:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- WRAPPER DAUN 1 (Paling Kiri) -->
                            <div id="daunWrapper1_Jungkit4BouvenUnik" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKaca1_Jungkit4BouvenUnik" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtas1_Jungkit4BouvenUnik" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>

                            <!-- WRAPPER DAUN 2 -->
                            <div id="daunWrapper2_Jungkit4BouvenUnik" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKaca2_Jungkit4BouvenUnik" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtas2_Jungkit4BouvenUnik" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>

                            <!-- WRAPPER DAUN 3 -->
                            <div id="daunWrapper3_Jungkit4BouvenUnik" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKaca3_Jungkit4BouvenUnik" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtas3_Jungkit4BouvenUnik" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>

                            <!-- WRAPPER DAUN 4 (Paling Kanan) -->
                            <div id="daunWrapper4_Jungkit4BouvenUnik" style="position:absolute;transform-origin:top center;transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);perspective:800px;z-index:1;">
                                <div id="daunKaca4_Jungkit4BouvenUnik" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:0;"></div>
                                <div id="engselAtas4_Jungkit4BouvenUnik" style="position:absolute;left:0;right:0;height:0;border-top:3px dashed #aaa;z-index:2;display:none;"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Label LEBAR -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI -->
                        <div id="tinggiWrapper_Jungkit4BouvenUnik" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi_Jungkit4BouvenUnik" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebar_Jungkit4BouvenUnik" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP -->
                    <div id="tombolWrapper_Jungkit4BouvenUnik" style="margin-top:30px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleJungkitBtn_Jungkit4BouvenUnik" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;width:auto;" onclick="toggleJungkit_Jungkit4BouvenUnik()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusJungkit_Jungkit4BouvenUnik" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Jungkit 4 Daun Bouven</h2>
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
                                <span>Jendela Jungkit 4 Daun Bouven adalah jendela <strong>top hung</strong> (buka ke atas) dengan sistem <strong>Bouven</strong> dan <strong>4 panel kaca</strong></span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input (ID UNIK, ROUTING TIDAK DIUBAH) -->
                    <form action="{{ route('boq.jendela.jungkit4bouven.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi_Jungkit4BouvenUnik" placeholder="Contoh: 60" required min="1" oninput="hitungJungkit_Jungkit4BouvenUnik()">
                            <div id="warningTinggi_Jungkit4BouvenUnik" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Tinggi maksimal 70 cm. Gunakan model Jungkit 4 Daun biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar_Jungkit4BouvenUnik" placeholder="Contoh: 100" required min="1" oninput="hitungJungkit_Jungkit4BouvenUnik()">
                            <div id="warningLebar_Jungkit4BouvenUnik" style="color:#dc3545;font-size:12px;font-weight:bold;display:none;margin-top:4px;">
                                ⚠️ Lebar maksimal 480 cm (4 panel × 120 cm). Gunakan model Jungkit 4 Daun biasa.
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <!-- INPUT JUMLAH UNIT -->
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah_Jungkit4BouvenUnik" placeholder="Contoh: 2" value="1" min="1" required oninput="hitungJungkit_Jungkit4BouvenUnik()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna_Jungkit4BouvenUnik" onchange="updateWarna_Jungkit4BouvenUnik()">
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

                        <button type="submit" class="btn-primary" id="submit_Jungkit4BouvenUnik">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isJungkitOpen_Jungkit4BouvenUnik = false;

function toggleJungkit_Jungkit4BouvenUnik() {
    isJungkitOpen_Jungkit4BouvenUnik = !isJungkitOpen_Jungkit4BouvenUnik;
    let wrapper1 = document.getElementById('daunWrapper1_Jungkit4BouvenUnik');
    let wrapper2 = document.getElementById('daunWrapper2_Jungkit4BouvenUnik');
    let wrapper3 = document.getElementById('daunWrapper3_Jungkit4BouvenUnik');
    let wrapper4 = document.getElementById('daunWrapper4_Jungkit4BouvenUnik');
    let btn = document.getElementById('toggleJungkitBtn_Jungkit4BouvenUnik');
    let status = document.getElementById('statusJungkit_Jungkit4BouvenUnik');

    if (!wrapper1 || !wrapper2 || !wrapper3 || !wrapper4 || !btn || !status) return;

    if (isJungkitOpen_Jungkit4BouvenUnik) {
        wrapper1.style.transform = 'rotateX(-70deg)';
        wrapper2.style.transform = 'rotateX(-70deg)';
        wrapper3.style.transform = 'rotateX(-70deg)';
        wrapper4.style.transform = 'rotateX(-70deg)';
        btn.innerHTML = '🔒 Tutup Jendela';
        status.textContent = 'Terbuka';
        status.style.color = '#28a745';
    } else {
        wrapper1.style.transform = 'rotateX(0deg)';
        wrapper2.style.transform = 'rotateX(0deg)';
        wrapper3.style.transform = 'rotateX(0deg)';
        wrapper4.style.transform = 'rotateX(0deg)';
        btn.innerHTML = '🔓 Buka Jendela';
        status.textContent = 'Tertutup';
        status.style.color = '#555';
    }
}

function hitungJungkit_Jungkit4BouvenUnik() {
    let inputTinggi = document.getElementById('inputTinggi_Jungkit4BouvenUnik');
    let inputLebar = document.getElementById('inputLebar_Jungkit4BouvenUnik');

    if (!inputTinggi || !inputLebar) {
        return;
    }

    let tinggi = parseInt(inputTinggi.value) || 0;
    let lebar = parseInt(inputLebar.value) || 0;
    
    let angkaTinggi = document.getElementById('angkaTinggi_Jungkit4BouvenUnik');
    let angkaLebar = document.getElementById('angkaLebar_Jungkit4BouvenUnik');
    
    if (angkaTinggi) angkaTinggi.textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    if (angkaLebar) angkaLebar.textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    // VALIDASI BOUVEN
    let warningTinggi = document.getElementById('warningTinggi_Jungkit4BouvenUnik');
    let warningLebar = document.getElementById('warningLebar_Jungkit4BouvenUnik');
    let btnSubmit = document.getElementById('submit_Jungkit4BouvenUnik');
    let isError = false;

    if (tinggi > 70) {
        warningTinggi.style.display = 'block';
        isError = true;
    } else {
        warningTinggi.style.display = 'none';
    }

    if (lebar > 480) {
        warningLebar.style.display = 'block';
        isError = true;
    } else {
        warningLebar.style.display = 'none';
    }

    btnSubmit.disabled = isError;
    btnSubmit.style.opacity = isError ? '0.5' : '1';
    btnSubmit.style.cursor = isError ? 'not-allowed' : 'pointer';

    if (tinggi > 0 && lebar > 0 && !isError) {
        let container = document.getElementById('jendelaContainer_Jungkit4BouvenUnik');
        if (!container) return;

        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen_Jungkit4BouvenUnik');
        if (!kusen) return;
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        // 4 DAUN SAMA RATA
        let lebarSatuDaun = kacaWidth / 4;
        
        let wrapper1 = document.getElementById('daunWrapper1_Jungkit4BouvenUnik');
        let wrapper2 = document.getElementById('daunWrapper2_Jungkit4BouvenUnik');
        let wrapper3 = document.getElementById('daunWrapper3_Jungkit4BouvenUnik');
        let wrapper4 = document.getElementById('daunWrapper4_Jungkit4BouvenUnik');
        let coupling1 = document.getElementById('coupling1_Jungkit4BouvenUnik');
        let coupling2 = document.getElementById('coupling2_Jungkit4BouvenUnik');
        let coupling3 = document.getElementById('coupling3_Jungkit4BouvenUnik');
        
        if (!wrapper1 || !wrapper2 || !wrapper3 || !wrapper4) return;
        
        // COUPLING TENGAH
        if (coupling1) {
            coupling1.style.left = (displayWidth / 4) - 5 + 'px';
            coupling1.style.top = '0px';
            coupling1.style.height = displayHeight;
        }
        if (coupling2) {
            coupling2.style.left = (displayWidth / 2) - 12 + 'px';
            coupling2.style.top = '0px';
            coupling2.style.height = displayHeight;
        }
        if (coupling3) {
            coupling3.style.left = ((displayWidth * 3) / 4) - 20 + 'px';
            coupling3.style.top = '0px';
            coupling3.style.height = displayHeight;
        }
        
        // DAUN 1
        wrapper1.style.width = lebarSatuDaun + 'px';
        wrapper1.style.height = kacaHeight + 'px';
        wrapper1.style.top = gap + 'px';
        wrapper1.style.left = gap + 'px';
        wrapper1.style.transformOrigin = 'top center';
        document.getElementById('daunKaca1_Jungkit4BouvenUnik').style.width = lebarSatuDaun + 'px';
        document.getElementById('daunKaca1_Jungkit4BouvenUnik').style.height = kacaHeight + 'px';
        
        // DAUN 2
        wrapper2.style.width = lebarSatuDaun + 'px';
        wrapper2.style.height = kacaHeight + 'px';
        wrapper2.style.top = gap + 'px';
        wrapper2.style.left = (gap + lebarSatuDaun) + 'px';
        wrapper2.style.transformOrigin = 'top center';
        document.getElementById('daunKaca2_Jungkit4BouvenUnik').style.width = lebarSatuDaun + 'px';
        document.getElementById('daunKaca2_Jungkit4BouvenUnik').style.height = kacaHeight + 'px';
        
        // DAUN 3
        wrapper3.style.width = lebarSatuDaun + 'px';
        wrapper3.style.height = kacaHeight + 'px';
        wrapper3.style.top = gap + 'px';
        wrapper3.style.left = (gap + lebarSatuDaun + lebarSatuDaun) + 'px';
        wrapper3.style.transformOrigin = 'top center';
        document.getElementById('daunKaca3_Jungkit4BouvenUnik').style.width = lebarSatuDaun + 'px';
        document.getElementById('daunKaca3_Jungkit4BouvenUnik').style.height = kacaHeight + 'px';
        
        // DAUN 4
        wrapper4.style.width = lebarSatuDaun + 'px';
        wrapper4.style.height = kacaHeight + 'px';
        wrapper4.style.top = gap + 'px';
        wrapper4.style.left = (gap + lebarSatuDaun + lebarSatuDaun + lebarSatuDaun) + 'px';
        wrapper4.style.transformOrigin = 'top center';
        document.getElementById('daunKaca4_Jungkit4BouvenUnik').style.width = lebarSatuDaun + 'px';
        document.getElementById('daunKaca4_Jungkit4BouvenUnik').style.height = kacaHeight + 'px';
    }
}

window.addEventListener('resize', function() {
    hitungJungkit_Jungkit4BouvenUnik();
});

function updateWarna_Jungkit4BouvenUnik() {
    let warna = document.getElementById('selectWarna_Jungkit4BouvenUnik').value;
    let kusen = document.getElementById('kusen_Jungkit4BouvenUnik');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    if (kusen) kusen.style.borderColor = warnaKusen;
}

function initJendelaJungkit_Jungkit4BouvenUnik() {
    setTimeout(hitungJungkit_Jungkit4BouvenUnik, 100);
}

const originalCloseModal_Jungkit4BouvenUnik = window.closeModal;
window.closeModal = function(modalId) {
    if (typeof originalCloseModal_Jungkit4BouvenUnik === 'function') {
        originalCloseModal_Jungkit4BouvenUnik(modalId);
    }
    if (modalId === 'modalJendelaJungkit4DaunBouven') {
        let inputTinggi = document.getElementById('inputTinggi_Jungkit4BouvenUnik');
        let inputLebar = document.getElementById('inputLebar_Jungkit4BouvenUnik');
        let inputJumlah = document.getElementById('inputJumlah_Jungkit4BouvenUnik');
        if (inputTinggi) inputTinggi.value = '';
        if (inputLebar) inputLebar.value = '';
        if (inputJumlah) inputJumlah.value = 1;
        hitungJungkit_Jungkit4BouvenUnik();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaJungkit4DaunBouven');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(hitungJungkit_Jungkit4BouvenUnik, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaJungkit4DaunBouven');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    hitungJungkit_Jungkit4BouvenUnik();
});
</script>