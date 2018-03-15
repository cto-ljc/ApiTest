<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;
use \think\Db;

class Api extends Common{	
	public function index($app_id){
		$app = Db::name('app') -> where(['id' => $app_id]) -> find();

		if(!$app){
			echo '项目不存在';
			die;
		}

		$api_item_array = Db::name('api_item') -> where(['app_id' => $app_id]) -> order('sort asc') -> select();

		$api_array = Db::name('api') -> where(['app_id' => $app_id]) -> order('sort asc') -> select();
		
		$this -> assign([
			'app' => $app,
			'app_id' => $app_id,
			'api_item_array' => $api_item_array,
			'api_array' => $api_array,
		]);

		return view();
	}

	/**
	 * 添加api
	 */
	public function add($api_item_id){
		if(request() -> isPost()){
			$post = input('post.');
			$api = $post['api'];

			if(\think\Db::name('api') -> where(['name' => $api['name'],'api_item_id' => $api_item_id]) -> count()){
				json_error('api名称重复');
			}

			$app_id = \think\Db::name('api_item') -> where(['id' => $api_item_id]) -> value('app_id');		
				
			$api['api_item_id'] = $api_item_id;
			$api['app_id'] = $app_id;
			$api_id = \think\Db::name('api') -> insertGetId($api);

			$api_data = \think\Db::name('api') -> where(['id' => $api_id]) -> find();

			json_success('添加成功',[
				'action' => 'add',
				'api' => $api_data
			]);
		}else{
			$api = [
				'name' => '',
				'path' => '',
				'sort' => '100'
			];

			$this -> assign([
				'api' => $api,
				'param' => []
			]);
			return view('form');
		}
	}

	/**
	 * 编辑接口
	 */
	public function edit($api_id){
		if(request() -> isPost()){
			$post = input('post.');
			$api = $post['api'];

			$api_item_id = \think\Db::name('api') -> where(['id' => $api_id]) -> value('api_item_id');

			if(\think\Db::name('api') -> where(['name' => $api['name'],'api_item_id' => $api_item_id,'id' => ['<>',$api_id]]) -> count()){
				json_error('api名称重复');
			}			
			
			$api_id = \think\Db::name('api') -> where(['id' => $api_id]) -> update($api);

			$api_data = \think\Db::name('api') -> where(['id' => $api_id]) -> find();

			json_success('添加成功',[
				'action' => 'edit',
				//'api' => $api_data
			]);
		}else{
			$api = \think\Db::name('api') -> where(['id' => $api_id]) -> find();

			$this -> assign([
				'api' => $api,
				'param' => []
			]);
			return view('form');
		}
	}

	//删除api
	public function del($id){
		if(request() -> isPost()){			
			if(\think\Db::name('api') -> where(['id' => $id]) -> delete() !== false){
				json_success('删除成功');
			}else{
				json_error('删除失败');
			}			
		}		
	}
}