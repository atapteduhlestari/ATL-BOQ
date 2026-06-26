<?php
// app/Models/ProductBrand.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $table = 'product_brands';
    
    protected $fillable = [
        'nama_brand',
        'slug',
        'logo'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}