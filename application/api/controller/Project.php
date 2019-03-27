<?php
namespace app\api\controller;

class Project extends Base{
  /**
   * 添加项目
   */
  public function append() {
    $user = $this -> user; // 用户信息
    $form_data = input(); //表单信息

    $Project = new \app\api\model\Project();
    $User = new \app\api\model\User();

    $form_data['uid'] = $user['uid'];
    $Project -> save($form_data);

    // 保存用户项目信息
    $project_id = $Project -> id; // 项目id
    $user_project_id = $User -> where('uid',$user['uid']) -> value('project_id');
    $user_project_id = json_decode($user_project_id);
    $user_project_id[] = $project_id;
    $User -> save([
      'project_id' => $user_project_id
    ],['uid' => $user['uid']]);

    // 更新用户信息
    $user = $User -> getUser($user['uid']);
    session('user',$user);

    json_success('添加成功');
  }
}