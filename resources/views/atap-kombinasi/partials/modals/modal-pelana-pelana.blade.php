<!-- Modal Atap Kombinasi - Pelana + Pelana + Pelana -->
<style>
    #modalPelana3::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPelana3 {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana3 .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana3 .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalPelana3" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPelana3')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPelana3')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/pelana-pelana.png') }}" 
                             alt="Pelana + Pelana + Pelana" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Pelana + Pelana + Pelana</h4>
                            <p class="text-xs text-gray-300">Kombinasi tiga atap pelana bersusun</p>
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
                        <!-- Bagian 1: Pelana A (Atas) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Atas - Pelana A</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Atas</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="panjang_pelana_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lebar_pelana_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="sudut_pelana_a" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Pelana B (Tengah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Tengah - Pelana B</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Tengah</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="panjang_pelana_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lebar_pelana_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="sudut_pelana_b" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Pelana C (Bawah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Bawah - Pelana C</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bawah</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="panjang_pelana_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="lebar_pelana_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="sudut_pelana_c" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungKombinasiPelana3()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganPelana3" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasPelana3" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterPelana3" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiPelana3" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingPelana3" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianPelana3" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_pelana3" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQPelana3()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalPelana3')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormPelana3()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilKombinasiPelana3 = null;

function hitungKombinasiPelana3() {
    let data = {
        jenis_kombinasi: 'pelana_3',
        panjang_pelana_a: parseFloat(document.getElementById('panjang_pelana_a').value) || 0,
        lebar_pelana_a: parseFloat(document.getElementById('lebar_pelana_a').value) || 0,
        sudut_pelana_a: parseFloat(document.getElementById('sudut_pelana_a').value) || 0,
        panjang_pelana_b: parseFloat(document.getElementById('panjang_pelana_b').value) || 0,
        lebar_pelana_b: parseFloat(document.getElementById('lebar_pelana_b').value) || 0,
        sudut_pelana_b: parseFloat(document.getElementById('sudut_pelana_b').value) || 0,
        panjang_pelana_c: parseFloat(document.getElementById('panjang_pelana_c').value) || 0,
        lebar_pelana_c: parseFloat(document.getElementById('lebar_pelana_c').value) || 0,
        sudut_pelana_c: parseFloat(document.getElementById('sudut_pelana_c').value) || 0
    };
    
    if (data.panjang_pelana_a <= 0 || data.lebar_pelana_a <= 0 || data.sudut_pelana_a <= 0) {
        alert('Isi semua field pada bagian Pelana A (Atas) dengan nilai > 0!');
        return;
    }
    if (data.panjang_pelana_b <= 0 || data.lebar_pelana_b <= 0 || data.sudut_pelana_b <= 0) {
        alert('Isi semua field pada bagian Pelana B (Tengah) dengan nilai > 0!');
        return;
    }
    if (data.panjang_pelana_c <= 0 || data.lebar_pelana_c <= 0 || data.sudut_pelana_c <= 0) {
        alert('Isi semua field pada bagian Pelana C (Bawah) dengan nilai > 0!');
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
    .then(responseData => {
        if (responseData.success) {
            hasilKombinasiPelana3 = responseData;
            
            document.getElementById('totalLuasPelana3').innerHTML = responseData.total.luas_atap + ' m²';
            document.getElementById('totalStarterPelana3').innerHTML = responseData.total.panjang_starter + ' m';
            document.getElementById('totalNokJuraiPelana3').innerHTML = responseData.total.panjang_nok_jurai + ' m';
            document.getElementById('totalFlashingPelana3').innerHTML = responseData.total.panjang_flashing + ' m';
            
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            responseData.details.forEach(item => {
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
            document.getElementById('detailBagianPelana3').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganPelana3').classList.remove('hidden');
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

function resetFormPelana3() {
    document.getElementById('panjang_pelana_a').value = '';
    document.getElementById('lebar_pelana_a').value = '';
    document.getElementById('sudut_pelana_a').value = '30';
    document.getElementById('panjang_pelana_b').value = '';
    document.getElementById('lebar_pelana_b').value = '';
    document.getElementById('sudut_pelana_b').value = '30';
    document.getElementById('panjang_pelana_c').value = '';
    document.getElementById('lebar_pelana_c').value = '';
    document.getElementById('sudut_pelana_c').value = '30';
    document.getElementById('hasilPerhitunganPelana3').classList.add('hidden');
    hasilKombinasiPelana3 = null;
}

function lanjutKeBOQPelana3() {
    let selectEl = document.getElementById('brand_boq_pelana3');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilKombinasiPelana3) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let details = hasilKombinasiPelana3.details;
    
    let luas1 = details[0]?.luas_atap || 0;
    let starter1 = details[0]?.starter || 0;
    let nok1 = details[0]?.nok_jurai || 0;
    let flashing1 = details[0]?.flashing || 0;
    
    let luas2 = details[1]?.luas_atap || 0;
    let starter2 = details[1]?.starter || 0;
    let nok2 = details[1]?.nok_jurai || 0;
    let flashing2 = details[1]?.flashing || 0;
    
    let luas3 = details[2]?.luas_atap || 0;
    let starter3 = details[2]?.starter || 0;
    let nok3 = details[2]?.nok_jurai || 0;
    let flashing3 = details[2]?.flashing || 0;
    
    let sudutA = parseFloat(document.getElementById('sudut_pelana_a').value) || 0;
    let sudutB = parseFloat(document.getElementById('sudut_pelana_b').value) || 0;
    let sudutC = parseFloat(document.getElementById('sudut_pelana_c').value) || 0;
    
    window.location.href = `/boq/atap-kombinasi/pelana-3?luas_atap_1=${luas1}&sudut_1=${sudutA}&starter_1=${starter1}&nok_1=${nok1}&flashing_1=${flashing1}&luas_atap_2=${luas2}&sudut_2=${sudutB}&starter_2=${starter2}&nok_2=${nok2}&flashing_2=${flashing2}&luas_atap_3=${luas3}&sudut_3=${sudutC}&starter_3=${starter3}&nok_3=${nok3}&flashing_3=${flashing3}`;
}
</script>