<?php
namespace app\home\model;
use app\common\model\BaseUserModel;

use think\Model;
use think\Db;
use think\Session;

class UserModel extends BaseUserModel{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_user';

    
    /*
     * 管理员登录
     * @param string $account 账号
     * @param string $password 密码
     */
    public function login($account,$password){
    	$map['account'] = $account;    	

    	if(!$user_info = db('user') -> where($map) -> find()){
    		$this -> error = '账号或密码错误';
    		return false;
    	}

        $uid = $user_info['uid'];    	

        if($user_info['password'] != $this -> encodePWD($password,$user_info['salt'])){
            $this -> error = '账号或密码错误';
            return false;
        }

        Session::set('uid',$uid);     //session保存用户uid

    	return true;
    }

    /*
     * 获取用户信息
     * @param int $uid 用户id
     */
    public function userInfo($uid){
        $map['uid'] = $uid;
        $user_info = db('user') -> where($map) -> find();
        return $user_info;
    }  
}