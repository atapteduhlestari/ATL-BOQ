<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaperoofKombinasiController extends Controller
{
     public function gergaji(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: gergaji() ===');
        
        $luasAtap = $request->luas_atap ?? 0;
        $starter = $request->starter ?? 0;
        $flashing = $request->flashing ?? 0;
        $nokJurai = $request->nok_jurai ?? 0;
        $panjangNok = $request->panjang_nok ?? 0;
        $panjangJurai = $request->panjang_jurai ?? 0;
        $sudut = $request->sudut ?? 0;
        $panjangBangunan = $request->panjang_bangunan ?? 0;
        $lebarBangunan = $request->lebar_bangunan ?? 0;
        $jumlahGerigi = $request->jumlah_gerigi ?? 0;
        $tinggiGerigi = $request->tinggi_gerigi ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        // DROPDOWN: TAPE ROOF NOK
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        // DROPDOWN: JURAI
        $areaJurai = ProductArea::where('slug', 'jurai')->first();
        $juraiOptions = collect();
        if ($areaJurai && $brand) {
            $juraiOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaJurai->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        return view('boq.taperoof.atap-kombinasi.taperoof-gergaji', compact(
            'nokOptions',
            'juraiOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap',
            'starter',
            'flashing',
            'nokJurai',
            'panjangNok',
            'panjangJurai',
            'sudut',
            'panjangBangunan',
            'lebarBangunan',
            'jumlahGerigi',
            'tinggiGerigi'
        ));
    }
    
    public function hitungGergaji(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungGergaji() ===');
        
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
        
        Log::info('HITUNG GERGAJI TAPE ROOF:', [
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
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        // 3. Nok Tutup - LOCK 2 (karena gergaji)
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
                $qty = 2;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
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
        // 10. PAKU & SCREW
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
        // 11. SCREW PLYWOOD
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
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL GERGAJI TAPE ROOF:', [
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
    
    private function formatResult($product, $qty, $area, $parameter)
    {
        return [
            'product_id' => $product->id,
            'produk_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $area,
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'pcs',
            'harga_satuan' => $product->harga_jual ?? 0,
            'total_harga' => ($product->harga_jual ?? 0) * $qty,
            'parameter' => $parameter . ' m'
        ];
    }
    
     public function exportPdfGergaji(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfGergaji() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF GERGAJI:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'gergaji';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Atap Gergaji TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Gergaji: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-gergaji';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-gergaji';
        }
        
        return view($view, compact('data'));
    }

      public function lengkung2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: lengkung2Sisi() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $tinggi = $request->tinggi ?? 0;
        $panjangB = $request->panjang_b ?? 0;
        
        $luasAtap3 = $request->luas_atap_3 ?? 0;
        $starter3 = $request->starter_3 ?? 0;
        $flashing3 = $request->flashing_3 ?? 0;
        $sudut3 = $request->sudut_3 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        $nok3 = $request->nok_3 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-lengkung-2-sisi', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'tinggi',
            'panjangB',
            'luasAtap3',
            'starter3',
            'flashing3',
            'sudut3',
            'totalNokJurai',
            'nok1',
            'nok2',
            'nok3',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungLengkung2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungLengkung2Sisi() ===');
        
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
        
        Log::info('HITUNG LENGKUNG 2 SISI TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 2;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
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
        
        // 8. PENANGKAL PETIR
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
        
        // 9. LANTAI KERJA
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
        
        // 10. PAKU & SCREW
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
        
        // 11. SCREW PLYWOOD
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
            $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
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
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL LENGKUNG 2 SISI TAPE ROOF:', [
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
    
    public function exportPdfLengkung2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfLengkung2Sisi() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF LENGKUNG 2 SISI:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'lengkung-2-sisi';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Lengkung 2 Sisi TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Lengkung 2 Sisi: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-lengkung-2-sisi';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-lengkung-2-sisi';
        }
        
        return view($view, compact('data'));
    }

     public function limasPelana(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: limasPelana() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-limas-pelana', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'totalNokJurai',
            'nok1',
            'nok2',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungLimasPelana(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungLimasPelana() ===');
        
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
        
        Log::info('HITUNG LIMAS PELANA TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 3;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
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
        
        // 8. PENANGKAL PETIR
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
        
        // 9. LANTAI KERJA
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
        
        // 10. PAKU & SCREW
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
        
        // 11. SCREW PLYWOOD
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
            $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
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
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL LIMAS PELANA TAPE ROOF:', [
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
    
    public function exportPdfLimasPelana(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfLimasPelana() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF LIMAS PELANA:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'limas-pelana';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Limas + Pelana TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Limas Pelana: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-limas-pelana';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limas-pelana';
        }
        
        return view($view, compact('data'));
    }

     public function limasanLimasan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: limasanLimasan() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-limasan-limasan', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'totalNokJurai',
            'nok1',
            'nok2',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungLimasanLimasan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungLimasanLimasan() ===');
        
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
        
        Log::info('HITUNG LIMASAN + LIMASAN TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 3;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
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
        
        // 8. PENANGKAL PETIR
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
        
        // 9. LANTAI KERJA
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
        
        // 10. PAKU & SCREW
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
        
        // 11. SCREW PLYWOOD
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
            $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
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
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL LIMASAN + LIMASAN TAPE ROOF:', [
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
    
    public function exportPdfLimasanLimasan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfLimasanLimasan() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF LIMASAN + LIMASAN:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'limasan-limasan';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Limasan + Limasan TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Limasan + Limasan: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-limasan-limasan';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-limasan';
        }
        
        return view($view, compact('data'));
    }

     public function limasanTrapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: limasanTrapesium() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-limasan-trapesium', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'totalNokJurai',
            'nok1',
            'nok2',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungLimasanTrapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungLimasanTrapesium() ===');
        
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
        
        Log::info('HITUNG LIMASAN + TRAPESIUM TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
            
            $results[] = [
                'product_id' => $produkAtap->id,
                'produk_id' => $produkAtap->id,
                'id' => $produkAtap->id,
                'nama_produk' => $produkAtap->nama_produk,
                'area' => 'Atap Utama',
                'qty' => $qty,
                'satuan' => 'm²',
                'harga_satuan' => $produkAtap->harga_jual ?? 0,
                'total_harga' => ($produkAtap->harga_jual ?? 0) * $qty,
                'parameter' => $luasAtap . ' m'
            ];
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
                        $results[] = [
                            'product_id' => $aksesoris->id,
                            'produk_id' => $aksesoris->id,
                            'id' => $aksesoris->id,
                            'nama_produk' => $aksesoris->nama_produk,
                            'area' => 'Starter',
                            'qty' => $qty,
                            'satuan' => 'm',
                            'harga_satuan' => $aksesoris->harga_jual ?? 0,
                            'total_harga' => ($aksesoris->harga_jual ?? 0) * $qty,
                            'parameter' => $luasAtap . ' m'
                        ];
                        $processedProductIds[] = $aksesoris->id;
                    }
                }
            }
        }
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
        if ($nokId && $panjangNokJurai > 0) {
            $nok = Product::with('unit')->find($nokId);
            if ($nok && !in_array($nok->id, $processedProductIds)) {
                $satuan = $nok->satuan_terkecil ?? 1;
                $qtyRaw = $panjangNokJurai / $satuan;
                $qty = ceil($qtyRaw + ($qtyRaw * $waste));
                $results[] = [
                    'product_id' => $nok->id,
                    'produk_id' => $nok->id,
                    'id' => $nok->id,
                    'nama_produk' => $nok->nama_produk,
                    'area' => 'Tape Roof Nok & Jurai',
                    'qty' => $qty,
                    'satuan' => 'm',
                    'harga_satuan' => $nok->harga_jual ?? 0,
                    'total_harga' => ($nok->harga_jual ?? 0) * $qty,
                    'parameter' => $panjangNokJurai . ' m'
                ];
                $processedProductIds[] = $nok->id;
            }
        }
        
        // 3. NOK TUTUP - LOCK 4
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
                $results[] = [
                    'product_id' => $nokTutup->id,
                    'produk_id' => $nokTutup->id,
                    'id' => $nokTutup->id,
                    'nama_produk' => $nokTutup->nama_produk,
                    'area' => 'Nok Tutup',
                    'qty' => $qty,
                    'satuan' => 'pcs',
                    'harga_satuan' => $nokTutup->harga_jual ?? 0,
                    'total_harga' => ($nokTutup->harga_jual ?? 0) * $qty,
                    'parameter' => $panjangNokJurai . ' m'
                ];
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
                $results[] = [
                    'product_id' => $underlayer->id,
                    'produk_id' => $underlayer->id,
                    'id' => $underlayer->id,
                    'nama_produk' => $underlayer->nama_produk,
                    'area' => 'Underlayer',
                    'qty' => $qty,
                    'satuan' => 'roll',
                    'harga_satuan' => $underlayer->harga_jual ?? 0,
                    'total_harga' => ($underlayer->harga_jual ?? 0) * $qty,
                    'parameter' => $luasAtap . ' m²'
                ];
                $processedProductIds[] = $underlayer->id;
            }
        }
        
        // 5. METAL FLASHING
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
                $results[] = [
                    'product_id' => $metalFlashing->id,
                    'produk_id' => $metalFlashing->id,
                    'id' => $metalFlashing->id,
                    'nama_produk' => $metalFlashing->nama_produk,
                    'area' => 'Metal Flashing',
                    'qty' => $qty,
                    'satuan' => 'm',
                    'harga_satuan' => $metalFlashing->harga_jual ?? 0,
                    'total_harga' => ($metalFlashing->harga_jual ?? 0) * $qty,
                    'parameter' => $panjangFlashing . ' m'
                ];
                $processedProductIds[] = $metalFlashing->id;
            }
        }
        
        // 6. WALL FLASHING
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
                $results[] = [
                    'product_id' => $wallFlashing->id,
                    'produk_id' => $wallFlashing->id,
                    'id' => $wallFlashing->id,
                    'nama_produk' => $wallFlashing->nama_produk,
                    'area' => 'Wall Flashing',
                    'qty' => $qty,
                    'satuan' => 'm',
                    'harga_satuan' => $wallFlashing->harga_jual ?? 0,
                    'total_harga' => ($wallFlashing->harga_jual ?? 0) * $qty,
                    'parameter' => $opsiDinding . ' m'
                ];
                $processedProductIds[] = $wallFlashing->id;
            }
        }
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => $satuanText,
                'harga_satuan' => $harga,
                'total_harga' => $harga * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'pcs';
            $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => $satuanText,
                'harga_satuan' => $harga,
                'total_harga' => $harga * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL LIMASAN + TRAPESIUM TAPE ROOF:', [
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
    
    public function exportPdfLimasanTrapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfLimasanTrapesium() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF LIMASAN + TRAPESIUM:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'limasan-trapesium';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Limasan + Trapesium TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Limasan + Trapesium: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-limasan-trapesium';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-trapesium';
        }
        
        return view($view, compact('data'));
    }
      public function limasanX(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: limasanX() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        $lebar1 = $request->lebar_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        $lebar2 = $request->lebar_2 ?? 0;
        
        $luasAtap3 = $request->luas_atap_3 ?? 0;
        $starter3 = $request->starter_3 ?? 0;
        $flashing3 = $request->flashing_3 ?? 0;
        $sudut3 = $request->sudut_3 ?? 0;
        $lebar3 = $request->lebar_3 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        $nok3 = $request->nok_3 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-limasan-x', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'lebar1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'lebar2',
            'luasAtap3',
            'starter3',
            'flashing3',
            'sudut3',
            'lebar3',
            'totalNokJurai',
            'nok1',
            'nok2',
            'nok3',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungLimasanX(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungLimasanX() ===');
        
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
        
        Log::info('HITUNG LIMASAN X TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 6
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
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => 'pcs',
                'harga_satuan' => $screwProduct->harga_jual ?? 0,
                'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => 'Box',
                'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
                'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL LIMASAN X TAPE ROOF:', [
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
    
    public function exportPdfLimasanX(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfLimasanX() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF LIMASAN X:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'limasan-x';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Limasan X TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Limasan X: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-limasan-x';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-x';
        }
        
        return view($view, compact('data'));
    }

     public function pelana2Kemiringan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: pelana2Kemiringan() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $luasAtap3 = $request->luas_atap_3 ?? 0;
        $starter3 = $request->starter_3 ?? 0;
        $flashing3 = $request->flashing_3 ?? 0;
        $sudut3 = $request->sudut_3 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        $nok3 = $request->nok_3 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-pelana-2-kemiringan', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'luasAtap3',
            'starter3',
            'flashing3',
            'sudut3',
            'totalNokJurai',
            'nok1',
            'nok2',
            'nok3',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungPelana2Kemiringan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungPelana2Kemiringan() ===');
        
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
        
        Log::info('HITUNG PELANA 2 KEMIRINGAN TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 2;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => 'pcs',
                'harga_satuan' => $screwProduct->harga_jual ?? 0,
                'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => 'Box',
                'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
                'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL PELANA 2 KEMIRINGAN TAPE ROOF:', [
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
    
    public function exportPdfPelana2Kemiringan(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfPelana2Kemiringan() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF PELANA 2 KEMIRINGAN:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'pelana-2-kemiringan';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Pelana 2 Kemiringan TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Pelana 2 Kemiringan: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-2-kemiringan';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-2-kemiringan';
        }
        
        return view($view, compact('data'));
    }

    public function pelana2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: pelana2Sisi() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $luasAtap3 = $request->luas_atap_3 ?? 0;
        $starter3 = $request->starter_3 ?? 0;
        $flashing3 = $request->flashing_3 ?? 0;
        $sudut3 = $request->sudut_3 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        $nok3 = $request->nok_3 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-pelana-2-sisi', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'luasAtap3',
            'starter3',
            'flashing3',
            'sudut3',
            'totalNokJurai',
            'nok1',
            'nok2',
            'nok3',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungPelana2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungPelana2Sisi() ===');
        
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
        
        Log::info('HITUNG PELANA 2 SISI TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 2;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => 'pcs',
                'harga_satuan' => $screwProduct->harga_jual ?? 0,
                'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => 'Box',
                'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
                'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL PELANA 2 SISI TAPE ROOF:', [
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
    
    public function exportPdfPelana2Sisi(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfPelana2Sisi() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF PELANA 2 SISI:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'pelana-2-sisi';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Pelana 2 Sisi TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Pelana 2 Sisi: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-2-sisi';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-2-sisi';
        }
        
        return view($view, compact('data'));
    }

     public function pelana2Trapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: pelana2Trapesium() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        
        $luasAtap3 = $request->luas_atap_3 ?? 0;
        $starter3 = $request->starter_3 ?? 0;
        $flashing3 = $request->flashing_3 ?? 0;
        $sudut3 = $request->sudut_3 ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        $nok3 = $request->nok_3 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-pelana-2trapesium', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'luasAtap3',
            'starter3',
            'flashing3',
            'sudut3',
            'totalNokJurai',
            'nok1',
            'nok2',
            'nok3',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungPelana2Trapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungPelana2Trapesium() ===');
        
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
        
        Log::info('HITUNG PELANA 2 TRAPESIUM TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 6;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => 'pcs',
                'harga_satuan' => $screwProduct->harga_jual ?? 0,
                'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => 'Box',
                'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
                'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL PELANA 2 TRAPESIUM TAPE ROOF:', [
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
    
    public function exportPdfPelana2Trapesium(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfPelana2Trapesium() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF PELANA 2 TRAPESIUM:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'pelana-2trapesium';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Pelana 2 Trapesium TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Pelana 2 Trapesium: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-2trapesium';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-2trapesium';
        }
        
        return view($view, compact('data'));
    }

    public function pelana3Arah(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: pelana3Arah() ===');
        
        $luasAtap1 = $request->luas_atap_1 ?? 0;
        $starter1 = $request->starter_1 ?? 0;
        $flashing1 = $request->flashing_1 ?? 0;
        $sudut1 = $request->sudut_1 ?? 0;
        $panjangA = $request->panjang_a ?? 0;
        $lebarA = $request->lebar_a ?? 0;
        
        $luasAtap2 = $request->luas_atap_2 ?? 0;
        $starter2 = $request->starter_2 ?? 0;
        $flashing2 = $request->flashing_2 ?? 0;
        $sudut2 = $request->sudut_2 ?? 0;
        $panjangB = $request->panjang_b ?? 0;
        $lebarB = $request->lebar_b ?? 0;
        
        $totalNokJurai = $request->total_nok_jurai ?? 0;
        $nok1 = $request->nok_1 ?? 0;
        $nok2 = $request->nok_2 ?? 0;
        
        $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
        $brandId = $brand->id ?? null;
        
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brand) {
            $nokOptions = Product::where('brand_id', $brand->id)
                ->where('area_id', $areaNok->id)
                ->with('unit')
                ->get();
        }
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        return view('boq.taperoof.atap-kombinasi.taperoof-pelana-3-arah', compact(
            'nokOptions',
            'rangkaOptions',
            'lantaiKerjaOptions',
            'luasAtap1',
            'starter1',
            'flashing1',
            'sudut1',
            'panjangA',
            'lebarA',
            'luasAtap2',
            'starter2',
            'flashing2',
            'sudut2',
            'panjangB',
            'lebarB',
            'totalNokJurai',
            'nok1',
            'nok2',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }
    
    public function hitungPelana3Arah(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: hitungPelana3Arah() ===');
        
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
        
        Log::info('HITUNG PELANA 3 ARAH TAPE ROOF:', [
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
        
        // 1. ATAP UTAMA
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
        
        // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
        
        // 3. NOK TUTUP - LOCK 4
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
                $qty = 3;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
            }
        }
        
        // 4. UNDERLAYER
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
        
        // 5. METAL FLASHING
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
        
        // 6. WALL FLASHING
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
        
        // 7. CEROBONG ASAP
        if ($opsiCerobong > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Cerobong Asap',
                'area' => 'Cerobong Asap',
                'qty' => $opsiCerobong,
                'satuan' => 'unit',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiCerobong . ' unit'
            ];
        }
        
        // 8. PENANGKAL PETIR
        if ($opsiPenangkal > 0) {
            $results[] = [
                'product_id' => null,
                'produk_id' => null,
                'id' => null,
                'nama_produk' => 'Penangkal Petir',
                'area' => 'Penangkal Petir',
                'qty' => $opsiPenangkal,
                'satuan' => 'meter',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'parameter' => $opsiPenangkal . ' meter'
            ];
        }
        
        // 9. LANTAI KERJA
        $luasPerLembar = 2.88;
        $qtyRaw = $luasAtap / $luasPerLembar;
        $qtyPlywood = ceil($qtyRaw + ($qtyRaw * $waste));
        
        $results[] = [
            'product_id' => null,
            'produk_id' => null,
            'id' => null,
            'nama_produk' => $lantaiKerja,
            'area' => 'Lantai Kerja',
            'qty' => $qtyPlywood,
            'satuan' => 'lembar',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'parameter' => $luasAtap . ' m²'
        ];
        
        // 10. PAKU & SCREW
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
            
            $results[] = [
                'product_id' => $screwProduct->id ?? null,
                'produk_id' => $screwProduct->id ?? null,
                'id' => $screwProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Paku & Screw',
                'qty' => $qtyScrew,
                'satuan' => 'Box',
                'harga_satuan' => $screwProduct->harga_jual ?? 0,
                'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
                'parameter' => $qtyAtapUtama . ' lembar atap'
            ];
        }
        
        // 11. SCREW PLYWOOD
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
            
            $results[] = [
                'product_id' => $screwPlywoodProduct->id ?? null,
                'produk_id' => $screwPlywoodProduct->id ?? null,
                'id' => $screwPlywoodProduct->id ?? null,
                'nama_produk' => $namaProduk,
                'area' => 'Screw Plywood',
                'qty' => $qtyScrewPlywood,
                'satuan' => 'pcs',
                'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
                'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
                'parameter' => $qtyPlywood . ' lembar plywood'
            ];
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL PELANA 3 ARAH TAPE ROOF:', [
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
    
    public function exportPdfPelana3Arah(Request $request)
    {
        Log::info('=== TaperoofKombinasiController: exportPdfPelana3Arah() ===');
        
        $data = $request->all();
        
        Log::info('DATA EXPORT PDF PELANA 3 ARAH:', [
            'data' => $data
        ]);
        
        $nomorBoq = Boq::generateNomorBoq();
        $data['nomor_boq'] = $nomorBoq;
        $data['model'] = 'pelana-3-arah';
        $data['tanggal'] = now()->format('d/m/Y');
        $data['judul'] = $data['judul'] ?? 'BOQ - Pelana 3 Arah TAPE ROOF';
        $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
        
        try {
            $results = $data['hasil'] ?? $data['results'] ?? [];
            
            if (is_string($results)) {
                $results = json_decode($results, true);
            }
            
            Log::info('TOTAL RESULTS: ' . count($results));
            
            $uniqueResults = [];
            $seenIds = [];
            
            foreach ($results as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                
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
            
            $grandTotal = 0;
            foreach ($uniqueResults as $item) {
                $grandTotal += $item['total_harga'] ?? 0;
            }
            $data['grand_total'] = $grandTotal;
            $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
            
            if (!empty($uniqueResults)) {
                $boq = new Boq();
                $boq->nomor_boq = $nomorBoq;
                $boq->tanggal_boq = now();
                $boq->save();
                
                foreach ($uniqueResults as $item) {
                    $produkId = $item['id'] ?? $item['product_id'] ?? null;
                    $qty = (int)($item['qty'] ?? 0);
                    
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
                
                Log::info('BOQ SAVED:', [
                    'boq_id' => $boq->id,
                    'total' => count($uniqueResults)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error export PDF Pelana 3 Arah: ' . $e->getMessage());
        }
        
        $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-3-arah';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-3-arah';
        }
        
        return view($view, compact('data'));
    }

    public function pelanaX(Request $request)
{
    Log::info('=== TaperoofKombinasiController: pelanaX() ===');
    
    $luasAtap1 = $request->luas_atap_1 ?? 0;
    $starter1 = $request->starter_1 ?? 0;
    $flashing1 = $request->flashing_1 ?? 0;
    $sudut1 = $request->sudut_1 ?? 0;
    $nok1 = $request->nok_1 ?? 0;
    $panjangA = $request->panjang_a ?? 0;
    $lebarA = $request->lebar_a ?? 0;
    
    $luasAtap2 = $request->luas_atap_2 ?? 0;
    $starter2 = $request->starter_2 ?? 0;
    $flashing2 = $request->flashing_2 ?? 0;
    $sudut2 = $request->sudut_2 ?? 0;
    $nok2 = $request->nok_2 ?? 0;
    $panjangB = $request->panjang_b ?? 0;
    $lebarB = $request->lebar_b ?? 0;
    
    $luasAtap3 = $request->luas_atap_3 ?? 0;
    $starter3 = $request->starter_3 ?? 0;
    $flashing3 = $request->flashing_3 ?? 0;
    $sudut3 = $request->sudut_3 ?? 0;
    $nok3 = $request->nok_3 ?? 0;
    $panjangC = $request->panjang_c ?? 0;
    $lebarC = $request->lebar_c ?? 0;
    
    $totalNokJurai = $request->total_nok_jurai ?? 0;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brand) {
        $nokOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaNok->id)
            ->with('unit')
            ->get();
    }
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    Log::info('DATA PELANA X TAPE ROOF:', [
        'luasAtap1' => $luasAtap1,
        'starter1' => $starter1,
        'flashing1' => $flashing1,
        'sudut1' => $sudut1,
        'nok1' => $nok1,
        'luasAtap2' => $luasAtap2,
        'starter2' => $starter2,
        'flashing2' => $flashing2,
        'sudut2' => $sudut2,
        'nok2' => $nok2,
        'luasAtap3' => $luasAtap3,
        'starter3' => $starter3,
        'flashing3' => $flashing3,
        'sudut3' => $sudut3,
        'nok3' => $nok3,
        'totalNokJurai' => $totalNokJurai
    ]);
    
    return view('boq.taperoof.atap-kombinasi.taperoof-pelana-x', compact(
        'nokOptions',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'luasAtap1',
        'starter1',
        'flashing1',
        'sudut1',
        'nok1',
        'panjangA',
        'lebarA',
        'luasAtap2',
        'starter2',
        'flashing2',
        'sudut2',
        'nok2',
        'panjangB',
        'lebarB',
        'luasAtap3',
        'starter3',
        'flashing3',
        'sudut3',
        'nok3',
        'panjangC',
        'lebarC',
        'totalNokJurai',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelanaX(Request $request)
{
    Log::info('=== TaperoofKombinasiController: hitungPelanaX() ===');
    
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
    
    Log::info('HITUNG PELANA X TAPE ROOF:', [
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
    // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
    // 3. NOK TUTUP - LOCK 4
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
            'id' => null,
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
            'id' => null,
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
        'id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 10. PAKU & SCREW
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
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => 'pcs',
            'harga_satuan' => $screwProduct->harga_jual ?? 0,
            'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 11. SCREW PLYWOOD
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
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => 'Box',
            'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
            'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA X TAPE ROOF:', [
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

public function exportPdfPelanaX(Request $request)
{
    Log::info('=== TaperoofKombinasiController: exportPdfPelanaX() ===');
    
    $data = $request->all();
    
    Log::info('DATA EXPORT PDF PELANA X:', [
        'data' => $data
    ]);
    
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    $data['model'] = 'pelana-x';
    $data['tanggal'] = now()->format('d/m/Y');
    $data['judul'] = $data['judul'] ?? 'BOQ - Pelana X TAPE ROOF';
    $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
    
    try {
        $results = $data['hasil'] ?? $data['results'] ?? [];
        
        if (is_string($results)) {
            $results = json_decode($results, true);
        }
        
        Log::info('TOTAL RESULTS: ' . count($results));
        
        $uniqueResults = [];
        $seenIds = [];
        
        foreach ($results as $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
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
        
        $grandTotal = 0;
        foreach ($uniqueResults as $item) {
            $grandTotal += $item['total_harga'] ?? 0;
        }
        $data['grand_total'] = $grandTotal;
        $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
        
        // ============================================================
        // TAMBAHKAN DATA PARAMETER UNTUK PDF
        // ============================================================
        $data['luas_atap_1'] = $request->luas_atap_1 ?? 0;
        $data['starter_1'] = $request->starter_1 ?? 0;
        $data['flashing_1'] = $request->flashing_1 ?? 0;
        $data['nok_1'] = $request->nok_1 ?? 0;
        $data['sudut_1'] = $request->sudut_1 ?? 0;
        $data['panjang_a'] = $request->panjang_a ?? 0;
        $data['lebar_a'] = $request->lebar_a ?? 0;
        
        $data['luas_atap_2'] = $request->luas_atap_2 ?? 0;
        $data['starter_2'] = $request->starter_2 ?? 0;
        $data['flashing_2'] = $request->flashing_2 ?? 0;
        $data['nok_2'] = $request->nok_2 ?? 0;
        $data['sudut_2'] = $request->sudut_2 ?? 0;
        $data['panjang_b'] = $request->panjang_b ?? 0;
        $data['lebar_b'] = $request->lebar_b ?? 0;
        
        $data['luas_atap_3'] = $request->luas_atap_3 ?? 0;
        $data['starter_3'] = $request->starter_3 ?? 0;
        $data['flashing_3'] = $request->flashing_3 ?? 0;
        $data['nok_3'] = $request->nok_3 ?? 0;
        $data['sudut_3'] = $request->sudut_3 ?? 0;
        $data['panjang_c'] = $request->panjang_c ?? 0;
        $data['lebar_c'] = $request->lebar_c ?? 0;
        
        $data['total_nok_jurai'] = $request->total_nok_jurai ?? 0;
        $data['luas_atap'] = $request->luas_atap ?? 0;
        $data['starter'] = $request->starter ?? 0;
        $data['nok_jurai'] = $request->nok_jurai ?? 0;
        $data['flashing'] = $request->flashing ?? 0;
        $data['sudut'] = $request->sudut ?? 30;
        $data['waste'] = $request->waste ?? 5;
        $data['rangka'] = $request->rangka ?? 'Baja Ringan';
        $data['lantai_kerja'] = $request->lantai_kerja ?? 'Plywood 9 mm';
        $data['opsi_dinding'] = $request->opsi_dinding ?? 0;
        $data['opsi_cerobong'] = $request->opsi_cerobong ?? 0;
        $data['opsi_penangkal'] = $request->opsi_penangkal ?? 0;
        
        if (!empty($uniqueResults)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            foreach ($uniqueResults as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
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
            
            Log::info('BOQ SAVED:', [
                'boq_id' => $boq->id,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error export PDF Pelana X: ' . $e->getMessage());
    }
    
    $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-x';
    
    if (!view()->exists($view)) {
        $view = 'boq.taperoof.pdf-taperoof-pelana-x';
    }
    
    return view($view, compact('data'));
}

public function pelanaDinding(Request $request)
{
    Log::info('=== TaperoofKombinasiController: pelanaDinding() ===');
    
    $luasAtap1 = $request->luas_atap_1 ?? 0;
    $starter1 = $request->starter_1 ?? 0;
    $flashing1 = $request->flashing_1 ?? 0;
    $sudut1 = $request->sudut_1 ?? 0;
    $nok1 = $request->nok_1 ?? 0;
    $panjang = $request->panjang ?? 0;
    $lebar = $request->lebar ?? 0;
    
    $luasAtap2 = $request->luas_atap_2 ?? 0;
    $starter2 = $request->starter_2 ?? 0;
    $flashing2 = $request->flashing_2 ?? 0;
    $nok2 = $request->nok_2 ?? 0;
    $wallFlashing = $request->wall_flashing ?? 0;
    $panjangDinding = $request->panjang_dinding ?? 0;
    $tinggiDinding = $request->tinggi_dinding ?? 0;
    $jumlahSisi = $request->jumlah_sisi ?? 0;
    $totalLuasDinding = $request->total_luas_dinding ?? 0;
    $totalWallFlashing = $request->total_wall_flashing ?? 0;
    
    $totalNokJurai = $request->total_nok_jurai ?? 0;
    
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('nama_brand', 'TAPE ROOF')->first();
    $brandId = $brand->id ?? null;
    
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brand) {
        $nokOptions = Product::where('brand_id', $brand->id)
            ->where('area_id', $areaNok->id)
            ->with('unit')
            ->get();
    }
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    Log::info('DATA PELANA + DINDING TAPE ROOF:', [
        'luasAtap1' => $luasAtap1,
        'starter1' => $starter1,
        'flashing1' => $flashing1,
        'sudut1' => $sudut1,
        'nok1' => $nok1,
        'luasAtap2' => $luasAtap2,
        'starter2' => $starter2,
        'flashing2' => $flashing2,
        'nok2' => $nok2,
        'wallFlashing' => $wallFlashing,
        'totalNokJurai' => $totalNokJurai,
        'totalLuasDinding' => $totalLuasDinding,
        'totalWallFlashing' => $totalWallFlashing
    ]);
    
    return view('boq.taperoof.atap-kombinasi.taperoof-pelana-dinding', compact(
        'nokOptions',
        'rangkaOptions',
        'lantaiKerjaOptions',
        'luasAtap1',
        'starter1',
        'flashing1',
        'sudut1',
        'nok1',
        'panjang',
        'lebar',
        'luasAtap2',
        'starter2',
        'flashing2',
        'nok2',
        'wallFlashing',
        'panjangDinding',
        'tinggiDinding',
        'jumlahSisi',
        'totalLuasDinding',
        'totalWallFlashing',
        'totalNokJurai',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelanaDinding(Request $request)
{
    Log::info('=== TaperoofKombinasiController: hitungPelanaDinding() ===');
    
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
    
    Log::info('HITUNG PELANA + DINDING TAPE ROOF:', [
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
    // 2. TAPE ROOF NOK & JURAI (dari dropdown)
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
    // 3. NOK TUTUP - LOCK 4
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
    // 6. WALL FLASHING (dari opsi)
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
            'id' => null,
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
            'id' => null,
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
        'id' => null,
        'nama_produk' => $lantaiKerja,
        'area' => 'Lantai Kerja',
        'qty' => $qtyPlywood,
        'satuan' => 'lembar',
        'harga_satuan' => 0,
        'total_harga' => 0,
        'parameter' => $luasAtap . ' m²'
    ];
    
    // ============================================================
    // 10. PAKU & SCREW
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
        
        $results[] = [
            'product_id' => $screwProduct->id ?? null,
            'produk_id' => $screwProduct->id ?? null,
            'id' => $screwProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Paku & Screw',
            'qty' => $qtyScrew,
            'satuan' => 'pcs',
            'harga_satuan' => $screwProduct->harga_jual ?? 0,
            'total_harga' => ($screwProduct->harga_jual ?? 0) * $qtyScrew,
            'parameter' => $qtyAtapUtama . ' lembar atap'
        ];
    }
    
    // ============================================================
    // 11. SCREW PLYWOOD
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
        
        $results[] = [
            'product_id' => $screwPlywoodProduct->id ?? null,
            'produk_id' => $screwPlywoodProduct->id ?? null,
            'id' => $screwPlywoodProduct->id ?? null,
            'nama_produk' => $namaProduk,
            'area' => 'Screw Plywood',
            'qty' => $qtyScrewPlywood,
            'satuan' => 'pcs',
            'harga_satuan' => $screwPlywoodProduct->harga_jual ?? 0,
            'total_harga' => ($screwPlywoodProduct->harga_jual ?? 0) * $qtyScrewPlywood,
            'parameter' => $qtyPlywood . ' lembar plywood'
        ];
    }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA + DINDING TAPE ROOF:', [
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

public function exportPdfPelanaDinding(Request $request)
{
    Log::info('=== TaperoofKombinasiController: exportPdfPelanaDinding() ===');
    
    $data = $request->all();
    
    Log::info('DATA EXPORT PDF PELANA + DINDING:', [
        'data' => $data
    ]);
    
    $nomorBoq = Boq::generateNomorBoq();
    $data['nomor_boq'] = $nomorBoq;
    $data['model'] = 'pelana-dinding';
    $data['tanggal'] = now()->format('d/m/Y');
    $data['judul'] = $data['judul'] ?? 'BOQ - Pelana + Dinding TAPE ROOF';
    $data['brand'] = $data['brand'] ?? 'TAPE ROOF';
    
    try {
        $results = $data['hasil'] ?? $data['results'] ?? [];
        
        if (is_string($results)) {
            $results = json_decode($results, true);
        }
        
        Log::info('TOTAL RESULTS: ' . count($results));
        
        $uniqueResults = [];
        $seenIds = [];
        
        foreach ($results as $item) {
            $produkId = $item['id'] ?? $item['product_id'] ?? null;
            
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
        
        $grandTotal = 0;
        foreach ($uniqueResults as $item) {
            $grandTotal += $item['total_harga'] ?? 0;
        }
        $data['grand_total'] = $grandTotal;
        $data['grand_total_formatted'] = 'Rp ' . number_format($grandTotal, 0, ',', '.');
        
        // ============================================================
        // TAMBAHKAN DATA PARAMETER UNTUK PDF
        // ============================================================
        $data['luas_atap_1'] = $request->luas_atap_1 ?? 0;
        $data['starter_1'] = $request->starter_1 ?? 0;
        $data['flashing_1'] = $request->flashing_1 ?? 0;
        $data['nok_1'] = $request->nok_1 ?? 0;
        $data['sudut_1'] = $request->sudut_1 ?? 0;
        $data['panjang'] = $request->panjang ?? 0;
        $data['lebar'] = $request->lebar ?? 0;
        
        $data['luas_atap_2'] = $request->luas_atap_2 ?? 0;
        $data['starter_2'] = $request->starter_2 ?? 0;
        $data['flashing_2'] = $request->flashing_2 ?? 0;
        $data['nok_2'] = $request->nok_2 ?? 0;
        $data['wall_flashing'] = $request->wall_flashing ?? 0;
        $data['panjang_dinding'] = $request->panjang_dinding ?? 0;
        $data['tinggi_dinding'] = $request->tinggi_dinding ?? 0;
        $data['jumlah_sisi'] = $request->jumlah_sisi ?? 0;
        $data['total_luas_dinding'] = $request->total_luas_dinding ?? 0;
        $data['total_wall_flashing'] = $request->total_wall_flashing ?? 0;
        
        $data['total_nok_jurai'] = $request->total_nok_jurai ?? 0;
        $data['luas_atap'] = $request->luas_atap ?? 0;
        $data['starter'] = $request->starter ?? 0;
        $data['nok_jurai'] = $request->nok_jurai ?? 0;
        $data['flashing'] = $request->flashing ?? 0;
        $data['sudut'] = $request->sudut ?? 30;
        $data['waste'] = $request->waste ?? 5;
        $data['rangka'] = $request->rangka ?? 'Baja Ringan';
        $data['lantai_kerja'] = $request->lantai_kerja ?? 'Plywood 9 mm';
        $data['opsi_dinding'] = $request->opsi_dinding ?? 0;
        $data['opsi_cerobong'] = $request->opsi_cerobong ?? 0;
        $data['opsi_penangkal'] = $request->opsi_penangkal ?? 0;
        
        if (!empty($uniqueResults)) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            foreach ($uniqueResults as $item) {
                $produkId = $item['id'] ?? $item['product_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
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
            
            Log::info('BOQ SAVED:', [
                'boq_id' => $boq->id,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error export PDF Pelana + Dinding: ' . $e->getMessage());
    }
    
    $view = 'boq.taperoof.atap-kombinasi.pdf-taperoof-pelana-dinding';
    
    if (!view()->exists($view)) {
        $view = 'boq.taperoof.pdf-taperoof-pelana-dinding';
    }
    
    return view($view, compact('data'));
}
}