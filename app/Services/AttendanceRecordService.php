<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRecordService
{
    final public static function canRegisterToStartWork(int $attendanceStatus): bool
    {
        return $attendanceStatus == \AttendanceStatusConst::LIST['UNREGISTERED']['STATUS'];
    }

    final public static function canRegisterForEndOfWork(int $attendanceStatus): bool
    {
        return $attendanceStatus == \AttendanceStatusConst::LIST['STARTED']['STATUS'];
    }

    final public function getAllAttendanceRecords(User $user): Collection
    {
        $allAttendanceRecords = $user->attendanceRecords()->get();

        return $allAttendanceRecords;
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
                return \AttendanceStatusConst::LIST['UNREGISTERED']['STATUS'];
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts == 0) {
                return \AttendanceStatusConst::LIST['STARTED']['STATUS'];
            } elseif ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts >= 1) {
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts >= 1) {
                return \AttendanceStatusConst::LIST['ENDED']['STATUS'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ' . $e->getMessage());
            return \AttendanceStatusConst::LIST['ILLEGAL']['STATUS'];
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
