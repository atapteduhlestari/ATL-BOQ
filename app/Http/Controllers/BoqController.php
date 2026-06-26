<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boq;
use App\Models\DetailBoq;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BoqController extends Controller
{
    /**
     * Store BOQ dari export PDF
     * Bisa digunakan untuk atap standar maupun kombinasi
     */
   public function storeBoq(Request $request)
{
    try {
        DB::beginTransaction();

        // Generate nomor BOQ
        $nomorBoq = Boq::generateNomorBoq();

        // Simpan BOQ
        $boq = Boq::create([
            'nomor_boq' => $nomorBoq,
            'tanggal_boq' => date('Y-m-d')
        ]);

        // Simpan detail BOQ
        $results = $request->results ?? [];

        foreach ($results as $item) {
            // Cari produk berdasarkan ID
            $produk = Product::find($item['produk_id']);

            DetailBoq::create([
                'boq_id' => $boq->id,
                'produk_id' => $produk->id ?? null,
                'kode_produk' => $produk->kode_produk ?? '',
                'qty' => $item['qty'] ?? 0
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'BOQ berhasil disimpan',
            'data' => [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan BOQ: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Get all BOQ
     */
    public function index()
    {
        $boqs = Boq::with('details.produk')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $boqs
        ]);
    }

    /**
     * Get BOQ by ID
     */
    public function show($id)
    {
        $boq = Boq::with('details.produk')->find($id);

        if (!$boq) {
            return response()->json([
                'success' => false,
                'message' => 'BOQ tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $boq
        ]);
    }
}