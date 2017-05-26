<?php
namespace app\home\model;

use think\Model;
use think\Db;

class NavModel extends Model{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_nav_item';

    public function test(){
    	return 'test';
    }

    /*
     * 获取指定app的接口列表
     */
    public function getNav($app_id){
    	$nav = $this -> where(array('app_id' => $app_id)) -> order('sort','asc') -> select(); 
        foreach($nav as $key => $value){
            $nav[$key]['icon'] = '&#'.$value['icon'].';';
            $map['nav_id'] = $value['id'];
            $nav_child = Db::name('api') -> where($map) -> order('sort','asc') -> select(); 
            $nav[$key]['child'] = $nav_child;
        }  
        return $nav;
    }
}