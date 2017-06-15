<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 刘剑超 <cto-ljc@qq.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*
 * 自定义成功数据结构
 */
function json_success($msg = '',$data = array()){
	$json['success'] = true;
	$json['msg'] = $msg;
	$json['data'] = $data;
	echo json_encode($json);
    exit;
}

/*
 * 自定义失败数据结构
 */
function json_error($msg = '',$data = array()){
	$json['success'] = false;
	$json['msg'] = $msg;
	$json['data'] = $data;
	echo json_encode($json);
    exit;
}