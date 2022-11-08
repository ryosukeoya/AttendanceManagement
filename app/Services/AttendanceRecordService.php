<?php

namespace App\Services;

use App\Models\User;

class AttendanceRecordService
{
    // TODO Refactor key,value
    private const ATTENDANCE_STATUS = [
        '未登録' => 0,
        '始業済み' => 1,
        '終業済み' => 2,
        '不正登録' => 3,
    ];

    final public static function canStartRegister(int $attendanceStatus)
    {
        return $attendanceStatus == self::ATTENDANCE_STATUS['未登録'];
    }

    final public static function canEndRegister(int $attendanceStatus)
    {
        return $attendanceStatus == self::ATTENDANCE_STATUS['終業済み'];
    }

    final public function getAttendanceStatus(User $user): int
    {
        $todayStartedRecordCounts = $this->getTodayStartedRecordCounts($user);
        $todayEndedRecordCounts = $this->getTodayEndedRecordCounts($user);

        $attendanceStatus = $this->assignAttendanceStatus($todayStartedRecordCounts, $todayEndedRecordCounts);
        return $attendanceStatus;
    }

    final public static function getTodayStartedRecord(User $user): mixed
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

    private static function assignAttendanceStatus(int $todayStartedRecordCounts, int $todayEndedRecordCounts): int
    {
        try {
            if ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts == 0) {
                return self::ATTENDANCE_STATUS['未登録'];
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts == 0) {
                return self::ATTENDANCE_STATUS['始業済み'];
            } elseif ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts >= 1) {
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUS['終業済み'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ', $e->getMessage());
            return self::ATTENDANCE_STATUS['不正登録'];
        }
    }

    private static function getTodayStartedRecordCounts(User $user): int
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
            // debug情報ID
            \Log::error('STARTED_RECORD  : ', $e->getMessage());
            return $todayStartedRecordCounts;
        }

        return $todayStartedRecordCounts;
    }

    private static function getTodayEndedRecordCounts(User $user): int
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
            \Log::error('ENDED_RECORD : ', $e->getMessage());
            return $todayEndedRecordCounts;
        }

        return $todayEndedRecordCounts;
    }
}
