<!-- Modal Atap Trapesium Kotak - VERSI PALING SIMPLE -->
<div id="modalTrapesiumKotak" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999; padding:20px; overflow-y:auto;">
    <div style="display:flex; align-items:center; justify-content:center; min-height:100vh;">
        <div style="background:white; border-radius:16px; max-width:880px; width:100%; max-height:90vh; overflow-y:auto; padding:30px; position:relative;">
            <button onclick="document.getElementById('modalTrapesiumKotak').style.display='none'; document.body.style.overflow='';" style="position:absolute; top:10px; right:15px; font-size:24px; background:none; border:none; cursor:pointer;">&times;</button>
            
            <div style="display:flex; flex-direction:column; gap:16px;">
                <h3 style="font-size:18px; font-weight:600;">Atap Trapesium Kotak</h3>
                <p style="font-size:13px; color:#94a3b8;">Masukkan ukuran untuk estimasi material</p>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label style="font-size:10px; font-weight:500; color:#94a3b8; text-transform:uppercase;">Panjang Atas</label>
                        <input type="number" id="tk_panjang_atas" step="0.1" style="width:100%; padding:8px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px;">
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:500; color:#94a3b8; text-transform:uppercase;">Panjang Bawah</label>
                        <input type="number" id="tk_panjang_bawah" step="0.1" style="width:100%; padding:8px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px;">
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:500; color:#94a3b8; text-transform:uppercase;">Tinggi</label>
                        <input type="number" id="tk_tinggi" step="0.1" style="width:100%; padding:8px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px;">
                    </div>
                    <div>
                        <label style="font-size:10px; font-weight:500; color:#94a3b8; text-transform:uppercase;">Kemiringan</label>
                        <input type="number" id="tk_sudut" step="1" min="1" max="89" value="30" style="width:100%; padding:8px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px;">
                    </div>
                </div>
                
                <button onclick="hitungTrapesiumKotak()" style="width:100%; padding:10px; background:#1a1a2e; color:white; border:none; border-radius:6px; font-size:13px; font-weight:500; cursor:pointer;">Hitung Luas & Estimasi</button>
                
                <div id="hasilPerhitunganTrapesiumKotak" style="display:none; background:#f8fafc; border-radius:8px; padding:12px 14px; border:1px solid #eef2f6;">
                    <div style="font-size:12px; font-weight:600; color:#1a1a2e; margin-bottom:4px;">Total Keseluruhan</div>
                    <div style="display:flex; justify-content:space-between; padding:3px 0; font-size:12px; border-bottom:1px solid #f1f4f9;">
                        <span style="color:#94a3b8;">Luas Atap</span>
                        <span id="tk_luasAtap" style="font-weight:500; color:#1a1a2e;">- m²</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:3px 0; font-size:12px; border-bottom:1px solid #f1f4f9;">
                        <span style="color:#94a3b8;">Panjang Sisi Miring</span>
                        <span id="tk_sisiMiring" style="font-weight:500; color:#1a1a2e;">- m</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:3px 0; font-size:12px; border-bottom:1px solid #f1f4f9;">
                        <span style="color:#94a3b8;">Keliling / Starter</span>
                        <span id="tk_starter" style="font-weight:500; color:#1a1a2e;">- m</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:3px 0; font-size:12px;">
                        <span style="color:#94a3b8;">Nok & Jurai</span>
                        <span id="tk_nokJurai" style="font-weight:500; color:#1a1a2e;">- m</span>
                    </div>
                </div>
                <div id="detailTrapesiumKotak" class="mt-3 space-y-2"></div>
                
                <div style="margin-top:12px; padding-top:12px; border-top:1px solid #eef2f6;">
                    <label style="font-size:10px; font-weight:500; color:#94a3b8; text-transform:uppercase;">Pilih Brand</label>
                    <select id="brand_boq_trapesium_kotak" style="width:100%; padding:8px 10px; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; background:white;">
                        <option value="">-- Pilih Brand --</option>
                        @foreach($brands ?? [] as $brand)
                            <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                        @endforeach
                    </select>
                    <button onclick="lanjutKeBOQTrapesiumKotak()" style="width:100%; padding:10px; background:#1a1a2e; color:white; border:none; border-radius:6px; font-size:13px; font-weight:500; cursor:pointer; margin-top:8px;">Lanjut ke BOQ</button>
                </div>
            </div>
        </div>
    </div>
</div>