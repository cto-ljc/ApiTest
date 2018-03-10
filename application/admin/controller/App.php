<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;

class App extends Common{
	protected $page_size = 15;

	//项目列表
	public function appList(){
		$app_model = new \app\admin\model\App();                      //实例化模型
        $list_data = $app_model -> getAppList($this -> page_size);
        
        $this -> assign('list_data',$list_data);
		return view('app_list');
	}

	//添加项目
	public function add(){
		if(request() -> isPost()){
			$data['name'] = input('post.name');
			$data['version'] = input('post.version');
			$data['describe'] = input('post.describe');
			$data['domain'] = input('post.domain');
			$data['sort'] = input('post.sort');
			
			$app_model = new \app\admin\model\App();                      //实例化模型
			$id = $app_model -> addApp($data);
			if($id !== false){

				$app_model = new \app\admin\model\App();                      //实例化模型
        		$list_data = $app_model -> getAppList($this -> page_size);

				json_success('添加成功',$list_data);
			}else{
				json_error($app_model -> getError());
			}
		}else{
			
			return view('app_form');
		}	
	}

	//编辑项目
	public function edit(){		
		if(request() -> isPost()){
			$app_id = input('param.app_id'); 

			$data['name'] = input('post.name');
			$data['version'] = input('post.version');
			$data['describe'] = input('post.describe');
			$data['domain'] = input('post.domain');
			$data['sort'] = input('post.sort');

			$app_model = new \app\admin\model\App();                      //实例化模型
			if($app_model -> editApp($app_id,$data) !== false){
				$app_model = new \app\admin\model\App();                      //实例化模型
        		$list_data = $app_model -> getAppList($this -> page_size);

				json_success('修改成功',$list_data);
			}else{
				json_error($app_model -> getError());
			}

		}else{
			$app_id = input('param.app_id'); 

			$app_model = new \app\admin\model\App();                      //实例化模型
	        $app_info = $app_model -> appInfo($app_id);
			$this -> assign('app_info',$app_info);
			return view('app_form');
		}		
	}

	//删除项目
	public function del(){
		if(request() -> isPost()){
			$app_id = input('post.app_id'); 
			$app_model = new \app\admin\model\App();                      //实例化模型
			if($app_model -> delApp($app_id) !== false){
				json_success('删除成功',array('callback_type' => 1));
			}else{
				json_error($app_model -> getError());
			}
		}
	}


}