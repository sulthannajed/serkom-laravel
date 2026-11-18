<div class="form-group">
    <label for="nama_produk">Nama produk</label>
    <input
        id="nama_produk"
        name="nama_produk"
        type="text"
        value="{{ old('nama_produk', $products->nama_produk ?? '') }}"
        class="form-control @error('nama_produk') is-invalid @enderror"
    >
    @error('nama_produk')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="deskripsi">Deskripsi</label>
    <textarea
        id="deskripsi"
        name="deskripsi"
        rows="5"
        class="form-control @error('deskripsi') is-invalid @enderror"
    >{{ old('deskripsi', $products->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="harga">Harga</label>
    <input
        id="harga"
        name="harga"
        type="number"
        min="0"
        value="{{ old('harga', $products->harga ?? '') }}"
        class="form-control @error('harga') is-invalid @enderror"
    >
    @error('harga')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="stok">Stok</label>
    <input
        id="stok"
        name="stok"
        type="number"
        min="0"
        value="{{ old('stok', $products->stok ?? '') }}"
        class="form-control @error('stok') is-invalid @enderror"
    >
    @error('stok')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="gambar">Gambar Produk (opsional)</label>
    @if (!empty($products->gambar))
        <div style="margin-bottom: 8px;">
            <img
                src="{{ asset('images/' . $products->gambar) }}"
                alt="{{ $products->nama_produk }}"
                style="max-width: 120px; max-height: 120px; border-radius: 6px; object-fit: cover; display: block; margin-bottom: 4px;"
            >
            <small style="color: #666;">Gambar saat ini: {{ $products->gambar }}</small>
        </div>
    @endif
    <input
        id="gambar"
        name="gambar"
        type="file"
        accept="image/jpeg,image/png,image/jpg,image/webp"
        class="form-control @error('gambar') is-invalid @enderror"
    >
    @error('gambar')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-actions">
    <button class="btn" type="submit">{{ $tombol }}</button>
    <a class="btn btn-secondary" href="{{ route('products.index') }}">
        Batal
    </a>
</div>