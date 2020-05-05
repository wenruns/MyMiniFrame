<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/29
 * Time: 18:47
 */
namespace src\xml;



class ExplainFileController extends BaseController
{

    protected $key = '123456';

    protected $xmlDirPath = ROOT_PATH . DS . 'storage' . DS;
//    protected $xmlDirPath = 'D:/';

    public function __construct()
    {
        parent::__construct();
        $this->key = config('config.key', '123456');
    }

    public function accept()
    {
        try {
            // 获取xml内容
            $content = $this->request('head').$this->file('xmlFile')->getContent().$this->request('footer');
            // 保存到目标xml文件
            $this->saveXml($content);
            apiResponse([
                'status' => true,
                'code' => 200,
                'errMsg' => 'ok'
            ]);
        } catch (\Exception $e) {
            apiResponse([
                'status' => false,
                'code' => 400,
                'errMsg' => $e->getMessage()
            ]);
        }
    }

    protected function saveXml($content)
    {
        $fileName = $this->file('xmlFile')->getName();
        $fileName = base64_decode(rtrim($fileName, '.txt'));
        $dirName = $this->xmlDirPath . substr($fileName, 0, strrpos($fileName, '/'));
        if (!is_dir($dirName)) {
            mkdir($dirName, 777, true);
            chmod($dirName, 0777);
        }
        // 解密
        $xmlContent = $this->decrypt($content, $this->key, $this->request('iv'));

        // 保存目标文件
        file_put_contents($this->xmlDirPath . $fileName, $xmlContent);

        $logName = substr($fileName, strrpos($fileName, '/')+1);
        $first= strpos($fileName, '/');
        $logPath = $this->xmlDirPath . substr($fileName, 0, $first).DS.'Backup'.substr($fileName, $first, strrpos($fileName, '/')-$first+1);
        if (strpos($fileName, 'Month') === false) {
            $logPath .= date('Y/m/d/');
        } else {
            $logPath .= date('Y/m/');
        }
        if (!is_dir($logPath)) {
            mkdir($logPath, 0777, true);
            chmod($logPath, 0777);
        }
//        dd($logName, $logPath, $fileName);
        // 保存日志文件
//        file_put_contents($logPath.str_replace('.xml', '', $logName).'.txt', $content);
        file_put_contents($logPath.$logName, $xmlContent);
    }

}