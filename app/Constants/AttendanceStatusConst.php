<?php

namespace App\Constants;

class AttendanceStatusConst
{
    const LIST = [
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
}
