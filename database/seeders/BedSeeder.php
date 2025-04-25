<?php

namespace Database\Seeders;

use App\Models\Bed;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Emergency Department Beds
        for ($i = 1; $i <= 10; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => 'ED-' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'location' => 'Emergency Department',
                    'status' => 'available',
                    'type' => $i <= 8 ? 'regular' : 'isolation',
                    'is_active' => true,
                ]
            );
        }

        // ICU Beds
        for ($i = 1; $i <= 5; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => 'ICU-' . $i],
                [
                    'location' => 'Intensive Care Unit',
                    'status' => 'available',
                    'type' => 'icu',
                    'is_active' => true,
                ]
            );
        }

        // Ward A Beds
        for ($i = 101; $i <= 110; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => (string)$i],
                [
                    'location' => 'Ward A',
                    'status' => 'available',
                    'type' => 'regular',
                    'is_active' => true,
                ]
            );
        }

        // Ward B Beds
        for ($i = 201; $i <= 210; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => (string)$i],
                [
                    'location' => 'Ward B',
                    'status' => 'available',
                    'type' => 'regular',
                    'is_active' => true,
                ]
            );
        }

        // Pediatric Ward
        for ($i = 301; $i <= 305; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => (string)$i],
                [
                    'location' => 'Pediatric Ward',
                    'status' => 'available',
                    'type' => 'pediatric',
                    'is_active' => true,
                ]
            );
        }

        // Maternity Ward
        for ($i = 401; $i <= 405; $i++) {
            Bed::updateOrCreate(
                ['bed_number' => (string)$i],
                [
                    'location' => 'Maternity Ward',
                    'status' => 'available',
                    'type' => 'maternity',
                    'is_active' => true,
                ]
            );
        }

        // Add some beds with different statuses
        Bed::updateOrCreate(
            ['bed_number' => 'ED-11'],
            [
                'location' => 'Emergency Department',
                'status' => 'cleaning',
                'type' => 'regular',
                'is_active' => true,
                'notes' => 'Marked for cleaning on ' . now()->format('Y-m-d H:i'),
            ]
        );

        Bed::updateOrCreate(
            ['bed_number' => 'ED-12'],
            [
                'location' => 'Emergency Department',
                'status' => 'maintenance',
                'type' => 'regular',
                'is_active' => true,
                'notes' => 'Maintenance required: Bed rail needs repair. Reported on ' . now()->format('Y-m-d H:i'),
            ]
        );

        Bed::updateOrCreate(
            ['bed_number' => 'ICU-6'],
            [
                'location' => 'Intensive Care Unit',
                'status' => 'reserved',
                'type' => 'icu',
                'is_active' => true,
                'notes' => 'Reserved for incoming transfer patient. Reserved on ' . now()->format('Y-m-d H:i'),
            ]
        );

        Bed::updateOrCreate(
            ['bed_number' => '111'],
            [
                'location' => 'Ward A',
                'status' => 'available',
                'type' => 'regular',
                'is_active' => false,
                'notes' => 'Bed decommissioned due to age. Inactive as of ' . now()->format('Y-m-d H:i'),
            ]
        );
    }
}
