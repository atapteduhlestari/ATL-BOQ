<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\DetailBoq;
use App\Models\ProductArea;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PalmexKombinasiController extends Controller
{
      public function limasanTrapesium(Request $request)
{
    $brand = ProductBrand::where('nama_brand', 'PALMEX')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = $request->query('jurai_1', 0);
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $flashing_1 = $request->query('flashing_1', 0);
    
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = $request->query('jurai_2', 0);
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    
    Log::info('=== BOQ Palmex Limasan + Trapesium ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('jurai_1: ' . $jurai_1);
    Log::info('nok_atas_1: ' . $nok_atas_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('jurai_2: ' . $jurai_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 2 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Limasan: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Trapesium: ' . $underlayers_2->count());
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];

   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-limasan-trapesium', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2',
        'juraiOptions', 'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2'
    ));
}

public function hitungLimasanTrapesium(Request $request)
{
    Log::info('=== PalmexController: hitungLimasanTrapesium() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'limasan');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'jenis' => $jenis,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokAtasId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA LIMASAN+TRAPESIUM dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Hitung Jumlah Jurai Dalam
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jarakJurai' => $jarakJurai,
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
            }
            // ============================================================
            // METAL FLASHING - HANYA UNTUK NON-EXPOSE
            // ============================================================
            elseif (($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing') && $sistemPemasangan == 'non-expose') {
                if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangFlashing / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $panjangFlashing;
                    $displayArea = 'Metal Flashing';
                    Log::info('Metal Flashing dihitung (non-expose):', ['qty' => $qty]);
                }
            }
            // ============================================================
            // METAL FLASHING - SKIP UNTUK EXPOSE
            // ============================================================
            elseif (($areaSlug == 'palmex-metal-flashing' || $areaName == 'Metal Flashing') && $sistemPemasangan == 'expose') {
                Log::info('⏭️ SKIP Metal Flashing (tidak digunakan untuk expose)');
                continue;
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
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
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
   // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function limasPelana(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = $request->query('jurai_1', 0);
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $flashing_1 = $request->query('flashing_1', 0);
    
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = $request->query('jurai_2', 0);
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    
    Log::info('=== BOQ Palmex Limas + Pelana ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('jurai_1: ' . $jurai_1);
    Log::info('nok_atas_1: ' . $nok_atas_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('jurai_2: ' . $jurai_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Limasan: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Pelana: ' . $underlayers_2->count());
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-limasan-pelana', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2',
        'juraiOptions', 'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2'
    ));
}
public function hitungLimasPelana(Request $request)
{
    Log::info('=== PalmexController: hitungLimasPelana() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'limasan');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'jenis' => $jenis,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI =====
    if ($jenis == 'limasan') {
        if (!$juraiId) {
            return response()->json([
                'success' => false,
                'message' => 'Jurai wajib dipilih untuk bagian Limasan!'
            ]);
        }
        if (!$nokAtasId) {
            return response()->json([
                'success' => false,
                'message' => 'Nok Atas wajib dipilih untuk bagian Limasan!'
            ]);
        }
    } else {
        if (!$nokAtasId) {
            return response()->json([
                'success' => false,
                'message' => 'Nok Atas wajib dipilih untuk bagian Pelana!'
            ]);
        }
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA LIMAS+PELANA dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Hitung Jumlah Jurai Dalam
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jarakJurai' => $jarakJurai,
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari dropdown) - HANYA UNTUK LIMASAN
    // ============================================================
    if ($jenis == 'limasan' && $juraiId && $panjangJurai > 0) {
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
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown)
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function pelana2Trapesium(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Pelana
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = 0;
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $flashing_1 = $request->query('flashing_1', 0);
    
    // Bagian 2: Trapesium A (Kiri-Kanan)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = $request->query('jurai_2', 0);
    $nok_atas_2 = 0;
    $flashing_2 = $request->query('flashing_2', 0);
    
    // Bagian 3: Trapesium B (Depan-Belakang)
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    $starter_3 = $request->query('starter_3', 0);
    $jurai_3 = $request->query('jurai_3', 0);
    $nok_atas_3 = 0;
    $flashing_3 = $request->query('flashing_3', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    $opsiDinding3 = $request->query('dinding_3', 0);
    $opsiKaca3 = $request->query('kaca_3', 0);
    
    Log::info('=== BOQ Palmex Pelana + 2 Trapesium ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('nok_atas_1: ' . $nok_atas_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('jurai_2: ' . $jurai_2);
    Log::info('flashing_2: ' . $flashing_2);
    Log::info('luas_atap_3: ' . $luas_atap_3);
    Log::info('sudut_3: ' . $sudut_3);
    Log::info('starter_3: ' . $starter_3);
    Log::info('jurai_3: ' . $jurai_3);
    Log::info('flashing_3: ' . $flashing_3);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 3 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah underlayer Bagian 3: ' . $underlayers_3->count());
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-pelana-2trapesium', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3',
        'juraiOptions', 'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'luas_atap_3', 'sudut_3', 'starter_3', 'jurai_3', 'nok_atas_3', 'flashing_3',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2', 'opsiDinding3', 'opsiKaca3'
    ));
}

public function hitungPelana2Trapesium(Request $request)
{
    Log::info('=== PalmexController: hitungPelana2Trapesium() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'pelana');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'jenis' => $jenis,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if ($jenis == 'pelana') {
        if (!$nokAtasId && $panjangNokAtas > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nok Atas wajib dipilih untuk bagian Pelana!'
            ]);
        }
    } else {
        if (!$juraiId && $panjangJurai > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jurai wajib dipilih untuk bagian Trapesium!'
            ]);
        }
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA PELANA 2 TRAPESIUM dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Hitung Jumlah Jurai Dalam
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                
                // Cari qty Nok Atas dari dropdown (jika ada)
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jarakJurai' => $jarakJurai,
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari dropdown) - UNTUK TRAPESIUM
    // ============================================================
    if ($jenis != 'pelana' && $juraiId && $panjangJurai > 0) {
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
    // 3. NOK ATAS (dari dropdown) - UNTUK PELANA
    // ============================================================
    if ($jenis == 'pelana' && $nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
      // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function limasanLimasan(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Limasan A (Depan)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = $request->query('jurai_1', 0);
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $flashing_1 = $request->query('flashing_1', 0);
    
    // Bagian 2: Limasan B (Belakang)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = $request->query('jurai_2', 0);
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    
    Log::info('=== BOQ Palmex Limasan + Limasan ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('jurai_1: ' . $jurai_1);
    Log::info('nok_atas_1: ' . $nok_atas_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('jurai_2: ' . $jurai_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 2 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-limasan-limasan', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2',
        'juraiOptions', 'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2'
    ));
}

public function hitungLimasanLimasan(Request $request)
{
    Log::info('=== PalmexController: hitungLimasanLimasan() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokAtasId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA LIMASAN+LIMASAN dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Hitung Jumlah Jurai Dalam
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jarakJurai' => $jarakJurai,
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. JURAI (dari dropdown)
    // ============================================================
    if ($juraiId && $panjangJurai > 0) {
        $jurai = Product::with('unit')->find($juraiId);
        if ($jurai) {
            // 0.25 = 25cm, 2x4 = 8
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
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}   

public function pelana2Kemiringan(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Kiri (1 Kemiringan)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = 0;
    $nok_atas_1 = 0;
    $flashing_1 = $request->query('flashing_1', 0);
    $lebar_1 = $request->query('lebar_1', 0);
    
    // Bagian 2: Tengah (Pelana)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = 0;
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $lebar_2 = $request->query('lebar_2', 0);
    
    // Bagian 3: Kanan (1 Kemiringan)
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    $starter_3 = $request->query('starter_3', 0);
    $jurai_3 = 0;
    $nok_atas_3 = 0;
    $flashing_3 = $request->query('flashing_3', 0);
    $lebar_3 = $request->query('lebar_3', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    $opsiDinding3 = $request->query('dinding_3', 0);
    $opsiKaca3 = $request->query('kaca_3', 0);
    
    Log::info('=== BOQ Palmex Pelana 2 Kemiringan ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    Log::info('luas_atap_3: ' . $luas_atap_3);
    Log::info('sudut_3: ' . $sudut_3);
    Log::info('starter_3: ' . $starter_3);
    Log::info('flashing_3: ' . $flashing_3);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 3 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN NOK ATAS =====
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah underlayer Bagian 3: ' . $underlayers_3->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
    $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-pelana-2-kemiringan', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'luas_atap_3', 'sudut_3', 'starter_3', 'jurai_3', 'nok_atas_3', 'flashing_3',
        'lebar_1', 'lebar_2', 'lebar_3',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2', 'opsiDinding3', 'opsiKaca3'
    ));
}

public function hitungPelana2Kemiringan(Request $request)
{
    Log::info('=== PalmexController: hitungPelana2Kemiringan() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$nokAtasId && $panjangNokAtas > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA PELANA 2 KEMIRINGAN dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
                continue;
            }
            
            // ===== SKIP JURAI (Pelana 2 kemiringan TIDAK ADA JURAI) =====
            if ($areaSlug == 'palmex-jurai' || 
                stripos($areaSlug, 'jurai') !== false ||
                stripos($areaName, 'Jurai') !== false) {
                Log::info('⏭️ SKIP Jurai (Pelana 2 kemiringan tidak punya jurai)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Pelana 2 kemiringan TIDAK ADA JURAI
                $jumlahJuraiDalam = 0;
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

public function pelana2Sisi(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Kiri (1 Kemiringan)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = 0;
    $nok_atas_1 = 0;
    $flashing_1 = $request->query('flashing_1', 0);
    $panjang_1 = $request->query('panjang_a', 0);
    $lebar_1 = $request->query('lebar_a', 0);
    
    // Bagian 2: Tengah (Pelana)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = 0;
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $panjang_2 = $request->query('panjang_b', 0);
    $lebar_2 = $request->query('lebar_b', 0);
    
    // Bagian 3: Kanan (1 Kemiringan)
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    $starter_3 = $request->query('starter_3', 0);
    $jurai_3 = 0;
    $nok_atas_3 = 0;
    $flashing_3 = $request->query('flashing_3', 0);
    $panjang_3 = $request->query('panjang_c', 0);
    $lebar_3 = $request->query('lebar_c', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    $opsiDinding3 = $request->query('dinding_3', 0);
    $opsiKaca3 = $request->query('kaca_3', 0);
    
    Log::info('=== BOQ Palmex Pelana + 2 Sisi ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    Log::info('luas_atap_3: ' . $luas_atap_3);
    Log::info('sudut_3: ' . $sudut_3);
    Log::info('starter_3: ' . $starter_3);
    Log::info('flashing_3: ' . $flashing_3);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 3 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN NOK ATAS =====
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah underlayer Bagian 3: ' . $underlayers_3->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
   
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-pelana-2-sisi', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'luas_atap_3', 'sudut_3', 'starter_3', 'jurai_3', 'nok_atas_3', 'flashing_3',
        'panjang_1', 'lebar_1', 'panjang_2', 'lebar_2', 'panjang_3', 'lebar_3',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2', 'opsiDinding3', 'opsiKaca3'
    ));
}

public function hitungPelana2Sisi(Request $request)
{
    Log::info('=== PalmexController: hitungPelana2Sisi() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'nokAtasId' => $nokAtasId
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$nokAtasId && $panjangNokAtas > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        if ($sistemPemasangan == 'expose') {
            $qty = ceil($luasDenganWaste * 9);
        } else {
            $qty = ceil($luasDenganWaste * $satuan);
        }
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        Log::info('Atap Utama dihitung:', [
            'luasAtap' => $luasAtap,
            'satuan' => $satuan,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
                continue;
            }
            
            // ===== SKIP JURAI (Pelana + 2 Sisi TIDAK ADA JURAI) =====
            if ($areaSlug == 'palmex-jurai' || 
                stripos($areaSlug, 'jurai') !== false ||
                stripos($areaName, 'Jurai') !== false) {
                Log::info('⏭️ SKIP Jurai (tidak punya jurai)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Pelana + 2 Sisi TIDAK ADA JURAI
                $jumlahJuraiDalam = 0;
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    $qtyRaw = ($luasAtap * $satuanAksesoris) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
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

public function pelana3Arah(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Depan (Pelana)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = 0;
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $flashing_1 = $request->query('flashing_1', 0);
    $panjang_a = $request->query('panjang_a', 0);
    $lebar_a = $request->query('lebar_a', 0);
    
    // Bagian 2: Belakang (Pelana)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = 0;
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $panjang_b = $request->query('panjang_b', 0);
    $lebar_b = $request->query('lebar_b', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    
    Log::info('=== BOQ Palmex Pelana 3 Arah ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('nok_atas_1: ' . $nok_atas_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 2 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN NOK ATAS =====
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
    
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-pelana-3-arah', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'panjang_a', 'lebar_a', 'panjang_b', 'lebar_b',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2'
    ));
}

public function hitungPelana3Arah(Request $request)
{
    Log::info('=== PalmexController: hitungPelana3Arah() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'depan');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'jenis' => $jenis,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$nokAtasId && $panjangNokAtas > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA PELANA 3 ARAH dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
                continue;
            }
            
            // ===== SKIP JURAI (Pelana 3 Arah TIDAK ADA JURAI) =====
            if ($areaSlug == 'palmex-jurai' || 
                stripos($areaSlug, 'jurai') !== false ||
                stripos($areaName, 'Jurai') !== false) {
                Log::info('⏭️ SKIP Jurai (tidak punya jurai)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Pelana 3 Arah TIDAK ADA JURAI
                $jumlahJuraiDalam = 0;
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
      // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

public function pelanaX(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Data dari perhitungan sebelumnya (3 Pelana)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    
    $starter_1 = $request->query('starter_1', 0);
    $starter_2 = $request->query('starter_2', 0);
    $starter_3 = $request->query('starter_3', 0);
    
    $sudut = $request->query('sudut_2', 0);
    $lebar_2 = $request->query('lebar_2', 0);
    
    $nok_1 = $request->query('nok_1', 0);
    $nok_2 = $request->query('nok_2', 0);
    $nok_3 = $request->query('nok_3', 0);
    
    $jurai_1 = $request->query('jurai_1', 0);
    $jurai_3 = $request->query('jurai_3', 0);
    
    $flashing_1 = $request->query('flashing_1', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $flashing_3 = $request->query('flashing_3', 0);
    
    // Opsi tambahan
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    // ===== HITUNG TOTAL =====
    $totalLuas = $luas_atap_1 + $luas_atap_2 + $luas_atap_3;
    
    // TOTAL STARTER = starter A + B + C - ((lebar B x 4) / cos(sudut))
    $radSudut = deg2rad($sudut);
    $cosSudut = cos($radSudut);
    $pengurang = ($lebar_2 * 4) / $cosSudut;
    $totalStarter = ($starter_1 + $starter_2 + $starter_3) - $pengurang;
    
    // TOTAL NOK & JURAI
    $totalNokJurai = ($nok_1 + $nok_2 + $nok_3) + ($jurai_1 + $jurai_3);
    
    // TOTAL FLASHING
    $totalFlashing = ($flashing_1 + $flashing_2 + $flashing_3) - $pengurang;
    
    // HITUNG TALANG JURAI OTOMATIS
    $lebarSetengah = $lebar_2 / 2;
    $talangJurai = sqrt(
        (2 * pow($lebarSetengah, 2)) + 
        pow($lebarSetengah * tan($radSudut), 2)
    ) * 4;
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER =====
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN NOK ATAS =====
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('=== BOQ Palmex Pelana X ===');
    Log::info('Total Luas: ' . $totalLuas);
    Log::info('Sudut: ' . $sudut);
    Log::info('Total Starter: ' . $totalStarter);
    Log::info('Total Nok & Jurai: ' . $totalNokJurai);
    Log::info('Total Flashing: ' . $totalFlashing);
    Log::info('Talang Jurai: ' . $talangJurai);
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
   
$areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    // Kirim data total ke view
    return view('boq.palmex.atap-kombinasi.palmex-pelana-x', compact(
        'products', 'starters', 'underlayers',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'totalLuas', 'sudut', 'totalStarter', 'totalNokJurai', 'totalFlashing', 'talangJurai',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}

public function hitungPelanaX(Request $request)
{
    Log::info('=== PalmexController: hitungPelanaX() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $starterProdukId = $request->input('starter_produk_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'pelana-x');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'nokAtasId' => $nokAtasId,
        'jenis' => $jenis,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$nokAtasId && $panjangNokAtas > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA PELANA X dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
                continue;
            }
            
            // ===== SKIP JURAI (Pelana X TIDAK ADA JURAI) =====
            if ($areaSlug == 'palmex-jurai' || 
                stripos($areaSlug, 'jurai') !== false ||
                stripos($areaName, 'Jurai') !== false) {
                Log::info('⏭️ SKIP Jurai (tidak punya jurai)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Pelana X TIDAK ADA JURAI
                $jumlahJuraiDalam = 0;
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        } else {
            Log::info('Wall Flashing tidak ditemukan di database');
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Wall Flashing',
                'area' => 'Wall Flashing',
                'qty' => ceil($panjangWallFlashing),
                'satuan' => 'm',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $panjangWallFlashing . ' m'
            ];
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

public function limasanX(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Data dari perhitungan sebelumnya (3 Limasan)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    
    $starter_1 = $request->query('starter_1', 0);
    $starter_2 = $request->query('starter_2', 0);
    $starter_3 = $request->query('starter_3', 0);
    
    $sudut = $request->query('sudut_2', 0);
    $lebar_2 = $request->query('lebar_2', 0);
    
    // NOK & JURAI (PALMEX: dipisah)
    $jurai_1 = $request->query('jurai_1', 0);
    $jurai_2 = $request->query('jurai_2', 0);
    $jurai_3 = $request->query('jurai_3', 0);
    
    $nok_atas_1 = $request->query('nok_atas_1', 0);
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $nok_atas_3 = $request->query('nok_atas_3', 0);
    
    $flashing_1 = $request->query('flashing_1', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $flashing_3 = $request->query('flashing_3', 0);
    
    // Opsi tambahan
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    // ===== HITUNG TOTAL =====
    $totalLuas = $luas_atap_1 + $luas_atap_2 + $luas_atap_3;
    
    // TOTAL STARTER = starter A + B + C - ((lebar B x 4) / cos(sudut))
    $radSudut = deg2rad($sudut);
    $cosSudut = cos($radSudut);
    $pengurang = ($lebar_2 * 4) / $cosSudut;
    $totalStarter = ($starter_1 + $starter_2 + $starter_3) - $pengurang;
    
    // TOTAL JURAI (semua Limasan punya jurai)
    $totalJurai = $jurai_1 + $jurai_2 + $jurai_3;
    
    // TOTAL NOK ATAS (semua Limasan punya nok atas)
    $totalNokAtas = $nok_atas_1 + $nok_atas_2 + $nok_atas_3;
    
    // TOTAL FLASHING
    $totalFlashing = ($flashing_1 + $flashing_2 + $flashing_3) - $pengurang;
    
    // HITUNG TALANG JURAI OTOMATIS
    // Rumus: sqrt(2 x (lebar b/2)^2 + ((lebar b/2) x tan(radians(sudut kemiringan)))^2) x 4
    $lebarSetengah = $lebar_2 / 2;
    $talangJurai = sqrt(
        (2 * pow($lebarSetengah, 2)) + 
        pow($lebarSetengah * tan($radSudut), 2)
    ) * 4;
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER =====
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    Log::info('=== BOQ Palmex Limasan X ===');
    Log::info('Total Luas: ' . $totalLuas);
    Log::info('Sudut: ' . $sudut);
    Log::info('Total Starter: ' . $totalStarter);
    Log::info('Total Jurai: ' . $totalJurai);
    Log::info('Total Nok Atas: ' . $totalNokAtas);
    Log::info('Total Flashing: ' . $totalFlashing);
    Log::info('Talang Jurai: ' . $talangJurai);
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    // Kirim data total ke view
    return view('boq.palmex.atap-kombinasi.palmex-limasan-x', compact(
        'products', 'starters', 'underlayers',
        'juraiOptions', 'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'totalLuas', 'sudut', 'totalStarter', 'totalJurai', 'totalNokAtas', 'totalFlashing', 'talangJurai',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}

public function hitungLimasanX(Request $request)
{
    Log::info('=== PalmexController: hitungLimasanX() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokAtasId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA LIMASAN X dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                // Hitung Jumlah Jurai Dalam
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = $step2Jurai + (2 * 4);
                $jumlahJuraiDalam = ceil($jumlahJuraiDalam);
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jarakJurai' => $jarakJurai,
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
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
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

public function gergaji(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL PARAMETER DARI URL =====
    $totalLuas = (float) $request->query('luas_atap', 0);
    $sudut = (float) $request->query('sudut', 0);
    $totalStarter = (float) $request->query('starter', 0);
    $totalNokAtas = (float) $request->query('nok_atas', 0);
    $totalFlashing = (float) $request->query('flashing', 0);
    $talangJurai = (float) $request->query('talang_jurai', 0);
    
    $opsiDinding = (float) $request->query('dinding', 0);
    $opsiKaca = (float) $request->query('kaca', 0);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('nama_area', 'Palmex Nok Atas')->first();
    
    // Produk Atap Utama
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // Starter
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // Underlayer
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN NOK ATAS =====
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->with('unit')
        ->get();
    
    Log::info('=== BOQ Palmex Gergaji ===');
    Log::info('totalLuas: ' . $totalLuas);
    Log::info('totalStarter: ' . $totalStarter);
    Log::info('totalNokAtas: ' . $totalNokAtas);
    Log::info('totalFlashing: ' . $totalFlashing);
    Log::info('nokAtasOptions: ' . $nokAtasOptions->count() . ' items');
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
   $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-gergaji', compact(
        'products', 'starters', 'underlayers',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'totalLuas', 'sudut', 'totalStarter', 'totalNokAtas', 'totalFlashing', 'talangJurai',
        'opsiDinding', 'opsiKaca'
    ));
}
public function hitungGergaji(Request $request)
{
    Log::info('=== PalmexController: hitungGergaji() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $panjangTalangJurai = $request->input('panjang_talang_jurai', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'panjangTalangJurai' => $panjangTalangJurai,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (kecuali Nok Atas)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA GERGAJI dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP Nok Atas)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('🔍 AKSESORIS:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan' => $satuanAksesoris
            ]);
            
            // ===== SKIP YANG DIHITUNG TERPISAH =====
            $skipAreas = ['Underlayer', 'Talang Jurai', 'Wall Flashing'];
            if (in_array($areaName, $skipAreas)) {
                Log::info('⏭️ SKIP (dihitung terpisah): ' . $areaName);
                continue;
            }
            
            // ===== SKIP NOK ATAS (pakai dropdown) =====
            if (stripos($areaSlug, 'nok-atas') !== false || 
                stripos($areaSlug, 'nok_atas') !== false ||
                stripos($areaName, 'Nok Atas') !== false) {
                Log::info('⏭️ SKIP Nok Atas: ' . $areaName);
                continue;
            }
            
            $qty = 0;
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if (stripos($areaSlug, 'starter') !== false || 
                stripos($areaName, 'Starter') !== false) {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qty = ceil($panjangStarter / $satuanAksesoris);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('✅ STARTER dihitung: ' . $qty);
                } else {
                    Log::info('⚠️ STARTER dilewati (panjang=0 atau satuan=0)');
                }
            }
            
            // ============================================================
            // SCREW
            // ============================================================
            elseif (stripos($areaSlug, 'screw') !== false || 
                    stripos($areaName, 'Screw') !== false) {
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                $jumlahJuraiDalam = 0; // Gergaji tidak ada jurai dalam
                
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                Log::info('✅ SCREW dihitung: ' . $qty);
            }
            
            // ============================================================
            // RAIL
            // ============================================================
            elseif (stripos($areaSlug, 'rail') !== false || 
                    stripos($areaName, 'Rail') !== false) {
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
                    $qty = ceil(($qtyStarter + $qtyAtapUtama) / 3);
                    $parameter = $panjangStarter;
                    $displayArea = 'Rail';
                    Log::info('✅ RAIL dihitung: ' . $qty);
                } else {
                    Log::info('⚠️ RAIL dilewati (qtyStarter+qtyAtapUtama=0)');
                }
            }
            
            // ============================================================
            // WIND
            // ============================================================
            elseif (stripos($areaSlug, 'wind') !== false || 
                    stripos($areaName, 'Wind') !== false) {
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
                Log::info('✅ WIND dihitung: ' . $qty);
            }
            
            // ============================================================
            // METAL FLASHING
            // ============================================================
            elseif ((stripos($areaSlug, 'metal-flashing') !== false || 
                    stripos($areaName, 'Metal Flashing') !== false) && $sistemPemasangan == 'non-expose'){
                if ($panjangFlashing > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangFlashing / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $panjangFlashing;
                    $displayArea = 'Metal Flashing';
                    Log::info('✅ METAL FLASHING dihitung: ' . $qty);
                } else {
                    Log::info('⚠️ METAL FLASHING dilewati (panjang=0)');
                }
            }
            
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif (stripos($areaSlug, 'flashing-kaca') !== false || 
                    stripos($areaName, 'Flashing Kaca') !== false) {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('✅ FLASHING KACA dihitung: ' . $qty);
                } else {
                    Log::info('⚠️ FLASHING KACA dilewati (opsiKaca=0)');
                }
            }
            
            // ============================================================
            // FALLBACK: AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea(
                    $areaName, 
                    $aksesoris, 
                    $luasAtap, 
                    $panjangStarter, 
                    0, 
                    $panjangFlashing, 
                    0, 
                    $panjangWallFlashing, 
                    $waste, 
                    $sudut
                );
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            // ===== TAMBAHKAN KE RESULTS JIKA QTY > 0 =====
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('✅ DITAMBAHKAN: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            } else {
                Log::info('❌ TIDAK DITAMBAHKAN: ' . $aksesoris->nama_produk . ' (Qty=0)');
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown - WAJIB)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI
    // ============================================================
    if ($panjangTalangJurai > 0) {
        $talangJurai = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Talang Jurai');
            })
            ->first();
            
        if ($talangJurai) {
            if (!in_array($talangJurai->id, $processedProductIds)) {
                $qtyRaw = $panjangTalangJurai / $talangJurai->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($talangJurai, $qty, 'Talang Jurai', $panjangTalangJurai);
                $processedProductIds[] = $talangJurai->id;
                Log::info('Talang Jurai PALMEX ditambahkan:', [
                    'nama' => $talangJurai->nama_produk,
                    'qty' => $qty
                ]);
            }
        } else {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Talang Jurai',
                'area' => 'Talang Jurai',
                'qty' => ceil($panjangTalangJurai),
                'satuan' => 'm',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $panjangTalangJurai . ' m'
            ];
            Log::info('Talang Jurai hardcode ditambahkan:', [
                'qty' => ceil($panjangTalangJurai)
            ]);
        }
    }
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
      // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function lengkung2Sisi(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    // Bagian 1: Kiri (1 Kemiringan)
    $luas_atap_1 = $request->query('luas_atap_1', 0);
    $sudut_1 = $request->query('sudut_1', 0);
    $starter_1 = $request->query('starter_1', 0);
    $jurai_1 = 0;
    $nok_atas_1 = 0;
    $flashing_1 = $request->query('flashing_1', 0);
    $panjang_1 = $request->query('panjang_a', 0);
    $lebar_1 = $request->query('lebar_a', 0);
    
    // Bagian 2: Tengah (Lengkung)
    $luas_atap_2 = $request->query('luas_atap_2', 0);
    $sudut_2 = $request->query('sudut_2', 0);
    $starter_2 = $request->query('starter_2', 0);
    $jurai_2 = 0;
    $nok_atas_2 = $request->query('nok_atas_2', 0);
    $flashing_2 = $request->query('flashing_2', 0);
    $panjang_2 = $request->query('panjang_b', 0);
    $lebar_2 = $request->query('lebar_b', 0);
    $tinggi = $request->query('tinggi', 0);
    
    // Bagian 3: Kanan (1 Kemiringan)
    $luas_atap_3 = $request->query('luas_atap_3', 0);
    $sudut_3 = $request->query('sudut_3', 0);
    $starter_3 = $request->query('starter_3', 0);
    $jurai_3 = 0;
    $nok_atas_3 = 0;
    $flashing_3 = $request->query('flashing_3', 0);
    $panjang_3 = $request->query('panjang_c', 0);
    $lebar_3 = $request->query('lebar_c', 0);
    
    // Opsi tambahan per bagian
    $opsiDinding1 = $request->query('dinding_1', 0);
    $opsiKaca1 = $request->query('kaca_1', 0);
    $opsiDinding2 = $request->query('dinding_2', 0);
    $opsiKaca2 = $request->query('kaca_2', 0);
    $opsiDinding3 = $request->query('dinding_3', 0);
    $opsiKaca3 = $request->query('kaca_3', 0);
    
    Log::info('=== BOQ Palmex Lengkung + 2 Sisi ===');
    Log::info('luas_atap_1: ' . $luas_atap_1);
    Log::info('sudut_1: ' . $sudut_1);
    Log::info('starter_1: ' . $starter_1);
    Log::info('flashing_1: ' . $flashing_1);
    Log::info('luas_atap_2: ' . $luas_atap_2);
    Log::info('sudut_2: ' . $sudut_2);
    Log::info('starter_2: ' . $starter_2);
    Log::info('nok_atas_2: ' . $nok_atas_2);
    Log::info('flashing_2: ' . $flashing_2);
    Log::info('luas_atap_3: ' . $luas_atap_3);
    Log::info('sudut_3: ' . $sudut_3);
    Log::info('starter_3: ' . $starter_3);
    Log::info('flashing_3: ' . $flashing_3);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaNokAtas = ProductArea::where('nama_area', 'Palmex Nok Atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER UNTUK 3 BAGIAN =====
    $underlayers_1 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_2 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    $underlayers_3 = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== NOK ATAS (DROPDOWN) =====
   $nokAtasOptions = Product::where('brand_id', $brand->id)
    ->where('area_id', $areaNokAtas->id)
    ->where('nama_produk', 'LIKE', 'PALMEX%')  // ← TAMBAHKAN INI
    ->with('unit')
    ->get();
    Log::info('Jumlah underlayer Bagian 1: ' . $underlayers_1->count());
    Log::info('Jumlah underlayer Bagian 2: ' . $underlayers_2->count());
    Log::info('Jumlah underlayer Bagian 3: ' . $underlayers_3->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    $rangkaOptions = ['Kayu', 'Baja Ringan', 'Baja Berat', 'Beton'];
  $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    return view('boq.palmex.atap-kombinasi.palmex-lengkung-2-sisi', compact(
        'products', 'starters', 'underlayers_1', 'underlayers_2', 'underlayers_3',
        'nokAtasOptions',
        'rangkaOptions', 'lantaiKerjaOptions',
        'luas_atap_1', 'sudut_1', 'starter_1', 'jurai_1', 'nok_atas_1', 'flashing_1',
        'luas_atap_2', 'sudut_2', 'starter_2', 'jurai_2', 'nok_atas_2', 'flashing_2',
        'luas_atap_3', 'sudut_3', 'starter_3', 'jurai_3', 'nok_atas_3', 'flashing_3',
        'panjang_1', 'lebar_1', 'panjang_2', 'lebar_2', 'panjang_3', 'lebar_3', 'tinggi',
        'opsiDinding1', 'opsiKaca1', 'opsiDinding2', 'opsiKaca2', 'opsiDinding3', 'opsiKaca3'
    ));
}

public function hitungLengkung2Sisi(Request $request)
{
    Log::info('=== PalmexController: hitungLengkung2Sisi() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'pelana');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'jenis' => $jenis,
        'nokAtasId' => $nokAtasId,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB UNTUK LENGKUNG =====
    if ($jenis == 'lengkung' && !$nokAtasId) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih untuk bagian lengkung!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA LENGKUNG 2 SISI dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing', 'Nok Atas'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
                continue;
            }
            
            // ===== SKIP NOK ATAS (pakai dropdown) =====
            if (stripos($areaSlug, 'nok-atas') !== false || 
                stripos($areaSlug, 'nok_atas') !== false ||
                stripos($areaName, 'Nok Atas') !== false) {
                Log::info('⏭️ SKIP Nok Atas: ' . $areaName);
                continue;
            }
            
            $qty = 0;
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX JURAI (LENGKUNG 2 SISI TIDAK ADA JURAI)
            // ============================================================
            elseif ($areaSlug == 'palmex-jurai' || $areaName == 'Palmex Jurai' || $areaName == 'Jurai') {
                $qty = 0;
                Log::info('Jurai dilewati (Lengkung 2 Sisi tidak punya jurai)');
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                $jumlahJuraiDalam = 0;
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
            }
        }
    }
    
    // ============================================================
    // 2. NOK ATAS (dari dropdown) - HANYA UNTUK LENGKUNG
    // ============================================================
    if ($jenis == 'lengkung' && $nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    } else {
        Log::info('Nok Atas tidak dihitung: jenis=' . $jenis . ', id=' . $nokAtasId . ', panjang=' . $panjangNokAtas);
    }
    
    // ============================================================
    // 3. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. TALANG JURAI - TIDAK DIPAKAI (Lengkung 2 Sisi)
    // ============================================================
    
    // ============================================================
    // 5. WALL FLASHING (conditional)
    // ============================================================
    if ($panjangWallFlashing > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $panjangWallFlashing / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $panjangWallFlashing);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        }
    }
    
       // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    
    // ============================================================
    // 8. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}
public function pelanaDinding(Request $request)
{
    $brand = ProductBrand::where('id', '14')->first();
    
    if (!$brand) {
        Log::error('Brand PALMEX tidak ditemukan!');
        abort(404, 'Brand PALMEX tidak ditemukan');
    }
    
    // ===== AMBIL SEMUA PARAMETER DARI URL =====
    $luasAtap = $request->query('luas_atap', 0);
    $luasDinding = $request->query('luas_dinding', 0);
    $sudut = $request->query('sudut', 0);
    $starter = $request->query('starter', 0);
    $jurai = $request->query('jurai', 0);
    $nokAtas = $request->query('nok_atas', 0);
    $flashing = $request->query('flashing', 0);
    
    $opsiDinding = $request->query('dinding', 0);
    $opsiKaca = $request->query('kaca', 0);
    $opsiPenangkal = $request->query('penangkal', 0);
    
    // ===== AREA =====
    $areaAtapUtama = ProductArea::where('nama_area', 'Atap Utama')->first();
    $areaUnderlayer = ProductArea::where('nama_area', 'Underlayer')->first();
    $areaStarter = ProductArea::where('nama_area', 'Starter')->first();
    $areaJurai = ProductArea::where('slug', 'palmex-jurai')->first();
    $areaNokAtas = ProductArea::where('slug', 'palmex-nok-atas')->first();
    
    // ===== PRODUK ATAP UTAMA =====
    $products = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaAtapUtama->id)
        ->with(['unit', 'accessories.unit', 'accessories.area'])
        ->get();
    
    // ===== STARTER =====
    $starters = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaStarter->id)
        ->with('unit')
        ->get();
    
    // ===== UNDERLAYER =====
    $underlayers = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaUnderlayer->id)
        ->with('unit')
        ->get();
    
    // ===== DROPDOWN JURAI & NOK ATAS =====
    $juraiOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaJurai->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();
    
    $nokAtasOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaNokAtas->id)
        ->where('nama_produk', 'LIKE', 'PALMEX%')
        ->with('unit')
        ->get();


        $areaLantaiKerja = ProductArea::where('id', '21')->first();
    $lantaiKerjaOptions = Product::where('brand_id', $brand->id)
        ->where('area_id', $areaLantaiKerja->id)
        ->with('unit') -> orderBy('id', 'asc')
        ->get();
    
    Log::info('Jumlah Jurai Options: ' . $juraiOptions->count());
    Log::info('Jumlah Nok Atas Options: ' . $nokAtasOptions->count());
    
    return view('boq.palmex.atap-kombinasi.palmex-pelana-dinding', compact(
        'products', 'starters', 'underlayers',
        'juraiOptions', 'nokAtasOptions','lantaiKerjaOptions',
        'luasAtap', 'luasDinding', 'sudut', 'starter', 'jurai', 'nokAtas', 'flashing',
        'opsiDinding', 'opsiKaca', 'opsiPenangkal'
    ));
}

public function hitungPelanaDinding(Request $request)
{
    Log::info('=== PalmexKombinasiController: hitungPelanaDinding() ===');
    Log::info('REQUEST DATA:', $request->all());
    
    // ===== AMBIL DATA =====
    $luasAtap = $request->input('luas_atap', 0);
    $luasDinding = $request->input('luas_dinding', 0);
    $panjangStarter = $request->input('panjang_starter', 0);
    $sudut = $request->input('sudut', 0);
    $panjangJurai = $request->input('panjang_jurai', 0);
    $panjangNokAtas = $request->input('panjang_nok_atas', 0);
    $panjangFlashing = $request->input('panjang_flashing', 0);
    $panjangWallFlashing = $request->input('panjang_wall_flashing', 0);
    $opsiDinding = $request->input('opsi_dinding', 0);
    $opsiKaca = $request->input('opsi_kaca', 0);
    $opsiPenangkal = $request->input('opsi_penangkal', 0);
    $waste = $request->input('waste', 5) / 100;
    
    $produkAtapId = $request->input('produk_atap_id');
    $juraiId = $request->input('jurai_id');
    $nokAtasId = $request->input('nok_atas_id');
    $underlayerId = $request->input('underlayer_id');
    $starterProdukId = $request->input('starter_produk_id');
    $rangka = $request->input('rangka', 'Baja Ringan');
    $lantaiKerja = $request->input('lantai_kerja', 'Plywood 9 mm');
    $sistemPemasangan = $request->input('sistem_pemasangan', 'expose');
    $jenis = $request->input('jenis', 'atap');
    $coverage = $request->input('coverage', 9); // <-- AMBIL COVERAGE
    
    // ===== VALIDASI SUDUT =====
    if ($sudut < 30) {
        $sistemPemasangan = 'non-expose';
        Log::info('Sudut ' . $sudut . '° < 30°, sistem dipaksa NON-EXPOSE');
    }
    
    $results = [];
    $processedProductIds = [];
    $brandId = 14; // PALMEX brand_id
    
    Log::info('DATA DITERIMA:', [
        'luasAtap' => $luasAtap,
        'luasDinding' => $luasDinding,
        'panjangStarter' => $panjangStarter,
        'panjangJurai' => $panjangJurai,
        'panjangNokAtas' => $panjangNokAtas,
        'panjangFlashing' => $panjangFlashing,
        'sudut' => $sudut,
        'sistemPemasangan' => $sistemPemasangan,
        'juraiId' => $juraiId,
        'nokAtasId' => $nokAtasId,
        'jenis' => $jenis,
        'coverage' => $coverage // <-- LOG COVERAGE
    ]);
    
    // ===== VALIDASI WAJIB =====
    if (!$juraiId && $panjangJurai > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Jurai wajib dipilih!'
        ]);
    }
    if (!$nokAtasId && $panjangNokAtas > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Nok Atas wajib dipilih!'
        ]);
    }
    
    // ============================================================
    // 1. ATAP UTAMA + AKSESORIS (SKIP JURAI & NOK ATAS)
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
        
        Log::info('Produk Atap PALMEX ditemukan:', [
            'id' => $produkAtap->id,
            'nama' => $produkAtap->nama_produk,
            'satuan_terkecil' => $produkAtap->satuan_terkecil
        ]);
        
        // ============================================================
        // 1a. ATAP UTAMA
        // ============================================================
        $satuan = $produkAtap->satuan_terkecil ?? 1;
        $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
        
        // ===== PAKAI COVERAGE =====
        // Coverage sudah didapat dari dropdown, nilainya 6, 7, atau 8
        $qty = ceil($luasDenganWaste * $coverage);
        
        Log::info('ATAP UTAMA PELANA + DINDING dihitung:', [
            'luasAtap' => $luasAtap,
            'waste' => $waste,
            'luasDenganWaste' => $luasDenganWaste,
            'coverage' => $coverage,
            'sistemPemasangan' => $sistemPemasangan,
            'qty' => $qty
        ]);
        
        $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
        $processedProductIds[] = $produkAtap->id;
        
        // ============================================================
        // 1b. LOOP AKSESORIS (SKIP JURAI & NOK ATAS)
        // ============================================================
        foreach ($produkAtap->accessories as $aksesoris) {
            $areaName = $aksesoris->area->nama_area;
            $areaSlug = $aksesoris->area->slug ?? '';
            $satuanAksesoris = $aksesoris->satuan_terkecil;
            
            Log::info('Aksesoris ditemukan:', [
                'nama' => $aksesoris->nama_produk,
                'area' => $areaName,
                'slug' => $areaSlug,
                'satuan_terkecil' => $satuanAksesoris
            ]);
            
            // SKIP yang dihitung terpisah
            if (in_array($areaName, ['Underlayer', 'Talang Jurai', 'Wall Flashing'])) {
                Log::info('Skip ' . $areaName . ' (dihitung terpisah)');
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
            $parameter = '-';
            $displayArea = $areaName;
            
            // ============================================================
            // STARTER
            // ============================================================
            if ($areaSlug == 'palmex-starter' || $areaName == 'Starter') {
                if ($panjangStarter > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $panjangStarter / $satuanAksesoris;
                    $qty = ceil($qtyRaw);
                    $parameter = $panjangStarter;
                    $displayArea = 'Starter';
                    Log::info('Starter dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // PALMEX SCREW
            // ============================================================
            elseif ($areaSlug == 'palmex-screw' || $areaName == 'Palmex Screw' || stripos($areaName, 'Screw') !== false) {
                $jarakJurai = ($sistemPemasangan == 'expose') ? 0.125 : 0.143;
                $step1Jurai = $panjangJurai - (0.25 * 4);
                $step2Jurai = $step1Jurai / $jarakJurai;
                $jumlahJuraiDalam = ceil($step2Jurai + (2 * 4));
                
                // Cari qty Nok Atas dari dropdown
                $qtyNokAtas = 0;
                if ($nokAtasId && $panjangNokAtas > 0) {
                    $nokAtasProduct = Product::find($nokAtasId);
                    if ($nokAtasProduct && $nokAtasProduct->satuan_terkecil > 0) {
                        $qtyNokAtas = ceil($panjangNokAtas / $nokAtasProduct->satuan_terkecil);
                    }
                }
                
                // Cari qty Starter dari results
                $qtyStarter = 0;
                foreach ($results as $result) {
                    if ($result['area'] == 'Starter') {
                        $qtyStarter = $result['qty'];
                        break;
                    }
                }
                
                // Hitung qty screw
                if ($sistemPemasangan == 'expose') {
                    // Expose: (Luas × Coverage) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * $coverage) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                } else {
                    // Non-Expose: (Luas × 21) + (Jurai × 2) + (Nok × 8)
                    $qtyRaw = ($luasAtap * 21) + ($jumlahJuraiDalam * 2) + ($qtyNokAtas * 8);
                }
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $parameter = $luasAtap;
                $displayArea = 'Screw';
                
                Log::info('Palmex Screw:', [
                    'jumlahJuraiDalam' => $jumlahJuraiDalam,
                    'qtyNokAtas' => $qtyNokAtas,
                    'qtyStarter' => $qtyStarter,
                    'qty' => $qty
                ]);
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
                    Log::info('Rail dihitung:', [
                        'qtyStarter' => $qtyStarter,
                        'qtyAtapUtama' => $qtyAtapUtama,
                        'qty' => $qty
                    ]);
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
                Log::info('Wind dihitung:', ['qty' => $qty]);
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
                    Log::info('Metal Flashing dihitung:', ['qty' => $qty]);
                }
            }
            // ============================================================
            // FLASHING KACA
            // ============================================================
            elseif ($areaSlug == 'flashing-kaca' || $areaName == 'Flashing Kaca') {
                if ($opsiKaca > 0 && $satuanAksesoris > 0) {
                    $qtyRaw = $opsiKaca / $satuanAksesoris;
                    $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                    $parameter = $opsiKaca;
                    $displayArea = 'Flashing Kaca';
                    Log::info('Flashing Kaca dihitung:', [
                        'opsiKaca' => $opsiKaca,
                        'satuan_terkecil' => $satuanAksesoris,
                        'qty' => $qty
                    ]);
                } else {
                    Log::info('Flashing Kaca dilewati (opsiKaca = 0)');
                }
            }
            // ============================================================
            // AREA LAINNYA
            // ============================================================
            else {
                Log::info('⚠️ AREA TIDAK TERDETEKSI, pakai hitungQtyByArea(): ' . $areaName);
                $qty = $this->hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, 0, $panjangFlashing, 0, $panjangWallFlashing, $waste, $sudut);
                $displayArea = $areaName;
                Log::info('📊 hitungQtyByArea return: ' . $qty);
            }
            
            if ($qty > 0) {
                $results[] = $this->formatResult($aksesoris, $qty, $displayArea, $parameter);
                $processedProductIds[] = $aksesoris->id;
                Log::info('Ditambahkan ke results: ' . $aksesoris->nama_produk . ' | Qty: ' . $qty);
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
    // 3. NOK ATAS (dari dropdown)
    // ============================================================
    if ($nokAtasId && $panjangNokAtas > 0) {
        $nokAtas = Product::with('unit')->find($nokAtasId);
        if ($nokAtas) {
            $qtyRaw = $panjangNokAtas / $nokAtas->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($nokAtas, $qty, 'Nok Atas', $panjangNokAtas);
            $processedProductIds[] = $nokAtas->id;
            Log::info('Nok Atas dari dropdown:', [
                'nama' => $nokAtas->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 4. UNDERLAYER (dari dropdown) - HANYA UNTUK NON-EXPOSE
    // ============================================================
    if ($sistemPemasangan == 'non-expose' && $underlayerId) {
        $underlayer = Product::with('unit')->find($underlayerId);
        if ($underlayer) {
            $qtyRaw = $luasAtap / $underlayer->satuan_terkecil;
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
            $results[] = $this->formatResult($underlayer, $qty, 'Underlayer', $luasAtap);
            $processedProductIds[] = $underlayer->id;
            Log::info('Underlayer PALMEX ditambahkan:', [
                'nama' => $underlayer->nama_produk,
                'qty' => $qty
            ]);
        }
    }
    
    // ============================================================
    // 5. TALANG JURAI - TIDAK DIPAKAI
    // ============================================================
    
    // ============================================================
    // 6. WALL FLASHING (conditional)
    // ============================================================
    if ($opsiDinding > 0) {
        $wallFlashing = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Wall Flashing');
            })
            ->first();
            
        if ($wallFlashing) {
            if (!in_array($wallFlashing->id, $processedProductIds)) {
                $qtyRaw = $opsiDinding / $wallFlashing->satuan_terkecil;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = $this->formatResult($wallFlashing, $qty, 'Wall Flashing', $opsiDinding);
                $processedProductIds[] = $wallFlashing->id;
                Log::info('Wall Flashing PALMEX ditambahkan:', [
                    'nama' => $wallFlashing->nama_produk,
                    'qty' => $qty
                ]);
            }
        } else {
            Log::info('Wall Flashing tidak ditemukan di database');
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'nama_produk' => 'Wall Flashing',
                'area' => 'Wall Flashing',
                'qty' => ceil($opsiDinding),
                'satuan' => 'm',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiDinding . ' m'
            ];
        }
    }
    
      // ============================================================
// 7. LANTAI KERJA (HANYA UNTUK NON-EXPOSE)
// ============================================================
$qtyPlywood = 0;

if ($sistemPemasangan == 'non-expose') {

    $plywoodProduct = null;

    // Cari produk lantai kerja by ID (angka) atau nama (fallback)
    if (!empty($lantaiKerja)) {
        if (is_numeric($lantaiKerja)) {
            // Kalau kirim ID numeric
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('id', (int) $lantaiKerja)
                ->first();
        } else {
            // Fallback: cari by nama
            $plywoodProduct = Product::where('brand_id', $brandId)
                ->where('nama_produk', 'LIKE', '%' . $lantaiKerja . '%')
                ->first();
        }
    }

    if ($plywoodProduct) {
        // Guard duplikat
        if (!in_array($plywoodProduct->id, $processedProductIds)) {

            $satuanTerkecil = $plywoodProduct->satuan_terkecil ?: 2.88;

            // qty = luasAtap / satuan_terkecil (+ waste)
            $qtyRaw = $luasAtap / $satuanTerkecil;
            $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));

            $results[] = $this->formatResult(
                $plywoodProduct,
                $qtyPlywood,
                'Lantai Kerja',
                $luasAtap . ' m²'
            );

            $processedProductIds[] = $plywoodProduct->id;

            Log::info('Lantai Kerja PALMEX dari database ditambahkan:', [
                'id'              => $plywoodProduct->id,
                'nama'            => $plywoodProduct->nama_produk,
                'satuan_terkecil' => $satuanTerkecil,
                'luasAtap'        => $luasAtap,
                'qtyRaw'          => $qtyRaw,
                'waste'           => $waste,
                'qty_final'       => $qtyPlywood,
            ]);
        }
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

        Log::warning('Lantai Kerja PALMEX fallback hardcode:', [
            'lantai_kerja_input' => $lantaiKerja,
            'is_numeric'         => is_numeric($lantaiKerja),
            'luasPerLembar'      => $luasPerLembar,
            'qty_final'          => $qtyPlywood,
        ]);
    }
} else {
    Log::info('Sistem Expose: Lantai Kerja TIDAK digunakan');
}
    
   // ============================================================
// 8. PAKU & SCREW (LANTAI KERJA) - HANYA UNTUK NON-EXPOSE
// ============================================================
if ($sistemPemasangan == 'non-expose' && $qtyPlywood > 0) {

    // ============================================================
    // A. TENTUKAN SCREW ID BERDASARKAN LANTAI KERJA + RANGKA
    // ============================================================

    $lantaiKerjaId = is_numeric($lantaiKerja) ? (int) $lantaiKerja : 0;

    $grupA              = [403, 404, 405];                        // → screw 410
    $grupB              = [406, 407, 408];                        // → screw 411
    $grupBajaBeratBeton = [403, 404, 405, 406, 407, 408];         // → screw 409

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
    // B. AMBIL PRODUK SCREW DARI DATABASE
    // ============================================================

    $screwProduct = null;
    if ($screwId) {
        $screwProduct = Product::where('brand_id', $brandId)
            ->where('id', $screwId)
            ->first();
    }

    // ============================================================
    // C. HITUNG QTY SCREW = qtyPlywood × satuan_terkecil (+ waste)
    // ============================================================

    if ($screwProduct) {
        if (!in_array($screwProduct->id, $processedProductIds)) {

            $satuanTerkecil = $screwProduct->satuan_terkecil ?: 1;

            // QTY = qtyPlywood × satuan_terkecil
            $qtyScrew = $qtyPlywood * $satuanTerkecil;
            $qty = ceil($qtyScrew + ($qtyScrew * $waste));

            $results[] = $this->formatResult(
                $screwProduct,
                $qty,
                'Paku & Screw',
                $qtyPlywood . ' lembar'
            );

            $processedProductIds[] = $screwProduct->id;

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
        }
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
            'screwName'       => $screwName,
        ]);
    }

} else {
    Log::info('Sistem Expose: Paku & Screw untuk plywood TIDAK digunakan');
}
    
    // ============================================================
    // 9. GRAND TOTAL
    // ============================================================
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('Total hasil: ' . count($results) . ' item');
    Log::info('Grand Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    Log::info('Coverage used: ' . $coverage);
    
    return response()->json([
        'success' => true,
        'results' => $results,
        'grand_total' => $grandTotal
    ]);
}

     public function exportPdf(Request $request, $jenis)
{
    Log::info('=== PalmexKombinasiController: exportPdf() === Jenis: ' . $jenis);
    
    $data = $request->all();
    
    Log::info('DATA MENTAH DARI REQUEST PALMEX KOMBINASI:', [
        'jenis' => $jenis,
        'data' => $data
    ]);
    
    // ============ DECODE HASIL YANG MUNGKIN JSON STRING ============
    $bagianKeys = ['bagian1', 'bagian2', 'bagian3', 'bagian4', 'total'];
    for ($i = 0; $i < count($bagianKeys); $i++) {
        $key = $bagianKeys[$i];
        if (isset($data[$key]['hasil'])) {
            if (is_string($data[$key]['hasil'])) {
                $decoded = json_decode($data[$key]['hasil'], true);
                if (is_array($decoded)) {
                    $data[$key]['hasil'] = $decoded;
                } else {
                    $data[$key]['hasil'] = [];
                }
            }
        }
    }
    
    // Decode detail_results juga
    if (isset($data['detail_results']) && is_string($data['detail_results'])) {
        $decoded = json_decode($data['detail_results'], true);
        if (is_array($decoded)) {
            $data['detail_results'] = $decoded;
        } else {
            $data['detail_results'] = [];
        }
    }
    
    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    $data['jenis'] = $jenis;
    
    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        // Kumpulkan semua hasil dari semua bagian - menggunakan for
        for ($i = 0; $i < count($bagianKeys); $i++) {
            $key = $bagianKeys[$i];
            if (isset($data[$key]['hasil']) && is_array($data[$key]['hasil'])) {
                $hasilCount = count($data[$key]['hasil']);
                for ($j = 0; $j < $hasilCount; $j++) {
                    $allResults[] = $data[$key]['hasil'][$j];
                }
            }
        }
        
        // Jika ada detail_results
        if (isset($data['detail_results']) && is_array($data['detail_results'])) {
            $detailCount = count($data['detail_results']);
            for ($i = 0; $i < $detailCount; $i++) {
                $allResults[] = $data['detail_results'][$i];
            }
        }
        
        Log::info('TOTAL RESULTS SEBELUM FILTER: ' . count($allResults));
        
        // Filter duplikat berdasarkan produk_id - menggunakan for
        $uniqueResults = [];
        $seenIds = [];
        
        $totalAll = count($allResults);
        for ($i = 0; $i < $totalAll; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? $item['id'] ?? $item['product_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            // Cek apakah sudah ada di seenIds - menggunakan for
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    Log::warning("DUPLIKAT SKIP:", ['id' => $produkId]);
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        Log::info('UNIQUE RESULTS:', [
            'total' => count($uniqueResults),
        ]);
        
        // Simpan ke database - menggunakan for
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error saving BOQ: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
    }
    
    // ============ PILIH VIEW BERDASARKAN JENIS ============
    $viewMap = [
        // Limasan + ...
        'limas-pelana' => 'boq.palmex.atap-kombinasi.pdf-palmex-limasan-pelana',
        'limasan-x' => 'boq.palmex.atap-kombinasi.pdf-palmex-limasan-x',
        'limasan-limasan' => 'boq.palmex.atap-kombinasi.pdf-palmex-limasan-limasan',
        'limasan-trapesium' => 'boq.palmex.atap-kombinasi.pdf-palmex-limasan-trapesium',
        
        // Pelana + ...
        'pelana-2-kemiringan' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-2-kemiringan',
        'pelana-2-sisi' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-2-sisi',
        'pelana-2trapesium' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-2trapesium',
        'pelana-3-arah' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-3-arah',
        'pelana-dinding' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-dinding',
        
        // Pelana X
        'pelana-x' => 'boq.palmex.atap-kombinasi.pdf-palmex-pelana-x',
        
        // Gergaji
        'gergaji' => 'boq.palmex.atap-kombinasi.pdf-palmex-gergaji',
        
        // Lengkung
        'lengkung-2-sisi' => 'boq.palmex.atap-kombinasi.pdf-palmex-lengkung-2-sisi',
    ];
    
    $view = $viewMap[$jenis] ?? 'boq.palmex.pdf-palmex-pelana';
    
    Log::info('VIEW SELECTED: ' . $view);
    
    return view($view, compact('data'));
}
    // ============================================================
    // FUNGSI BANTUAN (helper functions)
    // ============================================================
    
    private function formatResult($product, $qty, $area, $parameter)
    {
        return [
            'product_id' => $product->id,
            'produk_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $area,
            'qty' => $qty,
            'satuan' => $product->unit ? $product->unit->unit_name : 'unit',
            'harga_satuan' => $product->harga_price_list ?? 0,
            'total_harga' => $qty * ($product->harga_price_list ?? 0),
            'parameter' => $parameter
        ];
    }

    private function hitungQtyByArea($areaName, $aksesoris, $luasAtap, $panjangStarter, $panjangNok, $panjangFlashing, $panjangTalangJurai, $panjangWallFlashing, $waste, $sudut)
    {
        $satuan = $aksesoris->satuan_terkecil;
        $qty = 0;
        
        switch ($areaName) {
            case 'Starter':
                $qtyRaw = $panjangStarter / $satuan;
                break;
            case 'Nok':
                $qtyRaw = $panjangNok / $satuan;
                break;
            case 'Flashing':
                $qtyRaw = $panjangFlashing / $satuan;
                break;
            case 'Tepi':
                $qtyRaw = ($panjangStarter * 2) / $satuan;
                break;
            case 'Ridge Ventilator':
                $qtyRaw = $panjangNok / $satuan;
                break;
            case 'Saklar':
            case 'Listrik':
                $qtyRaw = $luasAtap / $satuan;
                break;
            default:
                $qtyRaw = 0;
        }
        
        if ($qtyRaw > 0) {
            $qty = ceil($qtyRaw + ($qtyRaw * $waste));
        }
        
        return $qty;
    }

    
}
