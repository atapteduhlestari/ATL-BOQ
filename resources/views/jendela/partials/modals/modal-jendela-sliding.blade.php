<!-- Modal Jendela Sliding -->
<div id="modalJendelaSliding" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaSliding')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer_SlidingUnik" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen Luar -->
                        <div id="kusen_SlidingUnik" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">

                            <!-- KACA KIRI (MATI / DIAM) -->
                            <div id="kacaKiri_SlidingUnik" style="position:absolute;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);z-index:1;"></div>

                            <!-- KACA KANAN (GESER) -->
                            <div id="kacaKananWrapper_SlidingUnik" style="position:absolute;transition:left 0.8s cubic-bezier(0.4, 0, 0.2, 1);z-index:2;background:rgba(135,206,235,0.3);border:2px solid rgba(0,0,0,0.1);">
                                <div id="kacaKanan_SlidingUnik" style="position:absolute;width:100%;height:100%;background:rgba(135,206,235,0.3);z-index:1;"></div>
                                <!-- Pegangan di ujung KANAN kaca kanan -->
                                <div id="peganganKanan_SlidingUnik" style="position:absolute;right:-8px;top:50%;transform:translateY(-50%);width:6px;height:40px;background:#888;border-radius:3px;border:1px solid #666;z-index:3;"></div>
                            </div>
                            
                        </div>
                        
                        <!-- Label LEBAR -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI -->
                        <div id="tinggiWrapper_SlidingUnik" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi_SlidingUnik" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR -->
                        <div id="angkaLebar_SlidingUnik" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>

                    <!-- TOMBOL BUKA / TUTUP -->
                    <div id="tombolWrapper_SlidingUnik" style="margin-top:50px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <button id="toggleSlidingBtn_SlidingUnik" class="btn-primary" style="padding:6px 20px;font-size:14px;cursor:pointer;width:auto;" onclick="toggleSliding_SlidingUnik()">
                            🔓 Buka Jendela
                        </button>
                        <span id="statusSliding_SlidingUnik" style="font-size:13px;font-weight:bold;color:#555;">Tertutup</span>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Sliding</h2>
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
                                <span>Minimum ketebalan kaca yang digunakan adalah <strong>5 mm</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Ketebalan profile standar: <strong>Casement Series 60</strong> dan <strong>Sliding Series 60 / Series 88</strong></span>
                            </li>
                            <li>
                                <span class="bullet">•</span>
                                <span>Jendela Sliding adalah jendela <strong>geser</strong> (sliding) yang membuka secara horizontal</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input (ROUTING & ID MODAL TETAP) -->
                    <form action="{{ route('boq.jendela.sliding.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="tinggi" id="inputTinggi_SlidingUnik" placeholder="Contoh: 120" required min="1" oninput="hitungSliding_SlidingUnik()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar_SlidingUnik" placeholder="Contoh: 80" required min="1" oninput="hitungSliding_SlidingUnik()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <!-- INPUT JUMLAH UNIT -->
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah_SlidingUnik" placeholder="Contoh: 2" value="1" min="1" required oninput="hitungSliding_SlidingUnik()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna_SlidingUnik" onchange="updateWarna_SlidingUnik()">
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

                        <button type="submit" class="btn-primary" id="submit_SlidingUnik">
                            Tambahkan ke BOQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isSlidingOpen_SlidingUnik = false;

function toggleSliding_SlidingUnik() {
    isSlidingOpen_SlidingUnik = !isSlidingOpen_SlidingUnik;
    let kacaKanan = document.getElementById('kacaKananWrapper_SlidingUnik');
    let btn = document.getElementById('toggleSlidingBtn_SlidingUnik');
    let status = document.getElementById('statusSliding_SlidingUnik');

    if (!kacaKanan || !btn || !status) return;

    if (isSlidingOpen_SlidingUnik) {
        // BUKA: geser kaca kanan ke kanan
        kacaKanan.style.left = '50%';
        btn.innerHTML = '🔓  Buka Jendela';
        status.textContent = 'Tertutup';
        status.style.color = '#555';
    } else {
        // TUTUP: kaca kanan kembali ke kiri
        kacaKanan.style.left = '0%';
        btn.innerHTML = '🔒 Tutup Jendela';
        status.textContent = 'Terbuka';
        status.style.color = '#28a745';
    }
}

function hitungSliding_SlidingUnik() {
    let inputTinggi = document.getElementById('inputTinggi_SlidingUnik');
    let inputLebar = document.getElementById('inputLebar_SlidingUnik');

    if (!inputTinggi || !inputLebar) {
        return;
    }

    let tinggi = parseInt(inputTinggi.value) || 0;
    let lebar = parseInt(inputLebar.value) || 0;
    
    let angkaTinggi = document.getElementById('angkaTinggi_SlidingUnik');
    let angkaLebar = document.getElementById('angkaLebar_SlidingUnik');
    
    if (angkaTinggi) angkaTinggi.textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    if (angkaLebar) angkaLebar.textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let container = document.getElementById('jendelaContainer_SlidingUnik');
        if (!container) return;

        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen_SlidingUnik');
        if (!kusen) return;
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        let kacaWidth = displayWidth - (borderThick * 2) - (gap * 2);
        let kacaHeight = displayHeight - (borderThick * 2) - (gap * 2);
        
        let kacaKiri = document.getElementById('kacaKiri_SlidingUnik');
        let kacaKanan = document.getElementById('kacaKananWrapper_SlidingUnik');
        
        if (!kacaKiri || !kacaKanan) return;
        
        // KACA KIRI (MATI) - posisi tetap di kiri
        kacaKiri.style.width = (kacaWidth * 0.55) + 'px';
        kacaKiri.style.height = kacaHeight + 'px';
        kacaKiri.style.top = gap + 'px';
        kacaKiri.style.left = gap + 'px';
        
        // KACA KANAN (GESER)
        let lebarPanel = kacaWidth * 0.50;
        kacaKanan.style.width = lebarPanel + 'px';
        kacaKanan.style.height = kacaHeight + 'px';
        kacaKanan.style.top = gap + 'px';
        kacaKanan.style.left = '0%';
        
        // Pastikan status terbuka/tutup tetap sesuai saat resize
        if (isSlidingOpen_SlidingUnik) {
            kacaKanan.style.left = '50%';
        } else {
            kacaKanan.style.left = '0%';
        }
    }
}

window.addEventListener('resize', function() {
    hitungSliding_SlidingUnik();
});

function updateWarna_SlidingUnik() {
    let warna = document.getElementById('selectWarna_SlidingUnik').value;
    let kusen = document.getElementById('kusen_SlidingUnik');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    if (kusen) kusen.style.borderColor = warnaKusen;
}

function initJendelaSliding_SlidingUnik() {
    setTimeout(hitungSliding_SlidingUnik, 100);
}

const originalCloseModal_SlidingUnik = window.closeModal;
window.closeModal = function(modalId) {
    if (typeof originalCloseModal_SlidingUnik === 'function') {
        originalCloseModal_SlidingUnik(modalId);
    }
    if (modalId === 'modalJendelaSliding') {
        let inputTinggi = document.getElementById('inputTinggi_SlidingUnik');
        let inputLebar = document.getElementById('inputLebar_SlidingUnik');
        let inputJumlah = document.getElementById('inputJumlah_SlidingUnik');
        if (inputTinggi) inputTinggi.value = '';
        if (inputLebar) inputLebar.value = '';
        if (inputJumlah) inputJumlah.value = 1;
        hitungSliding_SlidingUnik();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaSliding');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(hitungSliding_SlidingUnik, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaSliding');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    hitungSliding_SlidingUnik();
});
</script>