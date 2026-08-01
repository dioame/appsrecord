<?php

namespace Tests\Feature;

use App\Models\AppListing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTrustAndApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_make_admin_command_promotes_user(): void
    {
        $user = User::factory()->create(['email' => 'dioamejade@gmail.com']);

        $this->artisan('user:make-admin', ['email' => 'dioamejade@gmail.com'])
            ->assertSuccessful();

        $user->refresh();

        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->isTrusted());
    }

    public function test_untrusted_user_publish_requires_approval(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_trusted' => false]);
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $this->actingAs($user)->post(route('my-apps.store'), [
            'name' => 'Needs Review',
            'author' => $user->name,
            'description' => 'Waiting for approval.',
            'category_id' => $category->id,
            'platform' => 'web',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'is_published' => '1',
        ])->assertRedirect(route('dashboard'));

        $app = AppListing::query()->where('name', 'Needs Review')->firstOrFail();

        $this->assertTrue($app->is_published);
        $this->assertTrue($app->isPendingApproval());
        $this->get(route('apps.public', $app->slug))->assertNotFound();
    }

    public function test_trusted_user_publishes_immediately(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_trusted' => true]);
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $this->actingAs($user)->post(route('my-apps.store'), [
            'name' => 'Trusted App',
            'author' => $user->name,
            'description' => 'Goes live now.',
            'category_id' => $category->id,
            'platform' => 'web',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'is_published' => '1',
        ])->assertRedirect(route('dashboard'));

        $app = AppListing::query()->where('name', 'Trusted App')->firstOrFail();

        $this->assertTrue($app->isLive());
        $this->get(route('apps.public', $app->slug))->assertOk();
    }

    public function test_admin_can_toggle_trusted_and_approve_apps(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@appsrecord.test',
            'role' => User::ROLE_ADMIN,
            'is_trusted' => true,
        ]);
        $developer = User::factory()->create(['is_trusted' => false]);
        $category = Category::query()->create(['name' => 'Tools', 'slug' => 'tools']);

        $app = AppListing::query()->create([
            'user_id' => $developer->id,
            'category_id' => $category->id,
            'platform' => 'web',
            'name' => 'Pending App',
            'slug' => 'pending-app',
            'description' => 'Needs a yes.',
            'is_published' => true,
            'approval_status' => AppListing::APPROVAL_PENDING,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.trusted', $developer))
            ->assertRedirect();

        $this->assertTrue($developer->fresh()->is_trusted);

        $this->actingAs($admin)
            ->patch(route('admin.apps.approve', $app))
            ->assertRedirect();

        $this->assertTrue($app->fresh()->isLive());
        $this->get(route('apps.public', $app->slug))->assertOk()->assertSee('Trusted');
    }

    public function test_non_admin_cannot_access_users_section(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}
