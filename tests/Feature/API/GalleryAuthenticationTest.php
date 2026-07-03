<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GalleryAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_gallery_upload_returns_json_instead_of_redirecting(): void
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/gallery', [
                'year' => now()->year,
                'files' => [UploadedFile::fake()->image('unauthorized.jpg')],
            ]);

        $response->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
