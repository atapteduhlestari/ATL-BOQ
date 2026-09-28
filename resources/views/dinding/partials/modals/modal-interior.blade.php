<!-- Modal Interior -->
<div class="modal-overlay" id="modalInterior">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalInterior')">&times;</button>
            
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/aquapanel-indoor.png') }}" alt="Dinding Interior">
                    <div class="modal-image-overlay"></div>
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

<style>
    /* ===== GLASS EFFECT MODAL ===== */
    .modal-overlay {
        background: rgba(15, 23, 42, 0.45) !important;
        backdrop-filter: blur(12px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(12px) saturate(160%) !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        overflow-y: auto;
        padding: 20px;
    }

    .modal-overlay.active {
        display: block !important;
    }

    .modal-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
        width: 100%;
    }

    .modal-container {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(24px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        box-shadow:
            0 25px 50px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.4) inset,
            0 1px 0 rgba(255, 255, 255, 0.8) inset !important;
        border-radius: 20px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        max-height: 90vh;
        width: 100%;
        max-width: 880px;
        position: relative;
        animation: modalSlide 0.3s ease-out;
        margin: auto;
    }

    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Highlight tipis di atas container */
    .modal-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.9),
            transparent);
        pointer-events: none;
        z-index: 2;
    }

    /* ===== GAMBAR SISI KIRI ===== */
    .modal-image {
        position: relative;
        background: rgba(26, 26, 46, 0.6) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        width: 40%;
        min-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 20px 0 0 20px;
        flex-shrink: 0;
    }

    .modal-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        opacity: 0.75 !important;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
    }

    .modal-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(15, 52, 96, 0.4),
            rgba(26, 26, 46, 0.6));
        pointer-events: none;
        z-index: 1;
    }

    /* ===== MODAL GRID ===== */
    .modal-grid {
        display: flex;
        flex-direction: row;
        min-height: 380px;
    }

    /* ===== FORM SISI KANAN ===== */
    .modal-form {
        width: 60%;
        padding: 24px 28px;
        background: transparent !important;
    }

    .modal-form-title {
        color: #0f172a !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
        font-size: 16px;
        margin-bottom: 2px;
    }

    .modal-form-sub {
        color: #475569 !important;
        opacity: 0.85;
        font-size: 12px;
        margin-bottom: 16px;
    }

    /* ===== INPUT GROUP ===== */
    .input-group-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    /* Label */
    .input-label {
        display: block;
        color: #334155 !important;
        font-weight: 600 !important;
        opacity: 0.9;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 3px;
    }

    /* Input field dengan efek glass */
    .input-field {
        width: 100%;
        padding: 7px 10px;
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        border-radius: 6px;
        color: #0f172a !important;
        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.03),
            0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
        transition: all 0.25s ease !important;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
    }

    .input-field::placeholder {
        color: #94a3b8 !important;
        opacity: 0.7;
    }

    .input-field:hover {
        background: rgba(255, 255, 255, 0.75) !important;
        border-color: rgba(255, 255, 255, 0.9) !important;
    }

    .input-field:focus {
        background: rgba(255, 255, 255, 0.92) !important;
        border-color: rgba(15, 52, 96, 0.4) !important;
        box-shadow:
            0 0 0 4px rgba(15, 52, 96, 0.1),
            0 1px 0 rgba(255, 255, 255, 1) inset !important;
        outline: none !important;
    }

    /* ===== TOMBOL PRIMARY ===== */
    .btn-primary {
        width: 100%;
        padding: 9px 0;
        background: linear-gradient(135deg,
            rgba(15, 52, 96, 0.9),
            rgba(26, 26, 46, 0.95)) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 6px;
        color: #ffffff !important;
        font-weight: 600 !important;
        letter-spacing: 0.2px;
        box-shadow:
            0 4px 14px rgba(15, 52, 96, 0.3),
            0 1px 0 rgba(255, 255, 255, 0.2) inset !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        margin-top: 4px;
    }

    .btn-primary::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.25),
            transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg,
            rgba(15, 52, 96, 1),
            rgba(26, 26, 46, 1)) !important;
        transform: translateY(-1px);
        box-shadow:
            0 8px 24px rgba(15, 52, 96, 0.45),
            0 1px 0 rgba(255, 255, 255, 0.3) inset !important;
    }

    .btn-primary:hover::after {
        left: 100%;
    }

    .btn-primary:active {
        transform: translateY(0);
        box-shadow:
            0 4px 12px rgba(15, 52, 96, 0.3) !important;
    }

    /* ===== TOMBOL CLOSE ===== */
    .modal-close {
        position: absolute;
        top: 14px;
        right: 16px;
        background: rgba(255, 255, 255, 0.5) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        border-radius: 50% !important;
        width: 32px !important;
        height: 32px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #475569 !important;
        font-size: 20px !important;
        line-height: 1 !important;
        transition: all 0.25s ease !important;
        box-shadow:
            0 2px 8px rgba(0, 0, 0, 0.08),
            0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
        cursor: pointer;
        z-index: 10;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #0f172a !important;
        transform: rotate(90deg) scale(1.05);
        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.15),
            0 1px 0 rgba(255, 255, 255, 1) inset !important;
    }

    /* ===== SCROLLBAR ===== */
    .modal-container::-webkit-scrollbar {
        width: 6px;
    }
    .modal-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .modal-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.5) !important;
        border-radius: 4px;
    }
    .modal-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 768px) {
        .modal-container {
            border-radius: 16px !important;
            max-height: 95vh;
        }
        .modal-grid {
            flex-direction: column;
        }
        .modal-image {
            width: 100%;
            min-height: 200px;
            height: 200px;
            border-radius: 16px 16px 0 0;
        }
        .modal-form {
            width: 100%;
            padding: 20px;
        }
        .input-group-2 {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .modal-close {
            width: 28px !important;
            height: 28px !important;
            font-size: 18px !important;
        }
    }
</style>

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