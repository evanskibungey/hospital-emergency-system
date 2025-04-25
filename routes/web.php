<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\Nurse\VitalSignController;
use App\Http\Controllers\Nurse\MedicationController;
use App\Http\Controllers\Nurse\MedicationScheduleController;
use App\Http\Controllers\Nurse\MedicationAdministrationController;
use Illuminate\Support\Facades\Route;

// Include doctor routes
require __DIR__.'/doctor.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Reception routes
Route::middleware(['auth', 'verified', 'role:reception,admin'])->group(function () {
    // Dashboard
    Route::get('/reception', [ReceptionController::class, 'dashboard'])->name('reception.dashboard');
    
    // Patient Management
    Route::prefix('reception/patients')->group(function () {
        Route::get('/create', [ReceptionController::class, 'createPatient'])->name('reception.patients.create');
        Route::post('/', [ReceptionController::class, 'storePatient'])->name('reception.patients.store');
        Route::get('/{patient}', [ReceptionController::class, 'showPatient'])->name('reception.patients.show');
        Route::get('/search/query', [ReceptionController::class, 'searchPatients'])->name('reception.patients.search');
        
        // Visit Management
        Route::get('/{patient}/visits/create', [ReceptionController::class, 'createVisit'])->name('reception.visits.create');
        Route::post('/{patient}/visits', [ReceptionController::class, 'storeVisit'])->name('reception.visits.store');
    });
    
    // Visitor Management
    Route::prefix('reception/visitors')->group(function () {
        Route::get('/', [VisitorController::class, 'index'])->name('reception.visitors.index');
        Route::get('/patients/{patient}/create', [VisitorController::class, 'create'])->name('reception.visitors.create');
        Route::post('/patients/{patient}', [VisitorController::class, 'store'])->name('reception.visitors.store');
        Route::get('/{visitor}', [VisitorController::class, 'show'])->name('reception.visitors.show');
        Route::patch('/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('reception.visitors.checkout');
        Route::get('/search/query', [VisitorController::class, 'search'])->name('reception.visitors.search');
    });
});

// Nurse routes
Route::middleware(['auth', 'verified', 'role:nurse,admin'])->prefix('nurse')->name('nurse.')->group(function () {
    // Dashboard
    Route::get('/', [NurseDashboardController::class, 'index'])->name('dashboard');
    
    // Visit Assignment
    Route::post('/visits/{visit}/assign', [NurseDashboardController::class, 'assignVisit'])->name('assign-visit');
    Route::get('/visits/{visit}/assign-doctor', [NurseDashboardController::class, 'showAssignDoctorForm'])->name('assign-doctor');
    Route::post('/visits/{visit}/assign-doctor', [NurseDashboardController::class, 'assignDoctor'])->name('store-doctor-assignment');
    
    // Vital Signs Management
    Route::get('/vital-signs/search', [VitalSignController::class, 'searchPatient'])->name('vital-signs.search-patient');
    Route::get('/vital-signs/patients/{patient}', [VitalSignController::class, 'patientVisits'])->name('vital-signs.patient-visits');
    
    Route::get('/visits/{visit}/vital-signs', [VitalSignController::class, 'index'])->name('vital-signs.index');
    Route::get('/visits/{visit}/vital-signs/create', [VitalSignController::class, 'create'])->name('vital-signs.create');
    Route::post('/visits/{visit}/vital-signs', [VitalSignController::class, 'store'])->name('vital-signs.store');
    Route::get('/visits/{visit}/vital-signs/{vitalSign}', [VitalSignController::class, 'show'])->name('vital-signs.show');
    Route::get('/visits/{visit}/vital-signs/{vitalSign}/edit', [VitalSignController::class, 'edit'])->name('vital-signs.edit');
    Route::put('/visits/{visit}/vital-signs/{vitalSign}', [VitalSignController::class, 'update'])->name('vital-signs.update');
    
    // Medication Management
    Route::get('/medications', [MedicationController::class, 'index'])->name('medications.index');
    Route::get('/medications/create', [MedicationController::class, 'create'])->name('medications.create');
    Route::post('/medications', [MedicationController::class, 'store'])->name('medications.store');
    Route::get('/medications/{medication}', [MedicationController::class, 'show'])->name('medications.show');
    Route::get('/medications/{medication}/edit', [MedicationController::class, 'edit'])->name('medications.edit');
    Route::put('/medications/{medication}', [MedicationController::class, 'update'])->name('medications.update');
    Route::get('/medications/search/query', [MedicationController::class, 'search'])->name('medications.search');
    
    // Medication Schedule Management
    Route::get('/visits/{visit}/medications', [MedicationScheduleController::class, 'index'])->name('medication-schedules.index');
    Route::get('/visits/{visit}/medications/create', [MedicationScheduleController::class, 'create'])->name('medication-schedules.create');
    Route::post('/visits/{visit}/medications', [MedicationScheduleController::class, 'store'])->name('medication-schedules.store');
    Route::get('/visits/{visit}/medications/{medicationSchedule}', [MedicationScheduleController::class, 'show'])->name('medication-schedules.show');
    Route::get('/visits/{visit}/medications/{medicationSchedule}/edit', [MedicationScheduleController::class, 'edit'])->name('medication-schedules.edit');
    Route::put('/visits/{visit}/medications/{medicationSchedule}', [MedicationScheduleController::class, 'update'])->name('medication-schedules.update');
    Route::patch('/visits/{visit}/medications/{medicationSchedule}/cancel', [MedicationScheduleController::class, 'cancel'])->name('medication-schedules.cancel');
    
    // Medication Administration Management
    Route::get('/visits/{visit}/medications/{medicationSchedule}/administer', [MedicationAdministrationController::class, 'create'])->name('medication-administrations.create');
    Route::post('/visits/{visit}/medications/{medicationSchedule}/administer', [MedicationAdministrationController::class, 'store'])->name('medication-administrations.store');
    Route::get('/visits/{visit}/medications/{medicationSchedule}/administrations/{medicationAdministration}', [MedicationAdministrationController::class, 'show'])->name('medication-administrations.show');
    Route::get('/medications/due', [MedicationAdministrationController::class, 'dueMedications'])->name('medication-administrations.due');
    Route::get('/visits/{visit}/medication-history', [MedicationAdministrationController::class, 'visitAdministrations'])->name('medication-administrations.visit-history');
    Route::get('/medications/recent', [MedicationAdministrationController::class, 'recentAdministrations'])->name('medication-administrations.recent');
    
    // Bed Management
    Route::get('/beds', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'index'])->name('beds.index');
    Route::get('/beds/create', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'create'])->name('beds.create');
    Route::post('/beds', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'store'])->name('beds.store');
    Route::get('/beds/{bed}', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'show'])->name('beds.show');
    Route::get('/beds/{bed}/edit', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'edit'])->name('beds.edit');
    Route::put('/beds/{bed}', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'update'])->name('beds.update');
    Route::post('/beds/filter', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'filter'])->name('beds.filter');
    Route::patch('/beds/{bed}/mark-cleaning', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'markForCleaning'])->name('beds.mark-cleaning');
    Route::patch('/beds/{bed}/mark-maintenance', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'markForMaintenance'])->name('beds.mark-maintenance');
    Route::patch('/beds/{bed}/mark-clean', [\App\Http\Controllers\Nurse\Beds\BedController::class, 'markAsClean'])->name('beds.mark-clean');
    
    // Bed Assignments
    Route::get('/bed-assignments', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'index'])->name('bed-assignments.index');
    Route::get('/visits/{visit}/bed-assignment', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'show'])->name('bed-assignments.show');
    Route::get('/visits/{visit}/bed-assignment/create', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'create'])->name('bed-assignments.create');
    Route::post('/visits/{visit}/bed-assignment', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'store'])->name('bed-assignments.store');
    Route::get('/visits/{visit}/bed-assignment/edit', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'edit'])->name('bed-assignments.edit');
    Route::put('/visits/{visit}/bed-assignment', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'update'])->name('bed-assignments.update');
    Route::delete('/visits/{visit}/bed-assignment', [\App\Http\Controllers\Nurse\Beds\BedAssignmentController::class, 'destroy'])->name('bed-assignments.destroy');
    
    // Equipment Management
    Route::get('/equipment', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/equipment/create', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('/equipment', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('/equipment/{equipment}', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'show'])->name('equipment.show');
    Route::get('/equipment/{equipment}/edit', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('/equipment/{equipment}', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'update'])->name('equipment.update');
    Route::get('/equipment/search/query', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'search'])->name('equipment.search');
    Route::post('/equipment/filter', [\App\Http\Controllers\Nurse\Equipment\EquipmentController::class, 'filter'])->name('equipment.filter');
    
    // Equipment Checkouts
    Route::get('/equipment-checkouts', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'index'])->name('equipment-checkouts.index');
    Route::get('/equipment-checkouts/create', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'create'])->name('equipment-checkouts.create');
    Route::post('/equipment-checkouts', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'store'])->name('equipment-checkouts.store');
    Route::get('/equipment-checkouts/{equipmentCheckout}', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'show'])->name('equipment-checkouts.show');
    Route::get('/equipment-checkouts/{equipmentCheckout}/checkin', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'checkin'])->name('equipment-checkouts.checkin');
    Route::post('/equipment-checkouts/{equipmentCheckout}/checkin', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'processCheckin'])->name('equipment-checkouts.process-checkin');
    Route::post('/equipment-checkouts/{equipmentCheckout}/lost', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'markAsLost'])->name('equipment-checkouts.mark-lost');
    Route::get('/equipment-checkouts/visit/{visit}', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'visitCheckouts'])->name('equipment-checkouts.visit');
    Route::get('/equipment-checkouts-overdue', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'overdue'])->name('equipment-checkouts.overdue');
    Route::get('/equipment-checkouts-history', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'history'])->name('equipment-checkouts.history');
    Route::get('/equipment-checkouts/search/visits', [\App\Http\Controllers\Nurse\Equipment\EquipmentCheckoutController::class, 'searchVisits'])->name('equipment-checkouts.search-visits');
    
    // Equipment Maintenance
    Route::get('/equipment-maintenance', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'index'])->name('equipment-maintenance.index');
    Route::get('/equipment-maintenance/create', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'create'])->name('equipment-maintenance.create');
    Route::post('/equipment-maintenance', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'store'])->name('equipment-maintenance.store');
    Route::get('/equipment-maintenance/{equipmentMaintenance}', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'show'])->name('equipment-maintenance.show');
    Route::get('/equipment-maintenance/{equipmentMaintenance}/edit', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'edit'])->name('equipment-maintenance.edit');
    Route::put('/equipment-maintenance/{equipmentMaintenance}', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'update'])->name('equipment-maintenance.update');
    Route::get('/equipment-maintenance/{equipmentMaintenance}/complete', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'complete'])->name('equipment-maintenance.complete');
    Route::post('/equipment-maintenance/{equipmentMaintenance}/complete', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'processComplete'])->name('equipment-maintenance.process-complete');
    Route::get('/equipment-maintenance-overdue', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'overdue'])->name('equipment-maintenance.overdue');
    Route::get('/equipment-maintenance-today', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'scheduledToday'])->name('equipment-maintenance.scheduled-today');
    Route::get('/equipment-maintenance-history', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'history'])->name('equipment-maintenance.history');
    Route::get('/equipment-needing-maintenance', [\App\Http\Controllers\Nurse\Equipment\EquipmentMaintenanceController::class, 'equipmentNeedingMaintenance'])->name('equipment-maintenance.equipment-needing-maintenance');
});

require __DIR__.'/auth.php';