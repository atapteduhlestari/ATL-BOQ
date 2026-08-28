<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTipe extends Model
{
    protected $table = 'product_tipe';
    
    protected $fillable = [
        'nama_tipe',
    ];
    
    // Relasi ke Products
    public function products()
    {
        return $this->hasMany(Product::class, 'product_tipe_id');
    }
}