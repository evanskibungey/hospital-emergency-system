<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DoctorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the doctor role
        $doctorRole = Role::where('slug', 'doctor')->first();
        
        if (!$doctorRole) {
            $this->command->error('Doctor role not found!');
            return;
        }

        // Create doctor user
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@hospital.com'],
            [
                'name' => 'Doctor User',
                'password' => 'password', // Will be hashed by the model mutator
                'email_verified_at' => now(),
                'specialty' => 'General Medicine',
                'department' => 'Emergency',
                'is_on_call' => true,
            ]
        );

        // Assign doctor role
        $doctorUser->roles()->sync([$doctorRole->id]);

        $this->command->info('Doctor user created successfully.');
    }
}