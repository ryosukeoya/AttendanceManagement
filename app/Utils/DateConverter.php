<?php

namespace App\Services;

use Carbon\Carbon;

class DateConverter
{
    public static function getTodayString(Carbon $date, string $format = 'YYYY-MM-DD'): string
    {
        $todayDateString = null;

        switch ($format) {
            case 'YYYY-MM-DD':
                $todayDateString = $date->toDateString();
                break;
        }

        if (is_null($todayDateString)) {
            throw new \Exception();
        }

        return $todayDateString;
    }
}
