<!-- Modal Waterproofing -->
<div class="modal-overlay" id="modalWaterproofing">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalWaterproofing')">&times;</button>
            
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/waterproofing.png') }}" alt="Waterproofing">
                    <div class="modal-image-overlay"></div>
                </div>
                <div class="modal-form">
                    <h3 class="modal-form-title">Waterproofing</h3>
                    <p class="modal-form-sub">Masukkan data perhitungan waterproofing</p>

                    <form id="formWaterproofing">

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
                                <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3; margin-top: 6px; padding-top: 6px; border-top: 1px dashed #fcd34d;">
                                    <span style="color: #d97706; font-weight: 700;">•</span>
                                    <span><strong>Expose</strong> : Duo, Sagitta</span>
                                </li>
                                <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                    <span style="color: #d97706; font-weight: 700;">•</span>
                                    <span><strong>Non Expose</strong> : Sagitta, Soprasun, Polygum</span>
                                </li>
                            </ul>
                        </div>

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

                        <!-- Dropdown Brand dari Database -->
                        <div style="margin-bottom: 12px;">
                            <select id="brand_waterproofing" class="input-field">
                                <option value="">Pilih Brand</option>
                                @foreach($brands as $brand)
                                    @php
                                        $slug = strtolower($brand->nama_brand);
                                        $routeName = $brandRouteMap[$slug] ?? 'waterproofing.boq';
                                    @endphp
                                    <option 
                                        value="{{ $brand->id }}" 
                                        data-route="{{ route($routeName) }}">
                                        {{ $brand->nama_brand }}
                                    </option>
                                @endforeach
                            </select>
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

<style>
    /* ===== GLASS EFFECT MODAL ===== */
    .modal-overlay {
        background: rgba(15, 23, 42, 0.45) !important;
        backdrop-filter: blur(12px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(12px) saturate(160%) !important;
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

    /* Gambar sisi kiri */
    .modal-image {
        position: relative;
        background: rgba(26, 26, 46, 0.6) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .modal-image img {
        opacity: 0.75 !important;
    }

    .modal-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(15, 52, 96, 0.4),
            rgba(26, 26, 46, 0.6));
        pointer-events: none;
    }

    /* Form sisi kanan */
    .modal-form {
        background: transparent !important;
    }

    .modal-form-title {
        color: #0f172a !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .modal-form-sub {
        color: #475569 !important;
        opacity: 0.85;
    }

    /* Label */
    .input-label {
        color: #334155 !important;
        font-weight: 600 !important;
        opacity: 0.9;
    }

    /* Input field & Select dengan efek glass */
    .input-field {
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        color: #0f172a !important;
        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.03),
            0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
        transition: all 0.25s ease !important;
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

    /* Khusus select dropdown */
    select.input-field {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 8.825L1.175 4 2.238 2.938 6 6.7l3.763-3.762L10.825 4z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 12px !important;
        padding-right: 34px !important;
        cursor: pointer;
    }

    /* Style option di dalam dropdown */
    select.input-field option {
        background: #ffffff;
        color: #0f172a;
        padding: 8px;
    }

    /* Tombol primary dengan efek glass */
    .btn-primary {
        background: linear-gradient(135deg,
            rgba(15, 52, 96, 0.9),
            rgba(26, 26, 46, 0.95)) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        letter-spacing: 0.2px;
        box-shadow:
            0 4px 14px rgba(15, 52, 96, 0.3),
            0 1px 0 rgba(255, 255, 255, 0.2) inset !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative;
        overflow: hidden;
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

    /* Tombol close dengan efek glass */
    .modal-close {
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
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.85) !important;
        color: #0f172a !important;
        transform: rotate(90deg) scale(1.05);
        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.15),
            0 1px 0 rgba(255, 255, 255, 1) inset !important;
    }

    /* Scrollbar transparan */
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

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .modal-container {
            border-radius: 16px !important;
            max-height: 95vh;
        }
        .modal-image {
            border-radius: 16px 16px 0 0 !important;
        }
        .modal-close {
            width: 28px !important;
            height: 28px !important;
            font-size: 18px !important;
        }
    }
</style>

<script>
function lanjutKeBOQWaterproofing() {
    let select = document.getElementById('brand_waterproofing');
    let brandId = select.value;
    let selectedOption = select.options[select.selectedIndex];
    let route = selectedOption.getAttribute('data-route');

    let luas = parseFloat(document.getElementById('luas_waterproofing').value) || 0;
    let waste = parseFloat(document.getElementById('waste_waterproofing').value) || 5;
    let panjangPerimeter = parseFloat(document.getElementById('panjang_perimeter').value) || 0;
    let tinggiPerimeter = parseFloat(document.getElementById('tinggi_perimeter').value) || 0;
    let sudut = parseFloat(document.getElementById('sudut_waterproofing').value) || 0;

    if (!brandId) {
        alert('Pilih brand waterproofing terlebih dahulu!');
        return;
    }

    if (luas <= 0) {
        alert('Masukkan luas area terlebih dahulu!');
        return;
    }

    let finalPanjangPerimeter = panjangPerimeter;
    if (tinggiPerimeter <= 15 && tinggiPerimeter > 0) {
        finalPanjangPerimeter = panjangPerimeter + 0.2;
    }

    let params = new URLSearchParams({
        brand_id: brandId,
        luas: luas,
        waste: waste,
        panjang_perimeter: finalPanjangPerimeter,
        tinggi_perimeter: tinggiPerimeter,
        sudut: sudut
    });

    window.location.href = route + "?" + params.toString();
}
</script>