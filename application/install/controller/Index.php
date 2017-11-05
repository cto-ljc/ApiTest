<?php

namespace app\install\controller;
use think\Db;
use com\Storage;

class Index extends \think\Controller{
   
    //安装首页
    public function index(){        
        echo config('default_controller');
    }

    public function test(){
    	echo 'install test';
    }
}