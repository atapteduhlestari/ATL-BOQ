<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBrand;

class AtapStandarController extends Controller
{
    /**
     * Tampilkan halaman utama atap standar
     */
    public function index()
    {
        $brands = ProductBrand::all();
        return view('atap-standart.index', compact('brands'));
    }

      public function hitung(Request $request)
    {
        $jenis_atap = $request->jenis_atap;
        
        if ($jenis_atap == 1) {
            // Atap Limasan
            return $this->hitungLimasan($request);
        } elseif ($jenis_atap == 2) {
            // Atap Pelana
            return $this->hitungPelana($request);
        } elseif ($jenis_atap == 3) {
            // Atap Limas / Piramid
            return $this->hitungLimasPiramid($request);
        } elseif ($jenis_atap == 4) {
        return $this->hitungSatuKemiringan($request);
    } elseif ($jenis_atap == 5) {
        return $this->hitungKerucut($request);
    } elseif ($jenis_atap == 6) {
        return $this->hitungDome($request);
    }
        
        return response()->json(['success' => false, 'message' => 'Jenis atap tidak ditemukan']);
    }
    
    /**
     * Perhitungan Atap Limasan
     */
private function hitungLimasan($request)
{
    // Ambil data dari request
    $panjang = $request->panjang ?? $request->input('panjang', 0);
    $lebar = $request->lebar ?? $request->input('lebar', 0);
    $sudut = $request->sudut ?? $request->input('sudut', 0);
    
    // Rumus 1: variable a = ((lebar/2) * tan(deg2rad(angle)))
    $lebarSetengah = $lebar / 2;
    $rad = deg2rad($sudut);
    $variable_a = $lebarSetengah * tan($rad);
    
    // Rumus 2: panjang sisi miring = SQRT(((lebar/2)*(lebar/2)) + ($variable_a*$variable_a))
    $panjangSisiMiring = sqrt(($lebarSetengah * $lebarSetengah) + ($variable_a * $variable_a));
    
    // Rumus 3: Variable b = panjang - lebar
    $variable_b = $panjang - $lebar;
    
    // Rumus 4: variable c = (panjang + variable b) / 2
    $variable_c = ($panjang + $variable_b) / 2;
    
    // Rumus 5: variable d = (panjang sisi miring x variable c) x 2
    $variable_d = ($panjangSisiMiring * $variable_c) * 2;
    
    // Rumus 6: variable e = (lebar x panjang sisi miring x 0.5) x 2
    $variable_e = ($lebar * $panjangSisiMiring * 0.5) * 2;
    
    // Rumus 7: luas atap = variable d + variable e
    $luasAtap = $variable_d + $variable_e;
    
    // Perhitungan STARTING
    $starting = ($lebar * 2) + ($panjang * 2);
    
    // FLASHING = STARTING
    $flashing = $starting;
    
    // Perhitungan NOK DAN JURAI
    $variable_k = ($lebarSetengah * $lebarSetengah) + ($panjangSisiMiring * $panjangSisiMiring);
    $variable_l = sqrt($variable_k);
    $nokJurai = ($variable_l * 4) + $variable_b;
    
    $hasil = [
        'success' => true,
        'jenis_atap' => 'Atap Limasan',
        'panjang' => round($panjang, 2),
        'lebar' => round($lebar, 2),
        'sudut' => round($sudut, 2),
        'luas_atap' => round($luasAtap, 2),
        'panjang_sisi_miring' => round($panjangSisiMiring, 2),
        'starting' => round($starting, 2),
        'nok_jurai' => round($nokJurai, 2),
        'flashing' => round($flashing, 2),
    ];
    
    // ✅ LANGSUNG return JSON, jangan return back()
    return response()->json($hasil);
}
    /**
     * Perhitungan Atap Pelana
     */
    private function hitungPelana($request)
    {
        // Ambil data dari request
        $panjang = $request->panjang ?? $request->input('panjang', 0);
        $lebar = $request->lebar ?? $request->input('lebar', 0);
        $sudut = $request->sudut ?? $request->input('sudut', 0);
        
        // variable_b_pelana = lebar / 2
        $variable_b_pelana = $lebar / 2;
        
        // variable_a_pelana = variable_b_pelana * tan(deg2rad(sudut))
        $rad = deg2rad($sudut);
        $variable_a_pelana = $variable_b_pelana * tan($rad);
        
        // panjang sisi miring = sqrt((variable_a_pelana^2) + (variable_b_pelana^2))
        $panjangSisiMiring = sqrt(($variable_a_pelana * $variable_a_pelana) + ($variable_b_pelana * $variable_b_pelana));
        
        // luas atap = (panjang sisi miring x 2) x panjang
        $luasAtap = ($panjangSisiMiring * 2) * $panjang;
        
        // starting = (panjang sisi miring x 4) + (panjang x 2)
        $starting = ($panjangSisiMiring * 4) + ($panjang * 2);
        
        // flashing = starting
        $flashing = $starting;
        
        // nok dan jurai = panjang
        $nokJurai = $panjang;
        
        // Simpan ke session
        $hasil = [
            'success' => true,
            'jenis_atap' => 'Atap Pelana',
            'panjang' => round($panjang, 2),
            'lebar' => round($lebar, 2),
            'sudut' => round($sudut, 2),
            'variable_b_pelana' => round($variable_b_pelana, 2),
            'variable_a_pelana' => round($variable_a_pelana, 2),
            'panjang_sisi_miring' => round($panjangSisiMiring, 2),
            'luas_atap' => round($luasAtap, 2),
            'starting' => round($starting, 2),
            'flashing' => round($flashing, 2),
            'nok_jurai' => round($nokJurai, 2),
        ];
        

           
           return response()->json($hasil);
    }
    
    /**
     * Perhitungan Atap Limas / Piramid
     * Rumus:
     * panjang sisi miring sama dengan rumus pelana
     * luas area = ((panjang * 0.5) x panjang sisi miring) x 4
     * starting = panjang x 2
     * nok dan jurai = (sqrt(((panjang*0.5)x(panjang*0.5))+((panjang sisi miring x panjang sisi miring)))) x 4
     * flashing = starting
     */
   private function hitungLimasPiramid($request)
{
    // Ambil data dari request
    $panjang = $request->panjang ?? $request->input('panjang', 0);
    $lebar = $request->lebar ?? $request->input('lebar', 0);
    $sudut = $request->sudut ?? $request->input('sudut', 0);
    
    // Untuk atap limas, gunakan sisi terpendek atau rata-rata
    $sisi = min($panjang, $lebar); // atau ($panjang + $lebar) / 2
    
    // variable_b_limas = sisi / 2
    $variable_b_limas = $sisi / 2;
    
    // variable_a_limas = variable_b_limas * tan(deg2rad(sudut))
    $rad = deg2rad($sudut);
    $variable_a_limas = $variable_b_limas * tan($rad);
    
    // panjang sisi miring = sqrt((variable_a_limas^2) + (variable_b_limas^2))
    $panjangSisiMiring = sqrt(($variable_a_limas * $variable_a_limas) + ($variable_b_limas * $variable_b_limas));
    
    // luas area = (sisi x panjangSisiMiring / 2) x 4
    $luasAtap = ($sisi * $panjangSisiMiring / 2) * 4;
    
    // starting = keliling
    $starting = ($panjang * 2) + ($lebar * 2);
    
    // flashing = starting
    $flashing = $starting;
    
    // nok dan jurai
    $setengahSisi = $sisi * 0.5;
    $nokJuraiKuadrat = ($setengahSisi * $setengahSisi) + ($panjangSisiMiring * $panjangSisiMiring);
    $nokJurai = sqrt($nokJuraiKuadrat) * 4;
    
    $hasil = [
        'success' => true,
        'jenis_atap' => 'Atap Limas / Piramid',
        'panjang' => round($panjang, 2),
        'lebar' => round($lebar, 2),
        'sudut' => round($sudut, 2),
        'panjang_sisi_miring' => round($panjangSisiMiring, 2),
        'luas_atap' => round($luasAtap, 2),
        'starting' => round($starting, 2),
        'flashing' => round($flashing, 2),
        'nok_jurai' => round($nokJurai, 2),
    ];
    
    return response()->json($hasil);
}
   private function hitungSatuKemiringan($request)
{
    // Ambil data dari request
    $panjang = $request->panjang ?? $request->input('panjang', 0);
    $lebar = $request->lebar ?? $request->input('lebar', 0);
    $sudut = $request->sudut ?? $request->input('sudut', 0);
    
    $rad = deg2rad($sudut);
    $variable_a = $lebar * tan($rad);
    $panjangSisiMiring = sqrt(($lebar * $lebar) + ($variable_a * $variable_a));
    $luasAtap = $panjang * $panjangSisiMiring;
    $starting = ($panjang * 2) + ($panjangSisiMiring * 2);
    $flashing = $starting;
    $nokJurai = $starting; // 🟡 Nok & Jurai disamakan dengan Starting/Flashing
    
    $hasil = [
        'success' => true,
        'jenis_atap' => 'Atap Satu Kemiringan',
        'panjang' => round($panjang, 2),
        'lebar' => round($lebar, 2),
        'sudut' => round($sudut, 2),
        'panjang_sisi_miring' => round($panjangSisiMiring, 2),
        'luas_atap' => round($luasAtap, 2),
        'starting' => round($starting, 2),
        'flashing' => round($flashing, 2),
        'nok_jurai' => round($nokJurai, 2),
    ];
    
    return response()->json($hasil);
}
/**
 * Perhitungan Atap Kerucut
 * Rumus:
 * variable_a = (diameter/2) x (diameter/2)
 * variable_b = (tinggi x tinggi)
 * panjang sisi miring = sqrt(variable_a + variable_b)
 * luas area = (22/7) x (diameter/2) x panjang sisi miring
 * nok dan jurai = panjang sisi miring x 2
 * starting = 2 x 3.14 x (diameter/2)
 * flashing = starting
 */
private function hitungKerucut($request)
{
    // Ambil data dari request
    $tinggi = $request->tinggi ?? $request->input('tinggi', 0);
    $diameter = $request->diameter ?? $request->input('diameter', 0);
    $sudut = $request->sudut ?? $request->input('sudut', 0);
    
    // variable_a = (diameter/2) x (diameter/2)
    $jariJari = $diameter / 2;
    $variable_a = $jariJari * $jariJari;
    
    // variable_b = (tinggi x tinggi)
    $variable_b = $tinggi * $tinggi;
    
    // panjang sisi miring = sqrt(variable_a + variable_b)
    $panjangSisiMiring = sqrt($variable_a + $variable_b);
    
    // luas area = (22/7) x (diameter/2) x panjang sisi miring
    $luasAtap = (22/7) * $jariJari * $panjangSisiMiring;
    
    // nok dan jurai = panjang sisi miring x 2
    $nokJurai = $panjangSisiMiring * 2;
    
    // starting = 2 x 3.14 x (diameter/2)
    $starting = 2 * 3.14 * $jariJari;
    
    // flashing = starting
    $flashing = $starting;
    
    // Simpan ke session
    $hasil = [
        'success' => true,
        'jenis_atap' => 'Atap Kerucut',
        'tinggi' => round($tinggi, 2),
        'diameter' => round($diameter, 2),
        'sudut' => round($sudut, 2),
        'jari_jari' => round($jariJari, 2),
        'variable_a' => round($variable_a, 2),
        'variable_b' => round($variable_b, 2),
        'panjang_sisi_miring' => round($panjangSisiMiring, 2),
        'luas_atap' => round($luasAtap, 2),
        'starting' => round($starting, 2),
        'flashing' => round($flashing, 2),
        'nok_jurai' => round($nokJurai, 2),
    ];
    
   return response()->json($hasil);
}
/**
 * Perhitungan Atap Dome / Setengah Lingkaran
 * Rumus:
 * luas area = 2 x (22/7) x (diameter/2) x tinggi
 * starting = 2 x (22/7) x (diameter/2)
 * flashing = starting
 */
private function hitungDome($request)
{
    // Ambil data dari request
    $diameter = $request->diameter ?? $request->input('diameter', 0);
    $tinggi = $request->tinggi ?? $request->input('tinggi', 0);
    $sudut = $request->sudut ?? $request->input('sudut', 0);
    
    // Jari-jari = diameter / 2
    $jariJari = $diameter / 2;
    
    // luas area = 2 x (22/7) x (diameter/2) x tinggi
    $luasAtap = 2 * (22/7) * $jariJari * $tinggi;
    
    // starting = 2 x (22/7) x (diameter/2)
    $starting = 2 * (22/7) * $jariJari;
    
    // flashing = starting
    $flashing = $starting;
    
    // Nok & Jurai untuk dome (biasanya tidak ada, bisa pakai keliling lingkaran)
    $nokJurai = 2 * (22/7) * $jariJari;
    
    // Simpan ke session
    $hasil = [
        'success' => true,
        'jenis_atap' => 'Atap Dome / Setengah Lingkaran',
        'diameter' => round($diameter, 2),
        'tinggi' => round($tinggi, 2),
        'jari_jari' => round($jariJari, 2),
        'luas_atap' => round($luasAtap, 2),
        'starting' => round($starting, 2),
        'flashing' => round($flashing, 2),
        'nok_jurai' => round($nokJurai, 2),
    ];
   return response()->json($hasil);
}
}