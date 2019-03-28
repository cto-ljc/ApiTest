<?php
namespace app\api\controller;

class ApiCategory extends Base{
  /**
   * 添加项目
   */
  public function append() {
    $form_data = input(); //表单信息

    $ApiCategory = new \app\api\model\ApiCategory();
    $ApiCategory -> save($form_data);

    $form_data['id'] = $ApiCategory -> id;

    $return_data['category'] = $form_data;
    json_success('添加成功',$return_data);
  }

  /**
   * 更新项目
   */
  public function update(){
    $form_data = input(); //表单信息
    $id = input('id');

    $ApiCategory = new \app\api\model\ApiCategory();
    $ApiCategory -> save($form_data,['id',$id]);

    $return_data['category'] = $form_data;
    json_success('修改成功',$return_data);
  }

  /**
   * 删除栏目
   */
  public function batchDelete($id_array) {
    $ApiCategory = new \app\api\model\ApiCategory();
    $ApiCategory -> where('id', 'in', $id_array) -> delete();
    json_success('删除成功');
  }
}