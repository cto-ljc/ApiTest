<?php
namespace app\api\controller;

class Index extends Base{
  protected $ignore_actions = ['getuser','getmaindata','login','reg','sendemailcode']; //不做验证的方法

  public function getUser(){
    if (!$this -> user) {
      json_success('未登录', []);
    }

    $data['user'] = $this -> user;
    json_success('用户信息', $data);
  }

  /**
   * 获取页面主信息
   */
  public function getMainData(){
    $user = $this -> user; // 登陆用户信息
    $project_id_array = $user['project_id']; // 用户拥有的项目id
    $project_id = input('project_id', 0);

    $Project = new \app\api\model\Project();
    $ApiCategory = new \app\api\model\ApiCategory();

    if (!$user) {
      json_success('未登录', []);
    }
    
    $project_list = $Project -> where('id','in',$project_id_array) -> select(); // 项目列表

    if (!$project_id && $project_list) { // 若接口没有传项目id 并且项目列表不为空 则取第一个项目的id
      $project_id = $project_list[0]['id'];
    }

    if ($project_id) {
      $category_list = $ApiCategory -> where('project_id',$project_id) -> select(); // api目录列表
    } else {
      $category_list = [];
    }    

    $data['user'] = $user;
    $data['project_list'] = $project_list or [];
    $data['category_list'] = $category_list;
    json_success('页面数据', $data);
  }

  /**
   * 登陆
   */
  public function login($email,$password){
    $User = new \app\common\model\User();
    $user = $User -> emailLogin($email,$password);

    if ($user) {
      session('user',$user); // 登陆持久化
      $return_data['user'] = $user;
      json_success('登陆成功',$return_data);
    } else {
      json_error($User -> getError());
    } 
  }

  /**
   * 注册
   */
  public function reg($email,$password,$code){
    $EmailCode = new \app\common\model\EmailCode();
    $User = new \app\common\model\User();
    $UserEmail = \think\Db::name('user_email');

    if (!$EmailCode -> checkCode($email,$code,'reg')) {
      if (env('app.env') !== 'development') {
        json_error('验证码不正确');
      }
    }

    if ($UserEmail -> where('email',$email) -> count()) {
      json_error('此邮箱已被注册!');
    }

    $data['password'] = $password;
    $res = $User -> save($data);
    if (!$res) {
      json_error('注册失败！');
    }

    $uid = $User -> uid;

    $user_email_data['uid'] = $uid;
    $user_email_data['email'] = $email;
    $UserEmail -> insert($user_email_data);

    $user = $User -> getUser($uid);
    session('user',$user); // 登陆持久化

    $return_data['user'] = $user;
    json_success('注册成功',$return_data);
  }

  public function logout() {
    session(null);
    json_success('退出登陆成功');
  }

  /**
   * 发送验证码
   */
  public function sendEmailCode($email,$type){
    $EmailCode = new \app\common\model\EmailCode();
    $res = $EmailCode -> sendEmailCode($email,'','reg');
    if ($res) {
      json_success('验证码已发送至邮箱'.$email);
    } else {
      json_error($res);
    }    
  }
}
