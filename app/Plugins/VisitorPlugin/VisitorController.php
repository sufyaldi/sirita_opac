<?php

namespace App\Plugins\VisitorPlugin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    /**
     * Display the public visitor recording page (OPAC style)
     */
    public function index()
    {
        $now = Carbon::now();

        // Statistics
        $todayCount = Visitor::whereDate('created_at', $now->toDateString())->count();
        $monthCount = Visitor::whereYear('created_at', $now->year)
                             ->whereMonth('created_at', $now->month)
                             ->count();
        $yearCount  = Visitor::whereYear('created_at', $now->year)->count();
        $totalCount = Visitor::count();

        // Chart data — last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $chartData[] = [
                'label' => $date->translatedFormat('D, d M'),
                'count' => Visitor::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        // Recent 10 visitors
        $recentVisitors = Visitor::orderByDesc('created_at')->take(10)->get();

        $theme = \App\Models\Setting::get('opac_theme', 'default');
        return view("opac.templates.{$theme}.visitor", compact(
            'todayCount', 'monthCount', 'yearCount', 'totalCount',
            'chartData', 'recentVisitors'
        ));
    }

    /**
     * Store visitor entry (by Member ID or Manual input)
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id'   => 'nullable|string|max:50',
            'name'        => 'required_without:member_id|nullable|string|max:100',
            'institution' => 'nullable|string|max:150',
            'visitor_type'=> 'nullable|string|max:50',
        ]);

        if ($request->filled('member_id')) {
            // Find registered library member
            $member = Member::where('member_id', $request->member_id)->first();
            
            if (!$member) {
                return back()->withErrors(['member_id' => 'ID Anggota "' . $request->member_id . '" tidak ditemukan di database.']);
            }

            Visitor::create([
                'member_id'    => $member->member_id,
                'name'         => $member->member_name,
                'institution'  => $request->institution ?? 'IAIN Parepare',
                'visitor_type' => $request->visitor_type ?? 'Anggota Terdaftar'
            ]);

            return back()->with('success', 'Selamat Datang, ' . $member->member_name . '! Kunjungan Anda berhasil dicatat.');
        }

        // For manual non-member visitors
        Visitor::create([
            'member_id'    => null,
            'name'         => $request->name,
            'institution'  => $request->institution ?? 'Umum',
            'visitor_type' => $request->visitor_type ?? 'Tamu / Umum'
        ]);

        return back()->with('success', 'Kunjungan atas nama ' . $request->name . ' berhasil dicatat. Terima kasih!');
    }

    /**
     * Admin area: list all visitor records
     */
    public function adminIndex(Request $request)
    {
        $query = Visitor::orderByDesc('created_at');

        // Apply filters if any
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('visitor_type', 'like', "%{$search}%");
            });
        }

        $visitors = $query->paginate(20);

        return view('VisitorPlugin::admin_index', compact('visitors'));
    }

    /**
     * Admin area: delete a visitor record
     */
    public function adminDestroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
