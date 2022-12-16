<?php

namespace App\Entities;

use App\Entities\Abstracts\AStatus;

class AttendanceStatus extends AStatus
{
    private const STATUS_LIST = [
        'UNREGISTERED' => [
            'NUMBER' => 0,
            'MSG' => '未登録',
        ],
        'STARTED' => [
            'NUMBER' => 1,
            'MSG' => '始業済み',
        ],
        'ENDED' => [
            'NUMBER' => 2,
            'MSG' => '終業済み',
        ],
        'ILLEGAL' => [
            'NUMBER' => 3,
            'MSG' => '不正登録',
        ],
    ];

    private array|null $CURRENT_STATUS = null;

    public function __construct(int $todayStartedRecordCounts, int $todayEndedRecordCounts)
    {
        try {
            if ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts == 0) {
                $this->CURRENT_STATUS = self::STATUS_LIST['UNREGISTERED'];
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts == 0) {
                $this->CURRENT_STATUS = self::STATUS_LIST['STARTED'];
            } elseif ($todayStartedRecordCounts == 0 && $todayEndedRecordCounts >= 1) {
                /** ※日付跨ぎの勤務で不正登録になる */
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($todayStartedRecordCounts >= 1 && $todayEndedRecordCounts >= 1) {
                $this->CURRENT_STATUS = self::STATUS_LIST['ENDED'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ' . $e->getMessage());
            $this->CURRENT_STATUS = self::STATUS_LIST['ILLEGAL'];
        }
    }

    final public function canRegisterToStartWork(): bool
    {
        return $this->CURRENT_STATUS['NUMBER'] == self::STATUS_LIST['UNREGISTERED']['NUMBER'];
    }

    final public function canRegisterForEndOfWork(): bool
    {
        return $this->CURRENT_STATUS['NUMBER'] == self::STATUS_LIST['STARTED']['NUMBER'];
    }

    final public function getStatus(): array
    {
        return $this->CURRENT_STATUS;
    }

    final public function getStatusNumber(): int
    {
        return $this->CURRENT_STATUS['NUMBER'];
    }

    final public function getStatusMsg(): string
    {
        return $this->CURRENT_STATUS['MSG'];
    }
}
