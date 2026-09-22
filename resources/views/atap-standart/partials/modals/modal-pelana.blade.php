<!-- Modal Atap Pelana -->
<style>
    #modalPelana::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPelana {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalPelana" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPelana')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPelana')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/iko-atap-pelana2.png') }}" 
                             alt="Atap Pelana" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Pelana</h4>
                            <p class="text-xs text-gray-300">Model atap paling umum</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Pelana</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Grid -->
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" 
                                       id="panjang_pelana" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" 
                                       id="lebar_pelana" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_pelana" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       value="30"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungAtapPelana()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganPelana" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasAtapPelana" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="panjangSisiMiringPelana" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="startingPelana" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="nokJuraiPelana" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashingPelana" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_pelana" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQPelana()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalPelana')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormPelana()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global untuk menyimpan hasil perhitungan
let hasilPerhitunganPelana = null;

function hitungAtapPelana() {
    let panjang = document.getElementById('panjang_pelana').value;
    let lebar = document.getElementById('lebar_pelana').value;
    let sudut = document.getElementById('sudut_pelana').value;
    
    if (!panjang || !lebar || !sudut) {
        alert('Isi semua field terlebih dahulu!');
        return;
    }
    
    let p = parseFloat(panjang);
    let l = parseFloat(lebar);
    let s = parseFloat(sudut);
    
    if (p <= 0 || l <= 0) {
        alert('Panjang dan lebar harus lebih dari 0!');
        return;
    }
    
    if (s <= 0 || s >= 90) {
        alert('Sudut kemiringan harus antara 1° - 89°!');
        return;
    }
    
    let btn = event.target;
    if (btn.tagName !== 'BUTTON') {
        btn = btn.closest('button');
    }
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    fetch('/atap-standar/hitung', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            jenis_atap: 2,
            panjang: p,
            lebar: l,
            sudut: s
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hasilPerhitunganPelana = data;
            
            document.getElementById('luasAtapPelana').innerHTML = data.luas_atap + ' m²';
            document.getElementById('panjangSisiMiringPelana').innerHTML = data.panjang_sisi_miring + ' m';
            document.getElementById('startingPelana').innerHTML = data.starting + ' m';
            document.getElementById('nokJuraiPelana').innerHTML = data.nok_jurai + ' m';
            document.getElementById('flashingPelana').innerHTML = data.flashing + ' m';
            document.getElementById('hasilPerhitunganPelana').classList.remove('hidden');
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

function resetFormPelana() {
    document.getElementById('panjang_pelana').value = '';
    document.getElementById('lebar_pelana').value = '';
    document.getElementById('sudut_pelana').value = '30';
    document.getElementById('hasilPerhitunganPelana').classList.add('hidden');
    hasilPerhitunganPelana = null;
}
function lanjutKeBOQPelana() {
    let brandSlug = document.getElementById('brand_boq_pelana').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    let luasAtap = parseFloat(document.getElementById('luasAtapPelana').innerText) || 0;
    let panjangStarter = parseFloat(document.getElementById('startingPelana').innerText) || 0;
    let panjangNokJurai = parseFloat(document.getElementById('nokJuraiPelana').innerText) || 0;
    let panjangFlashing = parseFloat(document.getElementById('flashingPelana').innerText) || 0;
    let sudut = parseFloat(document.getElementById('sudut_pelana').value) || 0;
    
    // Mapping URL berdasarkan brand
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
        'palmex': '/boq/palmex/pelana',
        'tape-roof': '/boq/taperoof/pelana',
        'mahaflat': '/boq/mahaflat/pelana',
        'flexi-roof': '/boq/flexiroof/pelana',
        'eco-roof': '/boq/ecoroof/pelana',
        'emarin-roof': '/boq/emarin/pelana',
        'master-roof': '/boq/masterroof/pelana',
        'maha-roof': '/boq/maharoof/pelana',
        'mahaspan-roof': '/boq/mahaspanroof/pelana',
        'flexideck-seam': '/boq/flexideckseam/pelana',
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    // Khusus TAPERROOF - tanpa sistem pemasangan
    // Khusus TAPER ROOF
if (brandSlug === 'tape-roof') {  // <-- PAKAI 'tape-roof'
    window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
}else if (brandSlug === 'flexi-roof') {  // <-- PAKAI 'tape-roof'
    window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
} 
else if (brandSlug === 'eco-roof') {  // <-- PAKAI 'tape-roof'
    window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
} 
else if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'skyshield') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'palmex') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}&model=pelana`;
    } else {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    }
}
</script>