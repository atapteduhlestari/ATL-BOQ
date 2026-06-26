@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body, button, input, select, .text-sm, .text-xs, .text-lg, .text-xl, .text-3xl, .font-semibold, .font-medium, .font-bold {
        font-family: 'Poppins', sans-serif !important;
    }
    input, select, button {
        font-size: 13px !important;
    }
    label {
        font-size: 11px !important;
        letter-spacing: 0.3px;
    }
    h2, h3 {
        letter-spacing: -0.3px;
    }
    .delete-row {
        cursor: pointer;
        transition: all 0.2s;
    }
    .delete-row:hover {
        transform: scale(1.1);
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-cyan-600 via-teal-600 to-emerald-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold mb-1 tracking-tight">BOQ - SKYSHIELD Atap</h2>
                    <p class="text-cyan-100 text-xs">Hitung kebutuhan material atap SKYSHIELD berdasarkan perhitungan luas</p>
                </div>
                <div class="bg-white/20 backdrop-blur-md rounded-full px-3 py-1.5">
                    <span class="text-[10px] font-medium">SKYSHIELD</span>
                </div>
            </div>
        </div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form id="boqForm">
                <!-- Data Perhitungan Luas -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-cyan-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Data Perhitungan Luas Atap</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 rounded-xl p-4">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Luas Permukaan Atap</label>
                            <input type="number" id="luas_atap" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly placeholder="0 m²">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Sudut Kemiringan</label>
                            <input type="number" id="sudut" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly placeholder="0°">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Starter</label>
                            <input type="number" id="panjang_starter" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Nok & Jurai</label>
                            <input type="number" id="panjang_nok_jurai" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang Flashing</label>
                            <input type="number" id="panjang_flashing" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" readonly placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Talang Jurai</label>
                            <input type="number" id="panjang_talang_jurai" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" placeholder="0 m">
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Wall Flashing</label>
                            <input type="number" id="panjang_wall_flashing" step="0.01" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500" placeholder="0 m">
                        </div>
                    </div>
                </div>
                
                <!-- Pilihan Produk -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-teal-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-800">Pilihan Material</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">PRODUK ATAP UTAMA</label>
                            <select id="produk_atap_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 bg-white">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">UNDERLAYER</label>
                            <select id="underlayer_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 bg-white">
                                <option value="">Pilih Underlayer</option>
                                @foreach($underlayers as $underlayer)
                                    <option value="{{ $underlayer->id }}">
                                        {{ $underlayer->nama_produk }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">STRUKTUR RANGKA</label>
                            <select id="rangka" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 bg-white">
                                @foreach($rangkaOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">LANTAI KERJA</label>
                            <select id="lantai_kerja" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 bg-white">
                                @foreach($lantaiKerjaOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-1.5">WASTE (%)</label>
                            <input type="number" id="waste" step="1" value="5" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500">
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Hitung -->
                <div class="flex gap-3">
                    <button type="button" onclick="hitungBOQ()" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                        Hitung Material
                    </button>
                    <button type="button" id="btnPDF" onclick="exportToPDF()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm font-medium hidden transition-all">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export PDF
                    </button>
                </div>
            </form>
            
            <!-- Hasil Perhitungan -->
            <div id="hasilBOQ" class="mt-8 hidden">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-emerald-600 rounded-full"></div>
                    <h3 class="text-base font-semibold text-gray-800">Rincian Kebutuhan Material</h3>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-3 text-left text-xs font-medium text-gray-600">Nama Produk</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-600">Area</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-600">Qty</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-600">Satuan</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-600">Harga Satuan</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-600">Total</th>
                                <th class="p-3 text-center text-xs font-medium text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="boqTableBody" class="divide-y divide-gray-100"></tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr class="font-semibold">
                                <td colspan="6" class="p-3 text-right text-sm text-gray-700">GRAND TOTAL</td>
                                <td id="grandTotal" class="p-3 text-right text-cyan-600 font-bold text-base">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentResults = [];

function updateGrandTotal() {
    let grandTotal = currentResults.reduce((sum, item) => sum + item.total_harga, 0);
    document.getElementById('grandTotal').innerHTML = `Rp ${grandTotal.toLocaleString()}`;
}

function renderTable() {
    let tbody = document.getElementById('boqTableBody');
    tbody.innerHTML = '';
    
    currentResults.forEach((item, index) => {
        let row = `<tr class="hover:bg-gray-50">
            <td class="p-3 text-gray-800">${item.nama_produk}</td>
            <td class="p-3"><span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium ${getAreaColorClass(item.area)}">${item.area}</span></td>
            <td class="p-3 text-right font-medium">${item.qty.toLocaleString()}</td>
            <td class="p-3 text-right">${item.satuan}</td>
            <td class="p-3 text-right">${item.harga_satuan.toLocaleString()}</td>
            <td class="p-3 text-right font-medium text-emerald-600">${item.total_harga.toLocaleString()}</td>
            <td class="p-3 text-center">
                <button onclick="deleteRow(${index})" class="delete-row text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        </tr>`;
        tbody.innerHTML += row;
    });
    
    updateGrandTotal();
}

function deleteRow(index) {
    if (confirm('Hapus item ini?')) {
        currentResults.splice(index, 1);
        renderTable();
        
        if (currentResults.length === 0) {
            document.getElementById('hasilBOQ').classList.add('hidden');
            document.getElementById('btnPDF').classList.add('hidden');
        }
    }
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    document.getElementById('luas_atap').value = urlParams.get('luas_atap') || 0;
    document.getElementById('sudut').value = urlParams.get('sudut') || 0;
    document.getElementById('panjang_starter').value = urlParams.get('panjang_starter') || 0;
    document.getElementById('panjang_nok_jurai').value = urlParams.get('panjang_nok_jurai') || 0;
    document.getElementById('panjang_flashing').value = urlParams.get('panjang_flashing') || 0;
}

function hitungBOQ() {
    console.log('=== HITUNG BOQ DIPANGGIL ===');
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="inline w-4 h-4 mr-1 animate-spin" ...>Memproses...';
    btn.disabled = true;
    
    let data = {
        luas_atap: parseFloat(document.getElementById('luas_atap').value),
        sudut: parseFloat(document.getElementById('sudut').value),
        panjang_starter: parseFloat(document.getElementById('panjang_starter').value),
        panjang_nok_jurai: parseFloat(document.getElementById('panjang_nok_jurai').value),
        panjang_talang_jurai: parseFloat(document.getElementById('panjang_talang_jurai').value),
        panjang_flashing: parseFloat(document.getElementById('panjang_flashing').value),
        panjang_wall_flashing: parseFloat(document.getElementById('panjang_wall_flashing').value),
        produk_atap_id: document.getElementById('produk_atap_id').value,
        underlayer_id: document.getElementById('underlayer_id').value,
        rangka: document.getElementById('rangka').value,
        lantai_kerja: document.getElementById('lantai_kerja').value,
        waste: parseFloat(document.getElementById('waste').value)
    };
    
    console.log('DATA YANG DIKIRIM:', data);
    
    fetch('/boq/skyshield/hitung', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('RESPONSE STATUS:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('RESPONSE DATA:', data);
        if (data.success) {
            console.log('RESULTS DITERIMA:', data.results);
            console.log('JUMLAH ITEMS:', data.results.length);
            
            // CEK DUPLIKAT
            const seen = new Set();
            const duplicates = data.results.filter(item => {
                const key = item.id + '_' + item.area;
                if (seen.has(key)) return true;
                seen.add(key);
                return false;
            });
            
            if (duplicates.length > 0) {
                console.warn('DUPLIKAT DITEMUKAN DI RESPONSE:', duplicates);
            }
            
            currentResults = data.results;
            renderTable();
            document.getElementById('hasilBOQ').classList.remove('hidden');
            document.getElementById('btnPDF').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan');
        }
    })
    .catch(error => { 
        console.error('ERROR:', error);
        alert('Error'); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function getAreaColorClass(area) {
    const colors = {
        'Atap Utama': 'bg-blue-100 text-blue-700',
        'Underlayer': 'bg-green-100 text-green-700',
        'Starter': 'bg-orange-100 text-orange-700',
        'Nok & Jurai': 'bg-purple-100 text-purple-700',
        'Flashing': 'bg-yellow-100 text-yellow-700',
        'Talang Jurai': 'bg-cyan-100 text-cyan-700',
        'Wall Flashing': 'bg-pink-100 text-pink-700',
    };
    return colors[area] || 'bg-gray-100 text-gray-700';
}

function exportToPDF() {
    console.log('=== EXPORT TO PDF ===');
    console.log('currentResults BEFORE export:', currentResults);
    
    // CEK APAKAH UNDERLAYER ADA
    const underlayer = currentResults.find(item => item.area === 'Underlayer');
    console.log('UNDERLAYER DI CURRENT RESULTS:', underlayer);
    
    let data = {
        luas_atap: document.getElementById('luas_atap').value,
        sudut: document.getElementById('sudut').value,
        panjang_starter: document.getElementById('panjang_starter').value,
        panjang_nok_jurai: document.getElementById('panjang_nok_jurai').value,
        panjang_flashing: document.getElementById('panjang_flashing').value,
        talang_jurai: document.getElementById('panjang_talang_jurai').value,
        wall_flashing: document.getElementById('panjang_wall_flashing').value,
        waste: document.getElementById('waste').value,
        produk_atap: document.getElementById('produk_atap_id').selectedOptions[0]?.text,
        underlayer: document.getElementById('underlayer_id').selectedOptions[0]?.text,
        rangka: document.getElementById('rangka').value,
        lantai_kerja: document.getElementById('lantai_kerja').value,
        grand_total: document.getElementById('grandTotal').innerText,
        results: currentResults.map(item => ({
            id: item.id,
            product_id: item.product_id,
            nama: item.nama_produk,
            area: item.area,
            qty: item.qty,
            satuan: item.satuan,
            harga: item.harga_satuan,
            total: item.total_harga
        }))
    };
    
    console.log('FINAL DATA YANG DIKIRIM:', data);
    
    fetch('/boq/skyshield/export-pdf', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(html => {
        let win = window.open();
        win.document.write(html);
        win.document.close();
    });
}
</script>
@endsection