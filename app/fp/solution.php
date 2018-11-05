<?php

namespace App\Fp;

class Solution
{
    /**
     * Метод должен возвращать слова, преобразованные к верхнему регистру,
     * длина которых больше $len
     *
     * @param  string $text
     * @param  int $len
     * @return string
     */
    public static function upCaseWordGreaterThanLen($text, $len)
    {
        $resArr = [];

        foreach (explode(' ', $text) as $key => $word) {
            if (strlen($word) > $len)
                $resArr[$key] = strtoupper($word);
        }

        return implode(' ', $resArr);
    }

    /**
     * Метод должен декодировать код Морзе.
     * Код Морзе кодирует каждый символ как последовательность "." и "-".
     * Например, буква N кодируется как "-.", буква M - "--"
     * К словарю символов Морзе можно обращать так: $dictionary[morseChar]
     * В коде Морзе символы разделены пробелами, а слова - тремя пробелами.
     * Также в коде есть специальный символ SOS, который кодируется как "...---..." без пробелов.
     *
     * @param  string $morseCode
     * @param  array $dictionary
     * @return string
     */
    public static function decodeMorse($morseCode, $dictionary)
    {
        $wordsArr = explode('   ', trim($morseCode));
        $resArr = [];

        foreach ($wordsArr as $key => $word) {
            $letterArr = explode(' ', trim($word));
            $realWord = '';
            foreach ($letterArr as $letter) {
                $realWord .= $dictionary[$letter];
            }
            $resArr[$key] = $realWord;
        }

        return implode(' ', $resArr);
    }

    /**
     * Метод должен возвращать анаграммы слова $word
     * Слова являются анаграммами, если содержат одни и те же буквы
     *
     * @param  string $word
     * @param  array $words
     * @return static
     */
    public static function anagrams($word, $words)
    {
        $resArr =[];
        $exampleLettersArr = str_split($word);
        sort($exampleLettersArr);

        foreach ($words as $gword) {
            $wordLettersArr = str_split($gword);
            sort($wordLettersArr);
            if (array_diff_assoc($exampleLettersArr, $wordLettersArr) == [] && count($exampleLettersArr) == count($wordLettersArr))
                $resArr[] = $gword;
        }

        return $resArr;
    }
}