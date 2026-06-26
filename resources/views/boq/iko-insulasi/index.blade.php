@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body, button, input, select {
        font-family: 'Poppins', sans-serif !important;
    }
    input, select, button {
        font-size: 13px !important;
    }
    label {
        font-size: 11px !important;
        letter-spacing: 0.3px;
    }
</style>

<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h2 class="text-white font-semibold text-lg">BOQ - IKO Insulasi</h2>
            <p class="text-blue-100 text-xs">Hitung kebutuhan material insulasi</p>
        </div>
        
        <div class="p-6">
            <!-- Data Perhitungan -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <label class="block text-[10px] font-medium text-gray-500 mb-1">📐 Luas Area (m²)</label>
                        <input type="number" id="luas" class="w-full text-sm font-semibold bg-transparent" readonly value="{{ $luas ?? 0 }}">
                    </div>
                </div>
            </div>
            
            <!-- Pilih Produk -->
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">📦 Pilih Produk Insulasi</label>
                    <select id="produk_insulasi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">⚡ Waste (%)</label>
                    <input type="number" id="waste" value="5" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Tombol Hitung -->
            <button onclick="hitungMaterial()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all duration-200">
                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
                Hitung Material
            </button>
            
            <!-- Hasil Material -->
            <div id="hasilMaterial" class="mt-4 hidden">
                <div class="border-t pt-3">
                    <h4 class="text-xs font-semibold text-blue-700 mb-2">📋 Rincian Material</h4>
                    <div id="tableMaterial"></div>
                    <div class="text-right mt-2 font-semibold text-blue-600" id="grandTotal">Rp 0</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungMaterial() {
    let data = {
        luas: parseFloat(document.getElementById('luas').value) || 0,
        produk_insulasi_id: document.getElementById('produk_insulasi').value,
        waste: parseFloat(document.getElementById('waste').value) || 5
    };
    
    if (data.luas <= 0) {
        alert('⚠️ Luas area harus diisi terlebih dahulu!');
        return;
    }
    
    if (!data.produk_insulasi_id) {
        alert('⚠️ Pilih produk insulasi terlebih dahulu!');
        return;
    }
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="inline w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Menghitung...';
    btn.disabled = true;
    
    let url = '/boq/iko-insulasi/hitung';
    
    fetch(url, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderTable(data.results);
            document.getElementById('hasilMaterial').classList.remove('hidden');
            document.getElementById('grandTotal').innerHTML = 'Rp ' + data.grand_total.toLocaleString();
        } else {
            alert('Error: ' + (data.message || 'Gagal hitung'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan server');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function renderTable(results) {
    let html = `<div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Produk</th>
                    <th class="p-2 text-right">Qty</th>
                    <th class="p-2 text-right">Satuan</th>
                    <th class="p-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>`;
    
    let no = 1;
    results.forEach(item => {
        html += `<tr>
            <td class="p-2 text-center">${no++}</td>
            <td class="p-2">${item.nama_produk}</td>
            <td class="p-2 text-right">${item.qty}</td>
            <td class="p-2 text-right">${item.satuan}</td>
            <td class="p-2 text-right">Rp ${item.total_harga.toLocaleString()}</td>
        </tr>`;
    });
    
    html += `</tbody>
        </table>
    </div>`;
    document.getElementById('tableMaterial').innerHTML = html;
}
</script>
@endsection