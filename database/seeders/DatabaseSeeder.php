<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Biblio;
use App\Models\Gmd;
use App\Models\Item;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::create([
            'username' => 'admin',
            'realname' => 'Administrator',
            'passwd' => Hash::make('admin123'), // bcrypt hashed
            'email' => 'admin@sirita.local',
            'groups' => serialize(['1']), // Serialized PHP array matching SLiMS format
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        // 2. Create GMD types
        $gmdText = Gmd::create([
            'gmd_code' => 'TE',
            'gmd_name' => 'Text',
            'input_date' => now()->toDateString(),
        ]);

        $gmdMedia = Gmd::create([
            'gmd_code' => 'CD',
            'gmd_name' => 'CD-ROM',
            'input_date' => now()->toDateString(),
        ]);

        // 3. Create Publishers
        $pubOReilly = Publisher::create([
            'publisher_name' => "O'Reilly Media",
            'input_date' => now()->toDateString(),
        ]);

        $pubPackt = Publisher::create([
            'publisher_name' => 'Packt Publishing',
            'input_date' => now()->toDateString(),
        ]);

        // 4. Create Authors
        $authorTaylor = Author::create([
            'author_name' => 'Taylor Otwell',
            'authority_type' => 'p',
            'input_date' => now()->toDateString(),
        ]);

        $authorBob = Author::create([
            'author_name' => 'Robert C. Martin (Uncle Bob)',
            'authority_type' => 'p',
            'input_date' => now()->toDateString(),
        ]);

        $authorArie = Author::create([
            'author_name' => 'Arie Nugraha',
            'authority_type' => 'p',
            'input_date' => now()->toDateString(),
        ]);

        // 5. Create Bibliographies (Books)
        $book1 = Biblio::create([
            'gmd_id' => $gmdText->gmd_id,
            'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
            'edition' => '1st Edition',
            'isbn_issn' => '978-0132350884',
            'publisher_id' => $pubOReilly->publisher_id,
            'publish_year' => '2008',
            'call_number' => '005.1 MAR c',
            'classification' => '005.1',
            'notes' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees.',
            'input_date' => now(),
            'last_update' => now(),
        ]);

        $book2 = Biblio::create([
            'gmd_id' => $gmdText->gmd_id,
            'title' => 'Laravel Up & Running: A Framework for Building Modern PHP Apps',
            'edition' => '2nd Edition',
            'isbn_issn' => '978-1492041214',
            'publisher_id' => $pubOReilly->publisher_id,
            'publish_year' => '2019',
            'call_number' => '005.2 SHA l',
            'classification' => '005.2',
            'notes' => 'What sets Laravel apart from other PHP web frameworks? Speed and simplicity, for starters.',
            'input_date' => now(),
            'last_update' => now(),
        ]);

        $book3 = Biblio::create([
            'gmd_id' => $gmdText->gmd_id,
            'title' => 'Senayan Library Management System (SLiMS) Bulian Guide',
            'edition' => 'bulian-9',
            'isbn_issn' => '978-9791234567',
            'publisher_id' => $pubPackt->publisher_id,
            'publish_year' => '2020',
            'call_number' => '025.3 NUG s',
            'classification' => '025.3',
            'notes' => 'Dokumentasi panduan resmi penggunaan sistem otomasi perpustakaan Senayan versi 9 Bulian.',
            'input_date' => now(),
            'last_update' => now(),
        ]);

        // 6. Associate Books with Authors (Many-to-Many via pivot)
        $book1->authors()->attach($authorBob->author_id, ['level' => 1]);
        $book2->authors()->attach($authorTaylor->author_id, ['level' => 1]);
        $book3->authors()->attach($authorArie->author_id, ['level' => 1]);

        // 7. Create physical Items (Copies)
        Item::create([
            'biblio_id' => $book1->biblio_id,
            'item_code' => 'B00001',
            'call_number' => '005.1 MAR c',
            'location_id' => 'R01', // Shelf 1
            'input_date' => now(),
        ]);

        Item::create([
            'biblio_id' => $book2->biblio_id,
            'item_code' => 'B00002',
            'call_number' => '005.2 SHA l',
            'location_id' => 'R01',
            'input_date' => now(),
        ]);

        Item::create([
            'biblio_id' => $book2->biblio_id,
            'item_code' => 'B00003',
            'call_number' => '005.2 SHA l',
            'location_id' => 'R02', // Copy 2 on Shelf 2
            'input_date' => now(),
        ]);

        Item::create([
            'biblio_id' => $book3->biblio_id,
            'item_code' => 'B00004',
            'call_number' => '025.3 NUG s',
            'location_id' => 'R03',
            'input_date' => now(),
        ]);

        // 8. Create Member Types
        $memberTypeStd = \App\Models\MemberType::create([
            'member_type_name' => 'Standar',
            'loan_limit' => 3,
            'loan_periode' => 7,
            'enable_reserve' => 1,
            'reserve_limit' => 2,
            'member_periode' => 365,
            'reborrow_limit' => 2,
            'fine_each_day' => 500,
            'grace_periode' => 0,
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        // 9. Create Dummy Members
        Member::create([
            'member_id' => 'M00001',
            'member_name' => 'Aldi Wijaya',
            'gender' => 1,
            'member_type_id' => $memberTypeStd->member_type_id,
            'member_email' => 'aldi@example.com',
            'expire_date' => now()->addYears(2)->toDateString(),
            'register_date' => now()->toDateString(),
            'member_since_date' => now()->toDateString(),
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        Member::create([
            'member_id' => 'M00002',
            'member_name' => 'Budi Santoso',
            'gender' => 1,
            'member_type_id' => $memberTypeStd->member_type_id,
            'member_email' => 'budi@example.com',
            'expire_date' => now()->addYears(2)->toDateString(),
            'register_date' => now()->toDateString(),
            'member_since_date' => now()->toDateString(),
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);
    }
}
