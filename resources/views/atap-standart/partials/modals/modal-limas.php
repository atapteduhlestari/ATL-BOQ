<!-- Modal Atap Limas / Piramid -->
<style>
    #modalLimas::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalLimas {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimas .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimas .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalLimas" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalLimas')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalLimas')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/iko-atap-piramid2.png') }}" 
                             alt="Atap Limas" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Limas / Piramid</h4>
                            <p class="text-xs text-gray-300">Model Atap Piramid Modern</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Limas</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Grid -->
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" 
                                       id="panjang_limas" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" 
                                       id="lebar_limas" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_limas" 
                                       step="1"
                                       min="0"
                                       max="90"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="30">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungAtapLimas()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganLimas" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasAtapLimas" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="panjangSisiMiringLimas" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="startingLimas" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="nokJuraiLimas" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashingLimas" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_limas" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQLimas()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalLimas')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormLimas()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungAtapLimas() {
    let panjang = document.getElementById('panjang_limas').value;
    let lebar = document.getElementById('lebar_limas').value;
    let sudut = document.getElementById('sudut_limas').value;
    
    if (!panjang || !lebar || !sudut) {
        alert('Isi semua field terlebih dahulu!');
        return;
    }
    
    if (parseFloat(panjang) <= 0 || parseFloat(lebar) <= 0) {
        alert('Panjang dan lebar harus lebih dari 0!');
        return;
    }
    
    if (parseFloat(sudut) <= 0 || parseFloat(sudut) >= 90) {
        alert('Sudut kemiringan harus antara 1° - 89°!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    fetch('{{ route("atap-standar.hitung") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            jenis_atap: 3,
            panjang: parseFloat(panjang),
            lebar: parseFloat(lebar),
            sudut: parseFloat(sudut)
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('luasAtapLimas').innerHTML = data.luas_atap + ' m²';
            document.getElementById('panjangSisiMiringLimas').innerHTML = data.panjang_sisi_miring + ' m';
            document.getElementById('startingLimas').innerHTML = data.starting + ' m';
            document.getElementById('nokJuraiLimas').innerHTML = data.nok_jurai + ' m';
            document.getElementById('flashingLimas').innerHTML = data.flashing + ' m';
            document.getElementById('hasilPerhitunganLimas').classList.remove('hidden');
            
            document.getElementById('hasilPerhitunganLimas').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server. Silakan coba lagi.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function resetFormLimas() {
    document.getElementById('panjang_limas').value = '';
    document.getElementById('lebar_limas').value = '';
    document.getElementById('sudut_limas').value = '';
    document.getElementById('hasilPerhitunganLimas').classList.add('hidden');
}

function lanjutKeBOQLimas() {
    let brandSlug = document.getElementById('brand_boq_limas').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    let luasAtap = parseFloat(document.getElementById('luasAtapLimas').innerText) || 0;
    let panjangSisiMiring = parseFloat(document.getElementById('panjangSisiMiringLimas').innerText) || 0;
    let starting = parseFloat(document.getElementById('startingLimas').innerText) || 0;
    let nokJurai = parseFloat(document.getElementById('nokJuraiLimas').innerText) || 0;
    let flashing = parseFloat(document.getElementById('flashingLimas').innerText) || 0;
    let sudut = parseFloat(document.getElementById('sudut_limas').value) || 0;
    
    // Mapping URL untuk setiap brand
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    // Kirim parameter sesuai brand
    if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&sudut=${sudut}&panjang_starter=${starting}&panjang_nok_jurai=${nokJurai}&panjang_flashing=${flashing}`;
    } else if (brandSlug === 'skyshield') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${starting}&panjang_nok_jurai=${nokJurai}&panjang_flashing=${flashing}`;
    } else {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${starting}&panjang_nok_jurai=${nokJurai}&panjang_flashing=${flashing}`;
    }
}
</script>