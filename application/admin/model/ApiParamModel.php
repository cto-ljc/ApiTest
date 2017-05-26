<?php
namespace app\admin\model;
use app\common\model\BaseModel;
use think\Db;

class ApiParamModel extends BaseModel{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'api_param';

    private $encrypt = array('normal' => '不加密','md5' => 'md5加密','base64' => 'base64加密');					//加密方式

    public function apiParam($api_id){
    	$map['api_id'] = $api_id;
		$api_param = Db::name('api_param') -> where($map) -> order('sort','asc') -> select();
		return $api_param;
    }

    /*
     * 获取加密方式
     */
    public function getEncrypt(){
    	return $this -> encrypt;
    }

    /*
	 * 添加api参数
	 * @param int $api_id
	 * return bool 修改结果
	 */
    public function addParam($api_id,$data){ 	
    	$map['api_id'] = $api_id;
    	$map['name'] = $data['name'];
    	if(!Db::name('api_param') -> where($map) -> count()){
    		Db::name('api_param') -> insert($data);
    	}else{
    		$this -> error = '参数已存在';
    		return false;
    	}
    }

    /*
	 * 修改api参数
	 * @param int $param_id
	 * return bool 修改结果
	 */
    public function updateParam($param_id,$data){   
        $param_info = Db::name('api_param') -> where('id',$param_id) -> find();
        
    	$map['name'] = $data['name'];
        $map['api_id'] = $param_info['api_id'];
        $map['id'] = ['NEQ',$param_id];

    	if(!Db::name('api_param') -> where($map) -> count()){
    		Db::name('api_param') -> where('id',$param_id) -> update($data);
    	}else{
    		$this -> error = '参数已存在';
    		return false;
    	}    	
    } 

    /*
	 * 删除api参数
	 * @param int $param_id
	 * return bool 删除结果
	 */
    public function delParam($param_id){
    	return Db::name('api_param') -> where('id',$param_id) -> delete();
    }

}