<?php
namespace app\home\controller;
use think\Session;
use think\Db;
use think\Request;
use think\Loader;

class User extends Common{
    public function index()   {

        $user_model = new \app\home\model\User();   
        $user_info = $user_model -> userInfo($this -> uid);

        $app_model = new \app\home\model\App();
        $app_list = $app_model -> getAppList($this -> uid);  //app项目列表

        $this -> assign([
            'user_info' => $user_info,
            'app_list' => $app_list
        ]);

        return view();
    }

    /**
     * 获取app信息
     * @return [type] [description]
     */
    public function app($id){
        if(request() -> isPost()){
            $api_item_array = \think\Db::name('api_item') -> where(['app_id' => $id]) -> order('sort asc') -> select();
            $api_array = \think\Db::name('api') -> where(['app_id' => $id]) -> order('sort asc') -> select();

            json_success('请求成功',[
                'api_item_array' => $api_item_array,
                'api_array' => $api_array
            ]);
        }
    }

    /*
     * 主框架
     */
    public function main(){    
        $NavModel = Loader::model('NavModel');              //实例化模型
        $AppModel = new \app\admin\model\App();              //实例化模型
       

        $app_list = $AppModel -> getAppList($this -> uid);  //app项目列表
        $this -> assign('app_list',$app_list);              //视图参数

        $is_me_app = false;
        foreach ($app_list as $key => $value) {
            if($value['id'] == $this -> app_id){
                $is_me_app = true;
            }
        }

        if(!$is_me_app){
            if($app_list){
                $this -> app_id = $app_list[0]['id'];
                Session::set('app_id',$this -> app_id);
            }else{
                Session::set('app_id',null);
                $this -> app_id = 0;
            }           
        }

        $nav = $NavModel -> getNav($this -> app_id);        //左边接口目录
        $this -> assign('nav',$nav);                        //视图参数


        $app_info = $AppModel -> appInfo($this -> app_id);  //app项目详情
        $this -> assign('app_info',$app_info);              //视图参数

        $UserModel = new \app\admin\model\User();   
        $user_info = $UserModel -> userInfo($this -> uid);

        $this -> assign('user_info',$user_info);
        
        $this -> assign('title','API测试系统');
    	return view();                                      //渲染视图
    }

    public function changeApp(){
        if(!request()->isPost()){
            return;
        }

        $app_id = input('post.app_id'); 
        Session::set('app_id',$app_id);
        json_success('',array('app_id' => $app_id));
    }



    //欢迎页
    public function welcome(){        
    	return view();
    }

    //api测试页
    public function api($api_id = 0){    
        $api = Db::name('api') -> where(['id' => $api_id]) -> find();                 
        $api_param = Db::name('api_param') -> where(['api_id' => $api_id]) -> order('sort','asc') -> select(); 
        $app = Db::name('app') -> where(['id' => $api['app_id']]) -> find();

        $this -> assign([
            'api' => $api,
            'api_param' => $api_param,
            'app' => $app
        ]);
        
        return view();        
        
    }

    //接口提交
    public function apiPost(){
        if(!request()->isPost()){
            return;
        }

        $msg = '';        

        $param = input('post.');

        $o="";
        foreach ($param as $k=>$v)  {
            if($k!="api_url"){                
               $o.= "$k=".urlencode($v)."&"; 
            }           
        }

        $curlPost = substr($o,0,-1);
        $url = $param['api_url']; 
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
        session(null);
        $this->redirect('index/index');
    }

    //css测试
    public function test(){
        return view();     
    }
}
