<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerifiedPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DomainsController;
use App\Http\Controllers\DownloadableFilesController;
use App\Http\Controllers\DownloadAreaController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationScreeningController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImprintAndSupportController;
use App\Http\Controllers\KitaController;
use App\Http\Controllers\MilestonesController;
use App\Http\Controllers\OperatorsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubdomainsController;
use App\Http\Controllers\SurveyTimePeriodController;
use App\Http\Controllers\TrainingProposalsController;
use App\Http\Controllers\TrainingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\YearlyEvaluationsController;
use Illuminate\Support\Facades\Route;

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
|--------------------------------------------------------------------------
| Main routes
|--------------------------------------------------------------------------
*/
Route::group(['as' => 'main.', 'middleware' => []], function () {
    Route::get('/', function () {
        return redirect()->route('profile.edit');
    })->name('index');
});


/*
|--------------------------------------------------------------------------
| Authentication, 2FA & verification routes
|--------------------------------------------------------------------------
*/
Route::group([], function () {
    Route::group(['as' => 'auth.', 'middleware' => ['guest']], function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store'])->name('register_submit');
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::group(['as' => 'password.', 'middleware' => ['guest']], function () {
        Route::get('reset-password', function () {
            return redirect()->route('auth.login');
        });

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'verification.'], function () {
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verify');
        Route::get('verified', EmailVerifiedPromptController::class)->name('verified_notice');
    });

    // Custom verification
    Route::group(['prefix' => 'training-proposals', 'as' => 'training_proposals.'], function () {
        Route::get('/{trainingProposal}/confirm', [TrainingProposalsController::class, 'confirm'])->name('confirm');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['as' => 'auth.'], function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::group(['as' => 'verification.'], function () {
//        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verify');
        Route::get('verify-email', EmailVerificationPromptController::class)->name('notice');
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
|--------------------------------------------------------------------------
| Beokiz routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'verified', 'verified_2fa']], function () {
    /*
     * Dashboard routes
     */
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
//        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    /*
     * Profile routes
     */
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
//        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
     * Users routes
     */
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
//        Route::get('/{user}', [UsersController::class, 'show'])->name('show');
        Route::get('/{user}', [UsersController::class, 'edit'])->name('edit');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::post('/kita', [UsersController::class, 'storeFromKita'])->name('store_from_kita');
        Route::post('/operator', [UsersController::class, 'storeFromOperator'])->name('store_from_operator');
        Route::put('/{user}', [UsersController::class, 'update'])->name('update');
        Route::post('/{user}/send-verification-link', [UsersController::class, 'sendVerificationLink'])->name('send_verification_link');
        Route::post('/{user}/send-welcome-notification', [UsersController::class, 'sendWelcomeNotification'])->name('send_welcome_notification');
        Route::post('/{user}/connect-kita', [UsersController::class, 'connectKita'])->name('connect_kita');
        Route::post('/{user}/connect-kitas', [UsersController::class, 'connectKitas'])->name('connect_kitas');
        Route::post('/{user}/disconnect-kita', [UsersController::class, 'disconnectKita'])->name('disconnect_kita');
        Route::post('/{user}/disconnect-kitas', [UsersController::class, 'disconnectKitas'])->name('disconnect_kitas');
        Route::post('/{user}/connect-operator', [UsersController::class, 'connectOperator'])->name('connect_operator');
        Route::post('/{user}/connect-operators', [UsersController::class, 'connectOperators'])->name('connect_operators');
        Route::post('/{user}/disconnect-operator', [UsersController::class, 'disconnectOperator'])->name('disconnect_operator');
        Route::post('/{user}/disconnect-operators', [UsersController::class, 'disconnectOperators'])->name('disconnect_operators');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
    });

    /*
     * Domains routes
     */
    Route::group(['prefix' => 'domains', 'as' => 'domains.'], function () {
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
    Route::group(['prefix' => 'subdomains', 'as' => 'subdomains.'], function () {
//        Route::get('/', [SubdomainsController::class, 'index'])->name('index');
        Route::get('/{subdomain}', [SubdomainsController::class, 'show'])->name('show');
        Route::post('/', [SubdomainsController::class, 'store'])->name('store');
        Route::put('/{subdomain}', [SubdomainsController::class, 'update'])->name('update');
        Route::delete('/{subdomain}', [SubdomainsController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [SubdomainsController::class, 'reorder'])->name('reorder');
    });

    /*
     * Milestones routes
     */
    Route::group(['prefix' => 'milestones', 'as' => 'milestones.'], function () {
//        Route::get('/', [MilestonesController::class, 'index'])->name('index');
        Route::get('/{milestone}', [MilestonesController::class, 'show'])->name('show');
        Route::post('/', [MilestonesController::class, 'store'])->name('store');
        Route::put('/{milestone}', [MilestonesController::class, 'update'])->name('update');
        Route::delete('/{milestone}', [MilestonesController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [MilestonesController::class, 'reorder'])->name('reorder');
    });

    /*
     * Kitas routes
     */
    Route::group(['prefix' => 'kitas', 'as' => 'kitas.'], function () {
        Route::get('/', [KitaController::class, 'index'])->name('index');
        Route::get('/{kita}', [KitaController::class, 'show'])->name('show');
        Route::post('/', [KitaController::class, 'store'])->name('store');
        Route::put('/{kita}', [KitaController::class, 'update'])->name('update');
        Route::post('/{kita}/send-kita-certificate-notification', [KitaController::class, 'sendKitaCertificateNotification'])->name('send_kita_certificate_notification');
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
    Route::group(['prefix' => 'evaluations', 'as' => 'evaluations.'], function () {
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
     * Evaluation Screening routes
     */
    Route::group(['prefix' => 'screening', 'as' => 'screening.'], function () {
        Route::get('/', [EvaluationScreeningController::class, 'index'])->name('index');
        Route::get('/pdf', [EvaluationScreeningController::class, 'pdf'])->name('pdf');
        Route::post('/make', [EvaluationScreeningController::class, 'make'])->name('make');
        Route::get('/{domain}', [EvaluationScreeningController::class, 'show'])->name('show');
    });

    /*
     * Screening Export routes
     */
    Route::group(['prefix' => 'export', 'as' => 'export.'], function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::post('/', [ExportController::class, 'make'])->name('make');
    });

    /*
     * Yearly evaluations routes
     */
    Route::group(['prefix' => 'yearly-evaluations', 'as' => 'yearly_evaluations.'], function () {
        Route::get('/', [YearlyEvaluationsController::class, 'index'])->name('index');
        Route::get('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'show'])->name('show');
        Route::post('/', [YearlyEvaluationsController::class, 'store'])->name('store');
        Route::put('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'update'])->name('update');
        Route::delete('/{yearlyEvaluation}', [YearlyEvaluationsController::class, 'destroy'])->name('destroy');
    });

    /*
     * Settings routes
     */
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'update'])->name('update');
    });

    /*
     * Survey time periods routes
     */
    Route::group(['prefix' => 'survey-time-periods', 'as' => 'survey_time_periods.'], function () {
        Route::get('/', function () {
            return redirect()->route('settings.index');
        })->name('index');

//        Route::get('/', [SurveyTimePeriodController::class, 'index'])->name('index');
        Route::get('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'show'])->name('show');
        Route::post('/', [SurveyTimePeriodController::class, 'store'])->name('store');
        Route::put('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'update'])->name('update');
        Route::delete('/{surveyTimePeriod}', [SurveyTimePeriodController::class, 'destroy'])->name('destroy');
    });

    /*
     * Downloadable files routes
     */
    Route::group(['prefix' => 'downloadable-files', 'as' => 'downloadable_files.'], function () {
        Route::get('/', function () {
            return redirect()->route('settings.index');
        })->name('index');

//        Route::get('/', [DownloadableFilesController::class, 'index'])->name('index');
        Route::get('/{downloadableFile}', [DownloadableFilesController::class, 'show'])->name('show');
        Route::post('/', [DownloadableFilesController::class, 'store'])->name('store');
        Route::put('/{downloadableFile}', [DownloadableFilesController::class, 'update'])->name('update');
        Route::delete('/{downloadableFile}', [DownloadableFilesController::class, 'destroy'])->name('destroy');
    });

    /*
     * Download area routes
     */
    Route::group(['prefix' => 'download-area', 'as' => 'download_area.'], function () {
        Route::get('/', [DownloadAreaController::class, 'index'])->name('index');
    });

    /*
     * Operators routes
     */
    Route::group(['prefix' => 'operators', 'as' => 'operators.'], function () {
        Route::get('/', [OperatorsController::class, 'index'])->name('index');
        Route::get('/{operator}', [OperatorsController::class, 'show'])->name('show');
        Route::post('/', [OperatorsController::class, 'store'])->name('store');
        Route::put('/{operator}', [OperatorsController::class, 'update'])->name('update');
        Route::post('/{operator}/connect-user', [OperatorsController::class, 'connectUser'])->name('connect_user');
        Route::post('/{operator}/connect-users', [OperatorsController::class, 'connectUsers'])->name('connect_users');
        Route::post('/{operator}/disconnect-user', [OperatorsController::class, 'disconnectUser'])->name('disconnect_user');
        Route::post('/{operator}/disconnect-users', [OperatorsController::class, 'disconnectUsers'])->name('disconnect_users');
        Route::post('/{operator}/connect-kita', [OperatorsController::class, 'connectKita'])->name('connect_kita');
        Route::post('/{operator}/connect-kitas', [OperatorsController::class, 'connectKitas'])->name('connect_kitas');
        Route::post('/{operator}/disconnect-kita', [OperatorsController::class, 'disconnectKita'])->name('disconnect_kita');
        Route::post('/{operator}/disconnect-kitas', [OperatorsController::class, 'disconnectKitas'])->name('disconnect_kitas');
        Route::delete('/{operator}', [OperatorsController::class, 'destroy'])->name('destroy');
    });

    /*
     * Training routes
     */
    Route::group(['prefix' => 'trainings', 'as' => 'trainings.'], function () {
        Route::get('/', [TrainingsController::class, 'index'])->name('index');
        Route::get('/{training}', [TrainingsController::class, 'show'])->name('show');
        Route::post('/', [TrainingsController::class, 'store'])->name('store');
        Route::put('/{training}', [TrainingsController::class, 'update'])->name('update');
        Route::post('/{training}/add-kita', [TrainingsController::class, 'addKita'])->name('add_kita');
        Route::post('/{training}/add-kitas', [TrainingsController::class, 'addKitas'])->name('add_kitas');
        Route::post('/{training}/remove-kita', [TrainingsController::class, 'removeKita'])->name('remove_kita');
        Route::post('/{training}/remove-kitas', [TrainingsController::class, 'removeKitas'])->name('remove_kitas');
        Route::delete('/{training}', [TrainingsController::class, 'destroy'])->name('destroy');
    });

    /*
     * Training proposals routes
     */
    Route::group(['prefix' => 'training-proposals', 'as' => 'training_proposals.'], function () {
        Route::get('/', [TrainingProposalsController::class, 'index'])->name('index');
        Route::get('/{trainingProposal}', [TrainingProposalsController::class, 'show'])->name('show');
        Route::post('/', [TrainingProposalsController::class, 'store'])->name('store');
        Route::put('/{trainingProposal}', [TrainingProposalsController::class, 'update'])->name('update');
        Route::post('/{trainingProposal}/add-multiplier', [TrainingProposalsController::class, 'addMultiplier'])->name('add_multiplier');
        Route::post('/{trainingProposal}/add-kita', [TrainingProposalsController::class, 'addKita'])->name('add_kita');
        Route::post('/{trainingProposal}/add-kitas', [TrainingProposalsController::class, 'addKitas'])->name('add_kitas');
        Route::post('/{trainingProposal}/remove-kita', [TrainingProposalsController::class, 'removeKita'])->name('remove_kita');
        Route::post('/{trainingProposal}/remove-kitas', [TrainingProposalsController::class, 'removeKitas'])->name('remove_kitas');
        Route::delete('/{trainingProposal}', [TrainingProposalsController::class, 'destroy'])->name('destroy');
    });

    /*
     * Other routes
     */
    Route::group(['as' => 'other.'], function () {
        Route::get('/imprint-and-support', [ImprintAndSupportController::class, 'index'])->name('imprint_and_support_index');
    });
});
