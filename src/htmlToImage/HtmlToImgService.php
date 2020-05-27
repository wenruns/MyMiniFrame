<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 18:48
 */

namespace src\htmlToImage;


class HtmlToImgService
{
    /**
     * html转换成图片
     */
    public function html2Img()
    {
        $dir_path = storage_path('screen_shot');
        if (!is_dir($dir_path)) {
            mkdir($dir_path, 0777, true);
            chmod($dir_path, 0777);
        }
        $img_name = 'screen_shot_' . date('YmdHis') . '.png';

        // 本地截图保存路径
        $file_path = $dir_path . DS . $img_name;
        // 需要截图url
        $url = request('url');
        // 上传aliOss的名称
        $objectName = request('objectName');
        // 上传到aliOss的bucket对象
        $bucket = request('bucket');
        file_put_contents(storage_path('text.txt'), 'url:'.$url."\r\nname:".$objectName . "\r\nbucket:" . $bucket);
        try {
            if (function_exists('wkhtmltox_convert')) {
                $res = wkhtmltox_convert(
                    'image',
                    array(
                        'out' => $file_path,
                        'in' => $url,
                        'screenWidth'=>790,
                        'smartWidth'=>true,
                        'quality'=>100,
//                        'fmt'=>'jpg'
                    )
                );
                if (!$res) {
                    throw new \Exception('wkhtmltox截图失败！');
                }
            } else {
                throw new \Exception('wkhtmltox未安装！');
            }
        } catch (\Exception $e) {
            $this->clearFile($file_path);
            try {
                $tools = new PhantomjsTools();
                $tools->htmlToImage($url, $file_path);
            } catch (\Exception $e1) {
                apiResponse([
                    'status' => false,
                    'errMsg' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine(),
                ]);
            }
        }
        try {
            if (is_file($file_path)) {
                $aliOssService = new AliOssService();
                $res = $aliOssService->postFile($file_path, $objectName, $bucket);
                $this->clearFile($dir_path);
                apiResponse([
                    'status' => true,
                    'errMsg' => 'ok',
                    'postFile' => $res,
                ]);
            } else {
                apiResponse([
                    'status' => false,
                    'errMsg' => '截图失败',
                ]);
            }
        } catch (\Exception $e) {
            $this->clearFile($dir_path);
            apiResponse([
                'status' => false,
                'errMsg' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine(),
            ]);
        }
    }

    /**
     * @param $path
     */
    public function clearFile($path)
    {
//        if (is_dir($path)) {
//            foreach (scandir($path) as $key => $file) {
//                if ($file != '.' && $file != '..') {
//                    if (is_file($path . DS . $file)) {
//                        unlink($path . DS . $file);
//                    }
//                }
//            }
//        } else if (is_file($path)) {
//            unlink($path);
//        }
    }

}