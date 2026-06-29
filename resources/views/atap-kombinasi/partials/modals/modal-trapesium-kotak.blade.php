<!-- Modal Atap Trapesium Kotak -->
<style>
    #modalTrapesiumKotak::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalTrapesiumKotak {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalTrapesiumKotak .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalTrapesiumKotak .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalTrapesiumKotak" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalTrapesiumKotak')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalTrapesiumKotak')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/atap-kombinasi/trapesium-kotak.png') }}" 
                             alt="Atap Trapesium Kotak" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Trapesium Kotak</h4>
                            <p class="text-xs text-gray-300">Model atap trapesium untuk bangunan kotak</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Trapesium Kotak</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Atas</label>
                                <input type="number" 
                                       id="tk_panjang_atas" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Bawah</label>
                                <input type="number" 
                                       id="tk_panjang_bawah" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                <input type="number" 
                                       id="tk_tinggi" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="tk_sudut" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="30">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungTrapesiumKotak()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganTrapesiumKotak" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="tk_luasAtap" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="tk_sisiMiring" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="tk_starter" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="tk_nokJurai" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="tk_flashing" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                            <div id="detailTrapesiumKotak" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_trapesium_kotak" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQTrapesiumKotak()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalTrapesiumKotak')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetTrapesiumKotak()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== TRAPESIUM KOTAK ====================
let hasilTrapesiumKotak = null;

function hitungTrapesiumKotak() {
    let panjangAtas = parseFloat(document.getElementById('tk_panjang_atas').value) || 0;
    let panjangBawah = parseFloat(document.getElementById('tk_panjang_bawah').value) || 0;
    let tinggi = parseFloat(document.getElementById('tk_tinggi').value) || 0;
    let sudut = parseFloat(document.getElementById('tk_sudut').value) || 0;
    
    if (panjangAtas <= 0 || panjangBawah <= 0 || tinggi <= 0 || sudut <= 0) {
        alert('Isi semua field dengan nilai > 0!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
      fetch('{{ route("atap-kombinasi.hitung") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            jenis_kombinasi: 'trapesium_kotak',
            panjang_atas: panjangAtas,
            panjang_bawah: panjangBawah,
            tinggi: tinggi,
            sudut: sudut
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hasilTrapesiumKotak = data;
            
            document.getElementById('tk_luasAtap').innerHTML = data.total.luas_atap + ' m²';
            document.getElementById('tk_sisiMiring').innerHTML = data.total.panjang_sisi_miring + ' m';
            document.getElementById('tk_starter').innerHTML = data.total.panjang_starter + ' m';
            document.getElementById('tk_nokJurai').innerHTML = data.total.panjang_nok_jurai + ' m';
            document.getElementById('tk_flashing').innerHTML = data.total.panjang_flashing + ' m';
            
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
            
            document.getElementById('detailTrapesiumKotak').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganTrapesiumKotak').classList.remove('hidden');
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

function resetTrapesiumKotak() {
    document.getElementById('tk_panjang_atas').value = '';
    document.getElementById('tk_panjang_bawah').value = '';
    document.getElementById('tk_tinggi').value = '';
    document.getElementById('tk_sudut').value = '30';
    document.getElementById('hasilPerhitunganTrapesiumKotak').classList.add('hidden');
    hasilTrapesiumKotak = null;
}

function lanjutKeBOQTrapesiumKotak() {
    let selectEl = document.getElementById('brand_boq_trapesium_kotak');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilTrapesiumKotak) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let data = hasilTrapesiumKotak.total;
    
    // Mapping URL berdasarkan brand
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/trapesium-kotak',
        'skyshield': '/boq/atap-kombinasi-skyshield/trapesium-kotak',
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/trapesium-kotak';
    
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    url += `&panjang_atas=${document.getElementById('tk_panjang_atas').value}`;
    url += `&panjang_bawah=${document.getElementById('tk_panjang_bawah').value}`;
    url += `&tinggi=${document.getElementById('tk_tinggi').value}`;
    url += `&sudut=${document.getElementById('tk_sudut').value}`;
    url += `&luas_atap=${data.luas_atap}`;
    url += `&starter=${data.panjang_starter}`;
    url += `&nok_jurai=${data.panjang_nok_jurai}`;
    url += `&flashing=${data.panjang_flashing}`;
    
    window.location.href = url;
}
</script>