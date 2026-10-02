<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaharoofController extends Controller
{
    /**
     * Display BOQ Taperroof page berdasarkan model
     */
public function index($model)
{
    Log::info('=== TaperroofController: index() === Model: ' . $model);
    
    // Ambil brand sebagai OBJECT
    $brand = ProductBrand::where('id', '22')->first();
    
    if (!$brand) {
        Log::error('Brand MASTER ROOF tidak ditemukan');
        $brand = ProductBrand::first();
    }
    
    // Ambil BRAND ID untuk digunakan di query
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) { // Gunakan $brandId, bukan $brand
        $jenisAtapOptions = Product::where('brand_id', $brandId) // <-- Perbaikan di sini
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK
    // ============================================================
    $areaNok = ProductArea::where('id', '83')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) { // Gunakan $brandId
        $nokOptions = Product::where('brand_id', $brandId) // <-- Perbaikan di sini
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK 3 ARAH
    // ============================================================
    $areaNok3Arah = ProductArea::where('id', '85')->first();
    $nok3ArahOptions = collect();
    if ($areaNok3Arah && $brandId) { // Gunakan $brandId
        $nok3ArahOptions = Product::where('brand_id', $brandId) // <-- Perbaikan di sini
            ->where('area_id', $areaNok3Arah->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('id', '84')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) { // Gunakan $brandId
        $nokTutupOptions = Product::where('brand_id', $brandId) // <-- Perbaikan di sini
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 5. BUILD MAPPING
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipeKode = $nok->productTipe->kode_tipe ?? 'U';
        
        // Cari Nok 3 Arah dengan product_tipe_id yang sama
        $nok3Arah = $nok3ArahOptions->firstWhere('product_tipe_id', $tipeId);
        // Cari Nok Tutup dengan product_tipe_id yang sama
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipeKode,
            'productTipeId' => $tipeId,
            'nok3ArahId' => $nok3Arah->id ?? null,
            'nokTutupId' => $nokTutup->id ?? null,
            'nok3ArahName' => $nok3Arah->nama_produk ?? '',
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    // ============================================================
    // 6. VIEW
    // ============================================================
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    $viewMap = [
        'pelana' => 'boq.maharoof.maharoof-pelana',
        'limasan' => 'boq.maharoof.maharoof-limasan',
        'piramid' => 'boq.maharoof.maharoof-piramid',
        'satu-kemiringan' => 'boq.maharoof.maharoof-satu-kemiringan',
        'kerucut' => 'boq.maharoof.maharoof-kerucut',
        'dome' => 'boq.maharoof.maharoof-dome', 
    ];

    $view = $viewMap[$model] ?? 'boq.maharoof.maharoof-pelana';
    
    return view($view, compact(
        'jenisAtapOptions',
        'nokOptions',
        'nok3ArahOptions',
        'nokTutupOptions',
        'nokMapping',
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
    Log::info('=== FlexiroofController: hitungPelana() ===');
    
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
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first();
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // AUTO-SELECT NOK TUTUP BERDASARKAN PRODUCT_TIPE_ID
    // ============================================================
    $productTipeId = null;
    $productTipeKode = null;
    
    if ($nokId) {
        $nokProduct = Product::with('productTipe')->find($nokId);
        if ($nokProduct) {
            $productTipeId = $nokProduct->product_tipe_id;
            $productTipeKode = $nokProduct->productTipe->kode_tipe ?? 'U';
            
            Log::info('PRODUCT TIPE DARI PRODUK NOK:', [
                'nokId' => $nokId,
                'productTipeId' => $productTipeId,
                'productTipeKode' => $productTipeKode,
                'nama' => $nokProduct->nama_produk
            ]);
        }
    }
    
    if ($productTipeId && !$nokTutupId) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->where('product_tipe_id', $productTipeId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup) {
            $nokTutupId = $nokTutup->id;
            Log::info('AUTO-SELECT NOK TUTUP:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $productTipeId
            ]);
        } else {
            Log::warning('NOK TUTUP TIDAK DITEMUKAN UNTUK PRODUCT TIPE ID:', ['product_tipe_id' => $productTipeId]);
        }
    }
    
    Log::info('HITUNG PELANA FLEXI ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNok' => $panjangNok,
        'panjangFlashing' => $panjangFlashing,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'nokTutupId' => $nokTutupId,
        'productTipeId' => $productTipeId,
        'productTipeKode' => $productTipeKode,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - FLEXI ROOF
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK (tetap TAPE ROOF NOK, tidak diganti)
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
    // 3. NOK TUTUP
    // ============================================================
    if ($nokTutupId && $panjangNok > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNok);
            $processedProductIds[] = $nokTutup->id;
            
            Log::info('NOK TUTUP DIGUNAKAN:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $nokTutup->product_tipe_id
            ]);
        }
    } else {
        Log::warning('NOK TUTUP TIDAK DIGUNAKAN:', [
            'nokTutupId' => $nokTutupId,
            'panjangNok' => $panjangNok
        ]);
    }
    
    // ============================================================
    // 4. WALL FLASHING - FLEXI ROOF
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
    // 5. FLASHING KACA - FLEXI ROOF
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
    // 6. CEROBONG ASAP
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
    // 7. PENANGKAL PETIR
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
// 8. INSULASI - AKSESORIS (MAHAROOF)
// ============================================================
if ($insulasiAktif && $luasAtap > 0 && $brandId) {
    // Ambil SEMUA produk insulasi (bisa lebih dari 1)
    $insulasiProducts = Product::where('brand_id', $brandId)
        ->whereHas('area', function($q) {
            $q->where('slug', 'insulasi');
        })
        ->with('unit')
        ->get(); // <-- gunakan get() bukan first()
    
    if ($insulasiProducts->isNotEmpty()) {
        foreach ($insulasiProducts as $insulasiProduct) {
            // Cek apakah produk sudah diproses
            if (in_array($insulasiProduct->id, $processedProductIds)) {
                continue;
            }
            
            $satuan = $insulasiProduct->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            $qty = ceil($luasDenganWaste / $satuan);
            
            $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
            $processedProductIds[] = $insulasiProduct->id;
            
            Log::info('INSULASI DITAMBAHKAN (AKSESORIS):', [
                'product_id' => $insulasiProduct->id,
                'nama_produk' => $insulasiProduct->nama_produk,
                'qty' => $qty,
                'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                'luas_atap' => $luasAtap,
                'waste' => $waste
            ]);
        }
    } else {
        Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
    }
}
    
    // // ============================================================
    // // 9. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
    // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    // // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA FLEXI ROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
    ]);
    
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
    Log::info('=== MaharoofController: hitungLimasan() ===');
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // TAMBAHAN: Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nok3ArahId = $request->nok_3_arah_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first(); // MAHAROOF (ID 22)
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // AUTO-SELECT NOK 3 ARAH & NOK TUTUP BERDASARKAN PRODUCT_TIPE_ID
    // ============================================================
    $productTipeId = null;
    $productTipeKode = null;
    
    if ($nokId) {
        $nokProduct = Product::with('productTipe')->find($nokId);
        if ($nokProduct) {
            $productTipeId = $nokProduct->product_tipe_id;
            $productTipeKode = $nokProduct->productTipe->kode_tipe ?? 'U';
            
            Log::info('PRODUCT TIPE DARI PRODUK:', [
                'nokId' => $nokId,
                'productTipeId' => $productTipeId,
                'productTipeKode' => $productTipeKode,
                'nama' => $nokProduct->nama_produk
            ]);
        }
    }
    
    if ($productTipeId && !$nok3ArahId) {
        $nok3Arah = Product::where('brand_id', $brandId)
            ->where('product_tipe_id', $productTipeId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-3-arah');
            })
            ->with('unit')
            ->first();
        
        if ($nok3Arah) {
            $nok3ArahId = $nok3Arah->id;
            Log::info('AUTO-SELECT NOK 3 ARAH:', [
                'id' => $nok3Arah->id,
                'nama' => $nok3Arah->nama_produk,
                'product_tipe_id' => $productTipeId
            ]);
        }
    }
    
    if ($productTipeId && !$nokTutupId) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->where('product_tipe_id', $productTipeId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup) {
            $nokTutupId = $nokTutup->id;
            Log::info('AUTO-SELECT NOK TUTUP:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $productTipeId
            ]);
        }
    }
    
    Log::info('HITUNG LIMASAN MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNokJurai' => $panjangNokJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'nok3ArahId' => $nok3ArahId,
        'nokTutupId' => $nokTutupId,
        'productTipeId' => $productTipeId,
        'productTipeKode' => $productTipeKode,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - MAHAROOF
    // ============================================================
    $produkAtap = null;
    if ($jenisAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])
            ->find($jenisAtapId);
    }
    
    if (!$produkAtap && $brandId) {
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. STARTER - MAHAROOF
    // ============================================================
    if ($brandId && $panjangStarter > 0) {
        $starter = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'taperoof-starter');
            })
            ->with('unit')
            ->first();
        
        if ($starter && !in_array($starter->id, $processedProductIds)) {
            $satuan = $starter->satuan_terkecil ?? 1;
            $qtyRaw = $panjangStarter / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            $processedProductIds[] = $starter->id;
        }
    }
    
    // ============================================================
    // 3. TAPE ROOF NOK (dari dropdown)
    // ============================================================
    if ($nokId && $panjangNokJurai > 0) {
        $nok = Product::with('unit')->find($nokId);
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangNokJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Nok dan Jurai', $panjangNokJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 4. NOK TUTUP
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 5. NOK 3 ARAH
    // ============================================================
    if ($nok3ArahId && $panjangNokJurai > 0) {
        $nok3Arah = Product::with(['unit', 'productTipe'])->find($nok3ArahId);
        if ($nok3Arah && !in_array($nok3Arah->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nok3Arah, $qty, 'Nok 3 Arah', $panjangNokJurai);
            $processedProductIds[] = $nok3Arah->id;
        }
    }
    
    // ============================================================
    // 6. UNDERLAYER - MAHAROOF
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
    // 7. METAL FLASHING - MAHAROOF
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
    // 8. WALL FLASHING - MAHAROOF
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
    // 9. FLASHING KACA - MAHAROOF
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
    // 10. CEROBONG ASAP
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
    // 11. PENANGKAL PETIR
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
    // 12. INSULASI - AKSESORIS (MAHAROOF)
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        // Ambil SEMUA produk insulasi (bisa lebih dari 1)
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'insulasi');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
                $qty = ceil($luasDenganWaste / $satuan);
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMASAN:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'waste' => $waste
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // // ============================================================
    // // 13. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
     // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    
    //   // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN MAHAROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
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
    Log::info('=== MaharoofController: hitungPiramid() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first(); // MAHAROOF (ID 22)
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // AUTO-SELECT NOK TUTUP BERDASARKAN PRODUCT_TIPE_ID
    // ============================================================
    $productTipeId = null;
    $productTipeKode = null;
    
    if ($nokId) {
        $nokProduct = Product::with('productTipe')->find($nokId);
        if ($nokProduct) {
            $productTipeId = $nokProduct->product_tipe_id;
            $productTipeKode = $nokProduct->productTipe->kode_tipe ?? 'U';
            
            Log::info('PRODUCT TIPE DARI PRODUK NOK:', [
                'nokId' => $nokId,
                'productTipeId' => $productTipeId,
                'productTipeKode' => $productTipeKode,
                'nama' => $nokProduct->nama_produk
            ]);
        }
    }
    
    if ($productTipeId && !$nokTutupId) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->where('product_tipe_id', $productTipeId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup) {
            $nokTutupId = $nokTutup->id;
            Log::info('AUTO-SELECT NOK TUTUP:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $productTipeId
            ]);
        } else {
            Log::warning('NOK TUTUP TIDAK DITEMUKAN UNTUK PRODUCT TIPE ID:', ['product_tipe_id' => $productTipeId]);
        }
    }
    
    Log::info('HITUNG PIRAMID MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'nokTutupId' => $nokTutupId,
        'productTipeId' => $productTipeId,
        'productTipeKode' => $productTipeKode,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - MAHAROOF
    // ============================================================
    $produkAtap = null;
    if ($jenisAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])
            ->find($jenisAtapId);
    }
    
    if (!$produkAtap && $brandId) {
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. STARTER - MAHAROOF
    // ============================================================
    if ($brandId && $panjangStarter > 0) {
        $starter = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'taperoof-starter');
            })
            ->with('unit')
            ->first();
        
        if ($starter && !in_array($starter->id, $processedProductIds)) {
            $satuan = $starter->satuan_terkecil ?? 1;
            $qtyRaw = $panjangStarter / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            $processedProductIds[] = $starter->id;
        }
    }
    
    // ============================================================
    // 3. TAPE ROOF NOK & JURAI (dari dropdown)
    // ============================================================
    if ($nokId && $panjangJurai > 0) {
        $nok = Product::with('unit')->find($nokId);
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Nok dan Jurai', $panjangJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 4. NOK TUTUP
    // ============================================================
    if ($nokTutupId && $panjangJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangJurai);
            $processedProductIds[] = $nokTutup->id;
            
            Log::info('NOK TUTUP DIGUNAKAN:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $nokTutup->product_tipe_id
            ]);
        }
    } else {
        Log::warning('NOK TUTUP TIDAK DIGUNAKAN:', [
            'nokTutupId' => $nokTutupId,
            'panjangJurai' => $panjangJurai
        ]);
    }
    
    // ============================================================
    // 5. UNDERLAYER - MAHAROOF
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
    // 6. METAL FLASHING - MAHAROOF
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
    // 7. WALL FLASHING - MAHAROOF
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
    // 8. FLASHING KACA - MAHAROOF
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
    // 9. CEROBONG ASAP
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
    // 10. PENANGKAL PETIR
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
    // 11. INSULASI - AKSESORIS (MAHAROOF)
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        // Ambil SEMUA produk insulasi (bisa lebih dari 1)
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'insulasi');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
                $qty = ceil($luasDenganWaste / $satuan);
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PIRAMID:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'waste' => $waste
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // // ============================================================
    // // 12. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
      // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    
    //   // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PIRAMID MAHAROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
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
    Log::info('=== MaharoofController: hitungSatuKemiringan() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first(); // MAHAROOF (ID 22)
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG SATU KEMIRINGAN MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - MAHAROOF
    // ============================================================
    $produkAtap = null;
    if ($jenisAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])
            ->find($jenisAtapId);
    }
    
    if (!$produkAtap && $brandId) {
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. STARTER - MAHAROOF
    // ============================================================
    if ($brandId && $panjangStarter > 0) {
        $starter = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'taperoof-starter');
            })
            ->with('unit')
            ->first();
        
        if ($starter && !in_array($starter->id, $processedProductIds)) {
            $satuan = $starter->satuan_terkecil ?? 1;
            $qtyRaw = $panjangStarter / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            $processedProductIds[] = $starter->id;
        }
    }
    
    // ============================================================
    // 3. TAPE ROOF NOK - TIDAK ADA DI SATU KEMIRINGAN (SKIP)
    // ============================================================
    // Satu kemiringan tidak pakai Nok
    
    // ============================================================
    // 4. NOK TUTUP - TIDAK ADA DI SATU KEMIRINGAN (SKIP)
    // ============================================================
    // Satu kemiringan tidak pakai Nok Tutup
    
    // ============================================================
    // 5. UNDERLAYER - MAHAROOF
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
    // 6. METAL FLASHING - MAHAROOF
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
    // 7. WALL FLASHING - MAHAROOF
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
    // 8. FLASHING KACA - MAHAROOF
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
    // 9. CEROBONG ASAP
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
    // 10. PENANGKAL PETIR
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
    // 11. INSULASI - AKSESORIS (MAHAROOF)
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        // Ambil SEMUA produk insulasi (bisa lebih dari 1)
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'insulasi');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
                $qty = ceil($luasDenganWaste * $satuan);
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - SATU KEMIRINGAN:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'waste' => $waste
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // // ============================================================
    // // 12. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
     // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    //  // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL SATU KEMIRINGAN MAHAROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
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
    Log::info('=== MaharoofController: hitungKerucut() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first(); // MAHAROOF (ID 22)
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG KERUCUT MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - MAHAROOF
    // ============================================================
    $produkAtap = null;
    if ($jenisAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])
            ->find($jenisAtapId);
    }
    
    if (!$produkAtap && $brandId) {
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. STARTER - MAHAROOF
    // ============================================================
    if ($brandId && $panjangStarter > 0) {
        $starter = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'taperoof-starter');
            })
            ->with('unit')
            ->first();
        
        if ($starter && !in_array($starter->id, $processedProductIds)) {
            $satuan = $starter->satuan_terkecil ?? 1;
            $qtyRaw = $panjangStarter / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            $processedProductIds[] = $starter->id;
        }
    }
    
    // ============================================================
    // 3. JURAI - KERUCUT TIDAK PAKAI JURAI (SKIP)
    // ============================================================
    // Kerucut tidak menggunakan jurai karena bentuknya melingkar
    
    // ============================================================
    // 4. NOK TUTUP - TIDAK ADA DI KERUCUT (SKIP)
    // ============================================================
    // Kerucut tidak pakai Nok Tutup
    
    // ============================================================
    // 5. UNDERLAYER - MAHAROOF
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
    // 6. METAL FLASHING - MAHAROOF
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
    // 7. WALL FLASHING - MAHAROOF
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
    // 8. FLASHING KACA - MAHAROOF
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
    // 9. CEROBONG ASAP
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
    // 10. PENANGKAL PETIR
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
    // 11. INSULASI - AKSESORIS (MAHAROOF)
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        // Ambil SEMUA produk insulasi (bisa lebih dari 1)
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'insulasi');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
                $qty = ceil($luasDenganWaste / $satuan);
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - KERUCUT:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'waste' => $waste
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // // ============================================================
    // // 12. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
      // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    // // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL KERUCUT MAHAROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
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
    Log::info('=== MaharoofController: hitungDome() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '22')->first(); // MAHAROOF (ID 22)
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG DOME MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiKaca' => $opsiKaca,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
    ]);
    
    $results = [];
    $processedProductIds = [];
    
    // ============================================================
    // 1. ATAP UTAMA - MAHAROOF
    // ============================================================
    $produkAtap = null;
    if ($jenisAtapId) {
        $produkAtap = Product::with(['unit', 'accessories.unit', 'accessories.area'])
            ->find($jenisAtapId);
    }
    
    if (!$produkAtap && $brandId) {
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
        $qty = ceil($luasDenganWaste * $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. STARTER - MAHAROOF
    // ============================================================
    if ($brandId && $panjangStarter > 0) {
        $starter = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'taperoof-starter');
            })
            ->with('unit')
            ->first();
        
        if ($starter && !in_array($starter->id, $processedProductIds)) {
            $satuan = $starter->satuan_terkecil ?? 1;
            $qtyRaw = $panjangStarter / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            $processedProductIds[] = $starter->id;
        }
    }
    
    // ============================================================
    // 3. JURAI - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Jurai
    
    // ============================================================
    // 4. NOK TUTUP - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Nok Tutup
    
    // ============================================================
    // 5. TOPCAP - TIDAK ADA DI DOME (SKIP)
    // ============================================================
    // Dome tidak pakai Topcap
    
    // ============================================================
    // 6. UNDERLAYER - MAHAROOF
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
    // 7. METAL FLASHING - MAHAROOF
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
    // 8. WALL FLASHING - MAHAROOF
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
    // 9. FLASHING KACA - MAHAROOF
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
    // 10. CEROBONG ASAP
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
    // 11. PENANGKAL PETIR
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
    // 12. INSULASI - AKSESORIS (MAHAROOF)
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        // Ambil SEMUA produk insulasi (bisa lebih dari 1)
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'insulasi');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
                $qty = ceil($luasDenganWaste / $satuan);
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - DOME:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'waste' => $waste
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // // ============================================================
    // // 13. LANTAI KERJA
    // // ============================================================
    // $luasPerLembar = 2.88;
    // $qtyRaw = $luasAtap / $luasPerLembar;
    // $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    
    // $results[] = [
    //     'product_id' => null,
    //     'produk_id' => null,
    //     'nama_produk' => $lantaiKerja,
    //     'area' => 'Lantai Kerja',
    //     'qty' => $qtyPlywood,
    //     'satuan' => 'lembar',
    //     'harga_satuan' => 0,
    //     'total_harga' => 0,
    //     'parameter' => $luasAtap . ' m²'
    // ];
    
       // ============================================================
    // 10. PAKU & SCREW - Berdasarkan Jenis Rangka
    // ============================================================
    $qtyAtapUtama = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Atap Utama') {
            $qtyAtapUtama = $result['qty'];
            break;
        }
    }
    
    // Ambil panjang nok dari request atau hasil perhitungan
    $panjangNok = $request->panjang_nok ?? 0;
    
    if ($qtyAtapUtama > 0) {
        // Tentukan ID screw berdasarkan rangka
        $screwIds = [];
        if (in_array($rangka, ['Kayu', 'Baja Ringan'])) {
            $screwIds = [362, 363]; // Paku & Screw untuk kayu/baja ringan
        } elseif (in_array($rangka, ['Baja Berat', 'Beton'])) {
            $screwIds = [364, 365]; // Paku & Screw untuk baja berat/beton
        }
        
        foreach ($screwIds as $screwId) {
            $screwProduct = Product::with('unit')->find($screwId);
            
            if ($screwProduct) {
                $satuan = $screwProduct->satuan_terkecil ?? 1;
                
                // ID 344 dan 346: berdasarkan QTY Atap Utama (6 screw per lembar)
                // ID 345 dan 347: berdasarkan Panjang Nok
                if (in_array($screwId, [362, 364])) {
                    // Paku & Screw untuk atap utama
                    $qtyScrewRaw = ($qtyAtapUtama) / $satuan;
                    $parameter = $qtyAtapUtama . ' lembar atap';
                } else {
                    // Paku & Screw untuk nok (ID 345 dan 347)
                    $qtyScrewRaw = $panjangNok * $satuan;
                    $parameter = $panjangNok . ' meter nok';
                }
                
                $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
                
                $results[] = [
                    'product_id' => $screwProduct->id,
                    'produk_id' => $screwProduct->id,
                    'nama_produk' => $screwProduct->nama_produk,
                    'area' => 'Paku & Screw',
                    'qty' => $qtyScrew,
                    'satuan' => $screwProduct->unit->unit_name ?? 'pcs',
                    'harga_satuan' => $screwProduct->harga_jual ?? 0,
                    'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                    'parameter' => $parameter
                ];
            }
        }
    }
    
    //  // ============================================================
    // // 13. SCREW PLYWOOD (untuk plywood/lantai kerja)
    // // ============================================================
    // $qtyPlywood = 0;
    // foreach ($results as $result) {
    //     if ($result['area'] == 'Lantai Kerja') {
    //         $qtyPlywood = $result['qty'];
    //         break;
    //     }
    // }
    
    // if ($qtyPlywood > 0) {
    //     $screwPlywoodProduct = null;
    //     if ($brandId) {
    //         $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //             ->whereHas('area', function($q) {
    //                 $q->where('slug', 'screw-plywood');
    //             })
    //             ->with('unit')
    //             ->first();
    //     }
        
    //     $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //     $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //     $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
        
    //     $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //     $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //     $harga = $screwPlywoodProduct->harga_jual ?? 0;
        
    //     $results[] = [
    //         'product_id' => $screwPlywoodProduct->id ?? null,
    //         'produk_id' => $screwPlywoodProduct->id ?? null,
    //         'nama_produk' => $namaProduk,
    //         'area' => 'Screw Plywood',
    //         'qty' => $qtyScrewPlywood,
    //         'satuan' => $satuanText,
    //         'harga_satuan' => $harga,
    //         'total_harga' => $harga * $qtyScrewPlywood,
    //         'parameter' => $qtyPlywood . ' lembar plywood'
    //     ];
    // }
    // ============================================================
    // GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL DOME MAHAROOF:', [
        'total_items' => count($results),
        'grand_total' => $grandTotal,
        'areas' => array_column($results, 'area'),
        'insulasi_status' => $insulasiAktif ? 'AKTIF' : 'NONAKTIF'
    ]);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function exportPdf(Request $request, $model)
{
    Log::info('=== FlexiroofController: exportPdf() === Model: ' . $model);
    
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
                $produkId = 'manual_' . ($item['area'] ?? '') . '_' . ($item['nama_produk'] ?? '');
            }
            
            if (in_array($produkId, $seenIds)) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        $data['results'] = $uniqueResults;
        
        // SIMPAN KE DATABASE - HANYA YANG PUNYA PRODUCT_ID ASLI & BRAND FLEXI ROOF
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
                    $produk = \App\Models\Product::with('brand')->find($produkId);
                    
                    // CEK BRAND FLEXI ROOF
                    if ($produk && $produk->brand && $produk->brand->nama_brand === 'FLEXI ROOF') {
                        \DB::table('detail_boq')->insert([
                            'boq_id' => $boq->id,
                            'produk_id' => $produkId,
                            'kode_produk' => $produk->kode_produk,
                            'qty' => $qty,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }
        
    } catch (\Exception $e) {
        Log::error('Error export PDF FLEXI ROOF: ' . $e->getMessage());
    }
    
    $viewMap = [
        'pelana' => 'boq.maharoof.pdf-maharoof-pelana',
        'limasan' => 'boq.maharoof.pdf-maharoof-limasan',
        'piramid' => 'boq.maharoof.pdf-maharoof-piramid',
        'satu-kemiringan' => 'boq.maharoof.pdf-maharoof-satu-kemiringan',
        'kerucut' => 'boq.maharoof.pdf-maharoof-kerucut',
        'dome' => 'boq.maharoof.pdf-maharoof-dome',
    ];
    
    $view = $viewMap[$model] ?? 'boq.maharoof.pdf-maharoof-pelana';
    
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