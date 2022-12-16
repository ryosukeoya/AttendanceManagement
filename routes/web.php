<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\Api\AttendanceRecordApiController;
use App\Http\Controllers\AttendanceRecordController;

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

Route::middleware(['auth'])->group(function () {
    // TOP
    Route::get('/', [TopController::class, 'index'])->name('home');

    // Attendance Record
    Route::resource('attendance_record', AttendanceRecordController::class)->only([
        'index',
        'edit',
        'store',
        'destroy',
    ]);
    Route::get('attendance_record/start', [AttendanceRecordController::class, 'start'])->name(
        'attendance_record.start'
    );
    Route::get('attendance_record/end', [AttendanceRecordController::class, 'end'])->name('attendance_record.end');
    Route::patch('attendance_record/update', [AttendanceRecordController::class, 'update'])->name(
        'attendance_record.update'
    );

    // API
    Route::get('api_attendance_record/me/today_status', [
        AttendanceRecordApiController::class,
        'getAttendanceStatusOfJson',
    ])->name('api_attendance_record.me.today_status');
    Route::get('api_attendance_record/me/all', [
        AttendanceRecordApiController::class,
        'getCalendarResourcesOfJson',
    ])->name('api_calendar.me.all');
});

require __DIR__ . '/auth.php';
