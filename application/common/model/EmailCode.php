<?php
// +----------------------------------------------------------------------
// | 云溯码
// +----------------------------------------------------------------------
// | Author: 刘剑超 <php-ljc@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;
use think\Db;

class EmailCode extends \app\common\model\BaseModel{
  protected static function init(){
    self::beforeInsert(function ($model) {
      $model -> create_time = time();      
    });
  }

	/*
   * 发送邮箱验证码
   */
  public function sendEmailCode($email,$code='',$type='',$content=''){
    if (!$code) {
      $code = rand(1000,9999);
    }

    if($content){
      $content = str_replace("【code】",$code,$content);
    }else{
      $content = '您的验证码为'.$code.'，请不要将这个号码泄露给他人，验证码有效时间为五分钟。';
    }
    
    $ret = think_send_mail($email,'MX-API','MX-API',$content);
    if($ret === true){
      $data['email'] = $email;
      $data['code'] = $code;
      $data['type'] = $type;
      $data['ip'] = request() -> ip();
      $this -> save($data);
      return true;
    }else{
      return $ret;
    }
  }

  /*
   * 检查验证码
   */
  public function checkCode($email,$code,$type){
    $timeout = 60 * 5;

    $map['email'] = $email;
    $map['type'] = $type;

    $email_log = $this -> where($map) -> order('id desc') -> find();
    if(!$email_log){
      return false;
    }
    if($email_log['create_time'] < time() - $timeout){
      return false;
    }
    if($email_log['code'] != $code){
      return false;
    }
    $this -> where('id',$email_log['id']) -> setField('status',1);
    return true;
  }
}