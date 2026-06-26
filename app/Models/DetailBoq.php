<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBoq extends Model
{
     protected $table = 'detail_boq';
     
    use HasFactory;

    protected $fillable = [
        'boq_id',
        'produk_id',
        'kode_produk',
        'qty'
    ];

    protected $casts = [
        'qty' => 'decimal:2'
    ];

    public function boq()
    {
        return $this->belongsTo(Boq::class);
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
}