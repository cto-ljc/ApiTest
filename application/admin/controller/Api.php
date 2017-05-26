<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;
use \think\Db;

class Api extends Common{	
	/*
	 * api管理页
	 */
	public function apiManage(){
		$app_id = input('param.app_id',0);		
		$AppModel = Loader::model('AppModel');                      //实例化模型
        $app_list_data = $AppModel -> getAppList();
        $app_list = $app_list_data['list']; 
        $this -> assign('app_list',$app_list);

        if(!$app_id && $app_list){
        	$app_id = $app_list[0]['id'];
        }
        $this -> assign('app_id',$app_id);

        $NavModel = Loader::model('NavModel');                      //实例化模型
		$nav_data = $NavModel -> navList($app_id);
		$this -> assign('nav_list',$nav_data['list']);
		$this -> assign('nav_total',$nav_data['total']);

		$ApiModel = Loader::model('ApiModel');                      //实例化模型
		$api_data = $ApiModel -> apiData($app_id);
		$this -> assign('api_data',$api_data);
		
		return view('apiManage');
	}

	public function addApi(){
		$nav_id = input('param.nav_id');
		if(!$nav_id){
			return;
		}
		
		if(request() -> isPost()){		
			$post = input('post.');
			$api_info = $post['api_info'];
			$add_param = @$post['add_param'] ? @$post['add_param'] : array();

			$NavModel = Loader::model('NavModel');                      //实例化模型

			$nav_info = $NavModel -> navInfo($nav_id);
			$app_id = $nav_info['app_id'];
			$api_info['nav_id'] = $nav_id;
			$api_info['app_id'] = $app_id;
			
			Db::startTrans();
			
			//更新api信息
			$ApiModel = Loader::model('ApiModel');                      //实例化模型
			$api_id = $ApiModel -> addApi($api_info);

			if($api_id === false){
				Db::rollback();
				json_error($ApiModel -> getError());
			}
			
			$ApiParamModel = Loader::model('ApiParamModel');                      //实例化模型			

			
			if($add_param){		
				$NavModel = Loader::model('NavModel');                      //实例化模型
				$nav_info = $NavModel -> navInfo($nav_id);
		    	
				foreach($add_param as $key => $value){				
					$value['app_id'] = $nav_info['app_id'];
		    		$value['nav_id'] = $nav_info['id'];	
		    		$value['api_id'] = $api_id;	

					$ret = $ApiParamModel -> addParam($api_id,$value);
					if($ret === false){
						Db::rollback();
						json_error($ApiParamModel -> getError());
					}
				}		
			}

			Db::commit();    
			$api_param = $ApiParamModel -> apiParam($api_id);
			$data['api_id'] = $api_id;
			$data['api_param'] = $api_param;
			json_success('添加成功',$data);
		}else{
			$ApiParamModel = Loader::model('ApiParamModel');                      //实例化模型			

			$encrypt = $ApiParamModel -> getEncrypt();					//加密方式
			$this -> assign('encrypt',$encrypt);
			return view('apiForm');
		}
	}

	/*
	 * api修改
	 */
	public function editApi(){
		$api_id = input('param.api_id');
		if(!$api_id){
			return;
		}

		if(request() -> isPost()){
			$api_id = input('post.api_id');
			$post = input('post.');
			$api_info = $post['api_info'];
			$add_param = @$post['add_param'] ? @$post['add_param'] : array();
			$update_param = @$post['update_param'] ? @$post['update_param'] : array();
			$del_param = @$post['del_param'] ? @$post['del_param'] : array();

			Db::startTrans();

			//更新api信息
			$ApiModel = Loader::model('ApiModel');                      //实例化模型
			$ret = $ApiModel -> updateApi($api_id,$api_info);
			if($ret === false){
				Db::rollback();
				json_error($ApiModel -> getError());
			}

			$ApiParamModel = Loader::model('ApiParamModel');                      //实例化模型			

			if($del_param){
				foreach($del_param as $key => $value){
					$ret = $ApiParamModel -> delParam($value);
					if($ret === false){
						Db::rollback();
						json_error('删除失败');
					}
				}
			}
			
			if($update_param){				
				foreach($update_param as $key => $value){
					$param_id = $key;
					$ret = $ApiParamModel -> updateParam($param_id,$value);
					if($ret === false){
						Db::rollback();
						json_error($ApiParamModel -> getError());
					}
				}				
			}

			if($add_param){
				$api_info = $ApiModel -> apiInfo($api_id);
				$nav_id = $api_info['nav_id'];

				$NavModel = Loader::model('NavModel');                      //实例化模型
				$nav_info = $NavModel -> navInfo($nav_id);
		    	
				foreach($add_param as $key => $value){				
					$value['app_id'] = $nav_info['app_id'];
		    		$value['nav_id'] = $nav_info['id'];	
		    		$value['api_id'] = $api_id;	
					$ret = $ApiParamModel -> addParam($api_id,$value);
					if($ret === false){
						Db::rollback();
						json_error($ApiParamModel -> getError());
					}
				}		
			}

			Db::commit();    
			$api_param = $ApiParamModel -> apiParam($api_id);
			$data['api_id'] = $api_id;
			$data['api_param'] = $api_param;
			json_success('添加成功',$data);
			
		}else{
			$ApiModel = Loader::model('ApiModel');                      //实例化模型
			$api_info = $ApiModel -> ApiInfo($api_id);
			$this -> assign('info',$api_info);

			$ApiParamModel = Loader::model('ApiParamModel');                      //实例化模型
			$api_param = $ApiParamModel -> apiParam($api_id);
			$this -> assign('api_param',$api_param);

			$encrypt = $ApiParamModel -> getEncrypt();					//加密方式
			$this -> assign('encrypt',$encrypt);

			return view('apiForm');
		}
	}

	//删除api
	public function delApi(){
		if(request() -> isPost()){
			$api_id = input('post.api_id');
			$ApiModel = Loader::model('ApiModel');                      //实例化模型
			if($ApiModel -> delApi($api_id)){
				json_success('删除成功');
			}else{
				json_error('删除失败');
			}			
		}		
	}
}