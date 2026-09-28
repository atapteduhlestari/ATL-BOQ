<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\ProductArea;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $brandId    = $request->filled('brand_id')    ? (int) $request->brand_id    : null;
        $kategoriId = $request->filled('kategori_id') ? (int) $request->kategori_id : null;
        $search     = $request->filled('search')      ? $request->search            : null;

        $produk = Product::query()
            ->with(['brand', 'kategori', 'unit'])
            ->where('tipe_produk', 'main')
            ->when($brandId, fn ($q) => $q->where('brand_id', $brandId))
            ->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('nama_produk', 'ILIKE', "%{$search}%")
                      ->orWhere('kode_produk', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('nama_produk')
            ->paginate(10)
            ->withQueryString();

        $brands    = ProductBrand::orderBy('nama_brand')->get();
        $kategoris = ProductCategory::orderBy('category_name')->get();

        return view('admin.produk.index', compact(
            'produk', 'brands', 'kategoris', 'brandId', 'kategoriId', 'search'
        ));
    }
public function create()
{
    $kategoris = ProductCategory::orderBy('category_name')->get();
    $units     = ProductUnit::orderBy('unit_name')->get();
    $brands    = ProductBrand::orderBy('nama_brand')->get();
    $areas     = ProductArea::orderBy('nama_area')->get();

    // SEMUA produk (main & aksesoris) bisa dipilih sebagai aksesoris
    $produkMain = Product::with('brand')
        ->orderBy('nama_produk')
        ->get(['id', 'kode_produk', 'nama_produk', 'tipe_produk', 'brand_id']);

    return view('admin.produk.create', compact(
        'kategoris', 'units', 'brands', 'areas', 'produkMain'
    ));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'kode_produk'       => 'required|string|max:255|unique:products,kode_produk',
        'nama_produk'       => 'required|string|max:255',
        'kategori_id'       => 'required|exists:product_categories,id',
        'unit_id'           => 'required|exists:product_units,id',
        'tipe_produk'       => 'required|in:main,aksesoris',
        'hpp_produk'        => 'nullable|numeric',
        'harga_price_list'  => 'nullable|numeric',
        'area_id'           => 'required|exists:product_areas,id',
        'satuan_terkecil'   => 'required|numeric',
        'brand_id'          => 'required|exists:product_brands,id',
        'aksesoris'         => 'nullable|array',
        'aksesoris.*'       => 'exists:products,id',
    ]);

    $produk = Product::create([
        'kode_produk'      => $validated['kode_produk'],
        'nama_produk'      => $validated['nama_produk'],
        'kategori_id'      => $validated['kategori_id'],
        'unit_id'          => $validated['unit_id'],
        'tipe_produk'      => $validated['tipe_produk'],
        'hpp_produk'       => $validated['hpp_produk'] ?? null,
        'harga_price_list' => $validated['harga_price_list'] ?? null,
        'area_id'          => $validated['area_id'],
        'satuan_terkecil'  => $validated['satuan_terkecil'],
        'brand_id'         => $validated['brand_id'],
    ]);

    // Simpan relasi aksesoris (kalau tipe main & ada aksesoris yang dipilih)
    if ($produk->tipe_produk === 'main' && !empty($validated['aksesoris'])) {
        $produk->accessories()->sync($validated['aksesoris']);
    }

    return redirect()->route('admin.produk.index')
        ->with('success', 'Produk berhasil ditambahkan.');
}
public function show(string $id)
{
    $produk = Product::with(['brand', 'kategori', 'unit', 'area', 'accessories'])
        ->findOrFail($id);

    return view('admin.produk.show', compact('produk'));
}
public function edit(string $id)
{
    $produk = Product::with('accessories')->findOrFail($id);

    $kategoris = ProductCategory::orderBy('category_name')->get();
    $units     = ProductUnit::orderBy('unit_name')->get();
    $brands    = ProductBrand::orderBy('nama_brand')->get();
    $areas     = ProductArea::orderBy('nama_area')->get();

    // Semua produk kecuali dirinya sendiri
    $produkMain = Product::with('brand')
        ->where('id', '!=', $id)
        ->orderBy('nama_produk')
        ->get(['id', 'kode_produk', 'nama_produk', 'tipe_produk', 'brand_id']);

    // ID aksesoris yang sudah dipilih
    $aksesorisTerpilih = $produk->accessories->pluck('id')->toArray();

    return view('admin.produk.edit', compact(
        'produk',
        'kategoris',
        'units',
        'brands',
        'areas',
        'produkMain',
        'aksesorisTerpilih'
    ));
}

public function update(Request $request, string $id)
{
    $produk = Product::findOrFail($id);

    $validated = $request->validate([
        'kode_produk'       => 'required|string|max:255|unique:products,kode_produk,' . $produk->id,
        'nama_produk'       => 'required|string|max:255',
        'kategori_id'       => 'required|exists:product_categories,id',
        'unit_id'           => 'required|exists:product_units,id',
        'tipe_produk'       => 'required|in:main,aksesoris',
        'hpp_produk'        => 'nullable|numeric',
        'harga_price_list'  => 'nullable|numeric',
        'area_id'           => 'required|exists:product_areas,id',
        'satuan_terkecil'   => 'required|numeric',
        'brand_id'          => 'required|exists:product_brands,id',
        'aksesoris'         => 'nullable|array',
        'aksesoris.*'       => 'exists:products,id',
    ]);

    $produk->update([
        'kode_produk'      => $validated['kode_produk'],
        'nama_produk'      => $validated['nama_produk'],
        'kategori_id'      => $validated['kategori_id'],
        'unit_id'          => $validated['unit_id'],
        'tipe_produk'      => $validated['tipe_produk'],
        'hpp_produk'       => $validated['hpp_produk'] ?? null,
        'harga_price_list' => $validated['harga_price_list'] ?? null,
        'area_id'          => $validated['area_id'],
        'satuan_terkecil'  => $validated['satuan_terkecil'],
        'brand_id'         => $validated['brand_id'],
    ]);

    if ($produk->tipe_produk === 'main') {
        $produk->accessories()->sync($validated['aksesoris'] ?? []);
    } else {
        $produk->accessories()->sync([]);
    }

    return redirect()->route('admin.produk.show', $produk->id)
        ->with('success', 'Produk berhasil diperbarui.');
}
public function destroy(string $id)
{
    $produk = Product::findOrFail($id);

    // Hapus relasi aksesoris dulu (biar gak ada sisa di pivot)
    $produk->accessories()->detach();

    // Hapus juga kalau produk ini dipakai sebagai aksesoris oleh produk lain
    $produk->parentProducts()->detach();

    $produk->delete();

    return redirect()->route('admin.produk.index')
        ->with('success', 'Produk berhasil dihapus.');
}
}