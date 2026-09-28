<!-- Modal Jendela Mati 2 Kaca Mullion (2 Vertikal + 4 Horizontal) -->
<div id="modalJendelaMati2KacaMullion" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal('modalJendelaMati2KacaMullion')">&times;</button>

            <!-- Modal Content -->
            <div class="modal-grid">
                <!-- Image/Animation Section -->
                <div class="modal-image" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <!-- Canvas untuk animasi jendela -->
                    <div id="jendelaContainer2KMul" style="position:relative;border:3px solid #333;border-radius:8px;background:#e8f0fe;min-width:200px;min-height:150px;display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                        
                        <!-- Kusen -->
                        <div id="kusen2KMul" style="position:relative;border:8px solid #555;border-radius:4px;background:#87CEEB;transition:all 0.5s ease;">
                            
                            <!-- ===== KACA KIRI (6 Panel) ===== -->
                            <!-- Kiri-Atas-Kiri -->
                            <div id="kiriAtasKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kiri-Atas-Kanan -->
                            <div id="kiriAtasKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kiri-Tengah-Kiri -->
                            <div id="kiriTengahKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kiri-Tengah-Kanan -->
                            <div id="kiriTengahKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kiri-Bawah-Kiri -->
                            <div id="kiriBawahKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kiri-Bawah-Kanan -->
                            <div id="kiriBawahKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- ===== KACA KANAN (6 Panel) ===== -->
                            <!-- Kanan-Atas-Kiri -->
                            <div id="kananAtasKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kanan-Atas-Kanan -->
                            <div id="kananAtasKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kanan-Tengah-Kiri -->
                            <div id="kananTengahKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kanan-Tengah-Kanan -->
                            <div id="kananTengahKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kanan-Bawah-Kiri -->
                            <div id="kananBawahKiri2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>
                            <!-- Kanan-Bawah-Kanan -->
                            <div id="kananBawahKanan2KMul" style="position:absolute;border:2px solid transparent;transition:all 0.5s ease;display:flex;align-items:center;justify-content:center;"></div>

                            <!-- ===== GARIS STRUKTUR ===== -->
                            <!-- Coupling di TENGAH (pisah kaca kiri & kanan) -->
                            <div id="coupling2KMul" style="position:absolute;top:0;bottom:0;width:6px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- Mullion Vertikal KIRI -->
                            <div id="mullionVertKiri2KMul" style="position:absolute;top:0;bottom:0;width:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            <!-- Mullion Vertikal KANAN -->
                            <div id="mullionVertKanan2KMul" style="position:absolute;top:0;bottom:0;width:2px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- Mullion Horizontal KIRI ATAS -->
                            <div id="mullionHorKiriAtas2KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            <!-- Mullion Horizontal KIRI BAWAH -->
                            <div id="mullionHorKiriBawah2KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>

                            <!-- Mullion Horizontal KANAN ATAS -->
                            <div id="mullionHorKananAtas2KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            <!-- Mullion Horizontal KANAN BAWAH -->
                            <div id="mullionHorKananBawah2KMul" style="position:absolute;left:0;right:0;height:4px;background:#555;transition:all 0.5s ease;z-index:2;"></div>
                            
                        </div>
                        
                        <!-- Label LEBAR (di bawah) -->
                        <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 10px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);display:flex;align-items:center;gap:4px;">
                            <span>⬅➡</span> LEBAR
                        </div>

                        <!-- ANGKA TINGGI & LABEL TINGGI (Samping Kiri Kusen) -->
                        <div id="tinggiWrapper2KMul" style="position:absolute;top:50%;left:-75px;transform:translateY(-50%);display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div style="font-size:11px;font-weight:bold;background:#fff;color:#333;padding:2px 8px;border-radius:4px;border:1px solid #333;white-space:nowrap;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                ⬆ TINGGI
                            </div>
                            <div id="angkaTinggi2KMul" style="font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                100 cm
                            </div>
                        </div>

                        <!-- ANGKA LEBAR (Bawah Kusen) -->
                        <div id="angkaLebar2KMul" style="position:absolute;bottom:-55px;left:50%;transform:translateX(-50%);font-size:13px;font-weight:bold;color:#333;background:rgba(255,255,255,0.9);padding:2px 6px;border-radius:4px;border:1px solid #999;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            80 cm
                        </div>
                        
                    </div>
                </div>

                <!-- Form Section -->
                <div class="modal-form">
                    <h2 class="modal-form-title">Jendela Mati 2 Kaca Mullion</h2>
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
                                <span>Jendela Mati 2 Kaca Mullion adalah jendela <strong>tetap / non-opening</strong> dengan <strong>2 panel kaca</strong> dan <strong>mullion</strong> sebagai penambah kekuatan struktur</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('boq.jendela.mati2mullion.hitung') }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="panjang" id="inputTinggi2KMul" placeholder="Contoh: 120" required min="1" oninput="updateJendela2KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm) <span style="color:red;">*</span></label>
                            <input type="number" class="input-field" name="lebar" id="inputLebar2KMul" placeholder="Contoh: 80" required min="1" oninput="updateJendela2KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" id="inputJumlah2KMul" placeholder="Contoh: 2" value="1" min="1" required oninput="updateJendela2KMul()">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna" id="selectWarna2KMul" onchange="updateWarna2KMul()">
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
function updateJendela2KMul() {
    let tinggi = parseInt(document.getElementById('inputTinggi2KMul').value) || 0;
    let lebar = parseInt(document.getElementById('inputLebar2KMul').value) || 0;
    let jumlah = parseInt(document.getElementById('inputJumlah2KMul').value) || 1;
    
    // UPDATE ANGKA REALTIME DI LUAR
    document.getElementById('angkaTinggi2KMul').textContent = (tinggi > 0 ? tinggi : 0) + ' cm';
    document.getElementById('angkaLebar2KMul').textContent = (lebar > 0 ? lebar : 0) + ' cm';
    
    if (tinggi > 0 && lebar > 0) {
        let maxSize = 400;
        let scale = Math.min(1, maxSize / Math.max(tinggi, lebar));
        let displayWidth = lebar * scale;
        let displayHeight = tinggi * scale;
        
        let container = document.getElementById('jendelaContainer2KMul');
        container.style.width = (displayWidth + 40) + 'px';
        container.style.height = (displayHeight + 60) + 'px';
        container.style.minWidth = '200px';
        container.style.minHeight = '150px';
        
        let kusen = document.getElementById('kusen2KMul');
        kusen.style.width = displayWidth + 'px';
        kusen.style.height = displayHeight + 'px';
        let borderThick = Math.max(4, Math.min(12, Math.floor(displayWidth * 0.04)));
        kusen.style.borderWidth = borderThick + 'px';
        
        let gap = 4; 
        
        // --- BAGI LEBAR (3 Kolom: Kiri Kaca, Coupling, Kanan Kaca) ---
        let ruangBersihLebar = displayWidth - (borderThick * 2) - (gap * 2);
        // Ada 3 garis vertikal: mullionVertKiri (4px), coupling (4px), mullionVertKanan (4px)
        let lebarKacaKiri = (ruangBersihLebar - 12) / 2;
        let lebarKacaKanan = lebarKacaKiri;
        let lebarPanelKiri = (lebarKacaKiri - 4) / 2; // 4px = mullion vertikal kiri
        let lebarPanelKanan = (lebarKacaKanan - 4) / 2; // 4px = mullion vertikal kanan

        // --- BAGI TINGGI (3 Baris) ---
        let ruangBersihTinggi = displayHeight - (borderThick * 2) - (gap * 2);
        // Ada 2 garis horizontal per kaca (atas & bawah). Total 4 garis horizontal, masing-masing 4px.
        let totalGarisHor = 16; // 4 baris * 4px
        let tinggiPanel = (ruangBersihTinggi - totalGarisHor) / 3;
        
        // --- POSISI X (KIRI KE KANAN) ---
        let xKiriKaca = gap;
        let xKiriPanelKiri = xKiriKaca;
        let xMullionKiri = xKiriKaca + lebarPanelKiri;
        let xKiriPanelKanan = xMullionKiri + 4;
        let xCoupling = xKiriKaca + lebarKacaKiri;
        
        let xKananKaca = xCoupling + 4;
        let xKananPanelKiri = xKananKaca;
        let xMullionKanan = xKananKaca + lebarPanelKanan + 7;
        let xKananPanelKanan = xMullionKanan + 4;
        
        // --- POSISI Y (ATAS KE BAWAH) ---
        let yAtas = gap;
        let yTengah = yAtas + tinggiPanel + 4;
        let yBawah = yTengah + tinggiPanel + 4;
        
        // --- AMBIL ELEMEN ---
        // Kiri
        let kiriAtasKiri = document.getElementById('kiriAtasKiri2KMul');
        let kiriAtasKanan = document.getElementById('kiriAtasKanan2KMul');
        let kiriTengahKiri = document.getElementById('kiriTengahKiri2KMul');
        let kiriTengahKanan = document.getElementById('kiriTengahKanan2KMul');
        let kiriBawahKiri = document.getElementById('kiriBawahKiri2KMul');
        let kiriBawahKanan = document.getElementById('kiriBawahKanan2KMul');
        
        // Kanan
        let kananAtasKiri = document.getElementById('kananAtasKiri2KMul');
        let kananAtasKanan = document.getElementById('kananAtasKanan2KMul');
        let kananTengahKiri = document.getElementById('kananTengahKiri2KMul');
        let kananTengahKanan = document.getElementById('kananTengahKanan2KMul');
        let kananBawahKiri = document.getElementById('kananBawahKiri2KMul');
        let kananBawahKanan = document.getElementById('kananBawahKanan2KMul');
        
        // Garis
        let coupling = document.getElementById('coupling2KMul');
        let mullionVertKiri = document.getElementById('mullionVertKiri2KMul');
        let mullionVertKanan = document.getElementById('mullionVertKanan2KMul');
        let mullionHorKiriAtas = document.getElementById('mullionHorKiriAtas2KMul');
        let mullionHorKiriBawah = document.getElementById('mullionHorKiriBawah2KMul');
        let mullionHorKananAtas = document.getElementById('mullionHorKananAtas2KMul');
        let mullionHorKananBawah = document.getElementById('mullionHorKananBawah2KMul');
        
        // --- FUNGSI UPDATE PANEL ---
        function updatePanel(el, x, y) {
            el.style.width = lebarPanelKiri + 'px';
            el.style.height = tinggiPanel + 'px';
            el.style.top = y + 'px';
            el.style.left = x + 'px';
        }
        function updatePanelKanan(el, x, y) {
            el.style.width = lebarPanelKanan + 'px';
            el.style.height = tinggiPanel + 'px';
            el.style.top = y + 'px';
            el.style.left = x + 'px';
        }
        
        // --- UPDATE PANEL KIRI ---
        updatePanel(kiriAtasKiri, xKiriPanelKiri, yAtas);
        updatePanel(kiriAtasKanan, xKiriPanelKanan, yAtas);
        updatePanel(kiriTengahKiri, xKiriPanelKiri, yTengah);
        updatePanel(kiriTengahKanan, xKiriPanelKanan, yTengah);
        updatePanel(kiriBawahKiri, xKiriPanelKiri, yBawah);
        updatePanel(kiriBawahKanan, xKiriPanelKanan, yBawah);
        
        // --- UPDATE PANEL KANAN ---
        updatePanelKanan(kananAtasKiri, xKananPanelKiri, yAtas);
        updatePanelKanan(kananAtasKanan, xKananPanelKanan, yAtas);
        updatePanelKanan(kananTengahKiri, xKananPanelKiri, yTengah);
        updatePanelKanan(kananTengahKanan, xKananPanelKanan, yTengah);
        updatePanelKanan(kananBawahKiri, xKananPanelKiri, yBawah);
        updatePanelKanan(kananBawahKanan, xKananPanelKanan, yBawah);
        
        // --- UPDATE GARIS VERTIKAL ---
        coupling.style.left = xCoupling + 'px';
        coupling.style.top = '0px';
        coupling.style.height = displayHeight;
        
        mullionVertKiri.style.left = xMullionKiri + 'px';
        mullionVertKiri.style.top = '0px';
        mullionVertKiri.style.height = displayHeight;
        
        mullionVertKanan.style.left = xMullionKanan + 'px';
        mullionVertKanan.style.top = '0px';
        mullionVertKanan.style.height = displayHeight ;
        
        // --- UPDATE GARIS HORIZONTAL KIRI ---
        mullionHorKiriAtas.style.top = yAtas + tinggiPanel + 'px';
        mullionHorKiriAtas.style.left = xKiriKaca;
        mullionHorKiriAtas.style.width = lebarKacaKiri;
        
        mullionHorKiriBawah.style.top = yTengah + tinggiPanel + 'px';
        mullionHorKiriBawah.style.left = xKiriKaca;
        mullionHorKiriBawah.style.width = lebarKacaKiri;
        
        // --- UPDATE GARIS HORIZONTAL KANAN ---
        mullionHorKananAtas.style.top = yAtas + tinggiPanel + 'px';
        mullionHorKananAtas.style.left = xKananKaca;
        mullionHorKananAtas.style.width = lebarKacaKanan;
        
        mullionHorKananBawah.style.top = yTengah + tinggiPanel + 'px';
        mullionHorKananBawah.style.left = xKananKaca;
        mullionHorKananBawah.style.width = lebarKacaKanan;
    }

    // ===== RESPONSIVE MOBILE =====
    let screenWidth = window.innerWidth;
    let tinggiWrapper = document.getElementById('tinggiWrapper2KMul');
    let angkaLebar = document.getElementById('angkaLebar2KMul');

    if (screenWidth < 480) {
        tinggiWrapper.style.left = '-35px';
        tinggiWrapper.style.fontSize = '9px';
        tinggiWrapper.style.gap = '2px';
        document.getElementById('angkaTinggi2KMul').style.fontSize = '11px';
        angkaLebar.style.bottom = '-20px';
        angkaLebar.style.fontSize = '11px';
    } else {
        tinggiWrapper.style.left = '-75px';
        tinggiWrapper.style.fontSize = '11px';
        tinggiWrapper.style.gap = '4px';
        document.getElementById('angkaTinggi2KMul').style.fontSize = '13px';
        angkaLebar.style.bottom = '-55px';
        angkaLebar.style.fontSize = '13px';
    }
}

window.addEventListener('resize', function() {
    updateJendela2KMul();
});

function updateWarna2KMul() {
    let warna = document.getElementById('selectWarna2KMul').value;
    let kusen = document.getElementById('kusen2KMul');
    let coupling = document.getElementById('coupling2KMul');
    let mullionVertKiri = document.getElementById('mullionVertKiri2KMul');
    let mullionVertKanan = document.getElementById('mullionVertKanan2KMul');
    let mullionHorKiriAtas = document.getElementById('mullionHorKiriAtas2KMul');
    let mullionHorKiriBawah = document.getElementById('mullionHorKiriBawah2KMul');
    let mullionHorKananAtas = document.getElementById('mullionHorKananAtas2KMul');
    let mullionHorKananBawah = document.getElementById('mullionHorKananBawah2KMul');
    
    let warnaMap = {
        'Hitam': '#333',
        'Putih': '#f0f0f0',
        'Walnut': '#8B7355'
    };
    let warnaKusen = warnaMap[warna] || '#555';
    
    kusen.style.borderColor = warnaKusen;
    coupling.style.backgroundColor = warnaKusen;
    mullionVertKiri.style.backgroundColor = warnaKusen;
    mullionVertKanan.style.backgroundColor = warnaKusen;
    mullionHorKiriAtas.style.backgroundColor = warnaKusen;
    mullionHorKiriBawah.style.backgroundColor = warnaKusen;
    mullionHorKananAtas.style.backgroundColor = warnaKusen;
    mullionHorKananBawah.style.backgroundColor = warnaKusen;
}

function initJendelaMati2KMul() {
    setTimeout(updateJendela2KMul, 100);
}

const originalCloseModal2KMul = window.closeModal;
window.closeModal = function(modalId) {
    originalCloseModal2KMul(modalId);
    if (modalId === 'modalJendelaMati2KacaMullion') {
        document.getElementById('inputTinggi2KMul').value = '';
        document.getElementById('inputLebar2KMul').value = '';
        document.getElementById('inputJumlah2KMul').value = 1;
        updateJendela2KMul();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                let modal = document.getElementById('modalJendelaMati2KacaMullion');
                if (modal && modal.style.display !== 'none' && modal.style.display !== '') {
                    setTimeout(updateJendela2KMul, 200);
                }
            }
        });
    });
    
    let modal = document.getElementById('modalJendelaMati2KacaMullion');
    if (modal) {
        observer.observe(modal, { attributes: true });
    }
    
    updateJendela2KMul();
});
</script>