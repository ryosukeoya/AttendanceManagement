<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Collection;
use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordQuery
{
    final public static function getAllAttendanceRecords(User $user): Collection
    {
        $allAttendanceRecords = $user->attendanceRecords()->get();

        return $allAttendanceRecords;
    }

    final public static function getTodayStartedRecord(User $user): AttendanceRecord
    {
        $todayDateString = getTodayString('YYYY-MM-DD');
        $todayDateRegex = '%' . $todayDateString . '%';

        $todayStartedRecord = $user
            ->attendanceRecords()
            ->where('start_time', 'like', $todayDateRegex)
            ->latest()
            ->first();

        return $todayStartedRecord;
    }

    public static function getTodayStartedRecordCounts(User $user): int
    {
        $todayDateString = getTodayString('YYYY-MM-DD');
        $todayDateRegex = '%' . $todayDateString . '%';

        $todayStartedRecordCounts = $user
            ->attendanceRecords()
            ->where('start_time', 'like', $todayDateRegex)
            ->count();

        try {
            if ($todayStartedRecordCounts > 1) {
                throw new \RecordException('today started record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('STARTED_RECORD : ' . $e->getMessage(), [
                'user_id' => $user->id,
                'record_counts' => $todayStartedRecordCounts,
            ]);
            return $todayStartedRecordCounts;
        }

        return $todayStartedRecordCounts;
    }

    public static function getTodayEndedRecordCounts(User $user): int
    {
        $todayDateString = getTodayString('YYYY-MM-DD');
        $todayDateRegex = '%' . $todayDateString . '%';

        $todayEndedRecordCounts = $user
            ->attendanceRecords()
            ->where('end_time', 'like', $todayDateRegex)
            ->count();

        try {
            if ($todayEndedRecordCounts > 1) {
                throw new \RecordException('today ended record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('ENDED_RECORD : ' . $e->getMessage(), [
                'user_id' => $user->id,
                'record_counts' => $todayEndedRecordCounts,
            ]);
            return $todayEndedRecordCounts;
        }

        return $todayEndedRecordCounts;
    }
}
