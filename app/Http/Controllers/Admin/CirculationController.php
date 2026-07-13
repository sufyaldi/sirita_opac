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
use App\Models\Loan;
use App\Models\Member;
use App\Models\Fine;
use App\Models\Item;
use Illuminate\Http\Request;

class CirculationController extends Controller
{
    /**
     * Display circulation transactions dashboard.
     */
    public function index()
    {
        $recentLoans = Loan::with('member')
            ->orderBy('input_date', 'desc')
            ->limit(10)
            ->get();

        return view('admin.circulation.index', compact('recentLoans'));
    }

    /**
     * Start transaction for a specific member.
     */
    public function startTransaction(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string|exists:member,member_id',
        ]);

        $member = Member::findOrFail($request->member_id);

        // Active Loans (not returned)
        $activeLoans = Loan::with('item.bibliography')
            ->where('member_id', $member->member_id)
            ->where('is_return', 0)
            ->orderBy('loan_date', 'desc')
            ->get();

        // Loan History (returned)
        $loanHistory = Loan::with('item.bibliography')
            ->where('member_id', $member->member_id)
            ->where('is_return', 1)
            ->orderBy('return_date', 'desc')
            ->limit(30)
            ->get();

        // Fines history
        $fines = Fine::where('member_id', $member->member_id)
            ->orderBy('fines_date', 'desc')
            ->get();

        return view('admin.circulation.transaction', compact('member', 'activeLoans', 'loanHistory', 'fines'));
    }

    /**
     * Checkout item to member.
     */
    public function checkout(Request $request, $member_id)
    {
        $request->validate([
            'item_code' => 'required|string',
        ]);

        $member = Member::findOrFail($member_id);

        // Verify if the item exists in database
        $item = Item::where('item_code', $request->item_code)->first();
        if (!$item) {
            return redirect()->route('admin.circulation.transaction', ['member_id' => $member_id])
                             ->with('error', 'Kode eksemplar buku (Item Code) tidak ditemukan.');
        }

        // Verify if the item is already lent
        $isLent = Loan::where('item_code', $request->item_code)->where('is_return', 0)->exists();
        if ($isLent) {
            return redirect()->route('admin.circulation.transaction', ['member_id' => $member_id])
                             ->with('error', 'Buku sedang dipinjam oleh anggota lain.');
        }

        // Fetch dynamic LoanRule
        $loanRule = \App\Models\LoanRule::where('member_type_id', $member->member_type_id)
            ->where('coll_type_id', $item->coll_type_id)
            ->where(function ($q) use ($item) {
                $q->where('gmd_id', $item->gmd_id)
                  ->orWhere('gmd_id', 0); // fallback to GMD 'ALL'
            })
            ->orderBy('gmd_id', 'desc') // prioritize specific GMD over 'ALL'
            ->first();

        // Check limits
        $activeLoansCount = Loan::where('member_id', $member->member_id)->where('is_return', 0)->count();
        $loanLimit = $loanRule ? $loanRule->loan_limit : 5; // Default limit: 5 books

        if ($activeLoansCount >= $loanLimit) {
            return redirect()->route('admin.circulation.transaction', ['member_id' => $member_id])
                             ->with('error', "Batas peminjaman anggota untuk jenis koleksi ini telah mencapai limit ({$loanLimit} buku).");
        }

        $loanPeriod = $loanRule ? $loanRule->loan_periode : 7; // Default: 7 days
        $ruleId     = $loanRule ? $loanRule->loan_rules_id : 1;

        $loan = Loan::create([
            'item_code' => $request->item_code,
            'member_id' => $member->member_id,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays($loanPeriod)->toDateString(),
            'renewed' => 0,
            'loan_rules_id' => $ruleId,
            'is_lent' => 1,
            'is_return' => 0,
            'input_date' => now(),
            'last_update' => now(),
        ]);

        return redirect()->route('admin.circulation.transaction', ['member_id' => $member_id])
                         ->with('success', "Buku berhasil dipinjam (Masa pinjam {$loanPeriod} hari sesuai aturan).");
    }

    /**
     * Return/Checkin item.
     */
    public function checkin(Request $request, $loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        
        $loan->update([
            'is_return' => 1,
            'return_date' => now()->toDateString(),
            'actual' => now()->toDateString(),
            'last_update' => now(),
        ]);

        // Calculate fine if overdue
        $dueDate = \Carbon\Carbon::parse($loan->due_date)->startOfDay();
        $returnDate = \Carbon\Carbon::now()->startOfDay();
        
        $overdueDays = $this->calculateNetOverdueDays($dueDate, $returnDate);
        if ($overdueDays > 0) {
            // Find applicable fine rate from LoanRule
            $loanRule = \App\Models\LoanRule::find($loan->loan_rules_id);
            $fineRate = $loanRule ? $loanRule->fine_each_day : 500; // Default: 500/day
            
            // Check grace period
            $grace = $loanRule ? $loanRule->grace_periode : 0;
            if ($overdueDays <= $grace) {
                $fineAmount = 0;
            } else {
                $fineAmount = $overdueDays * $fineRate;
            }

            if ($fineAmount > 0) {
                Fine::create([
                    'fines_date' => now()->toDateString(),
                    'member_id' => $loan->member_id,
                    'debet' => $fineAmount,
                    'credit' => 0,
                    'description' => "Denda keterlambatan buku {$loan->item_code} selama {$overdueDays} hari",
                ]);
            }
        }

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Show Quick Return page.
     */
    public function showQuickReturn()
    {
        $log = session('quick_return_log', []);
        return view('admin.circulation.quick_return', compact('log'));
    }

    /**
     * Process quick return of an item via barcode scanner/manual code.
     */
    public function doQuickReturn(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string',
        ]);

        $itemCode = $request->item_code;

        // Find active loan for this item code
        $loan = Loan::where('item_code', $itemCode)
                    ->where('is_return', 0)
                    ->first();

        if (!$loan) {
            return redirect()->back()->with('error', "Eksemplar buku dengan kode '{$itemCode}' sedang tidak dipinjam.");
        }

        // Return the book
        $loan->update([
            'is_return' => 1,
            'return_date' => now()->toDateString(),
            'actual' => now()->toDateString(),
            'last_update' => now(),
        ]);

        $fineMessage = '';
        // Calculate fine if overdue
        $dueDate = \Carbon\Carbon::parse($loan->due_date)->startOfDay();
        $returnDate = \Carbon\Carbon::now()->startOfDay();
        
        $overdueDays = $this->calculateNetOverdueDays($dueDate, $returnDate);
        if ($overdueDays > 0) {
            $fineRate = 500;
            $fineAmount = $overdueDays * $fineRate;

            Fine::create([
                'fines_date' => now()->toDateString(),
                'member_id' => $loan->member_id,
                'debet' => $fineAmount,
                'credit' => 0,
                'description' => "Denda keterlambatan buku {$loan->item_code} selama {$overdueDays} hari (Quick Return)",
            ]);

            $fineMessage = " (Terlambat {$overdueDays} hari, denda Rp {$fineAmount})";
        }

        // Add to quick return session log
        $log = session('quick_return_log', []);
        array_unshift($log, [
            'item_code' => $itemCode,
            'member_id' => $loan->member_id,
            'return_time' => now()->format('H:i:s'),
            'status' => 'Sukses' . $fineMessage,
        ]);
        session(['quick_return_log' => $log]);

        return redirect()->back()->with('success', "Buku '{$itemCode}' berhasil dikembalikan{$fineMessage}.");
    }

    /**
     * Clear the quick return session log.
     */
    public function clearQuickReturnLog()
    {
        session()->forget('quick_return_log');
        return redirect()->back()->with('success', 'Log pengembalian berhasil dibersihkan.');
    }

    /**
     * Display list of all fines.
     */
    public function listFines(Request $request)
    {
        $query = Fine::with('member')->orderBy('fines_date', 'desc');

        if ($request->has('search')) {
            $query->where('member_id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function($q) use ($request) {
                      $q->where('member_name', 'like', '%' . $request->search . '%');
                  });
        }

        $fines = $query->paginate(15);

        return view('admin.circulation.fines', compact('fines'));
    }

    /**
     * Mark a specific fine as paid (set credit equal to debet).
     */
    public function payFine($fines_id)
    {
        $fine = Fine::findOrFail($fines_id);
        $fine->update([
            'credit' => $fine->debet
        ]);

        return redirect()->back()->with('success', 'Denda berhasil dibayar lunas.');
    }

    /**
     * Delete a fine record.
     */
    public function destroyFine($fines_id)
    {
        $fine = Fine::findOrFail($fines_id);
        $fine->delete();

        return redirect()->back()->with('success', 'Rekam denda berhasil dihapus.');
    }

    /**
     * Create a manual fine for a member.
     */
    public function storeFine(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string|exists:member,member_id',
            'debet' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
        ]);

        Fine::create([
            'fines_date' => now()->toDateString(),
            'member_id' => $request->member_id,
            'debet' => $request->debet,
            'credit' => 0,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Denda manual berhasil ditambahkan.');
    }

    /**
     * Print loan receipt for a member's active transactions.
     */
    public function printReceipt($member_id)
    {
        $member = Member::findOrFail($member_id);
        
        // Fetch all active, unreturned loans
        $activeLoans = Loan::with('item.bibliography')
            ->where('member_id', $member_id)
            ->where('is_return', 0)
            ->get();

        return view('admin.circulation.receipt', compact('member', 'activeLoans'));
    }

    /**
     * Calculate overdue days excluding specific/weekly holidays.
     */
    private function calculateNetOverdueDays($dueDate, $returnDate)
    {
        if ($returnDate->lessThanOrEqualTo($dueDate)) {
            return 0;
        }

        // Fetch specific holiday dates in this range
        $specificHolidays = \App\Models\Holiday::whereNotNull('holiday_date')
            ->whereBetween('holiday_date', [$dueDate->toDateString(), $returnDate->toDateString()])
            ->pluck('holiday_date')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->toDateString())
            ->toArray();

        // Fetch repeating weekly holidays
        $weeklyHolidays = \App\Models\Holiday::whereNull('holiday_date')
            ->pluck('holiday_dayname')
            ->map(fn($day) => strtolower($day))
            ->toArray();

        $netOverdue = 0;
        
        // Loop through each day from the day after due date to the return date
        $current = (clone $dueDate)->addDay();
        while ($current->lessThanOrEqualTo($returnDate)) {
            $isHoliday = false;

            // Check specific date
            if (in_array($current->toDateString(), $specificHolidays)) {
                $isHoliday = true;
            }

            // Check weekly day name (e.g. 'sunday', 'saturday')
            $dayName = strtolower($current->format('l'));
            if (in_array($dayName, $weeklyHolidays)) {
                $isHoliday = true;
            }

            if (!$isHoliday) {
                $netOverdue++;
            }

            $current->addDay();
        }

        return $netOverdue;
    }
}
