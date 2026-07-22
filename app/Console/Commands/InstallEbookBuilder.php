<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class InstallEbookBuilder extends Command
{
    protected $signature = 'app:install';
    protected $description = 'Install eBook Builder Pro';

    public function handle(): int
    {
        $this->info('🚀 eBook Builder Pro - Installation Wizard');
        $this->newLine();

        // Check PHP version
        $this->info('✓ Checking PHP version...');
        if (version_compare(PHP_VERSION, '8.3', '<')) {
            $this->error('PHP 8.3 or higher required');
            return 1;
        }
        $this->line('  PHP ' . PHP_VERSION . ' ✓');

        // Check extensions
        $this->info('✓ Checking extensions...');
        $required = ['curl', 'openssl', 'json', 'pdo'];
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $this->error("  $ext extension required");
                return 1;
            }
        }
        $this->line('  All required extensions loaded ✓');

        // Generate APP_KEY
        if (!env('APP_KEY')) {
            $this->info('✓ Generating application key...');
            $this->call('key:generate');
        }

        // Run migrations
        $this->info('✓ Running database migrations...');
        $this->call('migrate:fresh', ['--force' => true]);

        // Create Super Admin
        $this->info('✓ Creating Super Admin account...');
        $name = $this->ask('Super Admin name', 'Administrator');
        $email = $this->ask('Super Admin email', 'admin@ebook.local');
        $password = $this->secret('Super Admin password');

        if (User::where('email', $email)->exists()) {
            $this->warn('User already exists, skipping...');
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $this->line("  Super Admin created ✓");
        }

        // Publish storage
        $this->info('✓ Creating storage link...');
        $this->call('storage:link', ['--force' => true]);

        // Cache
        $this->info('✓ Caching...');
        $this->call('config:cache');
        $this->call('route:cache');

        // Summary
        $this->newLine();
        $this->info('✅ Installation completed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('  1. Configure .env file');
        $this->line('  2. Set OpenAI API key for AI features');
        $this->line('  3. Start the development server: php artisan serve');
        $this->line('  4. Visit: http://localhost:8000');
        $this->newLine();

        return 0;
    }
}
