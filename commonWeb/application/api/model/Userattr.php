<?php
namespace app\api\model;

use think\Model;
class Userattr extends Model
{
	// 设置完整的数据表（包含前缀）
	protected $table = 't_userattr';
	//
	protected $type =[
		'birthday' => 'timestamp:Y-m-d',
	];
	
}



?>