<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Plugins\AccreditationPlugin;

use App\Http\Controllers\Controller;
use App\Models\OrgUnit;
use App\Models\Biblio;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccreditationController extends Controller
{
    /**
     * Halaman utama laporan akreditasi — pilih unit.
     */
    public function index()
    {
        // Ambil node universitas (root absolut)
        $univNode = OrgUnit::active()->where('type', 'university')->first();

        // Yang ditampilkan di tree: children dari universitas (PUSAT, FK, FT, dll)
        // dengan eager-load sub-children (Program Studi)
        if ($univNode) {
            $units = OrgUnit::with('children.children')
                             ->active()
                             ->where('parent_id', $univNode->id)
                             ->orderBy('sort_order')
                             ->get();
        } else {
            // Fallback: semua unit non-universitas yang parent_id = null
            $units = OrgUnit::with('children.children')
                             ->active()
                             ->whereNull('parent_id')
                             ->where('type', '!=', 'university')
                             ->orderBy('sort_order')
                             ->get();
        }

        // Ringkasan statistik per unit (flat, untuk tabel)
        $summary = $this->buildUnitSummary();

        return view('AccreditationPlugin::index', compact('units', 'summary', 'univNode'));
    }


    /**
     * Laporan detail satu unit — semua metrik akreditasi.
     */
    public function unitReport(int $unitId)
    {
        $unit = OrgUnit::with(['children', 'parent'])->findOrFail($unitId);

        // Semua ID: unit ini + seluruh keturunannya
        $allIds = $unit->selfAndDescendantIds();

        // --- Metrik Judul (Biblio) ---
        $totalTitles = DB::table('biblio_org_unit')
            ->whereIn('unit_id', $allIds)
            ->distinct('biblio_id')
            ->count('biblio_id');

        $coreTitles = DB::table('biblio_org_unit')
            ->whereIn('unit_id', $allIds)
            ->where('is_core', 1)
            ->distinct('biblio_id')
            ->count('biblio_id');

        $recentTitles = DB::table('biblio_org_unit as bou')
            ->join('biblio as b', 'b.biblio_id', '=', 'bou.biblio_id')
            ->whereIn('bou.unit_id', $allIds)
            ->whereRaw('CAST(b.publish_year AS UNSIGNED) >= ?', [date('Y') - 10])
            ->distinct('bou.biblio_id')
            ->count('bou.biblio_id');

        $foreignTitles = DB::table('biblio_org_unit as bou')
            ->join('biblio as b', 'b.biblio_id', '=', 'bou.biblio_id')
            ->whereIn('bou.unit_id', $allIds)
            ->where('b.language_id', '!=', 'id')
            ->distinct('bou.biblio_id')
            ->count('bou.biblio_id');

        // --- Metrik Eksemplar (Item Fisik) ---
        $totalItems = Item::whereIn('branch_id', $allIds)->count();

        // --- Breakdown per DDC (Klasifikasi Desimal Dewey) ---
        $ddcBreakdown = DB::table('biblio_org_unit as bou')
            ->join('biblio as b', 'b.biblio_id', '=', 'bou.biblio_id')
            ->whereIn('bou.unit_id', $allIds)
            ->whereNotNull('b.classification')
            ->select(
                DB::raw("LEFT(b.classification, 3) as ddc_code"),
                DB::raw("COUNT(DISTINCT bou.biblio_id) as total")
            )
            ->groupBy('ddc_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // --- Breakdown per Tahun Terbit (untuk tren) ---
        $yearBreakdown = DB::table('biblio_org_unit as bou')
            ->join('biblio as b', 'b.biblio_id', '=', 'bou.biblio_id')
            ->whereIn('bou.unit_id', $allIds)
            ->whereNotNull('b.publish_year')
            ->whereRaw('CAST(b.publish_year AS UNSIGNED) > 1990')
            ->select(
                DB::raw("CAST(b.publish_year AS UNSIGNED) as year"),
                DB::raw("COUNT(DISTINCT bou.biblio_id) as total")
            )
            ->groupBy('year')
            ->orderByDesc('year')
            ->limit(15)
            ->get();

        // --- Daftar Lengkap Judul (untuk lampiran borang) ---
        $titleList = DB::table('biblio_org_unit as bou')
            ->join('biblio as b', 'b.biblio_id', '=', 'bou.biblio_id')
            ->leftJoin(DB::raw('(SELECT biblio_id, COUNT(*) as copy_count FROM item WHERE branch_id IN (' . implode(',', $allIds ?: [0]) . ') GROUP BY biblio_id) as ic'), 'ic.biblio_id', '=', 'b.biblio_id')
            ->whereIn('bou.unit_id', $allIds)
            ->select(
                'b.biblio_id',
                'b.title',
                'b.isbn_issn',
                'b.publish_year',
                'b.classification',
                'bou.is_core',
                DB::raw('COALESCE(ic.copy_count, 0) as copies')
            )
            ->distinct('b.biblio_id')
            ->orderBy('b.classification')
            ->orderBy('b.title')
            ->paginate(30);

        // Rasio judul : mahasiswa
        $studentCount = $unit->student_count;
        $ratio = ($studentCount && $studentCount > 0)
            ? round($totalTitles / $studentCount, 2)
            : null;

        // Persentase
        $recentPct  = $totalTitles > 0 ? round($recentTitles / $totalTitles * 100, 1) : 0;
        $foreignPct = $totalTitles > 0 ? round($foreignTitles / $totalTitles * 100, 1) : 0;
        $corePct    = $totalTitles > 0 ? round($coreTitles / $totalTitles * 100, 1) : 0;

        return view('AccreditationPlugin::unit_report', compact(
            'unit',
            'totalTitles', 'coreTitles', 'recentTitles', 'foreignTitles',
            'totalItems',
            'ddcBreakdown', 'yearBreakdown',
            'titleList',
            'studentCount', 'ratio',
            'recentPct', 'foreignPct', 'corePct',
            'allIds'
        ));
    }

    /**
     * Tag / un-tag judul ke unit (via form di halaman detail katalog).
     */
    public function tagBiblio(Request $request, int $biblioId)
    {
        $request->validate([
            'unit_id' => 'required|integer|exists:org_units,id',
            'is_core' => 'nullable|boolean',
            'notes'   => 'nullable|string|max:255',
        ]);

        DB::table('biblio_org_unit')->updateOrInsert(
            ['biblio_id' => $biblioId, 'unit_id' => $request->unit_id],
            [
                'is_core'   => $request->boolean('is_core'),
                'notes'     => $request->notes,
                'tagged_by' => auth()->id(),
                'tagged_at' => now(),
            ]
        );

        return back()->with('tag_success', 'Judul berhasil ditandai untuk koleksi unit perpustakaan.');
    }

    /**
     * Hapus tag judul dari unit.
     */
    public function untagBiblio(int $biblioId, int $unitId)
    {
        DB::table('biblio_org_unit')
            ->where('biblio_id', $biblioId)
            ->where('unit_id', $unitId)
            ->delete();

        return back()->with('tag_success', 'Tag koleksi berhasil dihapus.');
    }

    /**
     * Bangun ringkasan statistik semua unit untuk halaman index.
     */
    private function buildUnitSummary(): array
    {
        $units = OrgUnit::active()->get();
        $summary = [];

        foreach ($units as $unit) {
            $ids = $unit->selfAndDescendantIds();
            $summary[$unit->id] = [
                'titles' => DB::table('biblio_org_unit')->whereIn('unit_id', $ids)->distinct('biblio_id')->count('biblio_id'),
                'items'  => Item::whereIn('branch_id', $ids)->count(),
            ];
        }

        return $summary;
    }
}
