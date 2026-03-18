<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\DoctorController;
use  App\Http\Controllers\LoginController;
use  App\Http\Controllers\DashboardController;


Route::get('/', [DoctorController::class, 'index']);
Route::post('/doctor-store', [DoctorController::class, 'store'])->name('doctor.store');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/doctors', [DashboardController::class, 'index'])->name('doctors.index');
    Route::delete('/admin/doctors/{id}', [DashboardController::class, 'destroy'])
        ->name('doctors.destroy');
    Route::get('admin/doctors/export', [DashboardController::class, 'export'])->name('doctors.export');


});
