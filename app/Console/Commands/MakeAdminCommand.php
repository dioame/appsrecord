<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin
                            {email=dioamejade@gmail.com : The user email to promote}
                            {--revoke : Remove admin role instead}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an admin (and trusted) by email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $this->components->error("No user found with email [{$email}].");
            $this->line('Have them sign in or register first, then re-run this command.');

            return self::FAILURE;
        }

        if ($this->option('revoke')) {
            $user->forceFill([
                'role' => User::ROLE_USER,
            ])->save();

            $this->components->info("Admin revoked for {$user->name} <{$user->email}>.");

            return self::SUCCESS;
        }

        $user->forceFill([
            'role' => User::ROLE_ADMIN,
            'is_trusted' => true,
        ])->save();

        $this->components->info("Admin granted to {$user->name} <{$user->email}>.");
        $this->line('They can manage users and approve app deployments.');

        return self::SUCCESS;
    }
};
