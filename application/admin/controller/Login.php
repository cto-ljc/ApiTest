<?php
namespace app\admin\controller;
use think\Loader;
use think\Session;

class Login extends \think\Controller{


    public function login(){
    	if(request() -> isPost()){
    		$account = input('post.account');
    		$password = input('post.password');

    		$UserModel = Loader::model('UserModel');                      //实例化模型
    		if($user_info = $UserModel -> login($account,$password)){
    			json_success('成功');
    		}else{
    			json_error($UserModel -> getError());
    		}    		
    	}else{
            $this -> check_online();
    		//关闭模板
	    	//$this -> view -> engine -> layout(false);     	
	    	//$this -> view -> engine -> layout('public/base_header');
	    	$this -> assign('title','管理员登陆');
	    	return view();
    	}    	
    }

    protected function check_online(){        
        $this -> uid = Session::get('admin_uid');
        if($this -> uid){            
            $this->redirect('admin/index/main');
        }       
    }

    //注册 刘海诚 2017年2月6日18:06:39
    public function reg(){
        $account = input('post.account');
        $password = input('post.password');
        $UserModel = Loader::model('UserModel');
        if ($user_reg = $UserModel -> reg($account,$password)) {
           	json_success('注册成功');
        }else{
            json_error($UserModel -> getError());
        }
    }
}
