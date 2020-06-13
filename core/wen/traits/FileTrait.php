<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 10:05
 */

namespace core\wen\traits;

use core\wen\App;
use core\wen\File;

trait FileTrait
{
    /**
     * @var File
     */
    protected $fileInstance = null;

    /**
     * 获取file文件对象
     * @return File|mixed
     * @throws \Exception
     */
    protected function getFileInstance()
    {
        if (empty($this->fileInstance)) {
            $this->fileInstance = App::make(File::class);
        }
        return $this->fileInstance;
    }


    /**
     * @param $index
     * @return File
     * @throws \Exception
     */
    public function file($index)
    {
        $this->getFileInstance()->file($index);
        return $this->fileInstance;
    }

    /**
     * 获取文件内容
     * @return false|string|null
     * @throws \Exception
     */
    public function getFileContent()
    {
        return $this->getFileInstance()->getContent();
    }

    /**
     * 设置接收文件类型
     * @param string $type
     * @return File
     * @throws \Exception
     */
    public function setAcceptType($type = '*')
    {
        $this->getFileInstance()->setAcceptType($type);
        return $this->fileInstance;
    }

    /**
     * 保存文件
     * @param $path
     * @return bool
     * @throws \Exception
     */
    public function save($path)
    {
        return $this->getFileInstance()->save($path);
    }

}