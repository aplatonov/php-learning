<?php

namespace App\Arrays;

class Solution
{
    /**
     * Найти сумму всех положительных элементов массива
     *
     * @param  array $array
     * @return array
     */
    public static function sumOfPositive($array)
    {
        if (empty(array_filter($array)))
            return 0;

        return array_sum(array_filter($array, function ($var) {
            return $var > 0 ? $var : 0;
        }));
    }

    /**
     * Преобразовать массив из нулей и единиц в эквивалетное ему десятичное число
     *
     * @param  array $array
     * @return array
     */
    public static function binaryArrayToNumber($array)
    {
        return bindec(implode('', $array));
    }

    /**
     * Удалить все вхождения минимального и максимального значения массива
     *
     * @param  array $array
     * @return array
     */
    public static function deleteMinMax($array)
    {
        $max = max($array);
        $min = min($array);
        $result = [];

        foreach ($array as $element) {
            if ($element != $max && $element != $min)
                $result[] =  $element;
        }

        return $result;
    }
}
