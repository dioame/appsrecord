<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                '_section' => 'profile',
                'name' => 'Test User',
                'slug' => 'test-user',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test-user', $user->slug);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                '_section' => 'profile',
                'name' => 'Test User',
                'slug' => $user->slug ?? 'test-user',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_cv_details_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                '_section' => 'cv',
                'headline' => 'Full-stack developer',
                'location' => 'Manila',
                'skills' => ['Laravel', 'React'],
                'experience' => [
                    [
                        'title' => 'Developer',
                        'company' => 'Acme',
                        'period' => '2022 – Present',
                        'description' => 'Built products',
                    ],
                ],
                'education' => [
                    [
                        'school' => 'State University',
                        'degree' => 'B.S. CS',
                        'period' => '2018 – 2022',
                        'description' => '',
                    ],
                ],
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Full-stack developer', $user->headline);
        $this->assertSame('Manila', $user->location);
        $this->assertSame(['Laravel', 'React'], $user->skillList());
        $this->assertCount(1, $user->experienceEntries());
        $this->assertSame('Developer', $user->experienceEntries()[0]['title']);
        $this->assertTrue($user->hasCvContent());
    }

    public function test_clients_can_view_creator_cv_on_public_portfolio(): void
    {
        $user = User::factory()->create([
            'slug' => 'jane-doe',
            'headline' => 'Product designer',
            'skills' => ['Figma', 'Research'],
            'experience' => [
                [
                    'title' => 'Designer',
                    'company' => 'Studio',
                    'period' => '2021 – Present',
                    'description' => 'Shipped apps',
                ],
            ],
        ]);

        $response = $this->get('/creators/jane-doe?tab=cv');

        $response
            ->assertOk()
            ->assertSee('Product designer')
            ->assertSee('Figma')
            ->assertSee('Curriculum vitae')
            ->assertSee('Designer')
            ->assertSee('Preview & download', false);
    }

    public function test_clients_can_preview_cv_templates_before_download(): void
    {
        $user = User::factory()->create([
            'slug' => 'jane-doe',
            'headline' => 'Product designer',
            'skills' => ['Figma'],
        ]);

        $this->get('/creators/jane-doe/cv')
            ->assertOk()
            ->assertSee('CV preview')
            ->assertSee('Classic')
            ->assertSee('Executive')
            ->assertSee('Showcase')
            ->assertSee('Download PDF');

        $this->get('/creators/jane-doe/cv?template=executive')
            ->assertOk()
            ->assertSee('Executive template');

        $this->get('/creators/jane-doe/cv?template=showcase')
            ->assertOk()
            ->assertSee('Showcase template');
    }

    public function test_clients_can_download_creator_cv_as_pdf(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'slug' => 'jane-doe',
            'headline' => 'Product designer',
            'skills' => ['Figma', 'Research'],
            'experience' => [
                [
                    'title' => 'Designer',
                    'company' => 'Studio',
                    'period' => '2021 – Present',
                    'description' => 'Shipped apps',
                ],
            ],
        ]);

        $category = \App\Models\Category::query()->create([
            'name' => 'Productivity',
            'slug' => 'productivity',
        ]);

        $logoPath = 'apps/logos/test-logo.jpg';
        $shotPath = 'apps/screenshots/test-shot.jpg';

        $this->createJpegFixture($logoPath, 80, 80, [0, 122, 255]);
        $this->createJpegFixture($shotPath, 320, 200, [20, 180, 120]);

        \App\Models\AppListing::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'platform' => 'web',
            'name' => 'Focus Board',
            'slug' => 'focus-board',
            'description' => 'A planning board for teams.',
            'link' => 'https://example.com/focus',
            'logo' => $logoPath,
            'images' => [$shotPath],
            'is_published' => true,
        ]);

        $media = \App\Support\CvTemplates::media($user->fresh(), $user->publishedApps()->get());
        $this->assertNotNull($media['apps'][$user->publishedApps()->first()->id]['logo']);
        $this->assertNotEmpty($media['apps'][$user->publishedApps()->first()->id]['shots']);

        $response = $this->get('/creators/jane-doe/cv.pdf?template=showcase');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename=jane-doe-cv-showcase.pdf');

        $this->assertGreaterThan(1000, strlen($response->getContent()));
    }

    public function test_cv_pdf_returns_not_found_when_creator_has_no_cv(): void
    {
        User::factory()->create([
            'slug' => 'no-cv-yet',
            'bio' => null,
            'headline' => null,
            'skills' => null,
            'experience' => null,
            'education' => null,
        ]);

        $this->get('/creators/no-cv-yet/cv.pdf')->assertNotFound();
        $this->get('/creators/no-cv-yet/cv')->assertNotFound();
    }

    protected function createJpegFixture(string $relativePath, int $width, int $height, array $rgb): void
    {
        $fullPath = storage_path('app/public/'.$relativePath);
        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $color);
        imagejpeg($image, $fullPath, 90);
        imagedestroy($image);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
