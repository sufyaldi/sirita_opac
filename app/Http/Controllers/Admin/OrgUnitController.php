<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrgUnit;
use Illuminate\Http\Request;

class OrgUnitController extends Controller
{
    public function index()
    {
        $univNode = OrgUnit::active()->where('type', 'university')->first();
        
        if ($univNode) {
            $units = OrgUnit::with('children.children')
                             ->where('parent_id', $univNode->id)
                             ->orderBy('sort_order')
                             ->get();
        } else {
            $units = OrgUnit::with('children.children')
                             ->whereNull('parent_id')
                             ->where('type', '!=', 'university')
                             ->orderBy('sort_order')
                             ->get();
        }

        $allUnits = OrgUnit::orderBy('name')->get();
        return view('admin.org-units.index', compact('units', 'univNode', 'allUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:org_units,code',
            'name' => 'required|string|max:200',
            'short_name' => 'nullable|string|max:50',
            'type' => 'required|string|in:university,main_library,faculty,department,lp,upt,pusat,other',
            'parent_id' => 'nullable|integer|exists:org_units,id',
            'student_count' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        OrgUnit::create([
            'code' => $request->code,
            'name' => $request->name,
            'short_name' => $request->short_name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'student_count' => $request->student_count,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => 1,
            'input_date' => now(),
            'last_update' => now(),
        ]);

        return redirect()->route('admin.org-units.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $unit = OrgUnit::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:20|unique:org_units,code,' . $id,
            'name' => 'required|string|max:200',
            'short_name' => 'nullable|string|max:50',
            'type' => 'required|string|in:university,main_library,faculty,department,lp,upt,pusat,other',
            'parent_id' => 'nullable|integer|exists:org_units,id|different:id',
            'student_count' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        $unit->update([
            'code' => $request->code,
            'name' => $request->name,
            'short_name' => $request->short_name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'student_count' => $request->student_count,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'last_update' => now(),
        ]);

        return redirect()->route('admin.org-units.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = OrgUnit::findOrFail($id);

        // Check if there are children
        if ($unit->children()->exists()) {
            return back()->with('error', 'Unit tidak bisa dihapus karena memiliki unit bawahan.');
        }

        // Check if referenced in biblio_org_unit
        $hasBiblio = \DB::table('biblio_org_unit')->where('unit_id', $id)->exists();
        if ($hasBiblio) {
            return back()->with('error', 'Unit tidak bisa dihapus karena memiliki koleksi buku yang terkait.');
        }

        // Check if referenced in items
        if ($unit->items()->exists()) {
            return back()->with('error', 'Unit tidak bisa dihapus karena memiliki eksemplar fisik buku.');
        }

        $unit->delete();
        return redirect()->route('admin.org-units.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
