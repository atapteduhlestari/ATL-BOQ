<?php
// database/seeders/ProductBrandSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductBrand;

class ProductBrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['nama_brand' => 'IKO - ATAP', 'slug' => 'iko-atap'],
            ['nama_brand' => 'PALMEX', 'slug' => 'palmex'],
            ['nama_brand' => 'DUO', 'slug' => 'duo'],
            ['nama_brand' => 'SAGITTA', 'slug' => 'sagitta'],
            ['nama_brand' => 'SOPRASUN', 'slug' => 'soprasun'],
        ];
        
        foreach ($brands as $brand) {
            ProductBrand::create($brand);
        }
    }
}