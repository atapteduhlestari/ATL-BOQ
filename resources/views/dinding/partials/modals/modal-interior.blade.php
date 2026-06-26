<!-- Modal Interior -->
<div class="modal-overlay" id="modalInterior">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalInterior')">&times;</button>
            
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/dinding-interior.png') }}" alt="Dinding Interior">
                </div>
                <div class="modal-form">
                    <h3 class="modal-form-title">Dinding Interior</h3>
                    <p class="modal-form-sub">Masukkan data perhitungan dinding interior</p>

                    <form id="formInterior">
                        <div class="input-group-2">
                            <div>
                                <label class="input-label">Luas Dinding (m²)</label>
                                <input type="number" id="luas_dinding_interior" class="input-field" step="0.01" placeholder="0" value="0">
                            </div>
                            <div>
                                <label class="input-label">Waste (%)</label>
                                <input type="number" id="waste_interior" class="input-field" step="1" value="5">
                            </div>
                        </div>

                        <button type="button" class="btn-primary" onclick="lanjutKeBOQInterior()">
                            Hitung Material →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function lanjutKeBOQInterior() {
    let luasDinding = parseFloat(document.getElementById('luas_dinding_interior').value) || 0;
    let waste = parseFloat(document.getElementById('waste_interior').value) || 5;

    if (luasDinding <= 0) {
        alert('Masukkan luas dinding terlebih dahulu!');
        return;
    }

    window.location.href = "{{ route('dinding.boq-interior') }}?luas_dinding=" + luasDinding + "&waste=" + waste;
}
</script>