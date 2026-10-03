<?php

namespace Tests\Feature;

use App\Models\AppListing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_video_link_is_saved_when_an_app_is_created(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_trusted' => true]);
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $this->actingAs($user)->post(route('my-apps.store'), [
            'name' => 'Video App',
            'author' => $user->name,
            'description' => 'An app with a demo video.',
            'category_id' => $category->id,
            'platform' => 'web',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'is_published' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('app_listings', [
            'name' => 'Video App',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function test_latest_app_with_video_is_featured_ahead_of_newer_apps_without_video(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $videoApp = $this->createPublishedApp($user, $category, [
            'name' => 'Video App',
            'slug' => 'video-app',
            'video_url' => 'https://cdn.example.com/demo.mp4',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
        $this->createPublishedApp($user, $category, [
            'name' => 'Newer App',
            'slug' => 'newer-app',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Video App')
            ->assertSee($videoApp->video_url, false)
            ->assertSee('featured video');
    }

    public function test_video_helpers_only_embed_supported_providers(): void
    {
        $app = new AppListing(['video_url' => 'https://youtu.be/dQw4w9WgXcQ']);

        $this->assertSame(
            'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0&playsinline=1&autoplay=1&mute=1',
            $app->videoEmbedUrl(true)
        );

        $app->video_url = 'https://example.com/video-page';
        $this->assertNull($app->videoEmbedUrl());
        $this->assertNull($app->directVideoUrl());
    }

    private function createPublishedApp(User $user, Category $category, array $attributes): AppListing
    {
        return AppListing::query()->create(array_merge([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'platform' => 'web',
            'name' => 'Published App',
            'author' => $user->name,
            'slug' => 'published-app',
            'description' => 'Published description.',
            'is_published' => true,
            'approval_status' => AppListing::APPROVAL_APPROVED,
        ], $attributes));
    }
}
