<?php
namespace app\home\controller;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;

class Index extends \think\Controller{
    public function index()
    {
        $this->redirect('api');
    }

    //api测试页
    public function api(){ 
        $this -> assign('title','API测试系统');
        if(cache('captcha'.request() -> ip())){
            $show_code = 'show_code';
        }else{
            $show_code = '';
        }
        
        $this -> assign('show_code',$show_code);
        return view(); 
    }

    //接口提交
    public function apiPost(){
        if(!request()->isPost()){
            return;
        }

        $msg = '';     
        $post = input('post.');   
        $url = input('post.api_url');
        $param = $post['param'];
        
        $o="";
        foreach ($param as $k=>$v)  {                         
            $o.= "$k=".urlencode($v)."&";                       
        }

        $curlPost = substr($o,0,-1);
         
        $ch = curl_init();//初始化curl  
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);

        $data = curl_exec($ch);//运行curl  
        curl_close($ch);  
        //echo($data);

        json_success('成功',$data);
    }

    public function logout(){
        session('uid',null);
        $this->redirect('login/login');
    }

    //css测试
    public function test(){
        return view();     
    }
}
