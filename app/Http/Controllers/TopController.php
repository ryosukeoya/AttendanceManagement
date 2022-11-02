<?php

namespace App\Http\Controllers;

use App\Services\AttendanceRecordService;
use App\Models\User;

class TopController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function getAttendanceStatusOfJson()
    {
        $user = User::find(\Auth::id());

        $attendanceRecordService = new AttendanceRecordService();
        $attendanceStatus = $attendanceRecordService->getAttendanceStatus($user);

        return response()->json(['attendanceStatus' => $attendanceStatus]);
    }
}
