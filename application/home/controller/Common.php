<?php
namespace app\home\controller;
use think\Session;
use think\Request;

class Common extends \think\Controller{
	protected $app_id;
	protected $uid;
	//魔术方法 
    function __construct() {die;
        parent::__construct();	

        $this -> app_id = Session::get('app_id');

        $this -> checkOnline();

        $this -> assign('title',Request::instance() -> action());             
    }

    protected function checkOnline(){        
        $this -> uid = Session::get('uid');
        if(!$this -> uid){            
            $this->redirect('index/api');
        }       
    }
}