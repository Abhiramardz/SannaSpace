<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('superadmin.manajemen_kategori.manajemenkategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        Category::create(['name' => $request->name]);

        return redirect('/superadmin/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('/superadmin/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}
