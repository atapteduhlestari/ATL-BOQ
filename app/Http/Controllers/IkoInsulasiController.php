<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\Product;

class IkoInsulasiController extends Controller
{
    /**
     * Halaman utama BOQ Insulasi
     */
    public function index(Request $request)
    {
        $brand = ProductBrand::where('slug', 'iko-insulasi')->first();
        
        // Ambil parameter dari URL (luas saja, tanpa area)
        $luas = $request->query('luas', 0);
        $sudut = $request->query('sudut', 0);
        $panjangStarter = $request->query('panjang_starter', 0);
        $panjangNokJurai = $request->query('panjang_nok_jurai', 0);
        $panjangFlashing = $request->query('panjang_flashing', 0);
        
        $products = Product::where('brand_id', $brand->id)
        ->with('unit')
        ->get();
        
        // Aksesoris insulasi
        $accessories = Product::where('brand_id', $brand->id)
            ->whereHas('area', function($q) {
                $q->where('nama_area', 'Aksesoris Insulasi');
            })
            ->with('unit')
            ->get();
        
        return view('boq.iko-insulasi.index', compact(
            'products', 'accessories',
            'luas', 'sudut', 'panjangStarter', 'panjangNokJurai', 'panjangFlashing'
        ));
    }
    
    /**
     * Hitung material insulasi (AJAX)
     * Rumus: luas dengan waste / satuan_terkecil
     */
    public function hitung(Request $request)
    {
        $luas = $request->luas; // luas dari perhitungan atap
        $wastePersen = $request->waste ?? 5;
        
        $produkInsulasiId = $request->produk_insulasi_id;
        $aksesorisId = $request->aksesoris_id;
        
        $results = [];
        
        // 1. Insulasi utama (pakai luas, tanpa area)
        if ($produkInsulasiId) {
            $insulasi = Product::with('unit')->find($produkInsulasiId);
            
            // Hitung luas dengan waste
            $luasDenganWaste = $luas + ($luas * $wastePersen / 100);
            
            // Qty = luas dengan waste / satuan terkecil
            $qtyRaw = $luasDenganWaste / $insulasi->satuan_terkecil;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($insulasi, $qty, 'Insulasi', $luas, $wastePersen);
        }
        
        // 2. Aksesoris
        if ($aksesorisId) {
            $aksesoris = Product::with('unit')->find($aksesorisId);
            
            $luasDenganWaste = $luas + ($luas * $wastePersen / 100);
            $qtyRaw = $luasDenganWaste / $aksesoris->satuan_terkecil;
            $qty = ceil($qtyRaw);
            
            $results[] = $this->formatResult($aksesoris, $qty, 'Aksesoris Insulasi', $luas, $wastePersen);
        }
        
        $grandTotal = collect($results)->sum('total_harga');
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'grand_total' => $grandTotal
        ]);
    }
    
    /**
     * Format hasil perhitungan
     */
    private function formatResult($product, $qty, $area, $inputValue, $waste = 0)
    {
        $luasDenganWaste = $inputValue + ($inputValue * $waste / 100);
        
        return [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'area' => $area,
            'input_value' => $inputValue,
            'luas_dengan_waste' => round($luasDenganWaste, 2),
            'waste' => $waste,
            'satuan_terkecil' => $product->satuan_terkecil,
            'qty' => $qty,
            'satuan' => $product->unit->unit_name ?? 'm²',
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
        
        return view('boq.iko-insulasi.pdf', ['data' => $data]);
    }
}