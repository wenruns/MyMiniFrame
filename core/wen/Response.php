<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/7
 * Time: 13:59
 */

namespace core\wen;


use core\wen\output\Output;

class Response
{

    protected $_status_code = 200;

    protected $_error = null;

    protected $_description = 'ok';

    protected $_contents = null;


    /**
     * @param $content
     * @param \Throwable|null $e
     * @return $this
     */
    public function content($content, \Throwable $e = null)
    {
        if ($e) {
            $this->_contents = $e;
        } else {
            $this->_contents[] = $content;
        }
        return $this;
    }


    /**
     * @param string $status_code
     * @return $this
     */
    public function statusCode($status_code = '200')
    {
        $this->_status_code = $status_code;
        return $this;
    }

    public function error($error = '')
    {
        $this->_error = $error;
        return $this;
    }

    public function desc($desc = 'ok')
    {
        $this->_description = $desc;
        return $this;
    }

    public function output()
    {
        if ($this->_status_code != 200) {
            $this->showError();
        } else {
            foreach ($this->_contents as $content) {
                if (is_string($content)) {
                    echo $content;
                } else if ($content instanceof View) {
                    $content->render();
                }
            }
        }
    }

    protected function showError()
    {
        switch ($this->_status_code) {
            case 404:
                break;
            case 500:
                Output::throwException($this->_contents);
                break;
            default:

        }
    }
}