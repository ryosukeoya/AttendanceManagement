<?php

namespace App\Http\Controllers\Api;

use App\AttendanceStatusFactory;
use App\Http\Controllers\Controller;
use App\Queries\AttendanceRecordQuery;
use App\Models\User;

class AttendanceRecordApiController extends Controller
{
    public function getAttendanceStatusOfJson()
    {
        $attendanceStatus = AttendanceStatusFactory::create();
        $statusNumber = $attendanceStatus->getStatusNumber();

        return response()->json(['attendanceStatus' => $statusNumber]);
    }

    public function getCalendarResourcesOfJson()
    {
        $user = User::find(\Auth::id());

        $allAttendanceRecords = AttendanceRecordQuery::getAllAttendanceRecords($user);

        return response()->json(['calendarResources' => $allAttendanceRecords]);
    }
}
