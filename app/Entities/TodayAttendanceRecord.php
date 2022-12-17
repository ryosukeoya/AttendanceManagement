<?php

namespace App\Entities;

use Carbon\Carbon;

class TodayAttendanceRecord
{
    private Carbon $starTime;

    private Carbon $endTime;

    public function __construct(Carbon $starTime, Carbon $endTime)
    {
        if ($endTime <= $starTime) {
            $user = \Auth::user();
            \Log::error('ABNORMAL_RECORD : 終了時刻が開始時刻以前の値です', [
                'user_id' => $user->id,
            ]);
            throw new \RecordException('終了時刻が開始時刻以前の値です');
        }

        $this->starTime = $starTime;
        $this->endTime = $endTime;
    }

    public function getStartTime(): Carbon
    {
        return $this->starTime;
    }

    public function getEndTime(): Carbon
    {
        return $this->endTime;
    }
}
