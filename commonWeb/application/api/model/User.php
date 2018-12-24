<?php
namespace app\api\model;

use think\Model;
class User extends Model
{
	// 设置完整的数据表（包含前缀）
	protected $table = 't_user';
	//
	public function userattr(){
		return $this->hasOne('userattr');
	}
}



?>