<?php
// app/Models/ProductCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    
    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'is_active'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_id');
    }
}