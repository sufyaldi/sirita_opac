<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Biblio;
use App\Models\Fine;
use App\Models\Gmd;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure some basic lookup data exists
        $gmd = Gmd::first() ?? Gmd::create(['gmd_code' => 'TE', 'gmd_name' => 'Text', 'input_date' => now()]);
        $pub = Publisher::first() ?? Publisher::create(['publisher_name' => 'Gramedia Pustaka Utama', 'input_date' => now()]);
        $author = Author::first() ?? Author::create(['author_name' => 'Seno Gumira Ajidarma', 'authority_type' => 'p', 'input_date' => now()]);

        // 1. Create a list of interesting books (Bibliographies)
        $booksData = [
            ['title' => 'Belajar Laravel 11 dari Nol', 'isbn' => '978-602-876-001-1', 'class' => '005.26'],
            ['title' => 'Pemrograman JavaScript Modern', 'isbn' => '978-602-876-002-2', 'class' => '005.27'],
            ['title' => 'Arsitektur Microservices dengan Go', 'isbn' => '978-602-876-003-3', 'class' => '004.22'],
            ['title' => 'Desain Interface Premium dengan CSS Grid', 'isbn' => '978-602-876-004-4', 'class' => '006.7'],
            ['title' => 'Pengenalan Python untuk Data Science', 'isbn' => '978-602-876-005-5', 'class' => '005.13'],
            ['title' => 'Manajemen Database Relasional dengan PostgreSQL', 'isbn' => '978-602-876-006-6', 'class' => '005.74'],
            ['title' => 'Seni Berpikir Komputasional', 'isbn' => '978-602-876-007-7', 'class' => '004.01'],
            ['title' => 'Docker dan Kubernetes untuk Pemula', 'isbn' => '978-602-876-008-8', 'class' => '004.36'],
            ['title' => 'Keamanan Siber di Era Cloud Computing', 'isbn' => '978-602-876-009-9', 'class' => '005.8'],
            ['title' => 'Kecerdasan Buatan dan Deep Learning', 'isbn' => '978-602-876-010-5', 'class' => '006.3'],
        ];

        $bibliographies = [];
        foreach ($booksData as $data) {
            $bib = Biblio::create([
                'title' => $data['title'],
                'isbn_issn' => $data['isbn'],
                'classification' => $data['class'],
                'gmd_id' => $gmd->gmd_id,
                'publisher_id' => $pub->publisher_id,
                'publish_year' => '2025',
                'input_date' => now(),
                'last_update' => now(),
            ]);
            $bib->authors()->attach($author->author_id, ['level' => 1]);
            $bibliographies[] = $bib;
        }

        // 2. Create physical Items (Copies) for each book
        $items = [];
        $itemCounter = 1000;
        foreach ($bibliographies as $bib) {
            // Create 2 to 4 copies per book
            $copiesCount = rand(2, 4);
            for ($i = 0; $i < $copiesCount; $i++) {
                $itemCounter++;
                $items[] = Item::create([
                    'biblio_id' => $bib->biblio_id,
                    'item_code' => 'B' . $itemCounter,
                    'call_number' => $bib->classification . ' Copy ' . ($i + 1),
                    'input_date' => now(),
                    'last_update' => now(),
                ]);
            }
        }

        // 3. Create Members — diverse profiles for simulation
        // Login OPAC: gunakan member_id sebagai username + PIN sebagai password
        // Default PIN = 1234, kecuali MBR-007 (PIN = 5678)
        $defaultPin     = '1234';
        $defaultMpasswd = md5($defaultPin);

        $mtFirst  = \App\Models\MemberType::first();
        $mtSecond = \App\Models\MemberType::skip(1)->first() ?? $mtFirst;
        $mtSId    = $mtFirst ? $mtFirst->member_type_id : null;
        $mtLId    = $mtSecond ? $mtSecond->member_type_id : $mtSId;

        $membersData = [
            // MBR-001: Aktif normal, tidak ada denda
            ['member_id'=>'MBR-001','member_name'=>'Ahmad Rizky Pratama','gender'=>1,'birth_date'=>'1998-05-12','member_type_id'=>$mtSId,'member_address'=>'Jl. Merdeka No. 12, Jakarta Selatan','member_email'=>'ahmad.rizky@email.com','member_phone'=>'081234567001','inst_name'=>'Universitas Indonesia','member_since_date'=>'2024-01-10','register_date'=>'2024-01-10','expire_date'=>now()->addYear()->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
            // MBR-002: Buku terlambat + denda belum lunas
            ['member_id'=>'MBR-002','member_name'=>'Budi Santoso','gender'=>1,'birth_date'=>'1995-08-23','member_type_id'=>$mtSId,'member_address'=>'Jl. Sudirman No. 45, Bandung','member_email'=>'budi.santoso@email.com','member_phone'=>'081234567002','inst_name'=>'Universitas Padjadjaran','member_since_date'=>'2023-03-15','register_date'=>'2023-03-15','expire_date'=>now()->addMonths(8)->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
            // MBR-003: Dosen, keanggotaan hampir habis (15 hari lagi)
            ['member_id'=>'MBR-003','member_name'=>'Dr. Citra Lestari, M.Pd.','gender'=>2,'birth_date'=>'1980-12-01','member_type_id'=>$mtLId,'member_address'=>'Jl. Pendidikan No. 7, Yogyakarta','member_email'=>'citra.lestari@ugm.ac.id','member_phone'=>'081234567003','inst_name'=>'Universitas Gadjah Mada','member_since_date'=>'2022-08-01','register_date'=>'2022-08-01','expire_date'=>now()->addDays(15)->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
            // MBR-004: Keanggotaan KEDALUWARSA — login ditolak
            ['member_id'=>'MBR-004','member_name'=>'Dewi Rahmawati','gender'=>2,'birth_date'=>'2000-03-19','member_type_id'=>$mtSId,'member_address'=>'Jl. Kenanga No. 3, Surabaya','member_email'=>'dewi.rahma@email.com','member_phone'=>'081234567004','inst_name'=>'Universitas Airlangga','member_since_date'=>'2022-01-05','register_date'=>'2022-01-05','expire_date'=>now()->subMonths(3)->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
            // MBR-005: Anggota baru, belum ada riwayat pinjaman
            ['member_id'=>'MBR-005','member_name'=>'Eko Prasetyo','gender'=>1,'birth_date'=>'2003-07-14','member_type_id'=>$mtSId,'member_address'=>'Jl. Mawar No. 8, Malang','member_email'=>'eko.prasetyo@email.com','member_phone'=>'081234567005','inst_name'=>'Universitas Brawijaya','member_since_date'=>now()->subDays(7)->toDateString(),'register_date'=>now()->subDays(7)->toDateString(),'expire_date'=>now()->addYear()->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>1],
            // MBR-006: Banyak riwayat + beberapa pinjaman overdue
            ['member_id'=>'MBR-006','member_name'=>'Fiona Anggraeni','gender'=>2,'birth_date'=>'1997-11-30','member_type_id'=>$mtSId,'member_address'=>'Jl. Flamboyan No. 22, Semarang','member_email'=>'fiona.anggraeni@email.com','member_phone'=>'081234567006','inst_name'=>'Universitas Diponegoro','member_since_date'=>'2023-09-01','register_date'=>'2023-09-01','expire_date'=>now()->addMonths(10)->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
            // MBR-007: PIN berbeda (PIN = 5678) — untuk tes login dengan PIN non-default
            ['member_id'=>'MBR-007','member_name'=>'Gilang Ramadhan','gender'=>1,'birth_date'=>'1999-02-28','member_type_id'=>$mtSId,'member_address'=>'Jl. Anggrek No. 5, Medan','member_email'=>'gilang.ramadhan@email.com','member_phone'=>'081234567007','inst_name'=>'Universitas Sumatera Utara','member_since_date'=>'2024-02-01','register_date'=>'2024-02-01','expire_date'=>now()->addYears(2)->toDateString(),'pin'=>'5678','mpasswd'=>md5('5678'),'is_pending'=>0,'is_new'=>0],
            // MBR-008: Dosen senior, denda sudah dibayar lunas
            ['member_id'=>'MBR-008','member_name'=>'Prof. Hendra Wijaya, Ph.D.','gender'=>1,'birth_date'=>'1975-04-10','member_type_id'=>$mtLId,'member_address'=>'Jl. Profesor No. 1, Makassar','member_email'=>'hendra.wijaya@unhas.ac.id','member_phone'=>'081234567008','inst_name'=>'Universitas Hasanuddin','member_since_date'=>'2020-01-15','register_date'=>'2020-01-15','expire_date'=>now()->addYears(3)->toDateString(),'pin'=>$defaultPin,'mpasswd'=>$defaultMpasswd,'is_pending'=>0,'is_new'=>0],
        ];

        $members = [];
        foreach ($membersData as $m) {
            $members[] = Member::create(array_merge($m, [
                'input_date'  => now()->toDateString(),
                'last_update' => now()->toDateString(),
            ]));
        }


        // 4. Create Loans — skenario realistis per anggota
        // Helper: buat loan
        $makeLoan = function ($memberId, $item, $loanDate, $dueDays = 7, $returnDate = null) {
            $due = (clone $loanDate)->addDays($dueDays);
            Loan::create([
                'item_code'    => $item->item_code,
                'member_id'    => $memberId,
                'loan_date'    => $loanDate->toDateString(),
                'due_date'     => $due->toDateString(),
                'return_date'  => $returnDate ? $returnDate->toDateString() : null,
                'actual'       => $returnDate ? $returnDate->toDateString() : null,
                'is_return'    => $returnDate ? 1 : 0,
                'is_lent'      => 1,
                'renewed'      => 0,
                'loan_rules_id'=> 1,
                'input_date'   => $loanDate,
                'last_update'  => now(),
            ]);
        };

        // — MBR-001: Ahmad Rizky — Normal, semua tepat waktu
        $makeLoan('MBR-001', $items[0], Carbon::now()->subMonths(5)->subDays(3), 14, Carbon::now()->subMonths(5)->addDays(10));
        $makeLoan('MBR-001', $items[1], Carbon::now()->subMonths(3)->subDays(5), 14, Carbon::now()->subMonths(3)->addDays(12));
        $makeLoan('MBR-001', $items[2], Carbon::now()->subMonths(1)->subDays(2), 14, Carbon::now()->subMonths(1)->addDays(7));
        // 2 pinjaman aktif saat ini — masih dalam jatuh tempo
        $makeLoan('MBR-001', $items[3], Carbon::now()->subDays(5), 14);
        $makeLoan('MBR-001', $items[4], Carbon::now()->subDays(3), 14);

        // — MBR-002: Budi Santoso — Pinjaman terlambat + denda belum lunas
        $makeLoan('MBR-002', $items[5], Carbon::now()->subMonths(4), 7, Carbon::now()->subMonths(4)->addDays(9)); // telat 2 hr, lunas
        Fine::create(['fines_date'=>Carbon::now()->subMonths(4)->addDays(9)->toDateString(),'member_id'=>'MBR-002','debet'=>1000,'credit'=>1000,'description'=>'Keterlambatan 2 hari (lunas)']);
        $makeLoan('MBR-002', $items[6], Carbon::now()->subMonths(2), 7, Carbon::now()->subMonths(2)->addDays(12)); // telat 5 hr, belum lunas
        Fine::create(['fines_date'=>Carbon::now()->subMonths(2)->addDays(12)->toDateString(),'member_id'=>'MBR-002','debet'=>2500,'credit'=>0,'description'=>'Keterlambatan 5 hari (belum lunas)']);
        $makeLoan('MBR-002', $items[7], Carbon::now()->subMonths(1)->subDays(5), 7, Carbon::now()->subMonths(1)->addDays(5)); // telat 3 hr, belum lunas
        Fine::create(['fines_date'=>Carbon::now()->subMonths(1)->addDays(5)->toDateString(),'member_id'=>'MBR-002','debet'=>1500,'credit'=>0,'description'=>'Keterlambatan 3 hari (belum lunas)']);
        // 1 pinjaman aktif — OVERDUE 8 hari!
        $makeLoan('MBR-002', $items[8], Carbon::now()->subDays(15), 7);

        // — MBR-003: Dr. Citra — Dosen, riwayat pinjaman banyak, 1 aktif normal
        for ($i = 6; $i >= 2; $i--) {
            $ld = Carbon::now()->subMonths($i)->subDays(rand(1,10));
            $makeLoan('MBR-003', $items[$i % count($items)], $ld, 14, (clone $ld)->addDays(rand(10,13)));
        }
        $makeLoan('MBR-003', $items[2], Carbon::now()->subDays(4), 14); // aktif, 10 hari lagi

        // — MBR-004: Dewi — Keanggotaan expired, hanya ada riwayat lama
        $makeLoan('MBR-004', $items[0], Carbon::now()->subMonths(8), 7, Carbon::now()->subMonths(8)->addDays(6));
        $makeLoan('MBR-004', $items[1], Carbon::now()->subMonths(6), 7, Carbon::now()->subMonths(6)->addDays(7));

        // — MBR-005: Eko — Anggota baru, tidak ada pinjaman sama sekali (sengaja dikosongkan)

        // — MBR-006: Fiona — Banyak riwayat + 2 pinjaman OVERDUE
        for ($i = 6; $i >= 1; $i--) {
            $ld = Carbon::now()->subMonths($i)->subDays(rand(2,15));
            $makeLoan('MBR-006', $items[($i+1) % count($items)], $ld, 7, (clone $ld)->addDays(rand(5,7)));
        }
        $makeLoan('MBR-006', $items[0], Carbon::now()->subDays(20), 7);  // OVERDUE 13 hari!
        $makeLoan('MBR-006', $items[1], Carbon::now()->subDays(12), 7);  // OVERDUE 5 hari!
        Fine::create(['fines_date'=>Carbon::now()->subDays(5)->toDateString(),'member_id'=>'MBR-006','debet'=>6500,'credit'=>0,'description'=>'Keterlambatan 13 hari (belum lunas)']);

        // — MBR-007: Gilang — Normal, beberapa riwayat
        $makeLoan('MBR-007', $items[3], Carbon::now()->subMonths(3), 7, Carbon::now()->subMonths(3)->addDays(6));
        $makeLoan('MBR-007', $items[4], Carbon::now()->subMonths(1), 7, Carbon::now()->subMonths(1)->addDays(5));
        $makeLoan('MBR-007', $items[5], Carbon::now()->subDays(3), 14); // aktif, 11 hari lagi

        // — MBR-008: Prof. Hendra — Dosen senior, denda SUDAH LUNAS semua
        $makeLoan('MBR-008', $items[6], Carbon::now()->subMonths(5), 14, Carbon::now()->subMonths(5)->addDays(18)); // telat 4hr, lunas
        Fine::create(['fines_date'=>Carbon::now()->subMonths(5)->addDays(18)->toDateString(),'member_id'=>'MBR-008','debet'=>2000,'credit'=>2000,'description'=>'Keterlambatan 4 hari (sudah lunas)']);
        $makeLoan('MBR-008', $items[7], Carbon::now()->subMonths(2), 14, Carbon::now()->subMonths(2)->addDays(13));
        $makeLoan('MBR-008', $items[8], Carbon::now()->subDays(6), 14); // aktif, 8 hari lagi



        // 5. Create Serial Subscriptions
        $subHBR = \App\Models\SerialSubscription::create([
            'title' => 'Harvard Business Review',
            'publisher_name' => 'Harvard Business Publishing',
            'period_start' => now()->subMonths(6)->toDateString(),
            'period_end' => now()->addMonths(6)->toDateString(),
            'frequency' => 'Bulanan',
            'notes' => 'Rak Majalah D-1',
        ]);

        $subNature = \App\Models\SerialSubscription::create([
            'title' => 'Nature Journal',
            'publisher_name' => 'Nature Portfolio',
            'period_start' => now()->subMonths(3)->toDateString(),
            'period_end' => now()->addMonths(9)->toDateString(),
            'frequency' => 'Mingguan',
            'notes' => 'Rak Jurnal J-3',
        ]);

        // 6. Create Kardex issues for Harvard Business Review
        for ($i = 6; $i >= 1; $i--) {
            $pubDate = now()->subMonths($i)->setDate(now()->year, now()->subMonths($i)->month, 1);
            $isReceived = ($i > 1); // Older issues are received, recent one might be missing or expected
            
            \App\Models\SerialKardex::create([
                'subscription_id' => $subHBR->subscription_id,
                'issue_no' => 'Vol. 104 No. ' . (12 - $i),
                'publish_date' => $pubDate->toDateString(),
                'receive_date' => $isReceived ? $pubDate->addDays(5)->toDateString() : null,
                'status' => $isReceived ? 'received' : (rand(0, 1) === 1 ? 'expected' : 'missing'),
            ]);
        }

        // 7. Create Kardex issues for Nature Journal
        for ($i = 10; $i >= 1; $i--) {
            $pubDate = now()->subWeeks($i);
            $isReceived = ($i > 2);

            \App\Models\SerialKardex::create([
                'subscription_id' => $subNature->subscription_id,
                'issue_no' => 'Issue #' . (5400 - $i),
                'publish_date' => $pubDate->toDateString(),
                'receive_date' => $isReceived ? $pubDate->addDays(3)->toDateString() : null,
                'status' => $isReceived ? 'received' : (rand(0, 1) === 1 ? 'expected' : 'missing'),
            ]);
        }

        // 8. Create Stock Take Dummy Sessions
        // A finished session from last year
        $finishedSession = \App\Models\StockTakeSession::create([
            'session_name' => 'Stok Opname Tahunan 2025',
            'status' => 'finished',
            'created_at' => now()->subYear(),
            'updated_at' => now()->subYear()->addDays(5),
        ]);

        // An active session for current simulation
        $activeSession = \App\Models\StockTakeSession::create([
            'session_name' => 'Stok Opname Buku IT & Komputer 2026',
            'status' => 'active',
        ]);

        // Scan some random items into the active session
        $randomItemsToVerify = Item::inRandomOrder()->limit(8)->get();
        foreach ($randomItemsToVerify as $item) {
            \App\Models\StockTakeLog::create([
                'session_id' => $activeSession->session_id,
                'item_code' => $item->item_code,
                'created_at' => now()->subHours(rand(1, 5)),
            ]);
        }
    }
}
// Seeder link user to org units
