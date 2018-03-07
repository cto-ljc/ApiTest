<?php
namespace app\common\model;

use think\Model;
use think\Db;

class User extends BaseModel{
	private $sugar = ''; 

	//得到盐
    protected function getSalt() {
       $str = md5(uniqid().time().$this -> sugar ) ;
       return substr($str ,0 ,5);
    }

	//加盐加密
    protected function encodePWD($password , $salt) {
       return md5($password . $salt . $this -> sugar);
    }
}