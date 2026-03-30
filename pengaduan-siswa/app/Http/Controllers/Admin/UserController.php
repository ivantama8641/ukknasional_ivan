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
        $role = $request->get('role', 'siswa'); // filter by role if provided, defaults to siswa
        
        $users = User::where('id', '!=', auth()->id())
                     ->where('role', 'like', "%$role%")
                     ->latest()
                     ->paginate(15);
                     
        return view('admin.users.index', compact('users', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,guru,siswa',
            'nis_nip' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,guru,siswa',
            'nis_nip' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ];

        // Only validate password if the admin tries to change it
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8';
        }

        $request->validate($rules);

        $data = $request->except('password');
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->has('is_active');

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->complaints()->count() > 0 || $user->handledComplaints()->count() > 0) {
            // Soft deactivate if related records exist
            $user->update(['is_active' => false]);
            return redirect()->back()->with('warning', 'Pengguna tidak bisa dihapus sepenuhnya karena memiliki riwayat aduan. Akun telah dinonaktifkan.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus Permanen.');
    }
}
