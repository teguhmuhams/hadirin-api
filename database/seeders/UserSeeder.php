<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create administrator
        User::factory()->create([
            'identifier' =>  sprintf('%06d', 1),
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'role'  => User::ROLE_ADMIN,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        User::factory(5)
            ->teacher()
            ->create()
            ->each(function ($user) {
                Teacher::factory()->create(
                    [
                        'user_id' => $user->id,
                        'nip' => $user->identifier
                    ]
                );
            });

        // User::factory(5)->student()->create();
    }
}