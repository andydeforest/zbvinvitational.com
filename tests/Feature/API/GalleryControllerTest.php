<?php

namespace Tests\Feature;

use App\Models\Assets\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $mediaDisk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mediaDisk = (string) config('media-library.disk_name', 'public');
        Storage::fake($this->mediaDisk);
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function store_creates_photos_and_stores_files_on_s3(): void
    {
        $file1 = UploadedFile::fake()->image('test1.jpg', 600, 600)->size(500);
        $file2 = UploadedFile::fake()->image('test2.png', 300, 300)->size(300);

        $year = now()->year;

        $response = $this->post(
            '/api/gallery',
            [
                'year' => $year,
                'files' => [$file1, $file2],
            ]
        );

        $response->assertStatus(201)
            ->assertJsonCount(2, 'data');

        $this->assertDatabaseCount('photos', 2);

        $photos = Photo::orderBy('id')->get();
        $this->assertCount(2, $photos);

        foreach ($photos as $photo) {
            $media = $photo->getFirstMedia('gallery');
            $this->assertNotNull($media, "Expected each Photo to have one media item in 'gallery'.");

            // use getPathRelativeToRoot() to get path relative to the media disk root
            $relativePath = $media->getPathRelativeToRoot();
            $this->assertStringStartsWith(
                "gallery/{$year}/",
                $relativePath,
                "Expected media path to start with \"gallery/{$year}/\", got \"{$relativePath}\"."
            );

            Storage::disk($this->mediaDisk)->assertExists($relativePath);
        }

        $response->assertJsonFragment([
            'year' => $year,
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'year',
                    'url',
                    'media' => [
                        'id',
                        'uuid',
                        'file_name',
                        'mime_type',
                        'original_url',
                    ],
                ],
            ],
        ]);
    }

    #[Test]
    public function store_accepts_gallery_images_up_to_the_media_library_limit(): void
    {
        $file = UploadedFile::fake()->image('large-gallery-photo.jpg', 2400, 1800)->size(8 * 1024);

        $response = $this->post('/api/gallery', [
            'year' => now()->year,
            'files' => [$file],
        ]);

        $response->assertCreated()
            ->assertJsonCount(1, 'data');

        $this->assertDatabaseCount('photos', 1);
    }

    #[Test]
    public function store_cleans_up_created_photos_when_media_storage_fails(): void
    {
        config()->set('media-library.disk_name', 'missing-disk');

        $response = $this->post('/api/gallery', [
            'year' => now()->year,
            'files' => [UploadedFile::fake()->image('broken-upload.jpg')],
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Gallery upload failed: There is no filesystem disk named `missing-disk`',
            ]);

        $this->assertDatabaseCount('photos', 0);
        $this->assertDatabaseCount('media', 0);
    }

    #[Test]
    public function destroy_deletes_single_photo_and_its_media(): void
    {
        $photo = Photo::create(['year' => now()->year]);
        $fakeImage = UploadedFile::fake()->image('destroy.jpg')->size(200);
        $photo->addMedia($fakeImage)
            ->usingFileName('destroy-test.jpg')
            ->toMediaCollection('gallery');

        $media = $photo->getFirstMedia('gallery');
        $relativePath = $media->getPathRelativeToRoot();
        Storage::disk($this->mediaDisk)->assertExists($relativePath);

        $response = $this->deleteJson("/api/gallery/{$photo->id}");

        $response->assertStatus(200);

        $response->assertExactJson(['deleted' => [$photo->id]]);

        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);

        Storage::disk($this->mediaDisk)->assertMissing($relativePath);
    }

    #[Test]
    public function bulk_destroy_deletes_multiple_photos_and_media(): void
    {
        $photos = collect();
        $relativePaths = [];

        for ($i = 0; $i < 3; $i++) {
            $p = Photo::create(['year' => now()->year]);
            $fake = UploadedFile::fake()->image("bulk{$i}.jpg")->size(100 + $i * 50);
            $p->addMedia($fake)
                ->usingFileName("bulk-test-{$i}.jpg")
                ->toMediaCollection('gallery');

            $media = $p->getFirstMedia('gallery');
            $this->assertNotNull($media, "Expected media for Photo ID {$p->id}.");

            $relativePath = $media->getPathRelativeToRoot();
            Storage::disk($this->mediaDisk)->assertExists($relativePath);
            $relativePaths[] = $relativePath;

            $photos->push($p);
        }

        $idsToDelete = $photos->pluck('id')->all();

        $response = $this->postJson('/api/gallery/bulk-delete', [
            'ids' => $idsToDelete,
        ]);

        $response->assertStatus(200)
            ->assertExactJson(['deleted' => $idsToDelete]);

        foreach ($idsToDelete as $id) {
            $this->assertDatabaseMissing('photos', ['id' => $id]);
        }

        foreach ($relativePaths as $relativePath) {
            Storage::disk($this->mediaDisk)->assertMissing($relativePath);
        }
    }
}
