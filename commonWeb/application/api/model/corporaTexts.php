<?php
namespace app\api\model;

use think\Model;
class corporaTexts extends Model
{
	// 设置完整的数据表（包含前缀）
	protected $table = 't_corpora_texts';
	//
	public funtion profile(){
		return $this->has_many('t_corpora_extract');
	}
}



?>