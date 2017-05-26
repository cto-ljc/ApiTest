<?php
namespace app\common\model;

use think\Model;
use think\Db;

class BaseModel extends Model{
	protected $prefix = 'app_';

	//自定义初始化
    protected function initialize() {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this -> table = $this -> prefix . $this -> table;
    }

}