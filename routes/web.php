<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Investment\GoalController;
use App\Http\Controllers\Investment\RecordInvestmentController;
use App\Http\Controllers\Transaction\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Investment\InvestmentController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\User\ProfileControlller;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\Transaction\ExpenseController;
use App\Http\Controllers\Transaction\IncomeController;
use App\Http\Controllers\Transaction\TransferController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushSubscriptionController;

// // dashboard pages
// Route::get('/', function () {
//     return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
// Route::get('/profile', function () {
//     return view('pages.profile', ['title' => 'Profile']);
// })->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages
Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');

// // authentication pages
// Route::get('/signin', function () {
//     return view('pages.auth.signin', ['title' => 'Sign In']);
// })->name('signin');

// Route::get('/signup', function () {
//     return view('pages.auth.signup', ['title' => 'Sign Up']);
// })->name('signup');

// // ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');

// Landing Page
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware(['guest'])->group(function(){
    Route::get('/', [LandingController::class, 'index'])->name('landing');

    // Authentication
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [ResetPasswordController::class, 'index'])->name('forgot-password');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('forgot-password.send-reset-link');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware(['auth'])->group(function(){
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Verify Account
    Route::prefix('/verify-account')->as('verify.')->group(function(){
        Route::get('/', [OTPController::class, 'index'])->name('index');
        Route::post('/', [OTPController::class, 'verify']) ->name('verify');
        Route::post('/send', [OTPController::class, 'send']) ->name('send');
        Route::post('/resend', [OTPController::class, 'resend'])->name('resend');
    });

    // Transaction
    Route::prefix('/income')->as('income.')->group(function(){
        Route::get('/', [IncomeController::class, 'index'])->name('index');
        Route::post('/create', [IncomeController::class, 'store'])->name('store');
        Route::post('/update/{id}', [IncomeController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [IncomeController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/expense')->as('expense.')->group(function(){
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::post('/create', [ExpenseController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ExpenseController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/transfer')->as('transfer.')->group(function(){
        Route::get('/', [TransferController::class, 'index'])->name('index');
        Route::post('/create', [TransferController::class, 'store'])->name('store');
        Route::post('/update/{id}', [TransferController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TransferController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/category')->as('category.')->group(function(){
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::post('/update/{slug}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // Investment
    Route::prefix('/investment')->as('investment.')->group(function(){
        // Investment
        Route::get('/', [InvestmentController::class, 'index'])->name('index');
        Route::post('/store', [InvestmentController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [InvestmentController::class, 'destroy'])->name('delete');
        Route::post('/update/{id}', [InvestmentController::class, 'update'])->name('update');

        // Goal
        Route::post('/store/goal', [GoalController::class, 'store'])->name('goal.store');
        Route::post('/update/goal/{id}', [GoalController::class, 'update'])
        ->name('goal.update');
        Route::delete('/delete/goal/{id}', [GoalController::class, 'destroy'])
            ->name('goal.delete');

        // Record
        Route::post('/store/record-investment', [RecordInvestmentController::class, 'store'])->name('record-investment.store');
        Route::get('/print/record-investment', [RecordInvestmentController::class, 'print'])->name('record-investment.print');
    });

    // Report
    Route::prefix('/report')->as('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/print', [ReportController::class, 'print'])->name('print');
    });

    // Notifications (in-app bell icon)
    Route::prefix('/notifications')->as('notifications.')->group(function(){
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/feed', [NotificationController::class, 'feed'])->name('feed');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // Browser Push (Web Push API) subscriptions
    Route::prefix('/push-subscriptions')->as('push-subscriptions.')->group(function(){
        Route::post('/', [PushSubscriptionController::class, 'store'])->name('store');
        Route::delete('/', [PushSubscriptionController::class, 'destroy'])->name('destroy');
    });


    // User
    Route::prefix('/settings')->as('settings.')->group(function(){
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('change-password');
        Route::delete('/delete-account', [SettingsController::class, 'deleteAccount'])->name('delete-account');
    });

    Route::prefix('/profile')->as('profile.')->group(function(){
        Route::get('/', [ProfileControlller::class, 'index'])->name('index');
        Route::post('/update-profile-information', [ProfileControlller::class, 'updateProfileInformation'])->name('update-profile-information');
        Route::post('/update-address-information', [ProfileControlller::class, 'updateAddressInformation'])->name('update-address-information');
    });


    });

// Locale Switcher
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'id'], true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('locale.switch');
