<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class App extends Model{	
	/*
	 * 获取app项目列表
	 * return $list
	 */
	public function getAppList($page_size = 15,$map=[]){
		$list = $this -> where($map) -> order(['sort' => 'asc','id' => 'desc']) -> paginate($page_size,'',['query'=>input('get.')]);
		return $list;
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

	/*
	 * 添加app项目
	 * @param array $data 项目参数
	 * return bool 添加结果
	 */
	public function addApp($data){
		$map['name'] = $data['name'];
		if($this -> where($map) -> count()){
			$this -> error = '该项目已存在';
			return false;
		}

		Db::startTrans();
		try{
		    $app_id = $this -> insertGetId($data);

		    Db::name('api_item') -> insert([
		    	'app_id' => $app_id,
		    	'name' => '默认分类',
		    	'sort' => 1
		    ]);

		    // 提交事务
		    Db::commit();    
		    return true;
		} catch (\Exception $e) {
		    // 回滚事务
		    Db::rollback();
		    $this -> error = $e -> getMessage();
		    return false;
		}
	}

	/*
	 * 修改app项目详情
	 * @param int $app_id 
	 * @param array $data 项目参数
	 * return bool 修改结果
	 */
	public function editApp($app_id,$data){
		$map['id'] = ['<>',$app_id];	
		$map['name'] = $data['name'];
		if($this -> where($map) -> count()){
			$this -> error = '该项目已存在';
			return false;
		}			
		return $this -> where(['id' => $app_id]) -> update($data);
	}

	/*
	 * 删除app项目
	 * @param int $id 
	 * return bool 删除结果
	 */
	public function delApp($id){
		// 启动事务		
		Db::startTrans();
		try{
			Db::name('app') -> where('id',$id) -> delete();
			// 提交事务
		    Db::commit(); 
		    return true;
		} catch (\Exception $e) {
		    // 回滚事务		   
		    Db::rollback();
		    $this -> error = '删除失败';
		    return false;
		}		
		
	}
}