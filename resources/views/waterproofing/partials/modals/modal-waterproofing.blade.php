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
                                <label class="input-label">Tinggi Perimeter (m)</label>
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

    window.location.href = "{{ route('waterproofing.boq') }}?luas=" + luas + "&waste=" + waste + "&panjang_perimeter=" + panjangPerimeter + "&tinggi_perimeter=" + tinggiPerimeter + "&sudut=" + sudut;
}
</script>