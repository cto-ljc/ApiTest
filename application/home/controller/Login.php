<?php
namespace app\home\controller;
use think\Request;
use think\Loader;
use think\Session;

class Login extends \think\Controller{
	public function index(){
		$this->redirect('login');
	}

	//登陆页
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
			$this -> assign('title','用户登录');
			return view();
		}		
	}

    //登陆页
    public function emailLogin(){
        if(request() -> isPost()){
            $account = input('post.email');
            $password = input('post.password');

            $code = input('post.code');     

            

            $UserModel = Loader::model('UserModel');                      //实例化模型
            if($user_info = $UserModel -> login($account,$password,$code)){
                cache('captcha'.request() -> ip(),NULL);
                json_success('成功');
            }else{
                cache('captcha'.request() -> ip(),true,60*60*24);
                json_error($UserModel -> getError());
            }       
        }    
    }

	protected function check_online(){        
        $this -> uid = Session::get('uid');
        if($this -> uid){            
            $this->redirect('user/main');
        }       
    }

    //注册
    public function reg(){
    	if(request() -> isPost()){

    	}else{
    		
    	}
    }
}