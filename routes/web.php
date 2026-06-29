<?php

use App\Http\Controllers\AtapStandarController;
use App\Http\Controllers\AtapKombinasiController;
use App\Http\Controllers\IkoAtapController;
use App\Http\Controllers\SkyshieldStandarController;
use App\Http\Controllers\IkoInsulasiController;
use App\Http\Controllers\IkoAtapKombinasiController;
use App\Http\Controllers\SkyshieldKombinasiController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\JendelaController;
use App\Http\Controllers\DindingController;
use App\Http\Controllers\WaterproofingController;
// Route untuk halaman atap standar
Route::get('/atap-standar', [AtapStandarController::class, 'index'])->name('atap-standar.index');

// Route untuk perhitungan (AJAX)
Route::post('/atap-standar/hitung-limasan', [AtapStandarController::class, 'hitungLimasan'])->name('atap-standar.hitung-limasan');
Route::post('/atap-standar/hitung-pelana', [AtapStandarController::class, 'hitungPelana'])->name('atap-standar.hitung-pelana');
Route::post('/atap-standar/hitung-perisai', [AtapStandarController::class, 'hitungPerisai'])->name('atap-standar.hitung-perisai');
Route::post('/atap-standar/hitung-satu-kemiringan', [AtapStandarController::class, 'hitungSatuKemiringan'])->name('atap-standar.hitung-satu-kemiringan');

// Route handler umum
Route::post('/atap-standar/hitung', [AtapStandarController::class, 'hitung'])->name('atap-standar.hitung');
// BOQ Routes
Route::prefix('boq')->name('boq.')->group(function () {
Route::get('/iko-atap', [IkoAtapController::class, 'index'])->name('iko-atap.index');
Route::post('/iko-atap/hitung', [IkoAtapController::class, 'hitung'])->name('iko-atap.hitung');
Route::post('/iko-atap/export-pdf', [IkoAtapController::class, 'exportPdf'])->name('iko-atap.export-pdf');

Route::get('/skyshield', [SkyshieldStandarController::class, 'index'])->name('boq.skyshield');
Route::post('/skyshield/hitung', [SkyshieldStandarController::class, 'hitung'])->name('boq.skyshield.hitung');
Route::post('/skyshield/export-pdf', [SkyshieldStandarController::class, 'exportPdf'])->name('boq.skyshield.export-pdf');

Route::get('/iko-insulasi', [IkoInsulasiController::class, 'index'])->name('boq.iko-insulasi');
Route::post('/iko-insulasi/hitung', [IkoInsulasiController::class, 'hitung'])->name('boq.iko-insulasi.hitung');
Route::post('/iko-insulasi/hitung', [IkoInsulasiController::class, 'hitung'])->name('boq.iko-insulasi.hitung');

    Route::post('/atap-kombinasi/export-pdf', [IkoAtapKombinasiController::class, 'exportPdf'])->name('boq.iko-atap-kombinasi.export-pdf');
      // BOQ IKO Atap Kombinasi - Limasan + Trapesium
    Route::get('/atap-kombinasi/limasan-trapesium', [IkoAtapKombinasiController::class, 'limasanTrapesium'])->name('iko-atap-kombinasi.limasan-trapesium');
    Route::post('/atap-kombinasi/limasan-trapesium/hitung', [IkoAtapKombinasiController::class, 'hitungLimasanTrapesium'])->name('iko-atap-kombinasi.limasan-trapesium.hitung');
    
    // BOQ IKO Atap Kombinasi - Limas + Pelana
    Route::get('/atap-kombinasi/limas-pelana', [IkoAtapKombinasiController::class, 'limasPelana'])->name('iko-atap-kombinasi.limas-pelana');
    Route::post('/atap-kombinasi/limas-pelana/hitung', [IkoAtapKombinasiController::class, 'hitungLimasPelana'])->name('iko-atap-kombinasi.limas-pelana.hitung');

    // BOQ IKO Atap Kombinasi - Limas + Pelana
Route::get('/atap-kombinasi/limas-pelana', [IkoAtapKombinasiController::class, 'limasPelana'])->name('boq.iko-atap-kombinasi.limas-pelana');
Route::post('/atap-kombinasi/limas-pelana/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfLimasPelana'])->name('boq.iko-atap-kombinasi.limas-pelana.export-pdf');

Route::get('/atap-kombinasi/pelana-2trapesium', [IkoAtapKombinasiController::class, 'pelana2Trapesium'])->name('boq.iko-atap-kombinasi.pelana-2trapesium');
Route::post('/atap-kombinasi/pelana-2trapesium/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelana2Trapesium'])->name('boq.iko-atap-kombinasi.pelana-2trapesium.export-pdf');

Route::get('/atap-kombinasi/gergaji', [IkoAtapKombinasiController::class, 'gergaji'])->name('boq.atap-kombinasi.gergaji');
Route::post('/atap-kombinasi/gergaji/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfGergaji'])->name('boq.atap-kombinasi.gergaji.export-pdf');

Route::get('/atap-kombinasi/limasan-limasan', [IkoAtapKombinasiController::class, 'limasanLimasan'])->name('boq.iko-atap-kombinasi.limasan-limasan');
Route::post('/atap-kombinasi/limasan-limasan/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfLimasanLimasan'])->name('boq.iko-atap-kombinasi.limasan-limasan.export-pdf');
Route::get('/atap-kombinasi/limasan-x', [IkoAtapKombinasiController::class, 'limasanX'])->name('boq.atap-kombinasi.limasan-x');
Route::post('/atap-kombinasi/limasan-x/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfLimasanX'])->name('boq.atap-kombinasi.limasan-x.export-pdf');
Route::get('/atap-kombinasi/pelana-x', [IkoAtapKombinasiController::class, 'pelanaX'])->name('boq.atap-kombinasi.pelana-x');
Route::post('/atap-kombinasi/pelana-x/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelanaX'])->name('boq.atap-kombinasi.pelana-x.export-pdf');
Route::get('/atap-kombinasi/pelana-2-kemiringan', [IkoAtapKombinasiController::class, 'pelana2Kemiringan'])->name('boq.atap-kombinasi.pelana-2-kemiringan');
Route::get('/atap-kombinasi/lengkung-2-sisi', [IkoAtapKombinasiController::class, 'Lengkung2Sisi'])->name('boq.lengkung-2-sisi');
Route::post('/atap-kombinasi/lengkung-2-sisi/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfLengkung2Sisi'])->name('boq.atap-kombinasi.lengkung-2-sisi.export-pdf');
Route::get('/atap-kombinasi/pelana-dinding', [IkoAtapKombinasiController::class, 'pelanaDinding'])->name('boq.atap-kombinasi.pelana-dinding');
Route::post('/atap-kombinasi/pelana-dinding/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelanaDinding'])->name('boq.atap-kombinasi.pelana-dinding.export-pdf');
// Route untuk BOQ Pelana 3 Arah
Route::get('/atap-kombinasi/pelana-3-arah', [IkoAtapKombinasiController::class, 'pelana3Arah']);
Route::post('/atap-kombinasi/pelana-3-arah/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelana3Arah']);
Route::get('/atap-kombinasi/pelana-2-sisi', [IkoAtapKombinasiController::class, 'pelana2Sisi'])->name('boq.atap-kombinasi.pelana-2-sisi');
Route::post('/atap-kombinasi/pelana-2-sisi/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelana2Sisi'])->name('boq.atap-kombinasi.pelana-2-sisi.export-pdf');
Route::get('/atap-kombinasi/trapesium-kotak', [IkoAtapKombinasiController::class, 'trapesiumKotak'])->name('boq.atap-kombinasi.trapesium-kotak');
Route::post('/atap-kombinasi/trapesium-kotak/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfTrapesiumKotak'])->name('boq.atap-kombinasi.trapesium-kotak.export-pdf');
Route::post('/atap-kombinasi/pelana-2-kemiringan/export-pdf', [IkoAtapKombinasiController::class, 'exportPdfPelana2Kemiringan'])->name('boq.atap-kombinasi.pelana-2-kemiringan.export-pdf');

    Route::get('/atap-kombinasi-skyshield/limasan-trapesium', [SkyshieldKombinasiController::class, 'limasanTrapesium']);
    Route::get('/atap-kombinasi-skyshield/limas-pelana', [SkyshieldKombinasiController::class, 'limasPelana']);
    Route::get('/atap-kombinasi-skyshield/limasan-limasan', [SkyshieldKombinasiController::class, 'limasanLimasan']);
    Route::get('/atap-kombinasi-skyshield/pelana-pelana', [SkyshieldKombinasiController::class, 'pelanaPelana']);
    Route::get('/atap-kombinasi-skyshield/pelana-2trapesium', [SkyshieldKombinasiController::class, 'pelana2Trapesium']);
    Route::get('/atap-kombinasi-skyshield/pelana-x', [SkyshieldKombinasiController::class, 'pelanaX']);
    Route::get('/atap-kombinasi-skyshield/limasan-x', [SkyshieldKombinasiController::class, 'limasanX']);
    Route::get('/atap-kombinasi-skyshield/pelana-2-kemiringan', [SkyshieldKombinasiController::class, 'pelana2Kemiringan']);
    Route::get('/atap-kombinasi-skyshield/gergaji', [SkyshieldKombinasiController::class, 'gergaji']);
    Route::get('/atap-kombinasi-skyshield/pelana-2-sisi', [SkyshieldKombinasiController::class, 'pelana2Sisi']);
    Route::get('/atap-kombinasi-skyshield/pelana-dinding', [SkyshieldKombinasiController::class, 'pelanaDinding']);
    Route::get('/atap-kombinasi-skyshield/trapesium-kotak', [SkyshieldKombinasiController::class, 'trapesiumKotak']);
    Route::get('/atap-kombinasi-skyshield/lengkung-2-sisi', [SkyshieldKombinasiController::class, 'Lengkung2Sisi'])->name('boq.lengkung-2-sisi');
Route::get('/atap-kombinasi-skyshield/pelana-3-arah', [SkyshieldKombinasiController::class, 'pelana3Arah']);

});


// ==================== JENDELA ====================

// Halaman utama jendela
Route::get('/jendela', [JendelaController::class, 'index'])->name('jendela.index');

// AJAX hitung
Route::post('/jendela/hitung', [JendelaController::class, 'hitung'])->name('jendela.hitung');

// Export PDF
Route::post('/jendela/export-pdf', [JendelaController::class, 'exportPdf'])->name('jendela.export-pdf');

// BOQ Jendela
Route::prefix('boq/jendela')->name('boq.jendela.')->group(function () {
    Route::get('/kayu', [JendelaController::class, 'bojKayu'])->name('kayu');
    Route::get('/aluminium', [JendelaController::class, 'bojAluminium'])->name('aluminium');
    Route::get('/upvc', [JendelaController::class, 'bojUpvc'])->name('upvc');
    Route::get('/mati-1-kaca', [JendelaController::class, 'bojMati1Kaca'])->name('mati-1-kaca');
});

Route::get('/atap-kombinasi', [AtapKombinasiController::class, 'index'])->name('atap-kombinasi.index');
Route::post('/atap-kombinasi/hitung', [AtapKombinasiController::class, 'hitung'])->name('atap-kombinasi.hitung');


// BOQ
Route::post('/boq/store', [BoqController::class, 'storeBoq'])->name('boq.store');

// Get BOQ
Route::get('/boq', [BoqController::class, 'index'])->name('boq.index');
Route::get('/boq/{id}', [BoqController::class, 'show'])->name('boq.show');


Route::prefix('dinding')->group(function () {
    // Halaman utama (card eksterior & interior)
    Route::get('/', [DindingController::class, 'index'])->name('dinding.index');
    
    // Halaman BOQ
    Route::get('/boq-eksterior', [DindingController::class, 'boqEksterior'])->name('dinding.boq-eksterior');
    Route::get('/boq-interior', [DindingController::class, 'boqInterior'])->name('dinding.boq-interior');
    Route::get('/boq-insulasi', [DindingController::class, 'boqInsulasi'])->name('dinding.boq-insulasi');
Route::post('/hitung-insulasi', [DindingController::class, 'hitungInsulasi'])->name('dinding.hitung-insulasi');
    Route::post('/hitung-eksterior', [DindingController::class, 'hitungEksterior'])->name('dinding.hitung-eksterior');
    Route::post('/hitung-interior', [DindingController::class, 'hitungInterior'])->name('dinding.hitung-interior');
    
    // Export PDF
    Route::post('/boq/export-pdf', [DindingController::class, 'exportPdf'])->name('dinding.export-pdf');
});


// Waterproofing
Route::prefix('waterproofing')->group(function () {
    Route::get('/', [WaterproofingController::class, 'index'])->name('waterproofing.index');
    Route::get('/boq', [WaterproofingController::class, 'boq'])->name('waterproofing.boq');
    Route::post('/hitung', [WaterproofingController::class, 'hitung'])->name('waterproofing.hitung');
    Route::post('/export-pdf', [WaterproofingController::class, 'exportPdf'])->name('waterproofing.export-pdf');
});