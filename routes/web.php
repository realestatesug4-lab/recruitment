<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployerApplicationsController;
use App\Http\Controllers\EmployerOnboardingController;
use App\Http\Controllers\EmployerDashboardController;
use App\Http\Controllers\EmployerJobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeekerApplicationController;
use App\Http\Controllers\SeekerProfileController;
use App\Http\Controllers\SeekerDashboardController;
use App\Http\Controllers\SeekerSavedJobController;
use App\Http\Controllers\AiCareerController;
use App\Http\Controllers\AtsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/resources', [HomeController::class, 'resources'])->name('resources');

// Dynamic sitemap
Route::get('/sitemap.xml', function () {
    $sitemap = \Spatie\Sitemap\Sitemap::create();

    $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));
    $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/about')->setPriority(0.5)->setChangeFrequency('monthly'));
    $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/jobs')->setPriority(0.9)->setChangeFrequency('daily'));
    $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/companies')->setPriority(0.7)->setChangeFrequency('weekly'));

    // Published jobs
    \App\Domain\Jobs\Models\Job::published()->get()->each(function ($job) use ($sitemap) {
        $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create("/jobs/{$job->slug}")
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate($job->updated_at)
        );
    });

    // Company profiles
    \App\Domain\Companies\Models\Company::whereNotNull('slug')->get()->each(function ($company) use ($sitemap) {
        $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create("/companies/{$company->slug}")
                ->setPriority(0.6)
                ->setChangeFrequency('monthly')
        );
    });

    return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.mark-all-read');
});

/*
|--------------------------------------------------------------------------
| Employer routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('onboarding', [EmployerOnboardingController::class, 'create'])->name('onboarding.create');
    Route::post('onboarding', [EmployerOnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

    // Company profile
    Route::get('company', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('company', [CompanyController::class, 'updateForEmployer'])->name('company.update');

    // Jobs
    Route::get('jobs', [EmployerJobController::class, 'index'])->name('jobs.index');
    Route::get('jobs/create', [EmployerJobController::class, 'create'])->name('jobs.create');
    Route::post('jobs', [EmployerJobController::class, 'store'])->name('jobs.store');
    Route::get('jobs/{job:slug}/edit', [EmployerJobController::class, 'edit'])->name('jobs.edit');
    Route::put('jobs/{job:slug}', [EmployerJobController::class, 'update'])->name('jobs.update');
    Route::delete('jobs/{job:slug}', [EmployerJobController::class, 'destroy'])->name('jobs.destroy');

    // Applications (list + detail)
    Route::get('applications', [EmployerApplicationsController::class, 'index'])->name('applications.index');
    Route::get('applications/{application:uuid}', [EmployerApplicationsController::class, 'show'])->name('applications.show');

    // ATS Kanban
    Route::get('ats', [AtsController::class, 'index'])->name('ats');
    Route::patch('ats/{application:uuid}/status', [AtsController::class, 'updateStatus'])->name('ats.update-status');
});

/*
|--------------------------------------------------------------------------
| Public job listings
|--------------------------------------------------------------------------
*/
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Seeker routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::prefix('seeker')->name('seeker.')->group(function () {
        Route::get('dashboard', [SeekerDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('profile', [SeekerProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/create', [SeekerProfileController::class, 'create'])->name('profile.create');
        Route::post('profile', [SeekerProfileController::class, 'store'])->name('profile.store');
        Route::get('profile/edit', [SeekerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [SeekerProfileController::class, 'update'])->name('profile.update');

        // Saved jobs
        Route::get('saved-jobs', [SeekerSavedJobController::class, 'index'])->name('saved-jobs');
        Route::post('jobs/{job:slug}/save', [SeekerSavedJobController::class, 'toggle'])->name('saved-jobs.toggle');

        // Applications
        Route::get('jobs/{job:slug}/apply', [SeekerApplicationController::class, 'create'])->name('applications.create');
        Route::post('jobs/{job:slug}/apply', [SeekerApplicationController::class, 'store'])->name('applications.store');
        Route::get('jobs/{job:slug}/thank-you', [SeekerApplicationController::class, 'thankyou'])->name('applications.thankyou');
        Route::get('applications', [SeekerApplicationController::class, 'progress'])->name('applications.progress');
        Route::get('applications/{application:uuid}', [SeekerApplicationController::class, 'show'])->name('applications.show');

        // AI Career Tools
        Route::middleware('throttle:ai-tools')->group(function () {
            Route::get('ai-tools', [AiCareerController::class, 'index'])->name('ai-tools');
            Route::post('ai-tools/cover-letter', [AiCareerController::class, 'coverLetter'])->name('ai-tools.cover-letter');
            Route::post('ai-tools/interview', [AiCareerController::class, 'interview'])->name('ai-tools.interview');
            Route::post('ai-tools/salary', [AiCareerController::class, 'salary'])->name('ai-tools.salary');
            Route::post('ai-tools/cv', [AiCareerController::class, 'cv'])->name('ai-tools.cv');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public company pages
|--------------------------------------------------------------------------
*/
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/{company:slug}', [CompanyController::class, 'show'])->name('show');
});

require __DIR__.'/auth.php';
