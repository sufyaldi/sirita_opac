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
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * List all holidays (weekly and specific dates).
     */
    public function index()
    {
        $holidays = Holiday::orderBy('holiday_date', 'asc')
            ->orderBy('holiday_dayname', 'asc')
            ->paginate(15);
            
        return view('admin.system.holidays.index', compact('holidays'));
    }

    /**
     * Store a new holiday.
     */
    public function store(Request $request)
    {
        $request->validate([
            'holiday_dayname' => 'required|string|max:20',
            'holiday_date' => 'nullable|date',
            'description' => 'nullable|string|max:100',
        ]);

        // Prevent duplicate unique constraint
        $exists = Holiday::where('holiday_dayname', $request->holiday_dayname)
            ->where('holiday_date', $request->holiday_date)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Hari libur/tutup ini sudah terdaftar.');
        }

        Holiday::create($request->all());

        return redirect()->back()->with('success', 'Hari libur/tutup perpustakaan berhasil ditambahkan.');
    }

    /**
     * Delete a holiday.
     */
    public function destroy($holiday_id)
    {
        $holiday = Holiday::findOrFail($holiday_id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Hari libur/tutup berhasil dihapus.');
    }
}
