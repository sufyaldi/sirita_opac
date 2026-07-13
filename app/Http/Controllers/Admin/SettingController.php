<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display general system settings page.
     */
    public function index()
    {
        return view('admin.setting.index');
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        // For simulation, we simply redirect with a success message
        return redirect()->route('admin.setting.index')
                         ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
