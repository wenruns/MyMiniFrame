<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/29
 * Time: 18:53
 */
namespace core\wen;


class FilesController
{

    public function __construct()
    {
    }

    protected $file = null;

    protected $acceptType = '*';

    public function setAcceptType($type)
    {
        $this->acceptType = $type;
    }

    public function file($index = '')
    {
        if ($index) {
            if (!isset($_FILES[$index])) {
                throw new \Exception('undefined index '.$index);
            }
            $this->file = $_FILES[$index];
        } else {
            $this->file = $_FILES;
        }
        return $this;
    }

    public function getContent() {
        if (empty($this->file) || !isset($this->file['tmp_name'])) {
            return null;
        }
        return file_get_contents($this->file['tmp_name']);
    }

    public function getName()
    {
        if (empty($this->file) || !isset($this->file['name'])) {
            return null;
        }
        return $this->file['name'];
    }


    public function save($path)
    {
        if (empty($this->file)) {
            throw new \Exception('上传文件不能为空！');
        }
        return $this->upload($this->file, $path);
    }

    public function upload($file, $path)
    {
        if ($file['error'] > 0) {
            return false;
        }
        if ($this->acceptType == '*') {
            return move_uploaded_file($file['tmp_name'], $path);
        } else {
            $type = $file['type'];
            if ($type == $this->acceptType || (is_array($this->acceptType) && in_array($type, $this->acceptType))) {
                return move_uploaded_file($file['tmp_name'], $path);
            }
        }
        return false;
    }

    public function toArray()
    {
        return $this->file;
    }
}