<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['code' => 'fenykep_10x15', 'name' => 'Fénykép - 10x15', 'price' => 2.5],
            ['code' => 'fenykep_a4', 'name' => 'Fénykép - A4', 'price' => 10],
            ['code' => 'fenykep_a3', 'name' => 'Fénykép - A3', 'price' => 25],
            ['code' => 'vaszon_20x30', 'name' => 'Vászonkép - 20x30', 'price' => 60],
            ['code' => 'vaszon_30x40', 'name' => 'Vászonkép - 30x40', 'price' => 85],
            ['code' => 'vaszon_40x60', 'name' => 'Vászonkép - 40x60', 'price' => 135],
            ['code' => 'bogre', 'name' => 'Fényképes bögre', 'price' => 30],
            ['code' => 'kepkeret', 'name' => 'Képkeret', 'price' => 25],
            ['code' => 'udvozlokartya', 'name' => 'Üdvözlőkártya', 'price' => 6],
            ['code' => 'fa_hutomagnes', 'name' => 'Fa hűtőmágnes', 'price' => 10],
            ['code' => 'fenykepes_hutomagnes', 'name' => 'Fényképes hűtőmágnes 10x15', 'price' => 8],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
