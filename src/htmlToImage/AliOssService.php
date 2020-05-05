<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30
 * Time: 18:36
 */

namespace src\htmlToImage;

use OSS\OssClient;

class AliOssService
{
    protected $accessKeyId = '';
    protected $accessKeySecret = '';
    protected $endpoint = '';
    protected $bucket = '';
    protected $ossClient = null;
    protected $timeout = 3600;
    protected $watermarkMaxLength = 64;         //水印最大长度

    public function __construct()
    {
        $this->accessKeyId = config('config.OSS_ACCESS_ID');
        $this->accessKeySecret = config('config.OSS_ACCESS_KEY');
        $this->endpoint = config('config.OSS_ENDPOINT');
        $this->bucket = config('config.OSS_BUCKET');
        $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
    }

    /**
     * @param $localFile
     * @param $objectName
     * @param null $bucket
     * @return bool|null
     * @throws \OSS\Core\OssException
     *
     * 单文件上传
     */
    public function postFile($localFile, $objectName, $bucket = null)
    {
        if (empty($bucket)) {
            $bucket = $this->bucket;
        }
        // 文件名称
        $object = $objectName;
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        $filePath = $localFile;

        try {
//            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $getData = $this->ossClient->uploadFile($bucket, $object, $filePath);
            return $getData;
        } catch (OssException $e) {
            //todo 创建log记录文件上传失败原因
            throw new \Exception($e);
        }
    }


}