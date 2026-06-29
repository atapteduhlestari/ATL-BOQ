<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WaterproofingController extends Controller
{
    protected $boqController;

    public function __construct(BoqController $boqController)
    {
        $this->boqController = $boqController;
    }

    public function index()
    {
        Log::info('=== WaterproofingController: index() ===');
        return view('waterproofing.index');
    }

  public function boq(Request $request)
{
    $luas = $request->query('luas', 0);
    $waste = $request->query('waste', 5);
    $panjangPerimeter = $request->query('panjang_perimeter', 0);
    $tinggiPerimeter = $request->query('tinggi_perimeter', 0);
    $sudut = $request->query('sudut', 0);
    
    // Ambil semua produk waterproofing dari semua brand
    $areaWaterproofing = ProductArea::where('slug', 'waterproofing')->first();
    $products = Product::where('area_id', $areaWaterproofing->id)
        ->with(['unit', 'accessories.unit', 'accessories.area', 'brand'])
        ->get();
    
    // Set default results kosong
    $results = [];
    $grandTotal = 0;
    
    return view('waterproofing.boq.waterproofing', compact(
        'luas', 'waste', 'panjangPerimeter', 'tinggiPerimeter', 'sudut', 'products', 'results', 'grandTotal'
    ));
}

public function hitung(Request $request)
{
    Log::info('=== WaterproofingController: hitung() ===');
    
    $luas = $request->luas;
    $waste = $request->waste / 100;
    $produkId = $request->produk_id;
    $panjangPerimeter = $request->panjang_perimeter ?? 0;
    $tinggiPerimeter = $request->tinggi_perimeter ?? 0;
    $sudut = $request->sudut ?? 0;
    
    // Ambil produk dengan brand-nya
    $produk = Product::with(['unit', 'accessories.unit', 'accessories.area', 'brand'])->find($produkId);
    
    $results = [];
    
    if ($produk) {
        $brandName = $produk->brand->nama_brand ?? '';
        
        // ============================================
        // PRODUK UTAMA - PER BRAND
        // ============================================
        if ($brandName == 'DUO') {
            // DUO: (luas area / 7.268) + ((luas area) / satuan_terkecil) x waste
            $luasPerRoll = $luas / 7.268;
            $luasPerSatuan = $luas / $produk->satuan_terkecil;
            $qtyRaw = $luasPerRoll + ($luasPerSatuan * $waste);
            $qty = ceil($qtyRaw);
        } elseif ($brandName == 'DUO COMPOSITE') {
            // DUO COMPOSITE: ((luas / satuan_terkecil) + ((luas / satuan_terkecil) x waste))
            $luasPerSatuan = $luas / $produk->satuan_terkecil;
            $qtyRaw = $luasPerSatuan + ($luasPerSatuan * $waste);
            $qty = ceil($qtyRaw);
        } else {
            // Brand lain: Default (luas / satuan_terkecil) + waste
            $qtyRaw = $luas / $produk->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
        }
        
        $results[] = [
            'product_id' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'area' => $produk->area->nama_area ?? 'Waterproofing',
            'qty' => $qty,
            'satuan' => $produk->unit->unit_name ?? 'pcs',
            'harga_satuan' => $produk->harga_price_list,
            'total_harga' => $qty * $produk->harga_price_list,
            'brand' => $brandName
        ];
        
        // ============================================
        // AKSESORIS (dari ProductAccessory)
        // ============================================
        foreach ($produk->accessories as $aksesoris) {
            $areaSlug = $aksesoris->area->slug ?? '';
            
            // PERHITUNGAN BERDASARKAN AREA AKSESORIS
            switch ($areaSlug) {
                case 'upstand':
                    // Upstand: (panjang perimeter x (tinggi perimeter/100)) / satuan_terkecil
                    $qtyRaw = ($panjangPerimeter * ($tinggiPerimeter / 100)) / $aksesoris->satuan_terkecil;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    break;
                    
                case 'pelapis-dasar':
                    // Pelapis Dasar: ((luas area + (panjang perimeter x (tinggi perimeter / satuan_terkecil))) / satuan_terkecil) x 18
                    $luasUpstand = $panjangPerimeter * ($tinggiPerimeter / $aksesoris->satuan_terkecil);
                    $qtyRaw = (($luas + $luasUpstand) / $aksesoris->satuan_terkecil) * 18;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    break;
                    
                case 'lapisan':
                    // Lapisan: ((panjang perimeter x (tinggi perimeter/100)) + luas area) / satuan_terkecil
                    $luasUpstand = $panjangPerimeter * ($tinggiPerimeter / 100);
                    $qtyRaw = ($luasUpstand + $luas) / $aksesoris->satuan_terkecil;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    break;
                    
                case 'lantai-kerja':
                    // Lantai Kerja: luas area / (1.3 x 100)
                    $qtyRaw = $luas / (1.3 * 100);
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    break;
                    
                default:
                    // Default: luas / satuan_terkecil
                    $qtyRaw = $luas / $aksesoris->satuan_terkecil;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    break;
            }
            
            $results[] = [
                'product_id' => $aksesoris->id,
                'nama_produk' => $aksesoris->nama_produk,
                'area' => $aksesoris->area->nama_area ?? 'Aksesoris',
                'qty' => $qty,
                'satuan' => $aksesoris->unit->unit_name ?? 'pcs',
                'harga_satuan' => $aksesoris->harga_price_list,
                'total_harga' => $qty * $aksesoris->harga_price_list,
                'brand' => $brandName
            ];
        }
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal,
        'brand' => $brandName ?? null
    ]);
}
   public function exportPdf(Request $request)
{
    Log::info('=== WaterproofingController: exportPdf() ===');
    
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
    
    // Konversi data perhitungan - PAKAI FOR LOOP
    $numericFields = ['luas', 'waste', 'panjang_perimeter', 'tinggi_perimeter', 'sudut'];
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
            return view('waterproofing.boq.waterproofing-pdf', ['data' => $data]);
        }
        
        $boq = new Boq();
        $boq->nomor_boq = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();
        
        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);
        
        $savedCount = 0;
        $savedIds = [];
        $uniqueResults = [];
        
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
            
            // CEK DUPLIKAT PAKAI FOR LOOP
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
                $uniqueResults[] = $item;
                Log::info("BERHASIL SIMPAN: produk_id={$produkId}, qty={$qty}, kode={$produk->kode_produk}");
            } else {
                Log::warning("PRODUK ID {$produkId} TIDAK DITEMUKAN");
            }
        }
        
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
    
    return view('waterproofing.boq.waterproofing-pdf', ['data' => $data]);
}
}