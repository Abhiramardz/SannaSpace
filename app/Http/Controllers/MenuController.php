<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Tambahkan ini
use App\Models\Product;      // Pastikan modelnya Product
use App\Models\Category;

class MenuController extends Controller
{
    // Fungsi untuk halaman menu customer
    public function index()
    {
        $products = Product::all();
        $categories = Category::orderBy('name')->get();
        $productsByCategory = $products->groupBy('category');
        return view('customer.halamanmenu', compact('products', 'categories', 'productsByCategory'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('customer.detailmenu', compact('product'));
    }


}