<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/11
 * Time: 9:27
 */

class Output
{
    static function show($var)
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
    </style>
</head>
<body>';
        self::showSection($var);
        echo '</body>
</html>';
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
                        echo $pref . $key . ' => array(' . count($var) . '){<br/>';
                        self::showDetail($vo, ++$flag);
                        $flag--;
                        echo $pref . '}<br/>';
                    } else {
                        echo $pref . $key . ' => array(' . count($var) . '){}<br/>';
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


}