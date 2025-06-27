<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckUsersTable extends Command
{
    /**
     * Nama dan signature dari command.
     *
     * @var string
     */
    protected $signature = 'check:users';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Check users table structure and contents';

    /**
     * Eksekusi command.
     *
     * @return int
     */
    public function handle()
    {
        // Cek apakah tabel users ada
        if (!Schema::hasTable('users')) {
            $this->error('Tabel users tidak ditemukan!');
            return 1;
        }

        $this->info('Tabel users ditemukan. Struktur kolom:');
        
        // Tampilkan struktur tabel
        $columns = Schema::getColumnListing('users');
        $this->table(
            ['Nama Kolom'],
            array_map(fn($col) => [$col], $columns)
        );

        // Hitung jumlah user
        $count = DB::table('users')->count();
        $this->info("Jumlah user dalam database: $count");

        // Tampilkan beberapa user terakhir (jika ada)
        if ($count > 0) {
            $users = DB::table('users')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $this->info('\nBeberapa user terakhir:');
            $this->table(
                ['ID', 'Nama', 'Email', 'Role', 'Dibuat'],
                $users->map(function($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->created_at,
                    ];
                })->toArray()
            );
        }

        return 0;
    }
}
