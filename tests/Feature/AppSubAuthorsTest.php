<?php

namespace Tests\Feature;

use App\Models\AppListing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppSubAuthorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_app_with_sub_authors(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::query()->create([
            'name' => 'Tools',
            'slug' => 'tools',
        ]);

        $response = $this->actingAs($user)->post(route('my-apps.store'), [
            'name' => 'Team App',
            'author' => 'Lead Dev',
            'sub_authors' => [
                ['name' => 'Alex Helper', 'email' => 'alex@example.com'],
                ['name' => 'Sam Quiet', 'email' => ''],
                ['name' => '', 'email' => 'ignored@example.com'],
            ],
            'description' => 'Built with collaborators.',
            'category_id' => $category->id,
            'platform' => 'web',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));

        $app = AppListing::query()->where('name', 'Team App')->first();
        $this->assertNotNull($app);
        $this->assertSame('Lead Dev', $app->author);
        $this->assertCount(2, $app->subAuthorEntries());
        $this->assertSame('Alex Helper', $app->subAuthorEntries()[0]['name']);
        $this->assertSame('alex@example.com', $app->subAuthorEntries()[0]['email']);
        $this->assertSame('Sam Quiet', $app->subAuthorEntries()[1]['name']);
        $this->assertNull($app->subAuthorEntries()[1]['email']);
    }

    public function test_public_app_page_shows_sub_authors(): void
    {
        $user = User::factory()->create(['slug' => 'lead-dev']);
        $category = Category::query()->create([
            'name' => 'Tools',
            'slug' => 'tools',
        ]);

        $app = AppListing::query()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'platform' => 'web',
            'name' => 'Team App',
            'author' => 'Lead Dev',
            'sub_authors' => [
                ['name' => 'Alex Helper', 'email' => 'alex@example.com'],
            ],
            'slug' => 'team-app',
            'description' => 'Built with collaborators.',
            'is_published' => true,
        ]);

        $this->get(route('apps.public', $app->slug))
            ->assertOk()
            ->assertSee('Sub authors')
            ->assertSee('Alex Helper')
            ->assertSee('alex@example.com');
    }
}
