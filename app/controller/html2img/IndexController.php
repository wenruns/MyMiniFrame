<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/12
 * Time: 15:58
 */

namespace app\controller\html2img;


use app\services\aliOss\AliOssService;
use app\services\html2img\PhantomjsTools;

class IndexController
{
    protected $_upload_ali_oss = false;

    public function index()
    {
        $dir_path = storage_path('html2img/screen_shot');
        if (!is_dir($dir_path)) {
            mkdir($dir_path, 0777, true);
            chmod($dir_path, 0777);
        }
        $img_name = 'screen_shot_' . date('YmdHis') . '.png';

        // 本地截图保存路径
        $file_path = $dir_path . DS . date('Ymd') . DS . $img_name;
        // 需要截图url
        $url = request('url');
        // 上传aliOss的名称
        $objectName = request('objectName');
        // 上传到aliOss的bucket对象
        $bucket = request('bucket');
        storage('log_' . date('Y_m_d') . '.txt', 'url:' . $url . "\r\nobjectName:" . $objectName . "\r\nbucket:" . $bucket . "\r\n时间：" . date('H:i:s', time()) . "\r\n=============================\r\n", 'html2img/logs', FILE_APPEND);
        try {
            if (function_exists('wkhtmltox_convert')) {
                $res = wkhtmltox_convert(
                    'image',
                    array(
                        'out' => $file_path,
                        'in' => $url,
                        'screenWidth' => 790,
                        'smartWidth' => true,
                        'quality' => 100,
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
                return [
                    'status' => false,
                    'errMsg' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine(),
                ];
            }
        }
        try {
            if (is_file($file_path)) {
                $res = false;
                if ($this->_upload_ali_oss) {
                    $aliOssService = new AliOssService();
                    $res = $aliOssService->postFile($file_path, $objectName, $bucket);
                }
                $this->clearFile($dir_path);
                return [
                    'status' => true,
                    'errMsg' => 'ok',
                    'postFile' => $res,
                ];
            } else {
                return [
                    'status' => false,
                    'errMsg' => '截图失败',
                ];
            }
        } catch (\Exception $e) {
            $this->clearFile($dir_path);
            return [
                'status' => false,
                'errMsg' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine(),
            ];
        }
    }


    public function clearFile($path)
    {

    }
}