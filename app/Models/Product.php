<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';
    
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'slug',
        'kategori_id',
        'unit_id',
        'tipe_produk',
        'hpp_produk',
        'harga_price_list',
        'area_id',
        'satuan_terkecil',
        'brand_id',
        'description',
        'image',
        'stock',
        'min_stock',
        'is_active'
    ];
    
    protected $casts = [
        'hpp_produk' => 'decimal:2',
        'harga_price_list' => 'decimal:2',
        'satuan_terkecil' => 'decimal:2',
        'is_active' => 'boolean'
    ];
    
    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'kategori_id');
    }
    
    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }
    
    public function area(): BelongsTo
    {
        return $this->belongsTo(ProductArea::class, 'area_id');
    }
    
    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }
    
  public function accessories()
{
    return $this->belongsToMany(Product::class, 'product_accessories', 'parent_product_id', 'accessory_id');
}
    
    public function parentProducts()
    {
        return $this->belongsToMany(Product::class, 'product_accessories', 'accessory_id', 'parent_product_id');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('kategori_id', $categoryId);
    }
    
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }
}