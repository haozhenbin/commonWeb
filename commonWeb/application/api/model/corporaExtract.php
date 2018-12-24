<?php
namespace app\api\model;

use think\Model;
class corporaExtract extends Model
{
	// 设置完整的数据表（包含前缀）
	protected $table = 't_corpora_extract';
	//
	protected $type = [
		'DATEOFRECEIPT' => 'timestamp:Y-m-d',
	];
	
}



?>