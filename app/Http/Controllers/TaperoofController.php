<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaperoofController extends Controller
{
    /**
     * Display BOQ Taperroof page berdasarkan model
     */
   public function index($model)
{
    Log::info('=== TaperroofController: index() === Model: ' . $model);
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    
    if (!$brand) {
        Log::error('Brand TAPE ROOF tidak ditemukan');
        $brand = ProductBrand::first();
    }
    
    Log::info('Brand ditemukan: ' . ($brand->nama_brand ?? 'null') . ' (ID: ' . ($brand->id ?? 'null') . ')');
    
    // ============================================================
    // 1. PRODUK NOK
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    
    Log::info('Area Nok ditemukan: ' . ($areaNok ? 'Yes (ID: ' . $areaNok->id . ')' : 'No'));
    
    $nokOptions = collect();
    if ($areaNok && $brand) {
        $nokOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaNok->id)
            ->with('unit')
            ->get();
        
        Log::info('Jumlah Nok Options ditemukan: ' . $nokOptions->count());
        
        // Log detail produk nok
        foreach ($nokOptions as $nok) {
            Log::info('Nok produk: ' . $nok->nama_produk . ' (ID: ' . $nok->id . ')');
        }
    } else {
        Log::warning('Area Nok atau Brand tidak ditemukan!');
    }
    
    // ============================================================
    // 2. RANGKA & LANTAI KERJA (Hardcoded options)
    // ============================================================
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    // ============================================================
    // 3. VIEW MAPPING
    // ============================================================
    $viewMap = [
        'pelana' => 'boq.taperoof.taperoof-pelana',
        'limasan' => 'boq.taperoof.taperoof-limasan',
        'piramid' => 'boq.taperoof.taperoof-piramid',
        'satu-kemiringan' => 'boq.taperoof.taperoof-satu-kemiringan',
        'kerucut' => 'boq.taperoof.taperoof-kerucut',
        'dome' => 'boq.taperoof.taperoof-dome', 
    ];

    $view = $viewMap[$model] ?? 'boq.taperoof.taperoof-pelana';
    
    return view($view, compact(
        'nokOptions',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'model'
    ));
}

    /**
     * Calculate BOQ Taperroof berdasarkan model
     */
    public function hitung(Request $request, $model)
    {
        Log::info('=== TaperroofController: hitung() === Model: ' . $model);
        
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
     * HITUNG ATAP PELANA - TAPERROOF
     * ============================================================
     */
   private function hitungPelana($request)
    {
        $luasAtap = $request->luas_atap ?? 0;
        $panjangStarter = $request->panjang_starter ?? 0;
        $panjangNok = $request->panjang_nok ?? 0;
        $panjangFlashing = $request->panjang_flashing ?? 0;
        $waste = $request->waste / 100;
        
        $opsiKaca = $request->opsi_kaca ?? 0;
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        $nokId = $request->nok_id ?? null;
        $rangka = $request->rangka ?? 'Baja Ringan';
        $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $results = [];
        $processedProductIds = [];
        
        // ============================================================
        // 1. ATAP UTAMA
        // ============================================================
        $produkAtap = null;
        if ($brandId) {
            $produkAtap = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'atap-utama');
                })
                ->with(['unit', 'accessories.unit', 'accessories.area'])
                ->first();
        }
        
        if ($produkAtap) {
            $satuan = $produkAtap->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            $qty = ceil($luasDenganWaste / $satuan);
            
            $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
            $processedProductIds[] = $produkAtap->id;
            
            // STARTER dari aksesoris
            foreach ($produkAtap->accessories as $aksesoris) {
                if (in_array($aksesoris->id, $processedProductIds)) {
                    continue;
                }
                
                $areaSlug = $aksesoris->area->slug ?? '';
                $areaName = $aksesoris->area->nama_area ?? '';
                
                if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                    if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                        // RUMUS: (luas area / satuan terkecil) / 24
                        $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                        $qty = ceil($qtyRaw);
                        $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                        $processedProductIds[] = $aksesoris->id;
                    }
                }
            }
        }
        
        // ============================================================
        // 2. TAPE ROOF NOK (dari dropdown)
        // ============================================================
        if ($nokId && $panjangStarter > 0) {
            $nok = Product::with('unit')->find($nokId);
            if ($nok && !in_array($nok->id, $processedProductIds)) {
                $satuan = $nok->satuan_terkecil ?? 1;
                // RUMUS: panjang_starter / satuan_terkecil
                $qtyRaw = $panjangStarter / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($nok, $qty, 'Tape Roof Nok', $panjangStarter);
                $processedProductIds[] = $nok->id;
            }
        }
        
        // ============================================================
        // 3. NOK TUTUP (ambil otomatis dari database) - LOCK 2
        // ============================================================
        if ($brandId && $panjangNok > 0) {
            $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
            $nokTutup = null;
            if ($areaNokTutup) {
                $nokTutup = Product::where('brand_id', $brandId)
                    ->where('area_id', $areaNokTutup->id)
                    ->with('unit')
                    ->first();
            }
            
            if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
                // LOCK DI ANGKA 2
                $qty = 2;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNok);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // ============================================================
        // 4. UNDERLAYER
        // ============================================================
        if ($brandId) {
            $underlayer = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'underlayer');
                })
                ->with('unit')
                ->first();
            
            if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
                $satuan = $underlayer->satuan_terkecil ?? 1;
                $qtyRaw = $luasAtap / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
                $processedProductIds[] = $underlayer->id;
            }
        }
        
        // ============================================================
        // 5. METAL FLASHING
        // ============================================================
        if ($brandId && $panjangFlashing > 0) {
            $metalFlashing = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'metal-flashing');
                })
                ->with('unit')
                ->first();
            
            if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
                $satuan = $metalFlashing->satuan_terkecil ?? 1;
                $qtyRaw = $panjangFlashing / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
                $processedProductIds[] = $metalFlashing->id;
            }
        }
        
        // ============================================================
        // 6. WALL FLASHING
        // ============================================================
        if ($brandId && $opsiDinding > 0) {
            $wallFlashing = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'wall-flashing');
                })
                ->with('unit')
                ->first();
            
            if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
                $satuan = $wallFlashing->satuan_terkecil ?? 1;
                $qtyRaw = $opsiDinding / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
                $processedProductIds[] = $wallFlashing->id;
            }
        }
        
        // ============================================================
        // 7. FLASHING KACA
        // ============================================================
        if ($brandId && $opsiKaca > 0) {
            $flashingKaca = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'flashing-kaca');
                })
                ->with('unit')
                ->first();
            
            if ($flashingKaca && !in_array($flashingKaca->id, $processedProductIds)) {
                $satuan = $flashingKaca->satuan_terkecil ?? 1;
                $qtyRaw = $opsiKaca / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($flashingKaca, $qty, 'Flashing Kaca', $opsiKaca);
                $processedProductIds[] = $flashingKaca->id;
            }
        }
        
        // ============================================================
        // 8. CEROBONG ASAP
        // ============================================================
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // ============================================================
        // 9. PENANGKAL PETIR
        // ============================================================
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // ============================================================
        // 10. LANTAI KERJA
        // ============================================================
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // ============================================================
        // 11. PAKU & SCREW (untuk atap)
        // ============================================================
        $qtyAtapUtama = 0;
        foreach ($results as $result) {
            if ($result['area'] == 'Atap Utama') {
                $qtyAtapUtama = $result['qty'];
                break;
            }
        }
        
        if ($qtyAtapUtama > 0) {
            $screwProduct = null;
            if ($brandId) {
                $screwProduct = Product::where('brand_id', $brandId)
                    ->whereHas('area', function($q) {
                        $q->where('slug', 'paku-screw');
                    })
                    ->with('unit')
                    ->first();
            }
            
            $satuan = $screwProduct->satuan_terkecil ?? 1;
            // RUMUS: (qty atap utama * 53) / satuan_terkecil
            $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
            $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
            
            $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
            $satuanText = $screwProduct->unit->unit_name ?? 'pcs';
            $harga = $screwProduct->harga_jual ?? 0;
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => $satuanText,
                'harga_satuan' => $harga,
                'total_harga' => $harga * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // ============================================================
        // 12. SCREW PLYWOOD (untuk plywood/lantai kerja)
        // ============================================================
        $qtyPlywood = 0;
        foreach ($results as $result) {
            if ($result['area'] == 'Lantai Kerja') {
                $qtyPlywood = $result['qty'];
                break;
            }
        }
        
        if ($qtyPlywood > 0) {
            $screwPlywoodProduct = null;
            if ($brandId) {
                $screwPlywoodProduct = Product::where('brand_id', $brandId)
                    ->whereHas('area', function($q) {
                        $q->where('slug', 'screw-plywood');
                    })
                    ->with('unit')
                    ->first();
            }
            
            $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
            // RUMUS: (qty plywood * 40) / 750
            $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
            $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
            $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
            $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'box';
            $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => $satuanText,
                'harga_satuan' => $harga,
                'total_harga' => $harga * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        // ============================================================
        // GRAND TOTAL
        // ============================================================
        $grandTotal = collect($results)->sum('total_harga');
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'grand_total' => $grandTotal
        ]);
    }



    /**
     * ============================================================
     * HITUNG ATAP LIMASAN - TAPERROOF
     * ============================================================
     */
 private function hitungLimasan($request)
{
    Log::info('=== TaperroofController: hitungLimasan() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $nokId = $request->nok_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG LIMASAN TAPE ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNokJurai' => $panjangNokJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'brandId' => $brandId
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA
    // ============================================================
    $produkAtap = null;
    if ($brandId) {
        $produkAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->first();
    }
    
    if ($produkAtap) {
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        $qty = ceil($luasDenganWaste / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // STARTER dari aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            if (in_array($aksesoris->id, $processedProductIds)) {
                continue;
            }
            
            $areaSlug = $aksesoris->area->slug ?? '';
            $areaName = $aksesoris->area->nama_area ?? '';
            
            if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                    $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                    $qty = ceil($qtyRaw);
                    $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK (dari dropdown) - PAKAI PANJANG NOK JURAI
    // ============================================================
    if ($nokId && $panjangNokJurai > 0) {
        $nok = Product::with('unit')->find($nokId);
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangNokJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Tape Roof Nok & Jurai', $panjangNokJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP (ambil otomatis dari database) - LOCK 4
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
        $nokTutup = null;
        if ($areaNokTutup) {
            $nokTutup = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNokTutup->id)
                ->with('unit')
                ->first();
        }
        
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 4. NOK 3 ARAH (ambil otomatis dari database) - LOCK 2
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $areaNok3Arah = ProductArea::where('slug', 'nok-3-arah')->first();
        $nok3Arah = null;
        if ($areaNok3Arah) {
            $nok3Arah = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNok3Arah->id)
                ->with('unit')
                ->first();
        }
        
        if ($nok3Arah && !in_array($nok3Arah->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nok3Arah, $qty, 'Nok 3 Arah', $panjangNokJurai);
            $processedProductIds[] = $nok3Arah->id;
        }
    }
    
    // ============================================================
    // 5. UNDERLAYER
    // ============================================================
    if ($brandId) {
        $underlayer = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'underlayer');
            })
            ->with('unit')
            ->first();
        
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
    // 6. METAL FLASHING
    // ============================================================
    if ($brandId && $panjangFlashing > 0) {
        $metalFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'metal-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
            $satuan = $metalFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $panjangFlashing / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
            $processedProductIds[] = $metalFlashing->id;
        }
    }
    
    // ============================================================
    // 7. WALL FLASHING
    // ============================================================
    if ($brandId && $opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'wall-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $satuan = $wallFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $opsiDinding / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============================================================
    // 8. CEROBONG ASAP
    // ============================================================
    if ($opsiCerobong > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Cerobong Asap',
            'area' => 'Cerobong Asap',
            'qty' => $opsiCerobong,
            'satuan' => 'unit',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiCerobong . ' unit'
        ];
    }
    
    // ============================================================
    // 9. PENANGKAL PETIR
    // ============================================================
    if ($opsiPenangkal > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Penangkal Petir',
            'area' => 'Penangkal Petir',
            'qty' => $opsiPenangkal,
            'satuan' => 'meter',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiPenangkal . ' meter'
        ];
    }
    
    // ============================================================
    // 10. LANTAI KERJA
    // ============================================================
    $luasPerLembar = 2.88;
    $qtyRaw = $luasAtap / $luasPerLembar;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    $results[] = [
        'product_id' => null,
        'produk_id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 11. PAKU & SCREW (untuk atap)
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    if ($qtyAtapUtama > 0) {
        $screwProduct = null;
        if ($brandId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'paku-screw');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwProduct->satuan_terkecil ?? 1;
        $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
        $satuanText = $screwProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 12. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // ============================================================
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }
    
    if ($qtyPlywood > 0) {
        $screwPlywoodProduct = null;
        if ($brandId) {
            $screwPlywoodProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'screw-plywood');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
        $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
        $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
        $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
        $satuanText = $screwPlywoodProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN TAPE ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area')
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

    /**
     * ============================================================
     * HITUNG ATAP PIRAMID - TAPERROOF
     * ============================================================
     */
   private function hitungPiramid($request)
{
    Log::info('=== TaperroofController: hitungPiramid() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $nokId = $request->nok_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG PIRAMID TAPE ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'brandId' => $brandId
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA
    // ============================================================
    $produkAtap = null;
    if ($brandId) {
        $produkAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->first();
    }
    
    if ($produkAtap) {
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        $qty = ceil($luasDenganWaste / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // STARTER dari aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            if (in_array($aksesoris->id, $processedProductIds)) {
                continue;
            }
            
            $areaSlug = $aksesoris->area->slug ?? '';
            $areaName = $aksesoris->area->nama_area ?? '';
            
            if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                    $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                    $qty = ceil($qtyRaw);
                    $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK & JURAI (dari dropdown) - PAKAI PANJANG JURAI
    // ============================================================
    if ($nokId && $panjangJurai > 0) {
        $nok = Product::with('unit')->find($nokId);
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Tape Roof Nok & Jurai', $panjangJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP (ambil otomatis dari database) - LOCK 4
    // ============================================================
    if ($brandId && $panjangJurai > 0) {
        $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
        $nokTutup = null;
        if ($areaNokTutup) {
            $nokTutup = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNokTutup->id)
                ->with('unit')
                ->first();
        }
        
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 4. NOK 4 ARAH (ambil otomatis dari database) - LOCK 1
    // ============================================================
    if ($brandId && $panjangJurai > 0) {
        $areaNok4Arah = ProductArea::where('slug', 'nok-4-arah')->first();
        $nok4Arah = null;
        if ($areaNok4Arah) {
            $nok4Arah = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNok4Arah->id)
                ->with('unit')
                ->first();
        }
        
        if ($nok4Arah && !in_array($nok4Arah->id, $processedProductIds)) {
            $qty = 1;
            $results[] = $this->formatResult($nok4Arah, $qty, 'Nok 4 Arah', $panjangJurai);
            $processedProductIds[] = $nok4Arah->id;
        }
    }
    
    // ============================================================
    // 5. UNDERLAYER
    // ============================================================
    if ($brandId) {
        $underlayer = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'underlayer');
            })
            ->with('unit')
            ->first();
        
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
    // 6. METAL FLASHING
    // ============================================================
    if ($brandId && $panjangFlashing > 0) {
        $metalFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'metal-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
            $satuan = $metalFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $panjangFlashing / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
            $processedProductIds[] = $metalFlashing->id;
        }
    }
    
    // ============================================================
    // 7. WALL FLASHING
    // ============================================================
    if ($brandId && $opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'wall-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $satuan = $wallFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $opsiDinding / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============================================================
    // 8. CEROBONG ASAP
    // ============================================================
    if ($opsiCerobong > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Cerobong Asap',
            'area' => 'Cerobong Asap',
            'qty' => $opsiCerobong,
            'satuan' => 'unit',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiCerobong . ' unit'
        ];
    }
    
    // ============================================================
    // 9. PENANGKAL PETIR
    // ============================================================
    if ($opsiPenangkal > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Penangkal Petir',
            'area' => 'Penangkal Petir',
            'qty' => $opsiPenangkal,
            'satuan' => 'meter',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiPenangkal . ' meter'
        ];
    }
    
    // ============================================================
    // 10. LANTAI KERJA
    // ============================================================
    $luasPerLembar = 2.88;
    $qtyRaw = $luasAtap / $luasPerLembar;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    $results[] = [
        'product_id' => null,
        'produk_id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 11. PAKU & SCREW (untuk atap)
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    if ($qtyAtapUtama > 0) {
        $screwProduct = null;
        if ($brandId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'paku-screw');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwProduct->satuan_terkecil ?? 1;
        $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
        $satuanText = $screwProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 12. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // ============================================================
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }
    
    if ($qtyPlywood > 0) {
        $screwPlywoodProduct = null;
        if ($brandId) {
            $screwPlywoodProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'screw-plywood');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
        $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
        $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
        $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
        $satuanText = $screwPlywoodProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PIRAMID TAPE ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area')
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

    /**
     * ============================================================
     * HITUNG ATAP SATU KEMIRINGAN - TAPERROOF
     * ============================================================
     */
   private function hitungSatuKemiringan($request)
{
    Log::info('=== TaperroofController: hitungSatuKemiringan() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNok = $request->panjang_nok ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $nokId = $request->nok_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG SATU KEMIRINGAN TAPE ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNok' => $panjangNok,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'brandId' => $brandId
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA
    // ============================================================
    $produkAtap = null;
    if ($brandId) {
        $produkAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->first();
    }
    
    if ($produkAtap) {
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        $qty = ceil($luasDenganWaste / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // STARTER dari aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            if (in_array($aksesoris->id, $processedProductIds)) {
                continue;
            }
            
            $areaSlug = $aksesoris->area->slug ?? '';
            $areaName = $aksesoris->area->nama_area ?? '';
            
            if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                    $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                    $qty = ceil($qtyRaw);
                    $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK (dari dropdown) - PAKAI PANJANG NOK
    // ============================================================
    if ($nokId && $panjangNok > 0) {
        $nok = Product::with('unit')->find($nokId);
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangNok / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Tape Roof Nok', $panjangNok);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP - TIDAK ADA DI SATU KEMIRINGAN (SKIP)
    // ============================================================
    // Satu kemiringan tidak pakai Nok Tutup
    
    // ============================================================
    // 4. UNDERLAYER
    // ============================================================
    if ($brandId) {
        $underlayer = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'underlayer');
            })
            ->with('unit')
            ->first();
        
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
    // 5. METAL FLASHING
    // ============================================================
    if ($brandId && $panjangFlashing > 0) {
        $metalFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'metal-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
            $satuan = $metalFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $panjangFlashing / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
            $processedProductIds[] = $metalFlashing->id;
        }
    }
    
    // ============================================================
    // 6. WALL FLASHING
    // ============================================================
    if ($brandId && $opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'wall-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $satuan = $wallFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $opsiDinding / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============================================================
    // 7. CEROBONG ASAP
    // ============================================================
    if ($opsiCerobong > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Cerobong Asap',
            'area' => 'Cerobong Asap',
            'qty' => $opsiCerobong,
            'satuan' => 'unit',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiCerobong . ' unit'
        ];
    }
    
    // ============================================================
    // 8. PENANGKAL PETIR
    // ============================================================
    if ($opsiPenangkal > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Penangkal Petir',
            'area' => 'Penangkal Petir',
            'qty' => $opsiPenangkal,
            'satuan' => 'meter',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiPenangkal . ' meter'
        ];
    }
    
    // ============================================================
    // 9. LANTAI KERJA
    // ============================================================
    $luasPerLembar = 2.88;
    $qtyRaw = $luasAtap / $luasPerLembar;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    $results[] = [
        'product_id' => null,
        'produk_id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 10. PAKU & SCREW (untuk atap)
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    if ($qtyAtapUtama > 0) {
        $screwProduct = null;
        if ($brandId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'paku-screw');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwProduct->satuan_terkecil ?? 1;
        $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
        $satuanText = $screwProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 11. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // ============================================================
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }
    
    if ($qtyPlywood > 0) {
        $screwPlywoodProduct = null;
        if ($brandId) {
            $screwPlywoodProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'screw-plywood');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
        $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
        $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
        $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
        $satuanText = $screwPlywoodProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL SATU KEMIRINGAN TAPE ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area')
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

    /**
     * ============================================================
     * HITUNG ATAP KERUCUT - TAPERROOF
     * ============================================================
     */
  private function hitungKerucut($request)
{
    Log::info('=== TaperroofController: hitungKerucut() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG KERUCUT TAPE ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA
    // ============================================================
    $produkAtap = null;
    if ($brandId) {
        $produkAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->first();
    }
    
    if ($produkAtap) {
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        $qty = ceil($luasDenganWaste / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // STARTER dari aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            if (in_array($aksesoris->id, $processedProductIds)) {
                continue;
            }
            
            $areaSlug = $aksesoris->area->slug ?? '';
            $areaName = $aksesoris->area->nama_area ?? '';
            
            if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                    $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                    $qty = ceil($qtyRaw);
                    $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari panjang_jurai)
    // ============================================================
    if ($panjangJurai > 0) {
        $juraiProduct = null;
        if ($brandId) {
            $juraiProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'jurai');
                })
                ->with('unit')
                ->first();
        }
        
        if ($juraiProduct && !in_array($juraiProduct->id, $processedProductIds)) {
            $satuan = $juraiProduct->satuan_terkecil ?? 1;
            $qtyRaw = $panjangJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($juraiProduct, $qty, 'Jurai', $panjangJurai);
            $processedProductIds[] = $juraiProduct->id;
        } else {
            // FALLBACK
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Jurai TAPE ROOF',
                'area' => 'Jurai',
                'qty' => ceil($panjangJurai + ($panjangJurai * $waste)),
                'satuan' => 'm',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $panjangJurai . ' m'
            ];
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP - TIDAK ADA DI KERUCUT (SKIP)
    // ============================================================
    // Kerucut tidak pakai Nok Tutup
    
    // ============================================================
    // 4. UNDERLAYER
    // ============================================================
    if ($brandId) {
        $underlayer = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'underlayer');
            })
            ->with('unit')
            ->first();
        
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
    // 5. METAL FLASHING
    // ============================================================
    if ($brandId && $panjangFlashing > 0) {
        $metalFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'metal-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
            $satuan = $metalFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $panjangFlashing / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
            $processedProductIds[] = $metalFlashing->id;
        }
    }
    
    // ============================================================
    // 6. WALL FLASHING
    // ============================================================
    if ($brandId && $opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'wall-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $satuan = $wallFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $opsiDinding / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============================================================
    // 7. CEROBONG ASAP
    // ============================================================
    if ($opsiCerobong > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Cerobong Asap',
            'area' => 'Cerobong Asap',
            'qty' => $opsiCerobong,
            'satuan' => 'unit',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiCerobong . ' unit'
        ];
    }
    
    // ============================================================
    // 8. PENANGKAL PETIR
    // ============================================================
    if ($opsiPenangkal > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Penangkal Petir',
            'area' => 'Penangkal Petir',
            'qty' => $opsiPenangkal,
            'satuan' => 'meter',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiPenangkal . ' meter'
        ];
    }
    
    // ============================================================
    // 9. LANTAI KERJA
    // ============================================================
    $luasPerLembar = 2.88;
    $qtyRaw = $luasAtap / $luasPerLembar;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    $results[] = [
        'product_id' => null,
        'produk_id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 10. PAKU & SCREW (untuk atap)
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    if ($qtyAtapUtama > 0) {
        $screwProduct = null;
        if ($brandId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'paku-screw');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwProduct->satuan_terkecil ?? 1;
        $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
        $satuanText = $screwProduct->unit->unit_name ?? 'pcs';
        $harga = $screwProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 11. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // ============================================================
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }
    
    if ($qtyPlywood > 0) {
        $screwPlywoodProduct = null;
        if ($brandId) {
            $screwPlywoodProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'screw-plywood');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
        $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
        $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
        $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
        $satuanText = $screwPlywoodProduct->unit->nama_unit ?? 'pcs';
        $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL KERUCUT TAPE ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area')
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

    /**
     * ============================================================
     * HITUNG ATAP DOME - TAPERROOF
     * ============================================================
     */
private function hitungDome($request)
{
    Log::info('=== TaperroofController: hitungDome() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG DOME TAPE ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA
    // ============================================================
    $produkAtap = null;
    if ($brandId) {
        $produkAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'accessories.unit', 'accessories.area'])
            ->first();
    }
    
    if ($produkAtap) {
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        $qty = ceil($luasDenganWaste / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // STARTER dari aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            if (in_array($aksesoris->id, $processedProductIds)) {
                continue;
            }
            
            $areaSlug = $aksesoris->area->slug ?? '';
            $areaName = $aksesoris->area->nama_area ?? '';
            
            if ($areaSlug == 'taperoof-starter' || $areaName == 'Starter') {
                if ($luasAtap > 0 && $aksesoris->satuan_terkecil > 0) {
                    $qtyRaw = ($luasAtap / $aksesoris->satuan_terkecil) / 24;
                    $qty = ceil($qtyRaw);
                    $results[] = $this->formatResult($aksesoris, $qty, 'Starter', $luasAtap);
                    $processedProductIds[] = $aksesoris->id;
                }
            }
        }
    }
    
    // ============================================================
    // 2. JURAI - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Jurai
    
    // ============================================================
    // 3. NOK TUTUP - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Nok Tutup
    
    // ============================================================
    // 4. TOPCAP - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Topcap
    
    // ============================================================
    // 5. UNDERLAYER
    // ============================================================
    if ($brandId) {
        $underlayer = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'underlayer');
            })
            ->with('unit')
            ->first();
        
        if ($underlayer && !in_array($underlayer->id, $processedProductIds)) {
            $satuan = $underlayer->satuan_terkecil ?? 1;
            $qtyRaw = $luasAtap / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
        }
    }
    
    // ============================================================
    // 6. METAL FLASHING
    // ============================================================
    if ($brandId && $panjangFlashing > 0) {
        $metalFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'metal-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($metalFlashing && !in_array($metalFlashing->id, $processedProductIds)) {
            $satuan = $metalFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $panjangFlashing / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($metalFlashing, $qty, 'Metal Flashing', $panjangFlashing);
            $processedProductIds[] = $metalFlashing->id;
        }
    }
    
    // ============================================================
    // 7. WALL FLASHING
    // ============================================================
    if ($brandId && $opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'wall-flashing');
            })
            ->with('unit')
            ->first();
        
        if ($wallFlashing && !in_array($wallFlashing->id, $processedProductIds)) {
            $satuan = $wallFlashing->satuan_terkecil ?? 1;
            $qtyRaw = $opsiDinding / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
            $processedProductIds[] = $wallFlashing->id;
        }
    }
    
    // ============================================================
    // 8. CEROBONG ASAP
    // ============================================================
    if ($opsiCerobong > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Cerobong Asap',
            'area' => 'Cerobong Asap',
            'qty' => $opsiCerobong,
            'satuan' => 'unit',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiCerobong . ' unit'
        ];
    }
    
    // ============================================================
    // 9. PENANGKAL PETIR
    // ============================================================
    if ($opsiPenangkal > 0) {
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'nama_produk' => 'Penangkal Petir',
            'area' => 'Penangkal Petir',
            'qty' => $opsiPenangkal,
            'satuan' => 'meter',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $opsiPenangkal . ' meter'
        ];
    }
    
    // ============================================================
    // 10. LANTAI KERJA
    // ============================================================
    $luasPerLembar = 2.88;
    $qtyRaw = $luasAtap / $luasPerLembar;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    $results[] = [
        'product_id' => null,
        'produk_id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 11. PAKU & SCREW (untuk atap)
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    if ($qtyAtapUtama > 0) {
        $screwProduct = null;
        if ($brandId) {
            $screwProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'paku-screw');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwProduct->satuan_terkecil ?? 1;
        $qtyScrewRaw = ($qtyAtapUtama * 53) / $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $namaProduk = $screwProduct->nama_produk ?? 'Paku & Screw';
        $satuanText = $screwProduct->unit->unit_name ?? 'pcs';
        $harga = $screwProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 12. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // ============================================================
    $qtyPlywood = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Lantai Kerja') {
            $qtyPlywood = $result['qty'];
            break;
        }
    }
    
    if ($qtyPlywood > 0) {
        $screwPlywoodProduct = null;
        if ($brandId) {
            $screwPlywoodProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'screw-plywood');
                })
                ->with('unit')
                ->first();
        }
        
        $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
        $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
        $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
        $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
        $satuanText = $screwPlywoodProduct->unit->nama_unit ?? 'box';
        $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => $satuanText,
            'harga_satuan' => $harga,
            'total_harga' => $harga * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL DOME TAPE ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area')
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
   public function exportPdf(Request $request, $model)
{
    Log::info('=== TaperroofController: exportPdf() === Model: ' . $model);
    
    $data = $request->all();
    
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    $data['model'] = $model;
    $data['tanggal'] = now()->format('d/m/Y');
    
    try {
        $results = $data['results'] ?? [];
        
        $uniqueResults = [];
        $seenIds = [];
        
        foreach ($results as $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
            // GENERATE UNIQUE ID UNTUK ITEM YANG PRODUCT_ID = NULL
            if (!$produkId) {
                // Pake nama_produk + area sebagai unique identifier
                $produkId = 'manual_' . ($item['area'] ?? '') . '_' . ($item['nama_produk'] ?? '');
            }
            
            if (in_array($produkId, $seenIds)) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        $data['results'] = $uniqueResults;
        
        // SIMPAN KE DATABASE - HANYA YANG PUNYA PRODUCT_ID ASLI
        if (!empty($uniqueResults)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            foreach ($uniqueResults as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                // HANYA SIMPAN YANG PUNYA PRODUCT_ID ASLI
                if ($produkId && is_numeric($produkId) && $qty > 0) {
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
        }
        
    } catch (\Exception $e) {
        Log::error('Error: ' . $e->getMessage());
    }
    
    $viewMap = [
        'pelana' => 'boq.taperoof.pdf-taperoof-pelana',
        'limasan' => 'boq.taperoof.pdf-taperoof-limasan',
        'piramid' => 'boq.taperoof.pdf-taperoof-piramid',
        'satu-kemiringan' => 'boq.taperoof.pdf-taperoof-satu-kemiringan',
        'kerucut' => 'boq.taperoof.pdf-taperoof-kerucut',
        'dome' => 'boq.taperoof.pdf-taperoof-dome',
    ];
    
    $view = $viewMap[$model] ?? 'boq.taperoof.pdf-taperoof-pelana';
    
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