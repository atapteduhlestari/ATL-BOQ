<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductArea;
use App\Models\ProductAccessories;
use Illuminate\Support\Facades\Log;
use App\Models\Boq;
use App\Models\DetailBoq;

class PintuController extends Controller
{
    public function index()
    {
        return view('pintu.index');
    }

  public function hitungSwing1(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Swing 1 Daun) dari database
    $produkUtama = Product::find(209);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Swing 1 Daun tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 209)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = $satuanTerkecilSashVertikal * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = $satuanTerkecilGlazeVertikal * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN DOOR HARDWARE ===
    
    // 1. Door Engsel
    $doorEngselItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-engsel') {
            $doorEngselItem = $item;
            break;
        }
    }

    $totalDoorEngselQty = 0;
    if ($doorEngselItem) {
        $satuanTerkecil = (float)($doorEngselItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorEngselItem->qty = $qty;
        $totalDoorEngselQty += $qty;
    }

    // 2. Screw Door Hinge
    $screwDoorHingeItem = null;
    $screwDoorHingeQty = 0;

    if ($doorEngselItem) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'screw-door-hinge') {
                $screwDoorHingeItem = $item;
                break;
            }
        }

        $satuanTerkecilScrewDoorHinge = $screwDoorHingeItem ? (float)$screwDoorHingeItem->satuan_terkecil : 0;
        $screwDoorHingeQty = $totalDoorEngselQty * $satuanTerkecilScrewDoorHinge;
        if ($screwDoorHingeItem) {
            $screwDoorHingeItem->qty = $screwDoorHingeQty;
        }
    }

    // 3. Door Transmitter
    $doorTransmitterItem = null;
    $totalDoorTransmitterQty = 0;
    $ukuranTransmitter = 0;
    $ukuranTransmitterFinal = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-transmitter') {
            $doorTransmitterItem = $item;
            break;
        }
    }

    if ($doorTransmitterItem) {
        $ukuranTransmitter = $tinggi - 20;

        $transmitterMapping = [
            40 => 172,
            60 => 171,
            80 => 170,
            100 => 173,
            120 => 162,
            140 => 166,
            160 => 167,
            180 => 168,
            200 => 169,
        ];

        $ukuranTransmitterFinal = $ukuranTransmitter;
        if (!isset($transmitterMapping[$ukuranTransmitter])) {
            $availableSizes = array_keys($transmitterMapping);
            sort($availableSizes);
            
            $found = false;
            for ($i = 0; $i < count($availableSizes); $i++) {
                if ($availableSizes[$i] >= $ukuranTransmitter) {
                    $ukuranTransmitterFinal = $availableSizes[$i];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $ukuranTransmitterFinal = max($availableSizes);
            }
        }

        $transmitterId = $transmitterMapping[$ukuranTransmitterFinal] ?? null;

        if ($transmitterId) {
            for ($i = 0; $i < $aksesorisCount; $i++) {
                $item = $aksesoris[$i];
                if ($item->id == $transmitterId) {
                    $doorTransmitterItem = $item;
                    break;
                }
            }
        }

        if ($doorTransmitterItem) {
            $satuanTerkecil = (float)($doorTransmitterItem->satuan_terkecil ?? 0);
            $qty = $jumlah * $satuanTerkecil;
            $doorTransmitterItem->qty = $qty;
            $totalDoorTransmitterQty += $qty;
        }
    }

    // 4. Door Handle
    $doorHandleItem = null;
    $totalDoorHandleQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 1;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'door-engsel') {
            $qty = ($doorEngselItem && $doorEngselItem->id == $item->id) ? $totalDoorEngselQty : 0;
        } elseif ($areaSlug == 'screw-door-hinge') {
            $qty = $screwDoorHingeQty;
        } elseif ($areaSlug == 'door-transmitter') {
            $qty = ($doorTransmitterItem && $doorTransmitterItem->id == $item->id) ? $totalDoorTransmitterQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'door_engsel_item' => $doorEngselItem,
        'total_door_engsel_qty' => $totalDoorEngselQty,
        
        'screw_door_hinge_item' => $screwDoorHingeItem,
        'screw_door_hinge_qty' => $screwDoorHingeQty,
        
        'door_transmitter_item' => $doorTransmitterItem,
        'ukuran_transmitter' => $ukuranTransmitter,
        'ukuran_transmitter_final' => $ukuranTransmitterFinal,
        'total_door_transmitter_qty' => $totalDoorTransmitterQty,
        
        'door_handle_item' => $doorHandleItem,
        'total_door_handle_qty' => $totalDoorHandleQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-swing', $data);
}

public function exportPdfSwing1(Request $request)
{
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Swing 1 Daun';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 209)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = $satuanTerkecilSashVertikal * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;
    
    $variableGlazeVertikalA = $satuanTerkecilGlazeVertikal * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;
    
    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;
    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN DOOR HARDWARE ===
    
    // 1. Door Engsel
    $doorEngselItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-engsel') {
            $doorEngselItem = $item;
            break;
        }
    }

    $totalDoorEngselQty = 0;
    if ($doorEngselItem) {
        $satuanTerkecil = (float)($doorEngselItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorEngselItem->qty = $qty;
        $totalDoorEngselQty += $qty;
    }

    // 2. Screw Door Hinge
    $screwDoorHingeItem = null;
    $screwDoorHingeQty = 0;

    if ($doorEngselItem) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'screw-door-hinge') {
                $screwDoorHingeItem = $item;
                break;
            }
        }

        $satuanTerkecilScrewDoorHinge = $screwDoorHingeItem ? (float)$screwDoorHingeItem->satuan_terkecil : 0;
        $screwDoorHingeQty = $totalDoorEngselQty * $satuanTerkecilScrewDoorHinge;
        if ($screwDoorHingeItem) {
            $screwDoorHingeItem->qty = $screwDoorHingeQty;
        }
    }

    // 3. Door Transmitter
    $doorTransmitterItem = null;
    $totalDoorTransmitterQty = 0;
    $ukuranTransmitter = $tinggi - 20;
    $ukuranTransmitterFinal = $ukuranTransmitter;

    $transmitterMapping = [
        40 => 172,
        60 => 171,
        80 => 170,
        100 => 173,
        120 => 162,
        140 => 166,
        160 => 167,
        180 => 168,
        200 => 169,
    ];

    if (!isset($transmitterMapping[$ukuranTransmitter])) {
        $availableSizes = array_keys($transmitterMapping);
        sort($availableSizes);
        
        $found = false;
        for ($i = 0; $i < count($availableSizes); $i++) {
            if ($availableSizes[$i] >= $ukuranTransmitter) {
                $ukuranTransmitterFinal = $availableSizes[$i];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $ukuranTransmitterFinal = max($availableSizes);
        }
    }

    $transmitterId = $transmitterMapping[$ukuranTransmitterFinal] ?? null;

    if ($transmitterId) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->id == $transmitterId) {
                $doorTransmitterItem = $item;
                break;
            }
        }
    }

    if ($doorTransmitterItem) {
        $satuanTerkecil = (float)($doorTransmitterItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorTransmitterItem->qty = $qty;
        $totalDoorTransmitterQty += $qty;
    }

    // 4. Door Handle
    $doorHandleItem = null;
    $totalDoorHandleQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 1;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'door-engsel') {
            $qty = ($doorEngselItem && $doorEngselItem->id == $item->id) ? $totalDoorEngselQty : 0;
        } elseif ($areaSlug == 'screw-door-hinge') {
            $qty = $screwDoorHingeQty;
        } elseif ($areaSlug == 'door-transmitter') {
            $qty = ($doorTransmitterItem && $doorTransmitterItem->id == $item->id) ? $totalDoorTransmitterQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'door-')) {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SWING 1:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Swing 1: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'total_door_engsel_qty' => $totalDoorEngselQty,
        'screw_door_hinge_qty' => $screwDoorHingeQty,
        'door_transmitter_item' => $doorTransmitterItem,
        'ukuran_transmitter' => $ukuranTransmitter,
        'ukuran_transmitter_final' => $ukuranTransmitterFinal,
        'total_door_transmitter_qty' => $totalDoorTransmitterQty,
        'total_door_handle_qty' => $totalDoorHandleQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
    ];

    // IKUTIN CONTOH - RETURN VIEW
    return view('boq.pintu.pdf-pintu-swing', compact('data'));
}

 public function hitungSwingDouble(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Swing Double)
    $produkUtama = Product::find(224);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Swing Double tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 224)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 4 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 4 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN DOOR HARDWARE ===
    
    // 1. Door Engsel
    $doorEngselItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-engsel') {
            $doorEngselItem = $item;
            break;
        }
    }

    $totalDoorEngselQty = 0;
    if ($doorEngselItem) {
        $satuanTerkecil = (float)($doorEngselItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $doorEngselItem->qty = $qty;
        $totalDoorEngselQty += $qty;
    }

    // 2. Screw Door Hinge
    $screwDoorHingeItem = null;
    $screwDoorHingeQty = 0;

    if ($doorEngselItem) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'screw-door-hinge') {
                $screwDoorHingeItem = $item;
                break;
            }
        }

        $satuanTerkecilScrewDoorHinge = $screwDoorHingeItem ? (float)$screwDoorHingeItem->satuan_terkecil : 0;
        $screwDoorHingeQty = $totalDoorEngselQty * $satuanTerkecilScrewDoorHinge;
        if ($screwDoorHingeItem) {
            $screwDoorHingeItem->qty = $screwDoorHingeQty;
        }
    }

    // 3. Door Transmitter
    $doorTransmitterItem = null;
    $totalDoorTransmitterQty = 0;
    $ukuranTransmitter = 0;
    $ukuranTransmitterFinal = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-transmitter') {
            $doorTransmitterItem = $item;
            break;
        }
    }

    if ($doorTransmitterItem) {
        $ukuranTransmitter = $tinggi - 20;

        $transmitterMapping = [
            40 => 172,
            60 => 171,
            80 => 170,
            100 => 173,
            120 => 162,
            140 => 166,
            160 => 167,
            180 => 168,
            200 => 169,
        ];

        $ukuranTransmitterFinal = $ukuranTransmitter;
        if (!isset($transmitterMapping[$ukuranTransmitter])) {
            $availableSizes = array_keys($transmitterMapping);
            sort($availableSizes);
            
            $found = false;
            for ($i = 0; $i < count($availableSizes); $i++) {
                if ($availableSizes[$i] >= $ukuranTransmitter) {
                    $ukuranTransmitterFinal = $availableSizes[$i];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $ukuranTransmitterFinal = max($availableSizes);
            }
        }

        $transmitterId = $transmitterMapping[$ukuranTransmitterFinal] ?? null;

        if ($transmitterId) {
            for ($i = 0; $i < $aksesorisCount; $i++) {
                $item = $aksesoris[$i];
                if ($item->id == $transmitterId) {
                    $doorTransmitterItem = $item;
                    break;
                }
            }
        }

        if ($doorTransmitterItem) {
            $satuanTerkecil = (float)($doorTransmitterItem->satuan_terkecil ?? 0);
            $qty = $jumlah * $satuanTerkecil;
            $doorTransmitterItem->qty = $qty;
            $totalDoorTransmitterQty += $qty;
        }
    }

    // 4. Door Handle
    $doorHandleItem = null;
    $totalDoorHandleQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // 5. Flush Bolt
    $flushBoltItems = [];
    $totalFlushBoltQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'flush-bolt') {
            $flushBoltItems[] = $item;
        }
    }

    foreach ($flushBoltItems as $item) {
        $satuanTerkecil = (float)($item->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $item->qty = $qty;
        $totalFlushBoltQty += $qty;
    }

    // 6. Screw Flush Bolt
    $screwFlushBoltItem = null;
    $screwFlushBoltQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-flush-bolt') {
            $screwFlushBoltItem = $item;
            break;
        }
    }

    if ($screwFlushBoltItem) {
        $satuanTerkecilScrewFlushBolt = (float)($screwFlushBoltItem->satuan_terkecil ?? 0);
        $screwFlushBoltQty = $totalFlushBoltQty * $satuanTerkecilScrewFlushBolt;
        $screwFlushBoltItem->qty = $screwFlushBoltQty;
    }

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'door-engsel') {
            $qty = ($doorEngselItem && $doorEngselItem->id == $item->id) ? $totalDoorEngselQty : 0;
        } elseif ($areaSlug == 'screw-door-hinge') {
            $qty = $screwDoorHingeQty;
        } elseif ($areaSlug == 'door-transmitter') {
            $qty = ($doorTransmitterItem && $doorTransmitterItem->id == $item->id) ? $totalDoorTransmitterQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } elseif ($areaSlug == 'flush-bolt') {
            $qty = $item->qty ?? 0;
        } elseif ($areaSlug == 'screw-flush-bolt') {
            $qty = ($screwFlushBoltItem && $screwFlushBoltItem->id == $item->id) ? $screwFlushBoltQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'door_engsel_item' => $doorEngselItem,
        'total_door_engsel_qty' => $totalDoorEngselQty,
        
        'screw_door_hinge_item' => $screwDoorHingeItem,
        'screw_door_hinge_qty' => $screwDoorHingeQty,
        
        'door_transmitter_item' => $doorTransmitterItem,
        'ukuran_transmitter' => $ukuranTransmitter,
        'ukuran_transmitter_final' => $ukuranTransmitterFinal,
        'total_door_transmitter_qty' => $totalDoorTransmitterQty,
        
        'door_handle_item' => $doorHandleItem,
        'total_door_handle_qty' => $totalDoorHandleQty,
        
        'flush_bolt_items' => $flushBoltItems,
        'total_flush_bolt_qty' => $totalFlushBoltQty,
        
        'screw_flush_bolt_item' => $screwFlushBoltItem,
        'screw_flush_bolt_qty' => $screwFlushBoltQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-swing-double', $data);
}

public function exportPdfSwingDouble(Request $request)
{
    // Ambil data dari form
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Swing Double';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 224)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 4 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 4 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN DOOR HARDWARE ===
    
    // 1. Door Engsel
    $doorEngselItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-engsel') {
            $doorEngselItem = $item;
            break;
        }
    }

    $totalDoorEngselQty = 0;
    if ($doorEngselItem) {
        $satuanTerkecil = (float)($doorEngselItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $doorEngselItem->qty = $qty;
        $totalDoorEngselQty += $qty;
    }

    // 2. Screw Door Hinge
    $screwDoorHingeItem = null;
    $screwDoorHingeQty = 0;

    if ($doorEngselItem) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'screw-door-hinge') {
                $screwDoorHingeItem = $item;
                break;
            }
        }

        $satuanTerkecilScrewDoorHinge = $screwDoorHingeItem ? (float)$screwDoorHingeItem->satuan_terkecil : 0;
        $screwDoorHingeQty = $totalDoorEngselQty * $satuanTerkecilScrewDoorHinge;
        if ($screwDoorHingeItem) {
            $screwDoorHingeItem->qty = $screwDoorHingeQty;
        }
    }

    // 3. Door Transmitter
    $doorTransmitterItem = null;
    $totalDoorTransmitterQty = 0;
    $ukuranTransmitter = 0;
    $ukuranTransmitterFinal = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-transmitter') {
            $doorTransmitterItem = $item;
            break;
        }
    }

    if ($doorTransmitterItem) {
        $ukuranTransmitter = $tinggi - 20;

        $transmitterMapping = [
            40 => 172,
            60 => 171,
            80 => 170,
            100 => 173,
            120 => 162,
            140 => 166,
            160 => 167,
            180 => 168,
            200 => 169,
        ];

        $ukuranTransmitterFinal = $ukuranTransmitter;
        if (!isset($transmitterMapping[$ukuranTransmitter])) {
            $availableSizes = array_keys($transmitterMapping);
            sort($availableSizes);
            
            $found = false;
            for ($i = 0; $i < count($availableSizes); $i++) {
                if ($availableSizes[$i] >= $ukuranTransmitter) {
                    $ukuranTransmitterFinal = $availableSizes[$i];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $ukuranTransmitterFinal = max($availableSizes);
            }
        }

        $transmitterId = $transmitterMapping[$ukuranTransmitterFinal] ?? null;

        if ($transmitterId) {
            for ($i = 0; $i < $aksesorisCount; $i++) {
                $item = $aksesoris[$i];
                if ($item->id == $transmitterId) {
                    $doorTransmitterItem = $item;
                    break;
                }
            }
        }

        if ($doorTransmitterItem) {
            $satuanTerkecil = (float)($doorTransmitterItem->satuan_terkecil ?? 0);
            $qty = $jumlah * $satuanTerkecil;
            $doorTransmitterItem->qty = $qty;
            $totalDoorTransmitterQty += $qty;
        }
    }

    // 4. Door Handle
    $doorHandleItem = null;
    $totalDoorHandleQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // 5. Flush Bolt
    $flushBoltItems = [];
    $totalFlushBoltQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'flush-bolt') {
            $flushBoltItems[] = $item;
        }
    }

    foreach ($flushBoltItems as $item) {
        $satuanTerkecil = (float)($item->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $item->qty = $qty;
        $totalFlushBoltQty += $qty;
    }

    // 6. Screw Flush Bolt
    $screwFlushBoltItem = null;
    $screwFlushBoltQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-flush-bolt') {
            $screwFlushBoltItem = $item;
            break;
        }
    }

    if ($screwFlushBoltItem) {
        $satuanTerkecilScrewFlushBolt = (float)($screwFlushBoltItem->satuan_terkecil ?? 0);
        $screwFlushBoltQty = $totalFlushBoltQty * $satuanTerkecilScrewFlushBolt;
        $screwFlushBoltItem->qty = $screwFlushBoltQty;
    }

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'door-engsel') {
            $qty = ($doorEngselItem && $doorEngselItem->id == $item->id) ? $totalDoorEngselQty : 0;
        } elseif ($areaSlug == 'screw-door-hinge') {
            $qty = $screwDoorHingeQty;
        } elseif ($areaSlug == 'door-transmitter') {
            $qty = ($doorTransmitterItem && $doorTransmitterItem->id == $item->id) ? $totalDoorTransmitterQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } elseif ($areaSlug == 'flush-bolt') {
            $qty = $item->qty ?? 0;
        } elseif ($areaSlug == 'screw-flush-bolt') {
            $qty = ($screwFlushBoltItem && $screwFlushBoltItem->id == $item->id) ? $screwFlushBoltQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'door-') || str_starts_with($areaSlug, 'window-hardware-') || $areaSlug == 'flush-bolt') {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SWING DOUBLE:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Swing Double: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'total_door_engsel_qty' => $totalDoorEngselQty,
        'screw_door_hinge_qty' => $screwDoorHingeQty,
        'door_transmitter_item' => $doorTransmitterItem,
        'ukuran_transmitter' => $ukuranTransmitter,
        'ukuran_transmitter_final' => $ukuranTransmitterFinal,
        'total_door_transmitter_qty' => $totalDoorTransmitterQty,
        'total_door_handle_qty' => $totalDoorHandleQty,
        'flush_bolt_items' => $flushBoltItems,
        'total_flush_bolt_qty' => $totalFlushBoltQty,
        'screw_flush_bolt_item' => $screwFlushBoltItem,
        'screw_flush_bolt_qty' => $screwFlushBoltQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
        'decoration_bar_vertikal' => $decorationBarVertikalInput,
        'decoration_bar_horizontal' => $decorationBarHorizontalInput,
    ];

    // IKUTIN CONTOH - RETURN VIEW (bukan download)
    return view('boq.pintu.pdf-pintu-swing-double', compact('data'));
}

public function hitungSliding(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Sliding)
    $produkUtama = Product::find(228);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Sliding tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 228)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 4 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 4 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = $satuanTerkecilTrack * $jumlah;
        $variableTrackB = $variableTrackA * $lebar;
        $batangTrack = $variableTrackB / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = $variableMohairA * ($tinggi + $lebar);
        $batangMohair = $variableMohairB / 580;
        $mohairQty = ceil($batangMohair * 10) / 10;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE ROLLER ===
    $rollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-roller') {
            $rollerItem = $item;
            break;
        }
    }

    $totalRollerQty = 0;
    if ($rollerItem) {
        $satuanTerkecil = (float)($rollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rollerItem->qty = $qty;
        $totalRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE LIFTING ===
    $liftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $liftingItem = $item;
            break;
        }
    }

    $totalLiftingQty = 0;
    if ($liftingItem) {
        $satuanTerkecil = (float)($liftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $liftingItem->qty = $qty;
        $totalLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN SLIDING STOPPER ===
    $stopperItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'sliding-stopper') {
            $stopperItem = $item;
            break;
        }
    }

    $totalStopperQty = 0;
    if ($stopperItem) {
        $satuanTerkecil = (float)($stopperItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $stopperItem->qty = $qty;
        $totalStopperQty += $qty;
    }

    // === PERHITUNGAN SCREW ROLLER ===
    $screwRollerItem = null;
    $screwRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-roller') {
            $screwRollerItem = $item;
            break;
        }
    }

    if ($screwRollerItem) {
        $satuanTerkecilScrewRoller = (float)($screwRollerItem->satuan_terkecil ?? 0);
        // Qty screw roller = total roller qty x satuan terkecil
        $screwRollerQty = $totalRollerQty * $satuanTerkecilScrewRoller;
        $screwRollerItem->qty = $screwRollerQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } elseif ($areaSlug == 'window-hardware-roller') {
            $qty = ($rollerItem && $rollerItem->id == $item->id) ? $totalRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($liftingItem && $liftingItem->id == $item->id) ? $totalLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'sliding-stopper') {
            $qty = ($stopperItem && $stopperItem->id == $item->id) ? $totalStopperQty : 0;
        } elseif ($areaSlug == 'screw-roller') {
            $qty = ($screwRollerItem && $screwRollerItem->id == $item->id) ? $screwRollerQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'aluminium_track_item' => $aluminiumTrackItem,
        'aluminium_track_qty' => $aluminiumTrackQty,
        
        'mohair_item' => $mohairItem,
        'mohair_qty' => $mohairQty,
        
        'handle_item' => $handleItem,
        'total_handle_qty' => $totalHandleQty,
        
        'roller_item' => $rollerItem,
        'total_roller_qty' => $totalRollerQty,
        
        'lifting_item' => $liftingItem,
        'total_lifting_qty' => $totalLiftingQty,
        
        'strike_item' => $strikeItem,
        'total_strike_qty' => $totalStrikeQty,
        
        'stopper_item' => $stopperItem,
        'total_stopper_qty' => $totalStopperQty,
        
        'screw_roller_item' => $screwRollerItem,
        'screw_roller_qty' => $screwRollerQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-sliding', $data);
}
public function exportPdfSliding(Request $request)
{
    // Ambil data dari form
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Sliding';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 228)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 4 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 4 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $lebar;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = $satuanTerkecilTrack * $jumlah;
        $variableTrackB = $variableTrackA * $lebar;
        $batangTrack = $variableTrackB / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = $variableMohairA * ($tinggi + $lebar);
        $batangMohair = $variableMohairB / 580;
        $mohairQty = ceil($batangMohair * 10) / 10;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE ROLLER ===
    $rollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-roller') {
            $rollerItem = $item;
            break;
        }
    }

    $totalRollerQty = 0;
    if ($rollerItem) {
        $satuanTerkecil = (float)($rollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rollerItem->qty = $qty;
        $totalRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE LIFTING ===
    $liftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $liftingItem = $item;
            break;
        }
    }

    $totalLiftingQty = 0;
    if ($liftingItem) {
        $satuanTerkecil = (float)($liftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $liftingItem->qty = $qty;
        $totalLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN SLIDING STOPPER ===
    $stopperItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'sliding-stopper') {
            $stopperItem = $item;
            break;
        }
    }

    $totalStopperQty = 0;
    if ($stopperItem) {
        $satuanTerkecil = (float)($stopperItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $stopperItem->qty = $qty;
        $totalStopperQty += $qty;
    }

    // === PERHITUNGAN SCREW ROLLER ===
    $screwRollerItem = null;
    $screwRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-roller') {
            $screwRollerItem = $item;
            break;
        }
    }

    if ($screwRollerItem) {
        $satuanTerkecilScrewRoller = (float)($screwRollerItem->satuan_terkecil ?? 0);
        $screwRollerQty = $totalRollerQty * $satuanTerkecilScrewRoller;
        $screwRollerItem->qty = $screwRollerQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } elseif ($areaSlug == 'window-hardware-roller') {
            $qty = ($rollerItem && $rollerItem->id == $item->id) ? $totalRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($liftingItem && $liftingItem->id == $item->id) ? $totalLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'sliding-stopper') {
            $qty = ($stopperItem && $stopperItem->id == $item->id) ? $totalStopperQty : 0;
        } elseif ($areaSlug == 'screw-roller') {
            $qty = ($screwRollerItem && $screwRollerItem->id == $item->id) ? $screwRollerQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area untuk PDF
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca' || $areaSlug == 'mohair') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'window-hardware-') || $areaSlug == 'sliding-stopper') {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SLIDING:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Sliding: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'aluminium_track_qty' => $aluminiumTrackQty,
        'mohair_qty' => $mohairQty,
        'total_handle_qty' => $totalHandleQty,
        'total_roller_qty' => $totalRollerQty,
        'total_lifting_qty' => $totalLiftingQty,
        'total_strike_qty' => $totalStrikeQty,
        'total_stopper_qty' => $totalStopperQty,
        'screw_roller_qty' => $screwRollerQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
        'decoration_bar_vertikal' => $decorationBarVertikalInput,
        'decoration_bar_horizontal' => $decorationBarHorizontalInput,
    ];

    // IKUTIN CONTOH - RETURN VIEW (bukan download)
    return view('boq.pintu.pdf-pintu-sliding', compact('data'));
}

public function hitungSliding1(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Sliding 1)
    $produkUtama = Product::find(233);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Sliding tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 233)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = $satuanTerkecilSashVertikal * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = $satuanTerkecilGlazeVertikal * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = 1 * $jumlah;
        $variableTrackB = ($variableTrackA * $lebar)*2;
        $variableTrackB = ($variableTrackB >= 280) ? 580 : $variableTrackB;

        $batangTrack = ($variableTrackB * $jumlah) / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN TUTUP BRACKET ===
    $tutupBracketItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'tutup-bracket') {
            $tutupBracketItem = $item;
            break;
        }
    }

    $totalTutupBracketQty = 0;
    if ($tutupBracketItem) {
        $satuanTerkecil = (float)($tutupBracketItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $tutupBracketItem->qty = $qty;
        $totalTutupBracketQty += $qty;
    }

    // === PERHITUNGAN BRACKET SAMPING ===
    $bracketSampingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'bracket-samping') {
            $bracketSampingItem = $item;
            break;
        }
    }

    $totalBracketSampingQty = 0;
    if ($bracketSampingItem) {
        $satuanTerkecil = (float)($bracketSampingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $bracketSampingItem->qty = $qty;
        $totalBracketSampingQty += $qty;
    }

    // === PERHITUNGAN RODA ATAS ===
    $rodaAtasItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'roda-atas') {
            $rodaAtasItem = $item;
            break;
        }
    }

    $totalRodaAtasQty = 0;
    if ($rodaAtasItem) {
        $satuanTerkecil = (float)($rodaAtasItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rodaAtasItem->qty = $qty;
        $totalRodaAtasQty += $qty;
    }

    // === PERHITUNGAN ROLLER ATAS ===
    $rollerAtasItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'roll-atas') {
            $rollerAtasItem = $item;
            break;
        }
    }

    $totalRollerAtasQty = 0;
    if ($rollerAtasItem) {
        $satuanTerkecil = (float)($rollerAtasItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rollerAtasItem->qty = $qty;
        $totalRollerAtasQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 1;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10) + 12;

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'tutup-bracket') {
            $qty = ($tutupBracketItem && $tutupBracketItem->id == $item->id) ? $totalTutupBracketQty : 0;
        } elseif ($areaSlug == 'bracket-samping') {
            $qty = ($bracketSampingItem && $bracketSampingItem->id == $item->id) ? $totalBracketSampingQty : 0;
        } elseif ($areaSlug == 'roda-atas') {
            $qty = ($rodaAtasItem && $rodaAtasItem->id == $item->id) ? $totalRodaAtasQty : 0;
        } elseif ($areaSlug == 'roll-atas') {
            $qty = ($rollerAtasItem && $rollerAtasItem->id == $item->id) ? $totalRollerAtasQty : 0;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'aluminium_track_item' => $aluminiumTrackItem,
        'aluminium_track_qty' => $aluminiumTrackQty,
        
        'tutup_bracket_item' => $tutupBracketItem,
        'total_tutup_bracket_qty' => $totalTutupBracketQty,
        
        'bracket_samping_item' => $bracketSampingItem,
        'total_bracket_samping_qty' => $totalBracketSampingQty,
        
        'roda_atas_item' => $rodaAtasItem,
        'total_roda_atas_qty' => $totalRodaAtasQty,
        
        'roller_atas_item' => $rollerAtasItem,
        'total_roller_atas_qty' => $totalRollerAtasQty,
        
        'handle_item' => $handleItem,
        'total_handle_qty' => $totalHandleQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-sliding-1', $data);
}

public function exportPdfSliding1(Request $request)
{
    // Ambil data dari form
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Sliding';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 233)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = 1 * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = $satuanTerkecilSashVertikal * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = $satuanTerkecilGlazeVertikal * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = $satuanTerkecilInterlock * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = 1 * $jumlah;
        $variableTrackB = ($variableTrackA * $lebar) * 2;
        $variableTrackB = ($variableTrackB >= 280) ? 580 : $variableTrackB;

        $batangTrack = ($variableTrackB * $jumlah) / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN TUTUP BRACKET ===
    $tutupBracketItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'tutup-bracket') {
            $tutupBracketItem = $item;
            break;
        }
    }

    $totalTutupBracketQty = 0;
    if ($tutupBracketItem) {
        $satuanTerkecil = (float)($tutupBracketItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $tutupBracketItem->qty = $qty;
        $totalTutupBracketQty += $qty;
    }

    // === PERHITUNGAN BRACKET SAMPING ===
    $bracketSampingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'bracket-samping') {
            $bracketSampingItem = $item;
            break;
        }
    }

    $totalBracketSampingQty = 0;
    if ($bracketSampingItem) {
        $satuanTerkecil = (float)($bracketSampingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $bracketSampingItem->qty = $qty;
        $totalBracketSampingQty += $qty;
    }

    // === PERHITUNGAN RODA ATAS ===
    $rodaAtasItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'roda-atas') {
            $rodaAtasItem = $item;
            break;
        }
    }

    $totalRodaAtasQty = 0;
    if ($rodaAtasItem) {
        $satuanTerkecil = (float)($rodaAtasItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rodaAtasItem->qty = $qty;
        $totalRodaAtasQty += $qty;
    }

    // === PERHITUNGAN ROLLER ATAS ===
    $rollerAtasItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'roll-atas') {
            $rollerAtasItem = $item;
            break;
        }
    }

    $totalRollerAtasQty = 0;
    if ($rollerAtasItem) {
        $satuanTerkecil = (float)($rollerAtasItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $rollerAtasItem->qty = $qty;
        $totalRollerAtasQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 1;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10) + 12;

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'tutup-bracket') {
            $qty = ($tutupBracketItem && $tutupBracketItem->id == $item->id) ? $totalTutupBracketQty : 0;
        } elseif ($areaSlug == 'bracket-samping') {
            $qty = ($bracketSampingItem && $bracketSampingItem->id == $item->id) ? $totalBracketSampingQty : 0;
        } elseif ($areaSlug == 'roda-atas') {
            $qty = ($rodaAtasItem && $rodaAtasItem->id == $item->id) ? $totalRodaAtasQty : 0;
        } elseif ($areaSlug == 'roll-atas') {
            $qty = ($rollerAtasItem && $rollerAtasItem->id == $item->id) ? $totalRollerAtasQty : 0;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area untuk PDF
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'window-hardware-') || $areaSlug == 'tutup-bracket' || $areaSlug == 'bracket-samping' || $areaSlug == 'roda-atas' || $areaSlug == 'roll-atas') {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SLIDING 1:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Sliding 1: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'aluminium_track_qty' => $aluminiumTrackQty,
        'total_tutup_bracket_qty' => $totalTutupBracketQty,
        'total_bracket_samping_qty' => $totalBracketSampingQty,
        'total_roda_atas_qty' => $totalRodaAtasQty,
        'total_roller_atas_qty' => $totalRollerAtasQty,
        'total_handle_qty' => $totalHandleQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
        'decoration_bar_vertikal' => $decorationBarVertikalInput,
        'decoration_bar_horizontal' => $decorationBarHorizontalInput,
    ];

    // IKUTIN CONTOH - RETURN VIEW (bukan download)
    return view('boq.pintu.pdf-pintu-sliding-1', compact('data'));
}

public function hitungSliding3track(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Sliding 3 Track)
    $produkUtama = Product::find(241);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Sliding tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 241)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;

    // CEK TINGGI: jika tinggi > 280, maka tinggi jadi 580
    $tinggiFinal = ($tinggi >= 280) ? 580 : $tinggi;

    $batangFrameVertikal = ($variableFrameVertikalA * $tinggiFinal) / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggiFinal * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;

    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;

    // CEK LEBAR: jika lebar > 280, maka lebar jadi 580
    $lebarFinal = ($lebar >= 280) ? 580 : $lebar;

    $batangFrameHorizontal = ($variableFrameHorizontalA * $lebarFinal) / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebarFinal * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($totalLebar + $totalTinggi) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }

    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;

    $variableSashVertikalA = 6 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;

    // CEK TINGGI: jika tinggi > 280, maka tinggi jadi 580
    $tinggiFinal = ($tinggi > 280) ? 580 : $tinggi;

    $batangSashVertikal = ($variableSashVertikalA * $tinggiFinal) / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = 6 * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * ($lebar / 3);
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 6 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = 6 * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * ($lebar / 3);
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = 4 * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;

    // CEK TINGGI: jika tinggi > 280, maka tinggi jadi 580
    $tinggiFinal = ($tinggi > 280) ? 580 : $tinggi;

    $batangInterlock = ($variableInterlockA * $tinggiFinal) / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = 1 * $jumlah;
        $variableTrackB = ($variableTrackA * $lebar) * 2;
        $variableTrackB = ($variableTrackB >= 280) ? 580 : $variableTrackB;

        $batangTrack = ($variableTrackB * $jumlah) / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 1;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN DOOR HANDLE ===
    $doorHandleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    $totalDoorHandleQty = 0;
    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN ADJUSTABLE ROLLER ===
    $adjustableRollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'adjustable-roller') {
            $adjustableRollerItem = $item;
            break;
        }
    }

    $totalAdjustableRollerQty = 0;
    if ($adjustableRollerItem) {
        $satuanTerkecil = (float)($adjustableRollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $adjustableRollerItem->qty = $qty;
        $totalAdjustableRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE ANTILIFTING ===
    $antiLiftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $antiLiftingItem = $item;
            break;
        }
    }

    $totalAntiLiftingQty = 0;
    if ($antiLiftingItem) {
        $satuanTerkecil = (float)($antiLiftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 2;
        $antiLiftingItem->qty = $qty;
        $totalAntiLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = (($variableSashVertikalB + $variableSashHorizontalB) * 2);
        $batangMohair = $variableMohairB / 100;
        $mohairQty = ceil($batangMohair * 10) / 10;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN DOOR TRACK ===
    $doorTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-track') {
            $doorTrackItem = $item;
            break;
        }
    }

    $doorTrackQty = 0;
    if ($doorTrackItem) {
        $satuanTerkecilDoorTrack = (float)($doorTrackItem->satuan_terkecil ?? 0);
        
        $variableDoorTrackA = $satuanTerkecilDoorTrack * $jumlah;
        
        // CEK LEBAR: jika lebar > 280, maka lebar jadi 580
        $lebarFinal = ($lebar >= 280) ? 580 : $lebar;
        
        $batangDoorTrack = ($variableDoorTrackA * $lebarFinal) / 580;
        $doorTrackQty = ceil($batangDoorTrack * 10) / 10;
        $doorTrackItem->qty = $doorTrackQty;
    }

    // === PERHITUNGAN SCREW DOOR ROLLER ===
    $screwDoorRollerItem = null;
    $screwDoorRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-door-roller') {
            $screwDoorRollerItem = $item;
            break;
        }
    }

    if ($screwDoorRollerItem) {
        $satuanTerkecilScrewDoorRoller = (float)($screwDoorRollerItem->satuan_terkecil ?? 0);
        $screwDoorRollerQty = $totalAdjustableRollerQty * $satuanTerkecilScrewDoorRoller;
        $screwDoorRollerItem->qty = $screwDoorRollerQty;
    }
// === PERHITUNGAN SCREW ANTI STRIKE BLOCK ===
$screwAntiStrikeBlockItem = null;
$screwAntiStrikeBlockQty = 0;

for ($i = 0; $i < $aksesorisCount; $i++) {
    $item = $aksesoris[$i];
    if ($item->area && $item->area->slug == 'screw-anti-strike') {
        $screwAntiStrikeBlockItem = $item;
        break;
    }
}

if ($screwAntiStrikeBlockItem) {
    $satuanTerkecilScrewAntiStrikeBlock = (float)($screwAntiStrikeBlockItem->satuan_terkecil ?? 0);
    // Qty screw anti strike = total window hardware strike x satuan terkecil
    $screwAntiStrikeBlockQty = $totalStrikeQty * $satuanTerkecilScrewAntiStrikeBlock;
    $screwAntiStrikeBlockItem->qty = $screwAntiStrikeBlockQty;
}

    // === PERHITUNGAN SCREW ANTI LIFTING ===
    $screwAntiLiftingItem = null;
    $screwAntiLiftingQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-lifting') {
            $screwAntiLiftingItem = $item;
            break;
        }
    }

    if ($screwAntiLiftingItem) {
        $satuanTerkecilScrewAntiLifting = (float)($screwAntiLiftingItem->satuan_terkecil ?? 0);
        $screwAntiLiftingQty = $totalAntiLiftingQty * $satuanTerkecilScrewAntiLifting;
        $screwAntiLiftingItem->qty = $screwAntiLiftingQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10) + 12;

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } elseif ($areaSlug == 'adjustable-roller') {
            $qty = ($adjustableRollerItem && $adjustableRollerItem->id == $item->id) ? $totalAdjustableRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($antiLiftingItem && $antiLiftingItem->id == $item->id) ? $totalAntiLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'door-track') {
            $qty = $doorTrackQty;
        } elseif ($areaSlug == 'screw-door-roller') {
            $qty = ($screwDoorRollerItem && $screwDoorRollerItem->id == $item->id) ? $screwDoorRollerQty : 0;
        } elseif ($areaSlug == 'screw-anti-strike') {
            $qty = ($screwAntiStrikeBlockItem && $screwAntiStrikeBlockItem->id == $item->id) ? $screwAntiStrikeBlockQty : 0;
        } elseif ($areaSlug == 'screw-lifting') {
            $qty = ($screwAntiLiftingItem && $screwAntiLiftingItem->id == $item->id) ? $screwAntiLiftingQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'aluminium_track_item' => $aluminiumTrackItem,
        'aluminium_track_qty' => $aluminiumTrackQty,
        
        'handle_item' => $handleItem,
        'total_handle_qty' => $totalHandleQty,
        
        'door_handle_item' => $doorHandleItem,
        'total_door_handle_qty' => $totalDoorHandleQty,
        
        'adjustable_roller_item' => $adjustableRollerItem,
        'total_adjustable_roller_qty' => $totalAdjustableRollerQty,
        
        'anti_lifting_item' => $antiLiftingItem,
        'total_anti_lifting_qty' => $totalAntiLiftingQty,
        
        'strike_item' => $strikeItem,
        'total_strike_qty' => $totalStrikeQty,
        
        'mohair_item' => $mohairItem,
        'mohair_qty' => $mohairQty,
        
        'door_track_item' => $doorTrackItem,
        'door_track_qty' => $doorTrackQty,
        
        'screw_door_roller_item' => $screwDoorRollerItem,
        'screw_door_roller_qty' => $screwDoorRollerQty,
        
        'screw_anti_strike_block_item' => $screwAntiStrikeBlockItem,
        'screw_anti_strike_block_qty' => $screwAntiStrikeBlockQty,
        
        'screw_anti_lifting_item' => $screwAntiLiftingItem,
        'screw_anti_lifting_qty' => $screwAntiLiftingQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-sliding-3-track', $data);
}

public function exportPdfSliding3track(Request $request)
{
    // Ambil data dari form
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Sliding 3 Track';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 241)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * $lebarM;
    $luasKacaTotal = $luasKacaPerUnit * $jumlah;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;

    $tinggiFinal = ($tinggi >= 280) ? 580 : $tinggi;

    $batangFrameVertikal = ($variableFrameVertikalA * $tinggiFinal) / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggiFinal * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;

    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;

    $lebarFinal = ($lebar >= 280) ? 580 : $lebar;

    $batangFrameHorizontal = ($variableFrameHorizontalA * $lebarFinal) / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebarFinal * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($totalLebar + $totalTinggi) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }

    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;

    $variableSashVertikalA = 6 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;

    $tinggiFinal = ($tinggi > 280) ? 580 : $tinggi;

    $batangSashVertikal = ($variableSashVertikalA * $tinggiFinal) / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = 6 * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * ($lebar / 3);
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 6 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = 6 * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * ($lebar / 3);
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = 4 * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;

    $tinggiFinal = ($tinggi > 280) ? 580 : $tinggi;

    $batangInterlock = ($variableInterlockA * $tinggiFinal) / 580;
    $batangInterlockQty = ceil($batangInterlock * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = 1 * $jumlah;
        $variableTrackB = ($variableTrackA * $lebar) * 2;
        $variableTrackB = ($variableTrackB >= 280) ? 580 : $variableTrackB;

        $batangTrack = ($variableTrackB * $jumlah) / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN WINDOW HARDWARE HANDLE ===
    $handleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-handle') {
            $handleItem = $item;
            break;
        }
    }

    $totalHandleQty = 0;
    if ($handleItem) {
        $satuanTerkecil = (float)($handleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 1;
        $handleItem->qty = $qty;
        $totalHandleQty += $qty;
    }

    // === PERHITUNGAN DOOR HANDLE ===
    $doorHandleItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItem = $item;
            break;
        }
    }

    $totalDoorHandleQty = 0;
    if ($doorHandleItem) {
        $satuanTerkecil = (float)($doorHandleItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $doorHandleItem->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN ADJUSTABLE ROLLER ===
    $adjustableRollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'adjustable-roller') {
            $adjustableRollerItem = $item;
            break;
        }
    }

    $totalAdjustableRollerQty = 0;
    if ($adjustableRollerItem) {
        $satuanTerkecil = (float)($adjustableRollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $adjustableRollerItem->qty = $qty;
        $totalAdjustableRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE LIFTING ===
    $antiLiftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $antiLiftingItem = $item;
            break;
        }
    }

    $totalAntiLiftingQty = 0;
    if ($antiLiftingItem) {
        $satuanTerkecil = (float)($antiLiftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 2;
        $antiLiftingItem->qty = $qty;
        $totalAntiLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 6;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = (($variableSashVertikalB + $variableSashHorizontalB) * 2);
        $batangMohair = $variableMohairB / 100;
        $mohairQty = ceil($batangMohair * 10) / 10;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN DOOR TRACK ===
    $doorTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-track') {
            $doorTrackItem = $item;
            break;
        }
    }

    $doorTrackQty = 0;
    if ($doorTrackItem) {
        $satuanTerkecilDoorTrack = (float)($doorTrackItem->satuan_terkecil ?? 0);
        
        $variableDoorTrackA = $satuanTerkecilDoorTrack * $jumlah;
        
        $lebarFinal = ($lebar >= 280) ? 580 : $lebar;
        
        $batangDoorTrack = ($variableDoorTrackA * $lebarFinal) / 580;
        $doorTrackQty = ceil($batangDoorTrack * 10) / 10;
        $doorTrackItem->qty = $doorTrackQty;
    }

    // === PERHITUNGAN SCREW DOOR ROLLER ===
    $screwDoorRollerItem = null;
    $screwDoorRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-door-roller') {
            $screwDoorRollerItem = $item;
            break;
        }
    }

    if ($screwDoorRollerItem) {
        $satuanTerkecilScrewDoorRoller = (float)($screwDoorRollerItem->satuan_terkecil ?? 0);
        $screwDoorRollerQty = $totalAdjustableRollerQty * $satuanTerkecilScrewDoorRoller;
        $screwDoorRollerItem->qty = $screwDoorRollerQty;
    }

    // === PERHITUNGAN SCREW ANTI STRIKE BLOCK ===
    $screwAntiStrikeBlockItem = null;
    $screwAntiStrikeBlockQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-anti-strike') {
            $screwAntiStrikeBlockItem = $item;
            break;
        }
    }

    if ($screwAntiStrikeBlockItem) {
        $satuanTerkecilScrewAntiStrikeBlock = (float)($screwAntiStrikeBlockItem->satuan_terkecil ?? 0);
        $screwAntiStrikeBlockQty = $totalStrikeQty * $satuanTerkecilScrewAntiStrikeBlock;
        $screwAntiStrikeBlockItem->qty = $screwAntiStrikeBlockQty;
    }

    // === PERHITUNGAN SCREW ANTI LIFTING ===
    $screwAntiLiftingItem = null;
    $screwAntiLiftingQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-lifting') {
            $screwAntiLiftingItem = $item;
            break;
        }
    }

    if ($screwAntiLiftingItem) {
        $satuanTerkecilScrewAntiLifting = (float)($screwAntiLiftingItem->satuan_terkecil ?? 0);
        $screwAntiLiftingQty = $totalAntiLiftingQty * $satuanTerkecilScrewAntiLifting;
        $screwAntiLiftingItem->qty = $screwAntiLiftingQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10) + 12;

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'window-hardware-handle') {
            $qty = ($handleItem && $handleItem->id == $item->id) ? $totalHandleQty : 0;
        } elseif ($areaSlug == 'door-handle') {
            $qty = ($doorHandleItem && $doorHandleItem->id == $item->id) ? $totalDoorHandleQty : 0;
        } elseif ($areaSlug == 'adjustable-roller') {
            $qty = ($adjustableRollerItem && $adjustableRollerItem->id == $item->id) ? $totalAdjustableRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($antiLiftingItem && $antiLiftingItem->id == $item->id) ? $totalAntiLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'door-track') {
            $qty = $doorTrackQty;
        } elseif ($areaSlug == 'screw-door-roller') {
            $qty = ($screwDoorRollerItem && $screwDoorRollerItem->id == $item->id) ? $screwDoorRollerQty : 0;
        } elseif ($areaSlug == 'screw-anti-strike') {
            $qty = ($screwAntiStrikeBlockItem && $screwAntiStrikeBlockItem->id == $item->id) ? $screwAntiStrikeBlockQty : 0;
        } elseif ($areaSlug == 'screw-lifting') {
            $qty = ($screwAntiLiftingItem && $screwAntiLiftingItem->id == $item->id) ? $screwAntiLiftingQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area untuk PDF
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca' || $areaSlug == 'mohair') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'window-hardware-') || $areaSlug == 'door-handle' || $areaSlug == 'adjustable-roller') {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SLIDING 3 TRACK:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Sliding 3 Track: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'aluminium_track_qty' => $aluminiumTrackQty,
        'total_handle_qty' => $totalHandleQty,
        'total_door_handle_qty' => $totalDoorHandleQty,
        'total_adjustable_roller_qty' => $totalAdjustableRollerQty,
        'total_anti_lifting_qty' => $totalAntiLiftingQty,
        'total_strike_qty' => $totalStrikeQty,
        'mohair_qty' => $mohairQty,
        'door_track_qty' => $doorTrackQty,
        'screw_door_roller_qty' => $screwDoorRollerQty,
        'screw_anti_strike_block_qty' => $screwAntiStrikeBlockQty,
        'screw_anti_lifting_qty' => $screwAntiLiftingQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
        'decoration_bar_vertikal' => $decorationBarVertikalInput,
        'decoration_bar_horizontal' => $decorationBarHorizontalInput,
    ];

    // IKUTIN CONTOH - RETURN VIEW (bukan download)
    return view('boq.pintu.pdf-pintu-sliding-3-track', compact('data'));
}

public function hitungSliding4(Request $request)
{
    $request->validate([
        'tinggi' => 'required|numeric|min:1',
        'lebar' => 'required|numeric|min:1',
        'tebal_kaca' => 'required|numeric|min:1',
        'jumlah' => 'required|numeric|min:1',
        'warna' => 'required|string',
        'type_kaca' => 'required|string',
    ]);

    // Ambil data dari form
    $tinggi = $request->tinggi; // cm
    $lebar = $request->lebar; // cm
    $tebalKaca = $request->tebal_kaca; // mm
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $typeKaca = $request->type_kaca;

    // Ambil produk utama (Pintu Sliding 4)
    $produkUtama = Product::find(255);
    
    if (!$produkUtama) {
        return back()->with('error', 'Produk Pintu Sliding tidak ditemukan!');
    }

    // Ambil aksesoris dari tabel product_accessories dengan relasi area
    $aksesorisIds = ProductAccessories::where('parent_product_id', 255)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    
    // Konversi ke meter
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    $tebalKacaM = $tebalKaca / 1000;

    // 1. Luas Kaca (m²)
    $luasKacaPerUnit = $tinggiM * ($lebarM / $tebalKaca);
    $luasKacaTotal = ($luasKacaPerUnit * $jumlah) * 4;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    // 2. Keliling Profile (meter)
    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 8 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 8 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = 5 * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock);

    // === PERHITUNGAN PROFILE SASH JOIN VERTIKAL ===
    $profileSashJoinVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-join-vertikal') {
            $profileSashJoinVertikal = $item;
            break;
        }
    }

    $satuanTerkecilSashJoinVertikal = $profileSashJoinVertikal ? (float)$profileSashJoinVertikal->satuan_terkecil : 0;

    $variableSashJoinVertikalA = $satuanTerkecilSashJoinVertikal * $jumlah;
    $variableSashJoinVertikalB = $variableSashJoinVertikalA * $tinggi;
    $batangSashJoinVertikal = $variableSashJoinVertikalB / 580;
    $batangSashJoinVertikalQty = ceil($batangSashJoinVertikal * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = $satuanTerkecilTrack * $jumlah;
        $variableTrackB = $variableTrackA * $lebar;
        $batangTrack = $variableTrackB / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = $variableMohairA * ($tinggi + $lebar);
        $batangMohair = $variableMohairB / 580;
        $mohairQty = (ceil((((($variableSashHorizontalB + $variableSashVertikalB)) / 100) * 2)))*$jumlah;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN DOOR HANDLE (MULTIPLE ITEMS) ===
    $doorHandleItems = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItems[] = $item;
        }
    }

    $totalDoorHandleQty = 0;
    foreach ($doorHandleItems as $item) {
        $satuanTerkecil = (float)($item->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $item->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN DOOR HARDWARE LOCK ===
    $lockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-hardware-lock') {
            $lockItem = $item;
            break;
        }
    }

    $totalLockQty = 0;
    if ($lockItem) {
        $satuanTerkecil = (float)($lockItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $lockItem->qty = $qty;
        $totalLockQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE ROLLER ===
    $rollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-roller') {
            $rollerItem = $item;
            break;
        }
    }

    $totalRollerQty = 0;
    if ($rollerItem) {
        $satuanTerkecil = (float)($rollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 8;
        $rollerItem->qty = $qty;
        $totalRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE LIFTING ===
    $liftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $liftingItem = $item;
            break;
        }
    }

    $totalLiftingQty = 0;
    if ($liftingItem) {
        $satuanTerkecil = (float)($liftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $liftingItem->qty = $qty;
        $totalLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN SLIDING STOPPER ===
    $stopperItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'sliding-stopper') {
            $stopperItem = $item;
            break;
        }
    }

    $totalStopperQty = 0;
    if ($stopperItem) {
        $satuanTerkecil = (float)($stopperItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 8;
        $stopperItem->qty = $qty;
        $totalStopperQty += $qty;
    }

    // === PERHITUNGAN SCREW ROLLER ===
    $screwRollerItem = null;
    $screwRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-door-roller') {
            $screwRollerItem = $item;
            break;
        }
    }

    if ($screwRollerItem) {
        $satuanTerkecilScrewRoller = (float)($screwRollerItem->satuan_terkecil ?? 0);
        // Qty screw roller = total roller qty x satuan terkecil
        $screwRollerQty = $totalStrikeQty * $satuanTerkecilScrewRoller;
        $screwRollerItem->qty = $screwRollerQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === PERHITUNGAN QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-sash-join-vertikal') {
            $qty = $batangSashJoinVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'door-handle') {
            $qty = $item->qty ?? 0;
        } elseif ($areaSlug == 'door-hardware-lock') {
            $qty = ($lockItem && $lockItem->id == $item->id) ? $totalLockQty : 0;
        } elseif ($areaSlug == 'window-hardware-roller') {
            $qty = ($rollerItem && $rollerItem->id == $item->id) ? $totalRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($liftingItem && $liftingItem->id == $item->id) ? $totalLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'sliding-stopper') {
            $qty = ($stopperItem && $stopperItem->id == $item->id) ? $totalStopperQty : 0;
        } elseif ($areaSlug == 'screw-door-roller') {
            $qty = ($screwRollerItem && $screwRollerItem->id == $item->id) ? $screwRollerQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Data untuk view
    $data = [
        'produk' => $produkUtama,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebalKaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $typeKaca,
        
        'luas_kaca_per_unit' => $luasKacaPerUnit,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_per_unit' => $kelilingPerUnit,
        'keliling_total' => $kelilingTotal,
        
        'profile_frame_vertikal' => $profileFrameVertikal,
        'satuan_terkecil_frame_vertikal' => $satuanTerkecilFrameVertikal,
        'variable_frame_vertikal_a' => $variableFrameVertikalA,
        'variable_frame_vertikal_b' => $variableFrameVertikalB,
        'batang_frame_vertikal' => $batangFrameVertikal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        
        'profile_frame_horizontal' => $profileFrameHorizontal,
        'satuan_terkecil_frame_horizontal' => $satuanTerkecilFrameHorizontal,
        'variable_frame_horizontal_a' => $variableFrameHorizontalA,
        'variable_frame_horizontal_b' => $variableFrameHorizontalB,
        'batang_frame_horizontal' => $batangFrameHorizontal,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        
        'total_batang_reinforcement' => $totalBatangReinforcement,
        'reinforcement_qty' => $reinforcementQty,
        
        'profile_sash_vertikal' => $profileSashVertikal,
        'satuan_terkecil_sash_vertikal' => $satuanTerkecilSashVertikal,
        'variable_sash_vertikal_a' => $variableSashVertikalA,
        'variable_sash_vertikal_b' => $variableSashVertikalB,
        'batang_sash_vertikal' => $batangSashVertikal,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        
        'profile_sash_horizontal' => $profileSashHorizontal,
        'satuan_terkecil_sash_horizontal' => $satuanTerkecilSashHorizontal,
        'variable_sash_horizontal_a' => $variableSashHorizontalA,
        'variable_sash_horizontal_b' => $variableSashHorizontalB,
        'batang_sash_horizontal' => $batangSashHorizontal,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        
        'reinforcement_sash_qty' => $reinforcementSashQty,
        
        'profile_glaze_vertikal' => $profileGlazeVertikal,
        'satuan_terkecil_glaze_vertikal' => $satuanTerkecilGlazeVertikal,
        'variable_glaze_vertikal_a' => $variableGlazeVertikalA,
        'variable_glaze_vertikal_b' => $variableGlazeVertikalB,
        'batang_glaze_vertikal' => $batangGlazeVertikal,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        
        'profile_glaze_horizontal' => $profileGlazeHorizontal,
        'satuan_terkecil_glaze_horizontal' => $satuanTerkecilGlazeHorizontal,
        'variable_glaze_horizontal_a' => $variableGlazeHorizontalA,
        'variable_glaze_horizontal_b' => $variableGlazeHorizontalB,
        'batang_glaze_horizontal' => $batangGlazeHorizontal,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        
        'profile_interlock_vertikal' => $profileInterlockVertikal,
        'satuan_terkecil_interlock' => $satuanTerkecilInterlock,
        'variable_interlock_a' => $variableInterlockA,
        'variable_interlock_b' => $variableInterlockB,
        'batang_interlock' => $batangInterlock,
        'batang_interlock_qty' => $batangInterlockQty,
        
        'profile_sash_join_vertikal' => $profileSashJoinVertikal,
        'satuan_terkecil_sash_join_vertikal' => $satuanTerkecilSashJoinVertikal,
        'variable_sash_join_vertikal_a' => $variableSashJoinVertikalA,
        'variable_sash_join_vertikal_b' => $variableSashJoinVertikalB,
        'batang_sash_join_vertikal' => $batangSashJoinVertikal,
        'batang_sash_join_vertikal_qty' => $batangSashJoinVertikalQty,
        
        'aluminium_track_item' => $aluminiumTrackItem,
        'aluminium_track_qty' => $aluminiumTrackQty,
        
        'mohair_item' => $mohairItem,
        'mohair_qty' => $mohairQty,
        
        'door_handle_items' => $doorHandleItems,
        'total_door_handle_qty' => $totalDoorHandleQty,
        
        'lock_item' => $lockItem,
        'total_lock_qty' => $totalLockQty,
        
        'roller_item' => $rollerItem,
        'total_roller_qty' => $totalRollerQty,
        
        'lifting_item' => $liftingItem,
        'total_lifting_qty' => $totalLiftingQty,
        
        'strike_item' => $strikeItem,
        'total_strike_qty' => $totalStrikeQty,
        
        'stopper_item' => $stopperItem,
        'total_stopper_qty' => $totalStopperQty,
        
        'screw_roller_item' => $screwRollerItem,
        'screw_roller_qty' => $screwRollerQty,
        
        'decoration_bar_vertikal_input' => $decorationBarVertikalInput,
        'decoration_bar_vertikal_item' => $decorationBarVertikalItem,
        'batang_decoration_vertikal' => $batangDecorationVertikal,
        'batang_decoration_vertikal_qty' => $batangDecorationVertikalQty,
        
        'decoration_bar_horizontal_input' => $decorationBarHorizontalInput,
        'decoration_bar_horizontal_item' => $decorationBarHorizontalItem,
        'batang_decoration_horizontal' => $batangDecorationHorizontal,
        'batang_decoration_horizontal_qty' => $batangDecorationHorizontalQty,
        
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        
        'satuan_terkecil_screw' => $satuanTerkecilScrew,
        'screw_qty' => $screwQty,
        
        'setting_block_item' => $settingBlockItem,
        'satuan_terkecil_setting_block' => $satuanTerkecilSettingBlock,
        'variable_c' => $variableC,
        'variable_d' => $variableD,
        'variable_e' => $variableE,
        'variable_f' => $variableF,
        'setting_block_qty' => $settingBlockQty,
        
        'aksesoris' => $aksesoris,
    ];

    return view('boq.pintu.pintu-sliding-4', $data);
}

public function exportPdfSliding4(Request $request)
{
    // Ambil data dari form
    $tinggi = $request->tinggi;
    $lebar = $request->lebar;
    $tebal_kaca = $request->tebal_kaca;
    $jumlah = $request->jumlah;
    $warna = $request->warna;
    $type_kaca = $request->type_kaca;
    $judul = $request->judul ?? 'BOQ - Pintu Sliding 4';

    // Ambil data aksesoris
    $aksesorisIds = ProductAccessories::where('parent_product_id', 255)->pluck('accessory_id');
    $aksesoris = Product::with('area', 'unit')
        ->whereIn('id', $aksesorisIds)
        ->get();

    // === PERHITUNGAN DASAR ===
    $tinggiM = $tinggi / 100;
    $lebarM = $lebar / 100;
    
    $luasKacaPerUnit = $tinggiM * ($lebarM / $tebal_kaca);
    $luasKacaTotal = ($luasKacaPerUnit * $jumlah) * 4;
    $luasKacaTotal = ceil($luasKacaTotal * 10) / 10;

    $kelilingPerUnit = 2 * ($tinggiM + $lebarM);
    $kelilingTotal = $kelilingPerUnit * $jumlah;

    // === PERHITUNGAN PROFILE FRAME VERTIKAL ===
    $profileFrameVertikal = null;
    $aksesorisCount = count($aksesoris);
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-vertikal') {
            $profileFrameVertikal = $item;
            break;
        }
    }
    $satuanTerkecilFrameVertikal = $profileFrameVertikal ? (float)$profileFrameVertikal->satuan_terkecil : 0;

    $variableFrameVertikalA = $satuanTerkecilFrameVertikal * $jumlah;
    $variableFrameVertikalB = $variableFrameVertikalA * $tinggi;
    $batangFrameVertikal = $variableFrameVertikalB / 580;
    $batangFrameVertikalQty = ceil($batangFrameVertikal * 10) / 10;
    $totalTinggi = $tinggi * $variableFrameVertikalA;

    // === PERHITUNGAN PROFILE FRAME HORIZONTAL ===
    $profileFrameHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-frame-horizontal') {
            $profileFrameHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilFrameHorizontal = $profileFrameHorizontal ? (float)$profileFrameHorizontal->satuan_terkecil : 0;
    
    $variableFrameHorizontalA = $satuanTerkecilFrameHorizontal * $jumlah;
    $variableFrameHorizontalB = $variableFrameHorizontalA * $lebar;
    $batangFrameHorizontal = $variableFrameHorizontalB / 580;
    $batangFrameHorizontalQty = ceil($batangFrameHorizontal * 10) / 10;
    $totalLebar = $lebar * $variableFrameHorizontalA;

    // === PERHITUNGAN REINFORCEMENT ===
    $totalBatangReinforcement = ($variableFrameVertikalB + $variableFrameHorizontalB) / 600;
    $reinforcementQty = ceil($totalBatangReinforcement * 10) / 10;

    // === PERHITUNGAN PROFILE SASH VERTIKAL ===
    $profileSashVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-vertikal') {
            $profileSashVertikal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashVertikal = $profileSashVertikal ? (float)$profileSashVertikal->satuan_terkecil : 0;
    
    $variableSashVertikalA = 8 * $jumlah;
    $variableSashVertikalB = $variableSashVertikalA * $tinggi;
    $batangSashVertikal = $variableSashVertikalB / 580;
    $batangSashVertikalQty = ceil($batangSashVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE SASH HORIZONTAL ===
    $profileSashHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-horizontal') {
            $profileSashHorizontal = $item;
            break;
        }
    }
    
    $satuanTerkecilSashHorizontal = $profileSashHorizontal ? (float)$profileSashHorizontal->satuan_terkecil : 0;
    
    $variableSashHorizontalA = $satuanTerkecilSashHorizontal * $jumlah;
    $variableSashHorizontalB = $variableSashHorizontalA * $lebar;
    $batangSashHorizontal = $variableSashHorizontalB / 580;
    $batangSashHorizontalQty = ceil($batangSashHorizontal * 10) / 10;

    // === PERHITUNGAN REINFORCEMENT SASH ===
    $totalBatangSash = ($variableSashVertikalB + $variableSashHorizontalB) / 600;
    $reinforcementSashQty = ceil($totalBatangSash * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE VERTIKAL ===
    $profileGlazeVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-vertikal') {
            $profileGlazeVertikal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeVertikal = $profileGlazeVertikal ? (float)$profileGlazeVertikal->satuan_terkecil : 0;

    $variableGlazeVertikalA = 8 * $jumlah;
    $variableGlazeVertikalB = $variableGlazeVertikalA * $tinggi;
    $batangGlazeVertikal = $variableGlazeVertikalB / 580;
    $batangGlazeVertikalQty = ceil($batangGlazeVertikal * 10) / 10;

    // === PERHITUNGAN PROFILE GLAZE HORIZONTAL ===
    $profileGlazeHorizontal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-glaze-horizontal') {
            $profileGlazeHorizontal = $item;
            break;
        }
    }

    $satuanTerkecilGlazeHorizontal = $profileGlazeHorizontal ? (float)$profileGlazeHorizontal->satuan_terkecil : 0;

    $variableGlazeHorizontalA = $satuanTerkecilGlazeHorizontal * $jumlah;
    $variableGlazeHorizontalB = $variableGlazeHorizontalA * $lebar;
    $batangGlazeHorizontal = $variableGlazeHorizontalB / 580;
    $batangGlazeHorizontalQty = ceil($batangGlazeHorizontal * 10) / 10;

    // === PERHITUNGAN PROFILE INTERLOCK VERTIKAL ===
    $profileInterlockVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-interlock-vertikal') {
            $profileInterlockVertikal = $item;
            break;
        }
    }

    $satuanTerkecilInterlock = $profileInterlockVertikal ? (float)$profileInterlockVertikal->satuan_terkecil : 0;

    $variableInterlockA = 5 * $jumlah;
    $variableInterlockB = $variableInterlockA * $tinggi;
    $batangInterlock = $variableInterlockB / 580;
    $batangInterlockQty = ceil($batangInterlock);

    // === PERHITUNGAN PROFILE SASH JOIN VERTIKAL ===
    $profileSashJoinVertikal = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-sash-join-vertikal') {
            $profileSashJoinVertikal = $item;
            break;
        }
    }

    $satuanTerkecilSashJoinVertikal = $profileSashJoinVertikal ? (float)$profileSashJoinVertikal->satuan_terkecil : 0;

    $variableSashJoinVertikalA = $satuanTerkecilSashJoinVertikal * $jumlah;
    $variableSashJoinVertikalB = $variableSashJoinVertikalA * $tinggi;
    $batangSashJoinVertikal = $variableSashJoinVertikalB / 580;
    $batangSashJoinVertikalQty = ceil($batangSashJoinVertikal * 10) / 10;

    // === PERHITUNGAN ALUMINIUM TRACK ===
    $aluminiumTrackItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'profile-alumunium-track') {
            $aluminiumTrackItem = $item;
            break;
        }
    }

    $aluminiumTrackQty = 0;
    if ($aluminiumTrackItem) {
        $satuanTerkecilTrack = (float)($aluminiumTrackItem->satuan_terkecil ?? 0);
        
        $variableTrackA = $satuanTerkecilTrack * $jumlah;
        $variableTrackB = $variableTrackA * $lebar;
        $batangTrack = $variableTrackB / 580;
        $aluminiumTrackQty = ceil($batangTrack * 10) / 10;
        $aluminiumTrackItem->qty = $aluminiumTrackQty;
    }

    // === PERHITUNGAN MOHAIR ===
    $mohairItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'mohair') {
            $mohairItem = $item;
            break;
        }
    }

    $mohairQty = 0;
    if ($mohairItem) {
        $satuanTerkecilMohair = (float)($mohairItem->satuan_terkecil ?? 0);
        
        $variableMohairA = $satuanTerkecilMohair * $jumlah;
        $variableMohairB = $variableMohairA * ($tinggi + $lebar);
        $batangMohair = $variableMohairB / 580;
        $mohairQty = (ceil((((($variableSashHorizontalB + $variableSashVertikalB)) / 100) * 2))) * $jumlah;
        $mohairItem->qty = $mohairQty;
    }

    // === PERHITUNGAN DOOR HANDLE (MULTIPLE ITEMS) ===
    $doorHandleItems = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-handle') {
            $doorHandleItems[] = $item;
        }
    }

    $totalDoorHandleQty = 0;
    foreach ($doorHandleItems as $item) {
        $satuanTerkecil = (float)($item->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $item->qty = $qty;
        $totalDoorHandleQty += $qty;
    }

    // === PERHITUNGAN DOOR HARDWARE LOCK ===
    $lockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'door-hardware-lock') {
            $lockItem = $item;
            break;
        }
    }

    $totalLockQty = 0;
    if ($lockItem) {
        $satuanTerkecil = (float)($lockItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $lockItem->qty = $qty;
        $totalLockQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE ROLLER ===
    $rollerItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-roller') {
            $rollerItem = $item;
            break;
        }
    }

    $totalRollerQty = 0;
    if ($rollerItem) {
        $satuanTerkecil = (float)($rollerItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 8;
        $rollerItem->qty = $qty;
        $totalRollerQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE LIFTING ===
    $liftingItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-lifting') {
            $liftingItem = $item;
            break;
        }
    }

    $totalLiftingQty = 0;
    if ($liftingItem) {
        $satuanTerkecil = (float)($liftingItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $liftingItem->qty = $qty;
        $totalLiftingQty += $qty;
    }

    // === PERHITUNGAN WINDOW HARDWARE STRIKE ===
    $strikeItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'window-hardware-strike') {
            $strikeItem = $item;
            break;
        }
    }

    $totalStrikeQty = 0;
    if ($strikeItem) {
        $satuanTerkecil = (float)($strikeItem->satuan_terkecil ?? 0);
        $qty = $jumlah * $satuanTerkecil;
        $strikeItem->qty = $qty;
        $totalStrikeQty += $qty;
    }

    // === PERHITUNGAN SLIDING STOPPER ===
    $stopperItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'sliding-stopper') {
            $stopperItem = $item;
            break;
        }
    }

    $totalStopperQty = 0;
    if ($stopperItem) {
        $satuanTerkecil = (float)($stopperItem->satuan_terkecil ?? 0);
        $qty = $jumlah * 8;
        $stopperItem->qty = $qty;
        $totalStopperQty += $qty;
    }

    // === PERHITUNGAN SCREW ROLLER ===
    $screwRollerItem = null;
    $screwRollerQty = 0;

    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-door-roller') {
            $screwRollerItem = $item;
            break;
        }
    }

    if ($screwRollerItem) {
        $satuanTerkecilScrewRoller = (float)($screwRollerItem->satuan_terkecil ?? 0);
        $screwRollerQty = $totalStrikeQty * $satuanTerkecilScrewRoller;
        $screwRollerItem->qty = $screwRollerQty;
    }

    // === PERHITUNGAN DECORATION BAR ===
    $decorationBarVertikalInput = (int)($request->decoration_bar_vertikal ?? 0);
    $decorationBarVertikalItem = null;
    $batangDecorationVertikal = 0;
    $batangDecorationVertikalQty = 0;

    if ($decorationBarVertikalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-vertikal') {
                $decorationBarVertikalItem = $item;
                break;
            }
        }
        if ($decorationBarVertikalItem) {
            $satuanTerkecil = (float)($decorationBarVertikalItem->satuan_terkecil ?? 0);
            $variableDecorationVertikalA = $decorationBarVertikalInput * $jumlah * 2;
            $variableDecorationVertikalB = $variableDecorationVertikalA * $tinggi;
            $batangDecorationVertikal = $variableDecorationVertikalB / 580;
            $batangDecorationVertikalQty = ceil($batangDecorationVertikal * 10) / 10;
            $decorationBarVertikalItem->qty = $batangDecorationVertikalQty;
        }
    }

    $decorationBarHorizontalInput = (int)($request->decoration_bar_horizontal ?? 0);
    $decorationBarHorizontalItem = null;
    $batangDecorationHorizontal = 0;
    $batangDecorationHorizontalQty = 0;

    if ($decorationBarHorizontalInput > 0) {
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->area && $item->area->slug == 'profile-decoration-horizontal') {
                $decorationBarHorizontalItem = $item;
                break;
            }
        }
        if ($decorationBarHorizontalItem) {
            $satuanTerkecil = (float)($decorationBarHorizontalItem->satuan_terkecil ?? 0);
            $variableDecorationHorizontalA = $decorationBarHorizontalInput * $jumlah * 2;
            $variableDecorationHorizontalB = $variableDecorationHorizontalA * $lebar;
            $batangDecorationHorizontal = $variableDecorationHorizontalB / 580;
            $batangDecorationHorizontalQty = ceil($batangDecorationHorizontal * 10) / 10;
            $decorationBarHorizontalItem->qty = $batangDecorationHorizontalQty;
        }
    }

    $totalDecorationBarQty = ceil(($batangDecorationVertikal + $batangDecorationHorizontal) * 10) / 10;

    // === PERHITUNGAN SETTING BLOCK ===
    $settingBlockItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'setting-block') {
            $settingBlockItem = $item;
            break;
        }
    }
    
    $satuanTerkecilSettingBlock = $settingBlockItem ? (float)$settingBlockItem->satuan_terkecil : 0;
    
    $variableC = 2 * $satuanTerkecilSettingBlock;
    $variableD = $variableC * $jumlah;
    $variableE = $variableD * 25;
    $variableF = $variableE / 1000;
    $settingBlockQty = ceil($variableF);

    // === PERHITUNGAN SCREW REINFORCEMENT ===
    $screwItem = null;
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->area && $item->area->slug == 'screw-reinforcement') {
            $screwItem = $item;
            break;
        }
    }
    
    $satuanTerkecilScrew = $screwItem ? (float)$screwItem->satuan_terkecil : 0;
    $screwQty = ((($totalLebar + $totalTinggi)) + ($variableSashVertikalB + $variableSashHorizontalB)) / $satuanTerkecilScrew;
    $screwQty = ceil($screwQty * 10);

    // === HITUNG QTY PER AKSESORIS ===
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        $areaSlug = $item->area ? $item->area->slug : '';
        $qty = 0;
        
        if ($areaSlug == 'profile-frame-vertikal') {
            $qty = $batangFrameVertikalQty;
        } elseif ($areaSlug == 'profile-frame-horizontal') {
            $qty = $batangFrameHorizontalQty;
        } elseif ($areaSlug == 'reinforcement') {
            $qty = $reinforcementQty;
        } elseif ($areaSlug == 'reinforcement-sash') {
            $qty = $reinforcementSashQty;
        } elseif ($areaSlug == 'kaca') {
            $qty = $luasKacaTotal;
        } elseif ($areaSlug == 'screw-reinforcement') {
            $qty = $screwQty;
        } elseif ($areaSlug == 'setting-block') {
            $qty = $settingBlockQty;
        } elseif ($areaSlug == 'profile-sash-vertikal') {
            $qty = $batangSashVertikalQty;
        } elseif ($areaSlug == 'profile-sash-horizontal') {
            $qty = $batangSashHorizontalQty;
        } elseif ($areaSlug == 'profile-glaze-vertikal') {
            $qty = $batangGlazeVertikalQty;
        } elseif ($areaSlug == 'profile-glaze-horizontal') {
            $qty = $batangGlazeHorizontalQty;
        } elseif ($areaSlug == 'profile-interlock-vertikal') {
            $qty = $batangInterlockQty;
        } elseif ($areaSlug == 'profile-sash-join-vertikal') {
            $qty = $batangSashJoinVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-vertikal') {
            $qty = $batangDecorationVertikalQty;
        } elseif ($areaSlug == 'profile-decoration-horizontal') {
            $qty = $batangDecorationHorizontalQty;
        } elseif ($areaSlug == 'profile-alumunium-track') {
            $qty = $aluminiumTrackQty;
        } elseif ($areaSlug == 'mohair') {
            $qty = $mohairQty;
        } elseif ($areaSlug == 'door-handle') {
            $qty = $item->qty ?? 0;
        } elseif ($areaSlug == 'door-hardware-lock') {
            $qty = ($lockItem && $lockItem->id == $item->id) ? $totalLockQty : 0;
        } elseif ($areaSlug == 'window-hardware-roller') {
            $qty = ($rollerItem && $rollerItem->id == $item->id) ? $totalRollerQty : 0;
        } elseif ($areaSlug == 'window-hardware-lifting') {
            $qty = ($liftingItem && $liftingItem->id == $item->id) ? $totalLiftingQty : 0;
        } elseif ($areaSlug == 'window-hardware-strike') {
            $qty = ($strikeItem && $strikeItem->id == $item->id) ? $totalStrikeQty : 0;
        } elseif ($areaSlug == 'sliding-stopper') {
            $qty = ($stopperItem && $stopperItem->id == $item->id) ? $totalStopperQty : 0;
        } elseif ($areaSlug == 'screw-door-roller') {
            $qty = ($screwRollerItem && $screwRollerItem->id == $item->id) ? $screwRollerQty : 0;
        } else {
            $qty = $jumlah * ($item->satuan_terkecil ?? 1);
        }
        
        $item->qty = $qty;
    }

    // Kelompokkan berdasarkan area untuk PDF
    $grouped = [];
    for ($i = 0; $i < $aksesorisCount; $i++) {
        $item = $aksesoris[$i];
        if ($item->qty <= 0) {
            continue;
        }
        
        $areaSlug = $item->area ? $item->area->slug : 'lainnya';
        
        if (str_starts_with($areaSlug, 'profile')) {
            $groupKey = 'profile';
        } elseif ($areaSlug == 'setting-block' || $areaSlug == 'kaca' || $areaSlug == 'mohair') {
            $groupKey = 'kaca';
        } elseif ($areaSlug == 'reinforcement' || $areaSlug == 'reinforcement-sash') {
            $groupKey = 'reinforcement';
        } elseif (str_starts_with($areaSlug, 'screw')) {
            $groupKey = 'screw';
        } elseif (str_starts_with($areaSlug, 'window-hardware-') || $areaSlug == 'door-handle' || $areaSlug == 'door-hardware-lock' || $areaSlug == 'sliding-stopper') {
            $groupKey = 'hardware';
        } else {
            $groupKey = $areaSlug;
        }
        
        if (!isset($grouped[$groupKey])) {
            $grouped[$groupKey] = [];
        }
        $grouped[$groupKey][] = $item;
    }

    $areaLabels = [
        'profile' => 'PROFILE',
        'reinforcement' => 'REINFORCEMENT',
        'kaca' => 'KACA',
        'hardware' => 'HARDWARE',
        'screw' => 'SCREW',
    ];

    // ============ GENERATE NOMOR BOQ ============
    $nomorBoq = Boq::generateNomorBoq();

    // ============ STORE BOQ ============
    try {
        $allResults = [];
        
        for ($i = 0; $i < $aksesorisCount; $i++) {
            $item = $aksesoris[$i];
            if ($item->qty > 0) {
                $allResults[] = [
                    'produk_id' => $item->id,
                    'qty' => $item->qty,
                    'nama_produk' => $item->nama_produk,
                ];
            }
        }
        
        $uniqueResults = [];
        $seenIds = [];
        $allResultsCount = count($allResults);
        
        for ($i = 0; $i < $allResultsCount; $i++) {
            $item = $allResults[$i];
            $produkId = $item['produk_id'] ?? null;
            
            if (!$produkId) {
                continue;
            }
            
            $isDuplicate = false;
            $seenCount = count($seenIds);
            for ($j = 0; $j < $seenCount; $j++) {
                if ($seenIds[$j] == $produkId) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                continue;
            }
            
            $seenIds[] = $produkId;
            $uniqueResults[] = $item;
        }
        
        if (count($uniqueResults) > 0) {
            $boq = new Boq();
            $boq->nomor_boq = $nomorBoq;
            $boq->tanggal_boq = now();
            $boq->save();
            
            $uniqueCount = count($uniqueResults);
            for ($i = 0; $i < $uniqueCount; $i++) {
                $item = $uniqueResults[$i];
                $produkId = $item['produk_id'] ?? null;
                $qty = (int)($item['qty'] ?? 0);
                
                if ($produkId && $qty > 0) {
                    $produk = Product::find($produkId);
                    
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
            
            \Log::info('BOQ SAVED PINTU SLIDING 4:', [
                'boq_id' => $boq->id,
                'nomor_boq' => $nomorBoq,
                'total' => count($uniqueResults)
            ]);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error saving BOQ Pintu Sliding 4: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    $data = [
        'judul' => $judul,
        'tinggi' => $tinggi,
        'lebar' => $lebar,
        'tebal_kaca' => $tebal_kaca,
        'jumlah' => $jumlah,
        'warna' => $warna,
        'type_kaca' => $type_kaca,
        'luas_kaca_total' => $luasKacaTotal,
        'keliling_total' => $kelilingTotal,
        'batang_frame_vertikal_qty' => $batangFrameVertikalQty,
        'batang_frame_horizontal_qty' => $batangFrameHorizontalQty,
        'reinforcement_qty' => $reinforcementQty,
        'reinforcement_sash_qty' => $reinforcementSashQty,
        'batang_sash_vertikal_qty' => $batangSashVertikalQty,
        'batang_sash_horizontal_qty' => $batangSashHorizontalQty,
        'batang_glaze_vertikal_qty' => $batangGlazeVertikalQty,
        'batang_glaze_horizontal_qty' => $batangGlazeHorizontalQty,
        'batang_interlock_qty' => $batangInterlockQty,
        'batang_sash_join_vertikal_qty' => $batangSashJoinVertikalQty,
        'aluminium_track_qty' => $aluminiumTrackQty,
        'mohair_qty' => $mohairQty,
        'total_door_handle_qty' => $totalDoorHandleQty,
        'total_lock_qty' => $totalLockQty,
        'total_roller_qty' => $totalRollerQty,
        'total_lifting_qty' => $totalLiftingQty,
        'total_strike_qty' => $totalStrikeQty,
        'total_stopper_qty' => $totalStopperQty,
        'screw_roller_qty' => $screwRollerQty,
        'total_decoration_bar_qty' => $totalDecorationBarQty,
        'screw_qty' => $screwQty,
        'setting_block_qty' => $settingBlockQty,
        'grouped' => $grouped,
        'areaLabels' => $areaLabels,
        'nomor_boq' => $nomorBoq,
        'decoration_bar_vertikal' => $decorationBarVertikalInput,
        'decoration_bar_horizontal' => $decorationBarHorizontalInput,
        'door_handle_items' => $doorHandleItems,
    ];

    // IKUTIN CONTOH - RETURN VIEW (bukan download)
    return view('boq.pintu.pdf-pintu-sliding-4', compact('data'));
}
}