<?php
// +---------------------------------------------------------------------- 
// | Author: 刘剑超 cto-ljc@qq.com,
// +----------------------------------------------------------------------

// 检测环境是否支持可写
define('IS_WRITE', true);
// 定义当前模块路径
define('MODULE_PATH', APP_PATH . \think\Request::instance()->module() . '/');
/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env()
{
    $items = array(
        'os'     => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
        'php'    => array('PHP版本', '5.4', '5.4+', PHP_VERSION, 'success'),
        //'upload' => array('附件上传', '不限制', '2M+', '未知', 'success'),
        //'gd'     => array('GD库', '2.0', '2.0+', '未知', 'success'),
        'disk'   => array('磁盘空间', '5M', '不限制', '未知', 'success'),
    );

    //PHP环境检测
    if (@$items['php'] && $items['php'][3] < $items['php'][1]) {
        $items['php'][4] = 'error';
        session('error', true);
    }

    //附件上传检测
    if (@$items['upload'] && @ini_get('file_uploads')) {
        $items['upload'][3] = ini_get('upload_max_filesize');
    }

    //GD库检测
    if(@$items['gd']){
        $tmp = function_exists('gd_info') ? gd_info() : array();
        if (empty($tmp['GD Version'])) {
            $items['gd'][3] = '未安装';
            $items['gd'][4] = 'error';
            session('error', true);
        } else {
            $items['gd'][3] = $tmp['GD Version'];
        }
        unset($tmp);
    }
    

    //磁盘空间检测
    if (@$items['disk'] && function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(INSTALL_APP_PATH) / (1024 * 1024)) . 'M';
    }

    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile()
{
    $items = array(
        array('dir', '可写', 'success', 'config'),
        array('dir', '可写', 'success', 'runtime'),
    );

    foreach ($items as &$val) {
        $item = INSTALL_APP_PATH . $val[3];
        if ('dir' == $val[0]) {
            if (!is_writable($item)) {            	
                if (is_dir($val[3])) {
                    $val[1] = '可读';
                    $val[2] = 'error';
                    session('error', true);
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        } else {
            if (file_exists($item)) {
                if (!is_writable($item)) {
                    $val[1] = '不可写';
                    $val[2] = 'error';
                    session('error', true);
                }
            } else {
                if (!is_writable(dirname($item))) {
                    $val[1] = '不存在';
                    $val[2] = 'error';
                    session('error', true);
                }
            }
        }
    }

    return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func()
{
    $items = array(
        array('pdo', '支持', 'success', '类'),
        array('pdo_mysql', '支持', 'success', '模块'),
        array('file_get_contents', '支持', 'success', '函数'),
        array('mb_strlen', '支持', 'success', '函数'),
    );

    foreach ($items as &$val) {
        if (('类' == $val[3] && !class_exists($val[0]))
            || ('模块' == $val[3] && !extension_loaded($val[0]))
            || ('函数' == $val[3] && !function_exists($val[0]))
        ) {
            $val[1] = '不支持';
            $val[2] = 'error';
            session('error', true);
        }
    }

    return $items;
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config, $auth)
{
    if (is_array($config)) {
        //读取配置内容
        $config_tpl = file_get_contents(MODULE_PATH . 'data/config.tpl');
        $tags_tpl = file_get_contents(MODULE_PATH . 'data/tags.tpl');
        $database = file_get_contents(MODULE_PATH . 'data/database.tpl');
        //替换配置项
        foreach ($config as $name => $value) {
            $config_tpl = str_replace("[{$name}]", $value, $config_tpl);
            $tags_tpl = str_replace("[{$name}]", $value, $tags_tpl);
            $database = str_replace("[{$name}]", $value, $database);
        } 
        
        $config_tpl = str_replace('[AUTH_KEY]', $auth, $config_tpl);

        //写入应用配置文件
        if (!IS_WRITE) {            
            return '由于您的环境不可写，请复制下面的配置文件内容覆盖到相关的配置文件，然后再登录后台。<p>' 
            . realpath(ROOT_PATH) . '/config/database.php</p>           
            <textarea name="" style="width:650px;height:125px">' . $database . '</textarea>';
        } else {       
            if ( file_put_contents(ROOT_PATH . 'config/database.php', $database)) {
                show_msg('配置文件写入成功！');
            } else {
                show_msg('配置文件写入失败！', 'error');
                session('error', true);
            }
            return true;
        }
    }
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = '')
{
    //读取SQL文件
    $sql = file_get_contents(MODULE_PATH . 'data/install.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $orginal = config('original_table_prefix'); 
    $sql     = str_replace(" `{$orginal}", " `{$prefix}", $sql);


    //开始安装
    show_msg('开始安装数据库...');
    foreach ($sql as $value) {
        $value = trim($value);
        if (empty($value)) {
            continue;
        }

        if (substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            $msg  = "创建数据表{$name}";
            if (false !== $db->execute($value)) {
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！', 'error');
                session('error', '数据表{$name}创建失败');
            }
        } else {
            $db->execute($value);
        }

    }
}

function register_administrator($db, $prefix, $admin, $auth)
{
    show_msg('开始注册管理员帐号...');
   
    
    $sql = "INSERT INTO `[PREFIX]user` (uid,account,email,password,salt,state,reg_time,login_time,reg_ip,login_ip,rid)".
    "VALUES ('1', '[NAME]', '[EMAIL]', '[PASS]','[SALE]','1', '[TIME]', '[TIME]','[IP]', '[IP]','0')";
    
    $password = md5($admin['password'].$auth);    
    $sql      = str_replace(
        array('[PREFIX]', '[NAME]', '[PASS]','[SALE]', '[EMAIL]', '[TIME]', '[IP]'),
        array($prefix, $admin['username'], $password,$auth, $admin['email'], time(), request()->ip()),
        $sql);
   
    //执行sql
    $db -> execute($sql);

    show_msg('创始人帐号注册完成！');    
}

/**
 * 更新数据表
 * @param  resource $db 数据库连接资源
 */
function update_tables($db, $prefix = '')
{
    //读取SQL文件
    $sql = file_get_contents(MODULE_PATH . 'data/update.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $sql = str_replace(" `twothink_", " `{$prefix}", $sql);

    //开始安装
    show_msg('开始升级数据库...'); 
    foreach ($sql as $value) {
        $value = trim($value);
        if (empty($value)) {
            continue;
        }

        if (substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            $msg  = "创建数据表{$name}";
            if (false !== $db->execute($value)) {
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！', 'error');
                session('error', true);
            }
        } else {
            if (substr($value, 0, 8) == 'UPDATE `') {
                $name = preg_replace("/^UPDATE `(\w+)` .*/s", "\\1", $value);
                $msg  = "更新数据表{$name}";
            } else if (substr($value, 0, 11) == 'ALTER TABLE') {
                $name = preg_replace("/^ALTER TABLE `(\w+)` .*/s", "\\1", $value);
                $msg  = "修改数据表{$name}";
            } else if (substr($value, 0, 11) == 'INSERT INTO') {
                $name = preg_replace("/^INSERT INTO `(\w+)` .*/s", "\\1", $value);
                $msg  = "写入数据表{$name}";
            }
            if (($db->execute($value)) !== false) {
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！', 'error');
                session('error', true);
            }
        }
    }
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = '')
{
    echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
    flush();
    ob_flush();
}

/**
 * 生成系统AUTH_KEY
 */
function build_auth_key()
{
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars .= '`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
    $chars = str_shuffle($chars);
    return substr($chars, 0, 40);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function user_md5($str, $key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $key);
}
