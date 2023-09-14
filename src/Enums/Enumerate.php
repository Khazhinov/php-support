<?php

namespace Khazhinov\PhpSupport\Enums;

use BackedEnum;
use RuntimeException;

class Enumerate
{
    /**
     * Функция возвращает список значений переданного Enum.
     * Если Enum не имеет значений, будет возвращена ошибка.
     *
     * @param  class-string  $enum_class
     * @return array<mixed>
     */
    public static function getValues(string $enum_class): array
    {
        if (! is_a($enum_class, BackedEnum::class, true)) {
            throw new RuntimeException(sprintf("Undefined enum class or enum has no values: %s", $enum_class));
        }

        $enum_cases = $enum_class::cases();
        $result = [];
        foreach ($enum_cases as $case) {
            $result[] = $case->value;
        }

        return $result;
    }
}
