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
			$param = @$post['param'] ? $post['param'] : [];

			if(\think\Db::name('api') -> where(['name' => $api['name'],'api_item_id' => $api_item_id]) -> count()){
				json_error('api名称重复');
			}

			$app_id = \think\Db::name('api_item') -> where(['id' => $api_item_id]) -> value('app_id');		
				
			$api['api_item_id'] = $api_item_id;
			$api['app_id'] = $app_id;
			$api_id = \think\Db::name('api') -> insertGetId($api);

			foreach ($param as $key => $value) {
				if(Db::name('api_param') -> where(['api_id' => $api_id,'name' => $value['name']]) -> count()){
					json_error('参数'.$value['name'].'重复');
				}

				$value['app_id'] = $app_id;
				$value['api_item_id'] = $api_item_id;
				$value['api_id'] = $api_id;					
				Db::name('api_param') -> insert($value);
			}

			$api_data = \think\Db::name('api') -> where(['id' => $api_id]) -> find();

			json_success('添加成功',[
				'action' => 'add',
				'api' => $api_data
			]);
		}else{
			$api_model = new \app\admin\model\Api();
			$encrypt_array = $api_model -> getEncryptArray();

			$api = [
				'name' => '',
				'path' => '',
				'sort' => '100'
			];

			$this -> assign([
				'api' => $api,
				'param' => [],
				'encrypt_array' => $encrypt_array,
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
			$api_data = $post['api'];
			$param = @$post['param'] ? $post['param'] : [];

			$api = \think\Db::name('api') -> where(['id' => $api_id]) -> find();
			$api_item_id = $api['api_item_id'];
			$app_id = $api['app_id'];

			if(\think\Db::name('api') -> where(['name' => $api_data['name'],'api_item_id' => $api_item_id,'id' => ['<>',$api_id]]) -> count()){
				json_error('api名称重复');
			}			
			
			\think\Db::name('api') -> where(['id' => $api_id]) -> update($api_data);
			$api_data = \think\Db::name('api') -> where(['id' => $api_id]) -> find();

			foreach ($param as $key => $value) {
				if(@$value['id']){
					if(@$value['del']){	//删除
						Db::name('api_param') -> where(['id' => $value['id']]) -> delete();
					}else{
						if(Db::name('api_param') -> where(['api_id' => $api_id,'name' => $value['name'],'id' => ['<>',$value['id']]]) -> count()){
							json_error('参数'.$value['name'].'重复');
						}
						Db::name('api_param') -> where(['id' => $value['id']]) -> update($value);
					}					
				}else{
					if(Db::name('api_param') -> where(['api_id' => $api_id,'name' => $value['name']]) -> count()){
						json_error('参数'.$value['name'].'重复');
					}

					$value['app_id'] = $app_id;
					$value['api_item_id'] = $api_item_id;
					$value['api_id'] = $api_id;					
					Db::name('api_param') -> insert($value);
				}
			}


			json_success('添加成功',[
				'action' => 'edit',
				//'api' => $api_data
			]);
		}else{
			$api_model = new \app\admin\model\Api();
			$encrypt_array = $api_model -> getEncryptArray();

			$api = \think\Db::name('api') -> where(['id' => $api_id]) -> find();
			$param = \think\Db::name('api_param') -> where(['api_id' => $api_id]) -> select();

			$this -> assign([
				'api' => $api,
				'param' => $param,
				'encrypt_array' => $encrypt_array,
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

	/**
	 * 更新排序
	 * @return [type] [description]
	 */
	public function updateSort($id,$sort){
		\think\Db::name('api') -> where(['id' => $id]) -> setField('sort',$sort);
		json_success('修改成功');
	}
}