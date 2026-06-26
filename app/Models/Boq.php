<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boq extends Model
{
    use HasFactory;

    protected $table = 'boq'; // <-- TAMBAHKAN INI

    protected $fillable = [
        'nomor_boq',
        'tanggal_boq'
    ];

    public function details()
    {
        return $this->hasMany(DetailBoq::class);
    }

    public static function generateNomorBoq()
{
    $bulan = date('n'); // 1-12
    $tahun = date('Y');
    
    // Konversi bulan ke Romawi
    $bulanRomawi = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];
    
    $bulanRomawiString = $bulanRomawi[$bulan];
    
    // Hitung jumlah BOQ di bulan dan tahun ini
    $last = self::whereYear('created_at', $tahun)
        ->whereMonth('created_at', $bulan)
        ->count() + 1;
    
    // Format: ATL/BOQ/BULAN_ROMAWI/TAHUN/NOMOR_URUT
    return 'ATL/BOQ/' . $bulanRomawiString . '/' . $tahun . '/' . str_pad($last, 4, '0', STR_PAD_LEFT);
}
}