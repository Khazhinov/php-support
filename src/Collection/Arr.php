<?php

declare(strict_types = 1);

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
     * @param  ArrayAccess<string, mixed>|array<string, mixed>  $array
     * @param  string  $key
     * @return bool
     */
    public static function exists(ArrayAccess|array $array, string $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Устанавливает элемент массива. В качестве ключа разрешается использование точечной нотации ключа.
     *
     * @param  array<mixed>  $array
     * @param  string  $key
     * @param  mixed  $value
     * @return array<mixed>|mixed
     */
    public static function set(array &$array, string $key, mixed $value): mixed
    {
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
     * @param  ArrayAccess<string, mixed>|array<string, mixed>  $array
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(ArrayAccess|array $array, string $key, mixed $default = null): mixed
    {
        if (! static::accessible($array)) {
            return helper_extract_value($default);
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (! str_contains($key, '.')) {
            return $array[$key] ?? helper_extract_value($default);
        }

        foreach (explode('.', $key) as $segment) {
            /** @var ArrayAccess<string, mixed>|array<string, mixed> $array */
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
            if (! is_array($append)) { // @phpstan-ignore-line
                $append = [$append];
            }
            foreach ($append as $key => $value) {
                if (! array_key_exists($key, $base) && ! is_numeric($key)) {
                    $base[$key] = $value;

                    continue;
                }

                if ((isset($base[$key]) && is_array($base[$key])) && is_array($value)) {
                    /** @var array<mixed> $extracted */
                    $extracted = $base[$key];
                    /** @var array<mixed> $value */
                    $base[$key] = self::mergeRecursiveDistinct($extracted, $value);
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

    /**
     * Сортирует указанный массив и по ключу, и по значению
     *
     * @param  array<mixed>  $array
     * @return void
     */
    public static function recursiveSort(array &$array): void
    {
        if (self::isAssoc($array)) {
            ksort($array);

            foreach ($array as $key => &$subarray) {
                if (is_array($subarray)) {
                    self::recursiveSort($subarray);
                }
            }
        } else {
            sort($array);

            foreach ($array as &$subarray) {
                if (is_array($subarray)) {
                    self::recursiveSort($subarray);
                }
            }
        }
    }
}
