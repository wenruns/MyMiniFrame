<?php
/**
 * 模板解析
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
    /**
     * 模板配置
     * @var array
     */
    protected $_configs = [];

    /**
     * 模板名称
     * @var string
     */
    protected $_template = '';

    /**
     * 模板数据
     * @var array
     */
    protected $_options = [];

    /**
     * 模板根目录
     * @var mixed|string
     */
    protected $_root_path = '';

    /**
     * 模板文件后缀
     * @var mixed|string
     */
    protected $_suffix = '.blade.php';

    protected $_layouts = [];

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->_configs = config('view');
        $this->_root_path = str_replace('\\', '/', $this->_configs['tpl_path']);
        $this->_suffix = $this->_configs['tpl_suffix'];
    }

    /**
     * 设置根目录
     * @param $root_path
     * @return $this
     */
    public function rootPath($root_path)
    {
        $this->_root_path = str_replace('\\', '/', $root_path);
        return $this;
    }

    /**
     * 设置模板
     * @param string $template
     * @return $this
     */
    public function template($template = '')
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * 设置模板数据
     * @param array $options
     * @return $this\
     */
    public function options($options = [])
    {
        $this->_options = $options;
        return $this;
    }

    /**
     * 输出模板
     */
    public function render()
    {
        try {
            extract($this->_options);
            $template = $this->getTemplate();
            include_once $template;
        } catch (\Exception $e) {
            Output::throwException($e);
        }
        $this->clearEnv();
    }

    /**
     * 清空模板环境
     */
    public function clearEnv()
    {
        $this->_options = [];
        $this->_template = '';
    }


    /**
     * 获取模板
     * @return string
     * @throws \Exception
     */
    public function getTemplate()
    {
        if (file_exists($this->_template)) {
            return $this->getCachePath($this->_template);
        }
        if (empty($this->_template)) {
            $relative_path = $this->getDefaultRelativePath();
        } else {
            $relative_path = $this->_template . $this->_suffix;
        }
        return $this->getCachePath(strtolower($relative_path));
    }


    /**
     * 缓存模板文件
     * @param $real_path
     * @param $cache_path
     * @throws \Exception
     */
    protected function cacheViewPage($real_path, $cache_path)
    {
        $content = file_get_contents($real_path);
        $content = $this->parseContent($content);
        if ($content === false) {
            return;
        }
        file_put_contents($cache_path, $content);
    }


    /**
     * 解析模板
     * @param $content
     * @return mixed|string|string[]|null
     * @throws \Exception
     */
    protected function parseContent($content)
    {
        # 加载布局页面
        preg_match_all('/@include\s*\(([^\)]*)\)/', $content, $result);
        if (!empty($result)) {
            foreach ($result[0] as $k => $item) {
                $template_args = explode(',', $result[1][$k]);
                $temp_template = array_shift($template_args);
                $temp_template = trim(trim($temp_template, '\''), '"') . $this->_suffix;
                isset($this->_layouts[md5($temp_template)]) ? $this->_layouts[md5($temp_template)]++ : $this->_layouts[md5($temp_template)] = 1;
                $cache_path = $this->getCachePath($temp_template);
                $content = str_replace($item, '<?php view_layout("' . $cache_path . '"' . (empty($template_args) ? '' : ',' . implode(',', $template_args)) . ') ?>', $content);
            }
        }

        $border_left = $this->_configs['border_left'];
        $border_right = $this->_configs['border_right'];
        $note_border_left = $this->_configs['note_border_left'];
        $note_border_right = $this->_configs['note_border_right'];
        $html_border_left = $this->_configs['html_border_left'];
        $html_border_right = $this->_configs['html_border_right'];
        # 注释
        $content = preg_replace("/$note_border_left(.*)$note_border_right/", '<?php /**  ${1}  */ ?>', $content);
        # 输出html标签
        $non_content = '\\' . $html_border_right;
        $content = preg_replace("/$html_border_left([^$non_content]*)$html_border_right/", '<?php echo html_entity_decode(${1}); ?>', $content);
        # 输出html实体
        $non_content = '\\' . $border_right;
        $content = preg_replace("/$border_left([^$non_content]*)$border_right/", '<?php echo htmlentities(${1}); ?>', $content);
        # 方法调用
        $content = preg_replace('/@method\((.*)\)/', '<?php echo ${1}; ?>', $content);
        # 结束标签
        $content = preg_replace('/@(end\S*)/', '<?php ${1}; ?>', $content);
        # 匹配foreach、if、elseif、else、for等等
        $content = preg_replace('/@(([^\(\s])+\x20*(\((.*)\)|))/', '<?php ${1} : ?>', $content);
        return $content;
    }

    /**
     * 获取缓存文件路径
     * @param $relative_path
     * @return string
     * @throws \Exception
     */
    protected function getCachePath($relative_path)
    {
        $real_path = file_exists($relative_path) ? $relative_path : $this->getRealPath($relative_path);
        $cache_file_name = md5($real_path) . '.php';
        $path = CACHE_PATH . DS . 'views';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $cache_path = $path . DS . $cache_file_name;

        if ((!key_exists(md5($relative_path), $this->_layouts) || $this->_layouts[md5($relative_path)] == 1) && (config('app.app_debug', false) || !file_exists($cache_path))) {
            $this->cacheViewPage($real_path, $cache_path);
        }
        return str_replace('\\', '/', $cache_path);
    }

    /**
     * 获取模板文件真实路径
     * @param $relative_path
     * @return string
     * @throws \Exception
     */
    protected function getRealPath($relative_path)
    {
        $real_path = $this->_root_path . $relative_path;
//        echo ($real_path);die;
        if (is_file($real_path)) {
            return $real_path;
        }
        throw new \Exception('视图' . $relative_path . '不存在。');
    }

    /**
     * 获取默认的模板
     * @return mixed|string
     */
    protected function getDefaultRelativePath()
    {
        $class_suffix = config('app.class_suffix', 'Controller');
        $action = App::make(RouterDriver::class)->getAction();
        $relative_path = $action['namespace'] . DS . $action['controller'];
        $relative_path = str_replace($class_suffix, '', $relative_path);
        $relative_path = str_replace('\\', DS, $relative_path);
        $relative_path = trim($relative_path, DS) . DS . $action['method'] . $this->_suffix;
        return $relative_path;
    }
}