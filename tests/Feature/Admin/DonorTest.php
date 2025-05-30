<?php

namespace Tests\Feature\Admin;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DonorTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_create_multiple_donors()
    {
        $this->authenticate();

        $response = $this->post(route('admin.donors.store'), [
            'donors' => [
                ['name' => 'Donor One'],
                ['name' => 'Donor Two'],
            ],
        ]);

        $response->assertRedirect(route('admin.donors.index'));
        $this->assertDatabaseCount('donors', 2);
        $this->assertDatabaseHas('donors', ['name' => 'Donor One']);
        $this->assertDatabaseHas('donors', ['name' => 'Donor Two']);
    }

    #[Test]
    public function it_can_update_multiple_donors()
    {
        $this->authenticate();

        $donors = Donor::factory()->count(2)->create();

        $payload = [
            'donors' => [
                ['id' => $donors[0]->id, 'name' => 'Updated A'],
                ['id' => $donors[1]->id, 'name' => 'Updated B'],
            ],
        ];

        $response = $this->put(route('admin.donors.update'), $payload);
        $response->assertRedirect(route('admin.donors.index'));

        $this->assertDatabaseHas('donors', ['id' => $donors[0]->id, 'name' => 'Updated A']);
        $this->assertDatabaseHas('donors', ['id' => $donors[1]->id, 'name' => 'Updated B']);
    }

    #[Test]
    public function it_can_delete_multiple_donors()
    {
        $this->authenticate();

        $donors = Donor::factory()->count(2)->create();

        $response = $this->put(route('admin.donors.update'), [
            'donors' => [],
            'deleted' => $donors->pluck('id')->toArray(),
        ]);
        $response->assertRedirect(route('admin.donors.index'));

        foreach ($donors as $donor) {
            $this->assertDatabaseMissing('donors', ['id' => $donor->id]);
        }
    }
}
