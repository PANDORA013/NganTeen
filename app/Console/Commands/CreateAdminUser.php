<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email?} {--name=Administrator}';

    protected $description = 'Create or update an administrator without exposing the password';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Email admin');
        $password = $this->secret('Password admin (minimal 12 karakter)');
        $confirmation = $this->secret('Ulangi password');

        $validator = Validator::make(compact('email', 'password'), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }

        if (! hash_equals($password, $confirmation)) {
            $this->error('Konfirmasi password tidak cocok.');
            return self::FAILURE;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => $this->option('name'),
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info("Admin {$email} berhasil dibuat atau diperbarui.");
        return self::SUCCESS;
    }
}
