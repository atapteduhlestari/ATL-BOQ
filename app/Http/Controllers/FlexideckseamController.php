<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlexideckseamController extends Controller
{
    /**
     * Display BOQ Taperroof page berdasarkan model
     */
public function index($model)
{
    Log::info('=== TaperroofController: index() === Model: ' . $model);
    
    // Ambil brand sebagai OBJECT
    $brand = ProductBrand::where('id', '24')->first();
    
    if (!$brand) {
        Log::error('Brand MASTER ROOF tidak ditemukan');
        $brand = ProductBrand::first();
    }
    
    // Ambil BRAND ID untuk digunakan di query
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // VIEW
    // ============================================================
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    
    $viewMap = [
        'pelana' => 'boq.flexideckseam.flexideckseam-pelana',
        'limasan' => 'boq.flexideckseam.flexideckseam-limasan',
        'piramid' => 'boq.flexideckseam.flexideckseam-piramid',
        'satu-kemiringan' => 'boq.flexideckseam.flexideckseam-satu-kemiringan',
        'kerucut' => 'boq.flexideckseam.flexideckseam-kerucut',
        'dome' => 'boq.flexideckseam.flexideckseam-dome', 
    ];

    $view = $viewMap[$model] ?? 'boq.maharoof.maharoof-pelana';
    
    return view($view, compact(
        'rangkaOptions',
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
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? $request->panjang_nok ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG PELANA FLEXI ROOF:', [
        'luasAtap' => $luasAtap,
        'panjangNokJurai' => $panjangNokJurai,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) / satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $nok = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '83');
            })
            ->with('unit')
            ->first();
        
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangNokJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Nok dan Jurai', $panjangNokJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 4. WALL FLASHING
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
    // 5. CEROBONG ASAP
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
    // 6. PENANGKAL PETIR
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
    // 7. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - PELANA:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - PELANA:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7c. LEM
    // Rumus: panjang nok × 2 / 5
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $lem = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '8');
            })
            ->with('unit')
            ->first();
        
        if ($lem && !in_array($lem->id, $processedProductIds)) {
            $satuan = $lem->satuan_terkecil ?? 1;
            $qtyRaw = ($panjangNokJurai * 2) / 5;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($lem, $qty, 'Lem', $panjangNokJurai);
            $processedProductIds[] = $lem->id;
            
            Log::info('LEM DITAMBAHKAN - PELANA:', [
                'product_id' => $lem->id,
                'nama_produk' => $lem->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $lem->unit->unit_name ?? 'pcs',
                'panjang_nok_jurai' => $panjangNokJurai,
                'rumus' => 'panjang_nok * 2 / 5'
            ]);
        } else {
            Log::warning('PRODUK LEM TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG LIMASAN MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangNokJurai' => $panjangNokJurai,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'nokId' => $nokId,
        'nokTutupId' => $nokTutupId,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) × satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
      // ============================================================
    // 2. TAPE ROOF NOK
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $nok = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '83');
            })
            ->with('unit')
            ->first();
        
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangNokJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Nok dan Jurai', $panjangNokJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
      // ============================================================
    // 3. NOK TUTUP
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 4. WALL FLASHING
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
    // 5. CEROBONG ASAP
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
    // 6. PENANGKAL PETIR
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
    // 7. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMASAN:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
      // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - LIMASAN:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

      // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - LIMASAN:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7c. LEM
    // Rumus: panjang nok × 2 / 5
    // ============================================================
    if ($brandId && $panjangNokJurai > 0) {
        $lem = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'lem');
            })
            ->with('unit')
            ->first();
        
        if ($lem && !in_array($lem->id, $processedProductIds)) {
            $satuan = $lem->satuan_terkecil ?? 1;
            $qtyRaw = ($panjangNokJurai * 2) / 5;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($lem, $qty, 'Lem', $panjangNokJurai);
            $processedProductIds[] = $lem->id;
            
            Log::info('LEM DITAMBAHKAN - LIMASAN:', [
                'product_id' => $lem->id,
                'nama_produk' => $lem->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $lem->unit->unit_name ?? 'pcs',
                'panjang_nok_jurai' => $panjangNokJurai,
                'rumus' => 'panjang_nok * 2 / 5'
            ]);
        } else {
            Log::warning('PRODUK LEM TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $panjangJurai = $request->panjang_jurai ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG PIRAMID MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'panjangJurai' => $panjangJurai,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) / satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. TAPE ROOF NOK & JURAI
    // ============================================================
    if ($brandId && $panjangJurai > 0) {
        $nok = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '83');
            })
            ->with('unit')
            ->first();
        
        if ($nok && !in_array($nok->id, $processedProductIds)) {
            $satuan = $nok->satuan_terkecil ?? 1;
            $qtyRaw = $panjangJurai / $satuan;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nok, $qty, 'Nok dan Jurai', $panjangJurai);
            $processedProductIds[] = $nok->id;
        }
    }
    
    // ============================================================
    // 3. NOK TUTUP
    // ============================================================
    if ($brandId && $panjangJurai > 0) {
        $nokTutup = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'nok-tutup');
            })
            ->with('unit')
            ->first();
        
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 4;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangJurai);
            $processedProductIds[] = $nokTutup->id;
        }
    }
    
    // ============================================================
    // 4. WALL FLASHING
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
    // 5. CEROBONG ASAP
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
    // 6. PENANGKAL PETIR
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
    // 7. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PIRAMID:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - PIRAMID:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - PIRAMID:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7c. LEM
    // Rumus: panjang jurai × 2 / 5
    // ============================================================
    if ($brandId && $panjangJurai > 0) {
        $lem = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'lem');
            })
            ->with('unit')
            ->first();
        
        if ($lem && !in_array($lem->id, $processedProductIds)) {
            $satuan = $lem->satuan_terkecil ?? 1;
            $qtyRaw = ($panjangJurai * 2) / 5;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($lem, $qty, 'Lem', $panjangJurai);
            $processedProductIds[] = $lem->id;
            
            Log::info('LEM DITAMBAHKAN - PIRAMID:', [
                'product_id' => $lem->id,
                'nama_produk' => $lem->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $lem->unit->unit_name ?? 'pcs',
                'panjang_jurai' => $panjangJurai,
                'rumus' => 'panjang_jurai * 2 / 5'
            ]);
        } else {
            Log::warning('PRODUK LEM TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG SATU KEMIRINGAN MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) / satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. WALL FLASHING
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
    // 3. CEROBONG ASAP
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
    // 4. PENANGKAL PETIR
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
    // 5. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - SATU KEMIRINGAN:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - SATU KEMIRINGAN:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - SATU KEMIRINGAN:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG KERUCUT MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) / satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. WALL FLASHING
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
    // 3. CEROBONG ASAP
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
    // 4. PENANGKAL PETIR
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
    // 5. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - KERUCUT:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - KERUCUT:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - KERUCUT:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
    
    // 1. AMBIL PARAMETER
    $luasAtap = $request->luas_atap ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    // Ambil jenis_atap_id dari request
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '24')->first();
    $brandId = $brand->id ?? null;
    
    Log::info('HITUNG DOME MAHAROOF:', [
        'luasAtap' => $luasAtap,
        'sudut' => $sudut,
        'jenisAtapId' => $jenisAtapId,
        'opsiDinding' => $opsiDinding,
        'opsiCerobong' => $opsiCerobong,
        'opsiPenangkal' => $opsiPenangkal,
        'brandId' => $brandId,
        'insulasiAktif' => $insulasiAktif
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
        
        // Rumus sementara: luas atap × (1 + waste) / satuan
        // TODO: Ganti dengan rumus final setelah ditentukan
        $qty = ceil($luasAtap * (1 + $waste) / $satuan);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
    }
    
    // ============================================================
    // 2. WALL FLASHING
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
    // 3. CEROBONG ASAP
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
    // 4. PENANGKAL PETIR
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
    // 5. INSULASI - AKSESORIS
    // ============================================================
    if ($insulasiAktif && $luasAtap > 0 && $brandId) {
        $insulasiProducts = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('id', '86');
            })
            ->with('unit')
            ->get();
        
        if ($insulasiProducts->isNotEmpty()) {
            foreach ($insulasiProducts as $insulasiProduct) {
                if (in_array($insulasiProduct->id, $processedProductIds)) {
                    continue;
                }
                
                $satuan = $insulasiProduct->satuan_terkecil ?? 1;
                
                // Tentukan pembagi berdasarkan ID produk
                if ($insulasiProduct->id == 438) {
                    $pembagi = 1.1;
                } elseif ($insulasiProduct->id == 439) {
                    $pembagi = 1.2;
                } else {
                    $pembagi = 1.1; // default
                }
                
                // Hitung qty raw (sebelum ceil)
                $qtyRaw = $luasAtap / ($pembagi * $satuan);
                $qty = ceil($qtyRaw);
                
                // Simpan qty raw insulasi 438 untuk perhitungan Solasi
                if ($insulasiProduct->id == 438) {
                    $qtyRawInsulasi438 = $qtyRaw;
                }
                
                $results[] = $this->formatResult($insulasiProduct, $qty, 'Insulasi', $luasAtap);
                $processedProductIds[] = $insulasiProduct->id;
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - DOME:', [
                    'product_id' => $insulasiProduct->id,
                    'nama_produk' => $insulasiProduct->nama_produk,
                    'qty_raw' => $qtyRaw,
                    'qty' => $qty,
                    'satuan' => $insulasiProduct->unit->unit_name ?? 'm²',
                    'luas_atap' => $luasAtap,
                    'pembagi' => $pembagi,
                    'rumus' => 'luas_atap / (pembagi * satuan_terkecil)'
                ]);
            }
        } else {
            Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }
    
    // ============================================================
    // 4b. DUDUKAN ATAP
    // ============================================================
    if ($brandId && $luasAtap > 0) {
        $dudukanAtap = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'dudukan-atap');
            })
            ->with('unit')
            ->first();
        
        if ($dudukanAtap && !in_array($dudukanAtap->id, $processedProductIds)) {
            $satuan = $dudukanAtap->satuan_terkecil ?? 1;
            $qty = ceil($luasAtap * $satuan);
            
            $results[] = $this->formatResult($dudukanAtap, $qty, 'Dudukan Atap', $luasAtap);
            $processedProductIds[] = $dudukanAtap->id;
            
            Log::info('DUDUKAN ATAP DITAMBAHKAN - DOME:', [
                'product_id' => $dudukanAtap->id,
                'nama_produk' => $dudukanAtap->nama_produk,
                'qty' => $qty,
                'satuan' => $dudukanAtap->unit->unit_name ?? 'pcs',
                'luas_atap' => $luasAtap,
                'rumus' => 'luas_atap * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK DUDUKAN ATAP TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 7b. SOLASI
    // Rumus: qty raw insulasi ID 438 × satuan terkecil
    // ============================================================
    if ($insulasiAktif && $brandId && $qtyRawInsulasi438 > 0) {
        $solasi = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'solasi');
            })
            ->with('unit')
            ->first();
        
        if ($solasi && !in_array($solasi->id, $processedProductIds)) {
            $satuan = $solasi->satuan_terkecil ?? 1;
            $qtyRaw = $qtyRawInsulasi438 * $satuan;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($solasi, $qty, 'Solasi', $qtyRawInsulasi438);
            $processedProductIds[] = $solasi->id;
            
            Log::info('SOLASI DITAMBAHKAN - DOME:', [
                'product_id' => $solasi->id,
                'nama_produk' => $solasi->nama_produk,
                'qty_raw' => $qtyRaw,
                'qty' => $qty,
                'satuan' => $solasi->unit->unit_name ?? 'pcs',
                'qty_raw_insulasi_438' => $qtyRawInsulasi438,
                'rumus' => 'qty_raw_insulasi_438 * satuan_terkecil'
            ]);
        } else {
            Log::warning('PRODUK SOLASI TIDAK DITEMUKAN UNTUK BRAND ID:', ['brand_id' => $brandId]);
        }
    }

    // ============================================================
    // 8. PAKU & SCREW
    // ============================================================
    // ============================================================
    // Screw Dudukan Atap (ID: 431)
    // Rumus: luas atap × satuan terkecil
    // ============================================================
    if ($luasAtap > 0) {
        $screw431 = Product::with('unit')->find(431);
        
        if ($screw431) {
            $satuan = $screw431->satuan_terkecil ?? 1;
            $qtyScrew = ceil($luasAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw431->id,
                'produk_id'     => $screw431->id,
                'nama_produk'   => $screw431->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw431->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw431->harga_jual ?? 0,
                'total_harga'   => ($screw431->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $luasAtap . ' m²'
            ];
        }
    }

    // ============================================================
    // Screw Dudukan Atap (ID: 437)
    // Rumus: qty dudukan atap × satuan terkecil
    // ============================================================
    $qtyDudukanAtap = 0;
    foreach ($results as $result) {
        if ($result['area'] == 'Dudukan Atap') {
            $qtyDudukanAtap = $result['qty'];
            break;
        }
    }

    if ($qtyDudukanAtap > 0) {
        $screw437 = Product::with('unit')->find(437);
        
        if ($screw437) {
            $satuan = $screw437->satuan_terkecil ?? 1;
            $qtyScrew = ceil($qtyDudukanAtap * $satuan);
            
            $results[] = [
                'product_id'    => $screw437->id,
                'produk_id'     => $screw437->id,
                'nama_produk'   => $screw437->nama_produk,
                'area'          => 'Paku & Screw',
                'qty'           => $qtyScrew,
                'satuan'        => $screw437->unit->unit_name ?? 'pcs',
                'harga_satuan'  => $screw437->harga_jual ?? 0,
                'total_harga'   => ($screw437->harga_jual ?? 0) * $qtyScrew,
                'parameter'     => $qtyDudukanAtap . ' unit dudukan atap'
            ];
        }
    }
    
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
        'pelana' => 'boq.flexideckseam.pdf-flexideckseam-pelana',
        'limasan' => 'boq.flexideckseam.pdf-flexideckseam-limasan',
        'piramid' => 'boq.flexideckseam.pdf-flexideckseam-piramid',
        'satu-kemiringan' => 'boq.flexideckseam.pdf-flexideckseam-satu-kemiringan',
        'kerucut' => 'boq.flexideckseam.pdf-flexideckseam-kerucut',
        'dome' => 'boq.flexideckseam.pdf-flexideckseam-dome',
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