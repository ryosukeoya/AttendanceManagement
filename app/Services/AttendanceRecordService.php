<?php

namespace App\Services;

use App\Models\User;

class AttendanceRecordService
{
    private const ATTENDANCE_STATUS1 = [
        'key' => '未登録',
        'value' => 0,
    ];
    private const ATTENDANCE_STATUS2 = [
        'key' => '始業済み',
        'value' => 1,
    ];
    private const ATTENDANCE_STATUS3 = [
        'key' => '終業済み',
        'value' => 2,
    ];
    private const ATTENDANCE_STATUS4 = [
        'key' => '不正登録',
        'value' => 3,
    ];

    final public static function canStartRegister(int $attendanceStatus): bool
    {
        return $attendanceStatus == self::ATTENDANCE_STATUS1['value'];
    }

    final public static function canEndRegister(int $attendanceStatus): bool
    {
        return $attendanceStatus == self::ATTENDANCE_STATUS2['value'];
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
                return self::ATTENDANCE_STATUS1['value'];
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts == 0) {
                return self::ATTENDANCE_STATUS2['value'];
            } elseif ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts >= 1) {
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUS3['value'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ', $e->getMessage());
            return self::ATTENDANCE_STATUS4['value'];
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
