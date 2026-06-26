<?php
// app/Models/ProductAccessories.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAccessories extends Model
{
    protected $table = 'product_accessories';
    
    protected $fillable = [
        'parent_product_id',
        'accessory_id',
    ];
    
    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }
    
    public function accessory()
    {
        return $this->belongsTo(Product::class, 'accessory_id');
    }
}