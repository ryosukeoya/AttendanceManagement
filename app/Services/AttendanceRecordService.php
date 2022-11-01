<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class AttendanceRecordService
{
    private const ATTENDANCE_STATUS = [
        '始業済み' => 1,
        '終業済み' => 2,
    ];

    final public function getAttendanceStatus(User $user, Carbon $date): int
    {
        $startedRecordCounts = $this->getStartedRecordCounts($user, $date);
        $endedRecordCounts = $this->getEndedRecordCounts($user, $date);

        try {
            if ($startedRecordCounts >= 1 && $endedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUS['終業済み'];
            } elseif ($startedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUS['始業済み'];
            } elseif ($endedRecordCounts >= 1) {
                throw new \RecordException();
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ', $e);
        }
    }

    final private function getStartedRecordCounts(User $user, Carbon $date): int
    {
        $todayDateString = \DateConverter::getTodayString($date, 'YYYY-MM-DD');
        $todaysDateRegex = '%' . $todayDateString . '%';

        $startedRecordCounts = $user
            ->attendanceRecords()
            ->where('start_time', 'like', $todaysDateRegex)
            ->count();

        try {
            if ($startedRecordCounts > 1) {
                throw new \RecordException('today started record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('STARTED_RECORD : ', $e->getMessage());
        }

        return $startedRecordCounts;
    }

    final private function getEndedRecordCounts(User $user, Carbon $date): int
    {
        $todayDateString = \DateConverter::getTodayString($date, 'YYYY-MM-DD');
        $todaysDateRegex = '%' . $todayDateString . '%';

        $endedRecordCounts = $user
            ->attendanceRecords()
            ->where('end_time', 'like', $todaysDateRegex)
            ->count();

        try {
            if ($endedRecordCounts > 1) {
                throw new \RecordException('today ended record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('ENDED_RECORD : ', $e->getMessage());
        }

        return $endedRecordCounts;
    }
}
