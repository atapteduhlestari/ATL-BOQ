<!-- Modal Atap Lengkung + 2 Sisi Miring (3 Bagian) -->
<style>
    /* Sembunyikan scrollbar */
    #modalLengkung2Sisi::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalLengkung2Sisi {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLengkung2Sisi .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLengkung2Sisi .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalLengkung2Sisi" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalLengkung2Sisi')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalLengkung2Sisi')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/pelana-2-sisi-kemiringan.png') }}" 
                             alt="Atap Lengkung + 2 Sisi Miring" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Lengkung + 2 Sisi</h4>
                            <p class="text-xs text-gray-300">Kombinasi atap lengkung dengan dua sisi miring</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Lengkung</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                         <div class="bg-gray-50 border-l-2 border-gray-400 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-medium">Cara menghitung :</p>
                            <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                <div>Bagi bidang menjadi 3 bagian:</div>
                                <div class="pl-2">• <strong>Bagian Kiri</strong> = 1 Kemiringan</div>
                                <div class="pl-2">• <strong>Bagian Tengah</strong> = Setengah Tabung</div>
                                <div class="pl-2">• <strong>Bagian Kanan</strong> = 1 Kemiringan</div>
                            </div>
                        </div>

                        <!-- Bagian 1: Sisi Kiri -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Kiri</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">1 Kemiringan</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="lengkung_panjang_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lengkung_lebar_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="lengkung_sudut_a" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Lengkung (Tengah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Tengah</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Setengah Tabung</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="lengkung_panjang_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lengkung_lebar_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi Lengkung</label>
                                    <input type="number" id="lengkung_tinggi" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Sisi Kanan -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Kanan</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">1 Kemiringan</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="lengkung_panjang_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lengkung_lebar_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="lengkung_sudut_c" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungLengkung2Sisi()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganLengkung2Sisi" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasLengkung" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterLengkung" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500" id="labelNokJuraiLengkung">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiLengkung" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingLengkung" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianLengkung" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_lengkung" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQLengkung2Sisi()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalLengkung2Sisi')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetLengkung2Sisi()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilLengkung2Sisi = null;

function hitungLengkung2Sisi() {
    // Ambil brand dari select BOQ
    let brandSelect = document.getElementById('brand_boq_lengkung');
    let brand = brandSelect ? brandSelect.value : 'iko';
    
    let data = {
        jenis_kombinasi: 'lengkung_2_sisi',
        brand: brand,
        panjang_a: parseFloat(document.getElementById('lengkung_panjang_a').value) || 0,
        lebar_a: parseFloat(document.getElementById('lengkung_lebar_a').value) || 0,
        sudut_a: parseFloat(document.getElementById('lengkung_sudut_a').value) || 0,
        panjang_b: parseFloat(document.getElementById('lengkung_panjang_b').value) || 0,
        lebar_b: parseFloat(document.getElementById('lengkung_lebar_b').value) || 0,
        tinggi: parseFloat(document.getElementById('lengkung_tinggi').value) || 0,
        panjang_c: parseFloat(document.getElementById('lengkung_panjang_c').value) || 0,
        lebar_c: parseFloat(document.getElementById('lengkung_lebar_c').value) || 0,
        sudut_c: parseFloat(document.getElementById('lengkung_sudut_c').value) || 0
    };
    
    if (data.panjang_a <= 0 || data.lebar_a <= 0 || data.sudut_a <= 0 ||
        data.panjang_b <= 0 || data.lebar_b <= 0 || data.tinggi <= 0 ||
        data.panjang_c <= 0 || data.lebar_c <= 0 || data.sudut_c <= 0) {
        alert('Isi semua field dengan nilai > 0!');
        return;
    }
    
    let btn = event.target;
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
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            hasilLengkung2Sisi = resData;
            let total = resData.total;
            
            document.getElementById('totalLuasLengkung').innerHTML = total.luas_atap + ' m²';
            document.getElementById('totalStarterLengkung').innerHTML = total.panjang_starter + ' m';
            document.getElementById('totalFlashingLengkung').innerHTML = total.panjang_flashing + ' m';
            
            // ===== TAMPILKAN NOK & JURAI =====
            let labelElement = document.getElementById('labelNokJuraiLengkung');
            let valueElement = document.getElementById('totalNokJuraiLengkung');
            
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
            resData.details.forEach(item => {
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
            document.getElementById('detailBagianLengkung').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganLengkung2Sisi').classList.remove('hidden');
        } else {
            alert('Error: ' + (resData.message || 'Gagal hitung'));
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Terjadi kesalahan server: ' + err.message); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function resetLengkung2Sisi() {
    document.getElementById('lengkung_panjang_a').value = '';
    document.getElementById('lengkung_lebar_a').value = '';
    document.getElementById('lengkung_sudut_a').value = '30';
    document.getElementById('lengkung_panjang_b').value = '';
    document.getElementById('lengkung_lebar_b').value = '';
    document.getElementById('lengkung_tinggi').value = '';
    document.getElementById('lengkung_panjang_c').value = '';
    document.getElementById('lengkung_lebar_c').value = '';
    document.getElementById('lengkung_sudut_c').value = '30';
    document.getElementById('hasilPerhitunganLengkung2Sisi').classList.add('hidden');
    hasilLengkung2Sisi = null;
}

function lanjutKeBOQLengkung2Sisi() {
    let selectEl = document.getElementById('brand_boq_lengkung');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilLengkung2Sisi) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let d = hasilLengkung2Sisi.details;
    let total = hasilLengkung2Sisi.total;
    
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/lengkung-2-sisi',
        'skyshield': '/boq/atap-kombinasi-skyshield/lengkung-2-sisi',
        'palmex': '/boq/palmex/atap-kombinasi/lengkung-2-sisi',
        'tape-roof': '/boq/taperoof/atap-kombinasi/lengkung-2-sisi',  // <-- TAMBAHKAN TAPE ROOF
        'mahaflat': '/boq/mahaflat/atap-kombinasi/lengkung-2-sisi',  // <-- TAMBAHKAN TAPE ROOF
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/lengkung-2-sisi';
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    
    // ===== BAGIAN 1 (KIRI) =====
    url += `&luas_atap_1=${d[0]?.luas_atap||0}`;
    url += `&starter_1=${d[0]?.starter||0}`;
    url += `&flashing_1=${d[0]?.flashing||0}`;
    url += `&sudut_1=${document.getElementById('lengkung_sudut_a').value}`;
    
    // ===== BAGIAN 2 (LENGKUNG) =====
    url += `&luas_atap_2=${d[1]?.luas_atap||0}`;
    url += `&starter_2=${d[1]?.starter||0}`;
    url += `&flashing_2=${d[1]?.flashing||0}`;
    url += `&tinggi=${document.getElementById('lengkung_tinggi').value}`;
    url += `&panjang_b=${document.getElementById('lengkung_panjang_b').value}`;
    
    // ===== BAGIAN 3 (KANAN) =====
    url += `&luas_atap_3=${d[2]?.luas_atap||0}`;
    url += `&starter_3=${d[2]?.starter||0}`;
    url += `&flashing_3=${d[2]?.flashing||0}`;
    url += `&sudut_3=${document.getElementById('lengkung_sudut_c').value}`;
    
    // ===== BEDAKAN BRAND =====
    if (brandSlug === 'palmex') {
        // PALMEX
        let nokAtas2 = document.getElementById('lengkung_panjang_b').value || 0;
        url += `&jurai_1=0&nok_atas_1=0`;
        url += `&jurai_2=0&nok_atas_2=${nokAtas2}`;
        url += `&jurai_3=0&nok_atas_3=0`;
        url += `&total_jurai=${total?.panjang_jurai||0}`;
        url += `&total_nok_atas=${total?.panjang_nok_atas||0}`;
    } else if (brandSlug === 'tape-roof') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
        url += `&nok_3=${d[2]?.nok_jurai||0}`;
    } 
    else if (brandSlug === 'mahaflat') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
        url += `&nok_3=${d[2]?.nok_jurai||0}`;
    }
    else {
        // IKO/SKYSHIELD
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
        url += `&nok_3=${d[2]?.nok_jurai||0}`;
    }
    
    console.log('Final URL:', url);
    window.location.href = url;
}
</script>