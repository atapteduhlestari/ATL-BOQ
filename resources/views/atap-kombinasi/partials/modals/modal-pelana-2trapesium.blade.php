<!-- Modal Atap Kombinasi - Pelana + 2 Trapesium -->
<style>
    #modalPelana2Trapesium::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPelana2Trapesium {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana2Trapesium .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana2Trapesium .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalPelana2Trapesium" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPelana2Trapesium')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPelana2Trapesium')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/pelana-2-trapesium.png') }}" 
                             alt="Pelana + 2 Trapesium" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Pelana 2 Trapesium</h4>
                            <p class="text-xs text-gray-300">Kombinasi pelana dengan dua trapesium</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Kombinasi</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 border-l-2 border-gray-400 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-medium">Cara menghitung :</p>
                            <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                <div>Bagi bidang menjadi 3 bagian:</div>
                                <div class="pl-2">• <strong>Bagian Atas</strong> = Pelana</div>
                                <div class="pl-2">• <strong>Bagian Bawah</strong> = Trapesium</div>
                                <div class="pl-6">∘ <strong>Bagian Bawah Terbagi 2</strong> = Bagian (Kanan-Kiri) dan (Depan-Belakang)</div>
                            </div>
                        </div>

                        <!-- Bagian 1: Pelana (Tengah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Atas - Pelana</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Atas</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" 
                                           id="panjang_pelana_tengah" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" 
                                           id="lebar_pelana_tengah" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" 
                                           id="sudut_pelana_tengah" 
                                           step="1"
                                           min="1"
                                           max="89"
                                           value="30"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Trapesium A (Kiri-Kanan) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Bawah - Trapesium</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">(Kiri - Kanan)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Atas</label>
                                    <input type="number" 
                                           id="panjang_atas_a" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Bawah</label>
                                    <input type="number" 
                                           id="panjang_bawah_a" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                    <input type="number" 
                                           id="tinggi_a" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" 
                                           id="sudut_a" 
                                           step="1"
                                           min="1"
                                           max="89"
                                           value="30"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Trapesium B (Depan-Belakang) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Bawah - Trapesium</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">(Depan - Belakang)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Atas</label>
                                    <input type="number" 
                                           id="panjang_atas_b" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Bawah</label>
                                    <input type="number" 
                                           id="panjang_bawah_b" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                    <input type="number" 
                                           id="tinggi_b" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" 
                                           id="sudut_b" 
                                           step="1"
                                           min="1"
                                           max="89"
                                           value="30"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungKombinasiPelana2Trapesium()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganPelana2Trapesium" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasPelana2" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterPelana2" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500" id="labelNokJuraiPelana2">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiPelana2" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingPelana2" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianPelana2Trapesium" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_pelana_2trapesium" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQPelana2Trapesium()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalPelana2Trapesium')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormPelana2Trapesium()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilKombinasiPelana2Trapesium = null;

function hitungKombinasiPelana2Trapesium() {
    // Ambil brand dari select BOQ
    let brandSelect = document.getElementById('brand_boq_pelana_2trapesium');
    let brand = brandSelect ? brandSelect.value : 'iko';
    
    let data = {
        jenis_kombinasi: 'pelana_2trapesium',
        brand: brand,
        panjang_pelana: parseFloat(document.getElementById('panjang_pelana_tengah').value) || 0,
        lebar_pelana: parseFloat(document.getElementById('lebar_pelana_tengah').value) || 0,
        sudut_pelana: parseFloat(document.getElementById('sudut_pelana_tengah').value) || 0,
        panjang_atas_trapesium_a: parseFloat(document.getElementById('panjang_atas_a').value) || 0,
        panjang_bawah_trapesium_a: parseFloat(document.getElementById('panjang_bawah_a').value) || 0,
        tinggi_trapesium_a: parseFloat(document.getElementById('tinggi_a').value) || 0,
        sudut_trapesium_a: parseFloat(document.getElementById('sudut_a').value) || 0,
        panjang_atas_trapesium_b: parseFloat(document.getElementById('panjang_atas_b').value) || 0,
        panjang_bawah_trapesium_b: parseFloat(document.getElementById('panjang_bawah_b').value) || 0,
        tinggi_trapesium_b: parseFloat(document.getElementById('tinggi_b').value) || 0,
        sudut_trapesium_b: parseFloat(document.getElementById('sudut_b').value) || 0
    };
    
    if (data.panjang_pelana <= 0 || data.lebar_pelana <= 0 || data.sudut_pelana <= 0) {
        alert('Isi semua field pada bagian Pelana dengan nilai > 0!');
        return;
    }
    
    if (data.panjang_atas_trapesium_a <= 0 || data.panjang_bawah_trapesium_a <= 0 || data.tinggi_trapesium_a <= 0) {
        alert('Isi semua field pada bagian Trapesium A dengan nilai > 0!');
        return;
    }
    
    if (data.panjang_atas_trapesium_b <= 0 || data.panjang_bawah_trapesium_b <= 0 || data.tinggi_trapesium_b <= 0) {
        alert('Isi semua field pada bagian Trapesium B dengan nilai > 0!');
        return;
    }
    
    let btn = event.target;
    if (btn.tagName !== 'BUTTON') {
        btn = btn.closest('button');
    }
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
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            hasilKombinasiPelana2Trapesium = responseData;
            let total = responseData.total;
            
            document.getElementById('totalLuasPelana2').innerHTML = total.luas_atap + ' m²';
            document.getElementById('totalStarterPelana2').innerHTML = total.panjang_starter + ' m';
            document.getElementById('totalFlashingPelana2').innerHTML = total.panjang_flashing + ' m';
            
            // ===== TAMPILKAN NOK & JURAI =====
            let labelElement = document.getElementById('labelNokJuraiPelana2');
            let valueElement = document.getElementById('totalNokJuraiPelana2');
            
            if (brand === 'palmex') {
                // PALMEX: Jurai dan Nok Atas dipisah
                if (labelElement) labelElement.textContent = 'Panjang Jurai & Nok Atas';
                if (valueElement) {
                    valueElement.innerHTML = 
                        'Jurai: ' + total.panjang_jurai + ' m | Nok Atas: ' + total.panjang_nok_atas + ' m';
                }
            } else {
                // IKO/SKYSHIELD: Nok & Jurai digabung
                if (labelElement) labelElement.textContent = 'Panjang Nok & Jurai';
                if (valueElement) {
                    valueElement.innerHTML = total.panjang_nok_jurai + ' m';
                }
            }
            
            // ===== DETAIL PER BAGIAN =====
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            responseData.details.forEach(item => {
                detailHtml += `<div class="bg-white border border-gray-200 rounded-lg p-2 text-xs">
                    <div class="font-medium text-gray-800">${item.bagian}</div>
                    <div class="grid grid-cols-2 gap-1 mt-1 text-gray-500">`;
                
                if (brand === 'palmex') {
                    // PALMEX: Jurai dan Nok Atas
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Jurai: ${item.jurai} m</div>
                        <div>Nok Atas: ${item.nok_atas} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                    `;
                } else {
                    // IKO/SKYSHIELD: Nok & Jurai digabung
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Nok & Jurai: ${item.nok_jurai} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                    `;
                }
                
                detailHtml += `</div></div>`;
            });
            document.getElementById('detailBagianPelana2Trapesium').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganPelana2Trapesium').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan: ' + (responseData.message || 'Unknown error'));
        }
    })
    .catch(error => { 
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server'); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function resetFormPelana2Trapesium() {
    document.getElementById('panjang_pelana_tengah').value = '';
    document.getElementById('lebar_pelana_tengah').value = '';
    document.getElementById('sudut_pelana_tengah').value = '30';
    document.getElementById('panjang_atas_a').value = '';
    document.getElementById('panjang_bawah_a').value = '';
    document.getElementById('tinggi_a').value = '';
    document.getElementById('sudut_a').value = '30';
    document.getElementById('panjang_atas_b').value = '';
    document.getElementById('panjang_bawah_b').value = '';
    document.getElementById('tinggi_b').value = '';
    document.getElementById('sudut_b').value = '30';
    document.getElementById('hasilPerhitunganPelana2Trapesium').classList.add('hidden');
    hasilKombinasiPelana2Trapesium = null;
}

function lanjutKeBOQPelana2Trapesium() {
    let selectEl = document.getElementById('brand_boq_pelana_2trapesium');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilKombinasiPelana2Trapesium) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let details = hasilKombinasiPelana2Trapesium.details;
    let total = hasilKombinasiPelana2Trapesium.total;
    
    let sudutPelana = parseFloat(document.getElementById('sudut_pelana_tengah').value) || 0;
    let sudutA = parseFloat(document.getElementById('sudut_a').value) || 0;
    let sudutB = parseFloat(document.getElementById('sudut_b').value) || 0;
    
    // MAPPING URL - TAMBAHKAN TAPE ROOF
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/pelana-2trapesium',
        'skyshield': '/boq/atap-kombinasi-skyshield/pelana-2trapesium',
        'palmex': '/boq/palmex/atap-kombinasi/pelana-2trapesium',
        'tape-roof': '/boq/taperoof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'mahaflat': '/boq/mahaflat/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'flexi-roof': '/boq/flexiroof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'eco-roof': '/boq/ecoroof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'emarin-roof': '/boq/emarinroof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'master-roof': '/boq/masterroof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'maha-roof': '/boq/maharoof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'mahaspan-roof': '/boq/mahaspanroof/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
        'flexideck-seam': '/boq/flexideckseam/atap-kombinasi/pelana-2trapesium',  // <-- TAMBAHKAN
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/pelana-2trapesium';
    
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    
    // ===== BAGIAN 1 (PELANA) =====
    url += `&luas_atap_1=${details[0]?.luas_atap||0}`;
    url += `&sudut_1=${sudutPelana}`;
    url += `&starter_1=${details[0]?.starter||0}`;
    url += `&flashing_1=${details[0]?.flashing||0}`;
    
    // ===== BAGIAN 2 (TRAPESIUM A) =====
    url += `&luas_atap_2=${details[1]?.luas_atap||0}`;
    url += `&sudut_2=${sudutA}`;
    url += `&starter_2=${details[1]?.starter||0}`;
    url += `&flashing_2=${details[1]?.flashing||0}`;
    
    // ===== BAGIAN 3 (TRAPESIUM B) =====
    url += `&luas_atap_3=${details[2]?.luas_atap||0}`;
    url += `&sudut_3=${sudutB}`;
    url += `&starter_3=${details[2]?.starter||0}`;
    url += `&flashing_3=${details[2]?.flashing||0}`;
    
    // ===== BEDAKAN BRAND =====
    if (brandSlug === 'palmex') {
        url += `&jurai_1=0`;
        url += `&nok_atas_1=${details[0]?.nok_atas||0}`;
        url += `&jurai_2=${details[1]?.jurai||0}`;
        url += `&nok_atas_2=0`;
        url += `&jurai_3=${details[2]?.jurai||0}`;
        url += `&nok_atas_3=0`;
        url += `&total_jurai=${total?.panjang_jurai||0}`;
        url += `&total_nok_atas=${total?.panjang_nok_atas||0}`;
    } else if (brandSlug === 'tape-roof') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${details[0]?.nok_jurai||0}`;
        url += `&nok_2=${details[1]?.nok_jurai||0}`;
        url += `&nok_3=${details[2]?.nok_jurai||0}`;
    }  else if (brandSlug === 'mahaflat') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${details[0]?.nok_jurai||0}`;
        url += `&nok_2=${details[1]?.nok_jurai||0}`;
        url += `&nok_3=${details[2]?.nok_jurai||0}`;
    }
    else {
        // IKO/SKYSHIELD
        url += `&nok_1=${details[0]?.nok_jurai||0}`;
        url += `&nok_2=${details[1]?.nok_jurai||0}`;
        url += `&nok_3=${details[2]?.nok_jurai||0}`;
    }
    
    console.log('Final URL:', url);
    window.location.href = url;
}
</script>