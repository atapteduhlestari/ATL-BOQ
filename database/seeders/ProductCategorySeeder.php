<?php
// database/seeders/ProductCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Atap', 'Insulasi', 'Dak Beton', 'Rangka', 'Jendela', 'Pintu',
            'Kusen', 'Aksesoris Pintu & Pendela', 'Dinding', 'Plafon', 'Tangga',
            'Pagar', 'Rumah Prefab', 'Solar Roof', 'Solar Panel', 'Cabel Tray',
            'Roofing', 'Profil', 'Hardware', 'Walling', 'Ceiling', 'Reinforcement',
            'Talang', 'Waterprofing', 'Draincell', 'Membrane'
        ];
        
        foreach ($categories as $category) {
            ProductCategory::create([
                'category_name' => $category,
                'slug' => str()->slug($category),
                'is_active' => true
            ]);
        }
    }
}