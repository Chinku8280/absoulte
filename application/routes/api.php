<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\ClaimController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PayslipController;
use App\Http\Controllers\API\ProfileController;
use Google\Service\Adsense\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('/sendOtp', [PasswordResetController::class, 'sendOtp'])->name('sendOtp');
Route::post('/verifyOtp', [PasswordResetController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/updatePassword', [PasswordResetController::class, 'updatePassword'])->name('updatePassword');


Route::middleware('auth:sanctum')->group(function () {

    // get player id for onesignal notification
    Route::post('/get-player-id', [AuthController::class, 'get_player_id']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // get all onesignal notification
    Route::get('/get-all-notification', [NotificationController::class, 'get_all_notification']);

    Route::controller(HomeController::class)->group(function () {
        Route::get('/get-all-count', 'get_all_count');
    });

    Route::controller(JobController::class)->group(function () {
        Route::get('/pending-job', 'pending_job');
        Route::get('/completed-job', 'completed_job');
        Route::get('/weeklytask', 'weeklytask');
        Route::get('/view-directions', 'view_directions');
        Route::get('/view-job-details', 'view_job_details');
        Route::post('/start-task', 'start_task');
        Route::get('/get-start-task-time', 'get_start_task_time');
        Route::post('/end-task', 'end_task');
        Route::post('/complete-survey', 'complete_survey');
        Route::post('/complete-payment', 'complete_payment');
        Route::get('/get-payment-info', 'get_payment_info');
    });

    // hrms

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/get-profile', 'get_profile');
        Route::post('/update-profile', 'update_profile');
    });

    Route::controller(PasswordResetController::class)->group(function () {
        Route::post('/change-password', 'change_password');
    });

    Route::controller(AttendanceController::class)->group(function () {
        Route::post('/clock-in', 'clock_in');
        Route::post('/clock-out', 'clock_out');
        Route::get('/check-clock-in-out-status', 'check_clock_in_out_status');

        Route::get('/monthly-attendance', 'monthly_attendance');
        Route::get('/daily-attendance', 'daily_attendance');

        Route::get('/check-in-timesheet-details', 'check_in_timesheet_details');
        Route::get('/check-out-timesheet-details', 'check_out_timesheet_details');
    });

    Route::controller(LeaveController::class)->group(function () {
        Route::get('/leave-list', 'leave_list');
        Route::get('/leave-type', 'leave_type');
        Route::post('/apply-leave', 'apply_leave');
    });

    Route::controller(ClaimController::class)->group(function () {
        Route::get('/claim-list', 'claim_list');
        Route::get('/claim-type-list', 'claim_type_list');
        Route::post('/claim-application', 'claim_application');
    });

    Route::controller(PayslipController::class)->group(function () {
        Route::get('/monthly-payslip-list', 'monthly_payslip_list');
        Route::get('/download-payslip-pdf', 'download_payslip_pdf');
        // Route::get('/payslip-details', 'payslip_details');
    });

    Route::controller(CalendarController::class)->group(function () {
        Route::get('/get-calendar-data', 'get_calendar_data');
        Route::get('/get-calendar-data-details', 'get_calendar_data_details');
    });

    // pradip start
    // Route::get('/weeklytask', [JobController::class, 'weeklytask'])->name('weeklytask');
    // Route::get('/getmonthlyreview/{user_id}', [HomeController::class, 'getMonthlyReview'])->name('getMonthlyReview');
    // Route::get('/pendingjob/{user_id}', [JobController::class, 'pendingjob'])->name('pendingjob');
    // Route::get('/completedjob/{user_id}', [JobController::class, 'completedjob'])->name('completedjob');
    // Route::post('/job-details', [JobController::class, 'uploadJobDetails'])->name('uploadJobDetails');
    // Route::post('/update-damage-and-remark', [JobController::class, 'updateDamageAndRemark'])->name('updateDamageAndRemark');
    // Route::post('/reviews', [JobController::class, 'reviewAndRatings'])->name('reviewAndRatings');
    // Route::get('/getJobdetails', [JobController::class, 'getJobdetails'])->name('getJobdetails');
    // pradip end

});
