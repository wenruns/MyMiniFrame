<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/9
 * Time: 11:04
 */

namespace core\wen\output;


class File
{
    public static function getContent($file, $line_s = 0, $line_e = -1)
    {
        if (!file_exists($file)) {
            return false;
        }
        $content = file_get_contents($file);
        $start = self::getStringAppearLocationForN($content, "\r\n", $line_s);
        if ($line_e > 0) {
            $end = self::getStringAppearLocationForN($content, "\r\n", $line_e);
            if ($end) {
                $content = substr($content, $start, $end - $start);
            } else {
                $content = substr($content, $start);
            }
        } else {
            $content = substr($content, $start);
        }
        return $content;
    }


    public static function getStringAppearLocationForN($string, $subString, $times)
    {
        $pos = 0;
        for ($i = 0; $i < $times; $i++) {
            $pos = strpos($string, $subString, $pos + 1);
            if (!$pos) {
                return false;
            }
        }
        return $pos;
    }
}