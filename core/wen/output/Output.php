<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/11
 * Time: 9:27
 */

namespace core\wen\output;

use core\wen\App;
use core\wen\View;

class Output
{
    protected static $_output_exception = false;


    public static function header()
    {
        echo '<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        .one-group-data{
            background: #000;
            color: lawngreen;
            padding: 10px;
            margin-top: 10px;
        }
        *{
            padding: 0px;
            margin: 0px;
            list-style: none;
        }
    </style>
</head>
<body>';
    }

    public static function footer()
    {
        echo '</body>
</html>';
    }

    public static function show($var)
    {
        self::header();
        self::showSection($var);
        self::footer();
    }

    protected static function showSection($var)
    {
        foreach ($var as $key => $vo) {
            echo '<section class="one-group-data">';
            self::showDetail($vo);
            echo '</section>';
        }
    }

    protected static function showDetail($var, $flag = 0)
    {
        $pref = '';
        for ($i = 0; $i < $flag + 1; $i++) {
            $pref .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if (is_array($var)) {
            echo '<div class="one-array-data">';
            if (!$flag) {
                echo 'array(' . count($var) . '){';
                if (!empty($var)) {
                    echo '<br/>';
                }
            }
            foreach ($var as $key => $vo) {
                if (is_array($vo)) {
                    if (!empty($vo)) {
                        echo $pref . $key . ' => array(' . count($var[$key]) . '){<br/>';
                        self::showDetail($vo, ++$flag);
                        $flag--;
                        echo $pref . '}<br/>';
                    } else {
                        echo $pref . $key . ' => array(' . count($var[$key]) . '){}<br/>';
                    }
                } else {
                    echo $pref . $key . ' => ';
                    var_dump($vo);
                    echo '<br/>';
                }
            }

        } else {
            echo '<div class="one-string-data">';
            if (is_string($var)) {
                echo ($flag ? $pref : '') . 'string(' . strlen($var) . ')"' . $var . '"';
            } else {
                var_dump($var);
            }
            echo '</div>';
        }
        if (!$flag && is_array($var)) {
            echo '}</div>';
        }
    }


    public static function throwException(\Throwable $e)
    {
        if (self::$_output_exception) {
            return;
        }
        self::$_output_exception = true;
        $n = count($e->getTrace());
        $line = $e->getLine();
        $file = $e->getFile();
        $traces = [
            [
                'id' => $n + 1,
                'class' => 'Exception',
                'file' => str_replace(ROOT_PATH, '...', $file),
                'line' => $line,
            ]
        ];
        $exception_codes = [
            [
                'show' => 'block',
                'id' => $n + 1,
                'file' => $file,
                'content' => self::getContents($file, $line)
            ]
        ];
        foreach ($e->getTrace() as $k => $item) {
            if (!isset($item['class'])) {
                continue;
            }
            $traces[] = [
                'id' => $n,
                'class' => $item['class'],
                'file' => str_replace(ROOT_PATH, '...', $item['file']),
                'line' => $item['line'],
            ];

            $exception_codes[] = [
                'show' => 'none',
                'id' => $n,
                'file' => $item['file'],
                'content' => self::getContents($item['file'], $item['line'])
            ];
            $n--;
        }
        $view = new View();
        $data = [
            'msg' => $e->getMessage(),
            'class' => 'Exception',
            'traces' => $traces,
            'exception_codes' => $exception_codes,
            'env' => [
                'POST' => request_post(),
                'GET' => request_get(),
                'ENVIRONMENT' => env(),
                'SERVER' => server(),
            ],
        ];
        $view->rootPath(CORE_PATH . DS . 'wen' . DS . 'views' . DS)
            ->template('exception/exception')
            ->options($data)->render();
        exit(0);
    }


    protected static function getContents($file, $line)
    {
        $start = $line - 10;
        $end = $line + 10;
        $content = File::getContent($file, $start, $end);
        $content = str_replace(' ', ' &nbsp;', $content);
        $content = explode("\r\n", $content);
        $contents = [];
        foreach ($content as $str) {
            $ss = [
                'target' => $start == $line,
                'next' => ($start == ($line + 1) || $start == ($line - 1)),
                'line' => $start,
                'content' => $str,
            ];
            $contents[] = $ss;
            $start++;
        }
        return $contents;
    }
}