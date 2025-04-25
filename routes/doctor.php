<?php

use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\ConsultationRequestController;
use App\Http\Controllers\Doctor\DoctorTaskController;
use App\Http\Controllers\Doctor\TreatmentController;
use App\Http\Controllers\Doctor\MedicalNoteController;
use App\Http\Controllers\Doctor\LabOrderController;
use App\Http\Controllers\Doctor\ImagingOrderController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\DischargeController;
use App\Http\Controllers\Doctor\FollowUpAppointmentController;
use Illuminate\Support\Facades\Route;

// Doctor routes
Route::middleware(['auth', 'verified', 'role:doctor,admin'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/on-call-status', [DashboardController::class, 'updateOnCallStatus'])->name('on-call-status.update');
    
    // Visit Management
    Route::get('/visits/{visit}', [DashboardController::class, 'showVisit'])->name('visits.show');
    Route::patch('/visits/{visit}/toggle-critical', [DashboardController::class, 'toggleCritical'])->name('visits.toggle-critical');
    Route::post('/visits/{visit}/assign', [DashboardController::class, 'assignVisit'])->name('visits.assign');
    Route::delete('/visits/{visit}/release', [DashboardController::class, 'releaseVisit'])->name('visits.release');
    
    // Patient Management
    Route::get('/patients/{patientId}/summary', [DashboardController::class, 'patientSummary'])->name('patients.summary');
    
    // Consultation Requests
    Route::get('/consultations', [ConsultationRequestController::class, 'index'])->name('consultations.index');
    Route::get('/visits/{visit}/consultations/create', [ConsultationRequestController::class, 'create'])->name('consultations.create');
    Route::post('/visits/{visit}/consultations', [ConsultationRequestController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{consultationRequest}', [ConsultationRequestController::class, 'show'])->name('consultations.show');
    Route::patch('/consultations/{consultationRequest}/accept', [ConsultationRequestController::class, 'accept'])->name('consultations.accept');
    Route::patch('/consultations/{consultationRequest}/complete', [ConsultationRequestController::class, 'complete'])->name('consultations.complete');
    
    // Doctor Tasks
    Route::get('/tasks', [DoctorTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [DoctorTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [DoctorTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [DoctorTaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [DoctorTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [DoctorTaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/status', [DoctorTaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/tasks/{task}/start', [DoctorTaskController::class, 'startTask'])->name('tasks.start');
    Route::patch('/tasks/{task}/complete', [DoctorTaskController::class, 'completeTask'])->name('tasks.complete');
    Route::delete('/tasks/{task}', [DoctorTaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Treatment Management
    Route::get('/treatments', [TreatmentController::class, 'index'])->name('treatments.index');
    Route::get('/visits/{visit}/treatments/create', [TreatmentController::class, 'create'])->name('treatments.create');
    Route::post('/visits/{visit}/treatments', [TreatmentController::class, 'store'])->name('treatments.store');
    Route::get('/treatments/{treatment}', [TreatmentController::class, 'show'])->name('treatments.show');
    Route::get('/treatments/{treatment}/edit', [TreatmentController::class, 'edit'])->name('treatments.edit');
    Route::put('/treatments/{treatment}', [TreatmentController::class, 'update'])->name('treatments.update');
    Route::patch('/treatments/{treatment}/complete', [TreatmentController::class, 'complete'])->name('treatments.complete');
    Route::patch('/treatments/{treatment}/discontinue', [TreatmentController::class, 'discontinue'])->name('treatments.discontinue');
    
    // Medical Notes
    Route::get('/visits/{visit}/medical-notes', [MedicalNoteController::class, 'index'])->name('medical-notes.index');
    Route::get('/visits/{visit}/medical-notes/create', [MedicalNoteController::class, 'create'])->name('medical-notes.create');
    Route::post('/visits/{visit}/medical-notes', [MedicalNoteController::class, 'store'])->name('medical-notes.store');
    Route::get('/visits/{visit}/medical-notes/{medicalNote}', [MedicalNoteController::class, 'show'])->name('medical-notes.show');
    Route::get('/visits/{visit}/medical-notes/{medicalNote}/edit', [MedicalNoteController::class, 'edit'])->name('medical-notes.edit');
    Route::put('/visits/{visit}/medical-notes/{medicalNote}', [MedicalNoteController::class, 'update'])->name('medical-notes.update');
    Route::delete('/visits/{visit}/medical-notes/{medicalNote}', [MedicalNoteController::class, 'destroy'])->name('medical-notes.destroy');
    
    // Lab Orders
    Route::get('/lab-orders', [LabOrderController::class, 'index'])->name('lab-orders.index');
    Route::get('/visits/{visit}/lab-orders/create', [LabOrderController::class, 'create'])->name('lab-orders.create');
    Route::post('/visits/{visit}/lab-orders', [LabOrderController::class, 'store'])->name('lab-orders.store');
    Route::get('/lab-orders/{labOrder}', [LabOrderController::class, 'show'])->name('lab-orders.show');
    Route::get('/lab-orders/{labOrder}/edit', [LabOrderController::class, 'edit'])->name('lab-orders.edit');
    Route::put('/lab-orders/{labOrder}', [LabOrderController::class, 'update'])->name('lab-orders.update');
    Route::patch('/lab-orders/{labOrder}/cancel', [LabOrderController::class, 'cancel'])->name('lab-orders.cancel');
    Route::post('/lab-orders/{labOrder}/results', [LabOrderController::class, 'updateResults'])->name('lab-orders.update-results');
    
    // Imaging Orders
    Route::get('/imaging-orders', [ImagingOrderController::class, 'index'])->name('imaging-orders.index');
    Route::get('/visits/{visit}/imaging-orders/create', [ImagingOrderController::class, 'create'])->name('imaging-orders.create');
    Route::post('/visits/{visit}/imaging-orders', [ImagingOrderController::class, 'store'])->name('imaging-orders.store');
    Route::get('/imaging-orders/{imagingOrder}', [ImagingOrderController::class, 'show'])->name('imaging-orders.show');
    Route::get('/imaging-orders/{imagingOrder}/edit', [ImagingOrderController::class, 'edit'])->name('imaging-orders.edit');
    Route::put('/imaging-orders/{imagingOrder}', [ImagingOrderController::class, 'update'])->name('imaging-orders.update');
    Route::patch('/imaging-orders/{imagingOrder}/cancel', [ImagingOrderController::class, 'cancel'])->name('imaging-orders.cancel');
    Route::post('/imaging-orders/{imagingOrder}/results', [ImagingOrderController::class, 'updateResults'])->name('imaging-orders.update-results');
    
    // Prescriptions
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/visits/{visit}/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/visits/{visit}/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::get('/prescriptions/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
    Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
    Route::patch('/prescriptions/{prescription}/complete', [PrescriptionController::class, 'complete'])->name('prescriptions.complete');
    Route::patch('/prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])->name('prescriptions.cancel');
    Route::patch('/prescriptions/{prescription}/hold', [PrescriptionController::class, 'hold'])->name('prescriptions.hold');
    Route::patch('/prescriptions/{prescription}/reactivate', [PrescriptionController::class, 'reactivate'])->name('prescriptions.reactivate');
    
    // Discharges
    Route::get('/discharges', [DischargeController::class, 'index'])->name('discharges.index');
    Route::get('/visits/{visit}/discharge/create', [DischargeController::class, 'create'])->name('discharges.create');
    Route::post('/visits/{visit}/discharge', [DischargeController::class, 'store'])->name('discharges.store');
    Route::get('/discharges/{discharge}', [DischargeController::class, 'show'])->name('discharges.show');
    Route::get('/discharges/{discharge}/edit', [DischargeController::class, 'edit'])->name('discharges.edit');
    Route::put('/discharges/{discharge}', [DischargeController::class, 'update'])->name('discharges.update');
    Route::get('/discharges/{discharge}/print', [DischargeController::class, 'printInstructions'])->name('discharges.print');
    
    // Follow-up Appointments
    Route::get('/follow-up-appointments', [FollowUpAppointmentController::class, 'index'])->name('follow-up-appointments.index');
    Route::get('/follow-up-appointments/create', [FollowUpAppointmentController::class, 'create'])->name('follow-up-appointments.create');
    Route::post('/follow-up-appointments', [FollowUpAppointmentController::class, 'store'])->name('follow-up-appointments.store');
    Route::get('/follow-up-appointments/{followUpAppointment}', [FollowUpAppointmentController::class, 'show'])->name('follow-up-appointments.show');
    Route::get('/follow-up-appointments/{followUpAppointment}/edit', [FollowUpAppointmentController::class, 'edit'])->name('follow-up-appointments.edit');
    Route::put('/follow-up-appointments/{followUpAppointment}', [FollowUpAppointmentController::class, 'update'])->name('follow-up-appointments.update');
    Route::patch('/follow-up-appointments/{followUpAppointment}/confirm', [FollowUpAppointmentController::class, 'confirm'])->name('follow-up-appointments.confirm');
    Route::patch('/follow-up-appointments/{followUpAppointment}/complete', [FollowUpAppointmentController::class, 'complete'])->name('follow-up-appointments.complete');
    Route::patch('/follow-up-appointments/{followUpAppointment}/no-show', [FollowUpAppointmentController::class, 'noShow'])->name('follow-up-appointments.no-show');
    Route::patch('/follow-up-appointments/{followUpAppointment}/cancel', [FollowUpAppointmentController::class, 'cancel'])->name('follow-up-appointments.cancel');
    Route::get('/follow-up-appointments/{followUpAppointment}/print', [FollowUpAppointmentController::class, 'print'])->name('follow-up-appointments.print');
});
