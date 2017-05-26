<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Session;

class ModelBase extends Model{
	protected $prefix = 'api_';
}