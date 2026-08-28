<?php

use App\Http\Controllers\AtapStandarController;
use App\Http\Controllers\AtapKombinasiController;
use App\Http\Controllers\IkoAtapController;
use App\Http\Controllers\SkyshieldStandarController;
use App\Http\Controllers\PalmexController;
use App\Http\Controllers\TaperoofController;
use App\Http\Controllers\FlexiroofController;
use App\Http\Controllers\EcoroofController;
use App\Http\Controllers\EcoroofKombinasiController;
use App\Http\Controllers\FlexiroofKombinasiController;
use App\Http\Controllers\MahaflatController;
use App\Http\Controllers\MahaflatKombinasiController;
use App\Http\Controllers\TaperoofKombinasiController;
use App\Http\Controllers\PalmexKombinasiController;
use App\Http\Controllers\IkoInsulasiController;
use App\Http\Controllers\IkoAtapKombinasiController;
use App\Http\Controllers\SkyshieldKombinasiController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\JendelaController;
use App\Http\Controllers\PintuController;
use App\Http\Controllers\DindingController;
use App\Http\Controllers\WaterproofingController;
// Route untuk halaman atap standar
Route::get('/atap-standar', [AtapStandarController::class, 'index'])->name('atap-standar.index');

// Route untuk perhitungan (AJAX)
Route::post('/atap-standar/hitung-limasan', [AtapStandarController::class, 'hitungLimasan'])->name('atap-standar.hitung-limasan');
Route::post('/atap-standar/hitung-pelana', [AtapStandarController::class, 'hitungPelana'])->name('atap-standar.hitung-pelana');
Route::post('/atap-standar/hitung-perisai', [AtapStandarController::class, 'hitungPerisai'])->name('atap-standar.hitung-perisai');
Route::post('/atap-standar/hitung-satu-kemiringan', [AtapStandarController::class, 'hitungSatuKemiringan'])->name('atap-standar.hitung-satu-kemiringan');


Route::post('/atap-standar/hitung-palmex-limasan', [AtapStandarController::class, 'hitungPalmexLimasan'])->name('atap-standar.hitung-palmex-limasan');
Route::post('/atap-standar/hitung-palmex-piramid', [AtapStandarController::class, 'hitungPalmexPiramid'])->name('atap-standar.hitung-palmex-piramid');
Route::post('/atap-standar/hitung-palmex-kerucut', [AtapStandarController::class, 'hitungPalmexKerucut'])->name('atap-standar.hitung-palmex-kerucut');
Route::post('/atap-standar/hitung-palmex-satu-kemiringan', [AtapStandarController::class, 'hitungPalmexSatuKemiringan'])->name('atap-standar.hitung-palmex-satu-kemiringan');
Route::post('/atap-standar/hitung-palmex-dome', [AtapStandarController::class, 'hitungPalmexDome'])->name('atap-standar.hitung-palmex-dome');
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


// PALMEX 
Route::prefix('boq/palmex')->name('boq.palmex.')->group(function () {
    Route::get('/{model}', [App\Http\Controllers\PalmexController::class, 'index'])->name('index');
    Route::post('/{model}/hitung', [App\Http\Controllers\PalmexController::class, 'hitung'])->name('hitung');
    Route::post('/{model}/export-pdf', [App\Http\Controllers\PalmexController::class, 'exportPdf'])->name('export-pdf');
});
Route::post('/palmex/kombinasi/hitung', [App\Http\Controllers\AtapKombinasiController::class, 'hitung'])
    ->name('palmex.kombinasi.hitung');
    Route::get('/boq/palmex/atap-kombinasi/limasan-trapesium', [App\Http\Controllers\PalmexKombinasiController::class, 'limasanTrapesium'])
    ->name('boq.palmex.atap-kombinasi.limasan-trapesium');
   Route::get('/boq/palmex/atap-kombinasi/limas-pelana', [App\Http\Controllers\PalmexKombinasiController::class, 'limasPelana'])
    ->name('boq.palmex.atap-kombinasi.limas-pelana');
    Route::get('/boq/palmex/atap-kombinasi/pelana-2trapesium', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'pelana2Trapesium']
)->name('boq.palmex.atap-kombinasi.pelana-2trapesium');
Route::get('/boq/palmex/atap-kombinasi/limasan-limasan', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'limasanLimasan']
)->name('boq.palmex.atap-kombinasi.limasan-limasan');
Route::get('/boq/palmex/atap-kombinasi/pelana-2-kemiringan', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'pelana2Kemiringan']
)->name('boq.palmex.atap-kombinasi.pelana-2-kemiringan');
Route::get('/boq/palmex/atap-kombinasi/pelana-2-sisi', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'pelana2Sisi']
)->name('boq.palmex.atap-kombinasi.pelana-2-sisi');
Route::get('/boq/palmex/atap-kombinasi/pelana-3-arah', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'pelana3Arah']
)->name('boq.palmex.atap-kombinasi.pelana-3-arah');
Route::get('/boq/palmex/atap-kombinasi/pelana-x', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'pelanaX']
)->name('boq.palmex.atap-kombinasi.pelana-x');
Route::get('/boq/palmex/atap-kombinasi/limasan-x', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'limasanX']
)->name('boq.palmex.atap-kombinasi.limasan-x');
Route::get('/boq/palmex/atap-kombinasi/gergaji', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'gergaji']
)->name('boq.palmex.atap-kombinasi.gergaji');
Route::get('/boq/palmex/atap-kombinasi/lengkung-2-sisi', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'lengkung2Sisi']
)->name('boq.palmex.atap-kombinasi.lengkung-2-sisi');
Route::get('/boq/palmex/atap-kombinasi/pelana-dinding', [PalmexKombinasiController::class, 'pelanaDinding'])->name('boq.palmex.atap-kombinasi.pelana-dinding');


    Route::post('/boq/palmex/atap-kombinasi/limasan-trapesium/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungLimasanTrapesium']
)->name('boq.palmex.atap-kombinasi.limasan-trapesium.hitung');
Route::post('/boq/palmex/atap-kombinasi/limas-pelana/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungLimasPelana']
)->name('boq.palmex.atap-kombinasi.limas-pelana.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-2trapesium/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungPelana2Trapesium']
)->name('boq.palmex.atap-kombinasi.pelana-2trapesium.hitung');
Route::post('/boq/palmex/atap-kombinasi/limasan-limasan/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungLimasanLimasan']
)->name('boq.palmex.atap-kombinasi.limasan-limasan.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-2-kemiringan/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungPelana2Kemiringan']
)->name('boq.palmex.atap-kombinasi.pelana-2-kemiringan.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-2-sisi/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungPelana2Sisi']
)->name('boq.palmex.atap-kombinasi.pelana-2-sisi.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-3-arah/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungPelana3Arah']
)->name('boq.palmex.atap-kombinasi.pelana-3-arah.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-x/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungPelanaX']
)->name('boq.palmex.atap-kombinasi.pelana-x.hitung');
Route::post('/boq/palmex/atap-kombinasi/limasan-x/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungLimasanX']
)->name('boq.palmex.atap-kombinasi.limasan-x.hitung');
Route::post('/boq/palmex/atap-kombinasi/gergaji/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungGergaji']
)->name('boq.palmex.atap-kombinasi.gergaji.hitung');
Route::post('/boq/palmex/atap-kombinasi/lengkung-2-sisi/hitung', 
    [App\Http\Controllers\PalmexKombinasiController::class, 'hitungLengkung2Sisi']
)->name('boq.palmex.atap-kombinasi.lengkung-2-sisi.hitung');
Route::post('/boq/palmex/atap-kombinasi/pelana-dinding/hitung', [PalmexKombinasiController::class, 'hitungPelanaDinding'])->name('boq.palmex.atap-kombinasi.pelana-dinding.hitung');
    Route::post('/boq/palmex/atap-kombinasi/{jenis}/export-pdf', [PalmexKombinasiController::class, 'exportPdf'])
    ->name('boq.palmex.kombinasi.export-pdf');


// Taperoof
    Route::prefix('boq/taperoof')->group(function () {
    Route::get('/{model}', [TaperoofController::class, 'index'])->name('boq.taperoof.index');
    Route::post('/{model}/hitung', [TaperoofController::class, 'hitung'])->name('boq.taperoof.hitung');
    Route::post('/{model}/export-pdf', [TaperoofController::class, 'exportPdf'])->name('boq.taperoof.export-pdf');
});

Route::get('/boq/taperoof/atap-kombinasi/gergaji', [TaperoofKombinasiController::class, 'gergaji'])
    ->name('boq.taperoof.gergaji');
Route::post('/boq/taperoof/atap-kombinasi/gergaji/hitung', [TaperoofKombinasiController::class, 'hitungGergaji'])
    ->name('boq.taperoof.gergaji.hitung');
Route::post('/boq/taperoof/atap-kombinasi/gergaji/export-pdf', [TaperoofKombinasiController::class, 'exportPdfGergaji'])
    ->name('boq.taperoof.gergaji.export-pdf');


    Route::get('/boq/taperoof/atap-kombinasi/lengkung-2-sisi', [TaperoofKombinasiController::class, 'lengkung2Sisi'])
    ->name('boq.taperoof.lengkung-2-sisi');
Route::post('/boq/taperoof/atap-kombinasi/lengkung-2-sisi/hitung', [TaperoofKombinasiController::class, 'hitungLengkung2Sisi'])
    ->name('boq.taperoof.lengkung-2-sisi.hitung');
Route::post('/boq/taperoof/atap-kombinasi/lengkung-2-sisi/export-pdf', [TaperoofKombinasiController::class, 'exportPdfLengkung2Sisi'])
    ->name('boq.taperoof.lengkung-2-sisi.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/limas-pelana', [TaperoofKombinasiController::class, 'limasPelana'])
    ->name('boq.taperoof.limas-pelana');
Route::post('/boq/taperoof/atap-kombinasi/limas-pelana/hitung', [TaperoofKombinasiController::class, 'hitungLimasPelana'])
    ->name('boq.taperoof.limas-pelana.hitung');
Route::post('/boq/taperoof/atap-kombinasi/limas-pelana/export-pdf', [TaperoofKombinasiController::class, 'exportPdfLimasPelana'])
    ->name('boq.taperoof.limas-pelana.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/limasan-limasan', [TaperoofKombinasiController::class, 'limasanLimasan'])
    ->name('boq.taperoof.limasan-limasan');
Route::post('/boq/taperoof/atap-kombinasi/limasan-limasan/hitung', [TaperoofKombinasiController::class, 'hitungLimasanLimasan'])
    ->name('boq.taperoof.limasan-limasan.hitung');
Route::post('/boq/taperoof/atap-kombinasi/limasan-limasan/export-pdf', [TaperoofKombinasiController::class, 'exportPdfLimasanLimasan'])
    ->name('boq.taperoof.limasan-limasan.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/limasan-trapesium', [TaperoofKombinasiController::class, 'limasanTrapesium'])
    ->name('boq.taperoof.limasan-trapesium');
Route::post('/boq/taperoof/atap-kombinasi/limasan-trapesium/hitung', [TaperoofKombinasiController::class, 'hitungLimasanTrapesium'])
    ->name('boq.taperoof.limasan-trapesium.hitung');
Route::post('/boq/taperoof/atap-kombinasi/limasan-trapesium/export-pdf', [TaperoofKombinasiController::class, 'exportPdfLimasanTrapesium'])
    ->name('boq.taperoof.limasan-trapesium.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/limasan-x', [TaperoofKombinasiController::class, 'limasanX'])
    ->name('boq.taperoof.limasan-x');
Route::post('/boq/taperoof/atap-kombinasi/limasan-x/hitung', [TaperoofKombinasiController::class, 'hitungLimasanX'])
    ->name('boq.taperoof.limasan-x.hitung');
Route::post('/boq/taperoof/atap-kombinasi/limasan-x/export-pdf', [TaperoofKombinasiController::class, 'exportPdfLimasanX'])
    ->name('boq.taperoof.limasan-x.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/pelana-2-kemiringan', [TaperoofKombinasiController::class, 'pelana2Kemiringan'])
    ->name('boq.taperoof.pelana-2-kemiringan');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2-kemiringan/hitung', [TaperoofKombinasiController::class, 'hitungPelana2Kemiringan'])
    ->name('boq.taperoof.pelana-2-kemiringan.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2-kemiringan/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelana2Kemiringan'])
    ->name('boq.taperoof.pelana-2-kemiringan.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/pelana-2-sisi', [TaperoofKombinasiController::class, 'pelana2Sisi'])
    ->name('boq.taperoof.pelana-2-sisi');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2-sisi/hitung', [TaperoofKombinasiController::class, 'hitungPelana2Sisi'])
    ->name('boq.taperoof.pelana-2-sisi.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2-sisi/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelana2Sisi'])
    ->name('boq.taperoof.pelana-2-sisi.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/pelana-2trapesium', [TaperoofKombinasiController::class, 'pelana2Trapesium'])
    ->name('boq.taperoof.pelana-2trapesium');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2trapesium/hitung', [TaperoofKombinasiController::class, 'hitungPelana2Trapesium'])
    ->name('boq.taperoof.pelana-2trapesium.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-2trapesium/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelana2Trapesium'])
    ->name('boq.taperoof.pelana-2trapesium.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/pelana-3-arah', [TaperoofKombinasiController::class, 'pelana3Arah'])
    ->name('boq.taperoof.pelana-3-arah');
Route::post('/boq/taperoof/atap-kombinasi/pelana-3-arah/hitung', [TaperoofKombinasiController::class, 'hitungPelana3Arah'])
    ->name('boq.taperoof.pelana-3-arah.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-3-arah/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelana3Arah'])
    ->name('boq.taperoof.pelana-3-arah.export-pdf');

    Route::get('/boq/taperoof/atap-kombinasi/pelana-x', [TaperoofKombinasiController::class, 'pelanaX'])
    ->name('boq.taperoof.pelana-x');
Route::post('/boq/taperoof/atap-kombinasi/pelana-x/hitung', [TaperoofKombinasiController::class, 'hitungPelanaX'])
    ->name('boq.taperoof.pelana-x.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-x/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelanaX'])
    ->name('boq.taperoof.pelana-x.export-pdf');

        Route::get('/boq/taperoof/atap-kombinasi/pelana-dinding', [TaperoofKombinasiController::class, 'pelanaDinding'])
    ->name('boq.taperoof.pelana-dinding');
Route::post('/boq/taperoof/atap-kombinasi/pelana-dinding/hitung', [TaperoofKombinasiController::class, 'hitungPelanaDinding'])
    ->name('boq.taperoof.pelana-dinding.hitung');
Route::post('/boq/taperoof/atap-kombinasi/pelana-dinding/export-pdf', [TaperoofKombinasiController::class, 'exportPdfPelanaDinding'])
    ->name('boq.taperoof.pelana-dinding.export-pdf');
// MAHA FLAT ROOF
Route::prefix('boq/mahaflat')->group(function () {
    Route::get('/{model}', [MahaflatController::class, 'index'])->name('boq.mahaflat.index');
    Route::post('/{model}/hitung', [MahaflatController::class, 'hitung'])->name('boq.mahaflat.hitung');
    Route::post('/{model}/export-pdf', [MahaflatController::class, 'exportPdf'])->name('boq.mahaflat.export-pdf');
});

Route::get('/boq/mahaflat/atap-kombinasi/gergaji', [MahaflatKombinasiController::class, 'gergaji'])
    ->name('boq.mahaflat.gergaji');
Route::post('/boq/mahaflat/atap-kombinasi/gergaji/hitung', [MahaflatKombinasiController::class, 'hitungGergaji'])
    ->name('boq.mahaflat.gergaji.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/gergaji/export-pdf', [MahaflatKombinasiController::class, 'exportPdfGergaji'])
    ->name('boq.mahaflat.gergaji.export-pdf');

        Route::get('/boq/mahaflat/atap-kombinasi/lengkung-2-sisi', [MahaflatKombinasiController::class, 'lengkung2Sisi'])
    ->name('boq.mahaflat.lengkung-2-sisi');
Route::post('/boq/mahaflat/atap-kombinasi/lengkung-2-sisi/hitung', [MahaflatKombinasiController::class, 'hitungLengkung2Sisi'])
    ->name('boq.mahaflat.lengkung-2-sisi.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/lengkung-2-sisi/export-pdf', [MahaflatKombinasiController::class, 'exportPdfLengkung2Sisi'])
    ->name('boq.mahaflat.lengkung-2-sisi.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/limas-pelana', [MahaflatKombinasiController::class, 'limasPelana'])
    ->name('boq.mahaflat.limas-pelana');
Route::post('/boq/mahaflat/atap-kombinasi/limas-pelana/hitung', [MahaflatKombinasiController::class, 'hitungLimasPelana'])
    ->name('boq.mahaflat.limas-pelana.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/limas-pelana/export-pdf', [MahaflatKombinasiController::class, 'exportPdfLimasPelana'])
    ->name('boq.mahaflat.limas-pelana.export-pdf');

        Route::get('/boq/mahaflat/atap-kombinasi/limasan-limasan', [MahaflatKombinasiController::class, 'limasanLimasan'])
    ->name('boq.mahaflat.limasan-limasan');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-limasan/hitung', [MahaflatKombinasiController::class, 'hitungLimasanLimasan'])
    ->name('boq.mahaflat.limasan-limasan.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-limasan/export-pdf', [MahaflatKombinasiController::class, 'exportPdfLimasanLimasan'])
    ->name('boq.mahaflat.limasan-limasan.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/limasan-trapesium', [MahaflatKombinasiController::class, 'limasanTrapesium'])
    ->name('boq.mahaflat.limasan-trapesium');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-trapesium/hitung', [MahaflatKombinasiController::class, 'hitungLimasanTrapesium'])
    ->name('boq.mahaflat.limasan-trapesium.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-trapesium/export-pdf', [MahaflatKombinasiController::class, 'exportPdfLimasanTrapesium'])
    ->name('boq.mahaflat.limasan-trapesium.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/limasan-x', [MahaflatKombinasiController::class, 'limasanX'])
    ->name('boq.mahaflat.limasan-x');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-x/hitung', [MahaflatKombinasiController::class, 'hitungLimasanX'])
    ->name('boq.mahaflat.limasan-x.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/limasan-x/export-pdf', [MahaflatKombinasiController::class, 'exportPdfLimasanX'])
    ->name('boq.mahaflat.limasan-x.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/pelana-2-kemiringan', [MahaflatKombinasiController::class, 'pelana2Kemiringan'])
    ->name('boq.mahaflat.pelana-2-kemiringan');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2-kemiringan/hitung', [MahaflatKombinasiController::class, 'hitungPelana2Kemiringan'])
    ->name('boq.mahaflat.pelana-2-kemiringan.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2-kemiringan/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelana2Kemiringan'])
    ->name('boq.mahaflat.pelana-2-kemiringan.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/pelana-2-sisi', [MahaflatKombinasiController::class, 'pelana2Sisi'])
    ->name('boq.mahaflat.pelana-2-sisi');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2-sisi/hitung', [MahaflatKombinasiController::class, 'hitungPelana2Sisi'])
    ->name('boq.mahaflat.pelana-2-sisi.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2-sisi/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelana2Sisi'])
    ->name('boq.mahaflat.pelana-2-sisi.export-pdf');

    Route::get('/boq/mahaflat/atap-kombinasi/pelana-2trapesium', [MahaflatKombinasiController::class, 'pelana2Trapesium'])
    ->name('boq.mahaflat.pelana-2trapesium');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2trapesium/hitung', [MahaflatKombinasiController::class, 'hitungPelana2Trapesium'])
    ->name('boq.mahaflat.pelana-2trapesium.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-2trapesium/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelana2Trapesium'])
    ->name('boq.mahaflat.pelana-2trapesium.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/pelana-3-arah', [MahaflatKombinasiController::class, 'pelana3Arah'])
    ->name('boq.mahaflat.pelana-3-arah');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-3-arah/hitung', [MahaflatKombinasiController::class, 'hitungPelana3Arah'])
    ->name('boq.mahaflat.pelana-3-arah.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-3-arah/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelana3Arah'])
    ->name('boq.mahaflat.pelana-3-arah.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/pelana-x', [MahaflatKombinasiController::class, 'pelanaX'])
    ->name('boq.mahaflat.pelana-x');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-x/hitung', [MahaflatKombinasiController::class, 'hitungPelanaX'])
    ->name('boq.mahaflat.pelana-x.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-x/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelanaX'])
    ->name('boq.mahaflat.pelana-x.export-pdf');

     Route::get('/boq/mahaflat/atap-kombinasi/pelana-dinding', [MahaflatKombinasiController::class, 'pelanaDinding'])
    ->name('boq.mahaflat.pelana-x');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-dinding/hitung', [MahaflatKombinasiController::class, 'hitungPelanaDinding'])
    ->name('boq.mahaflat.pelana-dinding.hitung');
Route::post('/boq/mahaflat/atap-kombinasi/pelana-dinding/export-pdf', [MahaflatKombinasiController::class, 'exportPdfPelanaDinding'])
    ->name('boq.mahaflat.pelana-dinding.export-pdf');

    // FLEXIROOF
        Route::prefix('boq/flexiroof')->group(function () {
    Route::get('/{model}', [FlexiroofController::class, 'index'])->name('boq.flexiroof.index');
    Route::post('/{model}/hitung', [FlexiroofController::class, 'hitung'])->name('boq.flexiroof.hitung');
    Route::post('/{model}/export-pdf', [FlexiroofController::class, 'exportPdf'])->name('boq.flexiroof.export-pdf');
});

Route::get('/boq/flexiroof/atap-kombinasi/gergaji', [FlexiroofKombinasiController::class, 'gergaji'])
    ->name('boq.flexiroof.gergaji');
Route::post('/boq/flexiroof/atap-kombinasi/gergaji/hitung', [FlexiroofKombinasiController::class, 'hitungGergaji'])
    ->name('boq.flexiroof.gergaji.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/gergaji/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfGergaji'])
    ->name('boq.flexiroof.gergaji.export-pdf');

        Route::get('/boq/flexiroof/atap-kombinasi/lengkung-2-sisi', [FlexiroofKombinasiController::class, 'lengkung2Sisi'])
    ->name('boq.flexiroof.lengkung-2-sisi');
Route::post('/boq/flexiroof/atap-kombinasi/lengkung-2-sisi/hitung', [FlexiroofKombinasiController::class, 'hitungLengkung2Sisi'])
    ->name('boq.flexiroof.lengkung-2-sisi.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/lengkung-2-sisi/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfLengkung2Sisi'])
    ->name('boq.flexiroof.lengkung-2-sisi.export-pdf');

        Route::get('/boq/flexiroof/atap-kombinasi/limas-pelana', [FlexiroofKombinasiController::class, 'limasPelana'])
    ->name('boq.flexiroof.limas-pelana');
Route::post('/boq/flexiroof/atap-kombinasi/limas-pelana/hitung', [FlexiroofKombinasiController::class, 'hitungLimasPelana'])
    ->name('boq.flexiroof.limas-pelana.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/limas-pelana/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfLimasPelana'])
    ->name('boq.flexiroof.limas-pelana.export-pdf');

            Route::get('/boq/flexiroof/atap-kombinasi/limasan-limasan', [FlexiroofKombinasiController::class, 'limasanLimasan'])
    ->name('boq.flexiroof.limasan-limasan');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-limasan/hitung', [FlexiroofKombinasiController::class, 'hitungLimasanLimasan'])
    ->name('boq.flexiroof.limasan-limasan.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-limasan/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfLimasanLimasan'])
    ->name('boq.flexiroof.limasan-limasan.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/limasan-trapesium', [FlexiroofKombinasiController::class, 'limasanTrapesium'])
    ->name('boq.flexiroof.limasan-trapesium');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-trapesium/hitung', [FlexiroofKombinasiController::class, 'hitungLimasanTrapesium'])
    ->name('boq.flexiroof.limasan-trapesium.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-trapesium/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfLimasanTrapesium'])
    ->name('boq.flexiroof.limasan-trapesium.export-pdf');

        Route::get('/boq/flexiroof/atap-kombinasi/limasan-x', [FlexiroofKombinasiController::class, 'limasanX'])
    ->name('boq.flexiroof.limasan-x');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-x/hitung', [FlexiroofKombinasiController::class, 'hitungLimasanX'])
    ->name('boq.flexiroof.limasan-x.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/limasan-x/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfLimasanX'])
    ->name('boq.flexiroof.limasan-x.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/pelana-2-kemiringan', [FlexiroofKombinasiController::class, 'pelana2Kemiringan'])
    ->name('boq.flexiroof.pelana-2-kemiringan');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2-kemiringan/hitung', [FlexiroofKombinasiController::class, 'hitungPelana2Kemiringan'])
    ->name('boq.flexiroof.pelana-2-kemiringan.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2-kemiringan/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelana2Kemiringan'])
    ->name('boq.flexiroof.pelana-2-kemiringan.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/pelana-2-sisi', [FlexiroofKombinasiController::class, 'pelana2Sisi'])
    ->name('boq.flexiroof.pelana-2-sisi');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2-sisi/hitung', [FlexiroofKombinasiController::class, 'hitungPelana2Sisi'])
    ->name('boq.flexiroof.pelana-2-sisi.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2-sisi/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelana2Sisi'])
    ->name('boq.flexiroof.pelana-2-sisi.export-pdf');

        Route::get('/boq/flexiroof/atap-kombinasi/pelana-2trapesium', [FlexiroofKombinasiController::class, 'pelana2Trapesium'])
    ->name('boq.flexiroof.pelana-2trapesium');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2trapesium/hitung', [FlexiroofKombinasiController::class, 'hitungPelana2Trapesium'])
    ->name('boq.flexiroof.pelana-2trapesium.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-2trapesium/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelana2Trapesium'])
    ->name('boq.flexiroof.pelana-2trapesium.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/pelana-3-arah', [FlexiroofKombinasiController::class, 'pelana3Arah'])
    ->name('boq.flexiroof.pelana-3-arah');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-3-arah/hitung', [FlexiroofKombinasiController::class, 'hitungPelana3Arah'])
    ->name('boq.flexiroof.pelana-3-arah.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-3-arah/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelana3Arah'])
    ->name('boq.flexiroof.pelana-3-arah.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/pelana-dinding', [FlexiroofKombinasiController::class, 'pelanaDinding'])
    ->name('boq.flexiroof.pelana-x');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-dinding/hitung', [FlexiroofKombinasiController::class, 'hitungPelanaDinding'])
    ->name('boq.flexiroof.pelana-dinding.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-dinding/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelanaDinding'])
    ->name('boq.flexiroof.pelana-dinding.export-pdf');

         Route::get('/boq/flexiroof/atap-kombinasi/pelana-x', [FlexiroofKombinasiController::class, 'pelanaX'])
    ->name('boq.flexiroof.pelana-x');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-x/hitung', [FlexiroofKombinasiController::class, 'hitungPelanaX'])
    ->name('boq.flexiroof.pelana-x.hitung');
Route::post('/boq/flexiroof/atap-kombinasi/pelana-x/export-pdf', [FlexiroofKombinasiController::class, 'exportPdfPelanaX'])
    ->name('boq.flexiroof.pelana-x.export-pdf');

    // ECOROOF
            Route::prefix('boq/ecoroof')->group(function () {
    Route::get('/{model}', [EcoroofController::class, 'index'])->name('boq.ecoroof.index');
    Route::post('/{model}/hitung', [EcoroofController::class, 'hitung'])->name('boq.ecoroof.hitung');
    Route::post('/{model}/export-pdf', [EcoroofController::class, 'exportPdf'])->name('boq.ecoroof.export-pdf');
});

Route::get('/boq/ecoroof/atap-kombinasi/gergaji', [EcoroofKombinasiController::class, 'gergaji'])
    ->name('boq.ecoroof.gergaji');
Route::post('/boq/ecoroof/atap-kombinasi/gergaji/hitung', [EcoroofKombinasiController::class, 'hitungGergaji'])
    ->name('boq.ecoroof.gergaji.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/gergaji/export-pdf', [EcoroofKombinasiController::class, 'exportPdfGergaji'])
    ->name('boq.ecoroof.gergaji.export-pdf');

    
        Route::get('/boq/ecoroof/atap-kombinasi/lengkung-2-sisi', [EcoroofKombinasiController::class, 'lengkung2Sisi'])
    ->name('boq.ecoroof.lengkung-2-sisi');
Route::post('/boq/ecoroof/atap-kombinasi/lengkung-2-sisi/hitung', [EcoroofKombinasiController::class, 'hitungLengkung2Sisi'])
    ->name('boq.ecoroof.lengkung-2-sisi.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/lengkung-2-sisi/export-pdf', [EcoroofKombinasiController::class, 'exportPdfLengkung2Sisi'])
    ->name('boq.ecoroof.lengkung-2-sisi.export-pdf');

            Route::get('/boq/ecoroof/atap-kombinasi/limas-pelana', [EcoroofKombinasiController::class, 'limasPelana'])
    ->name('boq.ecoroof.limas-pelana');
Route::post('/boq/ecoroof/atap-kombinasi/limas-pelana/hitung', [EcoroofKombinasiController::class, 'hitungLimasPelana'])
    ->name('boq.ecoroof.limas-pelana.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/limas-pelana/export-pdf', [EcoroofKombinasiController::class, 'exportPdfLimasPelana'])
    ->name('boq.ecoroof.limas-pelana.export-pdf');

                Route::get('/boq/ecoroof/atap-kombinasi/limasan-limasan', [EcoroofKombinasiController::class, 'limasanLimasan'])
    ->name('boq.ecoroof.limasan-limasan');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-limasan/hitung', [EcoroofKombinasiController::class, 'hitungLimasanLimasan'])
    ->name('boq.ecoroof.limasan-limasan.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-limasan/export-pdf', [EcoroofKombinasiController::class, 'exportPdfLimasanLimasan'])
    ->name('boq.ecoroof.limasan-limasan.export-pdf');

         Route::get('/boq/ecoroof/atap-kombinasi/limasan-trapesium', [EcoroofKombinasiController::class, 'limasanTrapesium'])
    ->name('boq.ecoroof.limasan-trapesium');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-trapesium/hitung', [EcoroofKombinasiController::class, 'hitungLimasanTrapesium'])
    ->name('boq.ecoroof.limasan-trapesium.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-trapesium/export-pdf', [EcoroofKombinasiController::class, 'exportPdfLimasanTrapesium'])
    ->name('boq.ecoroof.limasan-trapesium.export-pdf');

            Route::get('/boq/ecoroof/atap-kombinasi/limasan-x', [EcoroofKombinasiController::class, 'limasanX'])
    ->name('boq.ecoroof.limasan-x');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-x/hitung', [EcoroofKombinasiController::class, 'hitungLimasanX'])
    ->name('boq.ecoroof.limasan-x.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/limasan-x/export-pdf', [EcoroofKombinasiController::class, 'exportPdfLimasanX'])
    ->name('boq.ecoroof.limasan-x.export-pdf');

             Route::get('/boq/ecoroof/atap-kombinasi/pelana-2-kemiringan', [EcoroofKombinasiController::class, 'pelana2Kemiringan'])
    ->name('boq.ecoroof.pelana-2-kemiringan');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2-kemiringan/hitung', [EcoroofKombinasiController::class, 'hitungPelana2Kemiringan'])
    ->name('boq.ecoroof.pelana-2-kemiringan.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2-kemiringan/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelana2Kemiringan'])
    ->name('boq.ecoroof.pelana-2-kemiringan.export-pdf');

            Route::get('/boq/ecoroof/atap-kombinasi/pelana-2-sisi', [EcoroofKombinasiController::class, 'pelana2Sisi'])
    ->name('boq.ecoroof.pelana-2-sisi');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2-sisi/hitung', [EcoroofKombinasiController::class, 'hitungPelana2Sisi'])
    ->name('boq.ecoroof.pelana-2-sisi.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2-sisi/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelana2Sisi'])
    ->name('boq.ecoroof.pelana-2-sisi.export-pdf');

        Route::get('/boq/ecoroof/atap-kombinasi/pelana-2trapesium', [EcoroofKombinasiController::class, 'pelana2Trapesium'])
    ->name('boq.ecoroof.pelana-2trapesium');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2trapesium/hitung', [EcoroofKombinasiController::class, 'hitungPelana2Trapesium'])
    ->name('boq.ecoroof.pelana-2trapesium.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-2trapesium/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelana2Trapesium'])
    ->name('boq.ecoroof.pelana-2trapesium.export-pdf');

         Route::get('/boq/ecoroof/atap-kombinasi/pelana-3-arah', [EcoroofKombinasiController::class, 'pelana3Arah'])
    ->name('boq.ecoroof.pelana-3-arah');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-3-arah/hitung', [EcoroofKombinasiController::class, 'hitungPelana3Arah'])
    ->name('boq.ecoroof.pelana-3-arah.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-3-arah/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelana3Arah'])
    ->name('boq.ecoroof.pelana-3-arah.export-pdf');

         Route::get('/boq/ecoroof/atap-kombinasi/pelana-dinding', [EcoroofKombinasiController::class, 'pelanaDinding'])
    ->name('boq.ecoroof.pelana-x');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-dinding/hitung', [EcoroofKombinasiController::class, 'hitungPelanaDinding'])
    ->name('boq.ecoroof.pelana-dinding.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-dinding/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelanaDinding'])
    ->name('boq.ecoroof.pelana-dinding.export-pdf');

         Route::get('/boq/ecoroof/atap-kombinasi/pelana-x', [EcoroofKombinasiController::class, 'pelanaX'])
    ->name('boq.ecoroof.pelana-x');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-x/hitung', [EcoroofKombinasiController::class, 'hitungPelanaX'])
    ->name('boq.ecoroof.pelana-x.hitung');
Route::post('/boq/ecoroof/atap-kombinasi/pelana-x/export-pdf', [EcoroofKombinasiController::class, 'exportPdfPelanaX'])
    ->name('boq.ecoroof.pelana-x.export-pdf');

// ==================== JENDELA ====================

// Halaman utama jendela
Route::get('/jendela', [JendelaController::class, 'index'])->name('jendela.index');

// AJAX hitung
Route::post('/jendela/hitung', [JendelaController::class, 'hitung'])->name('jendela.hitung');

// Export PDF
Route::post('/jendela/export-pdf', [JendelaController::class, 'exportPdf'])->name('jendela.export-pdf');

// BOQ Jendela
Route::post('/boq/jendela/mati1/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati1'])->name('boq.jendela.mati1.export-pdf');
Route::post('/boq/jendela/mati2/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati2'])->name('boq.jendela.mati2.export-pdf');
Route::post('/boq/jendela/mati3/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati3'])->name('boq.jendela.mati3.export-pdf');
Route::post('/boq/jendela/bouven1/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfBouven1'])->name('boq.jendela.bouven1.export-pdf');
Route::post('/boq/jendela/bouven2/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfBouven2'])->name('boq.jendela.bouven2.export-pdf');
Route::post('/boq/jendela/bouven3/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfBouven3'])->name('boq.jendela.bouven3.export-pdf');
Route::post('/boq/jendela/bouven4/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfBouven4'])->name('boq.jendela.bouven4.export-pdf');
Route::post('/boq/jendela/bouvensilang/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfBouvenSilang'])->name('boq.jendela.bouvensilang.export-pdf');
Route::post('/boq/jendela/mati1mullion/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati1Mullion'])->name('boq.jendela.mati1mullion.export-pdf');
Route::post('/boq/jendela/mati2mullion/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati2Mullion'])->name('boq.jendela.mati2mullion.export-pdf');
Route::post('/boq/jendela/mati3mullion/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati3Mullion'])->name('boq.jendela.mati3mullion.export-pdf');
Route::post('/boq/jendela/swing1/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing1'])->name('boq.jendela.swing1.export-pdf');
Route::post('/boq/jendela/swing1mullionvertikalhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing1mullionvertikalhorizontal'])->name('boq.jendela.swing1mullionvertikalhorizontal.export-pdf');
Route::post('/boq/jendela/swing1mullionvertikal2mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing1mullionvertikal2mullionhorizontal'])->name('boq.jendela.swing1mullionvertikal2mullionhorizontal.export-pdf');
Route::post('/boq/jendela/swing1mullionvertikal3mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing1mullionvertikal3mullionhorizontal'])->name('boq.jendela.swing1mullionvertikal3mullionhorizontal.export-pdf');
Route::post('/boq/jendela/swing2mullionvertikal1mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing2mullionvertikal1mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal1mullionhorizontal.export-pdf');
Route::post('/boq/jendela/swing2mullionvertikal2mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing2mullionvertikal2mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal2mullionhorizontal.export-pdf');
Route::post('/boq/jendela/swing2mullionvertikal3mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing2mullionvertikal3mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal3mullionhorizontal.export-pdf');
Route::post('/boq/jendela/swing2/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSwing2'])->name('boq.jendela.swing2.export-pdf');
Route::post('/boq/jendela/jungkit1/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit1'])->name('boq.jendela.jungkit1.export-pdf');
Route::post('/boq/jendela/jungkit1mullionvertikal1mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit1mullionvertikal1mullionhorizontal'])->name('boq.jendela.jungkit1mullionvertikal1mullionhorizontal.export-pdf');
Route::post('/boq/jendela/jungkit1mullionvertikal2mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit1mullionvertikal2mullionhorizontal'])->name('boq.jendela.jungkit1mullionvertikal2mullionhorizontal.export-pdf');
Route::post('/boq/jendela/jungkit2mullionvertikal1mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit2mullionvertikal1mullionhorizontal'])->name('boq.jendela.jungkit2mullionvertikal1mullionhorizontal.export-pdf');
Route::post('/boq/jendela/jungkit2mullionvertikal3mullionhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit2mullionvertikal3mullionhorizontal'])->name('boq.jendela.jungkit2mullionvertikal3mullionhorizontal.export-pdf');
Route::post('/boq/jendela/jungkit2/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit2'])->name('boq.jendela.jungkit2.export-pdf');
Route::post('/boq/jendela/jungkit1mullion/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit1mullion'])->name('boq.jendela.jungkit1mullion.export-pdf');
Route::post('/boq/jendela/jungkit2mullion/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit2mullion'])->name('boq.jendela.jungkit2mullion.export-pdf');
Route::post('/boq/jendela/jungkit4bouven/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit4bouven'])->name('boq.jendela.jungkit4bouven.export-pdf');
Route::post('/boq/jendela/jungkit2bouven/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit2bouven'])->name('boq.jendela.jungkit2bouven.export-pdf');
Route::post('/boq/jendela/jungkit12bouven/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfJungkit12bouven'])->name('boq.jendela.jungkit12bouven.export-pdf');
Route::post('/boq/jendela/sliding/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfSliding'])->name('boq.jendela.sliding.export-pdf');
Route::post('/boq/jendela/mati1mullion2horizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati1Mullion2Horizontal'])->name('boq.jendela.mati1mullion2horizontal.export-pdf');
Route::post('/boq/jendela/mati3mullion2vertikalmullion1horizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati3Mullion2vertikalmullion1horizontal'])->name('boq.jendela.mati3mullion2vertikalmullion1horizontal.export-pdf');
Route::post('/boq/jendela/mati1mullion1vertikalhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati1Mullion1VertikalHorizontal'])->name('boq.jendela.mati1mullion1vertikalhorizontal.export-pdf');
Route::post('/boq/jendela/mati2mullion2vertikalhorizontal/export-pdf', [App\Http\Controllers\JendelaController::class, 'exportPdfMati2Mullion2VertikalHorizontal'])->name('boq.jendela.mati2mullion2vertikalhorizontal.export-pdf');

// Route untuk hitung Jendela Mati 1 Kaca
Route::post('/boq/jendela/mati1/hitung', [JendelaController::class, 'hitungMati1'])->name('boq.jendela.mati1.hitung');
Route::post('/boq/jendela/mati2/hitung', [JendelaController::class, 'hitungMati2'])->name('boq.jendela.mati2.hitung');
Route::post('/boq/jendela/mati3/hitung', [JendelaController::class, 'hitungMati3'])->name('boq.jendela.mati3.hitung');
Route::post('/boq/jendela/bouven1/hitung', [JendelaController::class, 'hitungBouven1'])->name('boq.jendela.bouven1.hitung');
Route::post('/boq/jendela/bouven2/hitung', [JendelaController::class, 'hitungBouven2'])->name('boq.jendela.bouven2.hitung');
Route::post('/boq/jendela/bouven3/hitung', [JendelaController::class, 'hitungBouven3'])->name('boq.jendela.bouven3.hitung');
Route::post('/boq/jendela/bouven4/hitung', [JendelaController::class, 'hitungBouven4'])->name('boq.jendela.bouven4.hitung');
Route::post('/boq/jendela/bouvensilang/hitung', [JendelaController::class, 'hitungBouvenSilang'])->name('boq.jendela.bouvensilang.hitung');
Route::post('/boq/jendela/mati1mullion/hitung', [JendelaController::class, 'hitungMati1Mullion'])->name('boq.jendela.mati1mullion.hitung');
Route::post('/boq/jendela/mati2mullion/hitung', [JendelaController::class, 'hitungMati2Mullion'])->name('boq.jendela.mati2mullion.hitung');
Route::post('/boq/jendela/mati3mullion/hitung', [JendelaController::class, 'hitungMati3Mullion'])->name('boq.jendela.mati3mullion.hitung');
Route::post('/boq/jendela/swing1/hitung', [JendelaController::class, 'hitungSwing1'])->name('boq.jendela.swing1.hitung');
Route::post('/boq/jendela/swing1mullionvertikalhorizontal/hitung', [JendelaController::class, 'hitungSwing1mullionvertikalhorizontal'])->name('boq.jendela.swing1mullionvertikalhorizontal.hitung');
Route::post('/boq/jendela/swing1mullionvertikal2mullionhorizontal/hitung', [JendelaController::class, 'hitungSwing1mullionvertikal2mullionhorizontal'])->name('boq.jendela.swing1mullionvertikal2mullionhorizontal.hitung');
Route::post('/boq/jendela/swing1mullionvertikal3mullionhorizontal/hitung', [JendelaController::class, 'hitungSwing1mullionvertikal3mullionhorizontal'])->name('boq.jendela.swing1mullionvertikal3mullionhorizontal.hitung');
Route::post('/boq/jendela/swing2mullionvertikal1mullionhorizontal/hitung', [JendelaController::class, 'hitungSwing2mullionvertikal1mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal1mullionhorizontal.hitung');
Route::post('/boq/jendela/swing2mullionvertikal2mullionhorizontal/hitung', [JendelaController::class, 'hitungSwing2mullionvertikal2mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal2mullionhorizontal.hitung');
Route::post('/boq/jendela/swing2mullionvertikal3mullionhorizontal/hitung', [JendelaController::class, 'hitungSwing2mullionvertikal3mullionhorizontal'])->name('boq.jendela.swing2mullionvertikal3mullionhorizontal.hitung');
Route::post('/boq/jendela/swing2/hitung', [JendelaController::class, 'hitungSwing2'])->name('boq.jendela.swing2.hitung');
Route::post('/boq/jendela/jungkit1/hitung', [JendelaController::class, 'hitungJungkit1'])->name('boq.jendela.jungkit1.hitung');
Route::post('/boq/jendela/jungkit1mullionvertikal1mullionhorizontal/hitung', [JendelaController::class, 'hitungJungkit1mullionvertikal1mullionhorizontal'])->name('boq.jendela.jungkit1mullionvertikal1mullionhorizontal.hitung');
Route::post('/boq/jendela/jungkit1mullionvertikal2mullionhorizontal/hitung', [JendelaController::class, 'hitungJungkit1mullionvertikal2mullionhorizontal'])->name('boq.jendela.jungkit1mullionvertikal2mullionhorizontal.hitung');
Route::post('/boq/jendela/jungkit2mullionvertikal1mullionhorizontal/hitung', [JendelaController::class, 'hitungJungkit2mullionvertikal1mullionhorizontal'])->name('boq.jendela.jungkit2mullionvertikal1mullionhorizontal.hitung');
Route::post('/boq/jendela/jungkit2mullionvertikal3mullionhorizontal/hitung', [JendelaController::class, 'hitungJungkit2mullionvertikal3mullionhorizontal'])->name('boq.jendela.jungkit2mullionvertikal3mullionhorizontal.hitung');
Route::post('/boq/jendela/jungkit2/hitung', [JendelaController::class, 'hitungJungkit2'])->name('boq.jendela.jungkit2.hitung');
Route::post('/boq/jendela/jungkit1mullion/hitung', [JendelaController::class, 'hitungJungkit1mullion'])->name('boq.jendela.jungkit1mullion.hitung');
Route::post('/boq/jendela/jungkit2mullion/hitung', [JendelaController::class, 'hitungJungkit2mullion'])->name('boq.jendela.jungkit2mullion.hitung');
Route::post('/boq/jendela/jungkit4bouven/hitung', [JendelaController::class, 'hitungJungkit4bouven'])->name('boq.jendela.jungkit4bouven.hitung');
Route::post('/boq/jendela/jungkit2bouven/hitung', [JendelaController::class, 'hitungJungkit2bouven'])->name('boq.jendela.jungkit2bouven.hitung');
Route::post('/boq/jendela/jungkit12bouven/hitung', [JendelaController::class, 'hitungJungkit12bouven'])->name('boq.jendela.jungkit12bouven.hitung');
Route::post('/boq/jendela/sliding/hitung', [JendelaController::class, 'hitungSliding'])->name('boq.jendela.sliding.hitung');
Route::post('/boq/jendela/mati1mullion2horizontal/hitung', [JendelaController::class, 'hitungMati1Mullion2Horizontal'])->name('boq.jendela.mati1mullion2horizontal.hitung');
Route::post('/boq/jendela/mati3mullion2vertikalmullion1horizontal/hitung', [JendelaController::class, 'hitungMati3Mullion2vertikalmullion1horizontal'])->name('boq.jendela.mati3mullion2vertikalmullion1horizontal.hitung');
Route::post('/boq/jendela/mati1mullion1vertikalhorizontal/hitung', [JendelaController::class, 'hitungMati1Mullion1VertikalHorizontal'])->name('boq.jendela.mati1mullion1vertikalhorizontal.hitung');
Route::post('/boq/jendela/mati2mullion2vertikalhorizontal/hitung', [JendelaController::class, 'hitungMati2Mullion2VertikalHorizontal'])->name('boq.jendela.mati2mullion2vertikalhorizontal.hitung');

// BOQ PINTU
Route::get('/pintu', [PintuController::class, 'index'])->name('pintu.index');
Route::post('/boq/pintu/swing1/hitung', [PintuController::class, 'hitungSwing1'])->name('boq.pintu.swing1.hitung');
Route::post('/boq/pintu/swingDouble/hitung', [PintuController::class, 'hitungSwingDouble'])->name('boq.pintu.swingDouble.hitung');
Route::post('/boq/pintu/sliding/hitung', [PintuController::class, 'hitungSliding'])->name('boq.pintu.sliding.hitung');
Route::post('/boq/pintu/sliding1/hitung', [PintuController::class, 'hitungSliding1'])->name('boq.pintu.sliding1.hitung');
Route::post('/boq/pintu/sliding3track/hitung', [PintuController::class, 'hitungSliding3track'])->name('boq.pintu.sliding3track.hitung');
Route::post('/boq/pintu/sliding4/hitung', [PintuController::class, 'hitungSliding4'])->name('boq.pintu.sliding4.hitung');

// Export PDF PINTU
Route::post('/boq/pintu/swing1/export-pdf', [PintuController::class, 'exportPdfSwing1'])->name('boq.pintu.swing1.export-pdf');
Route::post('/boq/pintu/swingDouble/export-pdf', [PintuController::class, 'exportPdfSwingDouble'])->name('boq.pintu.swingDouble.export-pdf');
Route::post('/boq/pintu/sliding/export-pdf', [PintuController::class, 'exportPdfSliding'])->name('boq.pintu.sliding.export-pdf');
Route::post('/boq/pintu/sliding1/export-pdf', [PintuController::class, 'exportPdfSliding1'])->name('boq.pintu.sliding1.export-pdf');
Route::post('/boq/pintu/sliding3track/export-pdf', [PintuController::class, 'exportPdfSliding3track'])->name('boq.pintu.sliding3track.export-pdf');
Route::post('/boq/pintu/sliding4/export-pdf', [PintuController::class, 'exportPdfSliding4'])->name('boq.pintu.sliding4.export-pdf');

Route::get('/atap-kombinasi', [AtapKombinasiController::class, 'index'])->name('atap-kombinasi.index');
Route::post('/atap-kombinasi/hitung', [AtapKombinasiController::class, 'hitung'])->name('atap-kombinasi.hitung');


// BOQ
Route::post('/boq/store', [BoqController::class, 'storeBoq'])->name('boq.store');

// Get BOQ
Route::get('/boq', [BoqController::class, 'index'])->name('boq.index');
// Route::get('/boq/{id}', [BoqController::class, 'show'])->name('boq.show');


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