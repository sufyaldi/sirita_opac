<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRule;
use App\Models\MemberType;
use App\Models\CollType;
use App\Models\Gmd;
use Illuminate\Http\Request;

class LoanRulesController extends Controller
{
    /**
     * Display a listing of the loan rules.
     */
    public function index(Request $request)
    {
        $query = LoanRule::with(['memberType', 'collType', 'gmd']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('memberType', function ($q) use ($search) {
                $q->where('member_type_name', 'like', "%{$search}%");
            })->orWhereHas('collType', function ($q) use ($search) {
                $q->where('coll_type_name', 'like', "%{$search}%");
            });
        }

        $rules = $query->paginate(15);

        return view('admin.circulation.loan_rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new loan rule.
     */
    public function create()
    {
        $memberTypes = MemberType::orderBy('member_type_name')->get();
        $collTypes   = CollType::orderBy('coll_type_name')->get();
        $gmds        = Gmd::orderBy('gmd_name')->get();

        return view('admin.circulation.loan_rules.create', compact('memberTypes', 'collTypes', 'gmds'));
    }

    /**
     * Store a newly created loan rule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_type_id' => 'required|integer|exists:mst_member_type,member_type_id',
            'coll_type_id'   => 'required|integer|exists:mst_coll_type,coll_type_id',
            'gmd_id'         => 'required|integer', // 0 is usually 'ALL' or custom GMD ID
            'loan_limit'     => 'required|integer|min:0',
            'loan_periode'   => 'required|integer|min:0',
            'reborrow_limit' => 'required|integer|min:0',
            'fine_each_day'  => 'required|integer|min:0',
            'grace_periode'  => 'required|integer|min:0',
        ]);

        LoanRule::create(array_merge($validated, [
            'input_date'  => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]));

        return redirect()->route('circulation.loan-rules.index')
                         ->with('success', 'Aturan peminjaman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified loan rule.
     */
    public function edit($id)
    {
        $rule        = LoanRule::findOrFail($id);
        $memberTypes = MemberType::orderBy('member_type_name')->get();
        $collTypes   = CollType::orderBy('coll_type_name')->get();
        $gmds        = Gmd::orderBy('gmd_name')->get();

        return view('admin.circulation.loan_rules.edit', compact('rule', 'memberTypes', 'collTypes', 'gmds'));
    }

    /**
     * Update the specified loan rule in storage.
     */
    public function update(Request $request, $id)
    {
        $rule = LoanRule::findOrFail($id);

        $validated = $request->validate([
            'member_type_id' => 'required|integer|exists:mst_member_type,member_type_id',
            'coll_type_id'   => 'required|integer|exists:mst_coll_type,coll_type_id',
            'gmd_id'         => 'required|integer',
            'loan_limit'     => 'required|integer|min:0',
            'loan_periode'   => 'required|integer|min:0',
            'reborrow_limit' => 'required|integer|min:0',
            'fine_each_day'  => 'required|integer|min:0',
            'grace_periode'  => 'required|integer|min:0',
        ]);

        $rule->update(array_merge($validated, [
            'last_update' => now()->toDateString(),
        ]));

        return redirect()->route('circulation.loan-rules.index')
                         ->with('success', 'Aturan peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified loan rule from storage.
     */
    public function destroy($id)
    {
        $rule = LoanRule::findOrFail($id);
        $rule->delete();

        return redirect()->route('circulation.loan-rules.index')
                         ->with('success', 'Aturan peminjaman berhasil dihapus.');
    }
}
