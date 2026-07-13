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

        // 3. Create active Members
        $membersData = [
            ['id' => 'M1001', 'name' => 'Ahmad Rian'],
            ['id' => 'M1002', 'name' => 'Budi Santoso'],
            ['id' => 'M1003', 'name' => 'Citra Lestari'],
            ['id' => 'M1004', 'name' => 'Dewi Sartika'],
            ['id' => 'M1005', 'name' => 'Eko Prasetyo'],
            ['id' => 'M1006', 'name' => 'Fiona Putri'],
            ['id' => 'M1007', 'name' => 'Gilang Ramadhan'],
            ['id' => 'M1008', 'name' => 'Hendra Wijaya'],
        ];

        $members = [];
        foreach ($membersData as $m) {
            $members[] = Member::create([
                'member_id' => $m['id'],
                'member_name' => $m['name'],
                'gender' => rand(0, 1),
                'expire_date' => now()->addYear()->toDateString(),
                'input_date' => now()->toDateString(),
                'last_update' => now()->toDateString(),
            ]);
        }

        // 4. Create historic and active Loans spread across the last 6 months
        $monthsAgo = 6;
        $loanIdCounter = 100;
        
        foreach ($members as $member) {
            // Each member has borrowed multiple books in the past
            for ($m = $monthsAgo; $m >= 0; $m--) {
                $loanCount = rand(1, 2);
                for ($l = 0; $l < $loanCount; $l++) {
                    $randomItem = $items[array_rand($items)];
                    $loanDate = Carbon::now()->subMonths($m)->subDays(rand(1, 25));
                    $dueDate = (clone $loanDate)->addDays(7);
                    
                    // Most past loans are returned, but some are returned late (fines generated)
                    // Current month (m = 0) loans might still be active (is_return = 0)
                    $isReturned = ($m > 0) ? 1 : (rand(0, 1) === 1);
                    $returnDate = null;
                    if ($isReturned) {
                        // Some returned late
                        $isLate = (rand(1, 3) === 1);
                        if ($isLate) {
                            $lateDays = rand(1, 10);
                            $returnDate = (clone $dueDate)->addDays($lateDays);
                            
                            // Generate Fine
                            $fineAmount = $lateDays * 500;
                            $isPaid = (rand(0, 1) === 1);
                            Fine::create([
                                'fines_date' => $returnDate->toDateString(),
                                'member_id' => $member->member_id,
                                'debet' => $fineAmount,
                                'credit' => $isPaid ? $fineAmount : 0,
                                'description' => "Keterlambatan buku {$randomItem->item_code} selama {$lateDays} hari",
                            ]);
                        } else {
                            $returnDate = (clone $dueDate)->subDays(rand(0, 3));
                        }
                    }

                    Loan::create([
                        'item_code' => $randomItem->item_code,
                        'member_id' => $member->member_id,
                        'loan_date' => $loanDate->toDateString(),
                        'due_date' => $dueDate->toDateString(),
                        'return_date' => $returnDate ? $returnDate->toDateString() : null,
                        'actual' => $returnDate ? $returnDate->toDateString() : null,
                        'is_return' => $isReturned ? 1 : 0,
                        'is_lent' => 1,
                        'renewed' => 0,
                        'loan_rules_id' => 1,
                        'input_date' => $loanDate,
                        'last_update' => now(),
                    ]);
                }
            }
        }

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
    }
}
