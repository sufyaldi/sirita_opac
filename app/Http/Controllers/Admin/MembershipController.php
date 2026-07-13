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
use App\Models\MemberType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Display a listing of members with filters.
     */
    public function index(Request $request)
    {
        $query = Member::with('memberType');

        // Search by name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_name', 'like', "%{$search}%")
                  ->orWhere('member_id',   'like', "%{$search}%");
            });
        }

        // Filter by member type
        if ($request->filled('member_type_id')) {
            $query->where('member_type_id', $request->member_type_id);
        }

        // Filter: show only expired members
        if ($request->boolean('expired')) {
            $query->where('expire_date', '<', Carbon::today());
        }

        $members     = $query->orderBy('member_name')->paginate(20)->withQueryString();
        $memberTypes = MemberType::orderBy('member_type_name')->get();

        // Summary stats
        $totalMembers   = Member::count();
        $activeMembers  = Member::where('expire_date', '>=', Carbon::today())->count();
        $expiredMembers = Member::where('expire_date', '<',  Carbon::today())->count();

        return view('admin.membership.index', compact(
            'members', 'memberTypes', 'totalMembers', 'activeMembers', 'expiredMembers'
        ));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $memberTypes = MemberType::orderBy('member_type_name')->get();
        return view('admin.membership.create', compact('memberTypes'));
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'      => 'required|string|max:20|unique:member,member_id',
            'member_name'    => 'required|string|max:100',
            'gender'         => 'required|integer|in:0,1',
            'member_type_id' => 'required|integer|exists:mst_member_type,member_type_id',
            'member_email'   => 'nullable|email|max:100',
            'member_address' => 'nullable|string|max:255',
            'member_phone'   => 'nullable|string|max:50',
            'inst_name'      => 'nullable|string|max:100',
            'expire_date'    => 'required|date',
            'pin'            => 'required|string|min:4',
        ], [
            'pin.required' => 'PIN / Password wajib diisi untuk anggota baru.',
        ]);

        Member::create(array_merge($validated, [
            'mpasswd'          => md5($validated['pin']),
            'register_date'    => now()->toDateString(),
            'member_since_date'=> now()->toDateString(),
            'input_date'       => now()->toDateString(),
            'last_update'      => now()->toDateString(),
        ]));

        return redirect()->route('admin.membership.index')
                         ->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit($id)
    {
        $member      = Member::findOrFail($id);
        $memberTypes = MemberType::orderBy('member_type_name')->get();
        return view('admin.membership.edit', compact('member', 'memberTypes'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'member_name'    => 'required|string|max:100',
            'gender'         => 'required|integer|in:0,1',
            'member_type_id' => 'required|integer|exists:mst_member_type,member_type_id',
            'member_email'   => 'nullable|email|max:100',
            'member_address' => 'nullable|string|max:255',
            'member_phone'   => 'nullable|string|max:50',
            'inst_name'      => 'nullable|string|max:100',
            'expire_date'    => 'required|date',
            'pin'            => 'nullable|string|min:4',
        ]);

        $updateData = [
            'member_name'    => $validated['member_name'],
            'gender'         => $validated['gender'],
            'member_type_id' => $validated['member_type_id'],
            'member_email'   => $validated['member_email'],
            'member_address' => $validated['member_address'],
            'member_phone'   => $validated['member_phone'],
            'inst_name'      => $validated['inst_name'] ?? null,
            'expire_date'    => $validated['expire_date'],
            'last_update'    => now()->toDateString(),
        ];

        if (!empty($validated['pin'])) {
            $updateData['pin']    = $validated['pin'];
            $updateData['mpasswd']= md5($validated['pin']);
        }

        $member->update($updateData);

        return redirect()->route('admin.membership.index')
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Bulk extend membership for selected expired members.
     */
    public function extend(Request $request)
    {
        $request->validate([
            'member_ids'   => 'required|array|min:1',
            'member_ids.*' => 'required|string',
            'extend_months'=> 'required|integer|min:1|max:60',
        ]);

        $months  = (int) $request->extend_months;
        $updated = 0;

        foreach ($request->member_ids as $mid) {
            $member = Member::find($mid);
            if (!$member) continue;

            // If already expired, extend from today; otherwise from current expire_date
            $base = ($member->expire_date < Carbon::today())
                ? Carbon::today()
                : $member->expire_date;

            $member->update([
                'expire_date' => $base->addMonths($months)->toDateString(),
                'last_update' => now()->toDateString(),
            ]);
            $updated++;
        }

        return redirect()->route('admin.membership.index')
                         ->with('success', "{$updated} anggota berhasil diperpanjang selama {$months} bulan.");
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
