<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\BoqController;

class IkoAtapKombinasiController extends Controller
{
     protected $boqController;

    public function __construct(BoqController $boqController)
    {
        $this->boqController = $boqController;
    }
    public function index()
    {
        Log::info('=== IkoAtapController: index() ===');
        
        $brand = ProductBrand::where('nama_brand', 'IKO - ATAP')->first();
        
        $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
        $products = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaAtapUtama->id)
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->get();
        
        $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
        $underlayers = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaUnderlayer->id)
            ->with('unit')
            ->get();

        $areaLantaiKerja = ProductArea::where('id', '21')->first();
        $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaLantaiKerja->id)
            ->with('unit')
            ->orderBy('id', 'asc')
            ->get();
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        
        return view('boq.atap-kombinasi.iko-atap', compact(
            'products', 
            'underlayers', 
            'rangkaOptions', 
            'lantaiKerjaOptions'
        ));
    }
    
    public function hitung(Request $request)
    {
        Log::info('=== IkoAtapController: hitung() ===');
        
        $luasAtap = $request->luas_atap;
        $panjangStarter = $request->panjang_starter;
        $sudut = $request->sudut;
        $panjangNokJurai = $request->panjang_nok_jurai;
        $panjangTalangJurai = $request->panjang_talang_jurai;
        $panjangFlashing = $request->panjang_flashing;
        $panjangWallFlashing = $request->panjang_wall_flashing;
        $waste = $request->waste / 100;
        
        $produkAtapId = $request->produk_atap_id;
        $underlayerId = $request->underlayer_id;
        
        $results = [];
        
        // 1. Atap Utama + aksesoris (SEMUA dari sini)
        if ($produkAtapId) {
            $produkAtap = Product::with(['unit', 'area', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
            
            // Hitung Atap Utama
            $qtyRaw = $luasAtap / $produkAtap->satuan_terkecil;
            $qtyAtapUtama = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($produkAtap, $qtyAtapUtama, 'Atap Utama', $luasAtap);
            
            // Loop aksesoris
            foreach ($produkAtap->accessories as $aksesoris) {
                $areaName = $aksesoris->area->nama_area;
                
                // SKIP yang dihitung terpisah
                if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                    Log::info('Skip ' . $areaName . ' (dihitung terpisah):', ['nama' => $aksesoris->nama_produk]);
                    continue;
                }
                
                // Hitung qty berdasarkan area
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, $panjangNokJurai, $panjangFlashing, $panjangTalangJurai, $panjangWallFlashing, $waste, $sudut);
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $areaName, '-');
                }
            }
        }
        
        // 2. Underlayer (dari dropdown)
        if ($underlayerId) {
            $underlayer = Product::with('unit')->find($underlayerId);
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
        }
        
        // 3. Talang Jurai (conditional)
      if ($panjangTalangJurai > 0 && $produkAtapId) {
    // Ambil produk atap utama untuk dapetin brand_id dan relasi aksesoris
    $produkAtap = Product::with(['accessories'])->find($produkAtapId);
    
    // Cari aksesoris yang area nya 'Talang Jurai' dari parent product
    $talangJurai = $produkAtap->accessories->first(function($aksesoris) {
        return $aksesoris->area->nama_area == 'Talang Jurai';
    });
    
    if ($talangJurai) {
        $qtyRaw = $panjangTalangJurai / $talangJurai->satuan_terkecil;
        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
        $results[] = $this->formatResult($talangJurai, $qty, 'Talang Jurai', $panjangTalangJurai);
    }
}
        
        // 4. Wall Flashing (conditional)
        if ($panjangWallFlashing > 0) {
            $wallFlashing = Product::where('brand_id', 1)
                ->whereHas('area', fn($q) => $q->where('nama_area', 'Wall Flashing'))
                ->first();
                
         if ($wallFlashing) {
    $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
    
    Log::info('Perhitungan Wall Flashing:', [
        'panjangWallFlashing' => $panjangWallFlashing,
        'satuan_terkecil' => $wallFlashing->satuan_terkecil,
        'qtyRaw' => $qtyRaw,
        'waste' => $waste,
        'qtyRaw_waste' => $qtyRaw + ($qtyRaw * $waste),
        'qty_final' => $qty
    ]);
    
    $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
}
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'grand_total' => $grandTotal
        ]);
    }
    
    private function hitungQtyByArea($areaName, $product, $luasAtap, $panjangStarter, $panjangNokJurai, $panjangFlashing, $panjangTalangJurai, $panjangWallFlashing, $waste, $sudut)
    {
        $satuan = $product->satuan_terkecil;
        
        switch ($areaName) {
            case 'Atap Utama':
            case 'Underlayer':
                $qtyRaw = $luasAtap / $satuan;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Starter':
                return ceil($panjangStarter / $satuan);
                
            case 'Nok & Jurai':
                $qtyRaw = $panjangNokJurai / $satuan;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Metal Flashing':
                $qtyRaw = $panjangFlashing / $satuan;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Ridge Ventilator':
                $koefisienNFA = ($sudut >= 15 && $sudut <= 40) ? 300 : 600;
                $variable = (($luasAtap / $koefisienNFA) * 10000) / 275;
                $qtyRaw = $variable / $satuan;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Paku & Screw':
                $satuanPaku = ($sudut < 45) ? 20 : 13.4;
                $qtyRaw = $luasAtap / $satuanPaku;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Lem':
                return ceil($panjangStarter / $satuan);
                
            default:
                return 0;
        }
    }
    
    private function formatResult($product, $qty, $area, $inputValue)
    {
        return [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $area,
            'input_value' => $inputValue,
            'satuan_terkecil' => $product->satuan_terkecil,
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_price_list,
            'total_harga' => $qty * $product->harga_price_list
        ];
    }
public function exportPdf(Request $request)
{
    // ==================== LOG AWAL ====================
    \Log::info('=== EXPORT PDF IKO ATAP KOMBINASI - MULAI ===');
    
    $data = $request->all();
    
    // LOG DATA MENTAH DARI REQUEST
    \Log::info('DATA MENTAH DARI REQUEST:', [
        'data' => $data
    ]);
    
    // Decode JSON hasil (karena dikirim sebagai string dari JavaScript)
    if (isset($data['bagian1']['hasil']) && is_string($data['bagian1']['hasil'])) {
        $data['bagian1']['hasil'] = json_decode($data['bagian1']['hasil'], true);
        \Log::info('BAGIAN 1 HASIL DECODED:', [
            'total' => count($data['bagian1']['hasil']),
            'items' => $data['bagian1']['hasil']
        ]);
    }
    
    if (isset($data['bagian2']['hasil']) && is_string($data['bagian2']['hasil'])) {
        $data['bagian2']['hasil'] = json_decode($data['bagian2']['hasil'], true);
        \Log::info('BAGIAN 2 HASIL DECODED:', [
            'total' => count($data['bagian2']['hasil']),
            'items' => $data['bagian2']['hasil']
        ]);
    }
    
    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    \Log::info('NOMOR BOQ GENERATED:', ['nomor_boq' => $nomorBoq]);
    
    // ============ STORE BOQ ============
    try {
        $allProducts = [];
        $totalItems = 0;
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 2; $i++) {
            if (isset($data["bagian{$i}"]['hasil']) && is_array($data["bagian{$i}"]['hasil'])) {
                $bagianCount = count($data["bagian{$i}"]['hasil']);
                $totalItems += $bagianCount;
                \Log::info("BAGIAN {$i} - JUMLAH ITEM: {$bagianCount}");
                
                foreach ($data["bagian{$i}"]['hasil'] as $index => $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    $nama = $item['nama_produk'] ?? $item['nama'] ?? null;
                    
                    \Log::info("BAGIAN {$i} ITEM #{$index}: product_id={$produkId}, qty={$qty}, nama={$nama}");
                    
                    if ($produkId && $qty > 0) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                            \Log::info("PRODUK BARU ID {$produkId} DITAMBAHKAN DENGAN QTY {$qty}");
                        } else {
                            \Log::info("PRODUK ID {$produkId} SUDAH ADA, QTY SEBELUM: {$allProducts[$produkId]['qty']}, QTY TAMBAH: {$qty}");
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                        \Log::info("PRODUK ID {$produkId} QTY SEKARANG: {$allProducts[$produkId]['qty']}");
                    }
                }
            } else {
                \Log::warning("BAGIAN {$i} TIDAK ADA ATAU KOSONG");
            }
        }
        
        \Log::info('TOTAL ITEM DARI SEMUA BAGIAN: ' . $totalItems);
        \Log::info('TOTAL PRODUK UNIK: ' . count($allProducts));
        
        // Konversi ke array
        $uniqueResults = [];
        $seenIds = [];
        $counter = 0;
        
        foreach ($allProducts as $product) {
            $produkId = $product['produk_id'] ?? null;
            
            if (!$produkId) {
                \Log::warning("PRODUK ID KOSONG, DI-SKIP");
                continue;
            }
            
            if (in_array($produkId, $seenIds)) {
                \Log::warning("DUPLIKAT DITEMUKAN UNTUK ID {$produkId}, DI-SKIP");
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = [
                'id' => $produkId,
                'product_id' => $produkId,
                'qty' => $product['qty']
            ];
            $counter++;
            \Log::info("PRODUK UNIK #{$counter}: ID={$produkId}, QTY={$product['qty']}");
        }
        
        \Log::info('UNIQUE RESULTS FINAL:', [
            'total' => count($uniqueResults),
            'ids' => array_column($uniqueResults, 'id'),
            'qties' => array_column($uniqueResults, 'qty')
        ]);
        
        // Simpan ke database
        if (!empty($uniqueResults)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            \Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
            
            $savedCount = 0;
            foreach ($uniqueResults as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = \App\Models\Product::find($produkId);
                    
                    if ($produk) {
                        \DB::table('detail_boq')->insert([
                            'boq_id' => $boq->id,
                            'produk_id' => $produkId,
                            'kode_produk' => $produk->kode_produk,
                            'qty' => $qty,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $savedCount++;
                        \Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}, kode={$produk->kode_produk}");
                    } else {
                        \Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN DI DATABASE");
                    }
                }
            }
            
            \Log::info('BOQ SAVED SUCCESSFULLY:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total_produk' => $savedCount
            ]);
        } else {
            \Log::warning('TIDAK ADA PRODUK UNIK UNTUK DISIMPAN');
        }
        
    } catch (\Exception $e) {
        \Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        \Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }
    
    \Log::info('=== EXPORT PDF IKO ATAP KOMBINASI - SELESAI ===');
    
    return view('boq.atap-kombinasi.iko-atap-pdf', ['data' => $data]);
}
public function limasanTrapesium(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding1 = $request->query('dinding', 0);
    $opsiKaca1 = $request->query('kaca', 0);
    $opsiPenangkal1 = $request->query('penangkal', 0);
    $opsiExhaust1 = $request->query('exhaust', 0); // <-- TAMBAHKAN
    
    $opsiDinding2 = $request->query('dinding', 0);
    $opsiKaca2 = $request->query('kaca', 0);
    $opsiPenangkal2 = $request->query('penangkal', 0);
    $opsiExhaust2 = $request->query('exhaust', 0); // <-- TAMBAHKAN
    
    \Log::info('=== BOQ Limasan Trapesium ===');
    \Log::info('sudut_1 (Limasan): ' . $sudut_1);
    \Log::info('sudut_2 (Trapesium): ' . $sudut_2);
    \Log::info('opsi_exhaust_1: ' . $opsiExhaust1);
    \Log::info('opsi_exhaust_2: ' . $opsiExhaust2);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // UNDERLAYER UNTUK LIMASAN (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        \Log::info('Limasan: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 22);
    } else {
        \Log::info('Limasan: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK TRAPESIUM (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        \Log::info('Trapesium: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 22);
    } else {
        \Log::info('Trapesium: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    \Log::info('Jumlah underlayer Limasan: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Trapesium: ' . $underlayers_2->count());
    
      $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();

    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    
    return view('boq.atap-kombinasi.limasan-trapesium', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1', 'opsiExhaust1', // <-- TAMBAHKAN opsiExhaust1
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2', 'opsiExhaust2'  // <-- TAMBAHKAN opsiExhaust2
    ));
}
public function limasPelana(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding1 = $request->query('dinding', 0);
    $opsiKaca1 = $request->query('kaca', 0);
    $opsiPenangkal1 = $request->query('penangkal', 0);
    $opsiDinding2 = $request->query('dinding', 0);
    $opsiKaca2 = $request->query('kaca', 0);
    $opsiPenangkal2 = $request->query('penangkal', 0);
    
    \Log::info('=== BOQ Limas + Pelana ===');
    \Log::info('sudut_1 (Limas): ' . $sudut_1);
    \Log::info('sudut_2 (Pelana): ' . $sudut_2);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // UNDERLAYER UNTUK LIMAS (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        \Log::info('Limas: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 22);
    } else {
        \Log::info('Limas: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK PELANA (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        \Log::info('Pelana: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 22);
    } else {
        \Log::info('Pelana: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    \Log::info('Jumlah underlayer Limas: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Pelana: ' . $underlayers_2->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.atap-kombinasi.limas-pelana', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2'
    ));
}
public function exportPdfLimasPelana(Request $request)
{
    $data = $request->all();
    
    // Decode JSON hasil
    if (isset($data['bagian1']['hasil']) && is_string($data['bagian1']['hasil'])) {
        $data['bagian1']['hasil'] = json_decode($data['bagian1']['hasil'], true);
    }
    
    if (isset($data['bagian2']['hasil']) && is_string($data['bagian2']['hasil'])) {
        $data['bagian2']['hasil'] = json_decode($data['bagian2']['hasil'], true);
    }
    
    // Proses data
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing'];
    
    foreach (['bagian1', 'bagian2'] as $bagian) {
        if (isset($data[$bagian]['data_perhitungan'])) {
            foreach ($numericFields as $field) {
                if (isset($data[$bagian]['data_perhitungan'][$field])) {
                    $data[$bagian]['data_perhitungan'][$field] = (float) $data[$bagian]['data_perhitungan'][$field];
                }
            }
        }
        
        if (isset($data[$bagian]['total']) && is_string($data[$bagian]['total'])) {
            $data[$bagian]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data[$bagian]['total']);
        }
    }
    
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 2; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.limas-pelana-pdf', ['data' => $data]);
}

public function pelana2Trapesium(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'IKO - ATAP')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0); // Pelana
    $sudut_2 = $request->query('sudut_2', 0); // Trapesium A
    $sudut_3 = $request->query('sudut_3', 0); // Trapesium B
    
    // Ambil opsi tambahan dari URL
    $opsiDinding1 = $request->query('dinding', 0);
    $opsiKaca1 = $request->query('kaca', 0);
    $opsiPenangkal1 = $request->query('penangkal', 0);
    $opsiDinding2 = $request->query('dinding', 0);
    $opsiKaca2 = $request->query('kaca', 0);
    $opsiPenangkal2 = $request->query('penangkal', 0);
    $opsiDinding3 = $request->query('dinding', 0);
    $opsiKaca3 = $request->query('kaca', 0);
    $opsiPenangkal3 = $request->query('penangkal', 0);
    
    \Log::info('=== BOQ Pelana + 2 Trapesium ===');
    \Log::info('sudut_1 (Pelana): ' . $sudut_1);
    \Log::info('sudut_2 (Trapesium A): ' . $sudut_2);
    \Log::info('sudut_3 (Trapesium B): ' . $sudut_3);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // UNDERLAYER UNTUK PELANA (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        \Log::info('Pelana: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 22);
    } else {
        \Log::info('Pelana: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK TRAPESIUM A (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        \Log::info('Trapesium A: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 22);
    } else {
        \Log::info('Trapesium A: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    // UNDERLAYER UNTUK TRAPESIUM B (Bagian 3)
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_3 <= 15 && $sudut_3 > 0) {
        \Log::info('Trapesium B: Sudut ' . $sudut_3 . '° <= 30°, filter underlayer ID 22');
        $underlayers_3 = $underlayers_3->where('id', 22);
    } else {
        \Log::info('Trapesium B: Sudut ' . $sudut_3 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_3 = $underlayers_3->get();
    
    \Log::info('Jumlah underlayer Pelana: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Trapesium A: ' . $underlayers_2->count());
    \Log::info('Jumlah underlayer Trapesium B: ' . $underlayers_3->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
  $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.atap-kombinasi.pelana-2trapesium', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2', 'sudut_3',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2',
        'opsiDinding3', 'opsiKaca3', 'opsiPenangkal3'
    ));
}

public function exportPdfPelana2Trapesium(Request $request)
{
    $data = $request->all();
    
    // Decode JSON
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 3; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.pelana-2trapesium-pdf', ['data' => $data]);
}
public function limasanLimasan(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding1 = $request->query('dinding', 0);
    $opsiKaca1 = $request->query('kaca', 0);
    $opsiPenangkal1 = $request->query('penangkal', 0);
    $opsiDinding2 = $request->query('dinding', 0);
    $opsiKaca2 = $request->query('kaca', 0);
    $opsiPenangkal2 = $request->query('penangkal', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // UNDERLAYER UNTUK LIMASAN A
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK LIMASAN B
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 22);
    }
    $underlayers_2 = $underlayers_2->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.atap-kombinasi.limasan-limasan', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2'
    ));
}
public function exportPdfLimasanLimasan(Request $request)
{
    $data = $request->all();
    
    for ($i = 1; $i <= 2; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 2; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.limasan-limasan-pdf', ['data' => $data]);
}

public function pelanaPelana(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)->with('unit');
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)->with('unit');
    
    if ($sudut_1 <= 30 && $sudut_1 > 0) $underlayers_1 = $underlayers_1->where('id', 22);
    if ($sudut_2 <= 30 && $sudut_2 > 0) $underlayers_2 = $underlayers_2->where('id', 22);
    
    $underlayers_1 = $underlayers_1->get();
    $underlayers_2 = $underlayers_2->get();
    
    return view('boq.atap-kombinasi.pelana-pelana', compact('products', 'underlayers_1', 'underlayers_2', 'sudut_1', 'sudut_2'));
}
public function pelanaX(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 22);
    }
    $underlayers_2 = $underlayers_2->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_3 <= 15 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 22);
    }
    $underlayers_3 = $underlayers_3->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();

    
    return view('boq.atap-kombinasi.pelana-x', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2', 'sudut_3',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}
public function exportPdfPelanaX(Request $request)
{
    // ==================== LOG AWAL ====================
    \Log::info('=== EXPORT PDF PELANA X - MULAI ===');
    
    $data = $request->all();
    
    // LOG DATA MENTAH DARI REQUEST
    \Log::info('DATA MENTAH DARI REQUEST PELANA X:', [
        'data' => $data
    ]);
    
    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
        \Log::info('HASIL DECODED:', [
            'total' => count($data['hasil']),
            'items' => $data['hasil']
        ]);
    }
    
    // ===== PROSES DATA HASIL - AMBIL HARGA DARI DATABASE =====
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        foreach ($data['hasil'] as $key => $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
            // ===== AMBIL HARGA DARI DATABASE =====
            $harga = 0;
            if ($produkId) {
                $produk = \App\Models\Product::find($produkId);
                if ($produk) {
                    // Ambil harga_price_list dari database
                    $harga = (float) $produk->harga_price_list;
                    \Log::info("AMBIL HARGA DARI DB: produk_id={$produkId}, harga={$harga}");
                }
            }
            
            // Jika produk tidak ditemukan di DB, coba dari data yang dikirim
            if ($harga == 0) {
                if (isset($item['harga_satuan'])) {
                    if (is_string($item['harga_satuan'])) {
                        $harga = (float) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
                    } else {
                        $harga = (float) $item['harga_satuan'];
                    }
                }
            }
            
            // Konversi qty
            $qty = (int)($item['qty'] ?? 0);
            
            // Hitung total
            $total = $harga * $qty;
            
            // Update data
            $data['hasil'][$key]['harga_satuan'] = $harga;
            $data['hasil'][$key]['total_harga'] = $total;
            $data['hasil'][$key]['qty'] = $qty;
            
            \Log::info("PROSES INDEX {$key}:", [
                'nama' => $item['nama_produk'] ?? $item['nama'] ?? 'unknown',
                'produk_id' => $produkId,
                'harga_dari_db' => $harga,
                'qty' => $qty,
                'total_harga' => $total
            ]);
        }
    }
    
    // Hitung ulang grand_total
    $grandTotal = 0;
    foreach ($data['hasil'] ?? [] as $item) {
        $grandTotal += $item['total_harga'] ?? 0;
    }
    $data['grand_total'] = $grandTotal;
    \Log::info('GRAND TOTAL DIHITUNG ULANG:', ['grand_total' => $grandTotal]);
    
    // Konversi data perhitungan
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'talang_jurai', 'wall_flashing'];
    foreach ($numericFields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    \Log::info('NOMOR BOQ GENERATED:', ['nomor_boq' => $nomorBoq]);

    // ==================== STORE BOQ ====================
    try {
        if (!isset($data['hasil']) || empty($data['hasil'])) {
            \Log::warning('TIDAK ADA DATA HASIL UNTUK DISIMPAN');
            return view('boq.atap-kombinasi.pelana-x-pdf', ['data' => $data]);
        }
        
        $allIds = array_column($data['hasil'], 'id');
        \Log::info('SEMUA ID (array_column):', ['ids' => $allIds]);
        
        $boq = new Boq();
        $boq->nomor_boq = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();
        
        \Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
        
        $savedCount = 0;
        $savedIds = [];
        $uniqueResults = [];
        
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            $qty = (int)($item['qty'] ?? 0);
            $nama = $item['nama_produk'] ?? $item['nama'] ?? null;
            $area = $item['area'] ?? null;
            $harga = $item['harga_satuan'] ?? 0;
            $total = $item['total_harga'] ?? 0;
            $satuan = $item['satuan'] ?? 'pcs';
            
            \Log::info("PROSES INDEX {$i}:", [
                'produk_id' => $produkId,
                'qty' => $qty,
                'area' => $area,
                'harga' => $harga,
                'total' => $total
            ]);
            
            // SKIP jika produk_id null (seperti Plywood yang tidak ada di database)
            if (!$produkId) {
                \Log::warning("SKIP INDEX {$i}: produk_id={$produkId} (NULL)");
                continue;
            }
            
            if ($qty <= 0) {
                \Log::warning("SKIP INDEX {$i}: qty={$qty}");
                continue;
            }
            
            if (in_array($produkId, $savedIds)) {
                \Log::warning("DUPLIKAT SKIP: produk_id={$produkId} sudah pernah disimpan");
                continue;
            }
            
            $produk = \App\Models\Product::find($produkId);
            
            if ($produk) {
                \DB::table('detail_boq')->insert([
                    'boq_id' => $boq->id,
                    'produk_id' => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty' => $qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $savedIds[] = $produkId;
                $savedCount++;
                \Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}, kode={$produk->kode_produk}");
                
                $uniqueResults[] = [
                    'id' => $produkId,
                    'product_id' => $produkId,
                    'qty' => $qty,
                    'nama' => $nama,
                    'nama_produk' => $nama,
                    'area' => $area,
                    'harga_satuan' => $harga,
                    'total_harga' => $total,
                    'satuan' => $satuan
                ];
            } else {
                \Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN DI DATABASE");
            }
        }
        
        // Update data['hasil'] dengan hasil unik untuk PDF
        $data['hasil'] = $uniqueResults;
        
        \Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id' => $boq->id,
            'nomor_boq' => $nomorBoq,
            'total_produk' => $savedCount,
            'saved_ids' => $savedIds
        ]);
        
    } catch (\Exception $e) {
        \Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        \Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }
    
    \Log::info('=== EXPORT PDF PELANA X - SELESAI ===');
    
    return view('boq.atap-kombinasi.pelana-x-pdf', ['data' => $data]);
}
public function limasanX(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    $underlayers_1 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_1);
    $underlayers_2 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_2);
    $underlayers_3 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_3);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.atap-kombinasi.limasan-x', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2', 'sudut_3',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}

public function exportPdfLimasanX(Request $request)
{
    // ==================== LOG AWAL ====================
    \Log::info('=== EXPORT PDF LIMASAN X - MULAI ===');
    
    $data = $request->all();
    
    // LOG DATA MENTAH DARI REQUEST
    \Log::info('DATA MENTAH DARI REQUEST LIMASAN X:', [
        'data' => $data
    ]);
    
    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
        \Log::info('HASIL DECODED:', [
            'total' => count($data['hasil']),
            'items' => $data['hasil']
        ]);
    }
    
    // ===== PROSES DATA HASIL - AMBIL HARGA DARI DATABASE =====
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        // Pakai FOR loop, bukan foreach
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
            // ===== AMBIL HARGA DARI DATABASE =====
            $harga = 0;
            if ($produkId) {
                $produk = \App\Models\Product::find($produkId);
                if ($produk) {
                    // Ambil harga_price_list dari database
                    $harga = (float) $produk->harga_price_list;
                    \Log::info("AMBIL HARGA DARI DB: produk_id={$produkId}, harga={$harga}");
                }
            }
            
            // Jika produk tidak ditemukan di DB, coba dari data yang dikirim
            if ($harga == 0) {
                if (isset($item['harga_satuan'])) {
                    if (is_string($item['harga_satuan'])) {
                        $harga = (float) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
                    } else {
                        $harga = (float) $item['harga_satuan'];
                    }
                }
            }
            
            // Konversi qty
            $qty = (int)($item['qty'] ?? 0);
            
            // Hitung total
            $total = $harga * $qty;
            
            // Update data di array asli
            $data['hasil'][$i]['harga_satuan'] = $harga;
            $data['hasil'][$i]['total_harga'] = $total;
            $data['hasil'][$i]['qty'] = $qty;
            
            \Log::info("PROSES INDEX {$i}:", [
                'nama' => $item['nama_produk'] ?? $item['nama'] ?? 'unknown',
                'produk_id' => $produkId,
                'harga_dari_db' => $harga,
                'qty' => $qty,
                'total_harga' => $total
            ]);
        }
    }
    
    // Hitung ulang grand_total
    $grandTotal = 0;
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        for ($i = 0; $i < count($data['hasil']); $i++) {
            $grandTotal += $data['hasil'][$i]['total_harga'] ?? 0;
        }
    }
    $data['grand_total'] = $grandTotal;
    \Log::info('GRAND TOTAL DIHITUNG ULANG:', ['grand_total' => $grandTotal]);
    
    // Konversi data perhitungan
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'talang_jurai', 'wall_flashing'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    \Log::info('NOMOR BOQ GENERATED:', ['nomor_boq' => $nomorBoq]);

    // ==================== STORE BOQ ====================
    try {
        if (!isset($data['hasil']) || empty($data['hasil'])) {
            \Log::warning('TIDAK ADA DATA HASIL UNTUK DISIMPAN');
            return view('boq.atap-kombinasi.limasan-x-pdf', ['data' => $data]);
        }
        
        // Ambil semua ID pakai array_column
        $allIds = array_column($data['hasil'], 'id');
        \Log::info('SEMUA ID (array_column):', ['ids' => $allIds]);
        
        $boq = new Boq();
        $boq->nomor_boq = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();
        
        \Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
        
        $savedCount = 0;
        $savedIds = [];
        $uniqueResults = [];
        
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            $qty = (int)($item['qty'] ?? 0);
            $nama = $item['nama_produk'] ?? $item['nama'] ?? null;
            $area = $item['area'] ?? null;
            $harga = $item['harga_satuan'] ?? 0;
            $total = $item['total_harga'] ?? 0;
            $satuan = $item['satuan'] ?? 'pcs';
            
            \Log::info("PROSES INDEX {$i}:", [
                'produk_id' => $produkId,
                'qty' => $qty,
                'area' => $area,
                'harga' => $harga,
                'total' => $total
            ]);
            
            // SKIP jika produk_id null (seperti Plywood yang tidak ada di database)
            if (!$produkId) {
                \Log::warning("SKIP INDEX {$i}: produk_id={$produkId} (NULL)");
                continue;
            }
            
            if ($qty <= 0) {
                \Log::warning("SKIP INDEX {$i}: qty={$qty}");
                continue;
            }
            
            if (in_array($produkId, $savedIds)) {
                \Log::warning("DUPLIKAT SKIP: produk_id={$produkId} sudah pernah disimpan");
                continue;
            }
            
            $produk = \App\Models\Product::find($produkId);
            
            if ($produk) {
                \DB::table('detail_boq')->insert([
                    'boq_id' => $boq->id,
                    'produk_id' => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty' => $qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $savedIds[] = $produkId;
                $savedCount++;
                \Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}, kode={$produk->kode_produk}");
                
                $uniqueResults[] = [
                    'id' => $produkId,
                    'product_id' => $produkId,
                    'qty' => $qty,
                    'nama' => $nama,
                    'nama_produk' => $nama,
                    'area' => $area,
                    'harga_satuan' => $harga,
                    'total_harga' => $total,
                    'satuan' => $satuan
                ];
            } else {
                \Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN DI DATABASE");
            }
        }
        
        // Update data['hasil'] dengan hasil unik untuk PDF
        $data['hasil'] = $uniqueResults;
        
        \Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id' => $boq->id,
            'nomor_boq' => $nomorBoq,
            'total_produk' => $savedCount,
            'saved_ids' => $savedIds
        ]);
        
    } catch (\Exception $e) {
        \Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        \Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }
    
    \Log::info('=== EXPORT PDF LIMASAN X - SELESAI ===');
    
    return view('boq.atap-kombinasi.limasan-x-pdf', ['data' => $data]);
}
private function getFilteredUnderlayers($brand, $areaUnderlayer, $sudut)
{
    $query = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut <= 15 && $sudut > 0) {
        $query = $query->where('id', 22);
    }
    
    return $query->get();
}
public function gergaji(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut = $request->query('sudut', 0);
    $panjangBangunan = $request->query('panjang_bangunan', 0);
    $lebarBangunan = $request->query('lebar_bangunan', 0);
    $jumlahGerigi = $request->query('jumlah_gerigi', 0);
    $tinggiGerigi = $request->query('tinggi_gerigi', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut <= 15 && $sudut > 0) {
        $underlayers = $underlayers->where('id', 22);
    }
    $underlayers = $underlayers->get();
      $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    
    return view('boq.atap-kombinasi.gergaji', compact(
        'products', 'starters', 'underlayers', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut', 'panjangBangunan', 'lebarBangunan', 'jumlahGerigi', 'tinggiGerigi',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}
public function exportPdfGergaji(Request $request)
{
    // ==================== LOG AWAL ====================
    \Log::info('=== EXPORT PDF GERGAJI - MULAI ===');
    
    $data = $request->all();
    
    // LOG DATA MENTAH DARI REQUEST
    \Log::info('DATA MENTAH DARI REQUEST GERGAJI:', [
        'data' => $data
    ]);
    
    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
        \Log::info('HASIL DECODED:', [
            'total' => count($data['hasil']),
            'items' => $data['hasil']
        ]);
    }
    
    // Proses data hasil (konversi string ke integer) - HAPUS REFERENCE &
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        foreach ($data['hasil'] as $key => $item) {
            if (isset($item['qty'])) {
                $data['hasil'][$key]['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
            }
            if (isset($item['harga_satuan']) && is_string($item['harga_satuan'])) {
                $data['hasil'][$key]['harga_satuan'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
            }
            if (isset($item['total_harga']) && is_string($item['total_harga'])) {
                $data['hasil'][$key]['total_harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['total_harga']);
            }
        }
    }
    
    // Konversi grand total
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }
    
    // Konversi data perhitungan
    $numericFields = ['luas_atap', 'starter', 'nok_jurai', 'flashing', 'talang_jurai', 'wall_flashing', 'panjang_bangunan', 'lebar_bangunan', 'tinggi_gerigi'];
    foreach ($numericFields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    \Log::info('NOMOR BOQ GENERATED:', ['nomor_boq' => $nomorBoq]);

    // ==================== STORE BOQ ====================
    try {
        // CEK APAKAH HASIL ADA
        if (!isset($data['hasil']) || empty($data['hasil'])) {
            \Log::warning('TIDAK ADA DATA HASIL UNTUK DISIMPAN');
            return view('boq.atap-kombinasi.gergaji-pdf', ['data' => $data]);
        }
        
        // AMBIL SEMUA ID PAKAI array_column
        $allIds = array_column($data['hasil'], 'id');
        \Log::info('SEMUA ID (array_column):', ['ids' => $allIds]);
        
        // Simpan ke database
        $boq = new Boq();
        $boq->nomor_boq = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();
        
        \Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
        
        $savedCount = 0;
        $savedIds = [];
        
        // PAKAI FOR LOOP - LEBIH AMAN DARI FOREACH
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            $qty = (int)($item['qty'] ?? 0);
            
            \Log::info("PROSES INDEX {$i}:", [
                'produk_id' => $produkId,
                'qty' => $qty,
                'area' => $item['area'] ?? null
            ]);
            
            // SKIP JIKA TIDAK VALID
            if (!$produkId || $qty <= 0) {
                \Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }
            
            // CEK DUPLIKAT SEBELUM SIMPAN
            if (in_array($produkId, $savedIds)) {
                \Log::warning("DUPLIKAT SKIP: produk_id={$produkId} sudah pernah disimpan");
                continue;
            }
            
            $produk = \App\Models\Product::find($produkId);
            
            if ($produk) {
                \DB::table('detail_boq')->insert([
                    'boq_id' => $boq->id,
                    'produk_id' => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty' => $qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $savedIds[] = $produkId;
                $savedCount++;
                \Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}, kode={$produk->kode_produk}");
            } else {
                \Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN DI DATABASE");
            }
        }
        
        \Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id' => $boq->id,
            'nomor_boq' => $nomorBoq,
            'total_produk' => $savedCount,
            'saved_ids' => $savedIds
        ]);
        
    } catch (\Exception $e) {
        \Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        \Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }
    
    \Log::info('=== EXPORT PDF GERGAJI - SELESAI ===');
    
    return view('boq.atap-kombinasi.gergaji-pdf', ['data' => $data]);
}
public function pelana2Kemiringan(Request $request)
{
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    // Ambil opsi tambahan dari URL
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiPenangkal1 = $request->query('penangkal_1', 0);
    
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    $opsiPenangkal2 = $request->query('penangkal_2', 0);
    
    $opsiDinding3 = $request->query('dinding_3', 0);
    $opsiKaca3 = $request->query('kaca_3', 0);
    $opsiPenangkal3 = $request->query('penangkal_3', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 22);
    }
    $underlayers_2 = $underlayers_2->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_3 <= 15 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 22);
    }
    $underlayers_3 = $underlayers_3->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.atap-kombinasi.pelana-2-kemiringan', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut_1', 'sudut_2', 'sudut_3',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2',
        'opsiDinding3', 'opsiKaca3', 'opsiPenangkal3'
    ));
}
public function exportPdfPelana2Kemiringan(Request $request)
{
    $data = $request->all();
    
    // Decode JSON untuk 3 bagian
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }
    
    // Konversi nilai numerik
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing'];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['data_perhitungan'])) {
            foreach ($numericFields as $field) {
                if (isset($data["bagian{$i}"]['data_perhitungan'][$field])) {
                    $data["bagian{$i}"]['data_perhitungan'][$field] = (float) $data["bagian{$i}"]['data_perhitungan'][$field];
                }
            }
        }
        
        if (isset($data["bagian{$i}"]['total']) && is_string($data["bagian{$i}"]['total'])) {
            $data["bagian{$i}"]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data["bagian{$i}"]['total']);
        }
    }
    
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 3; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.pelana-2-kemiringan-pdf', ['data' => $data]);
}
public function lengkung2Sisi(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_3 = floatval($request->query('sudut_3', 0));
    
    // Data per bagian (dari hasil hitung di modal)
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    $talangJurai1 = floatval($request->query('talang_jurai_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    $talangJurai2 = floatval($request->query('talang_jurai_2', 0));
    
    $luasAtap3 = floatval($request->query('luas_atap_3', 0));
    $starter3 = floatval($request->query('starter_3', 0));
    $flashing3 = floatval($request->query('flashing_3', 0));
    $talangJurai3 = floatval($request->query('talang_jurai_3', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $sudutA = floatval($request->query('sudut_a', 0));
    $tinggi = floatval($request->query('tinggi', 0));
    $panjangC = floatval($request->query('panjang_c', 0));
    $lebarC = floatval($request->query('lebar_c', 0));
    $sudutC = floatval($request->query('sudut_c', 0));
    
    // ===== AMBIL OPSI TAMBAHAN DARI URL =====
    $opsiDinding1 = floatval($request->query('dinding_1', 0));
    $opsiKaca1 = floatval($request->query('kaca_1', 0));
    $opsiPenangkal1 = floatval($request->query('penangkal_1', 0));
    
    $opsiDinding2 = floatval($request->query('dinding_2', 0));
    $opsiKaca2 = floatval($request->query('kaca_2', 0));
    $opsiPenangkal2 = floatval($request->query('penangkal_2', 0));
    
    $opsiDinding3 = floatval($request->query('dinding_3', 0));
    $opsiKaca3 = floatval($request->query('kaca_3', 0));
    $opsiPenangkal3 = floatval($request->query('penangkal_3', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // Underlayer untuk Bagian 1 (Kiri)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    // Underlayer untuk Bagian 2 (Tengah - Lengkung)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    $underlayers_2 = $underlayers_2->get();
    
    // Underlayer untuk Bagian 3 (Kanan)
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_3 <= 15 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 22);
    }
    $underlayers_3 = $underlayers_3->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap3;
    $totalStarter = $starter1 + $starter2 + $starter3;
    $totalNok = 0; // Nok tidak dipakai
    $totalFlashing = $flashing1 + $flashing2 + $flashing3;
    $totalTalangJurai = $talangJurai1 + $talangJurai2 + $talangJurai3;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2; // meter
    $waste = 0.05; // 5%
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    $jumlahTalangJurai = ceil($totalTalangJurai / 1);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.lengkung-2-sisi', compact(
        'brand',
        'products',
        'starters',
        'underlayers_1',
        'underlayers_2',
        'underlayers_3',
        'luasAtap1', 'starter1', 'flashing1', 'talangJurai1',
        'luasAtap2', 'starter2', 'flashing2', 'talangJurai2',
        'luasAtap3', 'starter3', 'flashing3', 'talangJurai3',
        'panjangA', 'lebarA', 'sudutA',
        'tinggi',
        'panjangC', 'lebarC', 'sudutC',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing', 'totalTalangJurai',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing', 'jumlahTalangJurai',
        'ukuranLembaran',
        'sudut_1', 'sudut_3',
        'rangkaOptions', 'lantaiKerjaOptions',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2',
        'opsiDinding3', 'opsiKaca3', 'opsiPenangkal3'
    ));
}
public function exportPdfLengkung2Sisi(Request $request)
{
    $data = $request->all();
    
    // Decode JSON untuk 3 bagian
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }
    
    // Konversi nilai numerik
    $numericFields = ['luas_atap', 'sudut', 'starter', 'flashing', 'tinggi'];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['data_perhitungan'])) {
            foreach ($numericFields as $field) {
                if (isset($data["bagian{$i}"]['data_perhitungan'][$field])) {
                    $data["bagian{$i}"]['data_perhitungan'][$field] = (float) $data["bagian{$i}"]['data_perhitungan'][$field];
                }
            }
        }
        
        if (isset($data["bagian{$i}"]['total']) && is_string($data["bagian{$i}"]['total'])) {
            $data["bagian{$i}"]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data["bagian{$i}"]['total']);
        }
    }
    
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 3; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    // Tampilkan view PDF (langsung di browser)
    return view('boq.atap-kombinasi.lengkung-2-sisi-pdf', ['data' => $data]);
}
public function pelana2Sisi(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_2 = floatval($request->query('sudut_2', 0));
    $sudut_3 = floatval($request->query('sudut_3', 0));
    
    // Data per bagian (dari hasil hitung di modal)
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $nok1 = floatval($request->query('nok_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    $talangJurai1 = floatval($request->query('talang_jurai_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    $talangJurai2 = floatval($request->query('talang_jurai_2', 0));
    
    $luasAtap3 = floatval($request->query('luas_atap_3', 0));
    $starter3 = floatval($request->query('starter_3', 0));
    $flashing3 = floatval($request->query('flashing_3', 0));
    $talangJurai3 = floatval($request->query('talang_jurai_3', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $panjangB = floatval($request->query('panjang_b', 0));
    $lebarB = floatval($request->query('lebar_b', 0));
    $panjangC = floatval($request->query('panjang_c', 0));
    $lebarC = floatval($request->query('lebar_c', 0));
    
    // ===== AMBIL OPSI TAMBAHAN DARI URL =====
    $opsiDinding1 = floatval($request->query('dinding_1', 0));
    $opsiKaca1 = floatval($request->query('kaca_1', 0));
    $opsiPenangkal1 = floatval($request->query('penangkal_1', 0));
    
    $opsiDinding2 = floatval($request->query('dinding_2', 0));
    $opsiKaca2 = floatval($request->query('kaca_2', 0));
    $opsiPenangkal2 = floatval($request->query('penangkal_2', 0));
    
    $opsiDinding3 = floatval($request->query('dinding_3', 0));
    $opsiKaca3 = floatval($request->query('kaca_3', 0));
    $opsiPenangkal3 = floatval($request->query('penangkal_3', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // Underlayer untuk Bagian 1 (Kiri)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    // Underlayer untuk Bagian 2 (Tengah)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 22);
    }
    $underlayers_2 = $underlayers_2->get();
    
    // Underlayer untuk Bagian 3 (Kanan)
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_3 <= 15 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 22);
    }
    $underlayers_3 = $underlayers_3->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap3;
    $totalStarter = $starter1 + $starter2 + $starter3;
    $totalNok = $nok1; // Nok hanya dari bagian kiri
    $totalFlashing = $flashing1 + $flashing2 + $flashing3;
    $totalTalangJurai = $talangJurai1 + $talangJurai2 + $talangJurai3;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2; // meter
    $waste = 0.05; // 5%
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    $jumlahTalangJurai = ceil($totalTalangJurai / 1);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-2-sisi', compact(
        'brand',
        'products',
        'starters',
        'underlayers_1',
        'underlayers_2',
        'underlayers_3',
        'luasAtap1', 'starter1', 'nok1', 'flashing1', 'talangJurai1',
        'luasAtap2', 'starter2', 'flashing2', 'talangJurai2',
        'luasAtap3', 'starter3', 'flashing3', 'talangJurai3',
        'panjangA', 'lebarA',
        'panjangB', 'lebarB',
        'panjangC', 'lebarC',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing', 'totalTalangJurai',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing', 'jumlahTalangJurai',
        'ukuranLembaran',
        'sudut_1', 'sudut_2', 'sudut_3',
        'rangkaOptions', 'lantaiKerjaOptions',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2',
        'opsiDinding3', 'opsiKaca3', 'opsiPenangkal3'
    ));
}
public function pelanaDinding(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('id', '1')->first();

    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }

    // ===== DATA ATAP (dari URL pakai suffix _1) =====
    $luasAtap   = floatval($request->query('luas_atap_1', 0));
    $sudut      = floatval($request->query('sudut_1', 0));
    $starter    = floatval($request->query('starter_1', 0));
    $nokJurai   = floatval($request->query('nok_1', 0));
    $flashing   = floatval($request->query('flashing_1', 0));

    // ===== DATA DINDING (dari URL pakai suffix _2) =====
    $luasDinding = floatval($request->query('luas_atap_2', 0));

    // ===== DATA DIMENSI =====
    $panjang        = floatval($request->query('panjang', 0));
    $lebar          = floatval($request->query('lebar', 0));
    $panjangDinding = floatval($request->query('panjang_dinding', 0));
    $tinggiDinding  = floatval($request->query('tinggi_dinding', 0));
    $jumlahSisi     = intval($request->query('jumlah_sisi', 2));

    // ===== OPSI TAMBAHAN ATAP (BAGIAN 1) =====
    $opsiDinding1   = floatval($request->query('dinding_1', 0));
    $opsiKaca1      = floatval($request->query('kaca_1', 0));
    $opsiPenangkal1 = floatval($request->query('penangkal_1', 0));
    $opsiExhaust1   = floatval($request->query('exhaust_1', 0));

    // ===== OPSI TAMBAHAN DINDING (BAGIAN 2) =====
    $opsiDinding2   = floatval($request->query('dinding_2', 0));
    $opsiKaca2      = floatval($request->query('kaca_2', 0));
    $opsiPenangkal2 = floatval($request->query('penangkal_2', 0));
    $opsiExhaust2   = floatval($request->query('exhaust_2', 0));

    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = collect();
    if ($areaAtapUtama) {
        $products = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaAtapUtama->id)
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->get();
    }

    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = collect();
    if ($areaStarter) {
        $starters = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaStarter->id)
            ->with('unit')
            ->get();
    }

    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $underlayers = collect();
    if ($areaUnderlayer) {
        $underlayersQuery = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaUnderlayer->id)
            ->with('unit');

        if ($sudut <= 15 && $sudut > 0) {
            $underlayersQuery = $underlayersQuery->where('id', 22);
        }
        $underlayers = $underlayersQuery->get();
    }

    // ==================== PRODUK DINDING ====================
    // Dinding pakai produk Atap Utama yang sama
    $productsDinding = $products;

    // ==================== TOTAL ====================
    $totalLuas     = $luasAtap + $luasDinding;
    $totalStarter  = $starter;
    $totalNok      = $nokJurai;
    $totalFlashing = $flashing;

    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2;
    $waste          = 0.05;

    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter  = ceil($totalStarter / 1);
    $jumlahNok      = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);

    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];

    // ==================== LANTAI KERJA OPTIONS ====================
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = collect();
    if ($areaLantaiKerja) {
        $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaLantaiKerja->id)
            ->with('unit')
            ->orderBy('id', 'asc')
            ->get();
    }

    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-dinding', compact(
        'brand',
        'products',
        'starters',
        'underlayers',
        'productsDinding',
        'luasAtap',
        'sudut',
        'starter',
        'nokJurai',
        'flashing',
        'luasDinding',
        'panjang',
        'lebar',
        'panjangDinding',
        'tinggiDinding',
        'jumlahSisi',
        'totalLuas',
        'totalStarter',
        'totalNok',
        'totalFlashing',
        'jumlahLembaran',
        'jumlahStarter',
        'jumlahNok',
        'jumlahFlashing',
        'ukuranLembaran',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1', 'opsiExhaust1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2', 'opsiExhaust2'
    ));
}
public function pelana3Arah(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('id', '1')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_2 = floatval($request->query('sudut_2', 0));
    
    // Data per bagian
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $nok1 = floatval($request->query('nok_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    $talangJurai1 = floatval($request->query('talang_jurai_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $nok2 = floatval($request->query('nok_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    $talangJurai2 = floatval($request->query('talang_jurai_2', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $panjangB = floatval($request->query('panjang_b', 0));
    $lebarB = floatval($request->query('lebar_b', 0));
    
    // ===== AMBIL OPSI TAMBAHAN DARI URL =====
    // Opsi untuk Bagian 1 (Depan)
    $opsiDinding1 = floatval($request->query('dinding_1', 0));
    $opsiKaca1 = floatval($request->query('kaca_1', 0));
    $opsiPenangkal1 = floatval($request->query('penangkal_1', 0));
    
    // Opsi untuk Bagian 2 (Belakang)
    $opsiDinding2 = floatval($request->query('dinding_2', 0));
    $opsiKaca2 = floatval($request->query('kaca_2', 0));
    $opsiPenangkal2 = floatval($request->query('penangkal_2', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 15 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 22);
    }
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 15 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 22);
    }
    $underlayers_2 = $underlayers_2->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap2; // Depan + Belakang + Samping
    $totalStarter = $starter1 + $starter2 + $starter2;
    $totalNok = $nok1 + $nok2 + $nok2;
    $totalFlashing = $flashing1 + $flashing2 + $flashing2;
    $totalTalangJurai = $talangJurai1 + $talangJurai2 + $talangJurai2;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2;
    $waste = 0.05;
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    $jumlahTalangJurai = ceil($totalTalangJurai / 1);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();

    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-3-arah', compact(
        'brand',
        'products',
        'starters',
        'underlayers_1',
        'underlayers_2',
        'luasAtap1', 'starter1', 'nok1', 'flashing1', 'talangJurai1',
        'luasAtap2', 'starter2', 'nok2', 'flashing2', 'talangJurai2',
        'panjangA', 'lebarA',
        'panjangB', 'lebarB',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing', 'totalTalangJurai',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing', 'jumlahTalangJurai',
        'ukuranLembaran',
        'sudut_1', 'sudut_2',
        'rangkaOptions', 'lantaiKerjaOptions',
        'opsiDinding1', 'opsiKaca1', 'opsiPenangkal1',
        'opsiDinding2', 'opsiKaca2', 'opsiPenangkal2'
    ));
}
public function exportPdfPelana3Arah(Request $request)
{
    $data = $request->all();
    
    // Decode JSON untuk 3 bagian
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }
    
    // Konversi nilai numerik - Bagian 1 (Depan)
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'panjang', 'lebar'];
    if (isset($data['bagian1']['data_perhitungan'])) {
        foreach ($numericFields as $field) {
            if (isset($data['bagian1']['data_perhitungan'][$field])) {
                $data['bagian1']['data_perhitungan'][$field] = (float) $data['bagian1']['data_perhitungan'][$field];
            }
        }
    }
    
    // Konversi nilai numerik - Bagian 2 (Belakang)
    if (isset($data['bagian2']['data_perhitungan'])) {
        foreach ($numericFields as $field) {
            if (isset($data['bagian2']['data_perhitungan'][$field])) {
                $data['bagian2']['data_perhitungan'][$field] = (float) $data['bagian2']['data_perhitungan'][$field];
            }
        }
    }
    
    // Konversi total ke integer
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['total']) && is_string($data["bagian{$i}"]['total'])) {
            $data["bagian{$i}"]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data["bagian{$i}"]['total']);
        }
    }
    
    // Grand total = Depan + Belakang + Samping (sama dengan Belakang)
    $total1 = $data['bagian1']['total'] ?? 0;
    $total2 = $data['bagian2']['total'] ?? 0;
    $data['grand_total'] = $total1 + ($total2 * 2);
    
    // ==================== STORE BOQ ====================
    Log::info('========== START STORE BOQ PELANA 3 ARAH ==========');
    
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 3; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                Log::info('Bagian ' . $i . ' - Jumlah item: ' . count($data["bagian{$i}"]['hasil']));
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    // AMBIL PRODUK_ID DARI HASIL (SUDAH ADA DARI FRONTEND)
                    $produkId = $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    Log::info('Produk ID: ' . $produkId . ' - Qty: ' . $qty . ' - Nama: ' . ($item['nama_produk'] ?? ''));
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        Log::info('Total produk unik: ' . count($allProducts));
        Log::info('Data yang akan dikirim:', $allProducts);
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        
        Log::info('Mengirim ke BoqController...');
        $response = $this->boqController->storeBoq($storeRequest);
        Log::info('Response dari BoqController: ' . json_encode($response));
        
    } catch (\Exception $e) {
        Log::error('ERROR store BOQ: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
    }
    
    Log::info('========== END STORE BOQ PELANA 3 ARAH ==========');
     $data['nomor_boq'] = Boq::generateNomorBoq();
    
    // Tampilkan view PDF
    return view('boq.atap-kombinasi.pelana-3-arah-pdf', ['data' => $data]);
}


public function exportPdfPelana2Sisi(Request $request)
{
    $data = $request->all();
    
    // Decode JSON untuk 3 bagian
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }
    
    // Konversi nilai numerik
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'panjang', 'lebar'];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["bagian{$i}"]['data_perhitungan'])) {
            foreach ($numericFields as $field) {
                if (isset($data["bagian{$i}"]['data_perhitungan'][$field])) {
                    $data["bagian{$i}"]['data_perhitungan'][$field] = (float) $data["bagian{$i}"]['data_perhitungan'][$field];
                }
            }
        }
        
        if (isset($data["bagian{$i}"]['total']) && is_string($data["bagian{$i}"]['total'])) {
            $data["bagian{$i}"]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data["bagian{$i}"]['total']);
        }
    }
    
    // Konversi waste
    for ($i = 1; $i <= 3; $i++) {
        if (isset($data["waste_{$i}"])) {
            $data["waste_{$i}"] = (float) $data["waste_{$i}"];
        }
    }
    
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari semua bagian
        for ($i = 1; $i <= 3; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    // Tampilkan view PDF (langsung di browser)
    return view('boq.atap-kombinasi.pelana-2-sisi-pdf', ['data' => $data]);
}
public function exportPdfPelanaDinding(Request $request)
{
    $data = $request->all();
    
    // Decode JSON untuk hasil
    for ($i = 1; $i <= 2; $i++) {
        if (isset($data["bagian{$i}"]['hasil']) && is_string($data["bagian{$i}"]['hasil'])) {
            $data["bagian{$i}"]['hasil'] = json_decode($data["bagian{$i}"]['hasil'], true);
        }
    }
    
    // Konversi nilai numerik
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'panjang', 'lebar'];
    if (isset($data['bagian1']['data_perhitungan'])) {
        foreach ($numericFields as $field) {
            if (isset($data['bagian1']['data_perhitungan'][$field])) {
                $data['bagian1']['data_perhitungan'][$field] = (float) $data['bagian1']['data_perhitungan'][$field];
            }
        }
    }
    
    $numericFieldsDinding = ['luas_dinding', 'wall_flashing', 'panjang_dinding', 'tinggi_dinding', 'jumlah_sisi'];
    if (isset($data['bagian2']['data_perhitungan'])) {
        foreach ($numericFieldsDinding as $field) {
            if (isset($data['bagian2']['data_perhitungan'][$field])) {
                $data['bagian2']['data_perhitungan'][$field] = (float) $data['bagian2']['data_perhitungan'][$field];
            }
        }
    }
    
    for ($i = 1; $i <= 2; $i++) {
        if (isset($data["bagian{$i}"]['total']) && is_string($data["bagian{$i}"]['total'])) {
            $data["bagian{$i}"]['total'] = (int) str_replace(['Rp ', '.', ','], '', $data["bagian{$i}"]['total']);
        }
    }
    
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Gabungkan produk dari bagian 1 (Atap) dan bagian 2 (Dinding)
        for ($i = 1; $i <= 2; $i++) {
            if (isset($data["bagian{$i}"]['hasil'])) {
                foreach ($data["bagian{$i}"]['hasil'] as $item) {
                    $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
                    if ($produkId) {
                        if (!isset($allProducts[$produkId])) {
                            $allProducts[$produkId] = [
                                'produk_id' => $produkId,
                                'qty' => 0
                            ];
                        }
                        $allProducts[$produkId]['qty'] += $qty;
                    }
                }
            }
        }
        
        // Kirim ke BoqController
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }

    return view('boq.atap-kombinasi.pelana-dinding-pdf', ['data' => $data]);
}
public function trapesiumKotak(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $panjang_atas = floatval($request->query('panjang_atas', 0));
    $panjang_bawah = floatval($request->query('panjang_bawah', 0));
    $tinggi = floatval($request->query('tinggi', 0));
    $sudut = floatval($request->query('sudut', 0));
    $luas_atap = floatval($request->query('luas_atap', 0));
    $starter = floatval($request->query('starter', 0));
    $nok_jurai = floatval($request->query('nok_jurai', 0));
    $flashing = floatval($request->query('flashing', 0));
    $brand_slug = $request->query('brand_slug', 'iko-atap');
    
    // ===== AMBIL OPSI TAMBAHAN DARI URL =====
    $opsiDinding = floatval($request->query('dinding', 0));
    $opsiKaca = floatval($request->query('kaca', 0));
    $opsiPenangkal = floatval($request->query('penangkal', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('slug', $brand_slug)->first();
    
    if (!$brand) {
        $brand = ProductBrand::where('nama_brand', 'IKO - ATAP')->first();
    }
    
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    // Filter underlayer berdasarkan sudut
    if ($sudut <= 15 && $sudut > 0) {
        $underlayers = $underlayers->where('id', 22);
    }
    $underlayers = $underlayers->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luas_atap;
    $totalStarter = $starter;
    $totalNok = $nok_jurai;
    $totalFlashing = $flashing;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2;
    $waste = 0.05;
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    // ==================== DATA UNTUK VIEW ====================
    $data = [
        'judul' => 'BOQ - Atap Trapesium Kotak',
        'brand' => $brand->nama_brand,
        'brand_slug' => $brand->slug,
        'panjang_atas' => $panjang_atas,
        'panjang_bawah' => $panjang_bawah,
        'tinggi' => $tinggi,
        'sudut' => $sudut,
        'luas_atap' => $totalLuas,
        'starter' => $totalStarter,
        'nok_jurai' => $totalNok,
        'flashing' => $totalFlashing,
        'jumlah_lembaran' => $jumlahLembaran,
        'jumlah_starter' => $jumlahStarter,
        'jumlah_nok' => $jumlahNok,
        'jumlah_flashing' => $jumlahFlashing,
        'ukuran_lembaran' => $ukuranLembaran,
        'waste' => $waste * 100
    ];
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.trapesium-kotak', compact(
        'brand',
        'products',
        'starters',
        'underlayers',
        'panjang_atas',
        'panjang_bawah',
        'tinggi',
        'sudut',
        'totalLuas',
        'totalStarter',
        'totalNok',
        'totalFlashing',
        'jumlahLembaran',
        'jumlahStarter',
        'jumlahNok',
        'jumlahFlashing',
        'ukuranLembaran',
        'data',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}
public function exportPdfTrapesiumKotak(Request $request)
{
    $data = $request->all();
    
    // ==================== DECODE JSON UNTUK HASIL ====================
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }
    
    // ==================== KONVERSI NILAI NUMERIK ====================
    $numericFields = ['panjang_atas', 'panjang_bawah', 'tinggi', 'sudut', 'luas_atap', 'starter', 'nok_jurai', 'flashing', 'waste'];
    foreach ($numericFields as $field) {
        if (isset($data[$field])) {
            $data[$field] = (float) $data[$field];
        }
    }
    
    // Konversi total jika ada
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }
    
    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    
    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Ambil produk dari hasil
        if (isset($data['hasil']) && is_array($data['hasil'])) {
            foreach ($data['hasil'] as $item) {
                $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId) {
                    if (!isset($allProducts[$produkId])) {
                        $allProducts[$produkId] = [
                            'produk_id' => $produkId,
                            'qty' => 0
                        ];
                    }
                    $allProducts[$produkId]['qty'] += $qty;
                }
            }
        }
        
        // Kirim ke BoqController
        if (!empty($allProducts)) {
            $storeRequest = new \Illuminate\Http\Request([
                'results' => array_values($allProducts)
            ]);
            
            // Tambahkan data tambahan
            $storeRequest->merge([
                'judul' => $data['judul'] ?? 'BOQ - Atap Trapesium Kotak',
                'brand' => $data['brand'] ?? 'IKO - ATAP',
                'nomor_boq' => $nomorBoq,
            ]);
            
            $this->boqController->storeBoq($storeRequest);
        }
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ (Trapesium Kotak): ' . $e->getMessage());
    }
    
    // ==================== TAMPILKAN VIEW PDF ====================
    return view('boq.atap-kombinasi.trapesium-kotak-pdf', ['data' => $data]);
}
}