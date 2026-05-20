<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperadminUserController extends Controller
{
    public function index(Request $request)
{
    // 1. Tangkap parameter input dari URL
    $search = $request->get('search');
    $role = $request->get('role');
    $sort = $request->get('sort', 'name'); 
    $direction = $request->get('direction', 'asc');

    // 2. Validasi kolom sorting demi keamanan SQL Injection
    $allowedSorts = ['name', 'role', 'email'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'name';
    }
    $direction = ($direction === 'desc') ? 'desc' : 'asc';

    // 3. Query pencarian & filter data user
    $users = User::query()
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->when($role, function($query, $role) {
            return $query->where('role', $role);
        })
        ->orderBy($sort, $direction)
        ->get();

    // 4. Return ke view manajemen user
    return view('superadmin.manajemen user.manajemenuser', compact('users'));
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,kasir,customer',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/superadmin/user')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Hanya superadmin dan kasir yang bisa diedit
        if ($user->role === 'customer') {
            return redirect()->back()->with('error', 'Akun customer tidak dapat diubah oleh superadmin.');
        }

        return view('superadmin.manajemen user.edituser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'customer') {
            return redirect()->back()->with('error', 'Akun customer tidak dapat diubah.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,kasir,customer',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // PERBAIKAN DI SINI: Jangan gunakan Hash::make() karena sudah di-hash otomatis oleh Model
        if ($request->filled('password')) {
            $user->password = $request->password; 
        }

        $user->save();

        return redirect('/superadmin/user')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'customer') {
            return redirect()->back()->with('error', 'Akun customer tidak dapat dihapus.');
        }

        // Jangan hapus diri sendiri (opsional tapi bagus)
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }

    public function tambahuser()
    {
        return view('superadmin.manajemen user.tambahuser'); 
    }
}
