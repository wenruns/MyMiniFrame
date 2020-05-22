<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/11
 * Time: 9:27
 */

namespace core\wen\output;

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
        self::header();
        echo '<div style="background: gray; color: yellow; width: 100%;height:98vh;display: flex;align-items: flex-start;justify-content: space-between;box-sizing: border-box; overflow: hidden;">';
        self::exceptionMsg($e);
        echo '<div style="width: 2px;background: whitesmoke; height: 100%;"></div>';
        self::exceptionCode($e);
        echo '</div>';
        self::footer();
    }

    protected static function exceptionMsg(\Throwable $e)
    {

//        dd($e->getTrace(), $e->getPrevious());
        echo '<div style="width: calc(35% - 1em);height:100%;overflow: auto;">';
        echo '<div style="background: #555;margin: 10px;box-sizing: border-box;padding: 3em;">';
        echo '<div style="font-size: 1em; color: orangered;margin-bottom: .2em;">Exception</div>';
        echo '<div>' . $e->getMessage() . '</div>';
        echo '</div>';


        $len = count($e->getTrace()) + 1;
        echo '<div style="margin: 10px;box-sizing: border-box;padding: 0px;" class="exception-local-list">';


        echo '<li data-id="' . $len . '" style="list-style: none;background: #666;margin: 0px;box-sizing: border-box;cursor: pointer;display:flex;">';
        echo '<div style="width: calc(100% - 5px);padding: 1em;">';
        echo '<div style=""><span style="display: inline-block;color: #aaa;margin-right: .2em;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;width: 1.3em; height:1.3em; text-align:center;line-height:1.3em;background: #333;">' . $len . '</span>Exception</div>';
        echo '<div style="color: #999;font-size: .8em;">' . str_replace(ROOT_PATH, '...', $e->getFile()) . '&nbsp;&nbsp;&nbsp;' . $e->getLine() . '</div>';
        echo '</div>';
        echo '<div class="tag tag-' . $len . '" style="width: 5px;background: red;"></div>';
        echo '</li>';


        foreach ($e->getTrace() as $key => $item) {
            $len -= 1;
            echo '<li data-id="' . $len . '" style="list-style: none;background: #666;margin: 0px;box-sizing: border-box;border-top: 1px solid #fff;cursor: pointer;display:flex;">';
            echo '<div style="width: calc(100% - 5px);padding: 1em;">';
            echo '<div><span style="display: inline-block;color: #aaa;margin-right: .2em;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;width: 1.3em; height:1.3em; text-align:center;line-height:1.3em;background: #333;">' . $len . '</span>' . $item['class'] . '</div>';
            echo '<div  style="color: #999;font-size: .8em;">' . str_replace(ROOT_PATH, '...', $item['file']) . '&nbsp;&nbsp;:&nbsp;' . $item['line'] . '</div>';
            echo '</div>';
            echo '<div class="tag tag-' . $len . '" style="width: 5px;background: red;visibility: hidden;"></div>';
            echo '</li>';
        }
        echo '</div>';
        echo '</div>';
    }

    protected static function exceptionCode(\Throwable $e)
    {
        echo '<div style = "width: 65%;overflow-y: auto;overflow-x:hidden;height: 100%;" class="exception-code-env">';
        echo '<div style="margin: 10px;">';
        $len = count($e->getTrace()) + 1;
        self::exceptionLocationCode($e->getFile(), $e->getLine(), $len, true);
        foreach ($e->getTrace() as $key => $item) {
            $len -= 1;
            self::exceptionLocationCode($item['file'], $item['line'], $len);
        }
        echo '</div>';
        echo '<div style="width: 100%;overflow: auto;">';
        self::showSystemEnvironment();
        echo '</div>';
        echo '</div>';


        echo '<script>';
        echo <<<SCIRPT
        window.onload = function(){
            document.querySelectorAll('.exception-local-list li').forEach(function(item, key){
                item.addEventListener('click', function(e){
                    document.querySelector('.exception-code-env').scrollTo(0,0);
                    try{
                        e.path.forEach(function(vo, k){
                            if(vo.localName == 'li'){
                                var id = vo.attributes['data-id'].value;
                                
                                document.querySelector('#code_'+id).style.display = 'block';
                                var _elem = document.querySelector('#code_'+id), elem = _elem;
                            
                                while(_elem.previousSibling){
                                    _elem = _elem.previousSibling;
                                    _elem.style.display = 'none';
                                }
                                while(elem.nextSibling){
                                    elem = elem.nextSibling;
                                    elem.style.display = 'none';
                                }
                                
                   
                                vo.querySelector('.tag').style.visibility = '';
                                var _vo = vo;
                        
                                while(vo.previousSibling){
                                    vo = vo.previousSibling;
                                    vo.querySelector('.tag').style.visibility = 'hidden';
                                }
                                while(_vo.nextSibling){
                                    _vo = _vo.nextSibling;
                                    _vo.querySelector('.tag').style.visibility = 'hidden';
                                }
                                
                                throw new Error('Job is worked');
                            }
                            
                        });  
                    }catch(e){
                    }
                  
                });
            });
        };
SCIRPT;
        echo '</script>';
    }

    protected static function exceptionLocationCode($file, $line, $id, $show = false)
    {
        echo '<div style="display: ' . ($show ? '' : 'none') . ';" id="code_' . $id . '">';
        echo '<div > ' . $file . '</div >';
        echo '<div style = "background: black;box-sizing: border-box;padding: 15px 0px;font-size: 13px;" > ';
        $start = $line - 10;
        $end = $line + 10;
        $content = File::getContent($file, $start, $end);
        $content = str_replace(' ', ' &nbsp;', $content);
        $content = explode("\r\n", $content);
        foreach ($content as $str) {
            echo '<p style = "padding: 0px 15px;margin: 0px;' . ($line == $start ? 'background:rgba(255, 100, 100, .3);' : ($start == ($line - 1) || $start == $line + 1 ? 'background:rgba(255, 100, 100, .2);' : '')) . '" > ' . $start . ' . ' . $str . '</p> ';
            $start++;
        }
        echo '</div></div>';
    }

    protected static function showSystemEnvironment()
    {
        $arr = [
            'POST' => request_post(),
            'GET' => request_get(),
            'ENVIRONMENT' => env(),
            'SERVER' => server(),
        ];
        foreach ($arr as $key => $item) {
            echo '<h3>' . $key . (empty($item) ? '<span style="font-size: 12px;margin-left: 10px;color: #aaa;">empty</span>' : '') . '</h3>';
            self::echoArr($item);
        }
    }

    public static function echoArr(array $arr, $n = 0)
    {
        echo '<table style="font-size: 12px;">';
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                self::echoArr($val, $n + 1);
            } else {
                echo '<tr>
<td style="box-sizing: border-box; padding: 0px 10px;">' . $key . '</td>
<td>' . $val . '</td>
</tr>';
            }
        }
        echo '</table>';
    }
}