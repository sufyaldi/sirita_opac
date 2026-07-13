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
        $settings = [
            'library_name' => \App\Models\Setting::get('library_name', 'Perpustakaan SIRITA'),
            'fine_rate'    => \App\Models\Setting::get('fine_rate', '500'),
            'loan_limit'   => \App\Models\Setting::get('loan_limit', '3'),
            'loan_period'  => \App\Models\Setting::get('loan_period', '7'),
            'opac_theme'   => \App\Models\Setting::get('opac_theme', 'default'),
        ];
        
        return view('admin.setting.index', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'library_name' => 'required|string|max:200',
            'fine_rate'    => 'required|integer|min:0',
            'loan_limit'   => 'required|integer|min:1',
            'loan_period'  => 'required|integer|min:1',
            'opac_theme'   => 'required|string|in:default,classic,minimal',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }

        return redirect()->route('admin.setting.index')
                         ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
