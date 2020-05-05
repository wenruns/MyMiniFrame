<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31
 * Time: 10:54
 */

namespace src\htmlToImage;


class PhantomjsTools
{
    protected $command_linux = 'phantomjs';
    protected $command_window = '';

    protected $file = '';

    public function __construct()
    {
        $this->command_window = __DIR__.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'phantomjs.exe';
        $this->file = __DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'index.js';
    }

    /**
     * @param $url
     * @param $savePath
     * @param array $options
     * @return array
     */
    public function htmlToImage($url, $savePath, $options = [])
    {
        $command = (strtoupper(substr(PHP_OS,0,3))==='WIN' ? $this->command_window : $this->command_linux).' '.$this->file.' url='.$url.' path='.$savePath.' settings='.$this->makeJsonString($options);
//        dd($command);
        return $this->exec($command);
    }

    public function makeJsonString($arr, $str = '{')
    {
        $i = 0;
        $j = count($arr);
        foreach ($arr as $key => $val) {
            $str .= "'".$key."':";
            if (is_array($val)) {
                $str .= '{';
                $str = $this->makeJsonString($val, $str);
            } else {
                $str .= "'".$val."'";
            }
            $i++;
            if ($i < $j) {
                $str .= ',';
            }
        }
        return $str . '}';
    }

    /**
     * @param $command
     * @param array $output
     * @param null $return_var
     * @return array
     *
     * 执行指令
     */
    protected function exec($command, $output = [], $return_var = null)
    {
        exec($command . " 2>&1", $output, $return_var);
        // 字符串编码转换
        foreach ($output as $key => $val) {
            $encode = mb_detect_encoding($val, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
            $output[$key] = mb_convert_encoding($val, 'UTF-8', $encode);
        }
        return [
            'output' => $output,
            'return_var' => $return_var
        ];
    }
}