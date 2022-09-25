<?php

declare(strict_types=1);

namespace Khazhinov\PhpSupport\Collection;

use ArrayAccess;

class Arr
{
    /**
     * Определяет, является ли массив ассоциативным.
     *
     * Массив является «ассоциативным», если он не имеет последовательных числовых ключей, начинающихся с нуля.
     *
     * @param  array<mixed>  $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Определяет, является ли данное значение массивом.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible(mixed $value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Определяет доступность ключа в заданном массиве.
     *
     * @param  ArrayAccess|array<mixed>  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists(ArrayAccess|array $array, string|int $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Устанавливает элемент массива. В качестве ключа разрешается использование точечной нотации ключа.
     *
     * Если ключ не указан (равен null), то массив будем заменён на указанное значение.
     *
     * @param  array<mixed>  $array
     * @param  string|null  $key
     * @param  mixed  $value
     * @return array<mixed>|mixed
     */
    public static function set(array &$array, ?string $key, mixed $value): mixed
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key_value) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key_value]) || ! is_array($array[$key_value])) {
                $array[$key_value] = [];
            }

            $array = &$array[$key_value];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Получает элемент массива. В качестве ключа разрешается использование точечной нотации ключа.
     *
     * @param  ArrayAccess|array<mixed>  $array
     * @param  string|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(ArrayAccess|array $array, string|int|null $key, mixed $default = null): mixed
    {
        if (! static::accessible($array)) {
            return helper_extract_value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        /** @var string $key */
        if (! str_contains($key, '.')) {
            return $array[$key] ?? helper_extract_value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return helper_extract_value($default);
            }
        }

        return $array;
    }

    /**
     * Функция для последовательного объединения деревьев.
     * Данная функция является пересечением многолетнего опыта её использования и сотен мнений в интернете.
     * Как автор хочу сказать, что это не идеальный по времени/памяти экземпляр.
     * Но точно лучше своих собратьев по качеству объединения глубоких деревьев.
     *
     * @param  array<mixed>  ...$arrays
     * @return array<mixed>
     */
    public static function mergeRecursiveDistinct(array ...$arrays): array
    {
        $base = array_shift($arrays);
        if (! is_array($base)) {
            $base = [$base];
        }
        foreach ($arrays as $append) {
            if (! is_array($append)) {
                $append = [$append];
            }
            foreach ($append as $key => $value) {
                if (! array_key_exists($key, $base) && ! is_numeric($key)) {
                    $base[$key] = $value;

                    continue;
                }

                if ((isset($base[$key]) && is_array($base[$key])) || is_array($value)) {
                    $base[$key] = helper_array_merge_recursive_distinct($base[$key], $value);
                } else {
                    if (is_numeric($key)) {
                        if (! in_array($value, $base, true)) {
                            $base[] = $value;
                        }
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
        }

        return $base;
    }

    /**
     * Удаляет требуемый элемент массива
     *
     * @param  array<int|string, mixed>  $array
     * @param string|int $index
     * @return array<int|string, mixed>
     */
    public static function unset(array $array, string|int $index): array
    {
        unset($array[$index]);
        $tmp_array = [];
        foreach ($array as $item) {
            $tmp_array[] = $item;
        }

        return $tmp_array;
    }
}
