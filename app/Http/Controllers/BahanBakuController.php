<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BahanBaku;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $allowedSorts = ['name', 'stock', 'unit'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $bahanBakuList = $query->get();

        return view('superadmin.manajemen_stok.manajemenstok', compact('bahanBakuList'));
    }

    public function create()
    {
        return view('superadmin.manajemen_stok.tambahstok');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
        ]);

        BahanBaku::create($request->all());

        return redirect()->route('superadmin.stok.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('superadmin.manajemen_stok.editstok', compact('bahanBaku'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update($request->all());

        return redirect()->route('superadmin.stok.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function updateStock(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update([
            'stock' => $request->stock
        ]);

        return redirect()->route('superadmin.stok.index')->with('success', 'Stok bahan baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();
        return redirect()->route('superadmin.stok.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}
