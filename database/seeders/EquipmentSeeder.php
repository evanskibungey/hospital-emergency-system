<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentCheckout;
use App\Models\EquipmentMaintenance;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for assigning checkouts and maintenance
        $nurseUser = User::whereHas('roles', function($query) {
            $query->where('name', 'nurse');
        })->first();
        
        if (!$nurseUser) {
            // If no nurse user exists, use the first user
            $nurseUser = User::first();
        }
        
        // Get some visits for checkouts
        $activeVisits = Visit::whereIn('status', ['waiting', 'in_progress'])->take(5)->get();
        
        // Portable diagnostic equipment
        $this->createDiagnosticEquipment($nurseUser, $activeVisits);
        
        // Monitoring equipment
        $this->createMonitoringEquipment($nurseUser, $activeVisits);
        
        // Therapeutic equipment
        $this->createTherapeuticEquipment($nurseUser, $activeVisits);
        
        // Emergency equipment
        $this->createEmergencyEquipment($nurseUser, $activeVisits);
        
        // Patient care equipment
        $this->createPatientCareEquipment($nurseUser, $activeVisits);
    }
    
    /**
     * Create diagnostic equipment.
     */
    private function createDiagnosticEquipment($nurseUser, $activeVisits)
    {
        // Portable Ultrasound Machine
        $ultrasound = Equipment::updateOrCreate(
            ['serial_number' => 'USP-2023-45678'],
            [
                'name' => 'Portable Ultrasound',
                'model' => 'SonoSight M8',
                'manufacturer' => 'MediTech',
                'type' => 'portable',
                'category' => 'diagnostic',
                'quantity' => 2,
                'available_quantity' => 1,
                'purchase_date' => Carbon::now()->subYears(2),
                'last_maintenance_date' => Carbon::now()->subMonths(3),
                'next_maintenance_date' => Carbon::now()->addMonths(3),
                'status' => 'in_use',
                'location' => 'Emergency Department',
                'notes' => 'Includes 3 transducers',
                'is_active' => true,
            ]
        );
        
        // Add a checkout for the ultrasound
        if ($activeVisits->isNotEmpty() && $nurseUser) {
            EquipmentCheckout::updateOrCreate(
                [
                    'equipment_id' => $ultrasound->id,
                    'visit_id' => $activeVisits->first()->id,
                    'checked_out_by' => $nurseUser->id,
                ],
                [
                    'checked_out_at' => Carbon::now()->subHours(2),
                    'expected_return_at' => Carbon::now()->addHours(2),
                    'quantity' => 1,
                    'purpose' => 'Abdominal scan',
                    'status' => 'checked_out',
                    'checkout_notes' => 'Needed for patient assessment',
                    'condition_at_checkout' => 'Good condition',
                ]
            );
        }
        
        // ECG/EKG Machine
        Equipment::updateOrCreate(
            ['serial_number' => 'ECG-2022-12345'],
            [
                'name' => 'ECG/EKG Machine',
                'model' => 'CardioFlex X500',
                'manufacturer' => 'HeartTech Medical',
                'type' => 'portable',
                'category' => 'diagnostic',
                'quantity' => 3,
                'available_quantity' => 3,
                'purchase_date' => Carbon::now()->subYears(1)->subMonths(6),
                'last_maintenance_date' => Carbon::now()->subMonths(1),
                'next_maintenance_date' => Carbon::now()->addMonths(5),
                'status' => 'available',
                'location' => 'Cardiology',
                'notes' => '12-lead ECG with interpretation software',
                'is_active' => true,
            ]
        );
        
        // Digital X-Ray
        $xray = Equipment::updateOrCreate(
            ['serial_number' => 'XR-2020-98765'],
            [
                'name' => 'Digital X-Ray System',
                'model' => 'RadiusDR 500',
                'manufacturer' => 'RadiMed',
                'type' => 'fixed',
                'category' => 'diagnostic',
                'quantity' => 1,
                'available_quantity' => 0,
                'purchase_date' => Carbon::now()->subYears(3),
                'last_maintenance_date' => Carbon::now()->subMonths(2),
                'next_maintenance_date' => Carbon::now()->subDays(5),
                'status' => 'maintenance',
                'location' => 'Radiology',
                'notes' => 'Flat panel detector system',
                'is_active' => true,
            ]
        );
        
        // Maintenance record for X-Ray
        if ($nurseUser) {
            EquipmentMaintenance::updateOrCreate(
                [
                    'equipment_id' => $xray->id,
                    'requested_by' => $nurseUser->id,
                    'requested_at' => Carbon::now()->subDays(5),
                ],
                [
                    'scheduled_for' => Carbon::now()->addDays(2),
                    'type' => 'corrective',
                    'priority' => 'high',
                    'status' => 'scheduled',
                    'issue_description' => 'Error code E-045 appears when starting the system. Needs technician inspection.',
                    'service_provider' => 'RadiMed Technical Support',
                    'contact_info' => '800-555-1234',
                    'notes' => 'Technician scheduled for visit',
                ]
            );
        }
        
        // Otoscope/Ophthalmoscope Set
        Equipment::updateOrCreate(
            ['serial_number' => 'DOS-2023-56789'],
            [
                'name' => 'Diagnostic Set (Otoscope/Ophthalmoscope)',
                'model' => 'WelchAllyn 777',
                'manufacturer' => 'WelchAllyn',
                'type' => 'portable',
                'category' => 'diagnostic',
                'quantity' => 10,
                'available_quantity' => 8,
                'purchase_date' => Carbon::now()->subMonths(8),
                'last_maintenance_date' => null,
                'next_maintenance_date' => Carbon::now()->addYear(),
                'status' => 'in_use',
                'location' => 'Various Departments',
                'notes' => 'Wall-mounted and portable units',
                'is_active' => true,
            ]
        );
    }
    
    /**
     * Create monitoring equipment.
     */
    private function createMonitoringEquipment($nurseUser, $activeVisits)
    {
        // Vital Signs Monitor
        $vitalMonitor = Equipment::create([
            'name' => 'Vital Signs Monitor',
            'serial_number' => 'VSM-2021-34567',
            'model' => 'OmniCare VS900',
            'manufacturer' => 'OmniMed',
            'type' => 'portable',
            'category' => 'monitoring',
            'quantity' => 15,
            'available_quantity' => 10,
            'purchase_date' => Carbon::now()->subYears(2),
            'last_maintenance_date' => Carbon::now()->subMonths(2),
            'next_maintenance_date' => Carbon::now()->addMonths(4),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Measures BP, HR, SpO2, Temp',
            'is_active' => true,
        ]);
        
        // Add checkout for vital signs monitor
        if ($activeVisits->count() > 1 && $nurseUser) {
            EquipmentCheckout::create([
                'equipment_id' => $vitalMonitor->id,
                'visit_id' => $activeVisits[1]->id,
                'checked_out_by' => $nurseUser->id,
                'checked_out_at' => Carbon::now()->subHours(3),
                'expected_return_at' => Carbon::now()->addHours(1),
                'quantity' => 1,
                'purpose' => 'Continuous monitoring',
                'status' => 'checked_out',
                'checkout_notes' => 'Post-op patient monitoring',
                'condition_at_checkout' => 'Excellent condition',
            ]);
        }
        
        // Central Monitoring Station
        Equipment::create([
            'name' => 'Central Monitoring Station',
            'serial_number' => 'CMS-2019-12345',
            'model' => 'VitalView 3000',
            'manufacturer' => 'PatientCare Systems',
            'type' => 'fixed',
            'category' => 'monitoring',
            'quantity' => 2,
            'available_quantity' => 2,
            'purchase_date' => Carbon::now()->subYears(4),
            'last_maintenance_date' => Carbon::now()->subMonths(6),
            'next_maintenance_date' => Carbon::now()->subDays(10),
            'status' => 'available',
            'location' => 'ICU, CCU',
            'notes' => 'Connects to up to 16 bedside monitors',
            'is_active' => true,
        ]);
        
        // Telemetry System
        Equipment::create([
            'name' => 'Telemetry Transmitter Pack',
            'serial_number' => 'TEL-2022-78901',
            'model' => 'CardioTrak T50',
            'manufacturer' => 'HeartTech Medical',
            'type' => 'portable',
            'category' => 'monitoring',
            'quantity' => 20,
            'available_quantity' => 12,
            'purchase_date' => Carbon::now()->subYears(1),
            'last_maintenance_date' => Carbon::now()->subMonths(4),
            'next_maintenance_date' => Carbon::now()->addMonths(2),
            'status' => 'in_use',
            'location' => 'Cardiology, Med-Surg',
            'notes' => 'Wireless ECG monitoring',
            'is_active' => true,
        ]);
        
        // Pulse Oximeter
        Equipment::create([
            'name' => 'Pulse Oximeter',
            'serial_number' => 'PO-2023-12345',
            'model' => 'OxyMax P510',
            'manufacturer' => 'MediTech',
            'type' => 'portable',
            'category' => 'monitoring',
            'quantity' => 30,
            'available_quantity' => 25,
            'purchase_date' => Carbon::now()->subMonths(6),
            'last_maintenance_date' => null,
            'next_maintenance_date' => Carbon::now()->addYear(),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Fingertip units',
            'is_active' => true,
        ]);
    }
    
    /**
     * Create therapeutic equipment.
     */
    private function createTherapeuticEquipment($nurseUser, $activeVisits)
    {
        // Infusion Pump
        $infusionPump = Equipment::create([
            'name' => 'Infusion Pump',
            'serial_number' => 'IP-2021-56789',
            'model' => 'FlowMaster 3000',
            'manufacturer' => 'MediFlow',
            'type' => 'portable',
            'category' => 'therapeutic',
            'quantity' => 25,
            'available_quantity' => 15,
            'purchase_date' => Carbon::now()->subYears(2),
            'last_maintenance_date' => Carbon::now()->subMonths(3),
            'next_maintenance_date' => Carbon::now()->addMonths(3),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Programmable infusion pumps',
            'is_active' => true,
        ]);
        
        // Ventilator
        Equipment::create([
            'name' => 'Ventilator',
            'serial_number' => 'VENT-2020-12345',
            'model' => 'RespiCare V800',
            'manufacturer' => 'RespiTech',
            'type' => 'portable',
            'category' => 'therapeutic',
            'quantity' => 10,
            'available_quantity' => 5,
            'purchase_date' => Carbon::now()->subYears(3),
            'last_maintenance_date' => Carbon::now()->subMonth(),
            'next_maintenance_date' => Carbon::now()->addMonths(5),
            'status' => 'in_use',
            'location' => 'ICU, Emergency',
            'notes' => 'Advanced life support ventilators',
            'is_active' => true,
        ]);
        
        // CPAP Machine
        Equipment::create([
            'name' => 'CPAP Machine',
            'serial_number' => 'CPAP-2022-78901',
            'model' => 'SleepEase 500',
            'manufacturer' => 'RespiTech',
            'type' => 'portable',
            'category' => 'therapeutic',
            'quantity' => 8,
            'available_quantity' => 6,
            'purchase_date' => Carbon::now()->subYears(1),
            'last_maintenance_date' => Carbon::now()->subMonths(2),
            'next_maintenance_date' => Carbon::now()->addMonths(4),
            'status' => 'in_use',
            'location' => 'Respiratory Therapy',
            'notes' => 'For sleep apnea therapy',
            'is_active' => true,
        ]);
        
        // Defibrillator
        $defibrillator = Equipment::create([
            'name' => 'Defibrillator',
            'serial_number' => 'DEF-2021-34567',
            'model' => 'CardioSave D500',
            'manufacturer' => 'HeartTech Medical',
            'type' => 'portable',
            'category' => 'emergency',
            'quantity' => 12,
            'available_quantity' => 12,
            'purchase_date' => Carbon::now()->subYears(2),
            'last_maintenance_date' => Carbon::now()->subWeeks(2),
            'next_maintenance_date' => Carbon::now()->addMonths(1)->subWeeks(2),
            'status' => 'available',
            'location' => 'Various Departments',
            'notes' => 'AED with manual mode capability',
            'is_active' => true,
        ]);
        
        // Create maintenance record for one defibrillator
        if ($nurseUser) {
            EquipmentMaintenance::create([
                'equipment_id' => $defibrillator->id,
                'requested_by' => $nurseUser->id,
                'requested_at' => Carbon::now()->subDays(15),
                'scheduled_for' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(7),
                'completed_by' => $nurseUser->id,
                'type' => 'preventive',
                'priority' => 'high',
                'status' => 'completed',
                'issue_description' => 'Regular monthly check of defibrillators',
                'work_performed' => 'Full system check, battery test, replaced pads, all systems functional',
                'service_provider' => 'BioMed Department',
                'notes' => 'Next check scheduled in 30 days',
            ]);
        }
    }
    
    /**
     * Create emergency equipment.
     */
    private function createEmergencyEquipment($nurseUser, $activeVisits)
    {
        // Crash Cart
        Equipment::create([
            'name' => 'Crash Cart',
            'serial_number' => 'CART-2023-12345',
            'model' => 'EmergeMed 200',
            'manufacturer' => 'MediCarts',
            'type' => 'portable',
            'category' => 'emergency',
            'quantity' => 5,
            'available_quantity' => 5,
            'purchase_date' => Carbon::now()->subMonths(8),
            'last_maintenance_date' => Carbon::now()->subWeeks(1),
            'next_maintenance_date' => Carbon::now()->addWeeks(3),
            'status' => 'available',
            'location' => 'Various Departments',
            'notes' => 'Fully stocked emergency carts',
            'is_active' => true,
        ]);
        
        // Portable Suction Machine
        Equipment::create([
            'name' => 'Portable Suction Machine',
            'serial_number' => 'SUC-2022-56789',
            'model' => 'VacuMed P100',
            'manufacturer' => 'MediTech',
            'type' => 'portable',
            'category' => 'emergency',
            'quantity' => 15,
            'available_quantity' => 12,
            'purchase_date' => Carbon::now()->subYears(1),
            'last_maintenance_date' => Carbon::now()->subMonths(3),
            'next_maintenance_date' => Carbon::now()->addMonths(3),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Battery and wall-powered options',
            'is_active' => true,
        ]);
        
        // Transport Stretcher
        Equipment::create([
            'name' => 'Transport Stretcher',
            'serial_number' => 'STR-2021-78901',
            'model' => 'PatientGlide 500',
            'manufacturer' => 'MediMove',
            'type' => 'portable',
            'category' => 'patient_care',
            'quantity' => 20,
            'available_quantity' => 15,
            'purchase_date' => Carbon::now()->subYears(2),
            'last_maintenance_date' => Carbon::now()->subMonths(4),
            'next_maintenance_date' => Carbon::now()->addMonths(2),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Adjustable height, side rails',
            'is_active' => true,
        ]);
    }
    
    /**
     * Create patient care equipment.
     */
    private function createPatientCareEquipment($nurseUser, $activeVisits)
    {
        // Hospital Bed
        Equipment::create([
            'name' => 'Electric Hospital Bed',
            'serial_number' => 'BED-2020-12345',
            'model' => 'ComfortCare 3000',
            'manufacturer' => 'MediRest',
            'type' => 'fixed',
            'category' => 'patient_care',
            'quantity' => 50,
            'available_quantity' => 50,
            'purchase_date' => Carbon::now()->subYears(3),
            'last_maintenance_date' => Carbon::now()->subMonths(6),
            'next_maintenance_date' => Carbon::now()->addMonths(6),
            'status' => 'available',
            'location' => 'Various Departments',
            'notes' => 'These are managed separately through the bed management system',
            'is_active' => true,
        ]);
        
        // Wheelchair
        $wheelchair = Equipment::create([
            'name' => 'Wheelchair',
            'serial_number' => 'WC-2022-56789',
            'model' => 'MobilityPlus',
            'manufacturer' => 'MediMove',
            'type' => 'portable',
            'category' => 'patient_care',
            'quantity' => 30,
            'available_quantity' => 22,
            'purchase_date' => Carbon::now()->subYears(1),
            'last_maintenance_date' => Carbon::now()->subMonths(2),
            'next_maintenance_date' => Carbon::now()->addMonths(4),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Standard and bariatric options',
            'is_active' => true,
        ]);
        
        // Add a checkout for a wheelchair
        if ($activeVisits->count() > 2 && $nurseUser) {
            EquipmentCheckout::create([
                'equipment_id' => $wheelchair->id,
                'visit_id' => $activeVisits[2]->id,
                'checked_out_by' => $nurseUser->id,
                'checked_out_at' => Carbon::now()->subHours(5),
                'expected_return_at' => Carbon::now()->addHours(3),
                'quantity' => 1,
                'purpose' => 'Patient mobility',
                'status' => 'checked_out',
                'checkout_notes' => 'For patient discharge',
                'condition_at_checkout' => 'Good condition',
            ]);
        }
        
        // IV Stand
        Equipment::create([
            'name' => 'IV Stand',
            'serial_number' => 'IV-2022-78901',
            'model' => 'FluidStand 200',
            'manufacturer' => 'MediFlow',
            'type' => 'portable',
            'category' => 'patient_care',
            'quantity' => 40,
            'available_quantity' => 35,
            'purchase_date' => Carbon::now()->subYears(1),
            'last_maintenance_date' => Carbon::now()->subMonths(3),
            'next_maintenance_date' => Carbon::now()->addMonths(9),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Adjustable height, rolling base',
            'is_active' => true,
        ]);
        
        // Patient Lift
        Equipment::create([
            'name' => 'Patient Lift',
            'serial_number' => 'LIFT-2021-34567',
            'model' => 'SafeLift 500',
            'manufacturer' => 'MediLift',
            'type' => 'portable',
            'category' => 'patient_care',
            'quantity' => 10,
            'available_quantity' => 8,
            'purchase_date' => Carbon::now()->subYears(2),
            'last_maintenance_date' => Carbon::now()->subMonths(4),
            'next_maintenance_date' => Carbon::now()->addMonths(2),
            'status' => 'in_use',
            'location' => 'Various Departments',
            'notes' => 'Battery-powered hydraulic lifts',
            'is_active' => true,
        ]);
    }
}