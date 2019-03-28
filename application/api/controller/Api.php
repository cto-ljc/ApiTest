<?php
namespace app\api\controller;

class Api extends Base{
  /**
   * 添加项目
   */
  public function append() {
    $form_data = input(); //表单信息

    $Api = new \app\api\model\Api();

    $Api -> save($form_data);
    $id = $Api -> id;

    $form_data['id'] = $id;
    $data['api'] = $form_data;

    json_success('添加成功',$data);
  }
}