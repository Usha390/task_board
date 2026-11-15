<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = Department::firstOrCreate(
            ['name' => 'Marketing'],
            ['description' => 'Marketing department']
        );

        User::updateOrCreate(
            ['email' => 'director@taskboard.test'],
            [
                'name' => 'Director',
                'password' => 'password123',
                'role' => User::ROLE_DIRECTOR,
                'department_id' => null,
                'is_active' => true,
            ]
        );

        $head = User::updateOrCreate(
            ['email' => 'head@taskboard.test'],
            [
                'name' => 'Marketing Head',
                'password' => 'password123',
                'role' => User::ROLE_HEAD,
                'department_id' => $marketing->id,
                'is_active' => true,
            ]
        );

        $marketing->update(['head_id' => $head->id]);

        User::updateOrCreate(
            ['email' => 'employee@taskboard.test'],
            [
                'name' => 'Marketing Employee',
                'password' => 'password123',
                'role' => User::ROLE_EMPLOYEE,
                'department_id' => $marketing->id,
                'is_active' => true,
            ]
        );
    }
}