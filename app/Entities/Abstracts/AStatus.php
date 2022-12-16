<?php

namespace App\Entities\Abstracts;

abstract class AStatus
{
    abstract public function getStatus(): array;

    abstract public function getStatusNumber(): int;

    abstract public function getStatusMsg(): string;
}
