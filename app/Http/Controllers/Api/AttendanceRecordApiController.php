<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AttendanceRecordService;
use App\Models\User;

class AttendanceRecordApiController extends Controller
{
    public function getAttendanceStatusOfJson()
    {
        $user = User::find(\Auth::id());

        $attendanceRecordService = new AttendanceRecordService();
        $attendanceStatus = $attendanceRecordService->getAttendanceStatus($user);

        return response()->json(['attendanceStatus' => $attendanceStatus]);
    }

    public function getCalendarResourcesOfJson()
    {
        $user = User::find(\Auth::id());

        $attendanceRecordService = new AttendanceRecordService();
        $allAttendanceRecords = $attendanceRecordService->getAllAttendanceRecords($user);

        return response()->json(['calendarResources' => $allAttendanceRecords]);
    }
}
