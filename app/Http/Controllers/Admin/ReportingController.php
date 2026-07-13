<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biblio;
use App\Models\Fine;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    /**
     * Display reporting dashboard with statistics and charts.
     */
    public function index()
    {
        // 1. Metric Counts
        $totalBiblio = Biblio::count();
        $totalItems = Item::count();
        $totalMembers = Member::count();
        $activeLoans = Loan::where('is_return', 0)->count();
        
        // 2. Fines stats
        $totalFinesCharged = Fine::sum('debet');
        $totalFinesPaid = Fine::sum('credit');
        $unpaidFines = $totalFinesCharged - $totalFinesPaid;

        // 3. Top 5 Most Borrowed Books
        $topBooks = DB::table('loan')
            ->join('item', 'loan.item_code', '=', 'item.item_code')
            ->join('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
            ->select('biblio.title', DB::raw('count(loan.loan_id) as borrow_count'))
            ->groupBy('biblio.biblio_id', 'biblio.title')
            ->orderByDesc('borrow_count')
            ->limit(5)
            ->get();

        // 4. Monthly Loan Trends (Last 6 Months)
        $monthFormat = DB::getDriverName() === 'sqlite' 
            ? "strftime('%Y-%m', loan_date)" 
            : "DATE_FORMAT(loan_date, '%Y-%m')";

        $loanTrends = DB::table('loan')
            ->select(DB::raw("$monthFormat as month"), DB::raw('count(loan_id) as total_loans'))
            ->where('loan_date', '>=', now()->subMonths(6)->toDateString())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 5. Item Availability proportion
        $totalLentItems = Loan::where('is_return', 0)->distinct('item_code')->count('item_code');
        $availableItems = max(0, $totalItems - $totalLentItems);

        return view('admin.reporting.index', compact(
            'totalBiblio',
            'totalItems',
            'totalMembers',
            'activeLoans',
            'totalFinesPaid',
            'unpaidFines',
            'topBooks',
            'loanTrends',
            'availableItems',
            'totalLentItems'
        ));
    }
}
