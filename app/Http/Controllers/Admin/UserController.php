<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function show($id)
    {
        $user   = User::withCount('orders')->findOrFail($id);
        $orders = $user->orders()->with('pembayaran')->latest('created_at')->limit(5)->get();
        return view('admin.user.show', compact('user', 'orders'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'required|email|unique:users,email,' . $id . ',id_user',
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role'   => 'required|in:admin,pelanggan',
        ]);

        $data = $request->only('nama', 'email', 'no_hp', 'alamat', 'role');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
