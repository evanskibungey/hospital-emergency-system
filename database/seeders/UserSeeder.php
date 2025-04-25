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

        $doctorRole = Role::firstOrCreate(
            ['slug' => 'doctor'],
            [
                'name' => 'Doctor',
                'description' => 'Medical doctors responsible for patient diagnosis, treatment, and care plans.'
            ]
        );

        // Create admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Admin User',
                'password' => 'password', // Will be hashed by the model mutator
                'email_verified_at' => now(),
            ]
        );

        // Create reception user
        $receptionUser = User::updateOrCreate(
            ['email' => 'reception@hospital.com'],
            [
                'name' => 'Reception Staff',
                'password' => 'password', // Will be hashed by the model mutator
                'email_verified_at' => now(),
            ]
        );

        // Create nurse user
        $nurseUser = User::updateOrCreate(
            ['email' => 'nurse@hospital.com'],
            [
                'name' => 'Nurse Staff',
                'password' => 'password', // Will be hashed by the model mutator
                'email_verified_at' => now(),
            ]
        );

        // Create doctor user
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@hospital.com'],
            [
                'name' => 'Dr. John Smith',
                'password' => 'password', // Will be hashed by the model mutator
                'email_verified_at' => now(),
                'is_on_call' => true,
            ]
        );

        // Assign roles (sync prevents duplicates)
        $adminUser->roles()->sync([$adminRole->id]);
        $receptionUser->roles()->sync([$receptionRole->id]);
        $nurseUser->roles()->sync([$nurseRole->id]);
        $doctorUser->roles()->sync([$doctorRole->id]);
    }
}