<?php
// database/seeders/ProductAreaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductArea;

class ProductAreaSeeder extends Seeder
{
    public function run()
    {
        $areas = ['Atap Utama', 'Underlayer', 'Starter', 'Nok & Jurai', 'Flashing'];
        
        foreach ($areas as $area) {
            ProductArea::create([
                'nama_area' => $area,
                'slug' => str()->slug($area),
            ]);
        }
    }
}