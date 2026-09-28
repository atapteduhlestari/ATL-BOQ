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

    private function ceil2($value)
{
    return ceil($value * 100) / 100;
}

    public function __construct(BoqController $boqController)
    {
        $this->boqController = $boqController;
    }

    public function index()
    {
       $brands = ProductBrand::where('mapping_id', 2)->get();
     $brandRouteMap = [
        'duo'     => 'waterproofing.boq.duo',
        'sagitta' => 'waterproofing.boq.sagitta',
        'soprasun' => 'waterproofing.boq.soprasun',
        'polygum' => 'waterproofing.boq.polygum',
        // tambahkan brand lain di sini
    ];

    return view('waterproofing.index', compact('brands', 'brandRouteMap'));
    }


     // ====== BOQ DUO ======
   public function boqSoprasun(Request $request)
{
    $luas            = (float) $request->get('luas', 0);
    $waste           = (float) $request->get('waste', 5);
    $panjangPerimeter= (float) $request->get('panjang_perimeter', 0);
    $tinggiPerimeter = (float) $request->get('tinggi_perimeter', 0);
    $sudut           = (float) $request->get('sudut', 0);
    $brandId         = $request->get('brand_id');

    // Filter produk hanya untuk brand Duo
    $products = Product::where('brand_id', $brandId)
    ->where('tipe_produk', 'main')
    ->get();

    // Default results kosong, akan diisi via AJAX
    $results = [];

    return view('waterproofing.boq.soprasun', compact(
        'products',
        'results',
        'luas',
        'waste',
        'panjangPerimeter',
        'tinggiPerimeter',
        'sudut',
        'brandId'
    ));
}

public function hitungSoprasun(Request $request)
{
    Log::info('=== hitungSoprasun() ===');

    // ===== INPUT =====
    $luas             = (float) $request->input('luas', 0);
    $waste            = (float) $request->input('waste', 5) / 100;
    $panjangPerimeter = (float) $request->input('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->input('tinggi_perimeter', 0); // cm
    $sudut            = (float) $request->input('sudut', 0);
    $produkId         = $request->input('produk_id');
    $brandId          = $request->input('brand_id');

    // ===== VALIDASI =====
    if (!$produkId) {
        return response()->json(['success' => false, 'message' => 'Produk belum dipilih'], 400);
    }

    // ===== AMBIL PRODUK + RELASI =====
    $produk = Product::with(['unit', 'area'])->find($produkId);
    if (!$produk) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    $satuanTerkecil = $produk->satuan_terkecil ?: 1;
    $tinggiM        = $tinggiPerimeter / 100; // cm → m

    $results = [];
    $processedProductIds = [];

    // ============================================================
    // 1. WATERPROOFING (area utama)
    // ============================================================
    $qtyRaw = $luas / $satuanTerkecil;
    $qtyWaterproofing = $this->ceil2($qtyRaw + ($qtyRaw * $waste));

    $results[] = $this->formatResult($produk, $qtyWaterproofing, 'Waterproofing', $luas . ' m²');
    $processedProductIds[] = $produk->id;

    // ============================================================
    // 2. UPSTAND
    // ============================================================
    if ($panjangPerimeter > 0 && $tinggiPerimeter > 0) {
        // Cari produk upstand di brand yang sama (via relasi area)
        $produkUpstand = Product::with(['unit', 'area'])
            ->where('brand_id', $produk->brand_id)
            ->whereHas('area', function ($q) {
                $q->where('nama_area', 'Upstand');
            })
            ->first();

        // Kalau tidak ada produk upstand terpisah, pakai produk yang dipilih
        if (!$produkUpstand) {
            $produkUpstand = $produk;
        }

        $luasUpstand = $panjangPerimeter * $tinggiM;
        $satuanUpstand = $produkUpstand->satuan_terkecil ?: 1;

        $qtyRawUpstand = $luasUpstand / $satuanUpstand;
        $qtyUpstand    = $this->ceil2($qtyRawUpstand + ($qtyRawUpstand * $waste));

        if ($qtyUpstand > 0) {
            $results[] = $this->formatResult(
                $produkUpstand,
                $qtyUpstand,
                'Upstand',
                number_format($luasUpstand, 2) . ' m²'
            );
            $processedProductIds[] = $produkUpstand->id;
        }
    }

    // ============================================================
    // 3. PELAPIS DASAR (masuk ke Aksesoris)
    // ============================================================
    $produkPelapis = Product::with(['unit', 'area'])
        ->where('brand_id', $produk->brand_id)
        ->whereHas('area', function ($q) {
            $q->where('nama_area', 'Pelapis Dasar');
        })
        ->first();

    if ($produkPelapis) {
        $satuanPelapis = $produkPelapis->satuan_terkecil ?: 1;

        // Rumus: ((luas + (panjang × tinggi / satuan_terkecil)) / satuan_terkecil) × 18
        $qtyPelapis = (
            ($luas + ($panjangPerimeter * ($tinggiPerimeter / $satuanPelapis)))
            / $satuanPelapis
        ) * 18;

        // Apply waste
        $qtyPelapis = ceil($qtyPelapis + ($qtyPelapis * $waste));

        if ($qtyPelapis > 0) {
            $results[] = $this->formatResult(
                $produkPelapis,
                $qtyPelapis,
                'Pelapis Dasar',
                $luas . ' m²'
            );
            $processedProductIds[] = $produkPelapis->id;
        }
    } else {
        Log::warning('Produk Pelapis Dasar tidak ditemukan untuk brand_id: ' . $produk->brand_id);
    }

    // ============================================================
    // RETURN
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');

    Log::info('HASIL AKHIR SOPRASUN:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
    ]);

    return response()->json([
        'success'     => true,
        'results'     => $results,
        'grand_total' => $grandTotal,
    ]);
}

public function exportPdfSoprasun(Request $request)
{
    Log::info('=== WaterproofingController: exportPdfSoprasun() ===');

    $data = $request->all();

    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }

    // Konversi qty, harga_satuan, total_harga
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            if (isset($item['qty'])) {
                $data['hasil'][$i]['qty'] = (float) str_replace(['.', ','], '.', $item['qty']);
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

    // Konversi field numerik
    $numericFields = ['luas', 'waste', 'panjang_perimeter', 'tinggi_perimeter', 'sudut'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m', ' cm', ' °'], '', $data[$field]);
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
            return view('waterproofing.boq.soprasun-pdf', ['data' => $data]);
        }

        $boq = new Boq();
        $boq->nomor_boq   = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();

        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);

        $savedCount    = 0;
        $savedIds      = [];
        $uniqueResults = [];

        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item     = $items[$i];
            $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
            $qty      = (float) ($item['qty'] ?? 0);

            if (!$produkId || $qty <= 0) {
                Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }

            // Cek duplikat
            $isDuplicate = false;
            for ($j = 0; $j < count($savedIds); $j++) {
                if ($savedIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue;

            $produk = Product::find($produkId);
            if ($produk) {
                DB::table('detail_boq')->insert([
                    'boq_id'      => $boq->id,
                    'produk_id'   => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty'         => $qty,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $savedIds[]      = $produkId;
                $savedCount++;
                $uniqueResults[] = $item;
            }
        }

        $data['hasil'] = $uniqueResults;

        Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id'        => $boq->id,
            'nomor_boq'     => $nomorBoq,
            'total_produk'  => $savedCount,
        ]);

    } catch (\Exception $e) {
        Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }

    return view('waterproofing.boq.pdf-soprasun', ['data' => $data]);
}

     // ====== BOQ DUO ======
   public function boqPolygum(Request $request)
{
    $luas            = (float) $request->get('luas', 0);
    $waste           = (float) $request->get('waste', 5);
    $panjangPerimeter= (float) $request->get('panjang_perimeter', 0);
    $tinggiPerimeter = (float) $request->get('tinggi_perimeter', 0);
    $sudut           = (float) $request->get('sudut', 0);
    $brandId         = $request->get('brand_id');

    // Filter produk hanya untuk brand Duo
    $products = Product::where('brand_id', $brandId)
    ->where('tipe_produk', 'main')
    ->get();

    // Default results kosong, akan diisi via AJAX
    $results = [];

    return view('waterproofing.boq.polygum', compact(
        'products',
        'results',
        'luas',
        'waste',
        'panjangPerimeter',
        'tinggiPerimeter',
        'sudut',
        'brandId'
    ));
}

public function hitungPolygum(Request $request)
{
    Log::info('=== hitungSoprasun() ===');

    // ===== INPUT =====
    $luas             = (float) $request->input('luas', 0);
    $waste            = (float) $request->input('waste', 5) / 100;
    $panjangPerimeter = (float) $request->input('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->input('tinggi_perimeter', 0); // cm
    $sudut            = (float) $request->input('sudut', 0);
    $produkId         = $request->input('produk_id');
    $brandId          = $request->input('brand_id');

    // ===== VALIDASI =====
    if (!$produkId) {
        return response()->json(['success' => false, 'message' => 'Produk belum dipilih'], 400);
    }

    // ===== AMBIL PRODUK + RELASI =====
    $produk = Product::with(['unit', 'area'])->find($produkId);
    if (!$produk) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    $satuanTerkecil = $produk->satuan_terkecil ?: 1;
    $tinggiM        = $tinggiPerimeter / 100; // cm → m

    $results = [];
    $processedProductIds = [];

    // ============================================================
    // 1. WATERPROOFING (area utama)
    // ============================================================
    $qtyRaw = $luas / $satuanTerkecil;
    $qtyWaterproofing = $this->ceil2($qtyRaw + ($qtyRaw * $waste));

    $results[] = $this->formatResult($produk, $qtyWaterproofing, 'Waterproofing', $luas . ' m²');
    $processedProductIds[] = $produk->id;

    // ============================================================
    // 2. UPSTAND
    // ============================================================
    if ($panjangPerimeter > 0 && $tinggiPerimeter > 0) {
        // Cari produk upstand di brand yang sama (via relasi area)
        $produkUpstand = Product::with(['unit', 'area'])
            ->where('brand_id', $produk->brand_id)
            ->whereHas('area', function ($q) {
                $q->where('nama_area', 'Upstand');
            })
            ->first();

        // Kalau tidak ada produk upstand terpisah, pakai produk yang dipilih
        if (!$produkUpstand) {
            $produkUpstand = $produk;
        }

        $luasUpstand = $panjangPerimeter * $tinggiM;
        $satuanUpstand = $produkUpstand->satuan_terkecil ?: 1;

        $qtyRawUpstand = $luasUpstand / $satuanUpstand;
        $qtyUpstand    = $this->ceil2($qtyRawUpstand + ($qtyRawUpstand * $waste));

        if ($qtyUpstand > 0) {
            $results[] = $this->formatResult(
                $produkUpstand,
                $qtyUpstand,
                'Upstand',
                number_format($luasUpstand, 2) . ' m²'
            );
            $processedProductIds[] = $produkUpstand->id;
        }
    }

    // ============================================================
    // 3. PELAPIS DASAR (masuk ke Aksesoris)
    // ============================================================
    $produkPelapis = Product::with(['unit', 'area'])
        ->where('brand_id', $produk->brand_id)
        ->whereHas('area', function ($q) {
            $q->where('nama_area', 'Pelapis Dasar');
        })
        ->first();

    if ($produkPelapis) {
        $satuanPelapis = $produkPelapis->satuan_terkecil ?: 1;

        // Rumus: ((luas + (panjang × tinggi / satuan_terkecil)) / satuan_terkecil) × 18
        $qtyPelapis = (
            ($luas + ($panjangPerimeter * ($tinggiPerimeter / $satuanPelapis)))
            / $satuanPelapis
        ) * 18;

        // Apply waste
        $qtyPelapis = ceil($qtyPelapis + ($qtyPelapis * $waste));

        if ($qtyPelapis > 0) {
            $results[] = $this->formatResult(
                $produkPelapis,
                $qtyPelapis,
                'Pelapis Dasar',
                $luas . ' m²'
            );
            $processedProductIds[] = $produkPelapis->id;
        }
    } else {
        Log::warning('Produk Pelapis Dasar tidak ditemukan untuk brand_id: ' . $produk->brand_id);
    }

    // ============================================================
    // RETURN
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');

    Log::info('HASIL AKHIR SOPRASUN:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
    ]);

    return response()->json([
        'success'     => true,
        'results'     => $results,
        'grand_total' => $grandTotal,
    ]);
}

public function exportPdfPolygum(Request $request)
{
    Log::info('=== WaterproofingController: exportPdfSoprasun() ===');

    $data = $request->all();

    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }

    // Konversi qty, harga_satuan, total_harga
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            if (isset($item['qty'])) {
                $data['hasil'][$i]['qty'] = (float) str_replace(['.', ','], '.', $item['qty']);
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

    // Konversi field numerik
    $numericFields = ['luas', 'waste', 'panjang_perimeter', 'tinggi_perimeter', 'sudut'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m', ' cm', ' °'], '', $data[$field]);
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
            return view('waterproofing.boq.soprasun-pdf', ['data' => $data]);
        }

        $boq = new Boq();
        $boq->nomor_boq   = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();

        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);

        $savedCount    = 0;
        $savedIds      = [];
        $uniqueResults = [];

        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item     = $items[$i];
            $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
            $qty      = (float) ($item['qty'] ?? 0);

            if (!$produkId || $qty <= 0) {
                Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }

            // Cek duplikat
            $isDuplicate = false;
            for ($j = 0; $j < count($savedIds); $j++) {
                if ($savedIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue;

            $produk = Product::find($produkId);
            if ($produk) {
                DB::table('detail_boq')->insert([
                    'boq_id'      => $boq->id,
                    'produk_id'   => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty'         => $qty,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $savedIds[]      = $produkId;
                $savedCount++;
                $uniqueResults[] = $item;
            }
        }

        $data['hasil'] = $uniqueResults;

        Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id'        => $boq->id,
            'nomor_boq'     => $nomorBoq,
            'total_produk'  => $savedCount,
        ]);

    } catch (\Exception $e) {
        Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }

    return view('waterproofing.boq.pdf-polygum', ['data' => $data]);
}

public function boqSagitta(Request $request)
{
    $luas             = (float) $request->get('luas', 0);
    $waste            = (float) $request->get('waste', 5);
    $panjangPerimeter = (float) $request->get('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->get('tinggi_perimeter', 0);
    $sudut            = (float) $request->get('sudut', 0);
    $brandId          = $request->get('brand_id');

    $brand = ProductBrand::where('id', '11')->first();

    // Filter produk: HANYA yang area-nya "Waterproofing"
    $areaUtama = ProductArea::where('nama_area', 'Waterproofing')->first();

    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();

    $results = [];

    return view('waterproofing.boq.sagitta', compact(
        'products',
        'results',
        'luas',
        'waste',
        'panjangPerimeter',
        'tinggiPerimeter',
        'sudut',
        'brandId'
    ));
}

public function hitungSagitta(Request $request)
{
    Log::info('=== hitungSagitta() ===');

    // ===== INPUT =====
    $luas             = (float) $request->input('luas', 0);
    $waste            = (float) $request->input('waste', 5) / 100;
    $panjangPerimeter = (float) $request->input('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->input('tinggi_perimeter', 0); // cm
    $sudut            = (float) $request->input('sudut', 0);
    $produkId         = $request->input('produk_id');
    $brandId          = $request->input('brand_id');

    // ===== VALIDASI =====
    if (!$produkId) {
        return response()->json(['success' => false, 'message' => 'Produk belum dipilih'], 400);
    }

    // ===== AMBIL PRODUK INDUK + AKSESORIS (PIVOT) =====
    $produk = Product::with([
        'unit',
        'area',
        'accessories.unit',
        'accessories.area'
    ])->find($produkId);

    if (!$produk) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    $satuanTerkecil = $produk->satuan_terkecil ?: 1;
    $tinggiM        = $tinggiPerimeter / 100;

    $results = [];
    $processedProductIds = [];

    // ============================================================
    // 1. WATERPROOFING (area utama)
    // ============================================================
    $qtyRaw = $luas / $satuanTerkecil;
    $qtyWaterproofing = $this->ceil2($qtyRaw + ($qtyRaw * $waste));

    $results[] = $this->formatResult($produk, $qtyWaterproofing, 'Waterproofing', $luas . ' m²');
    $processedProductIds[] = $produk->id;

   // ============================================================
// 2. UPSTAND
// ============================================================
if ($panjangPerimeter > 0 && $tinggiPerimeter > 0) {

    $produkUpstand = Product::whereHas('area', function ($q) {
            $q->whereRaw('LOWER(nama_area) = ?', ['upstand']);
        })
        ->whereIn('id', function ($q) use ($produk) {
            $q->select('accessory_id')
              ->from('product_accessories')
              ->where('parent_product_id', $produk->id);
        })
        ->first();

    if (!$produkUpstand) {
        $produkUpstand = $produk;
    }

    $luasUpstand   = $panjangPerimeter * $tinggiM;
    $satuanUpstand = $produkUpstand->satuan_terkecil ?: 1;

    $qtyRawUpstand = $luasUpstand / $satuanUpstand;
    $qtyUpstand    = $this->ceil2($qtyRawUpstand + ($qtyRawUpstand * $waste));

    if ($qtyUpstand > 0 && !in_array($produkUpstand->id, $processedProductIds)) {
        $results[] = $this->formatResult(
            $produkUpstand,
            $qtyUpstand,
            'Upstand',
            number_format($luasUpstand, 2) . ' m²'
        );
        $processedProductIds[] = $produkUpstand->id;
    }
}
    // ============================================================
    // 3. PELAPIS DASAR (dari aksesoris pivot)
    // ============================================================
    $produkPelapis = $produk->accessories->first(function ($acc) {
        return $acc->area && strtolower($acc->area->nama_area) === 'pelapis dasar';
    });

    if ($produkPelapis && !in_array($produkPelapis->id, $processedProductIds)) {
        $satuanPelapis = $produkPelapis->satuan_terkecil ?: 1;

        // Rumus: ((luas + (panjang × tinggi / satuan)) / satuan) × 18
        $qtyPelapis = (
            ($luas + ($panjangPerimeter * ($tinggiPerimeter / $satuanPelapis)))
            / $satuanPelapis
        ) * 18;

        $qtyPelapis = $this->ceil2($qtyPelapis + ($qtyPelapis * $waste));

        if ($qtyPelapis > 0) {
            $results[] = $this->formatResult(
                $produkPelapis,
                $qtyPelapis,
                'Pelapis Dasar',
                $luas . ' m²'
            );
            $processedProductIds[] = $produkPelapis->id;
        }
    } else {
        Log::warning('Produk Pelapis Dasar tidak ditemukan untuk produk_id: ' . $produk->id);
    }

  // ============================================================
// 4. AKSESORIS - AREA ID 8
// ============================================================
$produkArea8 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 8;
});

if ($produkArea8 && !in_array($produkArea8->id, $processedProductIds)) {
    $satuanArea8 = $produkArea8->satuan_terkecil ?: 1;

    // Rumus: panjang perimeter / satuan_terkecil
    $qtyArea8 = $panjangPerimeter / $satuanArea8;
    $qtyArea8 = $this->ceil2($qtyArea8 + ($qtyArea8 * $waste));

    if ($qtyArea8 > 0) {
        $results[] = $this->formatResult(
            $produkArea8,
            $qtyArea8,
            $produkArea8->area->nama_area,
            $panjangPerimeter . ' m'
        );
        $processedProductIds[] = $produkArea8->id;
    }
}


// ============================================================
// 5. AKSESORIS - AREA ID 89
// ============================================================
$produkArea89 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 89;
});

if ($produkArea89 && !in_array($produkArea89->id, $processedProductIds)) {
    $satuanArea89 = $produkArea89->satuan_terkecil ?: 1;

    // Rumus: panjang perimeter / satuan_terkecil
    $qtyArea89 = $panjangPerimeter / $satuanArea89;
    $qtyArea89 = $this->ceil2($qtyArea89 + ($qtyArea89 * $waste));

    if ($qtyArea89 > 0) {
        $results[] = $this->formatResult(
            $produkArea89,
            $qtyArea89,
            $produkArea89->area->nama_area,
            $panjangPerimeter . ' m'
        );
        $processedProductIds[] = $produkArea89->id;
    }
}


// ============================================================
// 6. AKSESORIS - AREA ID 7
// ============================================================
$produkArea7 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 7;
});

if ($produkArea7 && !in_array($produkArea7->id, $processedProductIds)) {
    $satuanArea7 = $produkArea7->satuan_terkecil ?: 1;

    // Rumus: qty id 89 × satuan_terkecil
    $qtyArea7 = $qtyArea89 * $satuanArea7;
    $qtyArea7 = $this->ceil2($qtyArea7 + ($qtyArea7 * $waste));

    if ($qtyArea7 > 0) {
        $results[] = $this->formatResult(
            $produkArea7,
            $qtyArea7,
            $produkArea7->area->nama_area,
            $luas . ' m²'
        );
        $processedProductIds[] = $produkArea7->id;
    }
}

    // ============================================================
    // RETURN
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');

    Log::info('HASIL AKHIR SAGITTA:', [
        'produk_id'   => $produk->id,
        'produk_nama' => $produk->nama_produk,
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'aksesoris'   => $produk->accessories->pluck('nama_produk')->toArray(),
    ]);

    return response()->json([
        'success'     => true,
        'results'     => $results,
        'grand_total' => $grandTotal,
    ]);
}

public function exportPdfSagitta(Request $request)
{
    Log::info('=== WaterproofingController: exportPdfSoprasun() ===');

    $data = $request->all();

    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }

    // Konversi qty, harga_satuan, total_harga
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            if (isset($item['qty'])) {
                $data['hasil'][$i]['qty'] = (float) str_replace(['.', ','], '.', $item['qty']);
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

    // Konversi field numerik
    $numericFields = ['luas', 'waste', 'panjang_perimeter', 'tinggi_perimeter', 'sudut'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m', ' cm', ' °'], '', $data[$field]);
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
            return view('waterproofing.boq.soprasun-pdf', ['data' => $data]);
        }

        $boq = new Boq();
        $boq->nomor_boq   = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();

        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);

        $savedCount    = 0;
        $savedIds      = [];
        $uniqueResults = [];

        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item     = $items[$i];
            $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
            $qty      = (float) ($item['qty'] ?? 0);

            if (!$produkId || $qty <= 0) {
                Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }

            // Cek duplikat
            $isDuplicate = false;
            for ($j = 0; $j < count($savedIds); $j++) {
                if ($savedIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue;

            $produk = Product::find($produkId);
            if ($produk) {
                DB::table('detail_boq')->insert([
                    'boq_id'      => $boq->id,
                    'produk_id'   => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty'         => $qty,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $savedIds[]      = $produkId;
                $savedCount++;
                $uniqueResults[] = $item;
            }
        }

        $data['hasil'] = $uniqueResults;

        Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id'        => $boq->id,
            'nomor_boq'     => $nomorBoq,
            'total_produk'  => $savedCount,
        ]);

    } catch (\Exception $e) {
        Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }

    return view('waterproofing.boq.pdf-sagitta', ['data' => $data]);
}

public function boqDuo(Request $request)
{
    $luas             = (float) $request->get('luas', 0);
    $waste            = (float) $request->get('waste', 5);
    $panjangPerimeter = (float) $request->get('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->get('tinggi_perimeter', 0);
    $sudut            = (float) $request->get('sudut', 0);
    $brandId          = $request->get('brand_id');

    $brand = ProductBrand::where('id', '10')->first();

    // Filter produk: HANYA yang area-nya "Waterproofing"
    $areaUtama = ProductArea::where('nama_area', 'Waterproofing')->first();

    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();

    $results = [];

    return view('waterproofing.boq.duo', compact(
        'products',
        'results',
        'luas',
        'waste',
        'panjangPerimeter',
        'tinggiPerimeter',
        'sudut',
        'brandId'
    ));
}

public function hitungDuo(Request $request)
{
    Log::info('=== hitungSagitta() ===');

    // ===== INPUT =====
    $luas             = (float) $request->input('luas', 0);
    $waste            = (float) $request->input('waste', 5) / 100;
    $panjangPerimeter = (float) $request->input('panjang_perimeter', 0);
    $tinggiPerimeter  = (float) $request->input('tinggi_perimeter', 0); // cm
    $sudut            = (float) $request->input('sudut', 0);
    $produkId         = $request->input('produk_id');
    $brandId          = $request->input('brand_id');

    // ===== VALIDASI =====
    if (!$produkId) {
        return response()->json(['success' => false, 'message' => 'Produk belum dipilih'], 400);
    }

    // ===== AMBIL PRODUK INDUK + AKSESORIS (PIVOT) =====
    $produk = Product::with([
        'unit',
        'area',
        'accessories.unit',
        'accessories.area'
    ])->find($produkId);

    if (!$produk) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    $satuanTerkecil = $produk->satuan_terkecil ?: 1;
    $tinggiM        = $tinggiPerimeter / 100;

    $results = [];
    $processedProductIds = [];

    // ============================================================
    // 1. WATERPROOFING (area utama)
    // ============================================================
    $qtyRaw = $luas / $satuanTerkecil;
    $qtyWaterproofing = $this->ceil2($qtyRaw + ($qtyRaw * $waste));

    $results[] = $this->formatResult($produk, $qtyWaterproofing, 'Waterproofing', $luas . ' m²');
    $processedProductIds[] = $produk->id;

   // ============================================================
// 2. UPSTAND
// ============================================================
if ($panjangPerimeter > 0 && $tinggiPerimeter > 0) {

    $produkUpstand = Product::whereHas('area', function ($q) {
            $q->whereRaw('LOWER(nama_area) = ?', ['upstand']);
        })
        ->whereIn('id', function ($q) use ($produk) {
            $q->select('accessory_id')
              ->from('product_accessories')
              ->where('parent_product_id', $produk->id);
        })
        ->first();

    if (!$produkUpstand) {
        $produkUpstand = $produk;
    }

    $luasUpstand   = $panjangPerimeter * $tinggiM;
    $satuanUpstand = $produkUpstand->satuan_terkecil ?: 1;

    $qtyRawUpstand = $luasUpstand / $satuanUpstand;
    $qtyUpstand    = $this->ceil2($qtyRawUpstand + ($qtyRawUpstand * $waste));

    if ($qtyUpstand > 0 && !in_array($produkUpstand->id, $processedProductIds)) {
        $results[] = $this->formatResult(
            $produkUpstand,
            $qtyUpstand,
            'Upstand',
            number_format($luasUpstand, 2) . ' m²'
        );
        $processedProductIds[] = $produkUpstand->id;
    }
}
    // ============================================================
    // 3. PELAPIS DASAR (dari aksesoris pivot)
    // ============================================================
    $produkPelapis = $produk->accessories->first(function ($acc) {
        return $acc->area && strtolower($acc->area->nama_area) === 'pelapis dasar';
    });

    if ($produkPelapis && !in_array($produkPelapis->id, $processedProductIds)) {
        $satuanPelapis = $produkPelapis->satuan_terkecil ?: 1;

        // Rumus: ((luas + (panjang × tinggi / satuan)) / satuan) × 18
        $qtyPelapis = (
            ($luas + ($panjangPerimeter * ($tinggiPerimeter / $satuanPelapis)))
            / $satuanPelapis
        ) * 18;

        $qtyPelapis = $this->ceil2($qtyPelapis + ($qtyPelapis * $waste));

        if ($qtyPelapis > 0) {
            $results[] = $this->formatResult(
                $produkPelapis,
                $qtyPelapis,
                'Pelapis Dasar',
                $luas . ' m²'
            );
            $processedProductIds[] = $produkPelapis->id;
        }
    } else {
        Log::warning('Produk Pelapis Dasar tidak ditemukan untuk produk_id: ' . $produk->id);
    }

  // ============================================================
// 4. AKSESORIS - AREA ID 8
// ============================================================
$produkArea8 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 8;
});

if ($produkArea8 && !in_array($produkArea8->id, $processedProductIds)) {
    $satuanArea8 = $produkArea8->satuan_terkecil ?: 1;

    // Rumus: panjang perimeter / satuan_terkecil
    $qtyArea8 = $panjangPerimeter / $satuanArea8;
    $qtyArea8 = $this->ceil2($qtyArea8 + ($qtyArea8 * $waste));

    if ($qtyArea8 > 0) {
        $results[] = $this->formatResult(
            $produkArea8,
            $qtyArea8,
            $produkArea8->area->nama_area,
            $panjangPerimeter . ' m'
        );
        $processedProductIds[] = $produkArea8->id;
    }
}


// ============================================================
// 5. AKSESORIS - AREA ID 89
// ============================================================
$produkArea89 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 89;
});

if ($produkArea89 && !in_array($produkArea89->id, $processedProductIds)) {
    $satuanArea89 = $produkArea89->satuan_terkecil ?: 1;

    // Rumus: panjang perimeter / satuan_terkecil
    $qtyArea89 = $panjangPerimeter / $satuanArea89;
    $qtyArea89 = $this->ceil2($qtyArea89 + ($qtyArea89 * $waste));

    if ($qtyArea89 > 0) {
        $results[] = $this->formatResult(
            $produkArea89,
            $qtyArea89,
            $produkArea89->area->nama_area,
            $panjangPerimeter . ' m'
        );
        $processedProductIds[] = $produkArea89->id;
    }
}


// ============================================================
// 6. AKSESORIS - AREA ID 7
// ============================================================
$produkArea7 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 7;
});

if ($produkArea7 && !in_array($produkArea7->id, $processedProductIds)) {
    $satuanArea7 = $produkArea7->satuan_terkecil ?: 1;

    // Rumus: qty id 89 × satuan_terkecil
    $qtyArea7 = $qtyArea89 * $satuanArea7;
    $qtyArea7 = $this->ceil2($qtyArea7 + ($qtyArea7 * $waste));

    if ($qtyArea7 > 0) {
        $results[] = $this->formatResult(
            $produkArea7,
            $qtyArea7,
            $produkArea7->area->nama_area,
            $luas . ' m²'
        );
        $processedProductIds[] = $produkArea7->id;
    }
}
// ============================================================
// 7. AKSESORIS - AREA ID 20
// ============================================================
$produkArea20 = $produk->accessories->first(function ($acc) {
    return $acc->area_id == 20;
});

if ($produkArea20 && !in_array($produkArea20->id, $processedProductIds)) {
    $satuanArea20 = $produkArea20->satuan_terkecil ?: 1;

    // Rumus: (panjang perimeter + (tinggi perimeter / 100)) + luas
    $qtyRaw20 = ($panjangPerimeter + ($tinggiPerimeter / 100)) + $luas;
    $qtyArea20 = $this->ceil2($qtyRaw20 + ($qtyRaw20 * $waste));

    if ($qtyArea20 > 0) {
        $results[] = $this->formatResult(
            $produkArea20,
            $qtyArea20,
            $produkArea20->area->nama_area,
            $qtyRaw20 . ' m'
        );
        $processedProductIds[] = $produkArea20->id;
    }
}
    // ============================================================
    // RETURN
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');

    Log::info('HASIL AKHIR SAGITTA:', [
        'produk_id'   => $produk->id,
        'produk_nama' => $produk->nama_produk,
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'aksesoris'   => $produk->accessories->pluck('nama_produk')->toArray(),
    ]);

    return response()->json([
        'success'     => true,
        'results'     => $results,
        'grand_total' => $grandTotal,
    ]);
}

public function exportPdfDuo(Request $request)
{
    Log::info('=== WaterproofingController: exportPdfSoprasun() ===');

    $data = $request->all();

    // Decode JSON hasil
    if (isset($data['hasil']) && is_string($data['hasil'])) {
        $data['hasil'] = json_decode($data['hasil'], true);
    }

    // Konversi qty, harga_satuan, total_harga
    if (isset($data['hasil']) && is_array($data['hasil'])) {
        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            if (isset($item['qty'])) {
                $data['hasil'][$i]['qty'] = (float) str_replace(['.', ','], '.', $item['qty']);
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

    // Konversi field numerik
    $numericFields = ['luas', 'waste', 'panjang_perimeter', 'tinggi_perimeter', 'sudut'];
    for ($i = 0; $i < count($numericFields); $i++) {
        $field = $numericFields[$i];
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = (float) str_replace([' m²', ' m', ' cm', ' °'], '', $data[$field]);
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
            return view('waterproofing.boq.soprasun-pdf', ['data' => $data]);
        }

        $boq = new Boq();
        $boq->nomor_boq   = $nomorBoq;
        $boq->tanggal_boq = now();
        $boq->save();

        Log::info('BOQ CREATED:', ['boq_id' => $boq->id, 'nomor_boq' => $nomorBoq]);

        $savedCount    = 0;
        $savedIds      = [];
        $uniqueResults = [];

        $items = array_values($data['hasil']);
        for ($i = 0; $i < count($items); $i++) {
            $item     = $items[$i];
            $produkId = $item['product_id'] ?? $item['produk_id'] ?? null;
            $qty      = (float) ($item['qty'] ?? 0);

            if (!$produkId || $qty <= 0) {
                Log::warning("SKIP INDEX {$i}: produk_id={$produkId}, qty={$qty}");
                continue;
            }

            // Cek duplikat
            $isDuplicate = false;
            for ($j = 0; $j < count($savedIds); $j++) {
                if ($savedIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue;

            $produk = Product::find($produkId);
            if ($produk) {
                DB::table('detail_boq')->insert([
                    'boq_id'      => $boq->id,
                    'produk_id'   => $produkId,
                    'kode_produk' => $produk->kode_produk,
                    'qty'         => $qty,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $savedIds[]      = $produkId;
                $savedCount++;
                $uniqueResults[] = $item;
            }
        }

        $data['hasil'] = $uniqueResults;

        Log::info('BOQ SAVED SUCCESSFULLY:', [
            'boq_id'        => $boq->id,
            'nomor_boq'     => $nomorBoq,
            'total_produk'  => $savedCount,
        ]);

    } catch (\Exception $e) {
        Log::error('ERROR STORE BOQ: ' . $e->getMessage());
        Log::error('ERROR TRACE: ' . $e->getTraceAsString());
    }

    return view('waterproofing.boq.pdf-duo', ['data' => $data]);
}

private function formatResult($produk, $qty, $areaName, $parameter = '-')
{
    return [
        'product_id'   => $produk->id,
        'produk_id'    => $produk->id,
        'nama_produk'  => $produk->nama_produk,
        'area'         => $areaName,
        'qty'          => $qty,
        'satuan'       => $produk->unit->unit_name ?? 'pcs',
        'harga_satuan' => $produk->harga ?? 0,
        'total_harga'  => $qty * ($produk->harga ?? 0),
        'parameter'    => $parameter,
    ];
}

    // ====== HELPER: Siapkan data umum ======
    private function prepareBoqData(Request $request)
    {
        $luas            = (float) $request->get('luas', 0);
        $waste           = (float) $request->get('waste', 5);
        $panjangPerimeter= (float) $request->get('panjang_perimeter', 0);
        $tinggiPerimeter = (float) $request->get('tinggi_perimeter', 0);
        $sudut           = (float) $request->get('sudut', 0);
        $brandId         = $request->get('brand_id');

        // Ambil produk sesuai brand
        $products = Product::where('brand_id', $brandId)->get();

        return [
            'products'         => $products,
            'luas'             => $luas,
            'waste'            => $waste,
            'panjangPerimeter' => $panjangPerimeter,
            'tinggiPerimeter'  => $tinggiPerimeter,
            'sudut'            => $sudut,
            'brandId'          => $brandId,
        ];
    }
}