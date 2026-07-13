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
use App\Models\SerialKardex;
use App\Models\SerialSubscription;
use Illuminate\Http\Request;

class SerialControlController extends Controller
{
    /**
     * List all subscriptions.
     */
    public function index()
    {
        $subscriptions = SerialSubscription::orderBy('title', 'asc')->paginate(15);
        return view('admin.serial_control.index', compact('subscriptions'));
    }

    /**
     * Store new subscription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_name' => 'nullable|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'frequency' => 'required|string|max:50',
            'notes' => 'nullable|string|max:255',
        ]);

        SerialSubscription::create($request->all());

        return redirect()->back()->with('success', 'Langganan terbitan berseri berhasil ditambahkan.');
    }

    /**
     * Delete subscription.
     */
    public function destroy($subscription_id)
    {
        $subscription = SerialSubscription::findOrFail($subscription_id);
        $subscription->delete();

        return redirect()->back()->with('success', 'Data langganan berhasil dihapus.');
    }

    /**
     * Show Kardex card for a subscription.
     */
    public function showKardex($subscription_id)
    {
        $subscription = SerialSubscription::findOrFail($subscription_id);
        $kardexes = SerialKardex::where('subscription_id', $subscription_id)
            ->orderBy('publish_date', 'asc')
            ->get();

        return view('admin.serial_control.kardex', compact('subscription', 'kardexes'));
    }

    /**
     * Add expected issue to Kardex.
     */
    public function storeKardex(Request $request, $subscription_id)
    {
        $request->validate([
            'issue_no' => 'required|string|max:100',
            'publish_date' => 'required|date',
        ]);

        SerialKardex::create([
            'subscription_id' => $subscription_id,
            'issue_no' => $request->issue_no,
            'publish_date' => $request->publish_date,
            'status' => 'expected',
        ]);

        return redirect()->back()->with('success', 'Edisi yang diharapkan berhasil didaftarkan ke kartu Kardex.');
    }

    /**
     * Receive/check-in a Kardex issue.
     */
    public function receiveKardex(Request $request, $kardex_id)
    {
        $kardex = SerialKardex::findOrFail($kardex_id);
        $kardex->update([
            'status' => 'received',
            'receive_date' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Edisi berhasil ditandai sebagai Diterima.');
    }

    /**
     * Mark Kardex issue as missing.
     */
    public function missKardex($kardex_id)
    {
        $kardex = SerialKardex::findOrFail($kardex_id);
        $kardex->update([
            'status' => 'missing',
            'receive_date' => null,
        ]);

        return redirect()->back()->with('success', 'Edisi ditandai sebagai Hilang/Tidak Terbit.');
    }

    /**
     * Delete a Kardex issue.
     */
    public function destroyKardex($kardex_id)
    {
        $kardex = SerialKardex::findOrFail($kardex_id);
        $kardex->delete();

        return redirect()->back()->with('success', 'Edisi berhasil dihapus dari kartu Kardex.');
    }
}
