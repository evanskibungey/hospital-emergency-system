<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\Nurse\VitalSignController;
use Illuminate\Support\Facades\Route;

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
    
    // Visit Assignment - new route
    Route::post('/visits/{visit}/assign', [NurseDashboardController::class, 'assignVisit'])->name('assign-visit');
    
    // Vital Signs Management
    Route::get('/vital-signs/search', [VitalSignController::class, 'searchPatient'])->name('vital-signs.search-patient');
    Route::get('/vital-signs/patients/{patient}', [VitalSignController::class, 'patientVisits'])->name('vital-signs.patient-visits');
    
    Route::get('/visits/{visit}/vital-signs', [VitalSignController::class, 'index'])->name('vital-signs.index');
    Route::get('/visits/{visit}/vital-signs/create', [VitalSignController::class, 'create'])->name('vital-signs.create');
    Route::post('/visits/{visit}/vital-signs', [VitalSignController::class, 'store'])->name('vital-signs.store');
    Route::get('/visits/{visit}/vital-signs/{vitalSign}', [VitalSignController::class, 'show'])->name('vital-signs.show');
    Route::get('/visits/{visit}/vital-signs/{vitalSign}/edit', [VitalSignController::class, 'edit'])->name('vital-signs.edit');
    Route::put('/visits/{visit}/vital-signs/{vitalSign}', [VitalSignController::class, 'update'])->name('vital-signs.update');
});

require __DIR__.'/auth.php';