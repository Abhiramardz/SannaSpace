<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $sort = $request->get('sort', 'name'); 
        $direction = $request->get('direction', 'asc');

        $menus = Product::query()
            ->when($search, function($query, $search) {
               return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy($sort, $direction)
            ->get();

        $categories = Category::orderBy('name')->get();
        return view('superadmin.manajemen menu.manajemenmenu', compact('menus', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'cost_price'  => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'cost_price'  => $request->cost_price,
            'category'    => $request->category,
            'image'       => $imagePath,
        ]);

        return redirect('/superadmin/menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('superadmin.manajemen menu.editmenu', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'cost_price'  => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->cost_price = $request->cost_price;
        $product->category = $request->category;
        $product->save();

        return redirect('/superadmin/menu')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus file gambar jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/superadmin/menu')->with('success', 'Menu berhasil dihapus!');
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'stock' => 'required|integer'
        ]);

        $product->stock = $request->stock;
        $product->save();

        return back()->with('success', 'Stok ' . $product->name . ' berhasil diperbarui!');
    }

    public function tambahmenu() {
        $categories = Category::orderBy('name')->get();
        return view('superadmin.manajemen menu.tambahmenu', compact('categories'));
    }
}
