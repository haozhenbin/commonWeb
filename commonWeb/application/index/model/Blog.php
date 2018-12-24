<?php
namespace app\index\model;

use think\Model;
class Blog extends Model
{
	protected $autoWriteTimestamp = true;
	protected $insert = [
		'status' => 1,
	];
	protected $field = [
		'id'			=> 'int',
		'create_time'	=> 'int',
		'update_time'	=> 'int',
		'bname','title','content',
	];
	// 设置完整的数据表（包含前缀）
	protected $table = 't_blog';
}



?>