<!-- Modal Pintu Swing 1 Daun -->
<div id="modalPintuSliding3track" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal('modalPintuSliding3track')">&times;</button>
            <div class="modal-grid">
                <div class="modal-image">
                    <img src="{{ asset('images/pintu/pintu-sliding-3-track.png') }}" alt="Pintu Swing 1 Daun">
                </div>
                <div class="modal-form">
                    <h2 class="modal-form-title">Pintu Sliding 3 Track</h2>
                    <p class="modal-form-sub">Hitung kebutuhan material</p>

                    <!-- Notes -->
                    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px;">
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                            <span style="font-size: 14px;">📋</span>
                            <span style="font-size: 11px; font-weight: 600; color: #92400e;">Informasi Penting</span>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                <span style="color: #d97706; font-weight: 700;">•</span>
                                <span>Dimensi yang dimasukkan adalah ukuran <strong>bersih</strong> lubang pintu (bukaan) dalam satuan <strong>cm</strong></span>
                            </li>
                            <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                <span style="color: #d97706; font-weight: 700;">•</span>
                                <span>Minimum ketebalan kaca yang digunakan adalah <strong>5 mm</strong></span>
                            </li>
                            <li style="font-size: 10px; color: #78350f; padding: 2px 0; display: flex; align-items: flex-start; gap: 5px; line-height: 1.3;">
                                <span style="color: #d97706; font-weight: 700;">•</span>
                                <span>Pintu Swing 1 Daun adalah pintu <strong>buka dengan engsel</strong> dengan 1 daun pintu</span>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('boq.pintu.sliding3track.hitung') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Tinggi (cm)</label>
                            <input type="number" class="input-field" name="tinggi" placeholder="Contoh: 200" required min="1">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Lebar (cm)</label>
                            <input type="number" class="input-field" name="lebar" placeholder="Contoh: 80" required min="1">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Ketebalan Kaca (mm)</label>
                            <input type="number" class="input-field" name="tebal_kaca" placeholder="Contoh: 5" value="5" min="1">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Jumlah Unit</label>
                            <input type="number" class="input-field" name="jumlah" placeholder="Contoh: 2" value="1" min="1" required>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label class="input-label">Warna Profile</label>
                            <select class="input-field" name="warna">
                                <option value="Clear">Clear (Bening)</option>
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
                        <button type="submit" class="btn-primary">Tambahkan ke BOQ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  