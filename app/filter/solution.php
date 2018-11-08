<?php

namespace App\Filter;

/**
 * Class Solution
 * @package App\Filter
 *
 * Все функции должны возвращать массив id вакансий
 * @see \App\Filter\Solution::getIds
 */
class Solution
{
    /**
     * Метод должен возвращать только активные вакансии
     *
     * @param  array $vacancies
     * @return array
     */
    public static function filterActive($vacancies)
    {
        return self::getIds(
            array_filter($vacancies, function($v) {
                return $v['isActive'] =='Y';
            })
        );
    }

    /**
     * Метод должен возвращать только активные вакансии
     * с рейтингом большим, чем $rating
     *
     * @param  array $vacancies
     * @param  float $rating
     * @return array
     */
    public static function compareRatings($vacancies, $rating)
    {
        return self::getIds(
            array_filter($vacancies, function($v) use ($rating) {
                return $v['isActive'] =='Y' && (float)$v['rating'] > $rating;
            })
        );
    }

    /**
     * Метод должен возвращать вакансии, в названиях которых
     * содержится строка $str
     *
     * @param  array $vacancies
     * @param  string $str
     * @return array
     */
    public static function containsString($vacancies, $str)
    {
        return self::getIds(
            array_filter($vacancies, function($v) use ($str) {
                return stripos($v['name'], $str) !== false;
            })
        );
    }

    /**
     * @param  array $vacancies
     * @return array
     */
    protected static function getIds($vacancies)
    {
        $result = [];
        foreach ($vacancies as $v) {
            $result[] = $v["id"];
        }
        return $result;
    }
}
