<?php
namespace app\admin\controller;
use \think\Session;
use \think\Loader;

class Index extends Common{   
    public function index(){
        //用户信息
        $UserModel = new \app\admin\model\User();                      //实例化模型
        $user_info = $UserModel -> userInfo($this -> uid);
        $this -> assign('user_info',$user_info);

    	return view();
    }

    public function logout(){
        session('admin_uid',null);
        $this->redirect('admin/login/login');
    }

    public function welcome(){
    	
    }

    //帮助页面
    public function help(){
        return view();
    }

    //升级程序
    public function update(){


    }
}
