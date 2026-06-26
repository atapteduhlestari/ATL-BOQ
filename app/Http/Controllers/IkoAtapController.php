<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BoqController;

class IkoAtapController extends Controller
{
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
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        return view('boq.iko-atap', compact(
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
             'id' => $product->id,
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
    \Log::info('=== EXPORT PDF IKO ATAP - MULAI ===');
    
    $data = $request->all();
    
    // LOG DATA MENTAH DARI REQUEST
    \Log::info('DATA MENTAH DARI REQUEST:', [
        'data' => $data
    ]);
    
    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    
    // ============ STORE BOQ ============
    try {
        $results = $data['results'] ?? [];
        
        \Log::info('TOTAL RESULTS: ' . count($results));
        
        // Filter duplikat
        $uniqueResults = [];
        $seenIds = [];
        
        foreach ($results as $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            if (in_array($produkId, $seenIds)) {
                \Log::warning("DUPLIKAT SKIP:", ['id' => $produkId]);
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        \Log::info('UNIQUE RESULTS:', [
            'total' => count($uniqueResults),
            'ids' => array_column($uniqueResults, 'id')
        ]);
        
        $data['results'] = $uniqueResults;
        
        // Simpan ke database
        if (!empty($uniqueResults)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            foreach ($uniqueResults as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = \App\Models\Product::find($produkId);
                    
                    \DB::table('detail_boq')->insert([
                        'boq_id' => $boq->id,
                        'produk_id' => $produkId,
                        'kode_produk' => $produk ? $produk->kode_produk : null,
                        'qty' => $qty,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            \Log::info('BOQ SAVED:', [
                'boq_id' => $boq->id,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error: ' . $e->getMessage());
    }
    
    return view('boq.iko-atap-pdf', compact('data'));
}
}