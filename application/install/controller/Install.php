<?php

namespace app\install\controller;
use think\Db;
use com\Storage;

class Install extends \think\Controller{

    protected function _initialize(){
        if(is_file(ROOT_PATH . 'config/database.php') && request() -> action() != 'complete'){
            $url = 'http://'. $_SERVER['SERVER_NAME'] . rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
            $this->error('已经成功安装了api_test，请不要重复安装!',$url);
        }
    }

    //安装首页
    public function index(){        
        return $this->fetch();
    }


    //安装第一步，检测运行所需的环境设置
    public function step1(){
        session('error', false);

        //环境检测
        $env = check_env();
        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);
        return $this->fetch();
    }

    //安装第二步，创建数据库
    public function step2($db = null, $admin = null){
        if($this->request->isPost()){
            //检测管理员信息
            if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
                $this->error('请填写完整管理员信息');
            } else if($admin[1] != $admin[2]){
                $this->error('确认密码和密码不一致');
            } else {
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email'])
                = $admin;
                //缓存管理员信息
                session('admin_info', $info);
            }

            //检测数据库配置
            if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
                $this->error('请填写完整的数据库配置');
            } else {
                $DB = array();
                list($DB['type'], $DB['hostname'], $DB['database'], $DB['username'], $DB['password'],
                     $DB['hostport'], $DB['prefix']) = $db;
                //缓存数据库配置
                session('db_config', $DB);

                //创建数据库
                $dbname = $DB['database'];
                unset($DB['database']);
                $db  = \think\Db::connect($DB);//Db::getInstance($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
                $db->execute($sql) !== false || $this->error($db->getError());
            }

            //跳转到数据库安装页面
            $this->redirect('step3');
        } else {        
            session('update',null);    
            if(session('update')){
                session('step', 2);
                return $this->fetch('update');
            }else{                
                session('error') && $this->error('环境检测没有通过，请调整环境后重试！');

                $step = session('step');
                if($step != 1 && $step != 2){
                    $this->redirect('step1');
                }

                session('step', 2);
                return $this->fetch();
            }
        }
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){       

        if(session('step') != 2){
            $this->redirect('step2');
        }

        echo $this->fetch();

        if(session('update')){
            $db = \think\Db::connect();
            //更新数据表
            update_tables($db, config('database.prefix'));
        }else{
            session('error',null);
       
            //连接数据库
            $dbconfig = session('db_config');
            $db = Db::connect($dbconfig);
            
            //创建数据表
            create_tables($db, $dbconfig['prefix']);
            //注册创始人帐号
            $auth  = substr(md5(time()),5,6);
            
            $admin = session('admin_info');
            register_administrator($db, $dbconfig['prefix'], $admin, $auth);
            
            //创建配置文件
            $conf   =   write_config($dbconfig, $auth);
            session('config_file',$conf);           
        }

        if(session('error')){
            dump('baocu');
         	show_msg(session('error'));
        } else {     
            $this->success('安装成功', 'complete');
        }
    }

    //安装完成
    public function complete(){

        // 写入安装锁定文件 
        // file_put_contents('./static/data/install.lock', 'lock'); 
        // if(!session('update')){
        //     //创建配置文件
        //     $this->assign('info',session('config_file'));
        // }
        session('step', null);
        session('error', null);
        session('update',null);
        return $this->fetch();
    }
}
