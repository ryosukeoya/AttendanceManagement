<?php
declare(strict_types=1);

use Carbon\Carbon;

function getTodayString(string $format = 'YYYY-MM-DD'): string
{
    $todayDateString = null;
    $date = new Carbon();

    switch ($format) {
        case 'YYYY-MM-DD':
            $todayDateString = $date->toDateString();
            break;
        default:
            throw new \DateHelperException('指定のフォーマットは存在しません');
    }

    return $todayDateString;
}
