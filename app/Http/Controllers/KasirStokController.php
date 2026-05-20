<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;

class KasirStokController extends Controller
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
        
        return view('kasir.manajemen_stok.stokkasir', compact('bahanBakuList'));
    }

    public function updateStock(Request $request, $id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update([
            'stock' => $request->stock
        ]);

        return redirect()->route('kasir.stok.index')->with('success', 'Stok ' . $bahanBaku->name . ' berhasil diperbarui.');
    }
}