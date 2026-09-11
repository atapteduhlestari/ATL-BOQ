<!-- Modal Atap Dome / Setengah Lingkaran -->
<style>
    #modalDome::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalDome {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalDome .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalDome .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalDome" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalDome')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalDome')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/iko-atap-dome2.png') }}" 
                             alt="Atap Dome" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Dome</h4>
                            <p class="text-xs text-gray-300">Model atap setengah lingkaran / dome</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Dome</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Diameter</label>
                                <input type="number" 
                                       id="diameter_dome" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Tinggi</label>
                                <input type="number" 
                                       id="tinggi_dome" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungAtapDome()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganDome" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasAtapDome" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Jari-jari</span>
                                    <span id="jariJariDome" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="startingDome" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashingDome" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_dome" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQDome()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalDome')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormDome()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global untuk menyimpan hasil perhitungan
let hasilPerhitunganDome = null;

function hitungAtapDome() {
    let diameter = document.getElementById('diameter_dome').value;
    let tinggi = document.getElementById('tinggi_dome').value;
    
    if (!diameter || !tinggi) {
        alert('Isi semua field terlebih dahulu!');
        return;
    }
    
    let d = parseFloat(diameter);
    let t = parseFloat(tinggi);
    
    if (d <= 0 || t <= 0) {
        alert('Diameter dan tinggi harus lebih dari 0!');
        return;
    }
    
    let btn = event.target;
    if (btn.tagName !== 'BUTTON') {
        btn = btn.closest('button');
    }
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    // Pilih endpoint berdasarkan brand yang dipilih
    let brandSlug = document.getElementById('brand_boq_dome')?.value || '';
    let url = '/atap-standar/hitung'; // default
    let requestData = {};
    
    if (brandSlug === 'palmex') {
        url = '/atap-standar/hitung-palmex-dome';
        requestData = {
            diameter: d,
            tinggi: t
        };
    } else {
        requestData = {
            jenis_atap: 6,
            diameter: d,
            tinggi: t
        };
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.hasilPerhitunganDome = data;
            
            document.getElementById('luasAtapDome').innerHTML = data.luas_atap + ' m²';
            document.getElementById('jariJariDome').innerHTML = data.jari_jari + ' m';
            document.getElementById('startingDome').innerHTML = data.starting + ' m';
            
           
            
            document.getElementById('flashingDome').innerHTML = data.flashing + ' m';
            document.getElementById('hasilPerhitunganDome').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function resetFormDome() {
    document.getElementById('diameter_dome').value = '';
    document.getElementById('tinggi_dome').value = '';
    document.getElementById('hasilPerhitunganDome').classList.add('hidden');
    hasilPerhitunganDome = null;
}

function lanjutKeBOQDome() {
    let brandSlug = document.getElementById('brand_boq_dome').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!window.hasilPerhitunganDome) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let hasil = window.hasilPerhitunganDome;
    let luasAtap = hasil.luas_atap || 0;
    let panjangStarter = hasil.starting || 0;
    let panjangFlashing = hasil.flashing || 0;
    let panjangJurai = hasil.panjang_jurai || hasil.nok_jurai || 0;
    
    // MAPPING URL - TAMBAHKAN TAPE ROOF
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
        'palmex': '/boq/palmex/dome',
        'tape-roof': '/boq/taperoof/dome',  // <-- INI DITAMBAH
        'mahaflat': '/boq/mahaflat/dome',  // <-- INI DITAMBAH
        'flexi-roof': '/boq/flexiroof/dome',  // <-- INI DITAMBAH
        'eco-roof': '/boq/ecoroof/dome',  // <-- INI DITAMBAH
        'emarin-roof': '/boq/emarin/dome',  // <-- INI DITAMBAH
        'master-roof': '/boq/masterroof/dome',  // <-- INI DITAMBAH
        'maha-roof': '/boq/maharoof/dome',  // <-- INI DITAMBAH
        'mahaspan-roof': '/boq/mahaspanroof/dome',  // <-- INI DITAMBAH
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'palmex') {
        window.location.href = `${url}?luas_atap=${luasAtap}&panjang_starter=${panjangStarter}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else {
        // TAPE ROOF dan lainnya pake ini
        window.location.href = `${url}?luas_atap=${luasAtap}&panjang_starter=${panjangStarter}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    }
}
</script>