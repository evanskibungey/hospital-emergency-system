<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EquipmentMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for assigning maintenance
        $nurseUser = User::whereHas('roles', function($query) {
            $query->where('name', 'nurse');
        })->first();
        
        if (!$nurseUser) {
            // If no nurse user exists, use the first user
            $nurseUser = User::first();
        }
        
        // Get equipment items that don't have maintenance records yet
        $allEquipment = Equipment::all();
        
        // Create various maintenance records
        $this->createOverdueMaintenance($nurseUser, $allEquipment);
        $this->createScheduledTodayMaintenance($nurseUser, $allEquipment);
        $this->createUpcomingMaintenance($nurseUser, $allEquipment);
        $this->createCompletedMaintenance($nurseUser, $allEquipment);
        $this->createInProgressMaintenance($nurseUser, $allEquipment);
    }
    
    /**
     * Create overdue maintenance records.
     */
    private function createOverdueMaintenance($nurseUser, $allEquipment)
    {
        // Get 2 random equipment items for overdue maintenance
        $equipment = $allEquipment->random(min(2, $allEquipment->count()));
        
        foreach ($equipment as $item) {
            EquipmentMaintenance::create([
                'equipment_id' => $item->id,
                'requested_by' => $nurseUser->id,
                'requested_at' => Carbon::now()->subDays(rand(15, 30)),
                'scheduled_for' => Carbon::now()->subDays(rand(1, 10)),
                'type' => $this->getRandomMaintenanceType(),
                'priority' => $this->getRandomPriority(),
                'status' => 'in_progress',
                'issue_description' => $this->getRandomIssueDescription($item),
                'service_provider' => $this->getRandomServiceProvider(),
                'contact_info' => '800-555-' . rand(1000, 9999),
                'notes' => 'This maintenance is overdue and needs immediate attention',
            ]);
            
            // Update equipment status for better visualization
            $item->update(['status' => 'maintenance']);
        }
    }
    
    /**
     * Create maintenance records scheduled for today.
     */
    private function createScheduledTodayMaintenance($nurseUser, $allEquipment)
    {
        // Get 3 random equipment items for today's maintenance
        $equipment = $allEquipment->random(min(3, $allEquipment->count()));
        
        foreach ($equipment as $item) {
            EquipmentMaintenance::create([
                'equipment_id' => $item->id,
                'requested_by' => $nurseUser->id,
                'requested_at' => Carbon::now()->subDays(rand(3, 10)),
                'scheduled_for' => Carbon::now()->addHours(rand(1, 8)),
                'type' => $this->getRandomMaintenanceType(),
                'priority' => $this->getRandomPriority(),
                'status' => 'scheduled',
                'issue_description' => $this->getRandomIssueDescription($item),
                'service_provider' => $this->getRandomServiceProvider(),
                'contact_info' => '800-555-' . rand(1000, 9999),
                'notes' => 'Technician will arrive today',
            ]);
        }
    }
    
    /**
     * Create upcoming maintenance records.
     */
    private function createUpcomingMaintenance($nurseUser, $allEquipment)
    {
        // Get 4 random equipment items for upcoming maintenance
        $equipment = $allEquipment->random(min(4, $allEquipment->count()));
        
        foreach ($equipment as $item) {
            EquipmentMaintenance::create([
                'equipment_id' => $item->id,
                'requested_by' => $nurseUser->id,
                'requested_at' => Carbon::now()->subDays(rand(1, 5)),
                'scheduled_for' => Carbon::now()->addDays(rand(1, 14)),
                'type' => $this->getRandomMaintenanceType(true), // Mostly preventive
                'priority' => 'low',
                'status' => 'scheduled',
                'issue_description' => 'Regular preventive maintenance as per schedule',
                'service_provider' => $this->getRandomServiceProvider(),
                'contact_info' => '800-555-' . rand(1000, 9999),
                'notes' => 'Scheduled maintenance, equipment remains in use until service date',
            ]);
        }
    }
    
    /**
     * Create completed maintenance records.
     */
    private function createCompletedMaintenance($nurseUser, $allEquipment)
    {
        // Get 5 random equipment items for completed maintenance
        $equipment = $allEquipment->random(min(5, $allEquipment->count()));
        
        foreach ($equipment as $item) {
            $requestedAt = Carbon::now()->subDays(rand(20, 40));
            $scheduledFor = $requestedAt->copy()->addDays(rand(5, 10));
            $completedAt = $scheduledFor->copy()->addHours(rand(1, 24));
            
            EquipmentMaintenance::create([
                'equipment_id' => $item->id,
                'requested_by' => $nurseUser->id,
                'completed_by' => $nurseUser->id,
                'requested_at' => $requestedAt,
                'scheduled_for' => $scheduledFor,
                'completed_at' => $completedAt,
                'type' => $this->getRandomMaintenanceType(),
                'priority' => $this->getRandomPriority(),
                'status' => 'completed',
                'issue_description' => $this->getRandomIssueDescription($item),
                'work_performed' => $this->getRandomWorkPerformed(),
                'cost' => rand(50, 500) . '.' . rand(0, 99),
                'service_provider' => $this->getRandomServiceProvider(),
                'contact_info' => '800-555-' . rand(1000, 9999),
                'notes' => 'Maintenance completed successfully',
            ]);
            
            // Update equipment with last maintenance date
            $item->update([
                'last_maintenance_date' => $completedAt->toDateString(),
                'next_maintenance_date' => $completedAt->addMonths(rand(3, 12))->toDateString(),
            ]);
        }
    }
    
    /**
     * Create in-progress maintenance records.
     */
    private function createInProgressMaintenance($nurseUser, $allEquipment)
    {
        // Get 2 random equipment items for in-progress maintenance
        $equipment = $allEquipment->random(min(2, $allEquipment->count()));
        
        foreach ($equipment as $item) {
            EquipmentMaintenance::create([
                'equipment_id' => $item->id,
                'requested_by' => $nurseUser->id,
                'requested_at' => Carbon::now()->subDays(rand(2, 7)),
                'scheduled_for' => Carbon::now()->subHours(rand(1, 12)),
                'type' => $this->getRandomMaintenanceType(),
                'priority' => $this->getRandomPriority(true), // Mostly high priority
                'status' => 'in_progress',
                'issue_description' => $this->getRandomIssueDescription($item),
                'service_provider' => $this->getRandomServiceProvider(),
                'contact_info' => '800-555-' . rand(1000, 9999),
                'notes' => 'Technician currently working on this equipment',
            ]);
            
            // Update equipment status for better visualization
            $item->update(['status' => 'maintenance']);
        }
    }
    
    /**
     * Get a random maintenance type.
     */
    private function getRandomMaintenanceType($preventiveBias = false)
    {
        $types = ['preventive', 'corrective', 'inspection', 'calibration', 'other'];
        
        if ($preventiveBias) {
            // 70% chance of preventive when bias is true
            return (rand(1, 10) <= 7) ? 'preventive' : $types[array_rand(array_slice($types, 1, 4))];
        }
        
        return $types[array_rand($types)];
    }
    
    /**
     * Get a random priority level.
     */
    private function getRandomPriority($highBias = false)
    {
        $priorities = ['low', 'medium', 'high', 'critical'];
        
        if ($highBias) {
            // 70% chance of high or critical when bias is true
            $highIndex = rand(1, 10) <= 7 ? rand(2, 3) : rand(0, 1);
            return $priorities[$highIndex];
        }
        
        return $priorities[array_rand($priorities)];
    }
    
    /**
     * Get a random issue description.
     */
    private function getRandomIssueDescription($equipment)
    {
        $issues = [
            'Device showing error code E-' . rand(100, 999),
            'Unusual noise during operation',
            'Display screen flickering intermittently',
            'Battery not holding charge properly',
            'Calibration issues affecting readings',
            'Power-up failure requiring technical support',
            'Software version needs updating to latest release',
            'Wheel/caster broken or not rotating smoothly',
            'Alarm not sounding correctly',
            'Physical damage to casing requiring repair'
        ];
        
        return $issues[array_rand($issues)] . ' for ' . $equipment->name;
    }
    
    /**
     * Get a random work performed description.
     */
    private function getRandomWorkPerformed()
    {
        $workPerformed = [
            'Replaced faulty component and tested functionality',
            'Performed full calibration according to manufacturer specifications',
            'Updated firmware to latest version',
            'Replaced battery and tested charge cycle',
            'Cleaned internal components and performed system reset',
            'Repaired damaged casing and tested device operation',
            'Resolved software issue and performed system diagnostics',
            'Replaced worn parts and performed preventive maintenance',
            'Tightened loose connections and verified proper operation',
            'Diagnosed issue and ordered replacement parts'
        ];
        
        return $workPerformed[array_rand($workPerformed)];
    }
    
    /**
     * Get a random service provider name.
     */
    private function getRandomServiceProvider()
    {
        $providers = [
            'Hospital BioMed Department',
            'MedTech Services Inc.',
            'Precision Medical Equipment',
            'Healthcare Equipment Solutions',
            'BioMedical Engineering Services',
            'Manufacturer Authorized Service',
            'Elite Medical Repair',
            'MedEquip Calibration Services',
            'Hospital Maintenance Staff',
            'ProMed Technical Support'
        ];
        
        return $providers[array_rand($providers)];
    }
}