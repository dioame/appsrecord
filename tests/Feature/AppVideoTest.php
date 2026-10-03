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

    public function test_homepage_shows_multiple_published_app_videos(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $videoApp = $this->createPublishedApp($user, $category, [
            'name' => 'First Video App',
            'slug' => 'first-video-app',
            'video_url' => 'https://cdn.example.com/first-demo.mp4',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
        $newerVideoApp = $this->createPublishedApp($user, $category, [
            'name' => 'Second Video App',
            'slug' => 'second-video-app',
            'video_url' => 'https://cdn.example.com/second-demo.webm',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Featured videos')
            ->assertSee('First Video App')
            ->assertSee('Second Video App')
            ->assertSee($videoApp->video_url, false)
            ->assertSee($newerVideoApp->video_url, false);
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

    public function test_public_app_page_displays_its_video_when_available(): void
    {
        $user = User::factory()->create();
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);
        $app = $this->createPublishedApp($user, $category, [
            'name' => 'Demo App',
            'slug' => 'demo-app',
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
        ]);

        $this->get(route('apps.public', $app->slug))
            ->assertOk()
            ->assertSee('App video')
            ->assertSee('See Demo App in action.')
            ->assertSee('youtube-nocookie.com', false)
            ->assertSee('Demo App app video');
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
