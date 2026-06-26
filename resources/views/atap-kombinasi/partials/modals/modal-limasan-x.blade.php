<!-- Modal Atap Kombinasi - Limasan X (3 Tingkat) -->
<style>
    #modalLimasanX::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalLimasanX {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasanX .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasanX .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalLimasanX" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalLimasanX')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalLimasanX')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/limasan-x.png') }}" 
                             alt="Limasan X" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Limasan X</h4>
                            <p class="text-xs text-gray-300">Kombinasi tiga atap limasan bersusun</p>
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
                        <!-- Bagian 1: Limasan A (Atas) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Atas - Limasan A</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Atas</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="limasan_x_panjang_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="limasan_x_lebar_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="limasan_x_sudut_a" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Limasan B (Tengah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Tengah - Limasan B</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Tengah</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="limasan_x_panjang_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="limasan_x_lebar_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="limasan_x_sudut_b" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Limasan C (Bawah) -->
                        <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium text-gray-700">Bagian Bawah - Limasan C</span>
                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bawah</span>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                    <input type="number" id="limasan_x_panjang_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                    <input type="number" id="limasan_x_lebar_c" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                    <input type="number" id="limasan_x_sudut_c" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungLimasanX()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganLimasanX" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="totalLuasLimasanX" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Starter</span>
                                    <span id="totalStarterLimasanX" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Nok & Jurai</span>
                                    <span id="totalNokJuraiLimasanX" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Flashing</span>
                                    <span id="totalFlashingLimasanX" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailBagianLimasanX" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_limasan_x" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQLimasanX()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalLimasanX')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormLimasanX()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilLimasanX = null;

function hitungLimasanX() {
    let data = {
        jenis_kombinasi: 'limasan_x',
        panjang_a: parseFloat(document.getElementById('limasan_x_panjang_a').value) || 0,
        lebar_a: parseFloat(document.getElementById('limasan_x_lebar_a').value) || 0,
        sudut_a: parseFloat(document.getElementById('limasan_x_sudut_a').value) || 0,
        panjang_b: parseFloat(document.getElementById('limasan_x_panjang_b').value) || 0,
        lebar_b: parseFloat(document.getElementById('limasan_x_lebar_b').value) || 0,
        sudut_b: parseFloat(document.getElementById('limasan_x_sudut_b').value) || 0,
        panjang_c: parseFloat(document.getElementById('limasan_x_panjang_c').value) || 0,
        lebar_c: parseFloat(document.getElementById('limasan_x_lebar_c').value) || 0,
        sudut_c: parseFloat(document.getElementById('limasan_x_sudut_c').value) || 0
    };
    
    if (data.panjang_a <= 0 || data.lebar_a <= 0 || data.sudut_a <= 0 ||
        data.panjang_b <= 0 || data.lebar_b <= 0 || data.sudut_b <= 0 ||
        data.panjang_c <= 0 || data.lebar_c <= 0 || data.sudut_c <= 0) {
        alert('Isi semua field dengan nilai > 0!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    fetch('{{ route("atap-kombinasi.hitung") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            hasilLimasanX = resData;
            
            document.getElementById('totalLuasLimasanX').innerHTML = resData.total.luas_atap + ' m²';
            document.getElementById('totalStarterLimasanX').innerHTML = resData.total.panjang_starter + ' m';
            document.getElementById('totalNokJuraiLimasanX').innerHTML = resData.total.panjang_nok_jurai + ' m';
            document.getElementById('totalFlashingLimasanX').innerHTML = resData.total.panjang_flashing + ' m';
            
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            resData.details.forEach(item => {
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
            document.getElementById('detailBagianLimasanX').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganLimasanX').classList.remove('hidden');
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

function resetFormLimasanX() {
    document.getElementById('limasan_x_panjang_a').value = ''; 
    document.getElementById('limasan_x_lebar_a').value = ''; 
    document.getElementById('limasan_x_sudut_a').value = '30';
    document.getElementById('limasan_x_panjang_b').value = ''; 
    document.getElementById('limasan_x_lebar_b').value = ''; 
    document.getElementById('limasan_x_sudut_b').value = '30';
    document.getElementById('limasan_x_panjang_c').value = ''; 
    document.getElementById('limasan_x_lebar_c').value = ''; 
    document.getElementById('limasan_x_sudut_c').value = '30';
    document.getElementById('hasilPerhitunganLimasanX').classList.add('hidden');
    hasilLimasanX = null;
}

function lanjutKeBOQLimasanX() {
    let brandSlug = document.getElementById('brand_boq_limasan_x').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilLimasanX) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let d = hasilLimasanX.details;
    let total = hasilLimasanX.total;
    
    let url = `/boq/atap-kombinasi/limasan-x?brand_slug=${brandSlug}`;
    url += `&luas_atap_1=${d[0]?.luas_atap||0}&sudut_1=${document.getElementById('limasan_x_sudut_a').value}&starter_1=${d[0]?.starter||0}&nok_1=${d[0]?.nok_jurai||0}&flashing_1=${d[0]?.flashing||0}&lebar_1=${document.getElementById('limasan_x_lebar_a').value}`;
    url += `&luas_atap_2=${d[1]?.luas_atap||0}&sudut_2=${document.getElementById('limasan_x_sudut_b').value}&starter_2=${d[1]?.starter||0}&nok_2=${d[1]?.nok_jurai||0}&flashing_2=${d[1]?.flashing||0}&lebar_2=${document.getElementById('limasan_x_lebar_b').value}`;
    url += `&luas_atap_3=${d[2]?.luas_atap||0}&sudut_3=${document.getElementById('limasan_x_sudut_c').value}&starter_3=${d[2]?.starter||0}&nok_3=${d[2]?.nok_jurai||0}&flashing_3=${d[2]?.flashing||0}&lebar_3=${document.getElementById('limasan_x_lebar_c').value}`;
    
    url += `&starter_total=${total?.panjang_starter||0}`;
    url += `&talang_jurai=${total?.talang_jurai||0}`;
    
    window.location.href = url;
}
</script>