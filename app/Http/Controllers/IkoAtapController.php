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
    
    $brand = ProductBrand::where('id', '1')->first();
    
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
    
    // ===== AMBIL PRODUK STARTER =====
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
   
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit')->orderBy('id', 'asc')  // urutkan berdasarkan kolom created_at descending
        ->get();
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    
    return view('boq.iko-atap', compact(
        'products', 
        'underlayers', 
        'starters',  // <-- TAMBAHKAN INI
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
    
    // Opsi tambahan
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    $opsiExhaust = $request->opsi_exhaust ?? 0;
    
    // Pilihan material
    $produkAtapId = $request->produk_atap_id;
    $underlayerId = $request->underlayer_id;
    $starterProdukId = $request->starter_produk_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja;
    
    Log::info('Input parameters:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'sudut' => $sudut,
        'panjangNokJurai' => $panjangNokJurai,
        'panjangFlashing' => $panjangFlashing,
        'panjangWallFlashing' => $panjangWallFlashing,
        'opsiKaca' => $opsiKaca,
        'opsiPenangkal' => $opsiPenangkal,
        'opsiExhaust' => $opsiExhaust,
        'waste' => $waste,
        'produkAtapId' => $produkAtapId,
        'underlayerId' => $underlayerId,
        'starterProdukId' => $starterProdukId,
        'rangka' => $rangka,
        'lantaiKerja' => $lantaiKerja
    ]);
    
    $results = [];
    $brandId = 1; // IKO - ATAP
    
    // ============================================================
    // 1. Atap Utama + aksesoris
    // ============================================================
    if ($produkAtapId) {
        $produkAtap = Product::with(['unit', 'area', 'accessories.unit', 'accessories.area'])->find($produkAtapId);
        
        if (!$produkAtap) {
            Log::error('Produk atap tidak ditemukan: ' . $produkAtapId);
            return response()->json([
                'success' => false,
                'message' => 'Produk atap tidak ditemukan'
            ]);
        }
        
        Log::info('Produk Atap ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // Hitung Atap Utama
        $qtyRaw = $luasAtap / $produkAtap->satuan_terkecil;
        $qtyAtapUtama = ceil($qtyRaw + ($qtyRaw * $waste));
        $results[] = $this->formatResult($produkAtap, $qtyAtapUtama, 'Atap Utama', $luasAtap);
        
        // Loop aksesoris
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing', 'Kaca', 'Flashing Kaca', 'Penangkal Petir', 'Ventilasi Exhaust', 'Exhaust', 'Starter', 'Shingle Stick'])) {
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
    
    // ============================================================
    // 2. Starter (dari dropdown)
    // ============================================================
    if ($starterProdukId) {
        $starter = Product::with('unit')->find($starterProdukId);
        if ($starter) {
            $qtyRaw = $panjangStarter / $starter->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($starter, $qty, 'Starter', $panjangStarter);
            Log::info('Starter ditambahkan:', [
                'nama' => $starter->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. Underlayer (dari dropdown)
    // ============================================================
    if ($underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            Log::info('Underlayer ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. Talang Jurai (conditional)
    // ============================================================
    if ($panjangTalangJurai > 0 && $produkAtapId) {
        $produkAtap = Product::with(['accessories'])->find($produkAtapId);
        $talangJurai = $produkAtap->accessories->first(function($aksesoris) {
            return $aksesoris->area->nama_area == 'Talang Jurai';
        });
        
        if ($talangJurai) {
            $qtyRaw = $panjangTalangJurai / $talangJurai->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($talangJurai, $qty, 'Talang Jurai', $panjangTalangJurai);
            Log::info('Talang Jurai ditambahkan:', [
                'nama' => $talangJurai->nama_produk,
                'qty' => $qty,
                'panjang' => $panjangTalangJurai
            ]);
        }
    }
    
    // ============================================================
    // 5. Wall Flashing (conditional - dari opsi dinding)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            Log::info('Wall Flashing ditambahkan:', [
                'panjangWallFlashing' => $panjangWallFlashing,
                'satuan_terkecil' => $wallFlashing->satuan_terkecil,
                'qtyRaw' => $qtyRaw,
                'waste' => $waste,
                'qty_final' => $qty
            ]);
            
            $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
        } else {
            Log::warning('Wall Flashing product tidak ditemukan untuk brand_id: ' . $brandId);
        }
    }
    
    // ============================================================
    // 6. Flashing Kaca (conditional - dari opsi kaca)
    // ============================================================
    if ($opsiKaca > 0) {
        // Cari produk Flashing Kaca
        $kacaProduct = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Flashing Kaca')
                  ->orWhere('nama_area', 'Kaca');
            })
            ->first();
            
        if ($kacaProduct) {
            $qtyRaw = $opsiKaca / $kacaProduct->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            Log::info('Flashing Kaca ditambahkan:', [
                'panjangKaca' => $opsiKaca,
                'satuan_terkecil' => $kacaProduct->satuan_terkecil,
                'qtyRaw' => $qtyRaw,
                'qty_final' => $qty
            ]);
            
            $results[] = $this->formatResult($kacaProduct, $qty, 'Flashing Kaca', $opsiKaca);
        } else {
            Log::warning('Flashing Kaca product tidak ditemukan untuk brand_id: ' . $brandId);
        }
    }
    
    // ============================================================
    // 7. Penangkal Petir (conditional - dari opsi penangkal)
    // ============================================================
    if ($opsiPenangkal > 0) {
        $penangkalProduct = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Penangkal Petir');
            })
            ->first();
            
        if ($penangkalProduct) {
            $qtyRaw = $opsiPenangkal / $penangkalProduct->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            Log::info('Penangkal Petir ditambahkan:', [
                'jumlahTitik' => $opsiPenangkal,
                'satuan_terkecil' => $penangkalProduct->satuan_terkecil,
                'qtyRaw' => $qtyRaw,
                'qty_final' => $qty
            ]);
            
            $results[] = $this->formatResult($penangkalProduct, $qty, 'Penangkal Petir', $opsiPenangkal . ' titik');
        } else {
            Log::warning('Penangkal Petir product tidak ditemukan untuk brand_id: ' . $brandId);
        }
    }
    
    // ============================================================
    // 8. Ventilasi Exhaust (conditional - dari opsi exhaust)
    // ============================================================
    if ($opsiExhaust > 0) {
        $exhaustProduct = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Ventilasi Exhaust')
                  ->orWhere('nama_area', 'Exhaust');
            })
            ->first();
            
        if ($exhaustProduct) {
            $qtyRaw = $opsiExhaust / $exhaustProduct->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            
            Log::info('Ventilasi Exhaust ditambahkan:', [
                'jumlahTitik' => $opsiExhaust,
                'satuan_terkecil' => $exhaustProduct->satuan_terkecil,
                'qtyRaw' => $qtyRaw,
                'qty_final' => $qty
            ]);
            
            $results[] = $this->formatResult($exhaustProduct, $qty, 'Ventilasi Exhaust', $opsiExhaust . ' titik');
        } else {
            Log::warning('Ventilasi Exhaust product tidak ditemukan untuk brand_id: ' . $brandId);
        }
    }
    
    
// ============================================================
// 9. LANTAI KERJA / PLYWOOD (dari dropdown)
// ============================================================
$luasPerLembar = 2.88;
$qtyPlywood = 0;

// Cari produk Plywood berdasarkan nama dari dropdown
$plywoodProduct = Product::where('brand_id', $brandId)
    ->where('id', $lantaiKerja)
    ->first();

if ($plywoodProduct) {
    $qtyRaw = $luasAtap / $plywoodProduct->satuan_terkecil;
    $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
    $results[] = $this->formatResult($plywoodProduct, $qtyPlywood, 'Lantai Kerja', $luasAtap);
    Log::info('Lantai Kerja dari database ditambahkan:', [
        'nama' => $plywoodProduct->nama_produk,
        'qty' => $qtyPlywood
    ]);
} else {
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
    Log::info('Lantai Kerja hardcode ditambahkan:', [
        'nama' => $lantaiKerja,
        'qty' => $qtyPlywood
    ]);
}

// ============================================================
// 10. SCREW (berdasarkan struktur rangka + lantai kerja)
// ============================================================

// ============================================================
// TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
// ============================================================

$lantaiKerjaId = (int) $request->input('lantai_kerja');

$lantaiKerjaGrupA = [384, 383, 385];                         // → screw 390
$lantaiKerjaGrupB = [386, 387, 388];                         // → screw 391
$lantaiKerjaBajaBeratBeton = [383, 384, 385, 386, 387, 388]; // → screw 389

$screwId = null;

if (in_array($rangka, ['Kayu', 'Baja Ringan'], true)) {
    if (in_array($lantaiKerjaId, $lantaiKerjaGrupA, true)) {
        $screwId = 390;
    } elseif (in_array($lantaiKerjaId, $lantaiKerjaGrupB, true)) {
        $screwId = 391;
    }
} elseif (in_array($rangka, ['Baja Berat', 'Beton'], true)) {
    if (in_array($lantaiKerjaId, $lantaiKerjaBajaBeratBeton, true)) {
        $screwId = 389;
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
        'lantai_kerja'    => $lantaiKerjaId,
        'rangka'          => $rangka,
        'qtyPlywood'      => $qtyPlywood,
        'satuan_terkecil' => $satuanTerkecil,
        'qtyScrew'        => $qtyScrew,
        'qty_final'       => $qty
    ]);
} else {
    // Fallback hardcode kalau produk screw tidak ada di DB
    $screwName = in_array($rangka, ['Baja Berat', 'Beton'], true)
        ? 'Drilling Screw'
        : 'Screw Plywood';

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
        'lantai_kerja'    => $lantaiKerjaId,
        'rangka'          => $rangka,
    ]);
}
    
    // ============================================================
    // 11. Shingle Stick / Lem (conditional - dari opsi penangkal & exhaust)
    // ============================================================
    $totalTitikTambahan = $opsiPenangkal + $opsiExhaust;
    
    if ($totalTitikTambahan > 0) {
        // Cari produk Lem / Shingle Stick yang sudah ada di results
        $found = false;
        foreach ($results as &$result) {
            if ($result['area'] == 'Lem' || $result['area'] == 'Shingle Stick') {
                // 1 titik = 1 tube Lem (tanpa waste)
                $tambahanQty = $totalTitikTambahan;
                
                $result['qty'] += $tambahanQty;
                $result['total_harga'] = $result['qty'] * $result['harga_satuan'];
                
                Log::info('QTY Lem ditambahkan:', [
                    'area' => $result['area'],
                    'totalTitik' => $totalTitikTambahan,
                    'tambahan_qty' => $tambahanQty,
                    'qty_sebelum' => $result['qty'] - $tambahanQty,
                    'qty_sesudah' => $result['qty']
                ]);
                $found = true;
                break;
            }
        }
        
        // Jika belum ada produk Lem/Shingle Stick di results, tambahkan baru
        if (!$found) {
            // Cari produk Shingle Stick (Lem)
            $shingleStickProduct = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('nama_area', 'Lem')
                      ->orWhere('nama_area', 'Shingle Stick');
                })
                ->first();
                
            if ($shingleStickProduct) {
                // 1 titik = 1 tube Lem (tanpa waste)
                $qty = $totalTitikTambahan;
                
                $results[] = $this->formatResult($shingleStickProduct, $qty, 'Shingle Stick', $totalTitikTambahan . ' titik (Penangkal + Exhaust)');
                
                Log::info('Shingle Stick baru ditambahkan:', [
                    'qty' => $qty,
                    'totalTitik' => $totalTitikTambahan
                ]);
            } else {
                // Hardcode jika tidak ada di database
                $results[] = [
                    'product_id' => null,
                    'produk_id' => null,
                    'nama_produk' => 'Shingle Stick / Lem',
                    'area' => 'Shingle Stick',
                    'qty' => $totalTitikTambahan,
                    'satuan' => 'tube',
                    'harga_satuan' => 0,
                    'total_harga' => 0,
                    'parameter' => $totalTitikTambahan . ' titik'
                ];
                Log::info('Shingle Stick hardcode ditambahkan:', [
                    'qty' => $totalTitikTambahan
                ]);
            }
        }
    }
    // ============================================================
// 10b. PAKU (berdasarkan lantai kerja: GRC → 392, else → 7)
// ============================================================

$lantaiKerjaProduct = $lantaiKerjaId
    ? Product::find($lantaiKerjaId)
    : null;

$isGrc = $lantaiKerjaProduct
    && stripos($lantaiKerjaProduct->nama_produk, 'GRC') !== false;

$pakuId = $isGrc ? 392 : 7;

$pakuProduct = Product::where('brand_id', $brandId)
    ->where('id', $pakuId)
    ->first();

if ($pakuProduct) {
    // Rumus: luasAtap / satuanPaku
    $satuanPaku = ($sudut < 45) ? 20 : 13.4;
    $qtyRawPaku = $luasAtap / $satuanPaku;
    $qtyPaku = ceil($qtyRawPaku + ($qtyRawPaku * $waste));

    $results[] = $this->formatResult(
        $pakuProduct,
        $qtyPaku,
        'Paku & Screw',
        $luasAtap . ' m²'
    );

    Log::info('Paku dari database ditambahkan:', [
        'paku_id'         => $pakuId,
        'nama'            => $pakuProduct->nama_produk,
        'lantai_kerja_id' => $lantaiKerjaId,
        'lantai_kerja'    => $lantaiKerjaProduct->nama_produk ?? '-',
        'is_grc'          => $isGrc,
        'luasAtap'        => $luasAtap,
        'sudut'           => $sudut,
        'satuanPaku'      => $satuanPaku,
        'qtyRaw'          => $qtyRawPaku,
        'qty_final'       => $qtyPaku,
    ]);
} else {
    // Fallback hardcode kalau produk paku tidak ada di DB
    $results[] = [
        'product_id'   => null,
        'produk_id'    => null,
        'nama_produk'  => $isGrc ? 'Paku GRC' : 'Paku',
        'area'         => 'Paku & Screw',
        'qty'          => ceil(($luasAtap / (($sudut < 45) ? 20 : 13.4)) * (1 + $waste)),
        'satuan'       => 'pcs',
        'harga_satuan' => 0,
        'total_harga'  => 0,
        'parameter'    => $luasAtap . ' m²'
    ];

    Log::warning('Paku tidak ditemukan di database, fallback hardcode:', [
        'paku_id_target' => $pakuId,
        'lantai_kerja'   => $lantaiKerjaId,
        'is_grc'         => $isGrc,
    ]);
}
    
    // ============================================================
    // 12. Grand Total
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    
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