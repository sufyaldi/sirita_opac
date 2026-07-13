<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace Database\Seeders;

use App\Models\OrgUnit;
use App\Models\Biblio;
use App\Models\Item;
use Illuminate\Database\Seeder;

class OrgUnitSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------
        // Level 0: Universitas (Root)
        // ----------------------------------------------------------------
        $univ = OrgUnit::create([
            'code'       => 'UNIV',
            'name'       => 'Universitas SIRITA',
            'short_name' => 'UNIV',
            'type'       => 'university',
            'parent_id'  => null,
            'is_active'  => 1,
            'sort_order' => 0,
            'address'    => 'Jl. Pendidikan No. 1, Kota Ilmu',
            'email'      => 'perpustakaan@univ-sirita.ac.id',
            'input_date' => now(),
            'last_update'=> now(),
        ]);

        // ----------------------------------------------------------------
        // Level 1: UPT Perpustakaan Pusat (Main Library)
        // ----------------------------------------------------------------
        $pusat = OrgUnit::create([
            'code'          => 'PUSAT',
            'name'          => 'UPT Perpustakaan Pusat',
            'short_name'    => 'Perpus Pusat',
            'type'          => 'main_library',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 1,
            'address'       => 'Gedung Perpustakaan Pusat Lt. 1-4',
            'phone'         => '0411-123456',
            'email'         => 'pusat@perpus.univ-sirita.ac.id',
            'student_count' => null,
            'settings'      => [
                'loan_days'    => 14,
                'fine_per_day' => 500,
                'max_loans'    => 5,
                'open_days'    => ['Senin','Selasa','Rabu','Kamis','Jumat'],
                'open_time'    => '08:00',
                'close_time'   => '20:00',
            ],
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        // ----------------------------------------------------------------
        // Level 1: Fakultas-Fakultas
        // ----------------------------------------------------------------
        $fk = OrgUnit::create([
            'code'          => 'FK',
            'name'          => 'Perpustakaan Fakultas Kedokteran',
            'short_name'    => 'Perpus FK',
            'type'          => 'faculty',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 2,
            'student_count' => 800,
            'settings'      => ['loan_days'=>7,'fine_per_day'=>1000,'max_loans'=>3,'open_days'=>['Senin','Selasa','Rabu','Kamis','Jumat']],
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $ft = OrgUnit::create([
            'code'          => 'FT',
            'name'          => 'Perpustakaan Fakultas Teknik',
            'short_name'    => 'Perpus FT',
            'type'          => 'faculty',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 3,
            'student_count' => 1200,
            'settings'      => ['loan_days'=>14,'fine_per_day'=>500,'max_loans'=>4,'open_days'=>['Senin','Selasa','Rabu','Kamis','Jumat']],
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $fmipa = OrgUnit::create([
            'code'          => 'FMIPA',
            'name'          => 'Perpustakaan Fakultas MIPA',
            'short_name'    => 'Perpus FMIPA',
            'type'          => 'faculty',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 4,
            'student_count' => 600,
            'settings'      => ['loan_days'=>14,'fine_per_day'=>500,'max_loans'=>5,'open_days'=>['Senin','Selasa','Rabu','Kamis','Jumat']],
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $fhukum = OrgUnit::create([
            'code'          => 'FH',
            'name'          => 'Perpustakaan Fakultas Hukum',
            'short_name'    => 'Perpus FH',
            'type'          => 'faculty',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 5,
            'student_count' => 700,
            'settings'      => ['loan_days'=>14,'fine_per_day'=>500,'max_loans'=>4],
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        // ----------------------------------------------------------------
        // Level 2: Program Studi di bawah FT
        // ----------------------------------------------------------------
        $prodiTI = OrgUnit::create([
            'code'          => 'FT-TI',
            'name'          => 'Koleksi Prodi Teknik Informatika',
            'short_name'    => 'Teknik Informatika',
            'type'          => 'department',
            'parent_id'     => $ft->id,
            'is_active'     => 1,
            'sort_order'    => 1,
            'student_count' => 450,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $prodiSI = OrgUnit::create([
            'code'          => 'FT-SI',
            'name'          => 'Koleksi Prodi Sistem Informasi',
            'short_name'    => 'Sistem Informasi',
            'type'          => 'department',
            'parent_id'     => $ft->id,
            'is_active'     => 1,
            'sort_order'    => 2,
            'student_count' => 380,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $prodiTE = OrgUnit::create([
            'code'          => 'FT-TE',
            'name'          => 'Koleksi Prodi Teknik Elektro',
            'short_name'    => 'Teknik Elektro',
            'type'          => 'department',
            'parent_id'     => $ft->id,
            'is_active'     => 1,
            'sort_order'    => 3,
            'student_count' => 370,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        // Level 2: Prodi FMIPA
        $prodiFisika = OrgUnit::create([
            'code'          => 'FMIPA-FIS',
            'name'          => 'Koleksi Prodi Fisika',
            'short_name'    => 'Fisika',
            'type'          => 'department',
            'parent_id'     => $fmipa->id,
            'is_active'     => 1,
            'sort_order'    => 1,
            'student_count' => 200,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $prodiMath = OrgUnit::create([
            'code'          => 'FMIPA-MAT',
            'name'          => 'Koleksi Prodi Matematika',
            'short_name'    => 'Matematika',
            'type'          => 'department',
            'parent_id'     => $fmipa->id,
            'is_active'     => 1,
            'sort_order'    => 2,
            'student_count' => 180,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        // ----------------------------------------------------------------
        // Level 1: Lembaga & UPT lainnya
        // ----------------------------------------------------------------
        $lp = OrgUnit::create([
            'code'          => 'LP',
            'name'          => 'Lembaga Penelitian dan Pengabdian Masyarakat',
            'short_name'    => 'LP2M',
            'type'          => 'lp',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 10,
            'student_count' => null,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        $uptBahasa = OrgUnit::create([
            'code'          => 'UPT-BAHASA',
            'name'          => 'UPT Pengembangan Bahasa',
            'short_name'    => 'UPT Bahasa',
            'type'          => 'upt',
            'parent_id'     => $univ->id,
            'is_active'     => 1,
            'sort_order'    => 11,
            'student_count' => null,
            'input_date'    => now(),
            'last_update'   => now(),
        ]);

        // ----------------------------------------------------------------
        // Tagging koleksi buku yang ada ke unit (biblio_org_unit)
        // ----------------------------------------------------------------
        $allBiblios = Biblio::all();

        // Distribusikan judul ke prodi secara realistis
        foreach ($allBiblios as $index => $biblio) {
            $classif = $biblio->classification ?? '';

            // Buku IT/Komputer → Prodi TI & SI
            if (str_starts_with($classif, '005') || str_starts_with($classif, '004') || str_starts_with($classif, '006')) {
                \DB::table('biblio_org_unit')->insert([
                    'biblio_id' => $biblio->biblio_id,
                    'unit_id'   => $prodiTI->id,
                    'is_core'   => 1,
                    'notes'     => 'Buku inti kurikulum TI',
                    'tagged_at' => now(),
                ]);
                \DB::table('biblio_org_unit')->insertOrIgnore([
                    'biblio_id' => $biblio->biblio_id,
                    'unit_id'   => $prodiSI->id,
                    'is_core'   => 1,
                    'notes'     => 'Relevan untuk Sistem Informasi',
                    'tagged_at' => now(),
                ]);
                // Juga diklaim FT sebagai induk
                \DB::table('biblio_org_unit')->insertOrIgnore([
                    'biblio_id' => $biblio->biblio_id,
                    'unit_id'   => $ft->id,
                    'is_core'   => 0,
                    'tagged_at' => now(),
                ]);
            }
            // Selang-seling: beberapa buku juga untuk Pusat
            if ($index % 2 === 0) {
                \DB::table('biblio_org_unit')->insertOrIgnore([
                    'biblio_id' => $biblio->biblio_id,
                    'unit_id'   => $pusat->id,
                    'is_core'   => 0,
                    'tagged_at' => now(),
                ]);
            }
        }

        // ----------------------------------------------------------------
        // Assign branch_id ke item (eksemplar fisik ke unit)
        // ----------------------------------------------------------------
        $allItems = Item::all();
        $unitIds  = [$pusat->id, $ft->id, $fmipa->id, $fk->id];

        foreach ($allItems as $i => $item) {
            Item::where('item_id', $item->item_id)->update([
                'branch_id' => $unitIds[$i % count($unitIds)],
            ]);
        }
    }
}
