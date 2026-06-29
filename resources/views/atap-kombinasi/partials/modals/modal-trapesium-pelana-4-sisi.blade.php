<!-- Modal Trapesium + Pelana 4 Sisi -->
<style>
    #modalTrapesiumPelana4Sisi::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalTrapesiumPelana4Sisi {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalTrapesiumPelana4Sisi .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalTrapesiumPelana4Sisi .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalTrapesiumPelana4Sisi" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalTrapesiumPelana4Sisi')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalTrapesiumPelana4Sisi')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/trapesium-pelana.png') }}" 
                             alt="Trapesium + Pelana 4 Sisi" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Trapesium + Pelana 4 Sisi</h4>
                            <p class="text-xs text-gray-300">Kombinasi atap trapesium dengan pelana 4 sisi</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Hitung Trapesium + Pelana 4 Sisi</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- BAGIAN TRAPESIUM (×4 SISI) -->
                        <div class="border rounded-lg p-4 bg-gray-50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-semibold text-gray-700">📐 TRAPESIUM</span>
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">×4 Sisi</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Atas (m)</label>
                                    <input type="number" id="trapesium_panjang_atas" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Bawah (m)</label>
                                    <input type="number" id="trapesium_panjang_bawah" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi (m)</label>
                                    <input type="number" id="trapesium_tinggi" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan (°)</label>
                                    <input type="number" id="trapesium_sudut" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">*Karena 4 sisi sama, hasil akan dikalikan 4</p>
                        </div>

                        <!-- BAGIAN PELANA 4 SISI (×4) -->
                        <div class="border rounded-lg p-4 bg-gray-50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-semibold text-gray-700">📐 PELANA 4 SISI</span>
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">×4 Sisi</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang (m)</label>
                                    <input type="number" id="pelana_panjang" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar (m)</label>
                                    <input type="number" id="pelana_lebar" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan (°)</label>
                                    <input type="number" id="pelana_sudut" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">*Karena 4 sisi sama, hasil akan dikalikan 4</p>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungTrapesiumPelana4Sisi()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganTrapesiumPelana4Sisi" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>📊 TOTAL KESELURUHAN</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap Total</span>
                                    <span id="totalLuasTrapesiumPelana4Sisi" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter Total</span>
                                    <span id="totalStarterTrapesiumPelana4Sisi" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Nok & Jurai Total</span>
                                    <span id="totalNokJuraiTrapesiumPelana4Sisi" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing Total</span>
                                    <span id="totalFlashingTrapesiumPelana4Sisi" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianTrapesiumPelana4Sisi" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand <span class="text-red-500">*</span>
                            </label>
                            <select id="brand_boq_trapesium4" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQTrapesiumPelana4Sisi()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                Lanjut ke BOQ →
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalTrapesiumPelana4Sisi')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetTrapesiumPelana4Sisi()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilTrapesiumPelana4Sisi = null;

function hitungTrapesiumPelana4Sisi() {
    let data = {
        jenis_kombinasi: 'trapesium_pelana_4sisi',
        panjang_atas: parseFloat(document.getElementById('trapesium_panjang_atas').value) || 0,
        panjang_bawah: parseFloat(document.getElementById('trapesium_panjang_bawah').value) || 0,
        tinggi: parseFloat(document.getElementById('trapesium_tinggi').value) || 0,
        sudut_trapesium: parseFloat(document.getElementById('trapesium_sudut').value) || 0,
        panjang_pelana: parseFloat(document.getElementById('pelana_panjang').value) || 0,
        lebar_pelana: parseFloat(document.getElementById('pelana_lebar').value) || 0,
        sudut_pelana: parseFloat(document.getElementById('pelana_sudut').value) || 0
    };
    
    if (data.panjang_atas <= 0 || data.panjang_bawah <= 0 || data.tinggi <= 0 || data.sudut_trapesium <= 0 ||
        data.panjang_pelana <= 0 || data.lebar_pelana <= 0 || data.sudut_pelana <= 0) {
        alert('⚠️ Isi semua field dengan nilai > 0!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    fetch('/atap-kombinasi/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            hasilTrapesiumPelana4Sisi = resData;
            
            document.getElementById('totalLuasTrapesiumPelana4Sisi').innerHTML = resData.total.luas_atap + ' m²';
            document.getElementById('totalStarterTrapesiumPelana4Sisi').innerHTML = resData.total.panjang_starter + ' m';
            document.getElementById('totalNokJuraiTrapesiumPelana4Sisi').innerHTML = resData.total.panjang_nok_jurai + ' m';
            document.getElementById('totalFlashingTrapesiumPelana4Sisi').innerHTML = resData.total.panjang_flashing + ' m';
            
            let detailHtml = '<div class="text-xs font-semibold text-gray-700 mb-1">📋 Detail Per Bagian</div>';
            resData.details.forEach(item => {
                detailHtml += `<div class="bg-gray-50 rounded-lg p-2 text-xs">
                    <div class="font-medium text-gray-700">${item.bagian}</div>
                    <div class="grid grid-cols-2 gap-1 mt-1">
                        <div class="text-gray-500">Luas: ${item.luas_atap} m²</div>
                        <div class="text-gray-500">Starter: ${item.starter} m</div>
                        <div class="text-gray-500">Nok & Jurai: ${item.nok_jurai} m</div>
                        <div class="text-gray-500">Flashing: ${item.flashing} m</div>
                    </div>
                </div>`;
            });
            document.getElementById('detailBagianTrapesiumPelana4Sisi').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganTrapesiumPelana4Sisi').classList.remove('hidden');
        } else {
            alert('Error: ' + (resData.message || 'Gagal hitung'));
        }
    })
    .catch(err => { console.error(err); alert('Terjadi kesalahan server'); })
    .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
}

function resetTrapesiumPelana4Sisi() {
    document.getElementById('trapesium_panjang_atas').value = '';
    document.getElementById('trapesium_panjang_bawah').value = '';
    document.getElementById('trapesium_tinggi').value = '';
    document.getElementById('trapesium_sudut').value = '30';
    document.getElementById('pelana_panjang').value = '';
    document.getElementById('pelana_lebar').value = '';
    document.getElementById('pelana_sudut').value = '30';
    
    document.getElementById('hasilPerhitunganTrapesiumPelana4Sisi').classList.add('hidden');
    hasilTrapesiumPelana4Sisi = null;
}

function lanjutKeBOQTrapesiumPelana4Sisi() {
    let brandSlug = document.getElementById('brand_boq_trapesium4').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilTrapesiumPelana4Sisi) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let d = hasilTrapesiumPelana4Sisi.details;
    
    let url = `/boq/atap-kombinasi/trapesium-pelana-4sisi?brand_slug=${brandSlug}`;
    url += `&luas_atap_trapesium=${d[0]?.luas_atap||0}&starter_trapesium=${d[0]?.starter||0}&nok_trapesium=${d[0]?.nok_jurai||0}&flashing_trapesium=${d[0]?.flashing||0}`;
    url += `&luas_atap_pelana=${d[1]?.luas_atap||0}&starter_pelana=${d[1]?.starter||0}&nok_pelana=${d[1]?.nok_jurai||0}&flashing_pelana=${d[1]?.flashing||0}`;
    url += `&panjang_atas=${document.getElementById('trapesium_panjang_atas').value}`;
    url += `&panjang_bawah=${document.getElementById('trapesium_panjang_bawah').value}`;
    url += `&tinggi=${document.getElementById('trapesium_tinggi').value}`;
    url += `&sudut_trapesium=${document.getElementById('trapesium_sudut').value}`;
    url += `&panjang_pelana=${document.getElementById('pelana_panjang').value}`;
    url += `&lebar_pelana=${document.getElementById('pelana_lebar').value}`;
    url += `&sudut_pelana=${document.getElementById('pelana_sudut').value}`;
    
    window.location.href = url;
}
</script>