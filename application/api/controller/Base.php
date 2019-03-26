<?php
namespace app\api\controller;

class Base extends \think\Controller{
  protected $ignore_actions = []; //不做验证的方法
  protected $user;
  function __construct() {
    parent::__construct();  

    $user = session('user');
    $this -> user = $user ? $user : [];

    if(!in_array(request() -> action(), $this->ignore_actions)) {
      if (!$this -> user) {
        json_error('请重新登陆',[],10000);
      }
    } 
  }
}
