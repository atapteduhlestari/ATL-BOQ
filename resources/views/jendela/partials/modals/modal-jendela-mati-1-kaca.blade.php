<!-- Modal Jendela Mati 1 Kaca -->
<div id="modalJendelaMati1Kaca" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati1Kaca')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            <!-- Daun Jendela (Animasi TETAP UTUH) -->
                            <div id="daunJendela" style="position:relative;border:2px solid #333;background:rgba(135,206,235,0.3);transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;">
                            </div>
                        </div>
                        
                        <!-- Label LEBAR (di bawah - TETAP) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Sekarang ADA DI SAMPING KIRI KUSEN) -->
                        <div id="tinggiWrapper" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <!-- Label TINGGI yang dipindahkan ke sini -->
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <!-- Angka Realtime Tinggi -->
                            <div id="angkaTinggi" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Tetap di bawah) -->
                        <div id="angkaLebar" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                    
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 1 Kaca</h2>
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
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati1.hitung') }}" method="POST" id="formJendelaMati1">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi" placeholder="Contoh: 120" required min="1" oninput="updateJendela()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar" placeholder="Contoh: 80" required min="1" oninput="updateJendela()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna" onchange="updateWarna()">
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
function updateJendela() {
    let tinggi = parseInt(document.getElementById('inputTinggi').value) || 100;
    let lebar = parseInt(document.getElementById('inputLebar').value) || 80;
    let jumlah = parseInt(document.getElementById('inputJumlah').value) || 1;
    
    // UPDATE ANGKA REALTIME
    document.getElementById('angkaTinggi').textContent = tinggi + ' cm';
    document.getElementById('angkaLebar').textContent = lebar + ' cm';
    
    // Batasi ukuran maksimal agar tidak terlalu besar
    let maxSize = 400;
    let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
    let displayWidth = lebar * scale;
    let displayHeight = tinggi * scale;
    
    // Update container
    let container = document.getElementById('jendelaContainer');
    container.style.width = (displayWidth + 40) + 'px';
    container.style.height = (displayHeight + 60) + 'px';
    container.style.minWidth = '200px';
    container.style.minHeight = '150px';
    
    // Update kusen
    let kusen = document.getElementById('kusen');
    kusen.style.width = displayWidth + 'px';
    kusen.style.height = displayHeight + 'px';
    let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
    kusen.style.borderWidth = borderThick + 'px';
    
    // Update daun jendela
    let daun = document.getElementById('daunJendela');
    let gap = 4; 
    let daunWidth = displayWidth - (borderThick * 2) - (gap * 2);
    let daunHeight = displayHeight - (borderThick * 2) - (gap * 2);
    daun.style.width = daunWidth + 'px';
    daun.style.height = daunHeight + 'px';
    daun.style.position = 'absolute';
    daun.style.top = gap + 'px';
    daun.style.left = gap + 'px';

    // ===== TAMBAHAN UNTUK RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper');
    let angkaLebar = document.getElementById('angkaLebar');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px'; 
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

// Panggil fungsi responsive saat ukuran layar berubah (di-rotate atau resize)
window.addEventListener('resize', function() {
    updateJendela();
});

function updateWarna() {
    let warna = document.getElementById('selectWarna').value;
    let kusen = document.getElementById('kusen');
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    kusen.style.borderColor = warnaKusen;
}

// Initialize when modal opens
function initJendelaMati1() {
    setTimeout(updateJendela, 100);
}

// Override closeModal to reinitialize
const originalCloseModal = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal(modalId);
    if (modalId === 'modalJendelaMati1Kaca') {
        document.getElementById('inputTinggi').value = '';
        document.getElementById('inputLebar').value = '';
        document.getElementById('inputJumlah').value = 1;
    }
};

// Watch for modal opening
document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati1Kaca');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati1Kaca');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela();
});
</script>