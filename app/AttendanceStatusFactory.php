<?php

namespace App;

use App\Entities\AttendanceStatus;
use App\Queries\AttendanceRecordQuery;

class AttendanceStatusFactory
{
    private function __construct()
    {
    }

    final public static function create(): AttendanceStatus
    {
        $user = \Auth::user();

        $todayStartedRecordCounts = AttendanceRecordQuery::getTodayStartedRecordCounts($user);
        $todayEndedRecordCounts = AttendanceRecordQuery::getTodayEndedRecordCounts($user);

        $attendanceStatus = new AttendanceStatus($todayStartedRecordCounts, $todayEndedRecordCounts);

        return $attendanceStatus;
    }
}
