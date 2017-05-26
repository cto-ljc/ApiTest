<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;

class ApiNav extends Common{	
	public function navList(){
		$app_id = input('param.app_id');
		
		$AppModel = Loader::model('AppModel');                      //实例化模型
        $app_list = $AppModel -> getAppList();
        $this -> assign('app_list',$app_list);

        $NavModel = Loader::model('NavModel');                      //实例化模型
		$nav_data = $NavModel -> navList($app_id);
		$this -> assign('nav_list',$nav_data['list']);
		$this -> assign('nav_total',$nav_data['total']);

		return view();
	}

	public function editNav(){
		$nav_id = input('param.nav_id');
		$NavModel = Loader::model('NavModel');                      //实例化模型
		
		if(request() -> isPost()){
			$data['name'] = input('post.name');
			$data['icon'] = input('post.icon');
			$data['sort'] = input('post.sort');

			if($NavModel -> editNav($nav_id,$data) !== false){
				json_success('修改成功',array('callback_type' => 2));
			}else{
				json_error($NavModel -> getError());
			}
		}else{			
			$icon_list = $this -> layuiIconList();
			$this -> assign('icon_list',$icon_list);

			$nav_info = $NavModel -> navInfo($nav_id);
			$this -> assign('info',$nav_info);
			return view('navForm');
		}
	}

	public function addNav(){
		$NavModel = Loader::model('NavModel');                      //实例化模型
		$app_id = input('param.app_id');
		if(!$app_id){
			return;
		}
		if(request() -> isPost()){
			$data['app_id'] = $app_id;
			$data['name'] = input('post.name');
			$data['icon'] = input('post.icon');
			$data['sort'] = input('post.sort');

			if($NavModel -> addNav($data) !== false){
				json_success('添加成功',array('callback_type' => 2));
			}else{
				json_error($NavModel -> getError());
			}
		}else{
			$icon_list = $this -> layuiIconList();
			$this -> assign('icon_list',$icon_list);

			return view('navForm');
		}
	}

	public function delNav(){
		if(request() -> isPost()){
			$app_id = input('post.app_id');
			$NavModel = Loader::model('NavModel');                      //实例化模型	
			if($NavModel -> delNav($app_id) !== false){
				json_success('删除成功');
			}else{
				json_error($NavModel -> getError());
			}
		}
	}

	private function layuiIconList(){
		$icons = 'xe611;xe614;x1002;xe60f;xe615;xe641;xe620;xe628;x1006;x1007;xe629;xe600;xe617;xe606;xe609;xe60a;xe62c;x1005;xe61b;xe610;xe62d;xe63d;xe602;xe603;xe62e;xe62f;xe61f;xe601;xe630;xe631;xe642;xe61a;xe621;xe632;xe618;xe608;xe633;xe61c;xe634;xe607;xe635;xe636;xe60b;xe619;xe637;xe61d;xe640;xe604;xe612;xe605;xe638;xe60c;xe616;xe613;xe61e;xe60d;xe64c;xe60e;xe622;xe64f;xe64d;xe639;xe63e;xe623;xe63f;xe643;xe647;xe648;xe649;xe626;xe627;xe62b;xe63a;xe624;xe63b;xe650;xe64b;xe63c;xe62a;xe64e;xe646;xe625;xe64a;xe644;';
		$icon_list = explode(";",$icons,-1);
		foreach($icon_list as $key => $value){
			$icon_list[$key] = $value;
		}		
		return $icon_list;
	}
}