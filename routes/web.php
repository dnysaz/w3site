<?php
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserDashboardController;

use App\Http\Controllers\SiteController;
use App\Http\Controllers\GitDeployController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ShowcaseController;


// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSiteController;

use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\LogSystemController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\EnvEditorController;

//Google Auth
use App\Http\Controllers\Auth\GoogleAuthController;


Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);



/*
|--------------------------------------------------------------------------
| 1. Public Static Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track-v1/{subdomain}', [AnalyticsController::class, 'track'])->name('track.pixel');
Route::get('/showcase', [ShowcaseController::class, 'index'])->name('showcase.index');



// Bantuan & Info
Route::get('/dokumentasi', [PagesController::class, 'documentation'])->name('docs.index');
Route::get('/deployment-guide', [PagesController::class, 'deploymentGuide'])->name('docs.deployment');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('terms');

/*
|--------------------------------------------------------------------------
| 2. Authentication Routes (Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| 3. Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen User
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    // Manajemen Sites
    Route::get('/sites', [AdminSiteController::class, 'index'])->name('sites.index');
    Route::patch('/sites/{id}/toggle', [AdminSiteController::class, 'toggleStatus'])->name('sites.toggle');

    // --- GRUP SETTINGS & TOOLS ---
    Route::prefix('settings')->group(function () {
        
        // System Settings
        Route::get('/', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/maintenance-toggle', [AdminSettingsController::class, 'toggleMaintenance'])->name('maintenance.toggle');

        // Terminal (Kembali ke admin.terminal.index)
        Route::get('/terminal', [TerminalController::class, 'index'])->name('terminal.index');
        Route::post('/terminal/execute', [TerminalController::class, 'execute'])->name('terminal.execute');

        // ENV Editor (Kembali ke admin.env.index)
        Route::get('/env-editor', [EnvEditorController::class, 'index'])->name('env.index');
        Route::post('/env-editor', [EnvEditorController::class, 'update'])->name('env.update');
        Route::get('/db-backup', [EnvEditorController::class, 'backupDatabase'])->name('db.backup');

        // Logs (Kembali ke admin.logs.index)
        Route::prefix('logs')->name('logs.')->middleware([\App\Http\Middleware\OnlyAdminCanSeeLogs::class])->group(function () {
            Route::get('/', [LogSystemController::class, 'index'])->name('index');
            Route::post('/clear', [LogSystemController::class, 'clear'])->name('clear');
        });
    });
});

/*
|--------------------------------------------------------------------------
| 4. User Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified','CheckMaintenance'])->group(function () {
    
    // User Dashboard Utama
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('/dashboard/profile')->group(function() {
        Route::get('/', [UserDashboardController::class, 'profile'])->name('profile');
        Route::patch('/', [UserDashboardController::class, 'update'])->name('profile.update');
        Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [UserDashboardController::class, 'destroy'])->name('profile.destroy');
    });

    // Deployment
    Route::get('/dashboard/deploy/github/{subdomain?}', [GitDeployController::class, 'index'])->name('deploy.github');
    Route::post('/dashboard/deploy/github', [GitDeployController::class, 'process'])->name('deploy.process');
    Route::post('/dashboard/deploy/github/sync/{id}', [GitDeployController::class, 'sync'])->name('deploy.github.sync');
    
    // Site Management
    Route::get('/dashboard/mysite', [UserDashboardController::class, 'mysite'])->name('mysite');
    Route::post('/dashboard/sites/store-name', [SiteController::class, 'storeName'])->name('sites.storeName');
    Route::post('/dashboard/sites/upload-file', [SiteController::class, 'uploadFile'])->name('sites.uploadFile');
    Route::post('/dashboard/sites/{id}/toggle-public', [SiteController::class, 'togglePublic'])->name('sites.togglePublic');
    Route::delete('/dashboard/delete-site/{id}', [SiteController::class, 'destroy'])->name('sites.destroy');



    Route::post('/logout', action: [UserDashboardController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 5. Wildcard/Dynamic Routes (MUST BE AT THE VERY BOTTOM)
|--------------------------------------------------------------------------
*/
// Letakkan ini paling bawah agar tidak memblokir route /admin atau /dashboard
Route::get('/{slug}', [PublicController::class, 'handleSlug'])->name('public.handle');