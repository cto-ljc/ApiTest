<?php
/**
* 前端控制器-登录页面继承
*/
namespace app\common\controller;

//use think\Session;
//use think\Request;
use think\Request;

class Authentication extends Frontend
{
    protected $ignore_actions = [];
    protected $app_id;
    /**
    * 构造函数
    *  
    */
    function __construct() {
        parent::__construct();
        $request = Request::instance();
        if(!in_array($request->action(), $this->ignore_actions)) {
            $this->checkLogin();
        }
    }
    /**
    * 检查当前登陆状态
    * 
    */
    public function checkLogin(){
        $isLogin = $this->isLogin();
        if($isLogin == false){
            config('url_common_param',true);    //url规则临时修改
        }
        if($isLogin == true){
            
        }
        if($isLogin == false){
            $this -> redirect('login/index',['redirect_uri' => $this->getCurrentUrl()]);
        }
    }

}
