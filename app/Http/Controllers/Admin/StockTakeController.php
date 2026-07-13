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
use App\Models\Item;
use App\Models\ItemStatus;
use App\Models\Loan;
use App\Models\StockTakeLog;
use App\Models\StockTakeSession;
use Illuminate\Http\Request;

class StockTakeController extends Controller
{
    /**
     * Show initial page or active session.
     */
    public function index()
    {
        $activeSession = StockTakeSession::where('status', 'active')->first();
        if ($activeSession) {
            return redirect()->route('admin.stock-take.current');
        }

        $finishedSessions = StockTakeSession::where('status', 'finished')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.stock_take.index', compact('finishedSessions'));
    }

    /**
     * Start a new stock take session.
     */
    public function startSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255',
        ]);

        // Check if there is already an active session
        $exists = StockTakeSession::where('status', 'active')->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Ada sesi stok opname yang sedang berjalan.');
        }

        StockTakeSession::create([
            'session_name' => $request->session_name,
            'status' => 'active',
        ]);

        return redirect()->route('admin.stock-take.current')->with('success', 'Sesi stok opname baru berhasil dimulai.');
    }

    /**
     * Show the current active stock take session scanning panel.
     */
    public function showCurrent()
    {
        $session = StockTakeSession::where('status', 'active')->first();
        if (!$session) {
            return redirect()->route('admin.stock-take.index')->with('error', 'Tidak ada sesi stok opname aktif.');
        }

        $logs = StockTakeLog::with('item.biblio')
            ->where('session_id', $session->session_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.stock_take.current', compact('session', 'logs'));
    }

    /**
     * Scan an item barcode and record in log.
     */
    public function scanItem(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string',
        ]);

        $session = StockTakeSession::where('status', 'active')->first();
        if (!$session) {
            return redirect()->route('admin.stock-take.index')->with('error', 'Tidak ada sesi stok opname aktif.');
        }

        $item = Item::where('item_code', $request->item_code)->first();
        if (!$item) {
            return redirect()->back()->with('error', "Kode eksemplar '{$request->item_code}' tidak ditemukan di database.");
        }

        // Check if already scanned in this session
        $alreadyScanned = StockTakeLog::where('session_id', $session->session_id)
            ->where('item_code', $request->item_code)
            ->exists();

        if ($alreadyScanned) {
            return redirect()->back()->with('warning', "Kode eksemplar '{$request->item_code}' sudah diverifikasi sebelumnya.");
        }

        StockTakeLog::create([
            'session_id' => $session->session_id,
            'item_code' => $request->item_code,
        ]);

        return redirect()->back()->with('success', "Eksemplar '{$request->item_code}' ({$item->biblio->title}) berhasil diverifikasi.");
    }

    /**
     * Show real-time progress report.
     */
    public function showReport()
    {
        $session = StockTakeSession::where('status', 'active')->first();
        if (!$session) {
            return redirect()->route('admin.stock-take.index')->with('error', 'Tidak ada sesi stok opname aktif.');
        }

        $totalItems = Item::count();
        
        // Verified items in this session
        $verifiedCodes = StockTakeLog::where('session_id', $session->session_id)->pluck('item_code');
        $totalVerified = $verifiedCodes->count();

        // Items currently on loan
        $lentCodes = Loan::where('is_return', 0)->pluck('item_code');
        $totalLent = $lentCodes->count();

        // Missing items (Items NOT verified AND NOT on loan)
        $missingItems = Item::with('biblio')
            ->whereNotIn('item_code', $verifiedCodes)
            ->whereNotIn('item_code', $lentCodes)
            ->paginate(25);

        $totalMissing = max(0, $totalItems - $totalVerified - $totalLent);

        return view('admin.stock_take.report', compact('session', 'totalItems', 'totalVerified', 'totalLent', 'totalMissing', 'missingItems'));
    }

    /**
     * Finalize the stock take session.
     */
    public function finishSession()
    {
        $session = StockTakeSession::where('status', 'active')->first();
        if (!$session) {
            return redirect()->route('admin.stock-take.index')->with('error', 'Tidak ada sesi stok opname aktif.');
        }

        // Find missing items (not verified, not on loan)
        $verifiedCodes = StockTakeLog::where('session_id', $session->session_id)->pluck('item_code');
        $lentCodes = Loan::where('is_return', 0)->pluck('item_code');

        $missingItemQuery = Item::whereNotIn('item_code', $verifiedCodes)
            ->whereNotIn('item_code', $lentCodes);

        // Get or Create 'Lost' status
        $lostStatus = ItemStatus::where('item_status_name', 'LIKE', '%lost%')
            ->orWhere('item_status_name', 'LIKE', '%hilang%')
            ->first();

        if (!$lostStatus) {
            $lostStatus = ItemStatus::create([
                'item_status_id' => 'LO',
                'item_status_name' => 'Lost',
                'no_loan' => 1,
                'skip_stock_take' => 1,
                'input_date' => now()->toDateString(),
            ]);
        }

        // Update all missing items to status 'Lost'
        $updatedCount = $missingItemQuery->update([
            'item_status_id' => $lostStatus->item_status_id,
            'last_update' => now(),
        ]);

        // Finish session
        $session->update([
            'status' => 'finished',
        ]);

        return redirect()->route('admin.stock-take.index')->with('success', "Stok opname selesai. Sebanyak {$updatedCount} eksemplar yang tidak ditemukan telah ditandai sebagai 'Lost' (Hilang).");
    }
}
