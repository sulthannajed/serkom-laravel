<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function landing()
    {
        $products = Product::latest()->get();

        return view ('landing', compact('products'));
    }

    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100|unique:products,nama_produk',
            'deskripsi' => 'required|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_produk.unique' => 'Nama produk sudah digunakan.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['gambar'] = $filename;
        } else {
            $validated['gambar'] = null;
        }

        Product::query()->create($validated);

        return redirect()
        ->route('products.index')
        ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $products)
    {
        return view('products.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $products): RedirectResponse 
    {
        $validated = $request->validate(
            [
                'nama_produk' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('products', 'nama_produk')->ignore($products->id),
                ],
                'deskripsi' => 'required|string',
                'harga' => 'required|integer|min:0',
                'stok' => 'required|integer|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            [
                'nama_produk.unique' => 'Nama produk sudah digunakan.',
                'gambar.image' => 'File yang diunggah harus berupa gambar.',
                'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
                'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        if ($request->hasFile('gambar')) {
            $defaultImages = ['kopi-default.jpg', 'americano.jpg', 'espresso.jpg', 'sanger.jpg'];
            if ($products->gambar && !in_array($products->gambar, $defaultImages) && file_exists(public_path('images/' . $products->gambar))) {
                @unlink(public_path('images/' . $products->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['gambar'] = $filename;
        } else {
            unset($validated['gambar']);
        }

        $products->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $products): RedirectResponse
    {
        $defaultImages = ['kopi-default.jpg', 'americano.jpg', 'espresso.jpg', 'sanger.jpg'];
        if ($products->gambar && !in_array($products->gambar, $defaultImages) && file_exists(public_path('images/' . $products->gambar))) {
            @unlink(public_path('images/' . $products->gambar));
        }

        $products->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
