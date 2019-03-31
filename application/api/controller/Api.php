<?php
namespace app\api\controller;

class Api extends Base{
  protected $ignore_actions = ['commcurlrequest'];
  /**
   * 添加项目
   */
  public function append() {
    $form_data = input(); //表单信息

    $Api = new \app\api\model\Api();

    $Api -> save($form_data);
    $id = $Api -> id;

    $form_data['id'] = $id;

    $api = $Api -> where('id',$id) -> find();
    $data['api'] = $api;

    json_success('添加成功',$data);
  }

  /**
   * 编辑接口
   */
  public function update() {
    $form_data = input(); //表单信息
    $id = input('id');

    $Api = new \app\api\model\Api();

    $Api -> save($form_data,['id',$id]);

    $data['api'] = $form_data;

    json_success('修改成功',$data);
  }

  /**
   * 删除接口
   */
  public function delete() {
    $id = input('id');

    $Api = new \app\api\model\Api();

    $Api -> where('id',$id) -> delete();

    json_success('删除成功');
  }

  function commCurlRequest($url='',$postString='',$httpHeader='') {
    $url = 'http://192.168.1.8/admin/admin/login';
    $postString = '';
    $httpHeader = [];
    $httpHeader['Host'] = 'yunsuma.localhost';

    $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
    curl_setopt($ch,CURLOPT_POSTFIELDS,$postString); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
    curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']); 
    if(!empty($httpHeader) && is_array($httpHeader)) {
      dump($httpHeader);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader); 
    } 
    $data = curl_exec($ch); 
    $info = curl_getinfo($ch); //var_dump($info); 
    curl_close($ch); 
    return $data; 
  } 
}