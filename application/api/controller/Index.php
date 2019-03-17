<?php
namespace app\api\controller;

class Index extends Base{
  public function getUserInfo(){
    if (!$this -> user) {
      json_success('未登录', []);
    }

    $data['user'] = $this -> user;
    json_success('用户信息', $data);
  }
}
