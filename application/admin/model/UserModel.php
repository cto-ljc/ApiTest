<?php
namespace app\admin\model;
use app\common\model\BaseUserModel;

use think\Model;
use think\Db;
use think\Session;

class UserModel extends BaseUserModel{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_user';

    //管理员uid列表
    private $admin_uids = array('1');

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
    	// if(!in_array($uid,$this -> admin_uids)){
    	// 	$this -> error = '账号没有权限';
    	// 	return false;
    	// }

        if($user_info['rid'] > 1){
            $this -> error = '账号没有权限';
            return false;
        }

        if($user_info['password'] != $this -> encodePWD($password,$user_info['salt'])){
            $this -> error = '账号或密码错误';
            return false;
        }

        Session::set('admin_uid',$uid);     //session保存用户uid

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


    //用户列表
    public function userList($page = 1,$size = 10,$state = false){
        $map = array();
        if($state !== false){
            $map['state'] = $state;
        }
        $list = Db::name('user') -> where($map) -> page($page,$size) -> select();
        $total = Db::name('user') -> where($map) -> count();
       
        $data['list'] = $list;
        $data['total'] = $total;

        return $data;
    }

    /*
     * 管理员注册
     * @param string $account 账号
     * @param string $password 密码
     */
    public function reg($account,$password){
        $data["account"] = $account;
        $data["password"] = $password;
        $salt = $this -> getSalt();
        $data["salt"] = $salt;
        $data["state"] = 0;
        $data["reg_time"] = time();
        $data["del"] = 0;
        $reg = Db::name('user') -> insert($data);
        if ($reg) {
            return $reg;
        }else{
             $this -> error = '注册失败';
        }
    }

    /*
     * 管理员添加
     * @param string $account 账号
     * @param string $password 密码
     * @param string $uid 操作人uid
     */
    public function admina_Add($account,$password,$app_ids){

        $map["account"] = $account;
        $user = Db::name("user") -> where($map) -> select();
        if($user) {
          	json_error('该用户已经存在');
        }

        $data["account"] = $account;
        $salt = $this -> getSalt();
        $data["password"] = $this -> encodePWD($password,$salt);
        $data["salt"] = $salt;
        $data["state"] = 1;
        $data["reg_time"] = time();
        $data["del"] = 0;
        $data['app_ids'] = $app_ids;
        $data['rid'] = 2;

        $adminaAdd = Db::name('user') -> insert($data);
        if ($adminaAdd) {
            return $adminaAdd;
        }else{
            return false;
        }
    }


    /*
     * 管理员删除
     * @param int $uid 用户ID
     */

    public function del_user($uid){
        $data["state"] = 1;
        $del_user = Db::name('user') -> where('uid',$uid) -> update($data);
        if ($del_user) {
           	return true;
        }else{
            return false;
        }
    }


    /*
     * 修改用户密码
     * @param int $uid 用户id
     * @param string $password 用户密码
     */
    public function updatePassword($uid,$password){
        $salt = $this -> getSalt();
        $data['password'] = $this -> encodePWD($password,$salt);
        $data['salt'] = $salt;
        $ret = Db::name('user') -> where('uid',$uid) -> update($data);
        if($ret){
            return true;
        }else{
            $this -> error = '修改密码失败';
            return false;
        }
    }
}