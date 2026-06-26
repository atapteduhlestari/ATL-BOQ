<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class JendelaController extends Controller
{
    /**
     * Tampilkan halaman utama jendela
     */
    public function index()
    {
        $brands = ProductBrand::all();
        return view('jendela.index', compact('brands'));
    }

    /**
     * Hitung kebutuhan material jendela
     */
    public function hitung(Request $request)
    {
        $lebar = $request->lebar;
        $tinggi = $request->tinggi;
        $jumlahDaun = $request->jumlah_daun ?? 1;
        $jenisJendela = $request->jenis_jendela; // kayu, aluminium, upvc
        $jenisKaca = $request->jenis_kaca; // polos, patri, tempered
        $waste = $request->waste / 100 ?? 0.05;
        
        $results = [];
        
        // 1. HITUNG LUAS DAN KELILING
        $luasJendela = $lebar * $tinggi;
        $kelilingKusen = 2 * ($lebar + $tinggi);
        
        // 2. HITUNG MATERIAL KUSEN (per meter)
        $kusen = $this->getProductByCategory('Kusen Jendela', $jenisJendela);
        if ($kusen) {
            $qtyKusen = ceil($kelilingKusen / $kusen->satuan_terkecil);
            $qtyKusen = ceil($qtyKusen + ($qtyKusen * $waste));
            $results[] = $this->formatResult($kusen, $qtyKusen, 'Kusen Jendela', $kelilingKusen);
        }
        
        // 3. HITUNG MATERIAL DAUN JENDELA (per daun)
        $daunJendela = $this->getProductByCategory('Daun Jendela', $jenisJendela);
        if ($daunJendela) {
            $lebarDaun = $lebar / $jumlahDaun;
            $luasDaun = $lebarDaun * $tinggi;
            $qtyDaun = ceil($luasDaun / $daunJendela->satuan_terkecil) * $jumlahDaun;
            $qtyDaun = ceil($qtyDaun + ($qtyDaun * $waste));
            $results[] = $this->formatResult($daunJendela, $qtyDaun, 'Daun Jendela', $luasDaun);
        }
        
        // 4. HITUNG MATERIAL KACA
        $kaca = $this->getProductByCategory('Kaca Jendela', $jenisKaca);
        if ($kaca) {
            $luasKaca = $luasJendela;
            $qtyKaca = ceil($luasKaca / $kaca->satuan_terkecil);
            $qtyKaca = ceil($qtyKaca + ($qtyKaca * $waste));
            $results[] = $this->formatResult($kaca, $qtyKaca, 'Kaca Jendela', $luasKaca);
        }
        
        // 5. AKSESORIS (engsel, handle, kunci)
        $aksesoris = $this->getAccessoriesByJenis($jenisJendela, $jumlahDaun);
        foreach ($aksesoris as $item) {
            $results[] = $this->formatResult($item['product'], $item['qty'], $item['area'], '-');
        }
        
        // 6. PAKU & SCREW
        $pakuScrew = $this->getProductByCategory('Paku & Screw', 'jendela');
        if ($pakuScrew) {
            $qtyPaku = ceil($kelilingKusen / $pakuScrew->satuan_terkecil);
            $qtyPaku = ceil($qtyPaku + ($qtyPaku * $waste));
            $results[] = $this->formatResult($pakuScrew, $qtyPaku, 'Paku & Screw', $kelilingKusen);
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'grand_total' => $grandTotal,
            'perhitungan' => [
                'luas_jendela' => round($luasJendela, 2),
                'keliling_kusen' => round($kelilingKusen, 2),
                'lebar_daun' => round($lebar / $jumlahDaun, 2)
            ]
        ]);
    }
    
    /**
     * Get product by kategori dan jenis
     */
    private function getProductByCategory($kategori, $jenis)
    {
        $brand = ProductBrand::where('nama_brand', 'IKO - ATAP')->first();
        
        $product = Product::where('brand_id', $brand->id)
            ->whereHas('area', function($q) use ($kategori) {
                $q->where('nama_area', $kategori);
            })
            ->when($jenis, function($q) use ($jenis) {
                return $q->where('nama_produk', 'like', '%' . $jenis . '%');
            })
            ->with('unit')
            ->first();
        
        return $product;
    }
    
    /**
     * Get aksesoris berdasarkan jenis jendela
     */
    private function getAccessoriesByJenis($jenisJendela, $jumlahDaun)
    {
        $brand = ProductBrand::where('nama_brand', 'IKO - ATAP')->first();
        $accessories = [];
        
        // Engsel (2 per daun)
        $engsel = Product::where('brand_id', $brand->id)
            ->whereHas('area', fn($q) => $q->where('nama_area', 'Engsel Jendela'))
            ->first();
        if ($engsel) {
            $accessories[] = [
                'product' => $engsel,
                'qty' => $jumlahDaun * 2,
                'area' => 'Engsel'
            ];
        }
        
        // Handle (1 per daun)
        $handle = Product::where('brand_id', $brand->id)
            ->whereHas('area', fn($q) => $q->where('nama_area', 'Handle Jendela'))
            ->first();
        if ($handle) {
            $accessories[] = [
                'product' => $handle,
                'qty' => $jumlahDaun,
                'area' => 'Handle'
            ];
        }
        
        // Kunci (1 per jendela)
        $kunci = Product::where('brand_id', $brand->id)
            ->whereHas('area', fn($q) => $q->where('nama_area', 'Kunci Jendela'))
            ->first();
        if ($kunci) {
            $accessories[] = [
                'product' => $kunci,
                'qty' => 1,
                'area' => 'Kunci'
            ];
        }
        
        return $accessories;
    }
    
    /**
     * Format hasil perhitungan
     */
    private function formatResult($product, $qty, $area, $inputValue)
    {
        return [
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
    
    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $data = $request->all();
        
        if (isset($data['hasil']) && is_string($data['hasil'])) {
            $data['hasil'] = json_decode($data['hasil'], true);
        }
        
        if (isset($data['hasil']) && is_array($data['hasil'])) {
            foreach ($data['hasil'] as &$item) {
                if (isset($item['qty'])) {
                    $item['qty'] = (int) str_replace(['.', ','], '', $item['qty']);
                }
                if (isset($item['harga_satuan']) && is_string($item['harga_satuan'])) {
                    $item['harga_satuan'] = (int) str_replace(['Rp ', '.', ','], '', $item['harga_satuan']);
                }
                if (isset($item['total_harga']) && is_string($item['total_harga'])) {
                    $item['total_harga'] = (int) str_replace(['Rp ', '.', ','], '', $item['total_harga']);
                }
            }
        }
        
        return view('jendela.jendela-pdf', ['data' => $data]);
    }
}