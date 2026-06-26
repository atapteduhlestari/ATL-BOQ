<?php
// app/Http/Controllers/AtapKombinasiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBrand;

class AtapKombinasiController extends Controller
{
    public function index()
    { $brands = ProductBrand::all();
        return view('atap-kombinasi.index', compact('brands'));
    }

  public function hitung(Request $request)
{
    $jenis = $request->jenis_kombinasi;
    
    if ($jenis == 'limasan_trapesium') {
        return $this->hitungLimasanTrapesium($request);
    }
    if ($jenis == 'limas_pelana') {
        return $this->hitungLimasPelana($request);
    }
    if ($jenis == 'pelana_2trapesium') {
        return $this->hitungPelana2Trapesium($request);
    }
    if ($jenis == 'limasan_limasan') {  // <-- TAMBAHKAN INI
        return $this->hitungLimasanLimasan($request);
    }if ($jenis == 'pelana_x') {
    return $this->hitungPelanaX($request);
}if ($jenis == 'limasan_x') {
    return $this->hitungLimasanX($request);
}if ($jenis == 'gergaji') {
    return $this->hitungGergaji($request);
}if ($jenis == 'pelana_2_kemiringan') {
    return $this->hitungPelana2Kemiringan($request);
}if ($jenis == 'trapesium_pelana_4sisi') {
    return $this->hitungTrapesiumPelana4Sisi($request);
}
if ($jenis == 'lengkung_2_sisi') {
    return $this->hitungLengkung2Sisi($request);
} if ($jenis == 'pelana_2_sisi') {
        return $this->hitungPelana2Sisi($request);
    }
     if ($jenis == 'pelana_dinding') {  // <-- TAMBAHKAN INI
        return $this->hitungPelanaDinding($request);
    }
     if ($jenis == 'pelana_3_arah') {  // <-- TAMBAHKAN INI
        return $this->hitungPelana3Arah($request);
    }
     if ($jenis == 'trapesium_pelana_4sisi') {
        return $this->hitungTrapesiumPelana4Sisi($request);
    }
    if ($request->jenis_kombinasi == 'trapesium_kotak') {
        return $this->hitungTrapesiumKotak($request);
    }
    return response()->json(['success' => false, 'message' => 'Jenis kombinasi tidak ditemukan']);
}
    
    /**
     * Hitung Kombinasi Limasan + Trapesium
     * 
     * Bagian 1: Limasan (depan)
     * Bagian 2: Trapesium (samping kiri & kanan)
     */
private function hitungLimasanTrapesium($request)
{
    // Data Limasan
    $p_limasan = $request->panjang_limasan;
    $l_limasan = $request->lebar_limasan;
    $sudut_limasan = $request->sudut_limasan;
    
    // Data Trapesium
    $p_atas_trapesium = $request->panjang_atas_trapesium;
    $p_bawah_trapesium = $request->panjang_bawah_trapesium;
    $tinggi_trapesium = $request->tinggi_trapesium;
    $sudut_trapesium = $request->sudut_trapesium;
    
    // CEK APAKAH PANJANG LIMASAN SAMA DENGAN PANJANG ATAS TRAPESIUM
    $isPanjangSama = ($p_limasan == $p_atas_trapesium);
    
    // ==================== HITUNG LIMASAN ====================
    $radL = deg2rad($sudut_limasan);
    
    $variable_a = $l_limasan / 2;
    $variable_b = $variable_a * tan($radL);
    $sisiMiring = sqrt(($variable_a * $variable_a) + ($variable_b * $variable_b));
    $variable_c = $p_limasan - $l_limasan;
    $variable_d = (($p_limasan + $variable_c) / 2) * ($sisiMiring * $sisiMiring);
    $variable_e = ($l_limasan * $sisiMiring * 0.5) * 2;
    $luasLimasan = $variable_d + $variable_e;
    
    // STARTING LIMASAN: Jika panjang sama dengan atas trapesium, maka starter = 0
    if ($isPanjangSama) {
        $starterLimasan = 0;
    } else {
        $starterLimasan = ($l_limasan * 2) + ($p_limasan * 2);
    }
    
    $variable_f = ($variable_a * $variable_a) + ($sisiMiring * $sisiMiring);
    $nokJuraiLimasan = (sqrt($variable_f) * 4) + $variable_c;
    $flashingLimasan = $starterLimasan; // Flashing mengikuti starter
    
    // ==================== HITUNG TRAPESIUM (4 sisi) ====================
    $radT = deg2rad($sudut_trapesium);
    
    $variable_h = $tinggi_trapesium;
    
    $tanSudut = tan($radT);
    $sisiMiringTrapesium = sqrt(($variable_h * $variable_h) + (($variable_h * $tanSudut) * ($variable_h * $tanSudut)));
    
    $luasSatuTrapesium = (($p_bawah_trapesium + $p_atas_trapesium) / 2) * $tinggi_trapesium;
    $luasTrapesium = ($luasSatuTrapesium / cos($radT)) * 4;
    
    $starterTrapesium = $p_bawah_trapesium * 4;
    $nokJuraiTrapesium = ($tinggi_trapesium / cos($radT)) * 4;
    $flashingTrapesium = $starterTrapesium;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasLimasan + $luasTrapesium;
    $totalStarter = $starterLimasan + $starterTrapesium;
    $totalNokJurai = $nokJuraiLimasan + $nokJuraiTrapesium;
    $totalFlashing = $flashingLimasan + $flashingTrapesium;
    
    // Detail per bagian
    $details = [
        [
            'bagian' => 'Limasan (Depan)',
            'luas_atap' => round($luasLimasan, 2),
            'starter' => round($starterLimasan, 2),
            'nok_jurai' => round($nokJuraiLimasan, 2),
            'flashing' => round($flashingLimasan, 2),
            'is_panjang_sama' => $isPanjangSama
        ],
        [
            'bagian' => 'Trapesium (Samping Kiri & Kanan)',
            'luas_atap' => round($luasTrapesium, 2),
            'starter' => round($starterTrapesium, 2),
            'nok_jurai' => round($nokJuraiTrapesium, 2),
            'flashing' => round($flashingTrapesium, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'jenis_kombinasi' => 'Limasan + Trapesium',
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungLimasPelana($request)
{
    // Data Limas
    $p_limas = $request->panjang_limas;
    $l_limas = $request->lebar_limas;
    $sudut_limas = $request->sudut_limas;
    
    // Data Pelana
    $p_pelana = $request->panjang_pelana;
    $l_pelana = $request->lebar_pelana;
    $sudut_pelana = $request->sudut_pelana;
    
    // ==================== HITUNG LIMAS (RUMUS BARU) ====================
    $radLimas = deg2rad($sudut_limas);
    
    // variable c = lebar / 2
    $variable_c = $l_limas / 2;
    
    // variable d = variable c x tan(sudut)
    $variable_d = $variable_c * tan($radLimas);
    
    // panjang sisi miring = sqrt((c x c) + (d x d))
    $sisiMiringLimas = sqrt(($variable_c * $variable_c) + ($variable_d * $variable_d));
    
    // variable e = panjang - lebar
    $variable_e = $p_limas - $l_limas;
    
    // variable f = ((panjang + e) / 2) x panjang sisi miring
    $variable_f = (($p_limas + $variable_e) / 2) * $sisiMiringLimas;
    
    // variable g = 0.5 x lebar x panjang sisi miring
    $variable_g = 0.5 * $l_limas * $sisiMiringLimas;
    
    // luas limasan = variable f + variable g
    $luasLimas = ($variable_f + $variable_g)*2;
    
    // starting = (lebar x 2) + (panjang + variable e)
    $starterLimas = ($l_limas * 2) + ($p_limas + $variable_e);
    
    // variable h = (c x c) + (sisi miring x sisi miring)
    $variable_h = ($variable_c * $variable_c) + ($sisiMiringLimas * $sisiMiringLimas);
    
    // variable i = sqrt(variable h)
    $variable_i = sqrt($variable_h);
    
    // nok dan jurai = variable i x 3 + variable e
    $nokJuraiLimas = ($variable_i * 3) + $variable_e;
    
    // flashing = starter
    $flashingLimas = $starterLimas;
    
    // ==================== HITUNG PELANA (RUMUS BARU) ====================
    $radPelana = deg2rad($sudut_pelana);
    
    // variable a = lebar / 2
    $variable_a_pelana = $l_pelana / 2;
    
    // variable b = variable a x tan(sudut)
    $variable_b_pelana = $variable_a_pelana * tan($radPelana);
    
    // panjang sisi miring = sqrt((a x a) + (b x b))
    $sisiMiringPelana = sqrt(($variable_a_pelana * $variable_a_pelana) + ($variable_b_pelana * $variable_b_pelana));
    
    // luas area = (panjang sisi miring x 2) x panjang
    $luasPelana = ($sisiMiringPelana * 2) * $p_pelana;
    
    // starting = (panjang sisi miring x 2) + (panjang x 2)
    $starterPelana = ($sisiMiringPelana * 2) + ($p_pelana * 2);
    
    // nok dan jurai = panjang
    $nokJuraiPelana = $p_pelana;
    
    // flashing = starting
    $flashingPelana = $starterPelana;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasLimas + $luasPelana;
    $totalStarter = $starterLimas + $starterPelana;
    $totalNokJurai = $nokJuraiLimas + $nokJuraiPelana;
    $totalFlashing = $flashingLimas + $flashingPelana;
    
    $details = [
        [
            'bagian' => 'Limas (Atas)',
            'luas_atap' => round($luasLimas, 2),
            'starter' => round($starterLimas, 2),
            'nok_jurai' => round($nokJuraiLimas, 2),
            'flashing' => round($flashingLimas, 2)
        ],
        [
            'bagian' => 'Pelana (Bawah)',
            'luas_atap' => round($luasPelana, 2),
            'starter' => round($starterPelana, 2),
            'nok_jurai' => round($nokJuraiPelana, 2),
            'flashing' => round($flashingPelana, 2)
        ]
    ];
     \Illuminate\Support\Facades\Log::info('Hasil Perhitungan Limas + Pelana:', [
        'totalLuas' => $totalLuas,
        'totalStarter' => $totalStarter,
        'totalNokJurai' => $totalNokJurai,
        'totalFlashing' => $totalFlashing,
        'details' => $details
    ]);
    
    return response()->json([
        'success' => true,
        'jenis_kombinasi' => 'Limas + Pelana',
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungPelana2Trapesium($request)
{
    // Data Pelana
    $pe_pelana = $request->panjang_pelana;
    $le_pelana = $request->lebar_pelana;
    $sudut_pelana = $request->sudut_pelana;
    
    // Data Trapesium A
    $p_atas_a = $request->panjang_atas_trapesium_a;
    $p_bawah_a = $request->panjang_bawah_trapesium_a;
    $tinggi_a = $request->tinggi_trapesium_a;
    $sudut_a = $request->sudut_trapesium_a;
    
    // Data Trapesium B
    $p_atas_b = $request->panjang_atas_trapesium_b;
    $p_bawah_b = $request->panjang_bawah_trapesium_b;
    $tinggi_b = $request->tinggi_trapesium_b;
    $sudut_b = $request->sudut_trapesium_b;
    
    // ==================== HITUNG PELANA ====================
    $radPelana = deg2rad($sudut_pelana);
    $variable_a_pelana = $le_pelana / 2;
    $variable_b_pelana = $variable_a_pelana * tan($radPelana);
    $sisiMiringPelana = sqrt(($variable_a_pelana * $variable_a_pelana) + ($variable_b_pelana * $variable_b_pelana));
    $luasPelana = ($sisiMiringPelana * 2) * $pe_pelana;
    $starterPelana = $sisiMiringPelana * 4 ;
    $nokJuraiPelana = $pe_pelana;
    $flashingPelana = $starterPelana;
    
   // ==================== HITUNG TRAPESIUM A (1 sisi, nanti dikali 2) ====================
$radA = deg2rad($sudut_a);
$tanSudutA = tan($radA);

$sisiMiringA = sqrt(($tinggi_a * $tinggi_a) + (($tinggi_a * $tanSudutA) * ($tinggi_a * $tanSudutA)));
$luasSatuA = (($p_bawah_a + $p_atas_a) / 2) * $tinggi_a;
$luasA = ($luasSatuA / cos($radA)) * 2;  // 1 sisi (kiri) x2

$starterA = $p_bawah_a * 2;  // 1 sisi x2
$nokJuraiA = ($tinggi_a / cos($radA)) * 2;  // 1 sisi x2
$flashingA = $starterA;

// ==================== HITUNG TRAPESIUM B (1 sisi, nanti dikali 2) ====================
$radB = deg2rad($sudut_b);
$tanSudutB = tan($radB);

$sisiMiringB = sqrt(($tinggi_b * $tinggi_b) + (($tinggi_b * $tanSudutB) * ($tinggi_b * $tanSudutB)));
$luasSatuB = (($p_bawah_b + $p_atas_b) / 2) * $tinggi_b;
$luasB = ($luasSatuB / cos($radB)) * 2;  // 1 sisi (kanan) x2

$starterB = $p_bawah_b * 2;  // 1 sisi x2
$nokJuraiB = ($tinggi_b / cos($radB)) * 2;  // 1 sisi x2
$flashingB = $starterB;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasPelana + $luasA + $luasB;
    $totalStarter = $starterPelana + $starterA + $starterB;
    $totalNokJurai = $nokJuraiPelana + $nokJuraiA + $nokJuraiB;
    $totalFlashing = $flashingPelana + $flashingA + $flashingB;
    
    $details = [
        [
            'bagian' => 'Pelana (Tengah)',
            'luas_atap' => round($luasPelana, 2),
            'starter' => round($starterPelana, 2),
            'nok_jurai' => round($nokJuraiPelana, 2),
            'flashing' => round($flashingPelana, 2)
        ],
        [
            'bagian' => 'Trapesium A (Kiri)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2)
        ],
        [
            'bagian' => 'Trapesium B (Kanan)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'jenis_kombinasi' => 'Pelana + 2 Trapesium',
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungLimasanLimasan($request)
{
    // Data Limasan A (Atas)
    $p_a = $request->panjang_limasan_a;
    $l_a = $request->lebar_limasan_a;
    $sudut_a = $request->sudut_limasan_a;
    
    // Data Limasan B (Bawah)
    $p_b = $request->panjang_limasan_b;
    $l_b = $request->lebar_limasan_b;
    $sudut_b = $request->sudut_limasan_b;
    
    // ==================== HITUNG LIMASAN A ====================
    $radA = deg2rad($sudut_a);
    
    $lebarSetengahA = $l_a / 2;
    $aA = $lebarSetengahA * tan($radA);
    $sisiMiringA = sqrt(($lebarSetengahA * $lebarSetengahA) + ($aA * $aA));
    $bA = $p_a - $l_a;
    $cA = ($p_a + $bA) / 2;
    $dA = ($sisiMiringA * $cA) * 2;
    $eA = ($l_a * $sisiMiringA * 0.5) * 2;
    $luasA = $dA + $eA;
    $starterA = ($l_a * 2) + ($p_a * 2);
    $kA = ($lebarSetengahA * $lebarSetengahA) + ($sisiMiringA * $sisiMiringA);
    $nokJuraiA = (sqrt($kA) * 4) + $bA;
    $flashingA = $starterA;
    
    // ==================== HITUNG LIMASAN B ====================
    $radB = deg2rad($sudut_b);
    
    $lebarSetengahB = $l_b / 2;
    $aB = $lebarSetengahB * tan($radB);
    $sisiMiringB = sqrt(($lebarSetengahB * $lebarSetengahB) + ($aB * $aB));
    $bB = $p_b - $l_b;
    $cB = ($p_b + $bB) / 2;
    $dB = ($sisiMiringB * $cB) * 2;
    $eB = ($l_b * $sisiMiringB * 0.5) * 2;
    $luasB = $dB + $eB;
    $starterB = ($l_b * 2) + ($p_b * 2);
    $kB = ($lebarSetengahB * $lebarSetengahB) + ($sisiMiringB * $sisiMiringB);
    $nokJuraiB = (sqrt($kB) * 4) + $bB;
    $flashingB = $starterB;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasA + $luasB;
    $totalStarter = $starterA + $starterB;
    $totalNokJurai = $nokJuraiA + $nokJuraiB;
    $totalFlashing = $flashingA + $flashingB;
    
    $details = [
        [
            'bagian' => 'Limasan A (Atas)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2)
        ],
        [
            'bagian' => 'Limasan B (Bawah)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'jenis_kombinasi' => 'Limasan + Limasan',
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungPelanaX($request)
{
    // Data Pelana A (Atas)
    $p_a = $request->panjang_a;
    $l_a = $request->lebar_a;
    $sudut_a = $request->sudut_a;
    
    // Data Pelana B (Tengah)
    $p_b = $request->panjang_b;
    $l_b = $request->lebar_b;
    $sudut_b = $request->sudut_b;
    
    // Data Pelana C (Bawah)
    $p_c = $request->panjang_c;
    $l_c = $request->lebar_c;
    $sudut_c = $request->sudut_c;
    
    // Talang Jurai & Wall Flashing dari request
    $talangJurai = $request->talang_jurai ?? 0;
    $wallFlashing = $request->wall_flashing ?? 0;
    
    // Hitung Pelana A
    $radA = deg2rad($sudut_a);
    $sisiMiringA = sqrt(pow($l_a / 2, 2) + pow(($l_a / 2) * tan($radA), 2));
    $luasA = ($sisiMiringA * 2) * $p_a;
    $starterA = ($sisiMiringA * 4) + ($p_a * 2);
    $nokJuraiA = $p_a;
    $flashingA = $starterA;
    
    // Hitung Pelana B
    $radB = deg2rad($sudut_b);
    $sisiMiringB = sqrt(pow($l_b / 2, 2) + pow(($l_b / 2) * tan($radB), 2));
    $luasB = ($sisiMiringB * 2) * $p_b;
    $starterB = ($sisiMiringB * 4) + ($p_b * 2);
    $nokJuraiB = $p_b;
    $flashingB = $starterB;
    
    // Hitung Pelana C
    $radC = deg2rad($sudut_c);
    $sisiMiringC = sqrt(pow($l_c / 2, 2) + pow(($l_c / 2) * tan($radC), 2));
    $luasC = ($sisiMiringC * 2) * $p_c;
    $starterC = ($sisiMiringC * 4) + ($p_c * 2);
    $nokJuraiC = $p_c;
    $flashingC = $starterC;
    
    // Total
    $totalLuas = $luasA + $luasB + $luasC;
    
    // TOTAL STARTER = starter A + B + C - ((lebar B x 4) / cos(sudut kemiringan))
    $radSudut = deg2rad($sudut_b);
    $pengurang = ($l_b * 4) / cos($radSudut);
    $totalStarter = ($starterA + $starterB + $starterC) - $pengurang;
    
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    // Hitung Talang Jurai berdasarkan rumus yang diberikan
    // sqrt(2 x (lebar b/2)^2) + ((lebar b/2) x tan(radians(sudut kemiringan)))^2)
    $lebarSetengah = $l_b / 2;
    $talangJuraiHitung = sqrt((2 * pow($lebarSetengah, 2)) + pow($lebarSetengah * tan($radSudut), 2)) * 4;
    
    $details = [
        [
            'bagian' => 'Pelana A (Atas)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2)
        ],
        [
            'bagian' => 'Pelana B (Tengah)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2)
        ],
        [
            'bagian' => 'Pelana C (Bawah)',
            'luas_atap' => round($luasC, 2),
            'starter' => round($starterC, 2),
            'nok_jurai' => round($nokJuraiC, 2),
            'flashing' => round($flashingC, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2),
            'talang_jurai' => round($talangJuraiHitung, 2),
            'wall_flashing' => round($wallFlashing, 2)
        ]
    ]);
}
private function hitungLimasanX($request)
{
    $p_a = $request->panjang_a;
    $l_a = $request->lebar_a;
    $sudut_a = $request->sudut_a;
    
    $p_b = $request->panjang_b;
    $l_b = $request->lebar_b;
    $sudut_b = $request->sudut_b;
    
    $p_c = $request->panjang_c;
    $l_c = $request->lebar_c;
    $sudut_c = $request->sudut_c;
    
    // Hitung Limasan A
    $radA = deg2rad($sudut_a);
    $lebarSetengahA = $l_a / 2;
    $aA = $lebarSetengahA * tan($radA);
    $sisiMiringA = sqrt(($lebarSetengahA * $lebarSetengahA) + ($aA * $aA));
    $bA = $p_a - $l_a;
    $cA = ($p_a + $bA) / 2;
    $dA = ($sisiMiringA * $cA) * 2;
    $eA = ($l_a * $sisiMiringA * 0.5) * 2;
    $luasA = $dA + $eA;
    $starterA = ($l_a * 2) + ($p_a * 2);
    $kA = ($lebarSetengahA * $lebarSetengahA) + ($sisiMiringA * $sisiMiringA);
    $nokJuraiA = (sqrt($kA) * 4) + $bA; // Ini Harus DIpastikan
    $flashingA = $starterA;
    
    // Hitung Limasan B
    $radB = deg2rad($sudut_b);
    $lebarSetengahB = $l_b / 2;
    $aB = $lebarSetengahB * tan($radB);
    $sisiMiringB = sqrt(($lebarSetengahB * $lebarSetengahB) + ($aB * $aB));
    $bB = $p_b - $l_b;
    $cB = ($p_b + $bB) / 2;
    $dB = ($sisiMiringB * $cB) * 2;
    $eB = ($l_b * $sisiMiringB * 0.5) * 2;
    $luasB = $dB + $eB;
    $starterB = ($l_b * 2) + ($p_b * 2);
    $kB = ($lebarSetengahB * $lebarSetengahB) + ($sisiMiringB * $sisiMiringB);
    $nokJuraiB = (sqrt($kB) * 4) + $bB; // Ini Hraus Di pastikan
    $flashingB = $starterB;
    
    // Hitung Limasan C
    $radC = deg2rad($sudut_c);
    $lebarSetengahC = $l_c / 2;
    $aC = $lebarSetengahC * tan($radC);
    $sisiMiringC = sqrt(($lebarSetengahC * $lebarSetengahC) + ($aC * $aC));
    $bC = $p_c - $l_c;
    $cC = ($p_c + $bC) / 2;
    $dC = ($sisiMiringC * $cC) * 2;
    $eC = ($l_c * $sisiMiringC * 0.5) * 2;
    $luasC = $dC + $eC;
    $starterC = ($l_c * 2) + ($p_c * 2);
    $kC = ($lebarSetengahC * $lebarSetengahC) + ($sisiMiringC * $sisiMiringC);
    $nokJuraiC = (sqrt($kC) * 4) + $bC;
    $flashingC = $starterC;
    
    // TOTAL LUAS
    $totalLuas = $luasA + $luasB + $luasC;
    
    // TOTAL STARTER = (starterA + starterB + starterC) - ((lebarB x 4) / cos(sudut))
    $radSudut = deg2rad($sudut_b);
    $pengurang = ($l_b * 4) / cos($radSudut);
    $totalStarter = ($starterA + $starterB + $starterC) - $pengurang;
    
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    // TALANG JURAI = sqrt((2 x (lebarB/2)^2) + ((lebarB/2) x tan(sudut))^2)
    $lebarSetengah = $l_b / 2;
    $tanSudut = tan($radSudut);
    $talangJurai = sqrt((2 * pow($lebarSetengah, 2)) + pow($lebarSetengah * $tanSudut, 2));
    
    $details = [
        ['bagian' => 'Limasan A (Atas)', 'luas_atap' => round($luasA, 2), 'starter' => round($starterA, 2), 'nok_jurai' => round($nokJuraiA, 2), 'flashing' => round($flashingA, 2)],
        ['bagian' => 'Limasan B (Tengah)', 'luas_atap' => round($luasB, 2), 'starter' => round($starterB, 2), 'nok_jurai' => round($nokJuraiB, 2), 'flashing' => round($flashingB, 2)],
        ['bagian' => 'Limasan C (Bawah)', 'luas_atap' => round($luasC, 2), 'starter' => round($starterC, 2), 'nok_jurai' => round($nokJuraiC, 2), 'flashing' => round($flashingC, 2)]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2),
            'talang_jurai' => round($talangJurai, 2)
        ]
    ]);
}
private function hitungGergaji($request)
{
    $p = $request->panjang_bangunan;
    $l = $request->lebar_bangunan;
    $j = $request->jumlah_gerigi;
    $t = $request->tinggi_gerigi;
    $sudut = $request->sudut;
    
    // Lebar setiap gerigi
    $lebarGerigi = $l / $j;
    
    // variable a = (lebar x lebar) + (tinggi atap x tinggi atap)
    $variable_a = ($lebarGerigi * $lebarGerigi) + ($t * $t);
    
    // panjang sisi miring = sqrt(variable a)
    $sisiMiring = sqrt($variable_a);
    
    // variable b = panjang sisi miring x panjang
    $variable_b = $sisiMiring * $p;
    
    // variable c = tinggi atap x panjang
    $variable_c = $t * $p;
    
    // luas area 1 bidang = variable b + variable c
    $luasPerBidang = $variable_b + $variable_c;
    
    // luas area keseluruhan = luas area 1 bidang x jumlah atap
    $totalLuas = $luasPerBidang * $j;
    
    // starting = panjang x jumlah atap
    $starter = $p * $j;
    
    // nok dan jurai = panjang x jumlah atap
    $nokJurai = $p * $j;
    
    // flashing = panjang x jumlah atap
    $flashing = $p * $j;
    
    // TALANG JURAI = (panjang x jumlah atap) - panjang
    $talangJurai = ($p * $j) - $p;
    
    $details = [
        ['bagian' => 'Atap Gergaji - ' . $j . ' Gerigi', 'luas_atap' => round($totalLuas, 2), 'starter' => round($starter, 2), 'nok_jurai' => round($nokJurai, 2), 'flashing' => round($flashing, 2)]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($starter, 2),
            'panjang_nok_jurai' => round($nokJurai, 2),
            'panjang_flashing' => round($flashing, 2),
            'talang_jurai' => round($talangJurai, 2)
        ]
    ]);
}
private function hitungPelana2Kemiringan($request)
{
    // Bagian A (Kiri)
    $p_a = $request->panjang_a;
    $l_a = $request->lebar_a;
    $sudut_a = $request->sudut_a;
    
    // Bagian B (Tengah)
    $p_b = $request->panjang_b;
    $l_b = $request->lebar_b;
    $sudut_b = $request->sudut_b;
    
    // Bagian C (Kanan)
    $p_c = $request->panjang_c;
    $l_c = $request->lebar_c;
    $sudut_c = $request->sudut_c;
    
    // Hitung Bagian A
    $radA = deg2rad($sudut_a);
    $lebarSetengahA = $l_a / 2;
    $tanA = tan($radA);
    $sisiMiringA = sqrt((($lebarSetengahA * $lebarSetengahA) + (($lebarSetengahA * $tanA) * ($lebarSetengahA * $tanA))));
    $luasA = $p_a * ($sisiMiringA*2);
    $starterA = ($p_a * 2) + ($l_a * 2);
    $nokJuraiA = $p_a;
    $flashingA = $starterA;
    
    // Hitung Bagian B
    $radB = deg2rad($sudut_b);
    $lebarSetengahB = $l_b;
    $tanB = tan($radB);
    $sisiMiringB = sqrt(($lebarSetengahB * $lebarSetengahB) + (($lebarSetengahB * $tanB) * ($lebarSetengahB * $tanB)));
    $luasB = $p_b * ($sisiMiringB);
    $starterB = ($p_b * 2) + ($sisiMiringB * 2);
    $nokJuraiB = $p_b;
    $flashingB = $starterB;
    
    // Hitung Bagian C
    $radC = deg2rad($sudut_c);
    $lebarSetengahC = $l_c ;
    $tanC = tan($radC);
    $sisiMiringC = sqrt(($lebarSetengahC * $lebarSetengahC) + (($lebarSetengahC * $tanC) * ($lebarSetengahC * $tanC)));
    $luasC = $p_c * ($sisiMiringC);
    $starterC = ($p_c * 2) + ($sisiMiringC * 2);
    $nokJuraiC = $p_c;
    $flashingC = $starterC;
    
    // Total
    $totalLuas = $luasA + $luasB + $luasC;
    $totalStarter = $starterA + $starterB + $starterC;
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    $details = [
        ['bagian' => 'Bagian Kiri (Kemiringan ' . $sudut_a . '°)', 'luas_atap' => round($luasA, 2), 'starter' => round($starterA, 2), 'nok_jurai' => round($nokJuraiA, 2), 'flashing' => round($flashingA, 2)],
        ['bagian' => 'Bagian Tengah (Kemiringan ' . $sudut_b . '°)', 'luas_atap' => round($luasB, 2), 'starter' => round($starterB, 2), 'nok_jurai' => round($nokJuraiB, 2), 'flashing' => round($flashingB, 2)],
        ['bagian' => 'Bagian Kanan (Kemiringan ' . $sudut_c . '°)', 'luas_atap' => round($luasC, 2), 'starter' => round($starterC, 2), 'nok_jurai' => round($nokJuraiC, 2), 'flashing' => round($flashingC, 2)]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungLengkung2Sisi($request)
{
    // ==================== AMBIL INPUT ====================
    // Bagian A (Kiri - Pelana)
    $p_a = floatval($request->panjang_a);
    $l_a = floatval($request->lebar_a);
    $sudut_a = floatval($request->sudut_a);
    
    // Bagian B (Tengah - Lengkung)
    $p_b = floatval($request->panjang_b);
    $l_b = floatval($request->lebar_b);
    $tinggi = floatval($request->tinggi);
    
    // Bagian C (Kanan - Pelana)
    $p_c = floatval($request->panjang_c);
    $l_c = floatval($request->lebar_c);
    $sudut_c = floatval($request->sudut_c);
    
    // ==================== VALIDASI ====================
    if ($p_a <= 0 || $l_a <= 0 || $sudut_a <= 0 || 
        $p_b <= 0 || $l_b <= 0 || $tinggi <= 0 || 
        $p_c <= 0 || $l_c <= 0 || $sudut_c <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai harus > 0'
        ], 400);
    }
    
    // ==================== BAGIAN A (KIRI - PELANA) ====================
    // Rumus sisi miring:
    // variable a = lebar x tan(sudut kemiringan)
    // panjang sisi miring = sqrt((lebar x lebar) + (variable a x variable a))
    $radA = deg2rad($sudut_a);
    $variableA = $l_a * tan($radA);
    $sisiMiringA = sqrt(pow($l_a, 2) + pow($variableA, 2));
    
    // Luas area = panjang sisi miring x panjang
    $luasA = $sisiMiringA * $p_a;
    
    // Starter = flashing = (panjang sisi miring x 2) + (panjang x 2)
    $starterA = ($sisiMiringA * 2) + ($p_a * 2);
    $flashingA = $starterA;
    
    // Nok/Jurai = panjang
    $nokJuraiA = $p_a;
    
    // ==================== BAGIAN B (TENGAH - LENGKUNG) ====================
    // --- RUMUS LENGKUNG ---
    
    // 1. Hitung jari-jari (R)
    // R = (tinggi/2) + (lebar^2 / (8 * tinggi))
    $jariJari = ($tinggi / 2) + (pow($l_b, 2) / (8 * $tinggi));
    
    // 2. Hitung sudut busur (theta) dalam radian
    // θ = 2 * asin(lebar / (2 * R))
    $theta = 2 * asin($l_b / (2 * $jariJari));
    
    // 3. Hitung panjang lengkung (busur)
    // panjang_lengkung = R * θ
    $panjangLengkung = $jariJari * $theta;
    
    // 4. Luas area = panjang_lengkung * panjang_b
    $luasB = $panjangLengkung * $p_b;
    
    // 5. Starter = flashing = panjang_lengkung * 2
    $starterB = $panjangLengkung * 2;
    $flashingB = $panjangLengkung * 2;
    
    // 6. Nok/Jurai = panjang_b
    $nokJuraiB = $p_b;
    
    // ==================== BAGIAN C (KANAN - PELANA) ====================
    // Rumus sisi miring sama dengan bagian A
    $radC = deg2rad($sudut_c);
    $variableC = $l_c * tan($radC);
    $sisiMiringC = sqrt(pow($l_c, 2) + pow($variableC, 2));
    
    // Luas area = panjang sisi miring x panjang
    $luasC = $sisiMiringC * $p_c;
    
    // Starter = flashing = (panjang sisi miring x 2) + (panjang x 2)
    $starterC = ($sisiMiringC * 2) + ($p_c * 2);
    $flashingC = $starterC;
    
    // Nok/Jurai = panjang
    $nokJuraiC = $p_c;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasA + $luasB + $luasC;
    $totalStarter = $starterA + $starterB + $starterC;
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    // ==================== RESPONSE (MATCH VIEW) ====================
    $details = [
        [
            'bagian' => 'Sisi Kiri (Pelana ' . $sudut_a . '°)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2),
            'debug' => [
                'sisi_miring' => round($sisiMiringA, 2),
                'variable_a' => round($variableA, 2)
            ]
        ],
        [
            'bagian' => 'Bagian Tengah (Lengkung)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2),
            'debug' => [
                'jari_jari' => round($jariJari, 2),
                'panjang_lengkung' => round($panjangLengkung, 2)
            ]
        ],
        [
            'bagian' => 'Sisi Kanan (Pelana ' . $sudut_c . '°)',
            'luas_atap' => round($luasC, 2),
            'starter' => round($starterC, 2),
            'nok_jurai' => round($nokJuraiC, 2),
            'flashing' => round($flashingC, 2),
            'debug' => [
                'sisi_miring' => round($sisiMiringC, 2),
                'variable_c' => round($variableC, 2)
            ]
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ],
        'debug' => [
            'sisi_miring_a' => round($sisiMiringA, 2),
            'sisi_miring_c' => round($sisiMiringC, 2),
            'jari_jari_lengkung' => round($jariJari, 2),
            'panjang_lengkung' => round($panjangLengkung, 2),
            'sudut_busur_deg' => round(rad2deg($theta), 2)
        ]
    ]);
}
private function hitungPelana2Sisi($request)
{
    // ==================== AMBIL INPUT ====================
    // Bagian A (Kiri)
    $p_a = floatval($request->panjang_a);
    $l_a = floatval($request->lebar_a);
    $sudut_a = floatval($request->sudut_a);
    
    // Bagian B (Tengah)
    $p_b = floatval($request->panjang_b);
    $l_b = floatval($request->lebar_b);
    $sudut_b = floatval($request->sudut_b);
    
    // Bagian C (Kanan)
    $p_c = floatval($request->panjang_c);
    $l_c = floatval($request->lebar_c);
    $sudut_c = floatval($request->sudut_c);
    
    // ==================== VALIDASI ====================
    if ($p_a <= 0 || $l_a <= 0 || $sudut_a <= 0 || 
        $p_b <= 0 || $l_b <= 0 || $sudut_b <= 0 || 
        $p_c <= 0 || $l_c <= 0 || $sudut_c <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai harus > 0'
        ], 400);
    }
    
    // ==================== BAGIAN A (KIRI) ====================
    $radA = deg2rad($sudut_a);
    $lebarSetengahA = $l_a / 2;
    $tanA = tan($radA);
    $sisiMiringA = sqrt((($lebarSetengahA * $lebarSetengahA) + (($lebarSetengahA * $tanA) * ($lebarSetengahA * $tanA))));
    $luasA = $p_a * ($sisiMiringA * 2);
    $starterA = ($p_a * 2) + ($sisiMiringA * 4);
    $nokJuraiA = $p_a;
    $flashingA = $starterA;
    
    // ==================== BAGIAN B (TENGAH) ====================
    $radB = deg2rad($sudut_b);
    $lebarSetengahB = $l_b / 2;
    $tanB = tan($radB);
    $sisiMiringB = sqrt((($lebarSetengahB * $lebarSetengahB) + (($lebarSetengahB * $tanB) * ($lebarSetengahB * $tanB))));
    $luasB = $p_b * ($sisiMiringB * 2);
    $starterB = ($sisiMiringB * 4) + ($p_b * 2);
    $nokJuraiB = $p_b;
    $flashingB = $starterB;
    
    // ==================== BAGIAN C (KANAN) ====================
    $radC = deg2rad($sudut_c);
    $lebarSetengahC = $l_c / 2;
    $tanC = tan($radC);
    $sisiMiringC = sqrt((($lebarSetengahC * $lebarSetengahC) + (($lebarSetengahC * $tanC) * ($lebarSetengahC * $tanC))));
    $luasC = $p_c * ($sisiMiringC * 2);
    $starterC = ($p_c * 2) + ($sisiMiringC * 4);
    $nokJuraiC = $p_c;
    $flashingC = $starterC;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasA + $luasB + $luasC;
    $totalStarter = $starterA + $starterB + $starterC;
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    // ==================== RESPONSE ====================
    $details = [
        [
            'bagian' => 'Sisi Kiri (Kemiringan ' . $sudut_a . '°)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2)
        ],
        [
            'bagian' => 'Bagian Tengah (Kemiringan ' . $sudut_b . '°)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2)
        ],
        [
            'bagian' => 'Sisi Kanan (Kemiringan ' . $sudut_c . '°)',
            'luas_atap' => round($luasC, 2),
            'starter' => round($starterC, 2),
            'nok_jurai' => round($nokJuraiC, 2),
            'flashing' => round($flashingC, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungPelanaDinding($request)
{
    // ==================== AMBIL INPUT ====================
    // Data Atap Pelana
    $panjang = floatval($request->panjang);
    $lebar = floatval($request->lebar);
    $sudut = floatval($request->sudut);
    
    // Data Dinding
    $panjangDinding = floatval($request->panjang_dinding);
    $tinggiDinding = floatval($request->tinggi_dinding);
    $jumlahSisi = intval($request->jumlah_sisi) ?? 2;
    
    // ==================== VALIDASI ====================
    if ($panjang <= 0 || $lebar <= 0 || $sudut <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai atap harus > 0'
        ], 400);
    }
    
    if ($panjangDinding <= 0 || $tinggiDinding <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai dinding harus > 0'
        ], 400);
    }
    
    // ==================== HITUNG ATAP PELANA ====================
    $rad = deg2rad($sudut);
    $lebarSetengah = $lebar / 2;
    $tan = tan($rad);
    
    // Sisi miring atap
    $sisiMiring = sqrt(
        ($lebarSetengah * $lebarSetengah) + 
        (($lebarSetengah * $tan) * ($lebarSetengah * $tan))
    );
    
    // Luas atap (2 sisi)
    $luasAtap = $panjang * ($sisiMiring * 2);
    
    // Starter (keliling bawah atap)
    $starter = ($panjang * 2) + ($sisiMiring * 4);
    
    // Nok & Jurai (panjang puncak atap)
    $nokJurai = $panjang;
    
    // Flashing (sama dengan starter untuk pelana)
    $flashing = $starter;
    
    // ==================== HITUNG DINDING ====================
    // Luas dinding per sisi
    $luasDindingPerSisi = $panjangDinding * $tinggiDinding;
    
    // Total luas dinding (sesuai jumlah sisi)
    $luasDinding = $luasDindingPerSisi * $jumlahSisi;
    
    // Wall Flashing (keliling atas dinding)
    $wallFlashing = $panjangDinding * $jumlahSisi;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasAtap + $luasDinding;
    $totalStarter = $starter;
    $totalNokJurai = $nokJurai;
    $totalFlashing = $flashing;
    $totalWallFlashing = $wallFlashing;
    
    // ==================== RESPONSE ====================
    $details = [
        [
            'bagian' => 'Atap Pelana (Kemiringan ' . $sudut . '°)',
            'luas_atap' => round($luasAtap, 2),
            'starter' => round($starter, 2),
            'nok_jurai' => round($nokJurai, 2),
            'flashing' => round($flashing, 2),
            'wall_flashing' => 0
        ],
        [
            'bagian' => 'Dinding (' . $jumlahSisi . ' Sisi)',
            'luas_atap' => round($luasDinding, 2),
            'starter' => 0,
            'nok_jurai' => 0,
            'flashing' => 0,
            'wall_flashing' => round($wallFlashing, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($luasAtap, 2),
            'luas_dinding' => round($luasDinding, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2),
            'panjang_wall_flashing' => round($totalWallFlashing, 2)
        ]
    ]);
}
private function hitungPelana3Arah($request)
{
    // ==================== AMBIL INPUT ====================
    // Bagian A (Depan)
    $p_a = floatval($request->panjang_a);
    $l_a = floatval($request->lebar_a);
    $sudut_a = floatval($request->sudut_a);
    
    // Bagian B (Belakang)
    $p_b = floatval($request->panjang_b);
    $l_b = floatval($request->lebar_b);
    $sudut_b = floatval($request->sudut_b);
    
    // Bagian C (Samping - otomatis sama dengan B)
    $p_c = $p_b;
    $l_c = $l_b;
    $sudut_c = $sudut_b;
    
    // ==================== VALIDASI ====================
    if ($p_a <= 0 || $l_a <= 0 || $sudut_a <= 0 || 
        $p_b <= 0 || $l_b <= 0 || $sudut_b <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai harus > 0'
        ], 400);
    }
    
    // ==================== BAGIAN A (DEPAN) ====================
    $radA = deg2rad($sudut_a);
    $lebarSetengahA = $l_a / 2;
    $tanA = tan($radA);
    $sisiMiringA = sqrt((($lebarSetengahA * $lebarSetengahA) + (($lebarSetengahA * $tanA) * ($lebarSetengahA * $tanA))));
    $luasA = $p_a * ($sisiMiringA * 2);
    $starterA = ($p_a * 2) + ($sisiMiringA * 4);
    
    // Nok Depan = panjang depan + (lebar/2)
    $nokJuraiA = $p_a + ($l_a / 2);
    $flashingA = $starterA;
    
    // ==================== BAGIAN B (BELAKANG) ====================
    $radB = deg2rad($sudut_b);
    $lebarSetengahB = $l_b / 2;
    $tanB = tan($radB);
    $sisiMiringB = sqrt((($lebarSetengahB * $lebarSetengahB) + (($lebarSetengahB * $tanB) * ($lebarSetengahB * $tanB))));
    $luasB = $p_b * ($sisiMiringB * 2);
    $starterB = ($sisiMiringB * 4) + ($p_b * 2);
    
    // Nok Belakang tetap panjang belakang
    $nokJuraiB = $p_b;
    $flashingB = $starterB;
    
    // ==================== BAGIAN C (SAMPING - SAMA DENGAN B) ====================
    $luasC = $luasB;
    $starterC = $starterB;
    $nokJuraiC = $nokJuraiB;
    $flashingC = $flashingB;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasA + $luasB + $luasC;
    $totalStarter = $starterA + $starterB + $starterC;
    $totalNokJurai = $nokJuraiA + $nokJuraiB + $nokJuraiC;
    $totalFlashing = $flashingA + $flashingB + $flashingC;
    
    // ==================== RESPONSE ====================
    $details = [
        [
            'bagian' => 'Sisi Depan (Kemiringan ' . $sudut_a . '°)',
            'luas_atap' => round($luasA, 2),
            'starter' => round($starterA, 2),
            'nok_jurai' => round($nokJuraiA, 2),
            'flashing' => round($flashingA, 2)
        ],
        [
            'bagian' => 'Sisi Belakang (Kemiringan ' . $sudut_b . '°)',
            'luas_atap' => round($luasB, 2),
            'starter' => round($starterB, 2),
            'nok_jurai' => round($nokJuraiB, 2),
            'flashing' => round($flashingB, 2)
        ],
        [
            'bagian' => 'Sisi Samping (Otomatis dari Belakang)',
            'luas_atap' => round($luasC, 2),
            'starter' => round($starterC, 2),
            'nok_jurai' => round($nokJuraiC, 2),
            'flashing' => round($flashingC, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungTrapesiumPelana4Sisi($request)
{
    // ==================== AMBIL INPUT ====================
    // Trapesium (1 sisi - akan dikali 4)
    $panjang_atas = floatval($request->panjang_atas);
    $panjang_bawah = floatval($request->panjang_bawah);
    $tinggi = floatval($request->tinggi);
    $sudut_trapesium = floatval($request->sudut_trapesium);
    
    // Pelana (1 sisi - akan dikali 4)
    $panjang_pelana = floatval($request->panjang_pelana);
    $lebar_pelana = floatval($request->lebar_pelana);
    $sudut_pelana = floatval($request->sudut_pelana);
    
    // ==================== VALIDASI ====================
    if ($panjang_atas <= 0 || $panjang_bawah <= 0 || $tinggi <= 0 || $sudut_trapesium <= 0 ||
        $panjang_pelana <= 0 || $lebar_pelana <= 0 || $sudut_pelana <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai harus > 0'
        ], 400);
    }
    
    // ==================== HITUNG TRAPESIUM (1 SISI) ====================
    // Luas trapesium = ((atas + bawah) / 2) * tinggi
    $luasTrapesiumSatuSisi = (($panjang_atas + $panjang_bawah) / 2) * $tinggi;
    $starterTrapesiumSatuSisi = $panjang_atas + $panjang_bawah + (2 * $tinggi);
    $nokJuraiTrapesiumSatuSisi = $panjang_atas;
    $flashingTrapesiumSatuSisi = $starterTrapesiumSatuSisi;
    
    // Trapesium dikali 4 sisi
    $luasTrapesium = $luasTrapesiumSatuSisi * 4;
    $starterTrapesium = $starterTrapesiumSatuSisi * 4;
    $nokJuraiTrapesium = $nokJuraiTrapesiumSatuSisi * 4;
    $flashingTrapesium = $flashingTrapesiumSatuSisi * 4;
    
    // ==================== HITUNG PELANA (1 SISI) ====================
    $radPelana = deg2rad($sudut_pelana);
    $lebarSetengahPelana = $lebar_pelana / 2;
    $tanPelana = tan($radPelana);
    $sisiMiringPelana = sqrt((($lebarSetengahPelana * $lebarSetengahPelana) + (($lebarSetengahPelana * $tanPelana) * ($lebarSetengahPelana * $tanPelana))));
    
    $luasPelanaSatuSisi = $panjang_pelana * ($sisiMiringPelana * 2);
    $starterPelanaSatuSisi = ($panjang_pelana * 2) + ($sisiMiringPelana * 4);
    $nokJuraiPelanaSatuSisi = $panjang_pelana;
    $flashingPelanaSatuSisi = $starterPelanaSatuSisi;
    
    // Pelana dikali 4 sisi
    $luasPelana = $luasPelanaSatuSisi * 4;
    $starterPelana = $starterPelanaSatuSisi * 4;
    $nokJuraiPelana = $nokJuraiPelanaSatuSisi * 4;
    $flashingPelana = $flashingPelanaSatuSisi * 4;
    
    // ==================== TOTAL ====================
    $totalLuas = $luasTrapesium + $luasPelana;
    $totalStarter = $starterTrapesium + $starterPelana;
    $totalNokJurai = $nokJuraiTrapesium + $nokJuraiPelana;
    $totalFlashing = $flashingTrapesium + $flashingPelana;
    
    // ==================== RESPONSE ====================
    $details = [
        [
            'bagian' => 'Trapesium (×4 Sisi - Kemiringan ' . $sudut_trapesium . '°)',
            'luas_atap' => round($luasTrapesium, 2),
            'starter' => round($starterTrapesium, 2),
            'nok_jurai' => round($nokJuraiTrapesium, 2),
            'flashing' => round($flashingTrapesium, 2)
        ],
        [
            'bagian' => 'Pelana (×4 Sisi - Kemiringan ' . $sudut_pelana . '°)',
            'luas_atap' => round($luasPelana, 2),
            'starter' => round($starterPelana, 2),
            'nok_jurai' => round($nokJuraiPelana, 2),
            'flashing' => round($flashingPelana, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($totalLuas, 2),
            'panjang_starter' => round($totalStarter, 2),
            'panjang_nok_jurai' => round($totalNokJurai, 2),
            'panjang_flashing' => round($totalFlashing, 2)
        ]
    ]);
}
private function hitungTrapesiumKotak($request)
{
    // ==================== AMBIL INPUT ====================
    $panjang_atas = floatval($request->panjang_atas);
    $panjang_bawah = floatval($request->panjang_bawah);
    $tinggi = floatval($request->tinggi);
    $sudut = floatval($request->sudut);
    
    // ==================== VALIDASI ====================
    if ($panjang_atas <= 0 || $panjang_bawah <= 0 || $tinggi <= 0 || $sudut <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Semua nilai harus > 0'
        ], 400);
    }
    
    // ==================== HITUNG TRAPESIUM KOTAK ====================
    // 1. Luas area = (((panjang bawah + panjang atas)/2) x tinggi) x 4
    $luasSatuSisi = (($panjang_bawah + $panjang_atas) / 2) * $tinggi;
    $luasTotal = ($luasSatuSisi / cos(deg2rad($sudut))) * 4;
    
    // 2. Starting dan flashing = (panjang bawah x 4)
    $starter = $panjang_bawah * 4;
    $flashing = $panjang_bawah * 4;
    
    // 3. Nok dan jurai = (sqrt((tan(radians(sudut)) x tinggi)^2 + tinggi^2 + tinggi^2) x 4)
    $radSudut = deg2rad($sudut);
    $tanSudut = tan($radSudut);
    $nokJuraiSatuSisi = sqrt(pow(($tanSudut * $tinggi), 2) + pow($tinggi, 2) + pow($tinggi, 2));
    $nokJuraiTotal = $nokJuraiSatuSisi * 4;
    
    // Panjang sisi miring
    $sisiMiring = sqrt(pow($tinggi, 2) + pow(($panjang_bawah - $panjang_atas) / 2, 2));
    
    // ==================== RESPONSE ====================
    $details = [
        [
            'bagian' => 'Trapesium Kotak (×4 Sisi - Kemiringan ' . $sudut . '°)',
            'luas_atap' => round($luasTotal, 2),
            'starter' => round($starter, 2),
            'nok_jurai' => round($nokJuraiTotal, 2),
            'flashing' => round($flashing, 2),
            'panjang_sisi_miring' => round($sisiMiring, 2)
        ]
    ];
    
    return response()->json([
        'success' => true,
        'details' => $details,
        'total' => [
            'luas_atap' => round($luasTotal, 2),
            'panjang_starter' => round($starter, 2),
            'panjang_nok_jurai' => round($nokJuraiTotal, 2),
            'panjang_flashing' => round($flashing, 2),
            'panjang_sisi_miring' => round($sisiMiring, 2)
        ]
    ]);
}
}