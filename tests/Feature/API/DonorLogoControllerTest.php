<?php

namespace Tests\Feature;

use App\Models\Assets\DonorLogo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DonorLogoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('s3');
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function store_creates_logos_and_stores_files()
    {
        Storage::fake('s3');

        $file1 = UploadedFile::fake()->image('logo1.jpg');
        $file2 = UploadedFile::fake()->image('logo2.png');

        $response = $this->post('/api/donor-logos', [
            // the controller expects 'files' => array of UploadedFile
            'files' => [$file1, $file2],
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(2, 'data');

        $disk = config('medialibrary.disk_name', 's3');

        $this->assertDatabaseCount('donor_logos', 2);

        $data = $response->json('data');

        foreach ($data as $item) {
            $this->assertDatabaseHas('donor_logos', [
                'id' => $item['id'],
            ]);

            $logo = DonorLogo::findOrFail($item['id']);
            $media = $logo->getFirstMedia('donors');

            $this->assertNotNull(
                $media,
                "Expected a media record in the 'donors' collection for DonorLogo ID {$logo->id}"
            );

            Storage::disk($disk)
                ->assertExists($media->getPathRelativeToRoot());
        }
    }

    #[Test]
    public function destroy_deletes_single_logo()
    {
        $logo = DonorLogo::factory()->create();
        $logo->addMedia(UploadedFile::fake()->image('to-delete.jpg'))
            ->toMediaCollection();

        $this->assertDatabaseHas('donor_logos', ['id' => $logo->id]);
        $this->assertDatabaseCount('media', 1);

        $response = $this->deleteJson("/api/donor-logos/{$logo->id}");

        $response->assertOk()
            ->assertJson(['deleted' => [$logo->id]]);

        $this->assertDatabaseMissing('donor_logos', ['id' => $logo->id]);
    }

    #[Test]
    public function bulk_destroy_deletes_multiple_logos()
    {
        $logos = DonorLogo::factory()->count(3)->create()->each(function ($logo) {
            $logo->addMedia(UploadedFile::fake()->image("logo{$logo->id}.png"))
                ->toMediaCollection();
        });

        $idsToDelete = $logos->pluck('id')->take(2)->all();

        $response = $this->postJson('/api/donor-logos/bulk-delete', [
            'ids' => $idsToDelete,
        ]);

        $response->assertOk()
            ->assertJson(['deleted' => $idsToDelete]);

        foreach ($idsToDelete as $id) {
            $this->assertDatabaseMissing('donor_logos', ['id' => $id]);
        }
        $this->assertDatabaseHas('donor_logos', ['id' => $logos->last()->id]);
    }
}
