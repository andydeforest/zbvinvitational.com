<?php

namespace Tests\Feature;

use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DonorsPageTest extends TestCase
{
    use RefreshDatabase;

    protected string $mediaDisk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mediaDisk = (string) config('media-library.disk_name', 'public');
        Storage::fake($this->mediaDisk);
    }

    #[Test]
    public function it_only_returns_donor_logos_that_have_media_for_the_public_donors_page()
    {
        $logoWithMedia = DonorLogo::factory()->create();
        $logoWithMedia->addMediaFromString('logo-content')
            ->usingFileName('logo.png')
            ->toMediaCollection('donors');

        $logoWithoutMedia = DonorLogo::factory()->create();

        $response = $this->get(route('donors'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Donors')
            ->has('logos.data', 1)
            ->where('logos.data.0.id', $logoWithMedia->id)
            ->missing('logos.data.1')
        );

        $this->assertDatabaseHas('donor_logos', ['id' => $logoWithoutMedia->id]);
    }

    #[Test]
    public function donor_logo_resource_uses_legacy_flat_path_when_that_object_exists()
    {
        $logo = DonorLogo::factory()->create();
        $logo->addMediaFromString('logo-content')
            ->usingFileName('legacy-logo.png')
            ->toMediaCollection('donors');

        $media = $logo->getFirstMedia('donors');

        $this->assertNotNull($media);

        $currentPath = $media->getPathRelativeToRoot();
        $legacyPath = "donor-logos/{$media->file_name}";

        Storage::disk($this->mediaDisk)->move($currentPath, $legacyPath);

        $payload = DonorLogoResource::make($logo->fresh())->resolve();

        $this->assertStringEndsWith($legacyPath, $payload['media']['original_url']);
    }
}
