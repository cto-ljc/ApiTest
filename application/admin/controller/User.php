<?php
namespace app\admin\controller;
use \think\Loader;
use \think\Request;

class User extends Common{

    //用户列表
    public function userList(){
        $page = input('param.page',1);
        $size = input('param.size',10);
        $state = input('param.state',false);

        $UserModel = Loader::model('UserModel');                      //实例化模型
        $user_list_data = $UserModel -> userList($page,$size,$state);
        $user_list  = $user_list_data['list'];
        $total = $user_list_data['total'];

        $this -> assign('list',$user_list);
        $this -> assign('total',$total);
    	return view();
    }

    //添加用户 ,刘海诚 2017年2月7日17:02:28
    public function adminAdd(){
        if (request()->isPost()) {
            $account=input("post.account");
            $password=input("post.password");

            $UserModel = Loader::model('UserModel');
            $admina_Add = $UserModel -> admina_Add($account,$password);

            if ($admina_Add) {
               json_success('添加成功');
            }else{
                json_error('添加失败');
            }
        }else{
            return view();
        }
    }

    //删除用户 刘海成 2017年2月7日17:02:48
    public function del_user(){
        $uid = input("post.uid");
        if ($uid == request() -> session("admin_uid")) {
           json_error("你不能删除自己哦");
        }
        $UserModel = Loader::model('UserModel');
        $del_user = $UserModel -> del_user($uid);
        if ($del_user) {
            json_success('删除成功');
        }else{
            json_error('删除失败');
        }
    }

    //编辑用户
    function editUser(){

    }

    function updatePassword(){
        if(request() -> isPost()){
            $uid = input('post.uid');
            $password = input('post.password');

            if(!$uid || !$password){
                json_error('参数错误',array($uid,$password));
            }

            $UserModel = Loader::model('UserModel');                      //实例化模型
            if($UserModel -> updatePassword($uid,$password)){
                json_success('修改成功');
            }else{
                json_error('修改失败');
            }            
        }else{
            return view();
        }        
    }
    
}
