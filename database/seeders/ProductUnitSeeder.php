<?php
// database/seeders/ProductUnitSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductUnit;

class ProductUnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['unit_name' => 'Bundle', 'symbol' => 'bdl'],
            ['unit_name' => 'Meter', 'symbol' => 'm'],
            ['unit_name' => 'Pcs', 'symbol' => 'pcs'],
            ['unit_name' => 'Box', 'symbol' => 'box'],
            ['unit_name' => 'Roll', 'symbol' => 'roll'],
            ['unit_name' => 'Tube', 'symbol' => 'tube'],
            ['unit_name' => 'Meter Persegi', 'symbol' => 'm²'],
            ['unit_name' => 'Liter', 'symbol' => 'L'],
            ['unit_name' => 'Kg', 'symbol' => 'kg'],
            ['unit_name' => 'Pack', 'symbol' => 'pack'],
        ];
        
        foreach ($units as $unit) {
            ProductUnit::create($unit);
        }
    }
}