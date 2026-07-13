<?php

namespace Tests\Feature;

use App\Models\Biblio;
use App\Models\Fine;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CirculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful item checkout.
     */
    public function test_item_can_be_checked_out_to_member()
    {
        // 1. Create a Member
        $member = Member::create([
            'member_id' => 'M0001',
            'member_name' => 'Jane Doe',
            'gender' => 1,
            'expire_date' => now()->addYear()->toDateString(),
        ]);

        // 2. Create a Bibliography
        $biblio = Biblio::create([
            'title' => 'Laravel Up & Running',
            'isbn_issn' => '978-1492041214',
        ]);

        // 3. Create a physical Item linked to Bibliography
        $item = Item::create([
            'biblio_id' => $biblio->biblio_id,
            'item_code' => 'B0001',
            'input_date' => now(),
        ]);

        // 4. Hit checkout endpoint (Simulate request to CirculationController)
        $response = $this->post(route('admin.circulation.checkout', ['member_id' => $member->member_id]), [
            'item_code' => 'B0001',
        ]);

        // Verify redirect back with success message
        $response->assertRedirect();
        
        // Assert loan is registered in database
        $this->assertDatabaseHas('loan', [
            'member_id' => 'M0001',
            'item_code' => 'B0001',
            'is_return' => 0,
            'is_lent' => 1,
        ]);
    }

    /**
     * Test item return and overdue fine calculation.
     */
    public function test_item_return_calculates_overdue_fines()
    {
        // 1. Create Member and Item
        $member = Member::create([
            'member_id' => 'M0002',
            'member_name' => 'John Doe',
            'gender' => 1,
            'expire_date' => now()->addYear()->toDateString(),
        ]);

        $biblio = Biblio::create([
            'title' => 'Clean Code',
        ]);

        $item = Item::create([
            'biblio_id' => $biblio->biblio_id,
            'item_code' => 'B0002',
            'input_date' => now(),
        ]);

        // 2. Register an OVERDUE loan transaction (due date was 3 days ago)
        $loan = Loan::create([
            'item_code' => 'B0002',
            'member_id' => 'M0002',
            'loan_date' => now()->subDays(10)->toDateString(),
            'due_date' => now()->subDays(3)->toDateString(),
            'renewed' => 0,
            'loan_rules_id' => 1,
            'is_lent' => 1,
            'is_return' => 0,
            'input_date' => now()->subDays(10),
        ]);

        // 3. Return the book via checkin endpoint
        $response = $this->post(route('admin.circulation.checkin', ['loan_id' => $loan->loan_id]));

        $response->assertRedirect();

        // 4. Assert loan status is returned
        $this->assertDatabaseHas('loan', [
            'loan_id' => $loan->loan_id,
            'is_return' => 1,
        ]);

        // 5. Assert fine is calculated (3 days overdue * Rp 500 = Rp 1500)
        $this->assertDatabaseHas('fines', [
            'member_id' => 'M0002',
            'debet' => 1500,
        ]);
    }
}
