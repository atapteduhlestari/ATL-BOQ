<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Boq;

class DashboardController extends Controller
{
   public function index()
{
    $jumlahProduk = Product::where('tipe_produk', 'main')
        ->distinct()
        ->count('kode_produk');

    $jumlahBoq = Boq::count();

    $jumlahBoqHariIni = Boq::whereDate('created_at', today())->count();

    return view('admin.dashboard', compact('jumlahProduk', 'jumlahBoq', 'jumlahBoqHariIni'));
}
}