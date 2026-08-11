<!-- Modal Atap Satu Kemiringan -->
<style>
    #modalSatuKemiringan::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalSatuKemiringan {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalSatuKemiringan .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalSatuKemiringan .overflow-y-auto::-webkit-scrollbar {
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

<div id="modalSatuKemiringan" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalSatuKemiringan')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalSatuKemiringan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gray-900 rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/iko-atap-satu-kemiringan2.png') }}" 
                             alt="Atap Satu Kemiringan" 
                             class="w-full h-full object-contain opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-medium">Atap Satu Kemiringan</h4>
                            <p class="text-xs text-gray-300">Model atap miring tunggal / lean-to</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Satu Kemiringan</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Grid -->
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" 
                                       id="panjang_satu" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" 
                                       id="lebar_satu" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_satu" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       value="30"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" 
                                onclick="hitungAtapSatuKemiringan()"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganSatu" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasAtapSatu" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="panjangSisiMiringSatu" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="startingSatu" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="nokJuraiSatu" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashingSatu" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                                Pilih Brand
                            </label>
                            <select id="brand_boq_satu" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQSatu()" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalSatuKemiringan')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormSatuKemiringan()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global untuk menyimpan hasil perhitungan
let hasilPerhitunganSatu = null;

function hitungAtapSatuKemiringan() {
    let panjang = document.getElementById('panjang_satu').value;
    let lebar = document.getElementById('lebar_satu').value;
    let sudut = document.getElementById('sudut_satu').value;
    
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
    
    // Pilih endpoint berdasarkan brand yang dipilih
    let brandSlug = document.getElementById('brand_boq_satu')?.value || '';
    let url = '/atap-standar/hitung'; // default
    let requestData = {};
    
    if (brandSlug === 'palmex') {
        url = '/atap-standar/hitung-palmex-satu-kemiringan';
        requestData = {
            panjang: p,
            lebar: l,
            sudut: s
        };
    } else {
        requestData = {
            jenis_atap: 4,
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
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.hasilPerhitunganSatu = data;
            
            document.getElementById('luasAtapSatu').innerHTML = data.luas_atap + ' m²';
            document.getElementById('panjangSisiMiringSatu').innerHTML = data.panjang_sisi_miring + ' m';
            document.getElementById('startingSatu').innerHTML = data.starting + ' m';
            
            // Cek apakah ada panjang_nok dan panjang_jurai (PALMEX)
            if (data.panjang_nok !== undefined && data.panjang_jurai !== undefined) {
                document.getElementById('nokJuraiSatu').innerHTML = 'Nok: ' + data.panjang_nok + ' m | Jurai: ' + data.panjang_jurai + ' m';
                window.hasilPerhitunganSatu = {
                    ...data,
                    panjang_nok: data.panjang_nok,
                    panjang_jurai: data.panjang_jurai
                };
            } else {
                document.getElementById('nokJuraiSatu').innerHTML = data.nok_jurai + ' m';
                window.hasilPerhitunganSatu = {
                    ...data,
                    nok_jurai: data.nok_jurai
                };
            }
            
            document.getElementById('flashingSatu').innerHTML = data.flashing + ' m';
            document.getElementById('hasilPerhitunganSatu').classList.remove('hidden');
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

function resetFormSatuKemiringan() {
    document.getElementById('panjang_satu').value = '';
    document.getElementById('lebar_satu').value = '';
    document.getElementById('sudut_satu').value = '30';
    document.getElementById('hasilPerhitunganSatu').classList.add('hidden');
    hasilPerhitunganSatu = null;
}
function lanjutKeBOQSatu() {
    let brandSlug = document.getElementById('brand_boq_satu').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!window.hasilPerhitunganSatu) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let hasil = window.hasilPerhitunganSatu;
    let luasAtap = hasil.luas_atap || 0;
    let sudut = hasil.sudut || 0;
    let panjangStarter = hasil.starting || 0;
    let panjangFlashing = hasil.flashing || 0;
    
    let panjangNokJurai = hasil.nok_jurai || hasil.panjang_nok || 0;
    let panjangNok = hasil.panjang_nok || panjangNokJurai || 0;
    let panjangJurai = hasil.panjang_jurai || panjangNokJurai || 0;
    
    // MAPPING URL - TAMBAHKAN TAPE ROOF
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
        'palmex': '/boq/palmex/satu-kemiringan',
        'tape-roof': '/boq/taperoof/satu-kemiringan',  // <-- INI DITAMBAH
        'mahaflat': '/boq/mahaflat/satu-kemiringan',  // <-- INI DITAMBAH
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'palmex') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok=${panjangNok}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else {
        // TAPE ROOF dan lainnya pake ini
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok=${panjangNok}&panjang_flashing=${panjangFlashing}`;
    }
}
</script>