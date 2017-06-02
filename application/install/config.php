<?php
// +---------------------------------------------------------------------- 
// | Author: 刘剑超 cto-ljc@qq.com,
// +----------------------------------------------------------------------

/**
 * 安装程序配置文件
 */

define('INSTALL_APP_PATH', realpath('./') . '/');

return array(
    'ORIGINAL_TABLE_PREFIX' => 'api_', //默认表前缀
    // +----------------------------------------------------------------------
    // | 模板替换
    // +----------------------------------------------------------------------
    'view_replace_str'  =>  [
    		'__PUBLIC__'=>__ROOT__.'/static',
    		'__STATIC__' => __ROOT__.'/static',
    		'__IMG__'    =>__ROOT__.'/static/install/images',
    		'__CSS__'    => __ROOT__.'/static/install/css',
    		'__JS__'     => __ROOT__.'/static/install/js',
    ],
    
    // 默认控制器名
    'default_controller'     => 'install',
    // 默认操作名
    'default_action'         => 'index',

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',

        'layout_on'     =>  false,
        'layout_name'   =>  'layout',
    ],

    'app_init'=>[],

);
