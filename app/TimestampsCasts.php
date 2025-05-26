<?php

namespace App;

use App\Casts\TimestampTimezone;

trait TimestampsCasts
{
    public function initializeTimestampsCasts()
    {
        if (method_exists($this, 'getCreatedAtColumn')) {
            if (!isset($this->casts[$this->getCreatedAtColumn()])) {
                $this->casts[$this->getCreatedAtColumn()] = TimestampTimezone::class;
            }
        }

        if (method_exists($this, 'getUpdatedAtColumn')) {
            if (!isset($this->casts[$this->getUpdatedAtColumn()])) {
                $this->casts[$this->getUpdatedAtColumn()] = TimestampTimezone::class;
            }
        }

        if (method_exists($this, 'getDeletedAtColumn')) {
            if (!isset($this->casts[$this->getDeletedAtColumn()])) {
                $this->casts[$this->getDeletedAtColumn()] = TimestampTimezone::class;
            }
        }
    }
}
