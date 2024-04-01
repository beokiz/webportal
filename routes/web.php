<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\EvaluationScreeningController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DomainsController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\KitaController;
use App\Http\Controllers\MilestonesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubdomainsController;
use App\Http\Controllers\SurveyTimePeriodController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\YearlyEvaluationsController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Auth\RegisteredUserController;
//use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Main routes
 */
Route::group(['as' => 'main.', 'middleware' => []], function () {
    Route::get('/', function () {
        return redirect()->route('profile.edit');
    })->name('index');
});

/*
 * Dashboard routes
 */
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth', 'verified_2fa']], function () {
//    Route::get('/', [DashboardController::class, 'index'])->name('index');
});


/*
 * Profile routes
 */
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
//    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});


/*
 * Authentication, 2FA & verification routes
 */
Route::group(['middleware' => ['guest']], function () {
    Route::group(['as' => 'auth.'], function () {
        Route::get('register', function () {
            return redirect()->route('auth.login');
        });

//        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
//        Route::post('register', [RegisteredUserController::class, 'store']);
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::group(['as' => 'password.'], function () {
        Route::get('reset-password', function () {
            return redirect()->route('auth.login');
        });

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('store');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['as' => 'auth.'], function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::group(['as' => 'verification.'], function () {
        Route::get('verify-email', EmailVerificationPromptController::class)->name('notice');
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('send');
    });

    Route::group(['as' => 'password.'], function () {
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('update');
    });
});

Route::group(['prefix' => '2fa', 'as' => '2fa.', 'middleware' => ['protect_2fa_page']], function () {
    Route::get('/', [TwoFactorAuthenticationController::class, 'create'])->name('create');
    Route::post('/verify', [TwoFactorAuthenticationController::class, 'store'])->name('verify');
    Route::post('/resend', [TwoFactorAuthenticationController::class, 'resend'])->name('resend');
});


/*
 * Users routes
 */
Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [UsersController::class, 'index'])->name('index');
//    Route::get('/{user}', [UsersController::class, 'show'])->name('show');
    Route::get('/{user}', [UsersController::class, 'edit'])->name('edit');
    Route::post('/', [UsersController::class, 'store'])->name('store');
    Route::post('/kita', [UsersController::class, 'storeFromKita'])->name('store_from_kita');
    Route::put('/{user}', [UsersController::class, 'update'])->name('update');
    Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
});


/*
 * Domains routes
 */
Route::group(['prefix' => 'domains', 'as' => 'domains.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [DomainsController::class, 'index'])->name('index');
    Route::get('/{domain}', [DomainsController::class, 'show'])->name('show');
    Route::post('/', [DomainsController::class, 'store'])->name('store');
    Route::put('/{domain}', [DomainsController::class, 'update'])->name('update');
    Route::delete('/{domain}', [DomainsController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [DomainsController::class, 'reorder'])->name('reorder');
});


/*
 * Subdomains routes
 */
Route::group(['prefix' => 'subdomains', 'as' => 'subdomains.', 'middleware' => ['auth', 'verified_2fa']], function () {
//    Route::get('/', [SubdomainsController::class, 'index'])->name('index');
    Route::get('/{subdomain}', [SubdomainsController::class, 'show'])->name('show');
    Route::post('/', [SubdomainsController::class, 'store'])->name('store');
    Route::put('/{subdomain}', [SubdomainsController::class, 'update'])->name('update');
    Route::delete('/{subdomain}', [SubdomainsController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [SubdomainsController::class, 'reorder'])->name('reorder');
});


/*
 * Milestones routes
 */
Route::group(['prefix' => 'milestones', 'as' => 'milestones.', 'middleware' => ['auth', 'verified_2fa']], function () {
//    Route::get('/', [MilestonesController::class, 'index'])->name('index');
    Route::get('/{milestone}', [MilestonesController::class, 'show'])->name('show');
    Route::post('/', [MilestonesController::class, 'store'])->name('store');
    Route::put('/{milestone}', [MilestonesController::class, 'update'])->name('update');
    Route::delete('/{milestone}', [MilestonesController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [MilestonesController::class, 'reorder'])->name('reorder');
});


/*
 * Kitas routes
 */
Route::group(['prefix' => 'kitas', 'as' => 'kitas.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [KitaController::class, 'index'])->name('index');
    Route::get('/{kita}', [KitaController::class, 'show'])->name('show');
    Route::post('/', [KitaController::class, 'store'])->name('store');
    Route::put('/{kita}', [KitaController::class, 'update'])->name('update');
    Route::post('/{kita}/connect-user', [KitaController::class, 'connectUser'])->name('connect_user');
    Route::post('/{kita}/connect-users', [KitaController::class, 'connectUsers'])->name('connect_users');
    Route::post('/{kita}/disconnect-user', [KitaController::class, 'disconnectUser'])->name('disconnect_user');
    Route::post('/{kita}/disconnect-users', [KitaController::class, 'disconnectUsers'])->name('disconnect_users');
    Route::delete('/{kita}', [KitaController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [KitaController::class, 'reorder'])->name('reorder');
});


/*
 * Evaluations routes
 */
Route::group(['prefix' => 'evaluations', 'as' => 'evaluations.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [EvaluationController::class, 'index'])->name('index');
    Route::get('/create', [EvaluationController::class, 'create'])->name('create');
    Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
    Route::post('/{evaluation}/popup', [EvaluationController::class, 'showPopup'])->name('show_popup');
    Route::get('/{evaluation}/pdf', [EvaluationController::class, 'pdf'])->name('pdf');
    Route::get('/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('edit');
    Route::post('/', [EvaluationController::class, 'store'])->name('store');
    Route::put('/{evaluation}', [EvaluationController::class, 'update'])->name('update');
    Route::post('/{evaluation}/unfinished', [EvaluationController::class, 'unfinished'])->name('unfinished');
    Route::post('/save', [EvaluationController::class, 'save'])->name('save');
    Route::delete('/{evaluation}', [EvaluationController::class, 'destroy'])->name('destroy');
});


/*
 * Check Evaluation routes
 */
Route::group(['prefix' => 'screening', 'as' => 'screening.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [EvaluationScreeningController::class, 'index'])->name('index');
    Route::get('/{domain}', [EvaluationScreeningController::class, 'show'])->name('show');
    Route::post('/make', [EvaluationScreeningController::class, 'make'])->name('make');
});


/*
 * Screening Export routes
 */
Route::group(['prefix' => 'export', 'as' => 'export.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [ExportController::class, 'index'])->name('index');
    Route::post('/', [ExportController::class, 'make'])->name('make');
});


/*
 * Survey time periods routes
 */
Route::group(['prefix' => 'survey-time-periods', 'as' => 'survey_time_periods.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [SurveyTimePeriodController::class, 'index'])->name('index');
    Route::get('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'show'])->name('show');
    Route::post('/', [SurveyTimePeriodController::class, 'store'])->name('store');
    Route::put('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'update'])->name('update');
    Route::delete('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'destroy'])->name('destroy');
});


/*
 * Yearly evaluations routes
 */
Route::group(['prefix' => 'yearly-evaluations', 'as' => 'yearly_evaluations.', 'middleware' => ['auth', 'verified_2fa']], function () {
    Route::get('/', [YearlyEvaluationsController::class, 'index'])->name('index');
    Route::get('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'show'])->name('show');
    Route::post('/', [YearlyEvaluationsController::class, 'store'])->name('store');
    Route::put('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'update'])->name('update');
    Route::delete('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'destroy'])->name('destroy');
});
