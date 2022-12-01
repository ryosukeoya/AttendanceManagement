<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordService
{
    private const ATTENDANCE_STATUSES = [
        'UNREGISTERED' => [
            'MSG' => '未登録',
            'STATUS' => 0,
        ],
        'STARTED' => [
            'MSG' => '始業済み',
            'STATUS' => 1,
        ],
        'ENDED' => [
            'MSG' => '終業済み',
            'STATUS' => 2,
        ],
        'ILLEGAL' => [
            'MSG' => '不正登録',
            'STATUS' => 3,
        ],
    ];

    final public static function canStartRegister(int $attendanceStatus): bool
    {
        return $attendanceStatus == self::ATTENDANCE_STATUSES['UNREGISTERED']['STATUS'];
    }

    final public static function canEndRegister(int $attendanceStatus): bool
    {
        return $attendanceStatus == self::ATTENDANCE_STATUSES['STARTED']['STATUS'];
    }

    final public function getAttendanceStatus(User $user): int
    {
        $todayStartedRecordCounts = $this->getTodayStartedRecordCounts($user);
        $todayEndedRecordCounts = $this->getTodayEndedRecordCounts($user);

        $attendanceStatus = $this->assignAttendanceStatus($todayStartedRecordCounts, $todayEndedRecordCounts);
        return $attendanceStatus;
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

    private static function assignAttendanceStatus(int $todayStartedRecordCounts, int $todayEndedRecordCounts): int
    {
        try {
            if ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts == 0) {
                return self::ATTENDANCE_STATUSES['UNREGISTERED']['STATUS'];
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts == 0) {
                return self::ATTENDANCE_STATUSES['STARTED']['STATUS'];
            } elseif ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts >= 1) {
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUSES['ENDED']['STATUS'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ' . $e->getMessage());
            return self::ATTENDANCE_STATUSES['ILLEGAL']['STATUS'];
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
            \Log::error('STARTED_RECORD : ' . $e->getMessage(), [
                'user_id' => $user->id,
                'record_counts' => $todayStartedRecordCounts,
            ]);
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
            \Log::error('ENDED_RECORD : ' . $e->getMessage(), [
                'user_id' => $user->id,
                'record_counts' => $todayEndedRecordCounts,
            ]);
            return $todayEndedRecordCounts;
        }

        return $todayEndedRecordCounts;
    }
}
