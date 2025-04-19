<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'System administrators with full access to all features.'
            ]
        );
        
        $receptionRole = Role::firstOrCreate(
            ['slug' => 'reception'],
            [
                'name' => 'Reception/Intake Staff',
                'description' => 'Front desk staff responsible for patient registration and intake.'
            ]
        );

        $nurseRole = Role::firstOrCreate(
            ['slug' => 'nurse'],
            [
                'name' => 'Nurse',
                'description' => 'Medical staff responsible for patient care and monitoring.'
            ]
        );

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => 'password', // Will be hashed by the model mutator
            'email_verified_at' => now(),
        ]);

        // Create reception user
        $receptionUser = User::create([
            'name' => 'Reception Staff',
            'email' => 'reception@hospital.com',
            'password' => 'password', // Will be hashed by the model mutator
            'email_verified_at' => now(),
        ]);

        // Create nurse user
        $nurseUser = User::create([
            'name' => 'Nurse Staff',
            'email' => 'nurse@hospital.com',
            'password' => 'password', // Will be hashed by the model mutator
            'email_verified_at' => now(),
        ]);

        // Assign roles
        $adminUser->roles()->attach($adminRole);
        $receptionUser->roles()->attach($receptionRole);
        $nurseUser->roles()->attach($nurseRole);
    }
}