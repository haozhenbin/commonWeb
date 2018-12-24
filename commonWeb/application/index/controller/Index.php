<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use lib\escienceoauth;
use lib\IDPException;
use app\index\model\corporaTexts as corporaTextsModel;
class Index extends Controller
{
    //传入参数
    public function hello($name="is null",$id=0,$num=10){
        echo "is text.".$name." id = ".$id . " num=".$num;
        echo "<br>";
        echo config('kjy.OAUTH_INDEX_URI') ;
    }
    public function index()
    {
		require_once 'config_oauth.php';
        require_once 'class_escienceoauth.php';
        //header("https://passport.escience.cn/oauth2/authorize?response_type=code&redirect_uri=http://172.17.0.124/api/escienceAuth/escienceAuthLogin&client_id=79427&theme=embed");
       // echo 'hehe';
        //请求passport登录页面
        //echo  'good';
        //p($_GET);
        //$url = OAUTH_AUTHORIZE_URL.'?response_type=code&theme='.OAUTH_THEME.'&client_id='.OAUTH_CLIENT_ID.'&redirect_uri='.OAUTH_REDIRECT_URI;
            //header("location:$url");
            //header("location:thhp://www.baidu.com");
        if(!isset($_GET['code'])&&!isset($_GET['act'])){
            $url = config('kjy.OAUTH_AUTHORIZE_URL').'?response_type=code&theme='.config('kjy.OAUTH_THEME').'&client_id='.config('kjy.OAUTH_CLIENT_ID').'&redirect_uri='.config('kjy.OAUTH_REDIRECT_URI');
            header("location:$url");
            exit;
            //return;
        }
        //退出登录
        if($_GET['act']=='logout'){
            //退出成功后,重定向到127.0.0.1
            //此处理应先清除本地的Session,再重定向到通行证的退出连接
            header("location:".config('kjy.OAUTH_LOGOUT_URL').config('kjy.OAUTH_INDEX_URI'));
            exit;
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        //回调处理逻辑
        $provider = new EscienceOauth(array(
                'clientId'  =>  config('kjy.OAUTH_CLIENT_ID'),
                'clientSecret'  =>  config('kjy.OAUTH_CLIENT_SECRET'),
                'redirectUri'   =>  config('kjy.OAUTH_REDIRECT_URI')
        ));
        //获得AccessToken
        $userToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
        ]);
        //用户基本信息
        $userInfo = $userToken->userInfo;
        //打印用户信息,这里根据应用业务逻辑,保存到session或者,找到对应邮箱的用户,放到session里面
        var_dump($userInfo);
        echo '<a href="?act=logout">退出登录</a>';
    }

    public function escienceAuthLogin(){
        echo p($_REQUEST);
    }
    public function request(Request $rq){
        echo $rq->url();
        echo "<br>";
        echo $rq->param('name');
        echo p($rq->param());
        foreach ($rq->param() as $key => $value) {
            echo $key." ------> ".$value."<br>";
        }
        echo input('name').'<br>';
        echo p(input('get.'));
        echo p(input('get.name'));
        return json(input('get.'));
    }


    // 访问路径：http://localhost:8899/corpora/index.php/index/index/hel
    // http://localhost:8899/corpora/index.php/index/index/hel?str=good.
    public function hel($str="world."){
    	return "hello ".$str;
    }
    //模板渲染，采用视图
    public function showthml($name="show tpl"){
    	$this->assign("name",$name);
    	return $this->fetch();
    }

    //模板渲染，采用视图
    public function showss($name="show tpl",$id="123"){
        $ddd = select("select * from t_user;");
        p($ddd);
        $d =  Tbody($ddd);
        $this->assign("name",$name);
        $this->assign("ddd",$id);
        $this->assign("list",$ddd);
        $this->assign("tbd",$d);
        return $this->fetch();
    }
    //模板渲染，采用视图
    public function queryattr($tid="123"){
        return sel("select * from t_corpora_files where  id ='".$tid."'");
        
    }

    //读取数据库
    public function showdb()
    {
         return sel("select * from t_corpora_files limit 1,100;");
         //return json($data);
         //return sel("SHOW COLUMNS FROM `t_corpora_files`;");
         // $m   =   Db::query("SHOW COLUMNS FROM `t_corpora_files` ");
         // foreach ($m as $key => $value) {
         //    p($value);
         // }
         
        //return getCount("corpora_files","1=1");
        //return getjsonDs("corpora_files","1=1","*","WRITE_TIME","1","20");

    }

//     Db::table('think_user')
// ->where('name|title','like','thinkphp%')
// ->where('create_time&update_time','>',0)
// ->find();

    // Db::table('think_user')->select(function($query){$query->where('name','thinkphp')->whereOr('id','>',10);});


    // Db::query("select from think_user where id=? AND status=?",[8,1]);


    public function showdb2(){
        ini_set("memory_limit","80M");
        $rt = db('corpora_zi')->where('letternum','>',30)
        ->where("MATCH(`ytfc`) AGAINST('学习')")
        ->order('createDate')
        ->limit(10)
        ->select();
        p($rt);
        echo json_encode($rt, JSON_UNESCAPED_UNICODE);
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
    public function splittext($text = "？XXX你女子。
　XX你好。今天天气乍么样？
？太好了。我们去打球好？
　好。什么时候？
？现在去。可【以】[Zl]吗？
可【以】[Zl]。请问，我月月要去。可【以】[Zl]吗？
？太好了。他可以
【谢】[Zc]【谢】[Zc]
？有【谢】[Zc]。

" ){
        if ($text!="") {
            $arr = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY);
            // $arr = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            p($arr);
        }else{
            echo "enter null.";
        }
        

    }
    //test fenci 
    public function fctest($text = "你好" ){
        //lg("dddddddddddddddddddddddddddddd");

        $text="
　　我想找一？？我真正爱的丈【夫】[Zc]真正爱我一辈子。我希望被一？？我？佩的人物所爱。更重要的是，他生活的能度必？共，可能不？合我，因基本上不喜？有本事宣？我的丈夫是沉思的。他不？？【？】[Zc]太内向，也不？？【？】[Zc]？f？。有人？f，多做多，少？f？，他是一思想的人，另外？要有其他好的素？|有自己想法的丈夫一起生活和交？。一？？男人通常不是以他的？幼游？引我，而是他的思想。他必？和敏感，才能感受到我的真感受。
　　我希望我的丈夫一？？道德的人。但是，我不想得僵硬。
　　他一定能【？颉？[Zc]在生活中最的事情上找到快。我相信一？？幸福的生活完全不是由生活。
而目在精神上相互爱著。
　　　　　　　　

";
        $ywlist = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY);
        p($ywlist);
        foreach ($ywlist as $key => $value) {
            # code...
            $value = trimall($value) ;
            if(trim($value)!=""){
                $fc = fc_str($value);
                echo $fc->keys_slipt  .'<hr/>'.  $fc->keys_all;
            }
            
        }
        //$fc = fc_str($text);
        //echo $fc->keys_slipt  .'<hr/>'.  $fc->keys_all;
        //p($fc);
    }


    // 获取标注版语料
    public function gebzCorpusById(){
      // $posts = input('post.');
      // $id = isset($posts["id"])?$posts["id"]:"";
      // $m = Db::name("corpora_extract");
      // $map = array('id' => $id);
      // $data = $m->where($map)->select();
      $id = input('?post.id')?input('post.id'):"";   //获取单个值，一次性获取，节省内存
      $data = Db::name("corpora_extract")       //能不复制复杂数据结构，尽量不要复制          
              ->where('id',$id)  //查询语句简单，省去了临时的array结构。
              ->column('content','id');    //以后要什么字段在此增加
      return json($data);
    }
    //lg($_SERVER['DOCUMENT_ROOT'].VENDOR_PATH.'   111');
    //     if ($text!="") {
    //         //$url, $params, $method = 'GET', $header = array(), $timeout = 5
    //         $par = array('keys'=>$text);
    //         echo   http('http://localhost:8066/fc/fc',
    //                     $par,
    //                     'GET',array('title' => 'teshi' ),
    //                     10);

    //     }else{
    //         echo "enter null.";
    //     }
    // }
        

    public function split_ju(){
    	ini_set("memory_limit","80M");
        set_time_limit(0);
    	$rtstring = "";
        $tag = 0;
    	$data = Db::query("select t.CONTENT yc,e.CONTENT bzt, e.CONTENTEX bzhtml , e.OPERATOBJECT ctype , e.CORP_ORG_ID imgidlist ,t.TOTAL_COUNT wordsnum, t.id tid,e.id eid,t.forid fid,t.INPUTTIME from t_corpora_texts t , t_corpora_extract e where e.forid = t.ID and t.INPUTTIME >='2018-8-20' ORDER BY t.INPUTTIME
            ");
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
             
            if($v["tid"]=="c7d30f4d231a473ca3aad72f72627966"){
                $tag = 1;
            }
            if($tag == 1){

            echo $v["tid"]."-----". $v["INPUTTIME"]."<br>";
            $ywlist = preg_split("/(。|！|？)/",trim($v['yc']) ,-1, PREG_SPLIT_NO_EMPTY);
            $bztlist = preg_split("/(。|！|？)/",$v['bzt'] ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            $tag_ctype = $v["ctype"];
    		switch ($tag_ctype) {
    			case 'zi':
    				insertjufunc($ywlist,"zi",$v);
    				break;
                case 'ci':
                    insertjufunc($ywlist,"ci",$v);
                    break;
                case 'dy':
                    insertjufunc($ywlist,"dy",$v);
                    break;
                case 'js':
                    insertjufunc($ywlist,"js",$v);
                    break;
                case 'xc':
                    insertjufunc($ywlist,"xc",$v);
                    break;
                case 'bd':
                    insertjufunc($ywlist,"bd",$v);
                    break;
                case 'yp':
                    insertjufunc($ywlist,"yp",$v);
                    break;
                case 'yt':
                    insertjufunc($ywlist,"yt",$v);
                    break;
                case 'cx':
                    insertjufunc($ywlist,"cx",$v);
                    break;
    			default:
    				# code...
    				break;
    		}
        }
    	}
    	echo "this is OK!";
    }

    // 查看入库进度
    public function getjd($id){
        //$data = Db::query("select count(*) num from t_loaddatelog;");
        $data = sel("select count(DISTINCT(forid)) num from t_loaddatelog;");
        $cur =  $data[0]["num"];
        echo $cpl="当前进度：".round( $cur/$id * 100 , 2) . "％";
    }
    public function showjd(){
        
        //$this->assign("jd",$cpl);
        return $this->fetch();

    }
    //post测试工具
    public function testpost(){
        return $this->fetch();
    }

    //形成分类标注的语料库
    public function split_ju_bz(){
        ini_set("memory_limit","80M");
        set_time_limit(0);
        $rtstring = "";
        $tag = 0;
        // $data = Db::query("select t.CONTENT yc,e.CONTENT bzt, e.CONTENTEX bzhtml , e.OPERATOBJECT ctype , e.CORP_ORG_ID imgidlist ,t.TOTAL_COUNT wordsnum, t.id tid,e.id eid,t.forid fid,t.INPUTTIME from t_corpora_texts t , t_corpora_extract e where e.forid = t.ID  ORDER BY t.INPUTTIME
        //     ");

        $data = Db::query("select t.CONTENT yc,e.CONTENT bzt, e.CONTENTEX bzhtml , e.OPERATOBJECT ctype , e.CORP_ORG_ID imgidlist ,t.TOTAL_COUNT wordsnum, t.id tid,e.id eid,t.forid fid,t.INPUTTIME,e.DATEOFRECEIPT
from t_corpora_texts t , t_corpora_extract e where e.forid = t.ID  ORDER BY e.DATEOFRECEIPT ");
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
        //$totalnum = count($data);

        foreach ($data as $key => $v) {
            echo $v["tid"]."-----". $v["INPUTTIME"]."<br>";
            $tag = 0;
            if($v["tid"]=='bbcb8f1cd6ec4c3e97957b195e143545'){$tag = 1;}
            //$ywlist = preg_split("/(。|！|？)/",trim($v['yc']) ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            if($tag == 1){
            $ywlist = preg_split("/(。|！|？)/",$v['bzt'] ,-1, PREG_SPLIT_NO_EMPTY);
            $tag_ctype = $v["ctype"];
            switch ($tag_ctype) {
                case 'zi':
                    insertjufunc_bz($ywlist,"zi",$v);
                    break;
                case 'ci':
                    insertjufunc_bz($ywlist,"ci",$v);
                    break;
                case 'dy':
                    insertjufunc_bz($ywlist,"dy",$v);
                    break;
                case 'js':
                    insertjufunc_bz($ywlist,"js",$v);
                    break;
                case 'xc':
                    insertjufunc_bz($ywlist,"xc",$v);
                    break;
                case 'bd':
                    insertjufunc_bz($ywlist,"bd",$v);
                    break;
                case 'yp':
                    insertjufunc_bz($ywlist,"yp",$v);
                    break;
                case 'yt':
                    insertjufunc_bz($ywlist,"yt",$v);
                    break;
                case 'cx':
                    insertjufunc_bz($ywlist,"cx",$v);
                    break;
                default:
                    # code...
                    break;
            }
            }
        }
        
        echo "this is OK!";
    }
    public function getMOTHERTONGUEList(){

    }


    //传入参数
    // public function hello($name="is null",$id=0,$num=10){
    //     echo "is text.".$name." id = ".$id . " num=".$num;
    // }

    //读取数据库
    // public function showdb()
    // {
    //     return sel("select * from t_corpora_files limit 1,100;");
    //     //return getCount("corpora_files","1=1");
    //     //return getjsonDs("corpora_files","1=1","*","WRITE_TIME","1","20");
    // }

    //检索属性
    public function getattrs($col){
        return sel("select DISTINCT(".$col.") ".$col." from t_corpora_files"); 
    }

    public function getlist(){
        return sel("desc t_corpora_files ");
    }

        //getjsonDs($tablename,$selectData,$showList,$orderList,$p,$pnum)
    public function getusers($status,$email,$userid){
        $arr = array('status' => $status, 'email1' => 'liuyl@glf.com.cn','userid1'=>'liuyl' );
        return getjsonDs("user",$arr,'username,userid,email,status',"createtime",'0','10');
    }
    // public function test111(){
    //     echo substr("corpora_zi", strpos("corpora_zi","_")+1) ;
    // }

    //一般检索后台逻辑
    //一般检索后台逻辑--异步返回检索数据
    public function getzfcsample(){
        $posts = input('post.');
        $table = isset($posts["tablename"])?$posts["tablename"]:"corpora_zi";
        $cols = getcols(["t_corpora_files","t_".$table]);
        $map = array();

        if(isset($posts)){
            foreach ($posts as $key => $value) {
                $key = strtoupper($key);
                if(in_array($key,$cols,true) and trim($value)!=""){
                    $map[$key]=$value ;
                }
            }
        
        $map["tag"]="bz_".substr($table, strpos($table,"_")+1) ;
        $pcur = isset($posts["currpage"])?$posts["currpage"]:"1";
        $pnum = isset($posts["pagesize"])?$posts["pagesize"]:"10";
        $errtag = isset($posts["errtag"])?$posts["errtag"]:"";
        $keystr = isset($posts["keystr"])?$posts["keystr"]:"";
        $q = fulltextQueryStr($keystr,$errtag);
        
        //getjsonDs($table,$map,$showList,$orderList,$p,$pnum)
        $data = getjsonhsk($table,$map,"yt,fid,tid,eid,wordsnum,imgidlist,ctype,createDate,innerid, letternum,ytfc,ytfcattr",'innerid',$pcur,$pnum,$q['key'],$q['math']);
        return $data  ;
        }
    }

public function d_sample2(){
      ini_set("memory_limit","100M");
      layout('layout_no_out');
        $aurl = I("post.ae",'','strip_tags,htmlspecialchars');
        $a = urldecode( base64_decode($aurl) );
        if(strlen($a)>0){
          list($t,$col,$c,$years,$yop,$gbm,$zwname,$dengji,$fenshu,$fop,$fenshu2,$fop2,$keystr,$k1,$k2) = explode(':', $a);
          if(isset($years) and ($years!="")) { $map['date']= array($yop,$years);}
          if($fenshu!="") { if(!isset($map['zuowen'])){$map['zuowen'] = array();}  $zwrt = array($fop,$fenshu);  array_push($map['zuowen'],$zwrt);}
          if($fenshu2!="") { if(!isset($map['zuowen'])){$map['zuowen'] = array();} ;  $zwrt2 = array($fop2,$fenshu2);  array_push($map['zuowen'],$zwrt2);}
          if($dengji!="") { $map['jb']= $dengji ; }
          if($gbm!="") { $map['gbm']= $gbm ; }
          if($zwname!="") { $map['namezw']= $zwname ; }
          //查询关键字不为空
        if(($keystr !="" ) and ($keystr !="undefined")){    
          //查询key格式化
          if(($k1=='a')or($k1=='r')){
            $k2 ="";
          }
          $q = fulltextQueryStr($keystr,$k2);
          //如果选择是全部，查询类型默认选择全部
          if($k1=='a'){
            $map['_string']  = $q['math'];
            unset($map['errci']);
            unset($map['errzi']);
            unset($map['errbd']);
          }
          //如果选择是正确,要区分字还是词，如果是字排除字的错误，但是不排除标点和词，反之一样，$k2的长度来确定。
          if($k1=='r'){    
            $map['_string']  = $q['math'];
            //$map['errci']  = '0';
            //$map['errzi']  = '0';
            //$map['errbd']  = '0';
            if(mb_strlen($keystr,'utf-8')>1){
              //$map['_string'] = $q['math'];
              $map['errci']  = '0';
            }
            if(mb_strlen($keystr,'utf-8')==1){
              //$map['_string'] = $q['math'];
              $map['errzi']  = '0';
            }
          }
          //如果选择时错误,查找存在 $keystr.$k2 的关键字及可。当然如果选择是字（截取第一个字，词是两个）。
          if($k1=='w'){
              if($k2!=""){
                $map['_string'] = $q['math'];
              }else{
                if(mb_strlen($keystr,'utf-8')>1){
                  $map['_string'] = $q['math'];
                  $map['errci']  = '1';
                }
                if(mb_strlen($keystr,'utf-8')==1){
                  $map['_string'] = $q['math'];
                  $map['errzi']  = '1';
                }
              }
          }
        }
        $sx = 'zwm,gm,sse,date,namezw,koushi,zuowen,lh,rh,ch,zh,jb';   
        $sxname = '作文编码,国籍,性别,考试时间,作文题目,口试分数,作文分数,听力分数,阅读分数,综合分数,总分,证书';  
        if($c==''){
          $d['t'] = getS($t,$map,$sx,'');
          //$d['index'] = getS($t,$map,'InnerID','');
        }else{
          $d['t'] = getS($t,$map,$c.','.$sx,'');
          //$d['index'] = getS($t,$map,'InnerID','');
        }
        if($col==''){
          $d['c'] = $sxname ;
        }else{
          $d['c'] = $col.','.$sxname ;
        }
        $this->assign('d',$d); 
        return $this->display(ttex);
      }
    }

    //返回特殊检索结果，通过post调用；
    /*
        para:
        @
        

    */
    public function getzfc(){  
        $posts = input('post.');
        
        $map = array();
        $selDate = array();
        $reg = "";
        $regstr = "";
        $tag = 0;
        $keys = "";
        $reps = "";
        $table = isset($posts["tablename"])?$posts["tablename"]:"corpora_zi";
        $cols = getcols(["t_corpora_files","t_".$table]);
        if(isset($posts)){
            foreach ($posts as $key => $value) {
                $key = strtoupper($key);
                if(in_array($key,$cols,true) and trim($value)!=""){
                    $map[$key]=$value ;
                }
            }
        $map["tag"]="bz_".substr($table, strpos($table,"_")+1) ;
        $pcur = isset($posts["currpage"])?$posts["currpage"]:"1";
        $pnum = isset($posts["pagesize"])?$posts["pagesize"]:"10";
        $shou = isset($posts["shou"])?$posts["shou"]:"";
        $kaishi = isset($posts["kaishi"])?$posts["kaishi"]:"";
        $jiesu = isset($posts["jiesu"])?$posts["jiesu"]:"";
        $wei = isset($posts["wei"])?$posts["wei"]:"";
        $num = isset($posts["num"])?$posts["num"]:"";
        //lg($shou);
        if($shou !=""){
          $reg = '^'.preg_quote($shou).'(.*)';
          $keys = '(^'.preg_quote($shou).')(.*)';
          $reps = "<em>\\1</em>\\2";
          $tag = 1;
        }else{
          $keys = '(.*)(.*)';
          $reps = "\\1\\2";
        }

        if(($kaishi !="")and($jiesu !="")){
           if($num!=""){
              $n = (int)$num;
              $n = 3*$n;
              $reg .= '.*'.preg_quote($kaishi).'.{'.$n.'}'.preg_quote($jiesu).'.*';
              $keys .= '(.*)('.preg_quote($kaishi).')(.{'.$n.'})('.preg_quote($jiesu).')(.*)';
              $reps .= '\\3<em>\\4</em>\\5<em>\\6</em>\\7';
           }else{
              $reg .= '.*'.preg_quote($kaishi).'.*'.preg_quote($jiesu).'.*';
              $keys .= '(.*)('.preg_quote($kaishi).')(.*)('.preg_quote($jiesu).')(.*)';
              $reps .= '\\3<em>\\4</em>\\5<em>\\6</em>\\7';
           }
           $tag = 1;
        } 

        if(($kaishi !="")and($jiesu =="")){
          $reg .= '.*'.preg_quote($kaishi).'.*';
          $keys .= '(.*)('.preg_quote($kaishi).')(.*)(.*)(.*)';
          $reps .= '\\3<em>\\4</em>\\5\\6\\7';
          $tag = 1;

        } 

        if(($kaishi =="")and($jiesu !="")){
          $reg .= '.*'.preg_quote($jiesu).'.*';
          $keys .= '(.*)('.preg_quote($jiesu).')(.*)(.*)(.*)';
          $reps .= '\\3<em>\\4</em>\\5\\6\\7';
          $tag = 1;
        } 

        if(($jiesu =="") and ($kaishi =="")){
          $keys .= '(.*)(.*)(.*)(.*)(.*)';
          $reps .= '\\3\\4\\5\\6\\7';
        }
        if($wei !=""){$reg .=  preg_quote($wei).'...?$';
                      $keys .= '(.*)('.preg_quote($wei).')(...?$)';
                      $reps .= '\\8<em>\\9</em>\\10';
                      $tag = 1;
                    }
                    else
                    {
                      $keys .= '(.*)(.*)(.*)';
                      $reps .= '\\8\\9\\10';
                    }
      //lg($tag);
      //if($tag==1){$map['_string']=  "keykh2 regexp BINARY '".$reg."'";}
      //$regstr = "";
      if($tag==1){ $regstr  = " ytfc regexp BINARY '".$reg."'";}
      //getjsonhsk($tablename,$selectData,$showList,$orderList,$p,$pnum,$keys,$math)
      //($tablename,$selectData,$showList,$orderList,$p,$pnum,$keys,$reps)
      //lg($regstr);
      $selDate = getjsonDsReg($table,$map,"yt,fid,tid,eid,wordsnum,imgidlist,ctype,createDate,innerid, letternum,ytfc,ytfcattr",'innerid',$pcur,$pnum,$keys,$reps,$regstr ); 
      //$rptext = marktext4($keys,$reps,$vo['keykh2']);
      //$list['data'] = changeStrReg($selDate,$keys,$reps); 
      //$d['t']=gettablelist4($data['d']['ds'],$keys,$reps);
      //$d['pagelist'] = $data['d']['pagelist'];
      //$d['xz'] = base64_encode(urlencode("hskinfo:检索原句:keykh2:".$a));
      //$this->assign('data',$d);
      }
      //P($selDate);
      return json($selDate);
    }
    

    //字符串正则检索逻辑---检索结果下载
    public function d_zfc(){
      layout('layout_no_out');
      $par = urldecode( base64_decode(I("post.ae",'','strip_tags,htmlspecialchars')));
      if(strlen($par)>0){
        list($t,$col,$c,$years,$yop,$gbm,$zwname,$dengji,$fenshu,$fop,$fenshu2,$fop2,$cjtype,$shou,$wei,$kaishi,$jiesu,$num) = explode(':', $par);
        if(isset($years) and ($years!="")) { $map['date']= array($yop,$years);}
        if($fenshu!="") { if(!isset($map['zuowen'])){$map['zuowen'] = array();}  $zwrt = array($fop,$fenshu);  array_push($map['zuowen'],$zwrt);}
        if($fenshu2!="") { if(!isset($map['zuowen'])){$map['zuowen'] = array();} ;  $zwrt2 = array($fop2,$fenshu2);  array_push($map['zuowen'],$zwrt2);}
        if($dengji!="") { $map['jb']= $dengji ; }
        if($gbm!="") { $map['gbm']= $gbm ; }
        if($zwname!="") { $map['namezw']= $zwname ; }
        if($shou !=""){
          $reg = '^'.preg_quote($shou);
          $tag = 1;
        }

        if(($kaishi !="")and($jiesu !="")){
           if($num!=""){
              $n = (int)$num;
              $n = 3*$n;
              $reg .= '.*'.preg_quote($kaishi).'.{'.$n.'}'.preg_quote($jiesu).'.*';
           }else{
              $reg .= '.*'.preg_quote($kaishi).'...'.preg_quote($jiesu).'.*';
           }
           $tag = 1;
        } 

        if(($kaishi !="")and($jiesu =="")){
          $reg .= '.*'.preg_quote($kaishi).'.*';
          $tag = 1;

        } 

        if(($kaishi =="")and($jiesu !="")){
          $reg .= '.*'.preg_quote($jiesu).'.*';
          $tag = 1;
        } 

       
        if($wei !=""){$reg .=  preg_quote($wei).'...?$';
                      $tag = 1;
                    }
      
        if($tag==1){$map['_string']=  "keykh2 regexp BINARY '".$reg."'";}
        $sx = 'zwm,gm,sse,date,namezw,koushi,zuowen,lh,rh,ch,zh,jb';   
        $sxname = '作文编码,国籍,性别,考试时间,作文题目,口试分数,作文分数,听力分数,阅读分数,综合分数,总分,证书';  
        if($c==''){
          $d['t'] = getS($t,$map,$sx,'date');
        }else{
          $d['t'] = getS($t,$map,$c.','.$sx,'date');
        }
        if($col==''){
          $d['c'] = $sxname ;
        }else{
          $d['c'] = $col.','.$sxname ;
        }
        $this->assign('d',$d); 
        return $this->display(ttex);
      }
    }








            // ID               原始语料ID
            // OPERATORNAME        上传人姓名
            // MOTHERTONGUE        母语
            // LEARNINGTIME        学汉语时长
            // CHINATIME       中国国内学习时长
            // SHKTEST         是否参加SHK考试
            // SHKGRADE        SHK等级
            // LEARNINGPURPOSE     学习目的
            // LOWER_LIMIT     字数下限
            // USER_TYPE       学生类型
            // RATE            得分
            // PROJECT         课程名称
            // WRITE_TIME      写作日期
            // COST_TIME       写作时长
            // SCHOOL          目前所在学校
            // LEARNING_TYPE       学汉语方式
            // GENDER          性别
            // FILE_TYPE       文体
            // CHINESE_IS      是否华裔
            // ELSE_LAN        其他外语及程度
            // INFO            所学专业和年级
            // LEARNING_PLACE      学汉语地点
            // BEFORE_LEAN_TIME    来华前学汉语时间
            // AFTER_LEAN_TIME     来华后学汉语时间
            // BEFORE_LEAN_PLACE   来华前学汉语地点
            // AFTER_LEAN_PLACE    来华前学汉语地点
            // CORPUS_FILENAME     语料文件夹名称
            // STATUS          语料状态
            // CREATER_NAME        创建人姓名
            // CORPUS_NAME     作文标题
    public function shsample(){

    }

}


