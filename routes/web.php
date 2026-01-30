<?php
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\GitDeployController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ShowcaseController;


// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSiteController;
use App\Http\Controllers\Admin\AdminLinktreeController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminShortlinkController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\LogSystemController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\EnvEditorController;

//Google Auth
use App\Http\Controllers\Auth\GoogleAuthController;

// Midtrans 
use App\Http\Controllers\MidtransCallbackController;
Route::post('/midtrans-callback', [MidtransCallbackController::class, 'handle']);

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

// Produk
Route::prefix('produk')->group(function () {
    Route::get('/statis', [PagesController::class, 'staticSite'])->name('product.static');
    Route::get('/ai-builder', [PagesController::class, 'aiBuilder'])->name('product.ai');
    Route::get('/domain', [PagesController::class, 'customDomain'])->name('product.domain');
    Route::get('/database', [PagesController::class, 'cloudDatabase'])->name('product.database');
});

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
    Route::patch('/users/{id}/package', [AdminUserController::class, 'updatePackage'])->name('users.update-package');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Sites & Linktree
    Route::get('/sites', [AdminSiteController::class, 'index'])->name('sites.index');
    Route::patch('/sites/{id}/toggle', [AdminSiteController::class, 'toggleStatus'])->name('sites.toggle');
    Route::get('/linktrees', [AdminLinktreeController::class, 'index'])->name('linktrees.index');
    Route::post('/linktrees/{id}/toggle', [AdminLinktreeController::class, 'toggle'])->name('linktrees.toggle');

    // Transaksi & Shortlink
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [AdminTransactionController::class, 'export'])->name('transactions.export');
    Route::get('/shortlinks', [AdminShortlinkController::class, 'index'])->name('shortlinks.index');
    Route::post('/shortlinks/{id}/toggle', [AdminShortlinkController::class, 'toggle'])->name('shortlinks.toggle');

    // Chat
    Route::get('/chat', [AdminChatController::class, 'chat_admin'])->name('chat');

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

    // Pricing & Profile
    Route::get('/dashboard/pricing', [PackageController::class, 'index'])->name('pricing');
    Route::post('/dashboard/pricing/select', [PackageController::class, 'select'])->name('pricing.select');

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
    Route::post('/dashboard/sites/deploy-ai', [SiteController::class, 'deployFromAi'])->name('sites.deployAi');
    Route::get('/dashboard/sites/{subdomain}/details', [SiteController::class, 'site_details'])->name('sites.details');
    Route::delete('/dashboard/delete-site/{id}', [SiteController::class, 'destroy'])->name('sites.destroy');

    // AI Tools General
    Route::get('/dashboard/ai-magic-tools', [UserDashboardController::class, 'ai_magic_tools'])->name('ai.index');
    Route::get('/dashboard/my-ai-design', [UserDashboardController::class, 'my_ai_design'])->name('my.ai.design');

    // Shortlink Management
    Route::prefix('dashboard/shortlink')->group(function () {
        Route::get('/', [ShortlinkController::class, 'index'])->name('shortlink');
        Route::post('/', [ShortlinkController::class, 'store'])->name('shortlink.store');
        Route::delete('/{id}', [ShortlinkController::class, 'destroy'])->name('shortlink.destroy');
    });

    // AI Linktree
    Route::prefix('/dashboard/ai-linktree')->group(function() {
        Route::get('/', [AiController::class, 'linktree'])->name('linktree');
        Route::get('/show/{id}', [AiController::class, 'show_linktree'])->name('ai.linktree.show'); 
        Route::post('/generate', [AiController::class, 'ai_linktree_generate'])->name('ai.linktree.generate');
        Route::post('/store', [AiController::class, 'store_linktree'])->name('ai.linktree.store');
        Route::delete('/delete/{id}', [AiController::class, 'delete_linktree'])->name('ai.linktree.delete');
    });

    // Billing
    Route::get('/dashboard/billing', [TransactionController::class, 'index'])->name('billing.history');
    Route::get('/dashboard/billing/invoice/{order_id}', [TransactionController::class, 'printInvoice'])->name('billing.invoice');
    /* --- Package Specific Middlewares --- */
    Route::middleware(['CheckPackage'])->group(function () {
        Route::get('/dashboard/aibuilder', [UserDashboardController::class, 'aibuilder'])->name('aibuilder');
        Route::post('/dashboard/ai/generate', [AiController::class, 'generate'])->name('ai.generate');
        Route::post('/dashboard/ai/save', [AiController::class, 'saveDesign'])->name('ai.save');
        Route::get('/dashboard/ai/edit/{fileName}', [AiController::class, 'editDesign'])->name('ai.edit');
        Route::post('/dashboard/ai/update/{fileName}', [AiController::class, 'updateDesign'])->name('ai.update');
        Route::delete('/dashboard/ai/design/{fileName}', [AiController::class, 'destroy'])->name('ai.destroy');
        Route::get('/dashboard/ai-site/preview/{fileName}', [AiController::class, 'viewFile'])->name('ai.view');
    });

    Route::middleware(['CheckPackage:pro'])->group(function () {
        Route::prefix('/dashboard/ai-swot')->group(function() {
            Route::get('/', [AiController::class, 'aiswot'])->name('ai.swot.page');
            Route::post('/generate', [AiController::class, 'aiswot_generate'])->name('ai.swot.generate');
            Route::post('/reset', [AiController::class, 'destroy_swot_session'])->name('ai.swot.reset');
        });
        Route::prefix('/dashboard/ai-seo')->group(function() {
            Route::get('/', [AiController::class, 'ai_seo_header'])->name('ai.seo.header');
            Route::post('/generate', [AiController::class, 'ai_seo_generate'])->name('ai.seo.generate');
            Route::post('/reset', [AiController::class, 'destroy_seo_session'])->name('ai.seo.destroy');
        });
        Route::prefix('/dashboard/ai-blog')->group(function() {
            Route::get('/', [AiController::class, 'ai_blog_header'])->name('ai.blog.header');
            Route::get('/show/{id}', [AiController::class, 'show_blog'])->name('ai.blog.show'); 
            Route::post('/generate', [AiController::class, 'ai_blog_generate'])->name('ai.blog.generate');
            Route::post('/store', [AiController::class, 'store_blog'])->name('ai.blog.store');
            Route::delete('/delete/{id}', [AiController::class, 'delete_blog'])->name('ai.blog.delete');
        });
    });
    
    Route::post('/logout', action: [UserDashboardController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 5. Wildcard/Dynamic Routes (MUST BE AT THE VERY BOTTOM)
|--------------------------------------------------------------------------
*/
// Letakkan ini paling bawah agar tidak memblokir route /admin atau /dashboard
Route::get('/{slug}', [PublicController::class, 'handleSlug'])->name('public.handle');