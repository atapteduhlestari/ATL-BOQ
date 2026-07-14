<!-- Modal Waterproofing -->
<div class="modal-overlay" id="modalWaterproofing">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalWaterproofing')">&times;</button>
            
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/waterproofing.png') }}" alt="Waterproofing">
                </div>
                <div class="modal-form">
                    <h3 class="modal-form-title">Waterproofing</h3>
                    <p class="modal-form-sub">Masukkan data perhitungan waterproofing</p>

                    <!-- ===== NOTES / PEMBERITAHUAN ===== -->
                    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px;">
                        <div style="font-size: 11px; font-weight: 600; color: #92400e; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 14px;">📋</span> Petunjuk Pengisian
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                <span style="color: #d97706; font-weight: 700;">•</span>
                                <span><strong>Perimeter</strong> dihitung berdasarkan <strong>panjang keliling Membrane</strong> yang bertemu dengan dinding.</span>
                            </li>
                            <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                <span style="color: #d97706; font-weight: 700;">•</span>
                                <span>Jika tinggi perimeter <strong>≤ 15 cm</strong>, maka perhitungan area = <strong>Panjang area + 0.2 m</strong>.</span>
                            </li>
                        </ul>
                    </div>

                    <form id="formWaterproofing">
                        <div class="input-group-2">
                            <div>
                                <label class="input-label">Luas Area (m²)</label>
                                <input type="number" id="luas_waterproofing" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                            <div>
                                <label class="input-label">Waste (%)</label>
                                <input type="number" id="waste_waterproofing" class="input-field" step="1" value="5">
                            </div>
                        </div>

                        <div class="input-group-2">
                            <div>
                                <label class="input-label">Panjang Perimeter (m)</label>
                                <input type="number" id="panjang_perimeter" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                            <div>
                                <label class="input-label">Tinggi Perimeter (cm)</label>
                                <input type="number" id="tinggi_perimeter" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                        </div>

                        <div class="input-group-2">
                            <div>
                                <label class="input-label">Sudut Kemiringan (°)</label>
                                <input type="number" id="sudut_waterproofing" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                            <div>
                                <label class="input-label" style="visibility: hidden;">Kosong</label>
                                <div style="height: 38px;"></div>
                            </div>
                        </div>

                        <button type="button" class="btn-primary" onclick="lanjutKeBOQWaterproofing()">
                            Hitung Material →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function lanjutKeBOQWaterproofing() {
    let luas = parseFloat(document.getElementById('luas_waterproofing').value) || 0;
    let waste = parseFloat(document.getElementById('waste_waterproofing').value) || 5;
    let panjangPerimeter = parseFloat(document.getElementById('panjang_perimeter').value) || 0;
    let tinggiPerimeter = parseFloat(document.getElementById('tinggi_perimeter').value) || 0;
    let sudut = parseFloat(document.getElementById('sudut_waterproofing').value) || 0;

    if (luas <= 0) {
        alert('Masukkan luas area terlebih dahulu!');
        return;
    }

    // Jika tinggi perimeter <= 15 cm, tambahkan 0.2 m ke panjang perimeter
    let finalPanjangPerimeter = panjangPerimeter;
    if (tinggiPerimeter <= 15 && tinggiPerimeter > 0) {
        finalPanjangPerimeter = panjangPerimeter + 0.2;
    }

    window.location.href = "{{ route('waterproofing.boq') }}?luas=" + luas + "&waste=" + waste + "&panjang_perimeter=" + finalPanjangPerimeter + "&tinggi_perimeter=" + tinggiPerimeter + "&sudut=" + sudut;
}
</script>