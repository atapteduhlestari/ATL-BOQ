<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MahaspanroofKombinasiController extends Controller
{
public function gergaji(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: gergaji() ===');
    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    

    
    // ============================================================
    // 2. DROPDOWN: TAPE ROOF NOK
    // ============================================================
    $areaNok = ProductArea::where('id', '83')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. DROPDOWN: NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('id', '84')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        // Cari Nok Tutup dengan product_tipe_id yang sama
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING GERGAJI MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-gergaji', compact(
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
    Log::info('=== MaharoofKombinasiController: hitungGergaji() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // AMBIL JUMLAH GERIGI DARI REQUEST
    $jumlahGerigi = $request->jumlah_gerigi ?? 0;
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
                $q->where('id', '84');
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
    
    Log::info('HITUNG GERGAJI MAHAROOF:', [
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
        'nokTutupId' => $nokTutupId,
        'productTipeId' => $productTipeId,
        'productTipeKode' => $productTipeKode,
        'jumlahGerigi' => $jumlahGerigi,
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Jika sudut > 15 derajat, kurangi 0.1
    // Jika sudut <= 15 derajat, kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Hindari pembagian dengan nol
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1; // fallback minimal
    }
    
    // Qty = (Luas / Panjang Efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + ($waste / 100));
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 4. Nok Tutup - QTY = JUMLAH GERIGI x 2
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0 && $jumlahGerigi > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = $jumlahGerigi * 2;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
            
            Log::info('NOK TUTUP DIGUNAKAN:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $nokTutup->product_tipe_id,
                'qty' => $qty,
                'jumlah_gerigi' => $jumlahGerigi
            ]);
        }
    } else {
        Log::warning('NOK TUTUP TIDAK DIGUNAKAN:', [
            'nokTutupId' => $nokTutupId,
            'panjangNokJurai' => $panjangNokJurai,
            'jumlahGerigi' => $jumlahGerigi
        ]);
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - GERGAJI:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// ============================================================
if ($luasAtap > 0) {
    $screwAtapProduct = Product::with('unit')->find(378);
    
    if ($screwAtapProduct) {
        $satuan = $screwAtapProduct->satuan_terkecil ?? 1;
        
        // Rumus: luas atap × satuan terkecil
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtapProduct->id,
            'produk_id'     => $screwAtapProduct->id,
            'nama_produk'   => $screwAtapProduct->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtapProduct->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtapProduct->harga_jual ?? 0,
            'total_harga'   => ($screwAtapProduct->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// ============================================================
if ($luasAtap > 0) {
    $screwNokProduct = Product::with('unit')->find(379);
    
    if ($screwNokProduct) {
        $satuan = $screwNokProduct->satuan_terkecil ?? 1;
        
        // Rumus: luas atap × satuan terkecil
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNokProduct->id,
            'produk_id'     => $screwNokProduct->id,
            'nama_produk'   => $screwNokProduct->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNokProduct->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNokProduct->harga_jual ?? 0,
            'total_harga'   => ($screwNokProduct->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}
    
    //   // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL GERGAJI MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-gergaji';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-gergaji';
        }
        
        return view($view, compact('data'));
    }
public function lengkung2Sisi(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: lengkung2Sisi() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. DROPDOWN: TAPE ROOF NOK
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. DROPDOWN: NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        // Cari Nok Tutup dengan product_tipe_id yang sama
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING LENGKUNG 2 SISI MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-lengkung-2-sisi', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungLengkung2Sisi(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungLengkung2Sisi() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);

    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
    
    Log::info('HITUNG LENGKUNG 2 SISI MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 4. NOK TUTUP - QTY = 2
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
            
            Log::info('NOK TUTUP DIGUNAKAN:', [
                'id' => $nokTutup->id,
                'nama' => $nokTutup->nama_produk,
                'product_tipe_id' => $nokTutup->product_tipe_id,
                'qty' => $qty
            ]);
        }
    } else {
        Log::warning('NOK TUTUP TIDAK DIGUNAKAN:', [
            'nokTutupId' => $nokTutupId,
            'panjangNokJurai' => $panjangNokJurai
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LENGKUNG 2 SISI:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    //  // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LENGKUNG 2 SISI MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-lengkung-2-sisi';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-lengkung-2-sisi';
        }
        
        return view($view, compact('data'));
    }

public function limasPelana(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: limasPelana() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. DROPDOWN: TAPE ROOF NOK
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. DROPDOWN: NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING LIMAS PELANA MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-limas-pelana', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungLimasPelana(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungLimasPelana() ===');
    
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
    $panjangAtap = $request->input('panjang_atap', 0);
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
    
    Log::info('HITUNG LIMAS PELANA MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 3 (LIMAS + PELANA)
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 3;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMAS PELANA:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    
    //  // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMAS PELANA MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-limas-pelana';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limas-pelana';
        }
        
        return view($view, compact('data'));
    }

public function limasanLimasan(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: limasanLimasan() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. DROPDOWN: TAPE ROOF NOK
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. DROPDOWN: NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING LIMASAN LIMASAN MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-limasan-limasan', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungLimasanLimasan(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungLimasanLimasan() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
    
    Log::info('HITUNG LIMASAN + LIMASAN MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 3 (LIMASAN + LIMASAN)
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 3;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMASAN + LIMASAN:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    //  // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN + LIMASAN MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-limasan-limasan';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-limasan';
        }
        
        return view($view, compact('data'));
    }

public function limasanTrapesium(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: limasanTrapesium() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK 3 ARAH
    // ============================================================
    $areaNok3Arah = ProductArea::where('slug', 'nok-3-arah')->first();
    $nok3ArahOptions = collect();
    if ($areaNok3Arah && $brandId) {
        $nok3ArahOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok3Arah->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 5. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        $nok3Arah = $nok3ArahOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? '',
            'nok3ArahId' => $nok3Arah->id ?? null,
            'nok3ArahName' => $nok3Arah->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING LIMASAN TRAPESIUM MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    $nok3ArahTerpilih = $request->nok_3_arah ?? null;
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-limasan-trapesium', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nok3ArahOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal',
        'nok3ArahTerpilih'
    ));
}

public function hitungLimasanTrapesium(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungLimasanTrapesium() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $jenisAtapId = $request->jenis_atap_id ?? null;
    $nokId = $request->nok_id ?? null;
    $nokTutupId = $request->nok_tutup_id ?? null;
    $nok3ArahId = $request->nok_3_arah_id ?? null;
    $rangka = $request->rangka ?? 'Baja Ringan';
    $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
    
    // ============================================================
    // CEK APAKAH INSULASI AKTIF (MAHAROOF)
    // ============================================================
    $insulasiAktif = $request->insulasi ?? false;
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // AUTO-SELECT NOK TUTUP & NOK 3 ARAH BERDASARKAN PRODUCT_TIPE_ID
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
    
    Log::info('HITUNG LIMASAN + TRAPESIUM MAHAROOF:', [
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
        'nokTutupId' => $nokTutupId,
        'nok3ArahId' => $nok3ArahId,
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 4 (LIMASAN + TRAPESIUM)
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
    // 5. NOK 3 ARAH - QTY = 2
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMASAN + TRAPESIUM:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    //  // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN + TRAPESIUM MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-limasan-trapesium';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-trapesium';
        }
        
        return view($view, compact('data'));
    }
public function limasanX(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: limasanX() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING LIMASAN X MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-limasan-x', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungLimasanX(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungLimasanX() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
     $panjangAtap = $request->input('panjang_atap', 0);
    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG LIMASAN X MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 4 (LIMASAN X)
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - LIMASAN X:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    
    // // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL LIMASAN X MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-limasan-x';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-limasan-x';
        }
        
        return view($view, compact('data'));
    }

public function pelana2Kemiringan(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: pelana2Kemiringan() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING PELANA 2 KEMIRINGAN MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-2-kemiringan', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelana2Kemiringan(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungPelana2Kemiringan() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG PELANA 2 KEMIRINGAN MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 2 (PELANA 2 KEMIRINGAN)
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA 2 KEMIRINGAN:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    //   // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA 2 KEMIRINGAN MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-2-kemiringan';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-2-kemiringan';
        }
        
        return view($view, compact('data'));
    }

public function pelana2Sisi(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: pelana2Sisi() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING PELANA 2 SISI MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-2-sisi', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelana2Sisi(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungPelana2Sisi() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
    
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG PELANA 2 SISI MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 2 (PELANA 2 SISI)
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 2;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA 2 SISI:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    
    //  // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA 2 SISI MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-2-sisi';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-2-sisi';
        }
        
        return view($view, compact('data'));
    }

public function pelana2Trapesium(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: pelana2Trapesium() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING PELANA 2 TRAPESIUM MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-2trapesium', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelana2Trapesium(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungPelana2Trapesium() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG PELANA 2 TRAPESIUM MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 6 (PELANA 2 TRAPESIUM)
    // ============================================================
    if ($nokTutupId && $panjangNokJurai > 0) {
        $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
        if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
            $qty = 6;
            $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
            $processedProductIds[] = $nokTutup->id;
        }
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA 2 TRAPESIUM:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    
    //   // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA 2 TRAPESIUM MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-2-trapesium';
        
        if (!view()->exists($view)) {
            $view = 'boq.ecoroof.pdf-ecoroof-pelana-2-trapesium';
        }
        
        return view($view, compact('data'));
    }

public function pelana3Arah(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: pelana3Arah() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING PELANA 3 ARAH MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-3-arah', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelana3Arah(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungPelana3Arah() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG PELANA 3 ARAH MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 4 (PELANA 3 ARAH)
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA 3 ARAH:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
//    // ============================================================
//         // 13. SCREW PLYWOOD - MASTER ROOF
//         // ============================================================
//         $qtyPlywood = 0;
//         foreach ($results as $result) {
//             if ($result['area'] == 'Lantai Kerja') {
//                 $qtyPlywood = $result['qty'];
//                 break;
//             }
//         }
        
//         if ($qtyPlywood > 0) {
//             $screwPlywoodProduct = null;
//             if ($brandId) {
//                 $screwPlywoodProduct = Product::where('brand_id', $brandId)
//                     ->whereHas('area', function($q) {
//                         $q->where('slug', 'screw-plywood');
//                     })
//                     ->with('unit')
//                     ->first();
//             }
            
//             $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
//             $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
//             $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
//             $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
//             $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
//             $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
//             $results[] = [
//                 'product_id' => $screwPlywoodProduct->id ?? null,
//                 'produk_id' => $screwPlywoodProduct->id ?? null,
//                 'nama_produk' => $namaProduk,
//                 'area' => 'Screw Plywood',
//                 'qty' => $qtyScrewPlywood,
//                 'satuan' => $satuanText,
//                 'harga_satuan' => $harga,
//                 'total_harga' => $harga * $qtyScrewPlywood,
//                 'parameter' => $qtyPlywood . ' lembar plywood'
//             ];
//         }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA 3 ARAH MAHAROOF:', [
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
        
        $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-3-arah';
        
        if (!view()->exists($view)) {
            $view = 'boq.taperoof.pdf-taperoof-pelana-3-arah';
        }
        
        return view($view, compact('data'));
    }

 public function pelanaX(Request $request)
    {
        Log::info('=== MasterRoofPelanaXController: pelanaX() ===');
        
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
        
        $opsiKaca = $request->opsi_kaca ?? 0;
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        // MASTER ROOF brand_id = 21
        $brand = ProductBrand::where('id', '23')->first();
        $brandId = $brand->id ?? null;
        
        // ============================================================
        // 1. JENIS ATAP
        // ============================================================
        $jenisAtapOptions = [];
        if ($brandId) {
            $jenisAtapOptions = Product::where('brand_id', $brandId)
                ->whereHas('area', function($q) {
                    $q->where('slug', 'atap-utama');
                })
                ->with(['unit', 'productTipe'])
                ->get();
        }
        
        // ============================================================
        // 2. PRODUK NOK (Nok Biasa)
        // ============================================================
        $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
        $nokOptions = collect();
        if ($areaNok && $brandId) {
            $nokOptions = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNok->id)
                ->with(['unit', 'productTipe'])
                ->get();
        }
        
        // ============================================================
        // 3. PRODUK NOK TUTUP
        // ============================================================
        $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
        $nokTutupOptions = collect();
        if ($areaNokTutup && $brandId) {
            $nokTutupOptions = Product::where('brand_id', $brandId)
                ->where('area_id', $areaNokTutup->id)
                ->with(['unit', 'productTipe'])
                ->get();
        }
        
        // ============================================================
        // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
        // ============================================================
        $nokMapping = [];
        foreach ($nokOptions as $nok) {
            $tipeId = $nok->product_tipe_id;
            $tipe = $nok->productTipe->kode_tipe ?? 'U';
            
            $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
            
            $nokMapping[$nok->id] = [
                'tipe' => $tipe,
                'productTipeId' => $tipeId,
                'nokTutupId' => $nokTutup->id ?? null,
                'nokTutupName' => $nokTutup->nama_produk ?? ''
            ];
        }
        
        Log::info('NOK MAPPING PELANA X MASTER ROOF:', $nokMapping);
        
        $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
        $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
        
        Log::info('DATA PELANA X MASTER ROOF:', [
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
        
        return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-x', compact(
            'jenisAtapOptions',
            'nokOptions',
            'nokTutupOptions',
            'nokMapping',
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
            'opsiKaca',
            'opsiDinding',
            'opsiCerobong',
            'opsiPenangkal'
        ));
    }

    /**
     * Calculate material for Master Roof Pelana X
     */
     public function hitungPelanaX(Request $request)
    {
        Log::info('=== MaharoofPelanaXController: hitungPelanaX() ===');
        
        $luasAtap = $request->luas_atap ?? 0;
        $panjangStarter = $request->panjang_starter ?? 0;
        $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
        $panjangFlashing = $request->panjang_flashing ?? 0;
        $sudut = $request->sudut ?? 0;
        $waste = $request->waste / 100;
        $panjangAtap = $request->input('panjang_atap', 0);
        
        $opsiKaca = $request->opsi_kaca ?? 0;
        $opsiDinding = $request->opsi_dinding ?? 0;
        $opsiCerobong = $request->opsi_cerobong ?? 0;
        $opsiPenangkal = $request->opsi_penangkal ?? 0;
        
        // Ambil jenis_atap_id dari request
        $jenisAtapId = $request->jenis_atap_id ?? null;
        $nokId = $request->nok_id ?? null;
        $nokTutupId = $request->nok_tutup_id ?? null;
        $rangka = $request->rangka ?? 'Baja Ringan';
        $lantaiKerja = $request->lantai_kerja ?? 'Plywood 9 mm';
        
        // INSULASI - Fitur tambahan MAHAROOF
        $insulasi = $request->insulasi ?? false;
        
        // MAHAROOF brand_id = 22
        $brand = ProductBrand::where('id', '23')->first();
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
            }
        }
        
        Log::info('HITUNG PELANA X MAHAROOF:', [
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
            'nokTutupId' => $nokTutupId,
            'productTipeId' => $productTipeId,
            'productTipeKode' => $productTipeKode,
            'brandId' => $brandId,
            'insulasi' => $insulasi
        ]);
        
        $results = [];
        $processedProductIds = [];
        
        // ============================================================
        // 1. ATAP UTAMA - MAHAROOF (Gunakan jenis_atap_id yang dipilih)
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
    $results[] = $this->formatResult($produkAtap, $qty, 'Atap Utama', $luasAtap);
    $processedProductIds[] = $produkAtap->id;
}
       // ============================================================
// 1b. INSULASI - Fitur tambahan MAHAROOF
// ============================================================
if ($insulasi && $brandId) {
    $produkInsulasiList = Product::where('brand_id', $brandId)
        ->whereHas('area', function($q) {
            $q->where('slug', 'insulasi');
        })
        ->with('unit')
        ->get();
    
    if ($produkInsulasiList->isNotEmpty()) {
        foreach ($produkInsulasiList as $produkInsulasi) {
            // Cek apakah produk sudah diproses
            if (in_array($produkInsulasi->id, $processedProductIds)) {
                continue;
            }
            
            $satuan = $produkInsulasi->satuan_terkecil ?? 1;
            $luasDenganWaste = $luasAtap + ($luasAtap * $waste);
            $qty = ceil($luasDenganWaste / $satuan);
            
            $results[] = $this->formatResult($produkInsulasi, $qty, 'Insulasi', $luasAtap);
            $processedProductIds[] = $produkInsulasi->id;
            
            Log::info('INSULASI DITAMBAHKAN:', [
                'product_id' => $produkInsulasi->id,
                'nama' => $produkInsulasi->nama_produk,
                'qty' => $qty,
                'luasAtap' => $luasAtap
            ]);
        }
    } else {
        Log::warning('PRODUK INSULASI TIDAK DITEMUKAN UNTUK MAHAROOF');
    }
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
        // 3. TAPE ROOF NOK & JURAI
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
        // 4. NOK TUTUP - QTY = 4 (PELANA X)
        // ============================================================
        if ($nokTutupId && $panjangNokJurai > 0) {
            $nokTutup = Product::with(['unit', 'productTipe'])->find($nokTutupId);
            if ($nokTutup && !in_array($nokTutup->id, $processedProductIds)) {
                $qty = 4;
                $results[] = $this->formatResult($nokTutup, $qty, 'Nok Tutup', $panjangNokJurai);
                $processedProductIds[] = $nokTutup->id;
                
                Log::info('NOK TUTUP DIGUNAKAN:', [
                    'id' => $nokTutup->id,
                    'nama' => $nokTutup->nama_produk,
                    'product_tipe_id' => $nokTutup->product_tipe_id,
                    'qty' => $qty
                ]);
            }
        } else {
            Log::warning('NOK TUTUP TIDAK DIGUNAKAN:', [
                'nokTutupId' => $nokTutupId,
                'panjangNokJurai' => $panjangNokJurai
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
        
        // // ============================================================
        // // 11. LANTAI KERJA
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
// 12a. SCREW ATAP - MANUAL ID (378) - MAHAROOF
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 12b. SCREW NOK & JURAI - MANUAL ID (379) - MAHAROOF
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
        
        //   // ============================================================
        // // 13. SCREW PLYWOOD - MASTER ROOF
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
        
        $grandTotal = collect($results)->sum('total_harga');
        
        Log::info('HASIL PELANA X MAHAROOF:', [
            'total_items' => count($results),
            'grand_total' => $grandTotal,
            'areas' => array_column($results, 'area'),
            'insulasi' => $insulasi
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
    
    $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-x';
    
    if (!view()->exists($view)) {
        $view = 'boq.taperoof.pdf-taperoof-pelana-x';
    }
    
    return view($view, compact('data'));
}

public function pelanaDinding(Request $request)
{
    Log::info('=== MasterRoofKombinasiController: pelanaDinding() ===');
    
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
    
    $opsiKaca = $request->opsi_kaca ?? 0;
    $opsiDinding = $request->opsi_dinding ?? 0;
    $opsiCerobong = $request->opsi_cerobong ?? 0;
    $opsiPenangkal = $request->opsi_penangkal ?? 0;
    
    $brand = ProductBrand::where('id', '23')->first(); // MASTER ROOF
    $brandId = $brand->id ?? null;
    
    // ============================================================
    // 1. JENIS ATAP
    // ============================================================
    $jenisAtapOptions = [];
    if ($brandId) {
        $jenisAtapOptions = Product::where('brand_id', $brandId)
            ->whereHas('area', function($q) {
                $q->where('slug', 'atap-utama');
            })
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 2. PRODUK NOK (Nok Biasa)
    // ============================================================
    $areaNok = ProductArea::where('slug', 'taperoof-nok')->first();
    $nokOptions = collect();
    if ($areaNok && $brandId) {
        $nokOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNok->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 3. PRODUK NOK TUTUP
    // ============================================================
    $areaNokTutup = ProductArea::where('slug', 'nok-tutup')->first();
    $nokTutupOptions = collect();
    if ($areaNokTutup && $brandId) {
        $nokTutupOptions = Product::where('brand_id', $brandId)
            ->where('area_id', $areaNokTutup->id)
            ->with(['unit', 'productTipe'])
            ->get();
    }
    
    // ============================================================
    // 4. BUILD NOK MAPPING UNTUK AUTO-SELECT
    // ============================================================
    $nokMapping = [];
    foreach ($nokOptions as $nok) {
        $tipeId = $nok->product_tipe_id;
        $tipe = $nok->productTipe->kode_tipe ?? 'U';
        
        $nokTutup = $nokTutupOptions->firstWhere('product_tipe_id', $tipeId);
        
        $nokMapping[$nok->id] = [
            'tipe' => $tipe,
            'productTipeId' => $tipeId,
            'nokTutupId' => $nokTutup->id ?? null,
            'nokTutupName' => $nokTutup->nama_produk ?? ''
        ];
    }
    
    Log::info('NOK MAPPING PELANA + DINDING MASTER ROOF:', $nokMapping);
    
    $rangkaOptions = ['Baja Ringan', 'Baja Berat', 'Beton', 'Kayu'];
    $lantaiKerjaOptions = ['Plywood 9 mm', 'Plywood 12 mm', 'Plywood 15 mm', 'GRC 9 mm', 'GRC 12 mm', 'GRC 15 mm', 'Beton'];
    
    return view('boq.mahaspanroof.atap-kombinasi.mahaspanroof-pelana-dinding', compact(
        'jenisAtapOptions',
        'nokOptions',
        'nokTutupOptions',
        'nokMapping',
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
        'opsiKaca',
        'opsiDinding',
        'opsiCerobong',
        'opsiPenangkal'
    ));
}

public function hitungPelanaDinding(Request $request)
{
    Log::info('=== MaharoofKombinasiController: hitungPelanaDinding() ===');
    
    $luasAtap = $request->luas_atap ?? 0;
    $panjangStarter = $request->panjang_starter ?? 0;
    $panjangNokJurai = $request->panjang_nok_jurai ?? 0;
    $panjangFlashing = $request->panjang_flashing ?? 0;
    $sudut = $request->sudut ?? 0;
    $waste = $request->waste / 100;
    $panjangAtap = $request->input('panjang_atap', 0);
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
    
    $brand = ProductBrand::where('id', '23')->first(); // MAHAROOF
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
        }
    }
    
    Log::info('HITUNG PELANA + DINDING MAHAROOF:', [
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
    
    // Hitung panjang efektif berdasarkan sudut kemiringan
    // Sudut > 15°  → kurangi 0.1
    // Sudut ≤ 15°  → kurangi 0.2
    $pengurang = ($sudut > 15) ? 0.1 : 0.2;
    $panjangEfektif = $panjangAtap - $pengurang;
    
    // Fallback agar tidak bagi nol / negatif
    if ($panjangEfektif <= 0) {
        $panjangEfektif = 0.1;
    }
    
    // Rumus: (luas atap / panjang efektif) + waste
    $qtyDasar = $luasAtap / $panjangEfektif;
    $qtyDenganWaste = $qtyDasar * (1 + $waste);
    $qty = ceil($qtyDenganWaste * $satuan);
    
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
    // 3. TAPE ROOF NOK & JURAI
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
    // 4. NOK TUTUP - QTY = 4 (PELANA + DINDING)
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
                
                Log::info('INSULASI DITAMBAHKAN (AKSESORIS) - PELANA + DINDING:', [
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
// 13a. SCREW ATAP - MANUAL ID (378)
// Rumus: luas atap × satuan terkecil
// ============================================================
if ($luasAtap > 0) {
    $screwAtap = Product::with('unit')->find(378);
    
    if ($screwAtap) {
        $satuan = $screwAtap->satuan_terkecil ?? 1;
        $qtyScrewRaw = $luasAtap * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwAtap->id,
            'produk_id'     => $screwAtap->id,
            'nama_produk'   => $screwAtap->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwAtap->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwAtap->harga_jual ?? 0,
            'total_harga'   => ($screwAtap->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $luasAtap . ' m² luas atap'
        ];
    }
}

// ============================================================
// 13b. SCREW NOK & JURAI - MANUAL ID (379)
// Rumus: panjang nok & jurai × satuan terkecil
// ============================================================
if ($panjangNokJurai > 0) {
    $screwNok = Product::with('unit')->find(379);
    
    if ($screwNok) {
        $satuan = $screwNok->satuan_terkecil ?? 1;
        $qtyScrewRaw = $panjangNokJurai * $satuan;
        $qtyScrew = ceil($qtyScrewRaw + ($qtyScrewRaw * $waste));
        
        $results[] = [
            'product_id'    => $screwNok->id,
            'produk_id'     => $screwNok->id,
            'nama_produk'   => $screwNok->nama_produk,
            'area'          => 'Paku & Screw',
            'qty'           => $qtyScrew,
            'satuan'        => $screwNok->unit->unit_name ?? 'pcs',
            'harga_satuan'  => $screwNok->harga_jual ?? 0,
            'total_harga'   => ($screwNok->harga_jual ?? 0) * $qtyScrew,
            'parameter'     => $panjangNokJurai . ' meter nok & jurai'
        ];
    }
}
    //   // ============================================================
    //     // 13. SCREW PLYWOOD - MASTER ROOF
    //     // ============================================================
    //     $qtyPlywood = 0;
    //     foreach ($results as $result) {
    //         if ($result['area'] == 'Lantai Kerja') {
    //             $qtyPlywood = $result['qty'];
    //             break;
    //         }
    //     }
        
    //     if ($qtyPlywood > 0) {
    //         $screwPlywoodProduct = null;
    //         if ($brandId) {
    //             $screwPlywoodProduct = Product::where('brand_id', $brandId)
    //                 ->whereHas('area', function($q) {
    //                     $q->where('slug', 'screw-plywood');
    //                 })
    //                 ->with('unit')
    //                 ->first();
    //         }
            
    //         $satuan = $screwPlywoodProduct->satuan_terkecil ?? 1;
    //         $qtyScrewPlywoodRaw = ($qtyPlywood * 40) / 750;
    //         $qtyScrewPlywood = ceil($qtyScrewPlywoodRaw + ($qtyScrewPlywoodRaw * $waste));
            
    //         $namaProduk = $screwPlywoodProduct->nama_produk ?? 'Screw Plywood';
    //         $satuanText = $screwPlywoodProduct->unit->unit_name ?? 'Box';
    //         $harga = $screwPlywoodProduct->harga_jual ?? 0;
            
    //         $results[] = [
    //             'product_id' => $screwPlywoodProduct->id ?? null,
    //             'produk_id' => $screwPlywoodProduct->id ?? null,
    //             'nama_produk' => $namaProduk,
    //             'area' => 'Screw Plywood',
    //             'qty' => $qtyScrewPlywood,
    //             'satuan' => $satuanText,
    //             'harga_satuan' => $harga,
    //             'total_harga' => $harga * $qtyScrewPlywood,
    //             'parameter' => $qtyPlywood . ' lembar plywood'
    //         ];
    //     }
    
    $grandTotal = collect($results)->sum('total_harga');
    
    Log::info('HASIL PELANA + DINDING MAHAROOF:', [
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
    
    $view = 'boq.mahaspanroof.atap-kombinasi.pdf-mahaspanroof-pelana-dinding';
    
    if (!view()->exists($view)) {
        $view = 'boq.taperoof.pdf-taperoof-pelana-dinding';
    }
    
    return view($view, compact('data'));
}
}