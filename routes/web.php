<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\ParkingAreaController;
use App\Http\Controllers\Admin\ParkingSlotController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Petugas\PetugasController;
use App\Http\Controllers\User\ParkingController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeSectionController::class, 'showHome'])->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (AUTO ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

    return match (true) {
        $user->hasRole('admin') => redirect()->route('admin.dashboard'),
        $user->hasRole('petugas') => redirect()->route('petugas.dashboard'),
        $user->hasRole('pengguna') => redirect()->route('pengguna.dashboard'),
        default => abort(403, 'Role tidak ditemukan')
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        Route::resource('users', UserManagementController::class);
        Route::resource('parking-areas', ParkingAreaController::class)->except(['show']);
        Route::resource('parking-slots', ParkingSlotController::class);

        Route::get('/home-section', [HomeSectionController::class, 'edit'])->name('home.edit');
        Route::put('/home-section', [HomeSectionController::class, 'update'])->name('home.update');
        Route::get('/history', [DashboardController::class, 'history'])
            ->name('history');
    });

/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

        Route::get('/monitoring', [PetugasController::class, 'monitoring'])->name('monitoring');

        Route::get('/gate', [PetugasController::class, 'gate'])->name('gate');

        Route::get('/history', [PetugasController::class, 'history'])->name('history');

        // Ganti password
        Route::get('/password', [PetugasController::class, 'password'])->name('password');
        Route::post('/password', [PetugasController::class, 'updatePassword'])->name('updatePassword');
    });

/*
|--------------------------------------------------------------------------
| PENGGUNA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pengguna'])
    ->prefix('pengguna')
    ->name('pengguna.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        // Slot parkir
        Route::get('/slots', [ParkingController::class, 'index'])->name('slots');

        // History
        Route::get('/history', [UserController::class, 'history'])->name('history');

        // Ganti password
        Route::get('/password', [UserController::class, 'password'])->name('password');
        Route::post('/password', [UserController::class, 'updatePassword'])->name('updatePassword');
    });

require __DIR__ . '/auth.php';
