<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Americano',
                'deskripsi' => 'Kopi hitam autentik dengan rasa yang kuat dan aroma yang khas.',
                'harga' => 10000,
                'stok' => 100,
                'gambar' => 'americano.jpg',
            ],
            [
                'nama_produk' => 'Sanger',
                'deskripsi' => 'Kopi susu khas Aceh dengan cita rasa yang kaya dan lembut.',
                'harga' => 8000,
                'stok' => 100,
                'gambar' => 'sanger.jpg',
            ],
            [
                'nama_produk' => 'Espresso',
                'deskripsi' => 'Kopi hitam dengan rasa pekat dan aroma yang kuat, disajikan dalam porsi kecil.',
                'harga' => 17000,
                'stok' => 100,
                'gambar' => 'espresso.jpg',
            ],
        ];

        foreach ($products as $product ) {
            Product::query()->create($product);
        }
    }
}
