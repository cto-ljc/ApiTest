<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;

class ApiItem extends Common{	
	/**
	 * 添加栏目
	 */
	public function add($app_id,$name){
		if(request() -> isPost()){
			$id = \think\Db::name('api_item') -> insertGetId([
				'app_id' => $app_id,
				'name' => $name,
			]);

			$item = \think\Db::name('api_item') -> where(['id' => $id]) -> find();

			json_success('添加成功',$item);
		}
	}

	/**
	 * 编辑栏目
	 * @return [type] [description]
	 */
	public function edit($api_item_id,$name){
		if(request() -> isPost()){
			\think\Db::name('api_item') -> where(['id' => $api_item_id]) -> update([
				'name' => $name,
			]);

			$item = \think\Db::name('api_item') -> where(['id' => $api_item_id]) -> find();

			json_success('添加成功',$item);
		}
	}

	/**
	 * 删除栏目
	 * @return [type] [description]
	 */
	public function del($api_item_id){
		if(request() -> isPost()){
			\think\Db::name('api_item') -> where(['id' => $api_item_id]) -> delete();
			json_success('删除成功');
		}
	}
}