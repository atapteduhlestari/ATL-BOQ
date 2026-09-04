<!-- Modal Atap Gergaji / Sawtooth (3 Gerigi) -->
<style>
    /* Sembunyikan scrollbar untuk semua browser */
    #modalGergaji::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalGergaji {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    #modalGergaji .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalGergaji .overflow-y-auto::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    /* Sembunyikan scrollbar di dalam konten modal */
    .modal-content-scroll::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    .modal-content-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
</style>

<div id="modalGergaji" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalGergaji')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalGergaji')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/atap-gergaji.png') }}" 
                             alt="Atap Gergaji" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Gergaji</h4>
                            <p class="text-xs text-gray-300">Atap berbentuk gerigi untuk pabrik/gudang</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Gergaji</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 border-l-2 border-gray-400 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-medium">Cara menghitung :</p>
                            <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                <div>Atap gergaji terdiri dari beberapa gerigi yang sama:</div>
                                <div class="pl-2">• <strong>Jumlah Gerigi</strong> = Banyaknya puncak atap</div>
                                <div class="pl-2">• <strong>Tinggi Gerigi</strong> = Tinggi setiap puncak</div>
                                <div class="pl-2">• <strong>Kemiringan</strong> = Sudut kemiringan atap</div>
                            </div>
                        </div>

                        <!-- Parameter Utama -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="panjang_bangunan" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lebar_bangunan" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Jumlah Gerigi</label>
                                    <input type="number" id="jumlah_gerigi" step="1" min="1" max="10" value="3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi Gerigi</label>
                                    <input type="number" id="tinggi_gerigi" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="sudut" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungGergaji()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganGergaji" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasGergaji" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterGergaji" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500" id="labelNokJuraiGergaji">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiGergaji" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingGergaji" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianGergaji" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_gergaji" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQ()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalGergaji')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetForm()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilGergaji = null;

function hitungGergaji() {
    // Ambil brand dari select BOQ
    let brandSelect = document.getElementById('brand_boq_gergaji');
    let brand = brandSelect ? brandSelect.value : 'iko';
    
    let data = {
        jenis_kombinasi: 'gergaji',
        brand: brand,
        panjang_bangunan: parseFloat(document.getElementById('panjang_bangunan').value) || 0,
        lebar_bangunan: parseFloat(document.getElementById('lebar_bangunan').value) || 0,
        jumlah_gerigi: parseFloat(document.getElementById('jumlah_gerigi').value) || 1,
        tinggi_gerigi: parseFloat(document.getElementById('tinggi_gerigi').value) || 0,
        sudut: parseFloat(document.getElementById('sudut').value) || 30
    };
    
    if (data.panjang_bangunan <= 0 || data.lebar_bangunan <= 0 || data.jumlah_gerigi <= 0 || data.tinggi_gerigi <= 0) {
        alert('⚠️ Isi semua field dengan nilai > 0!');
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
            hasilGergaji = resData;
            let total = resData.total;
            
            document.getElementById('totalLuasGergaji').innerHTML = total.luas_atap + ' m²';
            document.getElementById('totalStarterGergaji').innerHTML = total.panjang_starter + ' m';
            document.getElementById('totalFlashingGergaji').innerHTML = total.panjang_flashing + ' m';
            
            // ===== TAMPILKAN NOK & JURAI =====
            let labelElement = document.getElementById('labelNokJuraiGergaji');
            let valueElement = document.getElementById('totalNokJuraiGergaji');
            
            if (brand === 'palmex') {
                // PALMEX: HANYA NOK ATAS, TIDAK ADA JURAI
                if (labelElement) labelElement.textContent = 'Panjang Nok Atas';
                if (valueElement) {
                    valueElement.innerHTML = total.panjang_nok_atas + ' m';
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
                    // PALMEX: Hanya Nok Atas
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
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
            document.getElementById('detailBagianGergaji').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganGergaji').classList.remove('hidden');
        } else {
            alert('Error: ' + (resData.message || 'Gagal hitung'));
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Terjadi kesalahan server'); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function resetForm() {
    document.getElementById('panjang_bangunan').value = '';
    document.getElementById('lebar_bangunan').value = '';
    document.getElementById('jumlah_gerigi').value = '3';
    document.getElementById('tinggi_gerigi').value = '';
    document.getElementById('sudut').value = '30';
    document.getElementById('hasilPerhitunganGergaji').classList.add('hidden');
    hasilGergaji = null;
}

function lanjutKeBOQ() {
    let selectEl = document.getElementById('brand_boq_gergaji');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilGergaji) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let total = hasilGergaji.total;
    let details = hasilGergaji.details;
    
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/gergaji',
        'skyshield': '/boq/atap-kombinasi-skyshield/gergaji',
        'palmex': '/boq/palmex/atap-kombinasi/gergaji',
        'tape-roof': '/boq/taperoof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'mahaflat': '/boq/mahaflat/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'flexi-roof': '/boq/flexiroof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'eco-roof': '/boq/ecoroof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'emarin-roof': '/boq/emarinroof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'master-roof': '/boq/masterroof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
        'maha-roof': '/boq/maharoof/atap-kombinasi/gergaji',  // <-- TAMBAHKAN TAPE ROOF
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/gergaji';
    
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    url += `&luas_atap=${total.luas_atap}`;
    url += `&starter=${total.panjang_starter}`;
    url += `&flashing=${total.panjang_flashing}`;
    url += `&talang_jurai=${total.talang_jurai || 0}`;
    url += `&sudut=${document.getElementById('sudut').value}`;
    url += `&panjang_bangunan=${document.getElementById('panjang_bangunan').value}`;
    url += `&lebar_bangunan=${document.getElementById('lebar_bangunan').value}`;
    url += `&jumlah_gerigi=${document.getElementById('jumlah_gerigi').value}`;
    url += `&tinggi_gerigi=${document.getElementById('tinggi_gerigi').value}`;
    
    if (brandSlug === 'palmex') {
        url += `&nok_atas=${total.panjang_nok_atas}`;
        url += `&total_nok_atas=${total.panjang_nok_atas}`;
        url += `&jurai=0`;
        url += `&total_jurai=0`;
    } else if (brandSlug === 'tape-roof') {
        // TAPE ROOF: Pakai Nok & Jurai (sama seperti IKO/SKYSHIELD)
        url += `&nok_jurai=${total.panjang_nok_jurai}`;
        url += `&panjang_nok=${total.panjang_nok_jurai}`;
        url += `&panjang_jurai=0`;
    } else {
        url += `&nok_jurai=${total.panjang_nok_jurai}`;
    }
    
    console.log('Final URL:', url);
    window.location.href = url;
}
</script>