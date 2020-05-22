<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/9
 * Time: 14:16
 */

namespace core\wen\exception;


use core\wen\output\Output;

class ParseException extends \Exception
{


    public function __construct($_message, $_file, $_line, $_type)
    {
        $this->message = $_message;
        $this->file = $_file;
        $this->line = $_line;
        $this->code = $_type;
    }

    public static function throwException()
    {
        $error_msg = error_get_last();
        if ($error_msg) {
            $e = new self($error_msg['message'], $error_msg['file'], $error_msg['line'], $error_msg['type']);
            Output::throwException($e);
        }
    }

    public static function errorHandle($errno, $errMsg, $errFile, $errLine)
    {
        $e = new self($errMsg, $errFile, $errLine, $errno);
        Output::throwException($e);
    }

}