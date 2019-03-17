<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*
 * 自定义成功数据结构
 */
function json_success($msg = '',$data = array(),$code = 1){
  $json['time'] = time();
  $json['type'] = 'success';
  $json['status'] = true;
  $json['msg'] = $msg;
  $json['data'] = $data;
  $json['code'] = $code;
  echo json_encode($json);
  exit;
}

/*
 * 自定义失败数据结构
 */
function json_error($msg = '',$data = array(),$code = 0){
  $json['time'] = time();
  $json['type'] = 'error';
  $json['status'] = false;
  $json['msg'] = $msg;
  $json['data'] = $data;
  $json['code'] = $code;
  echo json_encode($json);
  exit;
}


/*
 * 自定义警告数据结构
 */
function json_warning($msg = '',$data = array(),$code = 404){
  $json['time'] = time();
  $json['type'] = 'warning';
  $json['status'] = false;
  $json['msg'] = $msg;
  $json['data'] = $data;
  $json['code'] = $code;
  echo json_encode($json);
  exit;
}

/**
  * 系统邮件发送函数
  * @param string $to    接收邮件者邮箱
  * @param string $name  接收邮件者名称
  * @param string $subject 邮件主题 
  * @param string $body    邮件内容
  * @param string $attachment 附件列表
  * @return boolean 
  */
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
  $config = config('THINK_EMAIL');
     
  $mail             = new \PHPMailer\PHPMailer();
  $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
  $mail->IsSMTP();  // 设定使用SMTP服务
  $mail->SMTPDebug  = 0;                      // 关闭SMTP调试功能
                                              // 1 = errors and messages
                                              // 2 = messages only
  $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
  $mail->SMTPSecure = 'ssl';                  // 使用安全协议
  $mail->Host       = $config['SMTP_HOST'];   // SMTP 服务器
  $mail->Port       = $config['SMTP_PORT'];   // SMTP服务器的端口号
  $mail->Username   = $config['SMTP_USER'];   // SMTP服务器用户名
  $mail->Password   = $config['SMTP_PASS'];   // SMTP服务器密码
  $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
  $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
  $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
  $mail->AddReplyTo($replyEmail, $replyName);
  $mail->Subject    = $subject;
  $mail->MsgHTML($body);
  $mail->AddAddress($to, $name);
  if(is_array($attachment)){ // 添加附件
    foreach ($attachment as $file){
      is_file($file) && $mail->AddAttachment($file);
    }
  }
  return $mail->Send() ? true : $mail->ErrorInfo;
}

/*
 *从纬经度得距离  copy from gx.com
 * $a array 点1
 * $b array 点2
 * return float 长度距离
 */
function distance($lat1, $lng1, $lat2, $lng2){
  $pi80 = M_PI / 180;
  $lat1 *= $pi80;
  $lng1 *= $pi80;
  $lat2 *= $pi80;
  $lng2 *= $pi80;
  $r = 6372.797; // mean radius of Earth in km
  $dlat = $lat2 - $lat1;
  $dlng = $lng2 - $lng1;
  $a = sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  $km = $r * $c;
  return $km;
}

function curl($url,$type='POST',$param = [],$header = []){
  $ch = curl_init();    

  curl_setopt($ch, CURLOPT_URL, $url);
  //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  if($type == 'POST'){
    curl_setopt($ch, CURLOPT_POST, 1);
  }
  curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);

  return $result;
}

//简单签名
function create_sign($str){
  $salt = 'lahgqjb';
  return md5($str.$salt);
}

/**
* 写入日志
* 
* @param mixed $message
* @param mixed $title
* @param mixed $filename
*/
function wlog($message, $title = '', $filename = 'order/ab/log.log'){
  if($title == ''){
    $title = "\r\n";
  }else{
    $title = "\r\n" . $title . "\r\n";
  }
  $filename = ($filename == '') ? date("Y-m-d"). '.log' : $filename;
  $filename = LOG_PATH . $filename;
  if(!file_exists(dirname($filename)) ){
    $newPath = dirname($filename);
    mkdir($newPath, 0777, true);
  }

  @error_log($title.print_r($message, true)."\r\n", 3, $filename);
}

/**
 * 图片上传
 */
function upload_img($file,$type = 'common'){
  $img_path = $type . DS;
          
  $path = config('upload_img.save_path'). $img_path;
  $info = $file -> move($path);
  if($info){    
    // 成功上传后 获取上传信息
    // 输出 jpg
    $img_data['extension'] = $info->getExtension();
    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
    $img_data['save_name'] = $info->getSaveName();
    // 输出 42a79759f284b767dfcb2a0197904287.jpg
    $img_data['file_name'] = $info->getFilename(); 

    $path = $img_path.$img_data['save_name'];
    $path = str_replace(DS,'/',$path);  //分隔符替换
    $img_data['path'] = $path;
    $img_data['url'] = get_img_url($path);
    $data['success'] = true;
    $data['img_data'] = $img_data;
    return $data;
  }else{
    // 上传失败获取错误信息
    $data['success'] = false;
    $data['msg'] = $file->getError();
    return $data;
  }
}
    
/**
 * 根据相对路径获取图片绝对路径
 * @param $path 图片路径
 */
function get_img_url($path){
  return $path != '' ? config('upload_img.domain').$path : 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550825731271&di=69d34163cee727b59471b98df6fa6b06&imgtype=0&src=http%3A%2F%2Fpic.51yuansu.com%2Fpic3%2Fcover%2F02%2F21%2F00%2F59afc61f0f3d7_610.jpg';
}

/**
 * 获取文件地址
 */
function get_file_url($path){
  return $path != '' ? '/uploads/'.$path : '';
}

/**
 * 发送短信验证码
 */
function send_sms($phone,$code,$type){

}


/**
 * 将数组树形排序 刘剑超 2016年11月9日15:00:55
 * @param unknown $list数组对象
 * @param unknown $parent顶级ID
 * @param unknown $parentname 上级ID字段名
 * @param unknown $idnameID字段名
 * @param unknown $le 每一条数据的深度
 * @return unknown
 */
function tree($list, $parent,$parentname,$idname,$le=1) {
  static $tree;
  if($le == 1){
    $tree = array();
  }
  //保证找到的元素都放在一个数组$tree内,应该是$tree在所有的tree函数都共享    

  //遍历该数组，找到parent值为当前传递进来的$parent_id;
  foreach($list as $row) {      
    //判断
    if($row[$parentname] == $parent) {
      //保存起来
      $row['le']=$le;
      $tree[] = $row;
      //依据当前所找到的分类，找到其子节点，操作相同，递归完成
      tree($list, $row[$idname],$parentname,$idname,$le+1);        
    }
  }
  return $tree;
}

/**
 * 将数组树形排序加上前缀 刘剑超 2016年11月9日15:00:55
 * @param array $list数组对象 $name 名称字段
 * @return array
 */
function build_tree_name($list,$name) {
  foreach($list as $key => $value){
    $header = '';
    for($i = 1;$i < $value['le'];$i++){
      if($i == $value['le'] - 1){
        $header = '└'.$header;
      }else{
        $header = '─'.$header;
      }           
    }
    //$header = '-';
    $list[$key][$name] = $header.$list[$key][$name];
  }   
  return $list;
}

/**
 * 正则匹配手机号码
 * @param string $str_phone
 * @return bool
 */
function verify_phone($str_phone = ''){
  if(!preg_match("/^1\d{10}$/",$str_phone)){
    return false;
  }
  return true;
}

//将XML转为array
function xml_to_array($xml){    
  //禁止引用外部xml实体
  libxml_disable_entity_loader(true);
  $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
  return $values;
}

/**
 * 删除表情字符
 * @param  string $str 字符串
 * @return string      删掉表情后的字符串
 */
function filterEmoji($str){
  $str = preg_replace_callback('/./u',function(array $match){return strlen($match[0]) >= 4 ? '':$match[0];},$str);
  return $str;
}