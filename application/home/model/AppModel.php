<?php
namespace app\home\model;

use think\Model;
use think\Db;

class AppModel extends Model{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_app';

	/*
	 * 获取app项目列表
	 * @param int $uid 用户id
	 */
	public function getAppList($uid){		
		$app_ids = db('user') -> where(array('uid' => $uid)) -> value('app_ids');
        $app_ids = explode(',',$app_ids,-1);
		$map['id'] = array('in',$app_ids);
		$field = 'a.*';
		$app_list = Db::name('app') 
			-> alias('a')
			-> field($field)			
			-> where($map) 
			-> order(array('a.sort' => 'asc')) -> select();
		return $app_list;
	}

	/*
	 * 获取app项目详情
	 * @param int $app_id 
	 */
	public function appInfo($app_id){
		$map['id'] = $app_id;
		$app_info = Db::name('app') -> where($map) -> find();
		return $app_info;
	}
}