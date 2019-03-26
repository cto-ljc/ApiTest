<?php
// +----------------------------------------------------------------------
// | 云溯码
// +----------------------------------------------------------------------
// | Author: 刘剑超 <php-ljc@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;
use think\Db;

class User extends BaseModel{   
  protected $pk = 'uid';
  private $sugar = '';

  protected $type = [
    'project_id'    =>  'json',
  ];

  protected $status_label = [
      '0' => '禁用',
      '1' => '启用'
  ];

  protected static function init(){    
    //添加前执行事件
    self::beforeInsert(function ($model) {      
      $password = isset($model['password']) ? $model['password'] : 'mx-api';
      $salt = $model -> getSalt(); // 得到盐

      $model -> salt = $salt;
      $model -> password = $model -> encodePassword($password , $salt); // 加盐加密
      $model -> create_time = time();
    });
  }

  /**
   * 获取用户信息
   */
  public function getUser($uid){
    $field = 'u.uid,u.status,u.create_time,u.project_id,e.email';

    $user = $this -> alias('u') -> where('u.uid',$uid)
     -> join('user_email e','e.uid = u.uid')
     -> field($field)
     -> find();

    return $user;
  }

  /**
   * 邮箱登陆
   */
  public function emailLogin($email,$password){
    $user = $this -> alias('u') -> where('e.email',$email)
     -> join('user_email e','e.uid = u.uid')
     -> find();

    if (!$user) {
      $this -> error = '用户名或密码错误';
      return false;
    }

    if ($user['password'] != $this -> encodePassword($password,$user['salt'])) {
      $this -> error = '用户名或密码错误';
      return false;
    }

    $user = $this -> getUser($user['uid']);

    return $user;
  }

  //得到盐
  protected function getSalt() {
    $str = md5(uniqid().time().$this -> sugar ) ;
    return substr($str ,0 ,5);
  }

  //加盐加密
  public function encodePassword($password , $salt) {
     return md5($password . $salt . $this -> sugar);
  } 

  /**
   * 修改密码
   * @param $uid
   * @param $password
   * @return int|string
   */
  public function savePassword($uid, $password){
      $where = ['uid' => $uid];
      $salt = $this->getSalt();
      $password = $this->encodePassword($password, $salt);
      $data = ['salt' => $salt, 'password' => $password];
      $result = $this->where($where)->update($data);
      return $result;
  }

}