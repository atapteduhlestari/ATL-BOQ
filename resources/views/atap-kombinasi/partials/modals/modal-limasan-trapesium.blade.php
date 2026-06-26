<!-- Modal Atap Kombinasi - Limasan + Trapesium -->
<style>
    #modalLimasanTrapesium::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalLimasanTrapesium {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasanTrapesium .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasanTrapesium .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalLimasanTrapesium" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalLimasanTrapesium')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalLimasanTrapesium')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/trapesium-limasan2.png') }}" 
                             alt="Kombinasi Limasan + Trapesium" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Limasan + Trapesium</h4>
                            <p class="text-xs text-gray-300">Kombinasi limasan atas dengan trapesium bawah</p>
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
                        <!-- Notes -->
                        <div class="bg-gray-50 border-l-2 border-gray-400 rounded-lg p-3">
                            <p class="text-xs text-gray-600 font-medium">Catatan:</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Bagi bidang menjadi 2 bagian:<br>
                                • <strong>Bagian Atas</strong> = Limasan<br>
                                • <strong>Bagian Bawah</strong> = Trapesium
                            </p>
                        </div>

                        <!-- Bagian 1: Limasan (Atas) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Atas - Limasan</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Atas</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" 
                                           id="panjang_limasan" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" 
                                           id="lebar_limasan" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" 
                                           id="sudut_limasan" 
                                           step="1"
                                           min="1"
                                           max="89"
                                           value="30"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Trapesium (Bawah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Bawah - Trapesium</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bawah</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Atas</label>
                                    <input type="number" 
                                           id="panjang_atas_trapesium" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Bawah</label>
                                    <input type="number" 
                                           id="panjang_bawah_trapesium" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                    <input type="number" 
                                           id="tinggi_trapesium" 
                                           step="0.1"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" 
                                           id="sudut_trapesium" 
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
                                onclick="hitungKombinasi()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganLimasanTrapesium" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasLimasanTrapesium" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterLimasanTrapesium" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiLimasanTrapesium" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingLimasanTrapesium" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianLimasanTrapesium" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_limasan_trapesium" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQLimasanTrapesium()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalLimasanTrapesium')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormLimasanTrapesium()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilKombinasiLimasanTrapesium = null;

function hitungKombinasi() {
    let data = {
        jenis_kombinasi: 'limasan_trapesium',
        panjang_limasan: parseFloat(document.getElementById('panjang_limasan').value) || 0,
        lebar_limasan: parseFloat(document.getElementById('lebar_limasan').value) || 0,
        sudut_limasan: parseFloat(document.getElementById('sudut_limasan').value) || 0,
        panjang_atas_trapesium: parseFloat(document.getElementById('panjang_atas_trapesium').value) || 0,
        panjang_bawah_trapesium: parseFloat(document.getElementById('panjang_bawah_trapesium').value) || 0,
        tinggi_trapesium: parseFloat(document.getElementById('tinggi_trapesium').value) || 0,
        sudut_trapesium: parseFloat(document.getElementById('sudut_trapesium').value) || 0
    };
    
    if (!data.panjang_limasan || !data.lebar_limasan || !data.sudut_limasan) {
        alert('Isi semua field pada bagian Limasan!');
        return;
    }
    
    if (!data.panjang_atas_trapesium || !data.panjang_bawah_trapesium || !data.tinggi_trapesium || !data.sudut_trapesium) {
        alert('Isi semua field pada bagian Trapesium!');
        return;
    }
    
    let btn = event.target;
    if (btn.tagName !== 'BUTTON') {
        btn = btn.closest('button');
    }
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    fetch('{{ route("atap-kombinasi.hitung") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hasilKombinasiLimasanTrapesium = data;
            document.getElementById('totalLuasLimasanTrapesium').innerHTML = data.total.luas_atap + ' m²';
            document.getElementById('totalStarterLimasanTrapesium').innerHTML = data.total.panjang_starter + ' m';
            document.getElementById('totalNokJuraiLimasanTrapesium').innerHTML = data.total.panjang_nok_jurai + ' m';
            document.getElementById('totalFlashingLimasanTrapesium').innerHTML = data.total.panjang_flashing + ' m';
            
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            data.details.forEach(item => {
                detailHtml += `<div class="bg-white border border-gray-200 rounded-lg p-2 text-xs">
                    <div class="font-medium text-gray-800">${item.bagian}</div>
                    <div class="grid grid-cols-2 gap-1 mt-1 text-gray-500">
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Nok & Jurai: ${item.nok_jurai} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                    </div>
                </div>`;
            });
            document.getElementById('detailBagianLimasanTrapesium').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganLimasanTrapesium').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
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

function resetFormLimasanTrapesium() {
    document.getElementById('panjang_limasan').value = '';
    document.getElementById('lebar_limasan').value = '';
    document.getElementById('sudut_limasan').value = '30';
    document.getElementById('panjang_atas_trapesium').value = '';
    document.getElementById('panjang_bawah_trapesium').value = '';
    document.getElementById('tinggi_trapesium').value = '';
    document.getElementById('sudut_trapesium').value = '30';
    document.getElementById('hasilPerhitunganLimasanTrapesium').classList.add('hidden');
    hasilKombinasiLimasanTrapesium = null;
}

function lanjutKeBOQLimasanTrapesium() {
    let selectEl = document.getElementById('brand_boq_limasan_trapesium');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilKombinasiLimasanTrapesium) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let details = hasilKombinasiLimasanTrapesium.details;
    
    let luas1 = details[0]?.luas_atap || 0;
    let starter1 = details[0]?.starter || 0;
    let nok1 = details[0]?.nok_jurai || 0;
    let flashing1 = details[0]?.flashing || 0;
    
    let luas2 = details[1]?.luas_atap || 0;
    let starter2 = details[1]?.starter || 0;
    let nok2 = details[1]?.nok_jurai || 0;
    let flashing2 = details[1]?.flashing || 0;
    
    let sudutLimasan = parseFloat(document.getElementById('sudut_limasan').value) || 0;
    let sudutTrapesium = parseFloat(document.getElementById('sudut_trapesium').value) || 0;
    
    // Mapping URL berdasarkan brand
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/limasan-trapesium',
        'skyshield': '/boq/atap-kombinasi-skyshield/limasan-trapesium',
    };
    
    let url = controllerMap[brandSlug] || '/boq/atap-kombinasi/limasan-trapesium';
    
    window.location.href = `${url}?luas_atap_1=${luas1}&sudut_1=${sudutLimasan}&starter_1=${starter1}&nok_1=${nok1}&flashing_1=${flashing1}&luas_atap_2=${luas2}&sudut_2=${sudutTrapesium}&starter_2=${starter2}&nok_2=${nok2}&flashing_2=${flashing2}`;
}
</script>