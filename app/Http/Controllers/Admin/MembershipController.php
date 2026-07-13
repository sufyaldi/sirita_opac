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
use App\Models\Member;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->has('search')) {
            $query->where('member_name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%');
        }

        $members = $query->paginate(15);

        return view('admin.membership.index', compact('members'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('admin.membership.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|string|max:20|unique:member,member_id',
            'member_name' => 'required|string|max:100',
            'gender' => 'required|integer|in:0,1',
            'member_email' => 'nullable|email|max:100',
            'member_address' => 'nullable|string|max:255',
            'member_phone' => 'nullable|string|max:50',
            'expire_date' => 'required|date',
        ]);

        Member::create(array_merge($validated, [
            'register_date' => now()->toDateString(),
            'member_since_date' => now()->toDateString(),
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]));

        return redirect()->route('admin.membership.index')
                         ->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('admin.membership.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'member_name' => 'required|string|max:100',
            'gender' => 'required|integer|in:0,1',
            'member_email' => 'nullable|email|max:100',
            'member_address' => 'nullable|string|max:255',
            'member_phone' => 'nullable|string|max:50',
            'expire_date' => 'required|date',
        ]);

        $member->update(array_merge($validated, [
            'last_update' => now()->toDateString(),
        ]));

        return redirect()->route('admin.membership.index')
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.membership.index')
                         ->with('success', 'Anggota berhasil dihapus.');
    }
}
