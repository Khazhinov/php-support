<?php

declare(strict_types = 1);

namespace Khazhinov\PhpSupport\Date;

use DateTime as BaseDateTime;

class DateTime
{
    /**
     * Возвращает текущее время.
     *
     * @return BaseDateTime
     */
    public static function now(): BaseDateTime
    {
        return new BaseDateTime(datetime: 'now');
    }
}
