<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;

class App extends Common{

	public function appList(){
		$page_size = 15;
		$AppModel = new \app\admin\model\App();                      //实例化模型
        $list_data = $AppModel -> getAppList($page_size);
        
        $this -> assign('list_data',$list_data);
		return view('app_list');
	}

	public function addApp(){
		if(request() -> isPost()){
			$data['name'] = input('post.name');
			$data['version'] = input('post.version');
			$data['describe'] = input('post.describe');
			$data['domain'] = input('post.domain');
			$data['test_domain'] = input('post.test_domain');
			$data['sort'] = input('post.sort');
			
			$AppModel = new \app\admin\model\App();                      //实例化模型
			if($AppModel -> addApp($data) !== false){
				json_success('添加成功',array('callback_type' => 2));
			}else{
				json_error($AppModel -> getError());
			}
		}else{
			
			return view('appForm');
		}	
	}

	public function editApp(){		
		if(request() -> isPost()){
			$app_id = input('param.app_id'); 

			$data['name'] = input('post.name');
			$data['version'] = input('post.version');
			$data['describe'] = input('post.describe');
			$data['domain'] = input('post.domain');
			$data['test_domain'] = input('post.test_domain');
			$data['sort'] = input('post.sort');

			$AppModel = new \app\admin\model\App();                      //实例化模型
			if($AppModel -> editApp($app_id,$data) !== false){
				json_success('修改成功',array('callback_type' => 2));
			}else{
				json_error($AppModel -> getError());
			}

		}else{
			$app_id = input('param.app_id'); 

			$AppModel = new \app\admin\model\App();                      //实例化模型
	        $app_info = $AppModel -> appInfo($app_id);
			$this -> assign('app_info',$app_info);
			return view('appForm');
		}		
	}

	//删除项目
	public function delApp(){
		if(request() -> isPost()){
			$app_id = input('post.app_id'); 
			$AppModel = new \app\admin\model\App();                      //实例化模型
			if($AppModel -> delApp($app_id) !== false){
				json_success('删除成功',array('callback_type' => 1));
			}else{
				json_error($AppModel -> getError());
			}
		}
	}
}