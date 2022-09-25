<?php

declare(strict_types=1);

if (! function_exists('helper_array_is_assoc')) {
    /**
     * Определяет, является ли массив ассоциативным.
     *
     * Массив является «ассоциативным», если он не имеет последовательных числовых ключей, начинающихся с нуля.
     *
     * @param  array<mixed>  $array
     * @return bool
     */
    function helper_array_is_assoc(array $array): bool
    {
        return \Khazhinov\PhpSupport\Collection\Arr::isAssoc($array);
    }
}

if (! function_exists('helper_array_accessible')) {
    /**
     * Определяет, является ли данное значение массивом.
     *
     * @param  mixed  $value
     * @return bool
     */
    function helper_array_accessible(mixed $value): bool
    {
        return \Khazhinov\PhpSupport\Collection\Arr::accessible($value);
    }
}

if (! function_exists('helper_array_exists')) {
    /**
     * Определяет доступность ключа в заданном массиве.
     *
     * @param  ArrayAccess|array<mixed>  $array
     * @param  string|int  $key
     * @return bool
     */
    function helper_array_exists(ArrayAccess|array $array, string|int $key): bool
    {
        return \Khazhinov\PhpSupport\Collection\Arr::exists($array, $key);
    }
}

if (! function_exists('helper_array_set')) {
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
    function helper_array_set(array &$array, ?string $key, mixed $value): mixed
    {
        return \Khazhinov\PhpSupport\Collection\Arr::set($array, $key, $value);
    }
}

if (! function_exists('helper_array_get')) {
    /**
     * Получает элемент массива. В качестве ключа разрешается использование точечной нотации ключа.
     *
     * @param  ArrayAccess|array<mixed>  $array
     * @param  string|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function helper_array_get(ArrayAccess|array $array, string|int|null $key, mixed $default = null): mixed
    {
        return \Khazhinov\PhpSupport\Collection\Arr::get($array, $key, $default);
    }
}

if (! function_exists('helper_array_merge_recursive_distinct')) {
    /**
     * Функция для последовательного объединения деревьев.
     * Данная функция является пересечением многолетнего опыта её использования и сотен мнений в интернете.
     * Как автор хочу сказать, что это не идеальный по времени/памяти экземпляр.
     * Но точно лучше своих собратьев по качеству объединения глубоких деревьев.
     *
     * @param  array<mixed>  ...$arrays
     * @return array<mixed>
     */
    function helper_array_merge_recursive_distinct(array ...$arrays): array
    {
        return \Khazhinov\PhpSupport\Collection\Arr::mergeRecursiveDistinct($arrays);
    }
}

if (! function_exists('helper_array_unset')) {
    /**
     * Удаляет требуемый элемент массива
     *
     * @param  array<int|string, mixed>  $array
     * @param string|int $index
     * @return array<int|string, mixed>
     */
    function helper_array_unset(array $array, string|int $index): array
    {
        return \Khazhinov\PhpSupport\Collection\Arr::unset($array, $index);
    }
}

if (! function_exists('helper_extract_value')) {
    /**
     * Возвращает значение, даже в случае если переданное значение является функцией.
     *
     * @param  mixed  $value
     * @param  mixed  ...$args
     * @return mixed
     */
    function helper_extract_value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (! function_exists('helper_date_now')) {
    /**
     * Возвращает текущее время.
     *
     * @return DateTime
     */
    function helper_date_now(): DateTime
    {
        return \Khazhinov\PhpSupport\Date\DateTime::now();
    }
}

if (! function_exists('helper_string_upper')) {
    /**
     * Приводит строку к верхнему регистру.
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_upper(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::upper($input);
    }
}

if (! function_exists('helper_string_ucfirst')) {
    /**
     * Возводит первый символ строки в верхний регистр
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_ucfirst(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::ucfirst($input);
    }
}

if (! function_exists('helper_string_lower')) {
    /**
     * Приводит строку к нижнему регистру
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_lower(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::lower($input);
    }
}

if (! function_exists('helper_string_title')) {
    /**
     * Приводит каждую букву каждого слова к верхнему регистру
     * Каждое слово отделено пробелом
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_title(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::title($input);
    }
}

if (! function_exists('helper_string_camel')) {
    /**
     * Приводит строку в CamelCase
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_camel(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::camel($input);
    }
}

if (! function_exists('helper_string_snake')) {
    /**
     * Приводит строку в snake_case
     *
     * @param  string  $input
     * @return string
     */
    function helper_string_snake(string $input): string
    {
        return \Khazhinov\PhpSupport\String\Str::snake($input);
    }
}

if (! function_exists('helper_string_singular')) {
    /**
     * Переданное английское слово в единственное число
     *
     * @param  string  $input
     * @return string|bool
     */
    function helper_string_singular(string $input): string|bool
    {
        return \Khazhinov\PhpSupport\String\Str::singular($input);
    }
}

if (! function_exists('helper_string_plural')) {
    /**
     * Переданное английское слово во множественное число
     *
     * @param  string  $input
     * @return string|bool
     */
    function helper_string_plural(string $input): string|bool
    {
        return \Khazhinov\PhpSupport\String\Str::plural($input);
    }
}

if (! function_exists('helper_string_substr')) {
    /**
     * Отрезает заданную строку по заданной координате начала и требуемой длине
     *
     * @param  string|null  $input
     * @param  int  $start
     * @param  int|null  $length
     * @return string
     */
    function helper_string_substr(string|null $input, int $start, int $length = null): string
    {
        return \Khazhinov\PhpSupport\String\Str::substr($input, $start, $length);
    }
}
