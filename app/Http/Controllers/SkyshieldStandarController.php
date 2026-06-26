<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SkyshieldStandarController extends Controller
{
    public function index()
    {
        Log::info('=== SkyshieldStandarController: index() ===');
        
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
        
        return view('boq.skyshield-atap', compact(
            'products', 
            'underlayers', 
            'rangkaOptions', 
            'lantaiKerjaOptions'
        ));
    }
    
 public function hitung(Request $request)
{
    Log::info('=== SkyshieldStandarController: hitung() ===');
    
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
    
    // ============ AMBIL BRAND ID ============
    $brand = ProductBrand::where('nama_brand', 'SKYSHIELD')->first();
    
    $results = [];
    $processedProductIds = [];
    
    // 1. Atap Utama + aksesoris
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'area', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        // Hitung Atap Utama
        $qtyRaw = $luasAtap / $produkAtap->satuan_terkecil;
        $qtyAtapUtama = ceil($qtyRaw + ($qtyRaw * $waste));
        $results[] = $this->formatResult($produkAtap, $qtyAtapUtama, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // Loop aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah):', ['nama' => $aksesoris->nama_produk]);
                continue;
            }
            
            // CEK DUPLIKAT
            if (in_array($aksesoris->id, $processedProductIds)) {
                Log::warning('DUPLIKAT DITEMUKAN DI LOOP ACCESSORIES:', [
                    'id' => $aksesoris->id,
                    'nama' => $aksesoris->nama_produk,
                    'area' => $areaName
                ]);
                continue;
            }
            
            // Hitung qty berdasarkan area
            $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, $panjangNokJurai, $panjangFlashing, $panjangTalangJurai, $panjangWallFlashing, $waste, $sudut);
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $areaName, '-');
                $processedProductIds[] = $aksesoris->id;
            }
        }
    }
    
    // ============ 2. UNDERLAYER (DARI DROPDOWN) ============
    Log::info('CEK UNDERLAYER:', [
        'underlayerId' => $underlayerId,
        'processed_ids' => $processedProductIds
    ]);
    
    if ($underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        
        if ($underlayer) {
            Log::info('UNDERLAYER DITEMUKAN:', [
                'id' => $underlayer->id,
                'nama' => $underlayer->nama_produk
            ]);
            
            // CEK APAKAH SUDAH ADA DI PROCESSED
            if (!in_array($underlayer->id, $processedProductIds)) {
                $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
                $processedProductIds[] = $underlayer->id;
                
                Log::info('UNDERLAYER DITAMBAHKAN:', [
                    'id' => $underlayer->id,
                    'qty' => $qty
                ]);
            } else {
                Log::warning('UNDERLAYER SUDAH ADA, DI-SKIP:', [
                    'id' => $underlayer->id
                ]);
            }
        } else {
            Log::warning('UNDERLAYER TIDAK DITEMUKAN:', ['id' => $underlayerId]);
        }
    } else {
        Log::warning('UNDERLAYER ID KOSONG!');
    }
    
    // 3. Talang Jurai
    if ($panjangTalangJurai > 0 && $produkAtapId) {
        $produkAtap = Product::with(['accessories'])->find($produkAtapId);
        
        $talangJurai = $produkAtap->accessories->first(function($aksesoris) {
            return $aksesoris->area->nama_area == 'Talang Jurai';
        });
        
        if ($talangJurai && !in_array($talangJurai->id, $processedProductIds)) {
            $qtyRaw = $panjangTalangJurai / $talangJurai->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($talangJurai, $qty, 'Talang Jurai', $panjangTalangJurai);
            $processedProductIds[] = $talangJurai->id;
        }
    }
    
    // 4. Wall Flashing
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brand->id)
            ->whereHas('area', fn($q) => $q->where('nama_area', 'Wall Flashing'))
            ->first();
            
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            Log::info('Perhitungan Wall Flashing SKYSHIELD:', [
                'panjangWallFlashing' => $panjangWallFlashing,
                'satuan_terkecil' => $wallFlashing->satuan_terkecil,
                'qtyRaw' => $qtyRaw,
                'waste' => $waste,
                'qty_final' => $qty
            ]);
            
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============ LOG AKHIR ============
    Log::info('HASIL AKHIR SKYSHIELD:', [
        'total_items' => count($results),
        'product_ids' => array_column($results, 'id'),
        'areas' => array_column($results, 'area'),
        'processed_ids' => $processedProductIds
    ]);
    
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
                // PERBEDAAN: SKYSHIELD menggunakan koefisien berbeda
                $koefisienNFA = ($sudut >= 15 && $sudut <= 40) ? 350 : 650;
                $variable = (($luasAtap / $koefisienNFA) * 10000) / 275;
                $qtyRaw = $variable / $satuan;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Paku & Screw':
                // PERBEDAAN: SKYSHIELD menggunakan satuan berbeda
                $satuanPaku = ($sudut < 45) ? 22 : 14.5;
                $qtyRaw = $luasAtap / $satuanPaku;
                return ceil($qtyRaw + ($qtyRaw * $waste));
                
            case 'Lem':
                // PERBEDAAN: SKYSHIELD menggunakan perhitungan berbeda
                return ceil(($panjangStarter * 1.2) / $satuan);
                
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
    Log::info('=== EXPORT PDF SKYSHIELD - MULAI ===');
    
    $data = $request->all();
    
    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    
    // ============ STORE BOQ ============
    try {
        $results = $data['results'] ?? [];
        
        Log::info('TOTAL RESULTS: ' . count($results));
        
        // Filter duplikat
        $uniqueResults = [];
        $seenIds = [];
        
        foreach ($results as $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            if (in_array($produkId, $seenIds)) {
                Log::warning("DUPLIKAT SKIP:", ['id' => $produkId]);
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        Log::info('UNIQUE RESULTS:', [
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
                    $produk = Product::find($produkId);
                    
                    DB::table('detail_boq')->insert([
                        'boq_id' => $boq->id,
                        'produk_id' => $produkId,
                        'kode_produk' => $produk ? $produk->kode_produk : null,
                        'qty' => $qty,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            Log::info('BOQ SAVED:', [
                'boq_id' => $boq->id,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error: ' . $e->getMessage());
    }
    
    return view('boq.skyshield-atap-pdf', compact('data'));
}
}