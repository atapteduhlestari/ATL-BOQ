<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PalmexController extends Controller
{
    /**
     * Display BOQ Palmex page berdasarkan model
     */
    public function index($model)
{
    Log::info('=== PalmexController: index() === Model: ' . $model);
    
    $brand = ProductBrand::where('nama_brand', 'PALMEX')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan');
        $brand = ProductBrand::first();
    }
    
    // ============================================================
    // 1. PRODUK ATAP UTAMA (slug: atap-utama)
    // ============================================================
    $areaAtapUtama = ProductArea::where('slug', 'atap-utama')->first();
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ============================================================
    // 2. PRODUK JURAI (slug: palmex-jurai) - UNTUK LIMASAN, PELANA, DLL
    // ============================================================
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $juraiOptions = collect();
    if ($areaJurai) {
        $juraiOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaJurai->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK ATAS (slug: palmex-nok-atas) - UNTUK LIMASAN & PELANA
    // ============================================================
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    $nokAtasOptions = collect();
    if ($areaNokAtas) {
        $nokAtasOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaNokAtas->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 4. PRODUK NOK BULAT (slug: palmex-nok-bulat) - UNTUK PIRAMID, KERUCUT, DOME
    // ============================================================
    $areaNokBulat = ProductArea::where('slug', 'palmex-nok-bulat')->first();
    $nokBulatOptions = collect();
    if ($areaNokBulat) {
        $nokBulatOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaNokBulat->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 5. PRODUK SCREW (slug: palmex-screw)
    // ============================================================
    $areaScrew = ProductArea::where('slug', 'palmex-screw')->first();
    $screwOptions = collect();
    if ($areaScrew) {
        $screwOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaScrew->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 6. PRODUK RAIL (slug: palmex-rail)
    // ============================================================
    $areaRail = ProductArea::where('slug', 'palmex-rail')->first();
    $railOptions = collect();
    if ($areaRail) {
        $railOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaRail->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 7. PRODUK WIND (slug: palmex-wind)
    // ============================================================
    $areaWind = ProductArea::where('slug', 'palmex-wind')->first();
    $windOptions = collect();
    if ($areaWind) {
        $windOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaWind->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 8. UNDERLAYER (untuk non-expose)
    // ============================================================
       $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
        $underlayers = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaUnderlayer->id)
            ->with('unit')
            ->get();
    
    // ============================================================
    // 9. STARTER (slug: palmex-starter)
    // ============================================================
    $areaStarter = ProductArea::where('slug', 'palmex-starter')->first();
    $starterOptions = collect();
    if ($areaStarter) {
        $starterOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaStarter->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 10. METAL FLASHING (slug: palmex-metal-flashing)
    // ============================================================
    $areaMetalFlashing = ProductArea::where('slug', 'palmex-metal-flashing')->first();
    $metalFlashingOptions = collect();
    if ($areaMetalFlashing) {
        $metalFlashingOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaMetalFlashing->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 11. WALL FLASHING (slug: wall-flashing)
    // ============================================================
    $areaWallFlashing = ProductArea::where('slug', 'wall-flashing')->first();
    $wallFlashingOptions = collect();
    if ($areaWallFlashing) {
        $wallFlashingOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaWallFlashing->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 12. FLASHING KACA (slug: flashing-kaca)
    // ============================================================
    $areaFlashingKaca = ProductArea::where('slug', 'flashing-kaca')->first();
    $flashingKacaOptions = collect();
    if ($areaFlashingKaca) {
        $flashingKacaOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaFlashingKaca->id)
            ->where('nama_produk', 'LIKE', 'PALMEX%')  // HANYA PALMEX
            ->with('unit')
            ->get();
    }
    
    // ============================================================
    // 13. RANGKA & LANTAI KERJA (Hardcoded options)
    // ============================================================
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    // ============================================================
    // 14. VIEW MAPPING
    // ============================================================
    $viewMap = [
        'pelana' => 'boq.palmex.palmex-pelana',
        'limasan' => 'boq.palmex.palmex-limasan',
        'piramid' => 'boq.palmex.palmex-piramid',
        'satu-kemiringan' => 'boq.palmex.palmex-satu-kemiringan',
        'kerucut' => 'boq.palmex.palmex-kerucut',
        'dome' => 'boq.palmex.palmex-dome',
    ];
    
    $view = $viewMap[$model] ?? 'boq.palmex.palmex-pelana';
    
    // LOG UNTUK DEBUG
    Log::info('Jumlah produk: ' . $products->count());
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    Log::info('Jumlah Nok Bulat Options: ' . $nokBulatOptions->count());
    Log::info('Jumlah Underlayer: ' . $underlayers->count());
    
    return view($view, compact(
        'products',
        'juraiOptions',           // <-- UNTUK DROPDOWN JURAI
        'nokAtasOptions',         // <-- UNTUK DROPDOWN NOK ATAS
        'nokBulatOptions',        // <-- UNTUK DROPDOWN NOK BULAT (PIRAMID, KERUCUT, DOME)
        'screwOptions',
        'railOptions',
        'windOptions',
        'underlayers',
        'starterOptions',
        'metalFlashingOptions',
        'wallFlashingOptions',
        'flashingKacaOptions',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'model'
    ));
}

    /**
     * Calculate BOQ Palmex berdasarkan model
     */
    public function hitung(Request $request, $model)
    {
        Log::info('=== PalmexController: hitung() === Model: ' . $model);
        
        switch ($model) {
            case 'pelana':
                return $this->hitungPelana($request);
            case 'limasan':
                return $this->hitungLimasan($request);
            case 'piramid':
                return $this->hitungPiramid($request);
            case 'satu-kemiringan':
                return $this->hitungSatuKemiringan($request);
            case 'kerucut':
                return $this->hitungKerucut($request);
            case 'dome':
                return $this->hitungDome($request);
            default:
                return $this->hitungPelana($request);
        }
    }
    
/**
 * ============================================================
 * HITUNG ATAP PELANA
 * ============================================================
 */

private function hitungPelana($request)
{
   Log::info('=== PalmexController: hitungPelana() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $underlayerId = $request->underlayer_id;
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    
    // ===== VALIDASI SUDUT =====
    // Jika sudut < 30°, paksa ke non-expose
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $brand = ProductBrand::where('id', '14')->first();
    $brandId = $brand->id ?? 1;

    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja');
    
    Log::info('HITUNG PELANA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNokJurai' => $panjangNokJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'sistemPemasangan' => $sistemPemasangan
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
      if ($produkAtap) {
    // 1a. ATAP UTAMA
    $satuan = $produkAtap->satuan_terkecil ?? 1;
    $coverage = $request->coverage ?? 9; // Ambil coverage dari request
    
    // Hitung luas dengan waste terlebih dahulu
    $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
    
    if ($sistemPemasangan == 'expose') {
        // Expose: Luas (dengan waste) × Coverage
        $qty = ceil($luasDenganWaste * $coverage);
    } else {
        // Non-Expose: Luas (dengan waste) × Coverage
        // Atau tetap pakai satuan terkecil? Tergantung logic bisnis
        // Saya asumsikan pakai coverage juga karena sudah ada dropdown coverage
        $qty = ceil($luasDenganWaste * $coverage);
    }
    
    $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
    $processedProductIds[] = $produkAtap->id;
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP NOK BULAT (tidak digunakan di Pelana)
                if ($areaSlug == 'palmex-nok-bulat' || $areaName == 'Nok Bulat') {
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
    $qty = ceil($qtyRaw);
    $parameter = $panjangStarter;
    $displayArea = 'Starter';
                }
                elseif ($areaSlug == 'palmex-nok-atas' || $areaName == 'Nok Atas') {
                    $qtyRaw = $panjangNokJurai / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangNokJurai;
                    $displayArea = 'Nok Atas';
                }
                elseif ($areaSlug == 'palmex-topcap' || $areaName == 'Topcap') {
                    $qtyRaw = $panjangNokJurai / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $panjangNokJurai;
                    $displayArea = 'Topcap';
                }
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
           elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
    if ($sistemPemasangan == 'expose') {
        // Expose: (Luas × Satuan Terkecil) + (Panjang Nok × 8)
        $qtyRaw = ($luasAtap * $satuanAksesoris) + ($panjangNokJurai * 8);
    } else {
        // Non-Expose: (Luas × 21) + (Panjang Nok × 8)
        $qtyRaw = ($luasAtap * 21) + ($panjangNokJurai * 8);
    }
    $qty = ceil($qtyRaw);
    $parameter = $luasAtap;
    $displayArea = 'Screw';
}
             elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
    // Cari qty Starter dan Atap Utama dari results yang sudah ada
    $qtyStarter = 0;
    $qtyAtapUtama = 0;
    
    foreach ($results as $result) {
        if ($result['area'] == 'Starter') {
            $qtyStarter = $result['qty'];
        }
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
        }
    }
    
    // Rail = (Qty Starter + Qty Atap Utama) / 3
    $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
    $qty = ceil($qtyRaw);
    $parameter = $panjangStarter;
    $displayArea = 'Rail';
}
              elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
    if ($sistemPemasangan == 'expose') {
        // Expose: Luas × Satuan Terkecil
        $qtyRaw = $luasAtap * $satuanAksesoris;
    } else {
        // Non-Expose: Luas × 4
        $qtyRaw = $luasAtap * 4;
    }
    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
    $parameter = $luasAtap;
    $displayArea = 'Wind';
}
               elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
    // Metal Flashing: aksesoris bawaan atap, hitung dari panjang flashing
    $qtyRaw = $panjangFlashing / $satuanAksesoris;
    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
    $parameter = $panjangFlashing;
    $displayArea = 'Metal Flashing';
}
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}
    // ============================================================
    // 4. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

/**
 * ============================================================
 * HITUNG ATAP LIMASAN - PALMEX
 * Nok dan Jurai dipisah
 * ============================================================
 */
private function hitungLimasan($request)
{
    Log::info('=== PalmexController: hitungLimasan() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNok = $request->panjang_nok ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $juraiId = $request->jurai_id ?? null;
    $nokAtasId = $request->nok_atas_id ?? null;
    $underlayerId = $request->underlayer_id;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    $coverage = $request->coverage ?? 9; // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $brand = ProductBrand::where('id', '14')->first();
    $brandId = $brand->id ?? 1;
    
    Log::info('HITUNG LIMASAN:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNok' => $panjangNok,
        'panjangJurai' => $panjangJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'rangka' => $rangka,
        'sistemPemasangan' => $sistemPemasangan,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId && $panjangJurai > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokAtasId && $panjangNok > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product - SKIP JURAI & NOK)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if ($produkAtap) {
            // 1a. ATAP UTAMA
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            
            // ===== PAKAI COVERAGE =====
            // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
            $qty = ceil($luasDenganWaste * $coverage);
            
            Log::info('HITUNG ATAP UTAMA LIMASAN:', [
                'luasAtap' => $luasAtap,
                'waste' => $waste,
                'luasDenganWaste' => $luasDenganWaste,
                'coverage' => $coverage,
                'sistemPemasangan' => $sistemPemasangan,
                'qty' => $qty
            ]);
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP NOK BULAT (tidak digunakan di Limasan)
                if ($areaSlug == 'palmex-nok-bulat' || $areaName == 'Nok Bulat') {
                    continue;
                }
                
                // ===== SKIP JURAI (pakai dropdown) =====
                if ($areaSlug == 'palmex-jurai' || 
                    stripos($areaSlug, 'jurai') !== false ||
                    stripos($areaName, 'Jurai') !== false) {
                    Log::info('⏭️ SKIP Jurai (dari dropdown): ' . $areaName);
                    continue;
                }
                
                // ===== SKIP NOK ATAS (pakai dropdown) =====
                if ($areaSlug == 'palmex-nok-atas' || 
                    stripos($areaSlug, 'nok-atas') !== false ||
                    stripos($areaName, 'Nok Atas') !== false) {
                    Log::info('⏭️ SKIP Nok Atas (dari dropdown): ' . $areaName);
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                // ============================================================
                // STARTER
                // ============================================================
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangStarter / $satuanAksesoris;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Starter';
                    }
                }
                // ============================================================
                // WALL FLASHING (dari opsi dinding)
                // ============================================================
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                // ============================================================
                // FLASHING KACA (dari opsi kaca)
                // ============================================================
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
                // ============================================================
                // SCREW
                // ============================================================
                elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
                    $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                    $step1 = $panjangJurai - (0.25 * 4);
                    $step2 = $step1 / $jarakJurai;
                    $jumlahJuraiDalam = $step2 + (2 * 4);
                    $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                    
                    // Cari qty Nok Atas dari dropdown
                    $qtyNokAtas = 0;
                    if ($nokAtasId && $panjangNok > 0) {
                        $nokAtasProduct = Product::find($nokAtasId);
                        if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                            $qtyNokAtas = ceil($panjangNok / $nokAtasProduct->satuan_terkecil);
                        }
                    }
                    
                    if ($sistemPemasangan == 'expose') {
                        // Expose: (Luas × Coverage) + (Jumlah Jurai × 2) + (Nok × 8)
                        $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                    } else {
                        // Non-Expose: (Luas × 21) + (Jumlah Jurai × 2) + (Nok × 8)
                        $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Screw';
                }
                // ============================================================
                // RAIL
                // ============================================================
                elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
                    $qtyStarter = 0;
                    $qtyAtapUtama = 0;
                    
                    foreach ($results as $result) {
                        if ($result['area'] == 'Starter') {
                            $qtyStarter = $result['qty'];
                        }
                        if ($result['area'] == 'Atap Utama') {
                            $qtyAtapUtama = $result['qty'];
                        }
                    }
                    
                    if (($qtyStarter + $qtyAtapUtama) > 0) {
                        $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Rail';
                    }
                }
                // ============================================================
                // WIND
                // ============================================================
                elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
                    if ($sistemPemasangan == 'expose') {
                        // Expose: Luas × Coverage
                        $qtyRaw = $luasAtap * $coverage;
                    } else {
                        // Non-Expose: Luas × 4
                        $qtyRaw = $luasAtap * 4;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Wind';
                }
                // ============================================================
                // METAL FLASHING
                // ============================================================
                elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
                    if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangFlashing / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $panjangFlashing;
                        $displayArea = 'Metal Flashing';
                    }
                }
                else {
                    continue;
                }
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari dropdown)
    // ============================================================
    if ($juraiId && $panjangJurai > 0) {
        $jurai = Product::with('unit')->find($juraiId);
        if ($jurai) {
            $jarak = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
            $step1 = $panjangJurai - (0.25 * 4);
            $step2 = $step1 / $jarak;
            $step3 = $step2 + (2 * 4);
            $qtyRaw = $step3 / $jurai->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            $results[] = $this->formatResult($jurai, $qty, 'Jurai', $panjangJurai);
            $processedProductIds[] = $jurai->id;
        }
    }
    
    // ============================================================
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNok > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNok / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNok);
            $processedProductIds[] = $nokAtas->id;
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}

    
    // ============================================================
    // 7. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan,
        'rangka' => $rangka,
        'coverage_used' => $coverage
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
/**
 * ============================================================
 * HITUNG ATAP PIRAMID - PALMEX
 * Piramid hanya memiliki Jurai (tanpa Nok)
 * ============================================================
 */
private function hitungPiramid($request)
{
    Log::info('=== PalmexController: hitungPiramid() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $juraiId = $request->jurai_id ?? null;
    $nokBulatId = $request->nok_bulat_id ?? null;
    $underlayerId = $request->underlayer_id;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    $coverage = $request->coverage ?? 9; // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $brand = ProductBrand::where('id', '14')->first();
    $brandId = $brand->id ?? 1;
    
    Log::info('HITUNG PIRAMID:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'juraiId' => $juraiId,
        'nokBulatId' => $nokBulatId,
        'rangka' => $rangka,
        'sistemPemasangan' => $sistemPemasangan,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId && $panjangJurai > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokBulatId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Bulat wajib dipilih!'
        ]);
    }
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product - SKIP JURAI & NOK BULAT)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if ($produkAtap) {
            // 1a. ATAP UTAMA
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            
            // ===== PAKAI COVERAGE =====
            // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
            $qty = ceil($luasDenganWaste * $coverage);
            
            Log::info('HITUNG ATAP UTAMA PIRAMID:', [
                'luasAtap' => $luasAtap,
                'waste' => $waste,
                'luasDenganWaste' => $luasDenganWaste,
                'coverage' => $coverage,
                'sistemPemasangan' => $sistemPemasangan,
                'qty' => $qty
            ]);
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP NOK ATAS (Piramid tidak punya Nok Atas)
                if ($areaSlug == 'palmex-nok-atas' || $areaName == 'Nok Atas') {
                    continue;
                }
                
                // ===== SKIP JURAI (pakai dropdown) =====
                if ($areaSlug == 'palmex-jurai' || 
                    stripos($areaSlug, 'jurai') !== false ||
                    stripos($areaName, 'Jurai') !== false) {
                    Log::info('⏭️ SKIP Jurai (dari dropdown): ' . $areaName);
                    continue;
                }
                
                // ===== SKIP NOK BULAT (pakai dropdown) =====
                if ($areaSlug == 'palmex-nok-bulat' || 
                    stripos($areaSlug, 'nok-bulat') !== false ||
                    stripos($areaName, 'Nok Bulat') !== false) {
                    Log::info('⏭️ SKIP Nok Bulat (dari dropdown): ' . $areaName);
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                // ============================================================
                // STARTER
                // ============================================================
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangStarter / $satuanAksesoris;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Starter';
                    }
                }
                // ============================================================
                // WALL FLASHING (dari opsi dinding)
                // ============================================================
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                // ============================================================
                // FLASHING KACA (dari opsi kaca)
                // ============================================================
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
                // ============================================================
                // SCREW
                // ============================================================
                elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
                    // Hitung Jumlah Jurai Dalam
                    $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                    $step1Jurai = $panjangJurai - (0.25 * 4);
                    $step2Jurai = $step1Jurai / $jarakJurai;
                    $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                    $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                    
                    if ($sistemPemasangan == 'expose') {
                        // Expose: (Luas × Coverage) + (Jumlah Jurai × 2)
                        $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2);
                    } else {
                        // Non-Expose: (Luas × 21) + (Jumlah Jurai × 2)
                        $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2);
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Screw';
                }
                // ============================================================
                // RAIL
                // ============================================================
                elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
                    $qtyStarter = 0;
                    $qtyAtapUtama = 0;
                    
                    foreach ($results as $result) {
                        if ($result['area'] == 'Starter') {
                            $qtyStarter = $result['qty'];
                        }
                        if ($result['area'] == 'Atap Utama') {
                            $qtyAtapUtama = $result['qty'];
                        }
                    }
                    
                    if (($qtyStarter + $qtyAtapUtama) > 0) {
                        $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Rail';
                    }
                }
                // ============================================================
                // WIND
                // ============================================================
                elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
                    if ($sistemPemasangan == 'expose') {
                        // Expose: Luas × Coverage
                        $qtyRaw = $luasAtap * $coverage;
                    } else {
                        // Non-Expose: Luas × 4
                        $qtyRaw = $luasAtap * 4;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Wind';
                }
                // ============================================================
                // METAL FLASHING
                // ============================================================
                elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
                    if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangFlashing / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $panjangFlashing;
                        $displayArea = 'Metal Flashing';
                    }
                }
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari dropdown)
    // ============================================================
    if ($juraiId && $panjangJurai > 0) {
        $jurai = Product::with('unit')->find($juraiId);
        if ($jurai) {
            $jarak = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
            $step1 = $panjangJurai - (0.25 * 4);
            $step2 = $step1 / $jarak;
            $step3 = $step2 + (2 * 4);
            $qtyRaw = $step3 / $jurai->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            $results[] = $this->formatResult($jurai, $qty, 'Jurai', $panjangJurai);
            $processedProductIds[] = $jurai->id;
            Log::info('Jurai dari dropdown:', [
                'nama' => $jurai->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. NOK BULAT (dari dropdown) - WAJIB
    // ============================================================
    if ($nokBulatId) {
        $nokBulat = Product::with('unit')->find($nokBulatId);
        if ($nokBulat) {
            $qty = 1; // Nok Bulat selalu 1 untuk Piramid
            $results[] = $this->formatResult($nokBulat, $qty, 'Nok Bulat', '1 unit');
            $processedProductIds[] = $nokBulat->id;
            Log::info('Nok Bulat dari dropdown:', [
                'nama' => $nokBulat->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
      // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}
    // ============================================================
    // 7. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PIRAMID:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan,
        'rangka' => $rangka,
        'coverage_used' => $coverage
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
/**
 * ============================================================
 * HITUNG ATAP KERUCUT - PALMEX
 * Kerucut memiliki Nok dan Jurai dipisah
 * ============================================================
 */
private function hitungKerucut($request)
{
    Log::info('=== PalmexController: hitungKerucut() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $nokBulatId = $request->nok_bulat_id ?? null;
    $underlayerId = $request->underlayer_id;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    
    $brand = ProductBrand::where('nama_brand', 'PALMEX')->first();
    $brandId = $brand->id ?? 1;
    
    Log::info('HITUNG KERUCUT:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'nokBulatId' => $nokBulatId,
        'rangka' => $rangka
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$nokBulatId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Bulat wajib dipilih!'
        ]);
    }
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product - SKIP NOK BULAT)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if ($produkAtap) {
            // 1a. ATAP UTAMA
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            
            if ($sistemPemasangan == 'expose') {
                $qty = ceil($luasDenganWaste * 9);
            } else {
                $qty = ceil($luasDenganWaste * $satuan);
            }
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP NOK ATAS (Kerucut tidak punya Nok Atas)
                if ($areaSlug == 'palmex-nok-atas' || $areaName == 'Nok Atas') {
                    continue;
                }
                
                // ===== SKIP JURAI (Kerucut tidak punya Jurai) =====
                if ($areaSlug == 'palmex-jurai' || 
                    stripos($areaSlug, 'jurai') !== false ||
                    stripos($areaName, 'Jurai') !== false) {
                    Log::info('⏭️ SKIP Jurai (Kerucut tidak punya jurai)');
                    continue;
                }
                
                // ===== SKIP NOK BULAT (pakai dropdown) =====
                if ($areaSlug == 'palmex-nok-bulat' || 
                    stripos($areaSlug, 'nok-bulat') !== false ||
                    stripos($areaName, 'Nok Bulat') !== false) {
                    Log::info('⏭️ SKIP Nok Bulat (dari dropdown): ' . $areaName);
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                // ============================================================
                // STARTER
                // ============================================================
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangStarter / $satuanAksesoris;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Starter';
                    }
                }
                // ============================================================
                // WALL FLASHING (dari opsi dinding)
                // ============================================================
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                // ============================================================
                // FLASHING KACA (dari opsi kaca)
                // ============================================================
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
                // ============================================================
                // SCREW (Kerucut tidak ada jurai & nok)
                // ============================================================
                elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
                    if ($sistemPemasangan == 'expose') {
                        $qtyRaw = $luasAtap * $satuanAksesoris;
                    } else {
                        $qtyRaw = $luasAtap * 21;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Screw';
                }
                // ============================================================
                // RAIL
                // ============================================================
                elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
                    $qtyStarter = 0;
                    $qtyAtapUtama = 0;
                    
                    foreach ($results as $result) {
                        if ($result['area'] == 'Starter') {
                            $qtyStarter = $result['qty'];
                        }
                        if ($result['area'] == 'Atap Utama') {
                            $qtyAtapUtama = $result['qty'];
                        }
                    }
                    
                    if (($qtyStarter + $qtyAtapUtama) > 0) {
                        $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Rail';
                    }
                }
                // ============================================================
                // WIND
                // ============================================================
                elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
                    if ($sistemPemasangan == 'expose') {
                        $qtyRaw = $luasAtap * $satuanAksesoris;
                    } else {
                        $qtyRaw = $luasAtap * 4;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Wind';
                }
                // ============================================================
                // METAL FLASHING
                // ============================================================
                elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
                    if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangFlashing / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $panjangFlashing;
                        $displayArea = 'Metal Flashing';
                    }
                }
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. NOK BULAT (dari dropdown) - WAJIB
    // ============================================================
    if ($nokBulatId) {
        $nokBulat = Product::with('unit')->find($nokBulatId);
        if ($nokBulat) {
            $qty = 1; // Nok Bulat selalu 1 untuk Kerucut
            $results[] = $this->formatResult($nokBulat, $qty, 'Nok Bulat', '1 unit');
            $processedProductIds[] = $nokBulat->id;
            Log::info('Nok Bulat dari dropdown:', [
                'nama' => $nokBulat->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
      // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}
    
    // ============================================================
    // 6. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL KERUCUT:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan,
        'rangka' => $rangka
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
/**
 * ============================================================
 * HITUNG ATAP SATU KEMIRINGAN - PALMEX
 * Satu Kemiringan memiliki Nok dan Jurai (sama dengan panjang)
 * ============================================================
 */
private function hitungSatuKemiringan($request)
{
    Log::info('=== PalmexController: hitungSatuKemiringan() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $underlayerId = $request->underlayer_id;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    $coverage = $request->coverage ?? 9; // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $brand = ProductBrand::where('id', '14')->first();
    $brandId = $brand->id ?? 1;
    
    Log::info('HITUNG SATU KEMIRINGAN:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'rangka' => $rangka,
        'sistemPemasangan' => $sistemPemasangan,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if ($produkAtap) {
            // 1a. ATAP UTAMA
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            
            // ===== PAKAI COVERAGE =====
            // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
            $qty = ceil($luasDenganWaste * $coverage);
            
            Log::info('HITUNG ATAP UTAMA SATU KEMIRINGAN:', [
                'luasAtap' => $luasAtap,
                'waste' => $waste,
                'luasDenganWaste' => $luasDenganWaste,
                'coverage' => $coverage,
                'sistemPemasangan' => $sistemPemasangan,
                'qty' => $qty
            ]);
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP JURAI & NOK ATAS (Satu Kemiringan tidak punya)
                if ($areaSlug == 'palmex-jurai' || $areaName == 'Jurai') {
                    continue;
                }
                if ($areaSlug == 'palmex-nok-atas' || $areaName == 'Nok Atas') {
                    continue;
                }
                if ($areaSlug == 'palmex-nok-bulat' || $areaName == 'Nok Bulat') {
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                // ============================================================
                // STARTER
                // ============================================================
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangStarter / $satuanAksesoris;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Starter';
                    }
                }
                // ============================================================
                // WALL FLASHING (dari opsi dinding)
                // ============================================================
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                // ============================================================
                // FLASHING KACA (dari opsi kaca)
                // ============================================================
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
                // ============================================================
                // SCREW (Satu Kemiringan tidak ada jurai & nok)
                // ============================================================
                elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
                    // Satu Kemiringan
                    if ($sistemPemasangan == 'expose') {
                        // Expose: Luas × Coverage
                        $qtyRaw = $luasAtap * $coverage;
                    } else {
                        // Non-Expose: Luas × 21
                        $qtyRaw = $luasAtap * 21;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Screw';
                }
                // ============================================================
                // RAIL
                // ============================================================
                elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
                    $qtyStarter = 0;
                    $qtyAtapUtama = 0;
                    
                    foreach ($results as $result) {
                        if ($result['area'] == 'Starter') {
                            $qtyStarter = $result['qty'];
                        }
                        if ($result['area'] == 'Atap Utama') {
                            $qtyAtapUtama = $result['qty'];
                        }
                    }
                    
                    if (($qtyStarter + $qtyAtapUtama) > 0) {
                        $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Rail';
                    }
                }
                // ============================================================
                // WIND
                // ============================================================
                elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
                    if ($sistemPemasangan == 'expose') {
                        // Expose: Luas × Coverage
                        $qtyRaw = $luasAtap * $coverage;
                    } else {
                        // Non-Expose: Luas × 4
                        $qtyRaw = $luasAtap * 4;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Wind';
                }
                // ============================================================
                // METAL FLASHING
                // ============================================================
                elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
                    if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangFlashing / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $panjangFlashing;
                        $displayArea = 'Metal Flashing';
                    }
                }
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
         // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}
    
    // ============================================================
    // 5. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL SATU KEMIRINGAN:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan,
        'rangka' => $rangka,
        'coverage_used' => $coverage
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
/**
 * ============================================================
 * HITUNG ATAP DOME - PALMEX
 * Dome hanya memiliki Jurai (tanpa Nok)
 * ============================================================
 */
private function hitungDome($request)
{
    Log::info('=== PalmexController: hitungDome() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    
    $produkAtapId = $request->produk_atap_id;
    $underlayerId = $request->underlayer_id;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    $sistemPemasangan = $request->sistem_pemasangan ?? 'expose';
    
    $brand = ProductBrand::where('id', '14')->first();
    $brandId = $brand->id ?? 1;
    
    Log::info('HITUNG DOME:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiKaca' => $opsiKaca,
        'rangka' => $rangka
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (dari relasi product)
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if ($produkAtap) {
            // 1a. ATAP UTAMA
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            
            if ($sistemPemasangan == 'expose') {
                $qty = ceil($luasDenganWaste * 9);
            } else {
                $qty = ceil($luasDenganWaste * $satuan);
            }
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // 1b. LOOP SEMUA AKSESORIS DARI PRODUCT
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                // SKIP UNDERLAYER (dihitung terpisah dari dropdown)
                if ($areaSlug == 'underlayer' || $areaName == 'Underlayer') {
                    continue;
                }
                
                // SKIP JURAI (Dome tidak punya Jurai)
                if ($areaSlug == 'palmex-jurai' || $areaName == 'Jurai') {
                    continue;
                }
                
                // SKIP NOK ATAS (Dome tidak punya Nok Atas)
                if ($areaSlug == 'palmex-nok-atas' || $areaName == 'Nok Atas') {
                    continue;
                }
                
                // SKIP NOK BULAT (Dome tidak punya Nok Bulat)
                if ($areaSlug == 'palmex-nok-bulat' || $areaName == 'Nok Bulat') {
                    continue;
                }
                
                $qty = 0;
                $parameter = 0;
                $displayArea = $areaName;
                $satuanAksesoris = $aksesoris->satuan_terkecil ?? 1;
                
                // ============================================================
                // STARTER
                // ============================================================
                if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                    if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangStarter / $satuanAksesoris;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Starter';
                    }
                }
                // ============================================================
                // TOPCAP (puncak dome)
                // ============================================================
                elseif ($areaSlug == 'palmex-topcap' || $areaName == 'Topcap') {
                    $qty = 1; // Topcap selalu 1 untuk Dome
                    $parameter = '1 unit';
                    $displayArea = 'Topcap';
                }
                // ============================================================
                // WALL FLASHING (dari opsi dinding)
                // ============================================================
                elseif ($areaSlug == 'wall-flashing' || $areaName == 'Wall Flashing') {
                    if ($opsiDinding > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiDinding / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiDinding;
                        $displayArea = 'Wall Flashing';
                    }
                }
                // ============================================================
                // FLASHING KACA (dari opsi kaca)
                // ============================================================
                elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                    if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $opsiKaca / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $opsiKaca;
                        $displayArea = 'Flashing Kaca';
                    }
                }
                // ============================================================
                // SCREW (Dome tidak ada jurai)
                // ============================================================
                elseif ($areaSlug == 'palmex-screw' || $areaName == 'Screw') {
                    if ($sistemPemasangan == 'expose') {
                        $qtyRaw = $luasAtap * $satuanAksesoris;
                    } else {
                        $qtyRaw = $luasAtap * 21;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Screw';
                }
                // ============================================================
                // RAIL
                // ============================================================
                elseif ($areaSlug == 'palmex-rail' || $areaName == 'Rail') {
                    $qtyStarter = 0;
                    $qtyAtapUtama = 0;
                    
                    foreach ($results as $result) {
                        if ($result['area'] == 'Starter') {
                            $qtyStarter = $result['qty'];
                        }
                        if ($result['area'] == 'Atap Utama') {
                            $qtyAtapUtama = $result['qty'];
                        }
                    }
                    
                    if (($qtyStarter + $qtyAtapUtama) > 0) {
                        $qtyRaw = ($qtyStarter + $qtyAtapUtama) / 3;
                        $qty = ceil($qtyRaw);
                        $parameter = $panjangStarter;
                        $displayArea = 'Rail';
                    }
                }
                // ============================================================
                // WIND
                // ============================================================
                elseif ($areaSlug == 'palmex-wind' || $areaName == 'Wind') {
                    if ($sistemPemasangan == 'expose') {
                        $qtyRaw = $luasAtap * $satuanAksesoris;
                    } else {
                        $qtyRaw = $luasAtap * 4;
                    }
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $luasAtap;
                    $displayArea = 'Wind';
                }
                // ============================================================
                // METAL FLASHING
                // ============================================================
                elseif ($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing' && $sistemPemasangan == 'non-expose') {
                    if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                        $qtyRaw = $panjangFlashing / $satuanAksesoris;
                        $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                        $parameter = $panjangFlashing;
                        $displayArea = 'Metal Flashing';
                    }
                }
                
                if ($qty > 0) {
                    $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
       // ============================================================
// 5. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Cari produk lantai kerja dari database berdasarkan ID
    $lantaiKerjaProduct = Product::where('brand_id', $brandId)
        ->where('id', $lantaiKerja)
        ->first();

    if ($lantaiKerjaProduct) {
        $satuanTerkecil = $lantaiKerjaProduct->satuan_terkecil ?: 1;

        // qty = luasAtap / satuan_terkecil (+ waste)
        $qtyRaw = $luasAtap / $satuanTerkecil;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = $this->formatResult(
            $lantaiKerjaProduct,
            $qtyPlywood,
            'Lantai Kerja',
            $luasAtap . ' m²'
        );

        Log::info('Lantai Kerja dari database ditambahkan:', [
            'id'              => $lantaiKerjaProduct->id,
            'nama'            => $lantaiKerjaProduct->nama_produk,
            'satuan_terkecil' => $satuanTerkecil,
            'luasAtap'        => $luasAtap,
            'qtyRaw'          => $qtyRaw,
            'waste'           => $waste,
            'qty_final'       => $qtyPlywood,
        ]);
    } else {
        // Fallback hardcode kalau produk tidak ditemukan
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

        $results[] = [
            'product_id'   => null,
            'produk_id'    => null,
            'nama_produk'  => $lantaiKerja,
            'area'         => 'Lantai Kerja',
            'qty'          => $qtyPlywood,
            'satuan'       => 'lembar',
            'harga_satuan' => 0,
            'total_harga'  => 0,
            'parameter'    => $luasAtap . ' m²'
        ];

        Log::warning('Lantai Kerja tidak ditemukan di database, fallback hardcode:', [
            'lantai_kerja_id' => $lantaiKerja,
            'brand_id'        => $brandId,
            'luasPerLembar'   => $luasPerLembar,
            'qty_final'       => $qtyPlywood,
        ]);
    }
}
    
// ============================================================
// 6. SCREW PLYWOOD (HANYA UNTUK NON-EXPOSE)
// ============================================================
if ($sistemPemasangan == 'non-expose') {

    // Ambil qtyPlywood dari results (yang sudah dihitung di step 5)
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if (($result['area'] ?? '') == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }

    if ($qtyPlywood > 0) {

        // ============================================================
        // TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
        // ============================================================

        $lantaiKerjaId = (int) $lantaiKerja;

        $grupA = [403, 404, 405];           // → screw 410
        $grupB = [406, 407, 408];           // → screw 411
        $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408]; // → screw 409

        $screwId = null;

        if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
            if (in_array($lantaiKerjaId, $grupA, true)) {
                $screwId = 410;
            } elseif (in_array($lantaiKerjaId, $grupB, true)) {
                $screwId = 411;
            }
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
            if (in_array($lantaiKerjaId, $grupBajaBeratBeton, true)) {
                $screwId = 409;
            }
        }

        // ============================================================
        // AMBIL PRODUK SCREW DARI DATABASE
        // ============================================================

        $screwProduct = null;

        if ($screwId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->where('id', $screwId)
                ->first();
        }

        // ============================================================
        // HITUNG QTY SCREW = qtyPlywood × satuan_terkecil
        // ============================================================

        if ($screwProduct) {
            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;

            // Tambah waste + bulatkan
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            Log::info('Screw dari database ditambahkan:', [
                'screw_id'        => $screwId,
                'nama'            => $screwProduct->nama_produk,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
                'qtyPlywood'      => $qtyPlywood,
                'satuan_terkecil' => $satuanTerkecil,
                'qtyScrew'        => $qtyScrew,
                'qty_final'       => $qty,
            ]);
        } else {
            // Fallback hardcode kalau produk screw tidak ditemukan
            $isKayuOrBajaRingan = in_array($rangka, ['Kayu', 'Baja Ringan'], true);
            $screwName = $isKayuOrBajaRingan ? 'Screw Plywood' : 'Drilling Screw';

            $results[] = [
                'product_id'   => null,
                'produk_id'    => null,
                'nama_produk'  => $screwName,
                'area'         => 'Paku & Screw',
                'qty'          => $qtyPlywood * 40,
                'satuan'       => 'pcs',
                'harga_satuan' => 0,
                'total_harga'  => 0,
                'parameter'    => $qtyPlywood . ' lembar plywood'
            ];

            Log::warning('Screw tidak ditemukan, fallback hardcode:', [
                'screw_id_target' => $screwId,
                'lantai_kerja_id' => $lantaiKerjaId,
                'rangka'          => $rangka,
            ]);
        }
    }
}
    // ============================================================
    // 5. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL DOME:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'sistem_pemasangan' => $sistemPemasangan,
        'rangka' => $rangka
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
    /**
     * Export PDF Palmex berdasarkan model
     */
   /**
 * Export PDF Palmex Pelana
 */
public function exportPdf(Request $request, $model)
{
    Log::info('=== PalmexController: exportPdf() === Model: ' . $model);
    
    $data = $request->all();
    
    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    $data['model'] = $model;
    
    // ============ STORE BOQ ============
    try {
        $results = $data['results'] ?? [];
        
        Log::info('TOTAL RESULTS: ' . count($results));
        
        // ============ HAPUS FILTER DUPLIKAT YANG MEMBUAT ITEM NULL HILANG ============
        // Langsung gunakan semua results tanpa filter
        $data['results'] = $results;
        
        // Simpan ke database (hanya yang memiliki product_id)
        if (!empty($results)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                // Hanya simpan yang punya product_id dan qty > 0
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
            
            Log::info('BOQ SAVED:', [
                'boq_id' => $boq->id,
                'total' => count($results)
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error: ' . $e->getMessage());
    }
    
    // Pilih view berdasarkan model
    $viewMap = [
        'pelana' => 'boq.palmex.pdf-palmex-pelana',
        'limasan' => 'boq.palmex.pdf-palmex-limasan',
        'piramid' => 'boq.palmex.pdf-palmex-piramid',
        'satu-kemiringan' => 'boq.palmex.pdf-palmex-satu-kemiringan',
        'kerucut' => 'boq.palmex.pdf-palmex-kerucut',
        'dome' => 'boq.palmex.pdf-palmex-dome',
    ];
    
    $view = $viewMap[$model] ?? 'boq.palmex.pdf-palmex-pelana';
    
    return view($view, compact('data'));
}
    
    /**
     * Format hasil perhitungan
     */
    private function formatResult($product, $qty, $area, $parameter)
    {
        $hargaSatuan = $product->harga_price_list ?? 0;
        $totalHarga = $qty * $hargaSatuan;
        $satuan = $product->unit->unit_name ?? 'unit';
        
        return [
            'product_id' => $product->id,
            'produk_id' => $product->id,
            'id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $area,
            'qty' => $qty,
            'satuan' => $satuan,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'parameter' => $parameter
        ];
    }
}