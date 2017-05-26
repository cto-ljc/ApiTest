<?php
namespace app\admin\model;
use app\common\model\BaseModel;
use think\Db;

class ApiModel extends BaseModel{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api';
	/*
	 * api信息
	 */
	public function apiData($app_id = 0){
		$api_data = array();
		$map['app_id'] = $app_id;
		$app_list = Db::name('nav_item') -> where($map) -> order(array('sort' => 'asc')) -> select();
		foreach($app_list as $key => $value){
			$api_map['nav_id'] = $value['id'];

			$api_list = Db::name('api') -> where($api_map) -> order('sort','asc') -> select();
			$api_count = Db::name('api') -> where($api_map) -> count();
			$value['icon'] = '&#'.$value['icon'].';';
			$value['api_list'] = $api_list;
			$value['api_count'] = $api_count;
			$api_data[] = $value;
		}
		return $api_data;
	}

	/*
	 * api详情
	 * @param int $api_id
	 * return array
	 */
	public function apiInfo($api_id){
		$map['id'] = $api_id;
		$api_info = Db::name('api') -> where($map) -> find();		
		return $api_info;
	}

	public function addApi($data){
		if($data['name'] == ''){
			$this -> error = '接口名称不能为空';
			return false;
		}
		$map['nav_id'] = $data['nav_id'];
		$map['name'] = $data['name'];
		if(Db::name('api') -> where($map) -> count()){
			$this -> error = '接口已存在';
			return false;
		}
		return Db::name('api') -> insertGetId($data);				
	}

	/*
	 * 修改api详情
	 * @param int $api_id
	 * return bool 修改结果
	 */
	public function updateApi($api_id,$data){
		if($data['name'] == ''){
			$this -> error = '接口名称不能为空';
			return false;
		}
		$map['id'] = $api_id;				
		return Db::name('api') -> where($map) -> update($data);
	}

	/*
	 * 删除api
	 * @param int $api_id
	 * return bool 修改结果
	 */
	public function delApi($api_id){
		Db::startTrans();
		try{
			Db::name('api') -> where('id',$api_id) -> delete();
			Db::name('api_param') -> where('api_id',$api_id) -> delete();
		    // 提交事务
		    Db::commit();    
		    return true;
		} catch (\Exception $e) {
		    // 回滚事务
		    Db::rollback();
		    return false;
		}
	}
}