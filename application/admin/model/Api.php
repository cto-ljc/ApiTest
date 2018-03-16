<?php
namespace app\admin\model;
use app\common\model\BaseModel;
use think\Db;

class Api extends BaseModel{
	protected $encrypt_array = [
		'normal' => '不加密',
		'md5' => 'MD5加密',
		'base64' => 'base64加密',
	];

	/**
	 * 家里参数列表
	 * @return [type] [description]
	 */
	public function getEncryptArray(){
		return $this -> encrypt_array;
	}
}