<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SystemUserController extends Controller
{
    /**
     * Show Users and User Groups administration panel.
     */
    public function index()
    {
        $users = User::orderBy('username', 'asc')->paginate(15);
        $groups = UserGroup::orderBy('group_name', 'asc')->get();

        return view('admin.system.users.index', compact('users', 'groups'));
    }

    /**
     * Store new librarian user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:user,username',
            'realname' => 'required|string|max:100',
            'email' => 'nullable|email|max:200',
            'password' => 'required|string|min:6',
            'groups' => 'required|array',
        ]);

        User::create([
            'username' => $request->username,
            'realname' => $request->realname,
            'email' => $request->email,
            'passwd' => Hash::make($request->password),
            'groups' => serialize($request->groups),
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Akun pustakawan baru berhasil dibuat.');
    }

    /**
     * Update existing librarian user.
     */
    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'username' => 'required|string|max:50|unique:user,username,' . $user_id . ',user_id',
            'realname' => 'required|string|max:100',
            'email' => 'nullable|email|max:200',
            'password' => 'nullable|string|min:6',
            'groups' => 'required|array',
        ]);

        $data = [
            'username' => $request->username,
            'realname' => $request->realname,
            'email' => $request->email,
            'groups' => serialize($request->groups),
            'last_update' => now()->toDateString(),
        ];

        if ($request->filled('password')) {
            $data['passwd'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Akun pustakawan berhasil diperbarui.');
    }

    /**
     * Delete librarian user.
     */
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        
        // Prevent deleting last admin
        if ($user->username === 'admin') {
            return redirect()->back()->with('error', 'Akun admin utama tidak boleh dihapus.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pustakawan berhasil dihapus.');
    }

    /**
     * Store new User Group.
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:30|unique:user_group,group_name',
        ]);

        UserGroup::create([
            'group_name' => $request->group_name,
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Kelompok pengguna baru berhasil dibuat.');
    }

    /**
     * Delete User Group.
     */
    public function destroyGroup($group_id)
    {
        $group = UserGroup::findOrFail($group_id);
        $group->delete();

        return redirect()->back()->with('success', 'Kelompok pengguna berhasil dihapus.');
    }
}
