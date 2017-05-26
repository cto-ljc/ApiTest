<?php
#刘剑超 QQ:417112094
namespace app\admin\controller;
use think\Session;
use think\Request;

class Common extends \think\Controller{
	protected $app_id;
	protected $uid;
	//魔术方法 
    function __construct() {
        parent::__construct();	

        $this -> checkOnline();

        $this -> assign('title',Request::instance() -> action());       
    }

    protected function checkOnline(){        
        $this -> uid = Session::get('admin_uid');
        if(!$this -> uid){            
            $this->redirect('admin/login/login');
        }    	
    }
}