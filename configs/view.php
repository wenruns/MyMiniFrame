<?php
/**
 * 视图渲染配置
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/8
 * Time: 9:59
 */
return [
    /**
     * 视图根路径
     */
    'tpl_path' => ROOT_PATH . DS . 'app' . DS . 'views' . DS,
    /**
     * 视图后缀
     */
    'tpl_suffix' => '.blade.php',
    /**
     * 左边界
     */
    'border_left' => '{{',
    /**
     * 右边界
     */
    'border_right' => '}}',
    /**
     * html输出左边界
     */
    'html_border_left' => '{!!',
    /**
     * html输出右边界
     */
    'html_border_right' => '!!}',
    /**
     * 注释左边界
     */
    'note_border_left' => '{{--',
    /**
     * 注释右边界
     */
    'note_border_right' => '--}}',

];