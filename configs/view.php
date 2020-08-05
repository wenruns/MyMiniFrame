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
     * 模板变量匹配规则
     */
    'preg_replace' => [
        '/{{--(.*)--}}/'                        => '<?php /**  ${1}  */ ?>', // 注释匹配
        '/{!!([^\!!}]*)!!}/'                    => '<?php echo html_entity_decode(${1}); ?>', // html变量匹配
        '/{{([^\}}]*)}}/'                       => '<?php echo htmlentities(${1}); ?>', // 变量匹配
        '/@method\((.*)\)/'                     => '<?php echo ${1}; ?>', // 方法调用
        '/@(end\S*)/'                           => '<?php ${1}; ?>', // 结束标志匹配
        '/@switch\s*\((.*)\)\s*/'               => '<?php switch(${1}): ?>', // switch匹配
        '/@case\s*\((.*)\)\s*/'                 => '<?php case ${1}: ?>', // case匹配
        '/@break\s*/'                           => '<?php break; ?>', // break 匹配
        '/@(([^\(\s])+\x20*(\((.*)\)|))/'       => '<?php ${1} : ?>', // 匹配foreach、if、elseif、else、for等等
    ],



];