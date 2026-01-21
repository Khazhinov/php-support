<?php

declare(strict_types = 1);

namespace Khazhinov\PhpSupport\String;

use Symfony\Component\String\Inflector\EnglishInflector;

use function Symfony\Component\String\u;

class Str
{
    /**
     * Приводит строку к верхнему регистру
     *
     * @param  string  $input
     * @return string
     */
    public static function upper(string $input): string
    {
        return u($input)->upper()->toString();
    }

    /**
     * Возводит первый символ строки в верхний регистр
     *
     * @param  string  $input
     * @return string
     */
    public static function ucfirst(string $input): string
    {
        return static::upper(static::substr($input, 0, 1)).static::substr($input, 1);
    }

    /**
     * Приводит строку к нижнему регистру
     *
     * @param  string  $input
     * @return string
     */
    public static function lower(string $input): string
    {
        return u($input)->lower()->toString();
    }

    /**
     * Приводит каждую букву каждого слова к верхнему регистру
     * Каждое слово отделено пробелом
     *
     * @param  string  $input
     * @return string
     */
    public static function title(string $input): string
    {
        return u($input)->title()->toString();
    }

    /**
     * Приводит строку в CamelCase
     *
     * @param  string  $input
     * @return string
     */
    public static function camel(string $input): string
    {
        return u($input)->camel()->toString();
    }

    /**
     * Приводит строку в snake_case
     *
     * @param  string  $input
     * @return string
     */
    public static function snake(string $input): string
    {
        return u($input)->snake()->toString();
    }

    /**
     * Переданное английское слово в единственное число
     *
     * @param  string  $input
     * @return string|bool
     */
    public static function singular(string $input): string|bool
    {
        $inflector = new EnglishInflector();

        $result = $inflector->singularize(u($input)->toString());

        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * Переданное английское слово во множественное число
     *
     * @param  string  $input
     * @return string|bool
     */
    public static function plural(string $input): string|bool
    {
        $inflector = new EnglishInflector();

        $result = $inflector->pluralize(u($input)->toString());

        if (count($result) > 0) {
            return $result[0];
        }

        return false;
    }

    /**
     * Отрезает заданную строку по заданной координате начала и требуемой длине
     *
     * @param  string|null  $input
     * @param  int  $start
     * @param  int|null  $length
     * @return string
     */
    public static function substr(string|null $input, int $start, ?int $length = null): string
    {
        return mb_substr(u($input)->toString(), $start, $length, 'UTF-8');
    }
}
