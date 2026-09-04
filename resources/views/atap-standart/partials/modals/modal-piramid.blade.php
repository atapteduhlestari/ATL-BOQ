<!-- Modal Atap Limas / Piramid -->
<style>
    #modalPiramid::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPiramid {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPiramid .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPiramid .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalPiramid" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPiramid')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPiramid')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
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
                            <p class="text-xs text-gray-300">Model atap piramid modern</p>
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
                                       id="panjang_piramid" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" 
                                       id="lebar_piramid" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_piramid" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       value="30"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungPiramid()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPiramid" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasPiramid" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="sisiMiringPiramid" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="starterPiramid" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="nokPiramid" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashingPiramid" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_piramid" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutPiramid()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalPiramid')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetPiramid()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let dataPiramid = null;

function hitungPiramid() {
    let panjang = document.getElementById('panjang_piramid').value;
    let lebar = document.getElementById('lebar_piramid').value;
    let sudut = document.getElementById('sudut_piramid').value;
    
    if (panjang === '' || lebar === '' || sudut === '') {
        alert('Isi semua field terlebih dahulu!');
        return;
    }
    
    let p = parseFloat(panjang);
    let l = parseFloat(lebar);
    let s = parseFloat(sudut);
    
    if (isNaN(p) || isNaN(l) || isNaN(s)) {
        alert('Nilai harus berupa angka!');
        return;
    }
    
    if (p <= 0 || l <= 0) {
        alert('Panjang dan lebar harus lebih dari 0!');
        return;
    }
    
    if (s <= 0 || s >= 90) {
        alert('Sudut kemiringan harus antara 1° - 89°!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    // Pilih endpoint berdasarkan brand yang dipilih
    let brandSlug = document.getElementById('brand_piramid')?.value || '';
    let url = '/atap-standar/hitung'; // default dengan jenis_atap
    
    if (brandSlug === 'palmex') {
        url = '/atap-standar/hitung-palmex-piramid';
    }
    
    let requestData = {};
    
    if (brandSlug === 'palmex') {
        // PALMEX: kirim panjang, lebar, sudut
        requestData = {
            panjang: p,
            lebar: l,
            sudut: s
        };
    } else {
        // DEFAULT: kirim jenis_atap = 3 (Piramid)
        requestData = {
            jenis_atap: 3,
            panjang: p,
            lebar: l,
            sudut: s
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
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok: ' + res.status);
        }
        return res.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug
        if (data.success) {
            // Simpan data ke variabel global
            dataPiramid = data;
            
            document.getElementById('luasPiramid').innerHTML = data.luas_atap + ' m²';
            document.getElementById('sisiMiringPiramid').innerHTML = data.panjang_sisi_miring + ' m';
            document.getElementById('starterPiramid').innerHTML = data.starting + ' m';
            
            // Cek apakah ada panjang_jurai (PALMEX) atau nok_jurai (default)
            if (data.panjang_jurai !== undefined) {
                document.getElementById('nokPiramid').innerHTML = data.panjang_jurai + ' m';
            } else {
                document.getElementById('nokPiramid').innerHTML = data.nok_jurai + ' m';
            }
            
            document.getElementById('flashingPiramid').innerHTML = data.flashing + ' m';
            document.getElementById('hasilPiramid').classList.remove('hidden');
        } else {
            alert('Error: ' + (data.message || 'Gagal hitung'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan server: ' + err.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
function resetPiramid() {
    document.getElementById('panjang_piramid').value = '';
    document.getElementById('lebar_piramid').value = '';
    document.getElementById('sudut_piramid').value = '30';
    document.getElementById('hasilPiramid').classList.add('hidden');
    dataPiramid = null;
}

function lanjutPiramid() {
    let brandSlug = document.getElementById('brand_piramid').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!dataPiramid) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let luasAtap = parseFloat(dataPiramid.luas_atap) || 0;
    let panjangStarter = parseFloat(dataPiramid.starting) || 0;
    let panjangFlashing = parseFloat(dataPiramid.flashing) || 0;
    let sudut = parseFloat(dataPiramid.sudut) || 0;
    
    let panjangJurai = dataPiramid.panjang_jurai || dataPiramid.nok_jurai || 0;
    
    // MAPPING URL - TAMBAHKAN TAPE ROOF
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
        'palmex': '/boq/palmex/piramid',
        'tape-roof': '/boq/taperoof/piramid',  // <-- INI DITAMBAH
        'mahaflat': '/boq/mahaflat/piramid',  // <-- INI DITAMBAH
        'flexi-roof': '/boq/flexiroof/piramid',  // <-- INI DITAMBAH
        'eco-roof': '/boq/ecoroof/piramid',  // <-- INI DITAMBAH
        'emarin-roof': '/boq/emarin/piramid',  // <-- INI DITAMBAH
        'master-roof': '/boq/masterroof/piramid',  // <-- INI DITAMBAH
        'maha-roof': '/boq/maharoof/piramid',  // <-- INI DITAMBAH
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'palmex') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else {
        // TAPE ROOF dan lainnya pake ini
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    }
}
</script>