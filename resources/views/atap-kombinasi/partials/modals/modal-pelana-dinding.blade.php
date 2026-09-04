<!-- Modal Atap Pelana + Dinding -->
<style>
    #modalPelanaDinding::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPelanaDinding {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelanaDinding .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelanaDinding .overflow-y-auto::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    .modal-content-scroll::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    .modal-content-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
</style>

<div id="modalPelanaDinding" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPelanaDinding')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPelanaDinding')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/pelana-dinding.png') }}" 
                             alt="Atap Pelana + Dinding" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Pelana + Dinding</h4>
                            <p class="text-xs text-gray-300">Kombinasi atap pelana dengan perhitungan dinding</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap + Dinding</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Bagian Atap Pelana -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Atap Pelana</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Atap</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="pelana_dinding_panjang" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="pelana_dinding_lebar" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="pelana_dinding_sudut" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Dinding -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Dinding</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Dinding</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="pelana_dinding_panjang_dinding" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                    <input type="number" id="pelana_dinding_tinggi" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Jumlah Sisi</label>
                                    <select id="pelana_dinding_jumlah_sisi" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                        <option value="1">1 Sisi</option>
                                        <option value="2" selected>2 Sisi</option>
                                        <option value="3">3 Sisi</option>
                                        <option value="4">4 Sisi</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" id="btnHitungPelanaDinding" onclick="hitungPelanaDinding()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganPelanaDinding" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasPelanaDinding" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Luas Dinding</span>
                                    <span id="totalLuasDindingPelanaDinding" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterPelanaDinding" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500" id="labelNokJuraiPelanaDinding">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiPelanaDinding" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingPelanaDinding" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Wall Flashing</span>
                                    <span id="totalWallFlashingPelanaDinding" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianPelanaDinding" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_pelana_dinding" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQPelanaDinding()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalPelanaDinding')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetPelanaDinding()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilPelanaDinding = null;

function hitungPelanaDinding() {
    // Ambil brand dari select
    let brandSelect = document.getElementById('brand_boq_pelana_dinding');
    let brand = brandSelect ? brandSelect.value : 'iko';
    
    let data = {
        jenis_kombinasi: 'pelana_dinding',
        brand: brand,
        panjang: parseFloat(document.getElementById('pelana_dinding_panjang').value) || 0,
        lebar: parseFloat(document.getElementById('pelana_dinding_lebar').value) || 0,
        sudut: parseFloat(document.getElementById('pelana_dinding_sudut').value) || 0,
        panjang_dinding: parseFloat(document.getElementById('pelana_dinding_panjang_dinding').value) || 0,
        tinggi_dinding: parseFloat(document.getElementById('pelana_dinding_tinggi').value) || 0,
        jumlah_sisi: parseInt(document.getElementById('pelana_dinding_jumlah_sisi').value) || 2
    };
    
    if (data.panjang <= 0 || data.lebar <= 0 || data.sudut <= 0) {
        alert('Isi semua field atap dengan nilai > 0!');
        return;
    }
    
    if (data.panjang_dinding <= 0 || data.tinggi_dinding <= 0) {
        alert('Isi semua field dinding dengan nilai > 0!');
        return;
    }
    
    let btn = document.getElementById('btnHitungPelanaDinding');
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    // Tentukan URL berdasarkan brand
    let url;
    if (brand === 'palmex') {
        url = '{{ route("palmex.kombinasi.hitung") }}';
    } else {
        url = '{{ route("atap-kombinasi.hitung") }}';
    }
    
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            hasilPelanaDinding = resData;
            let total = resData.total;
            
            document.getElementById('totalLuasPelanaDinding').innerHTML = total.luas_atap + ' m²';
            document.getElementById('totalLuasDindingPelanaDinding').innerHTML = total.luas_dinding + ' m²';
            document.getElementById('totalStarterPelanaDinding').innerHTML = total.panjang_starter + ' m';
            document.getElementById('totalFlashingPelanaDinding').innerHTML = total.panjang_flashing + ' m';
            document.getElementById('totalWallFlashingPelanaDinding').innerHTML = total.panjang_wall_flashing + ' m';
            
            // ===== TAMPILKAN NOK & JURAI =====
            let labelElement = document.getElementById('labelNokJuraiPelanaDinding');
            let valueElement = document.getElementById('totalNokJuraiPelanaDinding');
            
            if (brand === 'palmex') {
                // PALMEX: Jurai dan Nok Atas dipisah
                if (labelElement) labelElement.textContent = 'Panjang Jurai & Nok Atas';
                if (valueElement) {
                    valueElement.innerHTML = 
                        'Jurai: ' + total.panjang_jurai + ' m | Nok Atas: ' + total.panjang_nok_atas + ' m';
                }
            } else {
                // IKO/SKYSHIELD/MAHAFLAT/TAPE ROOF: Nok & Jurai digabung
                if (labelElement) labelElement.textContent = 'Panjang Nok & Jurai';
                if (valueElement) {
                    valueElement.innerHTML = total.panjang_nok_jurai + ' m';
                }
            }
            
            // ===== DETAIL PER BAGIAN =====
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            resData.details.forEach((item, index) => {
                let bagianLabel = index === 0 ? 'Atap Pelana' : 'Dinding';
                detailHtml += `<div class="bg-white border border-gray-200 rounded-lg p-2 text-xs">
                    <div class="font-medium text-gray-800">${item.bagian}</div>
                    <div class="grid grid-cols-2 gap-1 mt-1 text-gray-500">`;
                
                if (brand === 'palmex') {
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Jurai: ${item.jurai} m</div>
                        <div>Nok Atas: ${item.nok_atas} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                        ${item.wall_flashing ? `<div>Wall Flashing: ${item.wall_flashing} m</div>` : ''}
                    `;
                } else {
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Nok & Jurai: ${item.nok_jurai} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                        ${item.wall_flashing ? `<div>Wall Flashing: ${item.wall_flashing} m</div>` : ''}
                    `;
                }
                
                detailHtml += `</div></div>`;
            });
            document.getElementById('detailBagianPelanaDinding').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganPelanaDinding').classList.remove('hidden');
        } else {
            alert('Error: ' + (resData.message || 'Gagal hitung'));
        }
    })
    .catch(err => { console.error(err); alert('Terjadi kesalahan server'); })
    .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
}

function resetPelanaDinding() {
    document.getElementById('pelana_dinding_panjang').value = '';
    document.getElementById('pelana_dinding_lebar').value = '';
    document.getElementById('pelana_dinding_sudut').value = '30';
    document.getElementById('pelana_dinding_panjang_dinding').value = '';
    document.getElementById('pelana_dinding_tinggi').value = '';
    document.getElementById('pelana_dinding_jumlah_sisi').value = '2';
    document.getElementById('hasilPerhitunganPelanaDinding').classList.add('hidden');
    hasilPelanaDinding = null;
}

function lanjutKeBOQPelanaDinding() {
    let selectEl = document.getElementById('brand_boq_pelana_dinding');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilPelanaDinding) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let d = hasilPelanaDinding.details;
    let total = hasilPelanaDinding.total;
    
    // Mapping URL berdasarkan brand - TAMBAHKAN MAHAFLAT
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/pelana-dinding',
        'skyshield': '/boq/atap-kombinasi-skyshield/pelana-dinding',
        'palmex': '/boq/palmex/atap-kombinasi/pelana-dinding',
        'tape-roof': '/boq/taperoof/atap-kombinasi/pelana-dinding',
        'mahaflat': '/boq/mahaflat/atap-kombinasi/pelana-dinding',
        'flexi-roof': '/boq/flexiroof/atap-kombinasi/pelana-dinding',
        'eco-roof': '/boq/ecoroof/atap-kombinasi/pelana-dinding',
        'emarin-roof': '/boq/emarinroof/atap-kombinasi/pelana-dinding',
        'master-roof': '/boq/masterroof/atap-kombinasi/pelana-dinding',
        'maha-roof': '/boq/maharoof/atap-kombinasi/pelana-dinding',
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/pelana-dinding';
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    
    // ===== BAGIAN 1 (ATAP PELANA) =====
    url += `&luas_atap_1=${d[0]?.luas_atap || 0}`;
    url += `&starter_1=${d[0]?.starter || 0}`;
    url += `&flashing_1=${d[0]?.flashing || 0}`;
    url += `&sudut_1=${document.getElementById('pelana_dinding_sudut').value}`;
    url += `&nok_1=${d[0]?.nok_jurai || 0}`; // <-- TAMBAHKAN
    
    // ===== BAGIAN 2 (DINDING) =====
    url += `&luas_atap_2=${d[1]?.luas_atap || 0}`;
    url += `&starter_2=${d[1]?.starter || 0}`;
    url += `&flashing_2=${d[1]?.flashing || 0}`;
    url += `&nok_2=${d[1]?.nok_jurai || 0}`; // <-- TAMBAHKAN
    url += `&wall_flashing=${d[1]?.wall_flashing || 0}`;
    
    // ===== TOTAL =====
    let totalNokJurai = (d[0]?.nok_jurai || 0) + (d[1]?.nok_jurai || 0);
    url += `&total_nok_jurai=${totalNokJurai}`;
    url += `&total_luas_dinding=${total?.luas_dinding || 0}`;
    url += `&total_wall_flashing=${total?.panjang_wall_flashing || 0}`;
    
    // ===== INPUTAN USER =====
    url += `&panjang=${document.getElementById('pelana_dinding_panjang').value}`;
    url += `&lebar=${document.getElementById('pelana_dinding_lebar').value}`;
    url += `&panjang_dinding=${document.getElementById('pelana_dinding_panjang_dinding').value}`;
    url += `&tinggi_dinding=${document.getElementById('pelana_dinding_tinggi').value}`;
    url += `&jumlah_sisi=${document.getElementById('pelana_dinding_jumlah_sisi').value}`;
    
    // ===== BEDAKAN BRAND =====
    if (brandSlug === 'palmex') {
        url += `&jurai=${total?.panjang_jurai || 0}`;
        url += `&nok_atas=${total?.panjang_nok_atas || 0}`;
    }
    
    console.log('Final URL:', url);
    window.location.href = url;
}
</script>