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

class SkyshieldKombinasiController extends Controller
{
     protected $boqController;

    public function __construct(BoqController $boqController)
    {
        $this->boqController = $boqController;
    }
    public function index()
    {
        Log::info('=== SkyShieldController: index() ===');
        
        $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
        
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
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
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

public function limasanTrapesium(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    \Log::info('=== BOQ Limasan Trapesium ===');
    \Log::info('sudut_1 (Limasan): ' . $sudut_1);
    \Log::info('sudut_2 (Trapesium): ' . $sudut_2);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // UNDERLAYER UNTUK LIMASAN (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        \Log::info('Limasan: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 32);
    } else {
        \Log::info('Limasan: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK TRAPESIUM (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        \Log::info('Trapesium: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 32);
    } else {
        \Log::info('Trapesium: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    \Log::info('Jumlah underlayer Limasan: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Trapesium: ' . $underlayers_2->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.limasan-trapesium', compact(
        'products', 'underlayers_1', 'underlayers_2', 'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2'
    ));
}
public function limasPelana(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    
    \Log::info('=== BOQ Limas + Pelana ===');
    \Log::info('sudut_1 (Limas): ' . $sudut_1);
    \Log::info('sudut_2 (Pelana): ' . $sudut_2);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // UNDERLAYER UNTUK LIMAS (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        \Log::info('Limas: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 32);
    } else {
        \Log::info('Limas: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK PELANA (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        \Log::info('Pelana: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 32);
    } else {
        \Log::info('Pelana: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    \Log::info('Jumlah underlayer Limas: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Pelana: ' . $underlayers_2->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.limas-pelana', compact(
        'products', 'underlayers_1', 'underlayers_2', 'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2'
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0); // Pelana
    $sudut_2 = $request->query('sudut_2', 0); // Trapesium A
    $sudut_3 = $request->query('sudut_3', 0); // Trapesium B
    
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
    
    // UNDERLAYER UNTUK PELANA (Bagian 1)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        \Log::info('Pelana: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 32);
    } else {
        \Log::info('Pelana: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK TRAPESIUM A (Bagian 2)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        \Log::info('Trapesium A: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 32);
    } else {
        \Log::info('Trapesium A: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    // UNDERLAYER UNTUK TRAPESIUM B (Bagian 3)
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_3 <= 30 && $sudut_3 > 0) {
        \Log::info('Trapesium B: Sudut ' . $sudut_3 . '° <= 30°, filter underlayer ID 22');
        $underlayers_3 = $underlayers_3->where('id', 32);
    } else {
        \Log::info('Trapesium B: Sudut ' . $sudut_3 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_3 = $underlayers_3->get();
    
    \Log::info('Jumlah underlayer Pelana: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Trapesium A: ' . $underlayers_2->count());
    \Log::info('Jumlah underlayer Trapesium B: ' . $underlayers_3->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.pelana-2trapesium', compact(
        'products', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2', 'sudut_3'
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    // Ambil sudut dari request/URL
    $sudut_1 = $request->query('sudut_1', 0); // Limasan A (Atas)
    $sudut_2 = $request->query('sudut_2', 0); // Limasan B (Bawah)
    
    \Log::info('=== BOQ Limasan + Limasan ===');
    \Log::info('sudut_1 (Limasan A): ' . $sudut_1);
    \Log::info('sudut_2 (Limasan B): ' . $sudut_2);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // UNDERLAYER UNTUK LIMASAN A (Bagian 1 - Atas)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        \Log::info('Limasan A: Sudut ' . $sudut_1 . '° <= 30°, filter underlayer ID 22');
        $underlayers_1 = $underlayers_1->where('id', 32);
    } else {
        \Log::info('Limasan A: Sudut ' . $sudut_1 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_1 = $underlayers_1->get();
    
    // UNDERLAYER UNTUK LIMASAN B (Bagian 2 - Bawah)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        \Log::info('Limasan B: Sudut ' . $sudut_2 . '° <= 30°, filter underlayer ID 22');
        $underlayers_2 = $underlayers_2->where('id', 32);
    } else {
        \Log::info('Limasan B: Sudut ' . $sudut_2 . '° > 30°, tampilkan semua underlayer');
    }
    $underlayers_2 = $underlayers_2->get();
    
    \Log::info('Jumlah underlayer Limasan A: ' . $underlayers_1->count());
    \Log::info('Jumlah underlayer Limasan B: ' . $underlayers_2->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.limasan-limasan', compact(
        'products', 'underlayers_1', 'underlayers_2', 'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2'
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_1 <= 30 && $sudut_1 > 0) $underlayers_1 = $underlayers_1->where('id', 32);
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_2 <= 30 && $sudut_2 > 0) $underlayers_2 = $underlayers_2->where('id', 32);
    $underlayers_2 = $underlayers_2->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_3 <= 30 && $sudut_3 > 0) $underlayers_3 = $underlayers_3->where('id', 32);
    $underlayers_3 = $underlayers_3->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.pelana-x', compact(
        'products', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2', 'sudut_3'
    ));
}
public function exportPdfPelanaX(Request $request)
{
    $data = $request->all();
    
    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }
    
    // Proses data hasil
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        foreach ($data['hasil'] as &$item) {
            if (isset($item['qty'])) {
                $item['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
            }
            if (isset($item['harga'])) {
                $item['harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga']);
            }
            if (isset($item['total'])) {
                $item['total'] = (int) str_replace(['Rp ', '.', ','], '', $item['total']);
            }
        }
    }
    
    // Konversi grand total
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }
    
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

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Ambil produk dari hasil
        if (isset($data['hasil'])) {
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
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.pelana-x-pdf', ['data' => $data]);
}
public function limasanX(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_1);
    $underlayers_2 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_2);
    $underlayers_3 = $this->getFilteredUnderlayers($brand, $areaUnderlayer, $sudut_3);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.limasan-x', compact(
        'products', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2', 'sudut_3'
    ));
}

public function exportPdfLimasanX(Request $request)
{
    $data = $request->all();
    
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }
    
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        foreach ($data['hasil'] as &$item) {
            if (isset($item['qty'])) $item['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
            if (isset($item['harga'])) $item['harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga']);
            if (isset($item['total'])) $item['total'] = (int) str_replace(['Rp ', '.', ','], '', $item['total']);
        }
    }
    
    $numericFields = ['luas_atap', 'sudut', 'starter', 'nok_jurai', 'flashing', 'talang_jurai', 'wall_flashing'];
    foreach ($numericFields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Ambil produk dari hasil
        if (isset($data['hasil'])) {
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
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.limasan-x-pdf', ['data' => $data]);
}

private function getFilteredUnderlayers($brand, $areaUnderlayer, $sudut)
{
    $query = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut <= 30 && $sudut > 0) {
        $query = $query->where('id', 22);
    }
    
    return $query->get();
}
public function gergaji(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut = $request->query('sudut', 0);
    $panjangBangunan = $request->query('panjang_bangunan', 0);
    $lebarBangunan = $request->query('lebar_bangunan', 0);
    $jumlahGerigi = $request->query('jumlah_gerigi', 0);
    $tinggiGerigi = $request->query('tinggi_gerigi', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    if ($sudut <= 30 && $sudut > 0) {
        $underlayers = $underlayers->where('id', 32);
    }
    $underlayers = $underlayers->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.gergaji', compact(
        'products', 'underlayers', 'rangkaOptions', 'lantaiKerjaOptions', 
        'sudut', 'panjangBangunan', 'lebarBangunan', 'jumlahGerigi', 'tinggiGerigi'
    ));
}
public function exportPdfGergaji(Request $request)
{
    $data = $request->all();
    
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }
    
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        foreach ($data['hasil'] as &$item) {
            if (isset($item['qty'])) $item['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
            if (isset($item['harga_satuan']) && is_string($item['harga_satuan'])) $item['harga_satuan'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
            if (isset($item['total_harga']) && is_string($item['total_harga'])) $item['total_harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['total_harga']);
        }
    }
    
    $numericFields = ['luas_atap', 'starter', 'nok_jurai', 'flashing', 'talang_jurai', 'wall_flashing', 'panjang_bangunan', 'lebar_bangunan', 'tinggi_gerigi'];
    foreach ($numericFields as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;

    // ==================== STORE BOQ ====================
    try {
        $allProducts = [];
        
        // Ambil produk dari hasil
        if (isset($data['hasil'])) {
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
        $storeRequest = new \Illuminate\Http\Request([
            'results' => array_values($allProducts)
        ]);
        $this->boqController->storeBoq($storeRequest);
        
    } catch (\Exception $e) {
        \Log::error('Gagal store BOQ: ' . $e->getMessage());
    }
    
    return view('boq.atap-kombinasi.gergaji-pdf', ['data' => $data]);
}
public function pelana2Kemiringan(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = $request->query('sudut_1', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_1 <= 30 && $sudut_1 > 0) $underlayers_1 = $underlayers_1->where('id', 32);
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_2 <= 30 && $sudut_2 > 0) $underlayers_2 = $underlayers_2->where('id', 32);
    $underlayers_2 = $underlayers_2->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)->where('area_id', $areaUnderlayer->id)->with('unit');
    if ($sudut_3 <= 30 && $sudut_3 > 0) $underlayers_3 = $underlayers_3->where('id', 32);
    $underlayers_3 = $underlayers_3->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.atap-kombinasi.pelana-2-kemiringan', compact(
        'products', 'underlayers_1', 'underlayers_2', 'underlayers_3', 
        'rangkaOptions', 'lantaiKerjaOptions', 'sudut_1', 'sudut_2', 'sudut_3'
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_3 = floatval($request->query('sudut_3', 0));
    
    // Data per bagian (dari hasil hitung di modal)
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    
    $luasAtap3 = floatval($request->query('luas_atap_3', 0));
    $starter3 = floatval($request->query('starter_3', 0));
    $flashing3 = floatval($request->query('flashing_3', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $sudutA = floatval($request->query('sudut_a', 0));
    $tinggi = floatval($request->query('tinggi', 0));
    $panjangC = floatval($request->query('panjang_c', 0));
    $lebarC = floatval($request->query('lebar_c', 0));
    $sudutC = floatval($request->query('sudut_c', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // Underlayer untuk Bagian 1 (Kiri)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 32);
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
    if ($sudut_3 <= 30 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 32);
    }
    $underlayers_3 = $underlayers_3->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap3;
    $totalStarter = $starter1 + $starter2 + $starter3;
    $totalNok = 0; // Nok tidak dipakai
    $totalFlashing = $flashing1 + $flashing2 + $flashing3;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2; // meter
    $waste = 0.05; // 5%
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.lengkung-2-sisi', compact(
        'brand',
        'products',
        'underlayers_1',
        'underlayers_2',
        'underlayers_3',
        'luasAtap1', 'starter1', 'flashing1',
        'luasAtap2', 'starter2', 'flashing2',
        'luasAtap3', 'starter3', 'flashing3',
        'panjangA', 'lebarA', 'sudutA',
        'tinggi',
        'panjangC', 'lebarC', 'sudutC',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing',
        'ukuranLembaran',
        'sudut_1', 'sudut_3'
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
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_2 = floatval($request->query('sudut_2', 0));
    $sudut_3 = floatval($request->query('sudut_3', 0));
    
    // Data per bagian (dari hasil hitung di modal)
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $nok1 = floatval($request->query('nok_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    
    $luasAtap3 = floatval($request->query('luas_atap_3', 0));
    $starter3 = floatval($request->query('starter_3', 0));
    $flashing3 = floatval($request->query('flashing_3', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $panjangB = floatval($request->query('panjang_b', 0));
    $lebarB = floatval($request->query('lebar_b', 0));
    $panjangC = floatval($request->query('panjang_c', 0));
    $lebarC = floatval($request->query('lebar_c', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    // Underlayer untuk Bagian 1 (Kiri)
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 32);
    }
    $underlayers_1 = $underlayers_1->get();
    
    // Underlayer untuk Bagian 2 (Tengah)
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 32);
    }
    $underlayers_2 = $underlayers_2->get();
    
    // Underlayer untuk Bagian 3 (Kanan)
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_3 <= 30 && $sudut_3 > 0) {
        $underlayers_3 = $underlayers_3->where('id', 32);
    }
    $underlayers_3 = $underlayers_3->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap3;
    $totalStarter = $starter1 + $starter2 + $starter3;
    $totalNok = $nok1; // Nok hanya dari bagian kiri
    $totalFlashing = $flashing1 + $flashing2 + $flashing3;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2; // meter
    $waste = 0.05; // 5%
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-2-sisi', compact(
        'brand',
        'products',
        'underlayers_1',
        'underlayers_2',
        'underlayers_3',
        'luasAtap1', 'starter1', 'nok1', 'flashing1',
        'luasAtap2', 'starter2', 'flashing2',
        'luasAtap3', 'starter3', 'flashing3',
        'panjangA', 'lebarA',
        'panjangB', 'lebarB',
        'panjangC', 'lebarC',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing',
        'ukuranLembaran',
        'sudut_1', 'sudut_2', 'sudut_3'
    ));
}
public function pelanaDinding(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    // Data Atap
    $luasAtap = floatval($request->query('luas_atap', 0));
    $sudut = floatval($request->query('sudut', 0));
    $starter = floatval($request->query('starter', 0));
    $nokJurai = floatval($request->query('nok_jurai', 0));
    $flashing = floatval($request->query('flashing', 0));
    
    // Data Dinding
    $luasDinding = floatval($request->query('luas_dinding', 0));
    $wallFlashing = floatval($request->query('wall_flashing', 0));
    
    // Data dimensi
    $panjang = floatval($request->query('panjang', 0));
    $lebar = floatval($request->query('lebar', 0));
    $panjangDinding = floatval($request->query('panjang_dinding', 0));
    $tinggiDinding = floatval($request->query('tinggi_dinding', 0));
    $jumlahSisi = intval($request->query('jumlah_sisi', 2));
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut <= 30 && $sudut > 0) {
        $underlayers = $underlayers->where('id', 32);
    }
    $underlayers = $underlayers->get();
    
    // ==================== AMBIL PRODUK DINDING ====================
    // Dinding tetap pakai produk Atap Utama (sama dengan atap)
    $productsDinding = $products;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap + $luasDinding;
    $totalStarter = $starter;
    $totalNok = $nokJurai;
    $totalFlashing = $flashing;
    $totalWallFlashing = $wallFlashing;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2; // meter
    $waste = 0.05; // 5%
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    $jumlahWallFlashing = ceil($totalWallFlashing / 1);
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-dinding', compact(
        'brand',
        'products',
        'underlayers',
        'productsDinding',
        'luasAtap',
        'sudut',
        'starter',
        'nokJurai',
        'flashing',
        'luasDinding',
        'wallFlashing',
        'panjang',
        'lebar',
        'panjangDinding',
        'tinggiDinding',
        'jumlahSisi',
        'totalLuas',
        'totalStarter',
        'totalNok',
        'totalFlashing',
        'totalWallFlashing',
        'jumlahLembaran',
        'jumlahStarter',
        'jumlahNok',
        'jumlahFlashing',
        'jumlahWallFlashing',
        'ukuranLembaran'
    ));
}
public function pelana3Arah(Request $request)
{
    // ==================== AMBIL DATA DARI URL ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $sudut_1 = floatval($request->query('sudut_1', 0));
    $sudut_2 = floatval($request->query('sudut_2', 0));
    
    // Data per bagian
    $luasAtap1 = floatval($request->query('luas_atap_1', 0));
    $starter1 = floatval($request->query('starter_1', 0));
    $nok1 = floatval($request->query('nok_1', 0));
    $flashing1 = floatval($request->query('flashing_1', 0));
    
    $luasAtap2 = floatval($request->query('luas_atap_2', 0));
    $starter2 = floatval($request->query('starter_2', 0));
    $nok2 = floatval($request->query('nok_2', 0));
    $flashing2 = floatval($request->query('flashing_2', 0));
    
    // Data dimensi
    $panjangA = floatval($request->query('panjang_a', 0));
    $lebarA = floatval($request->query('lebar_a', 0));
    $panjangB = floatval($request->query('panjang_b', 0));
    $lebarB = floatval($request->query('lebar_b', 0));
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    if (!$brand) {
        return redirect()->back()->with('error', 'Brand tidak ditemukan');
    }
    
    // ==================== AMBIL PRODUK ATAP UTAMA ====================
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_1 <= 30 && $sudut_1 > 0) {
        $underlayers_1 = $underlayers_1->where('id', 32);
    }
    $underlayers_1 = $underlayers_1->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    if ($sudut_2 <= 30 && $sudut_2 > 0) {
        $underlayers_2 = $underlayers_2->where('id', 32);
    }
    $underlayers_2 = $underlayers_2->get();
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap1 + $luasAtap2 + $luasAtap2; // Depan + Belakang + Samping
    $totalStarter = $starter1 + $starter2 + $starter2;
    $totalNok = $nok1 + $nok2 + $nok2;
    $totalFlashing = $flashing1 + $flashing2 + $flashing2;
    
    // ==================== KEBUTUHAN MATERIAL ====================
    $ukuranLembaran = 1.2;
    $waste = 0.05;
    
    $jumlahLembaran = ceil(($totalLuas / $ukuranLembaran) * (1 + $waste));
    $jumlahStarter = ceil($totalStarter / 1);
    $jumlahNok = ceil($totalNok / 1);
    $jumlahFlashing = ceil($totalFlashing / 1);
    
    // ==================== KIRIM KE VIEW ====================
    return view('boq.atap-kombinasi.pelana-3-arah', compact(
        'brand',
        'products',
        'underlayers_1',
        'underlayers_2',
        'luasAtap1', 'starter1', 'nok1', 'flashing1',
        'luasAtap2', 'starter2', 'nok2', 'flashing2',
        'panjangA', 'lebarA',
        'panjangB', 'lebarB',
        'totalLuas', 'totalStarter', 'totalNok', 'totalFlashing',
        'jumlahLembaran', 'jumlahStarter', 'jumlahNok', 'jumlahFlashing',
        'ukuranLembaran',
        'sudut_1', 'sudut_2'
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
    
    // ==================== AMBIL DATA BRAND ====================
    $brand = ProductBrand::where('slug', $brand_slug)->first();
    
    if (!$brand) {
        $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
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
    
    // ==================== AMBIL UNDERLAYER ====================
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit');
    
    // Filter underlayer berdasarkan sudut
    if ($sudut <= 30 && $sudut > 0) {
        // Cari underlayer khusus untuk sudut <= 30 derajat (misal id 22)
        $underlayers = $underlayers->where('id', 32);
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
        'data'
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