<!-- Modal Jendela Mati 1 Kaca (Fixed Window) -->
<div id="modalJendelaMati1Kaca" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modalJendelaMati1Kaca')"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto">
            <button onclick="closeModal('modalJendelaMati1Kaca')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10 bg-white rounded-full p-1 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: Gambar -->
                <div class="md:w-2/5 relative bg-gradient-to-br from-teal-600 to-cyan-600 rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none overflow-hidden">
                    <div class="h-64 md:h-full min-h-[280px] relative flex items-center justify-center p-6">
                        <img src="{{ asset('images/jendela/jendela-mati-1-kaca.png') }}" 
                             alt="Jendela Mati 1 Kaca" 
                             class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                            <h4 class="text-lg font-bold">Jendela Mati 1 Kaca</h4>
                            <p class="text-xs text-white/80">Jendela tetap / non-opening / fixed window</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-semibold text-gray-900">Hitung Kebutuhan Jendela Mati</h3>
                        <p class="text-xs text-gray-500 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Input Ukuran -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Lebar (m)</label>
                                <input type="number" id="mati1_lebar" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Tinggi (m)</label>
                                <input type="number" id="mati1_tinggi" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Jenis Material</label>
                                <select id="mati1_material" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="kayu">Kayu</option>
                                    <option value="aluminium">Aluminium</option>
                                    <option value="upvc">UPVC</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Jenis Kaca</label>
                                <select id="mati1_kaca" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="polos">Kaca Polos 5mm</option>
                                    <option value="patri">Kaca Patri</option>
                                    <option value="tempered">Kaca Tempered</option>
                                    <option value="isolasi">Kaca Isolasi Double</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Tebal Kusen (cm)</label>
                                <input type="number" id="mati1_tebal_kusen" step="0.5" value="5" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Waste (%)</label>
                                <input type="number" id="mati1_waste" step="1" value="5" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Tombol Hitung -->
                        <button type="button" onclick="hitungJendelaMati1Kaca()" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-medium">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                            </svg>
                            Hitung Luas & Estimasi
                        </button>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilPerhitunganMati1Kaca" class="hidden">
                            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-4 space-y-2 border border-teal-100">
                                <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                                    <span>📊 TOTAL KESELURUHAN</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-teal-100">
                                    <span class="text-gray-600">Luas Jendela</span>
                                    <span id="mati1_luas" class="font-semibold text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Panjang Kusen</span>
                                    <span id="mati1_kusen" class="font-semibold text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Luas Kaca</span>
                                    <span id="mati1_kaca_luas" class="font-semibold text-gray-900">- m²</span>
                                </div>
                                <div class="grand-total mt-3 text-right">
                                    <span class="text-sm">💰 Total Biaya:</span>
                                    <span id="mati1_total" class="text-lg font-bold ml-2">Rp 0</span>
                                </div>
                            </div>
                            <div id="mati1_detail" class="mt-3 space-y-2"></div>
                        </div>

                        <!-- Pilih Brand untuk BOQ -->
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Pilih Brand <span class="text-red-500">*</span>
                            </label>
                            <select id="brand_boj_mati1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQMati1()" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-medium mt-3">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                Lanjut ke BOQ →
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                        <button onclick="closeModal('modalJendelaMati1Kaca')" class="flex-1 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Tutup</button>
                        <button onclick="resetMati1()" class="flex-1 px-3 py-2 text-sm text-teal-600 hover:bg-teal-50 rounded-lg">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let hasilMati1 = null;

function hitungJendelaMati1Kaca() {
    let lebar = parseFloat(document.getElementById('mati1_lebar').value) || 0;
    let tinggi = parseFloat(document.getElementById('mati1_tinggi').value) || 0;
    let material = document.getElementById('mati1_material').value;
    let jenisKaca = document.getElementById('mati1_kaca').value;
    let tebalKusen = parseFloat(document.getElementById('mati1_tebal_kusen').value) || 5;
    let waste = parseFloat(document.getElementById('mati1_waste').value) || 5;
    
    if (lebar <= 0 || tinggi <= 0) {
        alert('⚠️ Isi lebar dan tinggi dengan nilai > 0!');
        return;
    }
    
    // Perhitungan
    let luasJendela = lebar * tinggi;
    
    // Panjang kusen = keliling + (tebal kusen x 4 untuk sambungan)
    let keliling = 2 * (lebar + tinggi);
    let panjangKusen = keliling + (tebalKusen / 100 * 4);
    
    // Luas kaca = luas jendela - (tebal kusen x keliling / 100)
    let luasKaca = luasJendela - ((tebalKusen / 100) * keliling);
    if (luasKaca < 0) luasKaca = luasJendela * 0.8;
    
    // Waste
    let wasteFactor = 1 + (waste / 100);
    let panjangKusenDenganWaste = panjangKusen * wasteFactor;
    let luasKacaDenganWaste = luasKaca * wasteFactor;
    
    // Harga estimasi per jenis material
    let hargaKusenPerMeter = 0;
    let hargaKacaPerM2 = 0;
    
    switch(material) {
        case 'kayu':
            hargaKusenPerMeter = 250000;
            break;
        case 'aluminium':
            hargaKusenPerMeter = 350000;
            break;
        case 'upvc':
            hargaKusenPerMeter = 450000;
            break;
    }
    
    switch(jenisKaca) {
        case 'polos':
            hargaKacaPerM2 = 150000;
            break;
        case 'patri':
            hargaKacaPerM2 = 350000;
            break;
        case 'tempered':
            hargaKacaPerM2 = 450000;
            break;
        case 'isolasi':
            hargaKacaPerM2 = 650000;
            break;
    }
    
    let totalKusen = panjangKusenDenganWaste * hargaKusenPerMeter;
    let totalKaca = luasKacaDenganWaste * hargaKacaPerM2;
    let totalKeseluruhan = totalKusen + totalKaca;
    
    // Aksesoris
    let aksesoris = 50000;
    totalKeseluruhan += aksesoris;
    
    hasilMati1 = {
        lebar: lebar,
        tinggi: tinggi,
        luas: luasJendela,
        panjang_kusen: panjangKusen,
        panjang_kusen_waste: panjangKusenDenganWaste,
        luas_kaca: luasKaca,
        luas_kaca_waste: luasKacaDenganWaste,
        total: totalKeseluruhan,
        material: material,
        jenis_kaca: jenisKaca
    };
    
    document.getElementById('mati1_luas').innerHTML = luasJendela.toFixed(2) + ' m²';
    document.getElementById('mati1_kusen').innerHTML = panjangKusen.toFixed(2) + ' m';
    document.getElementById('mati1_kaca_luas').innerHTML = luasKaca.toFixed(2) + ' m²';
    document.getElementById('mati1_total').innerHTML = 'Rp ' + totalKeseluruhan.toLocaleString();
    
    let detailHtml = `
        <div class="bg-teal-50 rounded-lg p-3 text-xs space-y-2">
            <div class="font-semibold text-teal-700">📋 Detail Perhitungan</div>
            <div class="grid grid-cols-2 gap-2">
                <div>Material Kusen:</div>
                <div class="font-semibold">${material.toUpperCase()}</div>
                <div>Panjang Kusen:</div>
                <div>${panjangKusen.toFixed(2)} m</div>
                <div>+ Waste ${waste}%:</div>
                <div>${panjangKusenDenganWaste.toFixed(2)} m</div>
                <div>Harga Kusen/m:</div>
                <div>Rp ${hargaKusenPerMeter.toLocaleString()}</div>
                <div class="border-t pt-1">Total Kusen:</div>
                <div class="border-t pt-1">Rp ${totalKusen.toLocaleString()}</div>
                <div>Jenis Kaca:</div>
                <div>${jenisKaca.toUpperCase()}</div>
                <div>Luas Kaca:</div>
                <div>${luasKaca.toFixed(2)} m²</div>
                <div>+ Waste ${waste}%:</div>
                <div>${luasKacaDenganWaste.toFixed(2)} m²</div>
                <div>Harga Kaca/m²:</div>
                <div>Rp ${hargaKacaPerM2.toLocaleString()}</div>
                <div class="border-t pt-1">Total Kaca:</div>
                <div class="border-t pt-1">Rp ${totalKaca.toLocaleString()}</div>
                <div>Aksesoris:</div>
                <div>Rp ${aksesoris.toLocaleString()}</div>
            </div>
        </div>
    `;
    document.getElementById('mati1_detail').innerHTML = detailHtml;
    document.getElementById('hasilPerhitunganMati1Kaca').classList.remove('hidden');
}

function resetMati1() {
    document.getElementById('mati1_lebar').value = '';
    document.getElementById('mati1_tinggi').value = '';
    document.getElementById('mati1_material').value = 'kayu';
    document.getElementById('mati1_kaca').value = 'polos';
    document.getElementById('mati1_tebal_kusen').value = '5';
    document.getElementById('mati1_waste').value = '5';
    document.getElementById('hasilPerhitunganMati1Kaca').classList.add('hidden');
    hasilMati1 = null;
}

function lanjutKeBOQMati1() {
    let brandSlug = document.getElementById('brand_boj_mati1').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilMati1) {
        alert('Hitung kebutuhan jendela terlebih dahulu!');
        return;
    }
    
    let url = `/boq/jendela/mati-1-kaca?brand_slug=${brandSlug}`;
    url += `&lebar=${hasilMati1.lebar}`;
    url += `&tinggi=${hasilMati1.tinggi}`;
    url += `&material=${hasilMati1.material}`;
    url += `&jenis_kaca=${hasilMati1.jenis_kaca}`;
    url += `&luas_jendela=${hasilMati1.luas}`;
    url += `&panjang_kusen=${hasilMati1.panjang_kusen}`;
    url += `&luas_kaca=${hasilMati1.luas_kaca}`;
    url += `&grand_total=${hasilMati1.total}`;
    
    window.location.href = url;
}
</script>