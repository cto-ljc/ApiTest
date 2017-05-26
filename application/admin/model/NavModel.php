<?php
namespace app\admin\model;
use app\common\model\BaseModel;
use think\Db;

class NavModel extends BaseModel{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'nav_item';
	/*
	 * api分类栏目列表
	 */
	public function navList($app_id = 0){
		$api_data = array();
		$map['app_id'] = $app_id;
		$nav_list = db('nav_item') -> where($map) -> order(array('sort' => 'asc')) -> select();		
		foreach($nav_list as $key => $value){
			$value['icon'] = '&#'.$value['icon'].';';
			$nav_list[$key] = $value;
		}

		$total = db('nav_item') -> where($map) -> order(array('sort' => 'asc')) -> count();	

		$data['total'] = $total;
		$data['list'] = $nav_list;
		return $data;
	}

	/*
	 * api分类栏目详情
	 */
	public function navInfo($nav_id){
		$map['id'] = $nav_id;
		$nav_info = db('nav_item') -> where($map) -> find();		
		return $nav_info;
	}

	/*
	 * 添加api分类栏目
	 * @param array $data 分类信息
	 * return bool 修改结果
	 */
	public function addNav($data){
		$map['name'] = $data['name'];
		if(db('nav_item') -> where($map) -> count()){
			$this -> error = '此分类已存在';
			return false;
		}
		return db('nav_item') -> insert($data);
	}

	/*
	 * 修改api分类栏目
	 * @param int $nav_id
	 * @param array $data 分类信息
	 * return bool 修改结果
	 */
	public function editNav($nav_id,$data){
		$map['id'] = $nav_id;
		return db('nav_item') -> where($map) -> update($data);
	}

	/*
	 * 删除api分类栏目
	 * @param int $nav_id
	 * return bool 删除结果
	 */
	public function delNav($app_id){
		Db::startTrans();
		try{
			Db::name('nav_item') -> where('id',$app_id) -> delete();
			Db::name('api') -> where('nav_id',$app_id) -> delete();
			Db::name('api_param') -> where('nav_id',$app_id) -> delete();
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