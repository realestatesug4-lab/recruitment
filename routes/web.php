<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployerApplicationsController;
use App\Http\Controllers\EmployerOnboardingController;
use App\Http\Controllers\EmployerDashboardController;
use App\Http\Controllers\EmployerJobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeekerApplicationController;
use App\Http\Controllers\SeekerProfileController;
use App\Http\Controllers\SeekerDashboardController;
use App\Http\Controllers\SeekerSavedJobController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/resources', [HomeController::class, 'resources'])->name('resources');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('onboarding', [EmployerOnboardingController::class, 'create'])->name('onboarding.create');
    Route::post('onboarding', [EmployerOnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
    Route::put('company', [CompanyController::class, 'updateForEmployer'])->name('company.update');
    Route::get('jobs', [EmployerJobController::class, 'index'])->name('jobs.index');
    Route::get('jobs/create', [EmployerJobController::class, 'create'])->name('jobs.create');
    Route::post('jobs', [EmployerJobController::class, 'store'])->name('jobs.store');
    Route::get('jobs/{job:slug}/edit', [EmployerJobController::class, 'edit'])->name('jobs.edit');
    Route::put('jobs/{job:slug}', [EmployerJobController::class, 'update'])->name('jobs.update');
    Route::delete('jobs/{job:slug}', [EmployerJobController::class, 'destroy'])->name('jobs.destroy');
    Route::get('applications', [EmployerApplicationsController::class, 'index'])->name('applications.index');
    Route::get('applications/{application:uuid}', [EmployerApplicationsController::class, 'show'])->name('applications.show');
});

Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    Route::prefix('seeker')->name('seeker.')->group(function () {
        Route::get('dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [SeekerProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/create', [SeekerProfileController::class, 'create'])->name('profile.create');
        Route::post('profile', [SeekerProfileController::class, 'store'])->name('profile.store');
        Route::get('profile/edit', [SeekerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [SeekerProfileController::class, 'update'])->name('profile.update');

        Route::post('jobs/{job:slug}/save', [SeekerSavedJobController::class, 'toggle'])->name('saved-jobs.toggle');

        Route::get('jobs/{job:slug}/apply', [SeekerApplicationController::class, 'create'])->name('applications.create');
        Route::post('jobs/{job:slug}/apply', [SeekerApplicationController::class, 'store'])->name('applications.store');
        Route::get('jobs/{job:slug}/thank-you', [SeekerApplicationController::class, 'thankyou'])->name('applications.thankyou');
        Route::get('applications/progress', [SeekerApplicationController::class, 'progress'])->name('applications.progress');
    });
});

Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/{company:slug}', [CompanyController::class, 'show'])->name('show');
});

require __DIR__.'/auth.php';
