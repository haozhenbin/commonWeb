<?php
namespace app\api\controller\v1;
use app\api\model;
use think\Db;
use think\Request;
use think\Controller;
use app\api\model\User as userModel;
class User
{
    public function index()
    {
        return "Hello World! API V1 ";
    }

    //读取数据库
    public function read($id=0)
    {
        //ini_set("memory_limit","80M");
    	//$data = Db::name("corpora_texts")->select();
        $user = userModel::get($id);
        if($user){
            return json($user);
        }else{
            return json(['error'=>'用户不存在'],404);
        }
    }
    //split函数
    /*
        flags—flags 可以是任何下面标记的组合(以位或运算 | 组合)：
        PREG_SPLIT_NO_EMPTY 
        如果这个标记被设置， preg_split() 将进返回分隔后的非空部分。
        PREG_SPLIT_DELIM_CAPTURE 
        如果这个标记设置了，用于分隔的模式中的括号表达式将被捕获并返回。
        PREG_SPLIT_OFFSET_CAPTURE 
        如果这个标记被设置, 对于每一个出现的匹配返回时将会附加字符串偏移量. 注意：这将会改变返回数组中的每一个元素, 使其每个元素成为一个由第0 个元素为分隔后的子串，第1个元素为该子串在subject 中的偏移量组成的数组。

    */
    public function splittext($text = "我是中国人。。。我爱我的祖国！你来自哪个国家？我来自日本。" ){
        if ($text!="") {
            $arr = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            echo print_r($arr);
        }else{
            echo "enter null.";
        }
        

    }
    //test fenci 
    public function fctest($text = "我是中国人。。。我爱我的祖国！你来自哪个国家？我来自日本。" ){
        lg("dddddddddddddddddddddddddddddd");
        //lg($_SERVER['DOCUMENT_ROOT'].VENDOR_PATH.'   111');
        if ($text!="") {
            //$url, $params, $method = 'GET', $header = array(), $timeout = 5
            $par = array('keys'=>$text);
            echo http('http://localhost:8066/fc/fc',
                        $par,
                        'GET',array('title' => 'teshi' ),
                        10);
            //echo fctoAttr($text); 
        }else{
            echo "enter null.";
        }
    }
        

    public function split_ju(){
    	ini_set("memory_limit","80M");
    	$rtstring = "";
    	$data = Db::query('select t.CONTENT yc,e.CONTENT bzt, e.CONTENTEX bzhtml , e.OPERATOBJECT ctype , e.CORP_ORG_ID imgidlist ,t.TOTAL_COUNT wordsnum, t.id tid,e.id eid,t.forid fid from t_corpora_texts t , t_corpora_extract e where e.forid = t.ID LIMIT 0,10; ');
    	//$data = Db::name("corpora_texts")->select();
  		//zi: '字',
		// ci: '词',
		// dy: '短语',
		// js: '句',
		// xc: '修辞',
		// bd: '标点',
		// yp: '语篇',
		// yt: '语体',
		// cx: '词性'
    	foreach ($data as $key => $v) {
    		switch ($v["ctype"]) {
    			case 'zi':
    				//$data = "insert into t_corpora_zi (yt,bzt,bzhtml,fid,tid,eid,wordsnum,imgidlist)values()";
    				$ywlist = preg_split("/(。|！|？)/",$v['yt'] ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

    				$bztlist = preg_split("/(。|！|？)/",$v['bzt'] ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
                    foreach ($ywlist as $k => $val) {
                        $data = [ 
                                'yt' => $val, 
                                'ytfc' => fc_str($val),
                                'bzt' => fctoAttr($val), 
                                'bztfc' => '',
                                'bzhtml' => '', 
                                'fid' => $v['fid'], 
                                'tid' => $v['tid'], 
                                'eid' => $v['eid'], 
                                'wordsnum' => mb_strlen($val,"UTF8"), 
                                'seq' => $k, 
                                'tag' => 'yw', 
                                'imgidlist' => $v['imgidlist']
                            ];
                    }
    				
    				Db::name('corpara_zi')->insert($data);
    				break;
    			default:
    				# code...
    				break;
    		}
    	}
    	
    	$this->assign("rt",$data);
    	return $this->fetch();
    }



}


