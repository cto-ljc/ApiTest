<?php
namespace app\home\model;

use think\Model;
use think\Db;
use think\Session;

class UserModel extends \app\common\model\User{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_user';

    
    /*
     * 管理员登录
     * @param string $account 账号
     * @param string $password 密码
     */
    public function login($account,$password,$code){
    	$map['account'] = $account;    	

    	if(!$user_info = db('user') -> where($map) -> find()){
    		$this -> error = '账号或密码错误';
    		return false;
    	}

        if((cache('captcha'.request() -> ip()) || $user_info['error_count'] != 0) && !captcha_check($code)){
            $this -> error = '验证码错误';
            return false;
        }

        $uid = $user_info['uid'];    	

        if($user_info['password'] != $this -> encodePWD($password,$user_info['salt'])){
            $this -> error = '账号或密码错误';
            db('user') -> where($map) -> setInc('error_count');;
            return false;
        }

        //更新登录信息
        $update_data['error_count'] = 0;
        $update_data['login_ip'] = request() -> ip();
        $update_data['login_time'] = time();
        db('user') -> where($map) -> update($update_data);        

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