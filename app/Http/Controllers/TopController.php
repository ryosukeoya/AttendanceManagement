<?php

namespace App\Http\Controllers;

use App\Services\AttendanceRecordService;
use App\Models\User;
use Carbon\Carbon;

class TopController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function getAttendanceStatusOfJson()
    {
        $date = new Carbon();
        $user = User::find(\Auth::id());

        $attendanceRecordService = new AttendanceRecordService();
        $attendanceStatus = $attendanceRecordService->getAttendanceStatus($user, $date);

        return response()->json(['attendanceStatus' => $attendanceStatus]);
    }
}
