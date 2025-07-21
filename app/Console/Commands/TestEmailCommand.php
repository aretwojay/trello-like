<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration with Resend';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing email configuration...');
        $this->newLine();

        // Configuration check
        $this->info('📋 Configuration:');
        $this->table(['Setting', 'Value'], [
            ['MAIL_MAILER', config('mail.default')],
            ['RESEND_API_KEY', !empty(env('RESEND_API_KEY')) ? '✅ Set' : '❌ Not set'],
            ['MAIL_FROM_ADDRESS', config('mail.from.address')],
            ['MAIL_FROM_NAME', config('mail.from.name')],
            ['QUEUE_CONNECTION', config('queue.default')],
        ]);
        $this->newLine();

        // Get email to send test to
        $email = $this->argument('email');
        if (!$email) {
            $email = $this->ask('Enter email address to send test email to:');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return 1;
        }

        // Find or create a test user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->info("Creating test user for {$email}...");
            $user = new User([
                'name' => 'Test User',
                'email' => $email,
                'password' => bcrypt('password'),
            ]);
        }

        try {
            $this->info('📧 Sending test welcome email...');
            
            // Send email synchronously for testing
            Mail::to($email)->send(new WelcomeEmail($user));
            
            $this->info("✅ Email sent successfully to {$email}");
            $this->info('Check your inbox (and spam folder) for the welcome email.');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email:');
            $this->error($e->getMessage());
            $this->newLine();
            
            if ($this->option('verbose')) {
                $this->error('Stack trace:');
                $this->error($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}
