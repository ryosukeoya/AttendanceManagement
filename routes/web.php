<?php

use Illuminate\Support\Facades\Route;
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
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::resource('attendance_record', AttendanceRecordController::class)->only([
        'index',
        'create',
        'edit',
        'store',
        'update',
        'destroy',
    ]);
});

require __DIR__ . '/auth.php';
