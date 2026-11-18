<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('DIRECTOR_EMAIL');
        $password = env('DIRECTOR_PASSWORD');

        if (! $email || ! $password) {
            $this->command->error('DIRECTOR_EMAIL ar DIRECTOR_PASSWORD age .env file e set koro.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('DIRECTOR_NAME', 'Director'),
                'password' => $password,
                'role' => User::ROLE_DIRECTOR,
                'department_id' => null,
                'is_active' => true,
            ]
        );
    }
}