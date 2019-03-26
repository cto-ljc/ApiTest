<?php
// +----------------------------------------------------------------------
// | 云溯码
// +----------------------------------------------------------------------
// | Author: 刘剑超 <php-ljc@qq.com>
// +----------------------------------------------------------------------
// ! 公共模型

namespace app\common\model;

use think\Model;
use think\Db;

class BaseModel extends model{ 
  
  //-------------------------------修改器-------------------------------
  /**
   * 修改时间
   * @return [type]        [description]
   */
  public function getUpdateTimeLabelAttr($value){
    return date('Y-m-d H:i:s',$value);
  }

  /**
   * 创建时间
   * @return [type]        [description]
   */
  public function getCreateTimeLabelAttr($value){
    return date('Y-m-d H:i:s',$value);
  }
  //-------------------------------修改器-------------------------------
}