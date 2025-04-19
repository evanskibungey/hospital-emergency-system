<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Reception/Intake Staff',
                'slug' => 'reception',
                'description' => 'Front desk staff responsible for patient registration and intake.',
            ],
            [
                'name' => 'Nurse',
                'slug' => 'nurse',
                'description' => 'Medical staff responsible for patient care and monitoring.',
            ],
            [
                'name' => 'Doctor',
                'slug' => 'doctor',
                'description' => 'Medical professionals who diagnose and treat patients.',
            ],
            [
                'name' => 'Department Head',
                'slug' => 'department-head',
                'description' => 'Manages a specific department within the hospital.',
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System administrators with full access to all features.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}