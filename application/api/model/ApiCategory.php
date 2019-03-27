<?php
// +----------------------------------------------------------------------
// | 云溯码
// +----------------------------------------------------------------------
// | Author: 刘剑超 <php-ljc@qq.com>
// +----------------------------------------------------------------------

namespace app\api\model;

use think\Model;
use think\Db;

class ApiCategory extends \app\common\model\BaseModel{
  protected static function init(){    
    //添加前执行事件
    self::beforeInsert(function ($model) { 
      $model -> create_time = $model -> update_time = time();
    });
  }
}