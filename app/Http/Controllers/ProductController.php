<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->paginate(5);

        return view('content.general.general-product', compact([
            'products',
        ]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required|string',
            'images' => 'required|image|file|max:5120|mimes:jpeg,png,jpg',
        ]);

        $sanitasi = Purifier::clean($request->description);
        if (empty(strip_tags($sanitasi))) {
            return back()->withErrors(['Description' => 'Deskripsi mengandung elemen tidak valid atau kosong.'])->withInput();
        }
        $validatedData['description'] = $sanitasi;

        $validatedData['images'] = $request->file('images') 
            ? $request->file('images')->store('product-images', 'public') 
            : null;

        Product::create($validatedData);

        return redirect()->back()->with('success', 'Data Produk Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required|string',
            'images' => 'required|image|file|max:5120|mimes:jpeg,png,jpg',
        ]);

        $sanitasi = Purifier::clean($request->description);
        if (empty(strip_tags($sanitasi))) {
            return back()->withErrors(['Description' => 'Deskripsi mengandung elemen tidak valid atau kosong.'])->withInput();
        }
        $validatedData['description'] = $sanitasi;

        if($request->file('images')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['images'] = $request->file('images') 
            ? $request->file('images')->store('product-images', 'public') 
            : null;
        }

        $product->update($validatedData);
        return redirect()->back()->with('success', 'Data Produk Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $deletedProduct = Product::findOrFail($id);
        $deletedProduct->delete();

        return redirect()->back()->with('success', 'Data Produk Berhasil Dihapus');
    }
}
