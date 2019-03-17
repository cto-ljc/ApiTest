<?php
namespace app\api\controller;

class Base extends \think\Controller{
  protected $user;
  function __construct() {
    parent::__construct();  

    $user = session('user');
    $this -> user = $user ? $user : [];
  }
}
