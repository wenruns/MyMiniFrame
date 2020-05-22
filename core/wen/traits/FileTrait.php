<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 10:05
 */

namespace core\wen\traits;

use core\wen\File;

trait FileTrait
{
    protected $fileInstance = null;


    protected function getFileInstance()
    {
        if (empty($this->fileInstance)) {
            $this->fileInstance = new File();
        }
        return $this->fileInstance;
    }

    public function file($index)
    {
        $this->getFileInstance()->file($index);
        return $this->fileInstance;
    }

    public function getFileContent()
    {
        return $this->getFileInstance()->getContent();
    }

    public function setAcceptType($type = '*')
    {
        $this->getFileInstance()->setAcceptType($type);
        return $this->fileInstance;
    }

    public function save($path)
    {
        return $this->getFileInstance()->save($path);
    }

}