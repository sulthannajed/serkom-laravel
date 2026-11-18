<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_dapat_diakses(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk();
        $response->assertSee('Kopi Ulee Kareng');
    }

    public function test_produk_valid_dapat_disimpan(): void
    {
        $response = $this->post(route('products.store'), [
            'nama_produk' => 'Kopi Test',
            'deskripsi' => 'Produk untuk pengujian.',
            'harga' => 20000,
            'stok' => 10,
            'gambar' => null,
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'nama_produk' => 'Kopi Test',
            'harga' => 20000,
            'stok' => 10,
        ]);
    }

    public function test_harga_harus_bilangan_bulat(): void
    {
        $response = $this->post(route('products.store'), [
            'nama_produk' => 'Kopi Tidak Valid',
            'deskripsi' => 'Data uji tidak valid.',
            'harga' => 'dua puluh ribu',
            'stok' => 5,
        ]);

        $response->assertSessionHasErrors(['harga']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_nama_produk_harus_unik(): void
    {
        Product::create([
            'nama_produk' => 'Kopi Aceh',
            'deskripsi' => 'Kopi khas Aceh.',
            'harga' => 15000,
            'stok' => 10,
        ]);

        $response = $this->post(route('products.store'), [
            'nama_produk' => 'Kopi Aceh',
            'deskripsi' => 'Mencoba input nama yang sama.',
            'harga' => 20000,
            'stok' => 5,
        ]);

        $response->assertSessionHasErrors(['nama_produk']);
        $this->assertDatabaseCount('products', 1);
    }

    public function test_gambar_dapat_diupload(): void
    {
        $file = UploadedFile::fake()->create('kopi-baru.jpg', 100, 'image/jpeg');

        $response = $this->post(route('products.store'), [
            'nama_produk' => 'Kopi Gambar',
            'deskripsi' => 'Deskripsi produk bergambar.',
            'harga' => 25000,
            'stok' => 8,
            'gambar' => $file,
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('nama_produk', 'Kopi Gambar')->first();
        $this->assertNotNull($product->gambar);
        $this->assertFileExists(public_path('images/' . $product->gambar));

        if (file_exists(public_path('images/' . $product->gambar))) {
            @unlink(public_path('images/' . $product->gambar));
        }
    }

    public function test_file_gambar_tidak_valid_ditolak(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $response = $this->post(route('products.store'), [
            'nama_produk' => 'Kopi Gagal Gambar',
            'deskripsi' => 'Deskripsi...',
            'harga' => 20000,
            'stok' => 5,
            'gambar' => $file,
        ]);

        $response->assertSessionHasErrors(['gambar']);
    }
}