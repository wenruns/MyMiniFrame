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


    public static function show($var)
    {
        (new View('dump/dump', ['data' => $var]))
            ->rootPath(CORE_PATH . DS . 'wen' . DS . 'views' . DS)
            ->render();
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
            ->variables($data)->render();
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