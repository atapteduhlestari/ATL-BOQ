<!-- Modal Eksterior -->
<div class="modal-overlay" id="modalEksterior">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalEksterior')">&times;</button>
            
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/aquapanel-outdoor.png') }}" alt="Dinding Eksterior">
                </div>
                <div class="modal-form">
                    <h3 class="modal-form-title">Dinding Eksterior</h3>
                    <p class="modal-form-sub">Masukkan data perhitungan dinding exterior</p>

                    <form id="formEksterior">
                        <div class="input-group-2">
                            <div>
                                <label class="input-label">Luas Dinding (m²)</label>
                                <input type="number" id="luas_dinding_eksterior" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                            <div>
                                <label class="input-label">Waste (%)</label>
                                <input type="number" id="waste_eksterior" class="input-field" step="1" value="5">
                            </div>
                        </div>

                        <button type="button" class="btn-primary" onclick="lanjutKeBOQEksterior()">Hitung Material</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function lanjutKeBOQEksterior() {
    let luasDinding = parseFloat(document.getElementById('luas_dinding_eksterior').value) || 0;
    let waste = parseFloat(document.getElementById('waste_eksterior').value) || 5;

    if (luasDinding <= 0) {
        alert('Masukkan luas dinding terlebih dahulu!');
        return;
    }

    // Redirect ke halaman BOQ Eksterior dengan parameter
    window.location.href = "{{ route('dinding.boq-eksterior') }}?luas_dinding=" + luasDinding + "&waste=" + waste;
}
</script>