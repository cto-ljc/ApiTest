<?php
namespace app\api\controller;

class ApiCategory extends Base{
  /**
   * 添加项目
   */
  public function append() {
    $user = $this -> user; // 用户信息
    $form_data = input(); //表单信息

    $ApiCategory = new \app\api\model\ApiCategory();
    $ApiCategory -> save($form_data);

    json_success('添加成功');
  }
}