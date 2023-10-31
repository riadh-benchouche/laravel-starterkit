<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    public static function trimContent($description, $limit = 100, $break = ' ')
    {
        $intro = $description;

        preg_match("/<\w.*?>(\w.*?)<\/\w.*?>/", $intro, $matches);

        if (isset($matches[1])) {
            $intro = trim(strip_tags($matches[1]));
        }

        //get short text
        $intro = self::truncateMessage($intro, $limit, $break);

        return html_entity_decode($intro, ENT_QUOTES | ENT_HTML5);
    }

    public static function truncateMessage($string, $limit=150, $break=".", $pad="...")
    {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit) {
            return $string;
        }

        // is $break present between $limit and the end of the string?
        if((false !== ($breakpoint = strpos($string, $break, $limit))) && $breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }

        return $string;
    }

    public static function generateRandomCode($length = 10, $onlyNumbers = false)
    {
        if ($onlyNumbers === true) {
            return Str::substr(Str::uuid()->getInteger(), 0, $length);
        }

        return Str::substr(Str::uuid()->getHex(), 0, $length);
    }

    public static function getModelName($model) {
        $path = explode('\\', $model);
        return array_pop($path);
    }
}
