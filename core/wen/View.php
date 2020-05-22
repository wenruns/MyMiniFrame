<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 16:30
 */

namespace core\wen;

use core\router\RouterDriver;
use core\wen\output\Output;

class View
{
    protected $_configs = [];

    protected $_template = '';

    protected $_options = [];

    public function __construct()
    {
        $this->_configs = config('view');
    }

    public function template($template = '')
    {
        $this->_template = $template;
        return $this;
    }

    public function options($options = [])
    {
        $this->_options = $options;
        return $this;
    }

    public function render()
    {
        try {
            extract($this->_options);
            $template = $this->getTemplate();
            include_once $template;
        } catch (\Exception $e) {
            Output::throwException($e);
        }
    }


    /**
     *
     * @return string
     */
    public function getTemplate()
    {
        if (file_exists($this->_template)) {
            return $this->_template;
        }
        if (empty($this->_template)) {
            $relative_path = $this->getDefaultRelativePath();
        } else {
            $relative_path = $this->_template . $this->_configs['tpl_suffix'];
        }
        return $this->getCachePath(strtolower($relative_path));
    }


    protected function cacheViewPage($real_path, $cache_path)
    {
        $content = file_get_contents($real_path);
        $content = $this->parseContent($content);
        file_put_contents($cache_path, $content);
    }


    protected function parseContent($content)
    {
        $border_left = $this->_configs['border_left'];
        $border_right = $this->_configs['border_right'];
        $note_border_left = $this->_configs['note_border_left'];
        $note_border_right = $this->_configs['note_border_right'];
        # 注释
        $content = preg_replace("/$note_border_left(.*)$note_border_right/", '<?php /**  ${1}  */ ?>', $content);
        #
        $content = preg_replace("/$border_left!!(.*)!!$border_right/", '<?php echo html_entity_decode(${1}); ?>', $content);
        $content = preg_replace("/$border_left([^\}]*)$border_right/", '<?php echo htmlentities(${1}); ?>', $content);
        $content = preg_replace('/@(foreach\s*\(\s*[^\)]+\s*\))/', '<?php ${1} : ?>', $content);
        $content = preg_replace('/@(endforeach)/', '<?php ${1}; ?>', $content);
        $content = preg_replace('/@(if\s*\(.*\))/', '<?php ${1} : ?>', $content);
        $content = preg_replace('/@(elseif\s*\(.*\))/', '<?php ${1} : ?>', $content);
        $content = preg_replace('/@(else)/', '<?php ${1} : ?>', $content);
        $content = preg_replace('/@(endif)/', '<?php ${1}; ?>', $content);
        $content = preg_replace('/@method\((.*)\)/', '<?php echo ${1}; ?>', $content);
        return $content;
    }

    protected function getCachePath($relative_path)
    {
        $real_path = $this->getRealPath($relative_path);
        $cache_file_name = md5($real_path) . '.php';
        $path = CACHE_PATH . DS . 'views';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $cache_path = $path . DS . $cache_file_name;

        if (config('app.APP_DEBUG', false) || !file_exists($cache_path)) {
            $this->cacheViewPage($real_path, $cache_path);
        }
        return $cache_path;
    }

    protected function getRealPath($relative_path)
    {
        $real_path = $this->_configs['tpl_path'] . $relative_path;
        if (is_file($real_path)) {
            return $real_path;
        }
        throw new \Exception('视图' . $relative_path . '不存在。');

    }

    protected function getDefaultRelativePath()
    {
        $class_suffix = config('app.class_suffix', 'Controller');
        $action = App::make(RouterDriver::class)->getAction();
        $relative_path = $action['namespace'] . DS . $action['controller'];
        $relative_path = str_replace($class_suffix, '', $relative_path);
        $relative_path = str_replace('\\', DS, $relative_path);
        $relative_path = trim($relative_path, DS) . DS . $action['method'] . $this->_configs['tpl_suffix'];
        return $relative_path;
    }
}