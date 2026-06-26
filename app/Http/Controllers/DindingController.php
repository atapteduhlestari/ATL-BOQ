<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DindingController extends Controller
{
    protected $boqController;

    public function __construct(BoqController $boqController)
    {
        $this->boqController = $boqController;
    }

    public function index()
    {
        Log::info('=== DindingController: index() ===');
        
        return view('dinding.index');
    }

    /**
     * Hitung material dinding eksterior
     */
   public function hitungEksterior(Request $request)
{
    Log::info('=== DindingController: hitungEksterior() ===');
    
    $luasDinding = $request->luas_dinding;
    $waste = $request->waste / 100;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // Ambil brand
    $brand = ProductBrand::where('nama_brand', 'AQUAPANEL')->first();
    
    // Ambil semua produk berdasarkan slug area
    $areaSlugs = ['lem', 'join-dinding', 'cat', 'jaring-penguat', 'pelapis-dinding', 'dinding'];
    $products = [];
    
    foreach ($areaSlugs as $slug) {
        $area = ProductArea::where('slug', $slug)->first();
        if ($area) {
            $product = Product::where('brand_id', $brand->id)
                ->where('area_id', $area->id)
                ->first();
            if ($product) {
                $products[] = $product;
            }
        }
    }
    
    // Ambil paku & screw berdasarkan rangka
    $pakuScrewId = ($rangka == 'Baja Ringan') ? 42 : 43;
    $pakuScrew = Product::find($pakuScrewId);
    if ($pakuScrew) {
        $products[] = $pakuScrew;
    }
    
    $results = [];
    
    // Hitung material dinding
    foreach ($products as $product) {
        $area = $product->area->slug ?? '';
        
        // Dinding: (luas + waste) / satuan_terkecil
        if ($area == 'dinding') {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
        }
        // Paku & Screw: (luas + waste) / satuan_terkecil
        else if ($area == 'paku-dan-screw' || $product->id == $pakuScrewId) {
            $qtyRaw = ($luasDinding * 15) / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        // Lainnya: Luas / Satuan Terkecil (tanpa waste)
        else {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        
        $results[] = [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $product->area->nama_area ?? 'Dinding',
            'input_value' => $luasDinding,
            'satuan_terkecil' => $product->satuan_terkecil,
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_price_list,
            'total_harga' => $qty * $product->harga_price_list
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal,
        'luas_dinding' => $luasDinding
    ]);
}
public function boqEksterior(Request $request)
{
    $luasDinding = $request->query('luas_dinding', 0);
    $waste = $request->query('waste', 5);
    $rangka = $request->query('rangka', 'Baja Ringan');
    
    // Ambil brand AQUAPANEL
    $brand = ProductBrand::where('nama_brand', 'AQUAPANEL')->first();
    
    // Ambil semua produk berdasarkan slug area
    $areaSlugs = ['lem', 'join-dinding', 'cat', 'jaring-penguat', 'pelapis-dinding', 'dinding'];
    $products = [];
    
    foreach ($areaSlugs as $slug) {
        $area = ProductArea::where('slug', $slug)->first();
        if ($area) {
            $product = Product::where('brand_id', $brand->id)
                ->where('area_id', $area->id)
                ->first();
            if ($product) {
                $products[] = $product;
            }
        }
    }
    
    // Ambil paku & screw berdasarkan rangka
    $pakuScrewId = ($rangka == 'Baja Ringan') ? 42 : 43;
    $pakuScrew = Product::find($pakuScrewId);
    if ($pakuScrew) {
        $products[] = $pakuScrew;
    }
    
    $results = [];
    $wasteDecimal = $waste / 100;
    
    foreach ($products as $product) {
        $area = $product->area->slug ?? '';
        
        if ($area == 'dinding' || $product->id == $pakuScrewId) {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $wasteDecimal));
        } else {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        
        $results[] = [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $product->area->nama_area ?? 'Dinding',
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_price_list,
            'total_harga' => $qty * $product->harga_price_list
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    return view('dinding.boq.eksterior', compact(
        'luasDinding', 
        'waste', 
        'rangka',
        'results', 
        'grandTotal'
    ));
}

    /**
     * Hitung material dinding interior
     */
public function hitungInterior(Request $request)
{
    Log::info('=== DindingController: hitungInterior() ===');
    
    $luasDinding = $request->luas_dinding;
    $waste = $request->waste / 100;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // Ambil brand AQUAPANEL - INDOOR
    $brand = ProductBrand::where('nama_brand', 'AQUAPANEL - INDOOR')->first();
    
    if (!$brand) {
        return response()->json([
            'success' => false,
            'message' => 'Brand AQUAPANEL - INDOOR tidak ditemukan'
        ]);
    }
    
    // Ambil semua produk berdasarkan slug area (INTERIOR)
    // lem -> luas / satuan_terkecil
    // cat -> (luas / 0.05) / satuan_terkecil
    // dinding -> (luas + waste) / satuan_terkecil
    $areaSlugs = ['lem', 'cat', 'dinding'];
    $products = [];
    
    foreach ($areaSlugs as $slug) {
        $area = ProductArea::where('slug', $slug)->first();
        if ($area) {
            // PAKAI get() BIAR SEMUA PRODUK DI AREA ITU MASUK
            $productList = Product::where('brand_id', $brand->id)
                ->where('area_id', $area->id)
                ->get();
            
            foreach ($productList as $product) {
                $products[] = $product;
                Log::info('Produk ditemukan untuk area: ' . $slug, [
                    'product_id' => $product->id,
                    'nama_produk' => $product->nama_produk
                ]);
            }
        } else {
            Log::warning('Area tidak ditemukan: ' . $slug);
        }
    }
    
    // Ambil paku & screw berdasarkan rangka
    // Paku & Screw: (luas + waste) / satuan_terkecil
    $pakuScrewId = ($rangka == 'Baja Ringan') ? 42 : 43;
    $pakuScrew = Product::find($pakuScrewId);
    if ($pakuScrew) {
        $products[] = $pakuScrew;
        Log::info('Paku & Screw ditambahkan:', [
            'id' => $pakuScrew->id,
            'nama' => $pakuScrew->nama_produk,
            'rangka' => $rangka
        ]);
    } else {
        Log::warning('Paku & Screw dengan ID ' . $pakuScrewId . ' tidak ditemukan');
    }
    
    $results = [];
    $wasteDecimal = $waste;
    
    foreach ($products as $product) {
        $area = $product->area->slug ?? '';
        
        // Dinding: (luas + waste) / satuan_terkecil
        if ($area == 'dinding') {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $wasteDecimal));
            Log::info('Perhitungan Dinding:', [
                'product' => $product->nama_produk,
                'luas' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty_raw' => $qtyRaw,
                'waste' => $wasteDecimal,
                'qty_final' => $qty
            ]);
        }
        // Paku & Screw: (luas + waste) / satuan_terkecil
        else if ($product->id == $pakuScrewId) {
            $qtyRaw = ($luasDinding*15) / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
            Log::info('Perhitungan Paku & Screw:', [
                'product' => $product->nama_produk,
                'luas' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty_raw' => $qtyRaw,
                'waste' => $wasteDecimal,
                'qty_final' => $qty
            ]);
        }
        // Cat: (luas / 0.05) / satuan_terkecil
        else if ($area == 'cat') {
            $qtyRaw = ($luasDinding / 0.05) / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
            Log::info('Perhitungan Cat:', [
                'product' => $product->nama_produk,
                'luas' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty_raw' => $qtyRaw,
                'qty_final' => $qty
            ]);
        }
        // Lem: luas / satuan_terkecil
        else if ($area == 'lem') {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
            Log::info('Perhitungan Lem:', [
                'product' => $product->nama_produk,
                'luas' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty_raw' => $qtyRaw,
                'qty_final' => $qty
            ]);
        }
        // Default: luas / satuan_terkecil
        else {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
            Log::info('Perhitungan Default:', [
                'product' => $product->nama_produk,
                'area' => $area,
                'luas' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty_raw' => $qtyRaw,
                'qty_final' => $qty
            ]);
        }
        
        $results[] = [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $product->area->nama_area ?? 'Dinding Interior',
            'input_value' => $luasDinding,
            'satuan_terkecil' => $product->satuan_terkecil,
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_price_list,
            'total_harga' => $qty * $product->harga_price_list
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Hasil hitung Interior:', [
        'total_produk' => count($results),
        'grand_total' => $grandTotal
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal,
        'luas_dinding' => $luasDinding
    ]);
}
public function boqInterior(Request $request)
{
    $luasDinding = $request->query('luas_dinding', 0);
    $waste = $request->query('waste', 5);
    $rangka = $request->query('rangka', 'Baja Ringan');
    
    // Ambil brand AQUAPANEL
    $brand = ProductBrand::where('nama_brand', 'AQUAPANEL - INDOOR')->first();
    
    // Ambil semua produk berdasarkan slug area (INTERIOR)
    // lem -> luas / satuan_terkecil
    // cat -> (luas / 0.05) / satuan_terkecil
    // dinding -> (luas + waste) / satuan_terkecil
    $areaSlugs = ['lem', 'cat', 'dinding'];
    $products = [];
    
    foreach ($areaSlugs as $slug) {
        $area = ProductArea::where('slug', $slug)->first();
        if ($area) {
            // PAKAI get() BIAR SEMUA PRODUK DI AREA ITU MASUK
            $productList = Product::where('brand_id', $brand->id)
                ->where('area_id', $area->id)
                ->get();
            
            foreach ($productList as $product) {
                $products[] = $product;
            }
        }
    }
    
    // Ambil paku & screw berdasarkan rangka
    // Paku & Screw: (luas + waste) / satuan_terkecil
    $pakuScrewId = ($rangka == 'Baja Ringan') ? 42 : 43;
    $pakuScrew = Product::find($pakuScrewId);
    if ($pakuScrew) {
        $products[] = $pakuScrew;
    }
    
    $results = [];
    $wasteDecimal = $waste / 100;
    
    foreach ($products as $product) {
        $area = $product->area->slug ?? '';
        
        // Dinding: (luas + waste) / satuan_terkecil
        if ($area == 'dinding') {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $wasteDecimal));
        }
        // Paku & Screw: (luas + waste) / satuan_terkecil
        else if ($product->id == $pakuScrewId) {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $wasteDecimal));
        }
        // Cat: (luas / 0.05) / satuan_terkecil
        else if ($area == 'cat') {
            $qtyRaw = ($luasDinding / 0.05) / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        // Lem: luas / satuan_terkecil
        else if ($area == 'lem') {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        // Default: luas / satuan_terkecil
        else {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
        }
        
        $results[] = [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $product->area->nama_area ?? 'Dinding Interior',
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_price_list,
            'total_harga' => $qty * $product->harga_price_list
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    return view('dinding.boq.interior', compact(
        'luasDinding', 
        'waste', 
        'rangka',
        'results', 
        'grandTotal'
    ));
}

public function boqInsulasi(Request $request)
{
    $luasDinding = $request->query('luas_dinding', 0);
    $waste = $request->query('waste', 5);
    $selectedProductId = $request->query('produk_insulasi', null);

    $brand = ProductBrand::where('nama_brand', 'IKO - INSULASI')->first();
    
    $areaInsulasi = ProductArea::where('slug', 'dinding')->first();
    $productsInsulasi = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaInsulasi->id)
        ->get();
    
    $results = [];
    $grandTotal = 0;
    
    // Jika ada produk yang dipilih, hitung hanya produk itu
    if ($selectedProductId) {
        $product = Product::find($selectedProductId);
        if ($product) {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw);
            
            // Ambil waste dari request
            $wasteDecimal = $waste / 100;
            // Tambahkan waste ke qty
            $qtyWithWaste = ceil($qtyRaw + ($qtyRaw * $wasteDecimal));
            
            $results[] = [
                'product_id' => $product->id,
                'nama_produk' => $product->nama_produk,
                'area' => $product->area->nama_area ?? 'Dinding Insulasi',
                'qty' => $qtyWithWaste,
                'satuan' => $product->unit->unit_name ?? 'pcs',
                'harga_satuan' => $product->harga_price_list,
                'total_harga' => $qtyWithWaste * $product->harga_price_list
            ];
            
            $grandTotal = $results[0]['total_harga'];
        }
    }
    
    return view('dinding.boq.insulasi', compact(
        'luasDinding', 
        'waste',
        'results', 
        'grandTotal',
        'productsInsulasi',
        'selectedProductId'
    ));
}
public function hitungInsulasi(Request $request)
{
    Log::info('=== DindingController: hitungInsulasi() ===');
    
    $luasDinding = $request->luas_dinding;
    $waste = $request->waste / 100;
    $produkInsulasiId = $request->produk_insulasi;
    
    $brand = ProductBrand::where('nama_brand', 'IKO - INSULASI')->first();
    
    if (!$brand) {
        return response()->json([
            'success' => false,
            'message' => 'Brand INSULASI tidak ditemukan'
        ]);
    }
    
    $results = [];
    
    // Ambil produk insulasi yang dipilih dari dropdown
    if ($produkInsulasiId) {
        $product = Product::find($produkInsulasiId);
        
        if ($product) {
            $qtyRaw = $luasDinding / $product->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            $results[] = [
                'product_id' => $product->id,
                'nama_produk' => $product->nama_produk,
                'area' => $product->area->nama_area ?? 'Dinding Insulasi',
                'input_value' => $luasDinding,
                'satuan_terkecil' => $product->satuan_terkecil,
                'qty' => $qty,
                'satuan' => $product->unit->unit_name ?? 'pcs',
                'harga_satuan' => $product->harga_price_list,
                'total_harga' => $qty * $product->harga_price_list
            ];
        }
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal,
        'luas_dinding' => $luasDinding
    ]);
}
    /**
     * Export PDF Dinding
     */
  public function exportPdf(Request $request)
{
    Log::info('=== DindingController: exportPdf() ===');
    
    $data = $request->all();
    
    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }
    
    // Konversi data - PAKAI FOR LOOP
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            if (isset($item['qty'])) {
                $data['hasil'][$i]['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
            }
            if (isset($item['harga_satuan']) && is_string($item['harga_satuan'])) {
                $data['hasil'][$i]['harga_satuan'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
            }
            if (isset($item['total_harga']) && is_string($item['total_harga'])) {
                $data['hasil'][$i]['total_harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['total_harga']);
            }
        }
    }
    
    // Konversi grand total
    if (isset($data['grand_total']) && is_string($data['grand_total'])) {
        $data['grand_total'] = (int) str_replace(['Rp ', '.', ','], '', $data['grand_total']);
    }
    
    // Konversi data perhitungan
    $numericFields = ['panjang', 'lebar', 'tinggi', 'jumlah_sisi', 'luas_dinding', 'luas_pintu', 'luas_jendela', 'waste'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m'], '', $data[$field]);
        }
    }

    // ==================== GENERATE NOMOR BOQ ====================
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    Log::info('NOMOR BOQ GENERATED:', ['nomor_boq' => $nomorBoq]);

    // ==================== STORE BOQ ====================
    try {
        if (!isset($data['hasil']) || empty($data['hasil'])) {
            Log::warning('TIDAK ADA DATA HASIL UNTUK DISIMPAN');
            return view('dinding.boq.dinding-pdf', ['data' => $data]);
        }
        
        // AMBIL SEMUA ID PAKAI array_column
        $allIds = array_column($data['hasil'], 'product_id');
        Log::info('SEMUA ID (array_column):', ['ids' => $allIds]);
        
        // Simpan ke database
        $boq = new Boq();
        $boq->nomor_boq = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();
        
        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
        
        $savedCount = 0;
        $savedIds = [];
        $uniqueResults = [];
        
        // PAKAI FOR LOOP
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $produkId = $item['product_id'] ?? $item['id'] ?? null;
            $qty = (int)($item['qty'] ?? 0);
            
            Log::info("PROSES INDEX {$i}:", [
                'produk_id' => $produkId,
                'qty' => $qty
            ]);
            
            if (!$produkId || $qty <= 0) {
                Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }
            
            // CEK DUPLIKAT
            $isDuplicate = false;
            for ($j = 0; $j < count($savedIds); $j++) {
                if ($savedIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                Log::warning("DUPLIKAT SKIP: produk_id={$produkId}");
                continue;
            }
            
            $produk = Product::find($produkId);
            
            if ($produk) {
                DB::table('detail_boq')->insert([
                    'boq_id' => $boq->id,
                    'produk_id' => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty' => $qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $savedIds[] = $produkId;
                $savedCount++;
                Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}");
                
                $uniqueResults[] = $item;
            } else {
                Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN");
            }
        }
        
        // Update data['hasil'] untuk PDF
        $data['hasil'] = $uniqueResults;
        
        Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id' => $boq->id,
            'nomor_boq' => $nomorBoq,
            'total_produk' => $savedCount,
            'saved_ids' => $savedIds
        ]);
        
    } catch (\Exception $e) {
        Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }
    
    return view('dinding.boq.dinding-pdf', ['data' => $data]);
}
}