<?php
// app/Models/ProductArea.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductArea extends Model
{
    protected $table = 'product_areas';
    
    protected $fillable = [
        'nama_area',
        'slug'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'area_id');
    }
}