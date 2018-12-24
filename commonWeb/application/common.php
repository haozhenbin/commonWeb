<?php
use Think\Log;
use Think\Controller;
use Think\Model;
use think\Db;
use Think\Config;
use think\Validate;
use PHPMailer\PHPMailer;

function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}

//写日志
function lg($str,$level='info'){
    Log::record("【手动输出】：".$str,$level);
    Log::save();
}

function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = '';// "-"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}


function guidNEW(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = '';// "-"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4);
                //.$hyphen
                //.substr($charid,20,12);
        return $uuid;
    }
}
function utf8_strlen($string = null) {
  // 将字符串分解为单元
  preg_match_all("/./us", $string, $match);
  // 返回单元个数
  return count($match[0]);
}

function checkinput($colArr,$re){
    $rule = array();
    $message = array();
    foreach ($colArr as $key => $value) {
        //$rule[] = [$value=>'require'];
        $rule = array_merge($rule,[$value=>'require']);
        $message = array_merge($message,[$value.'.require'=>$value.'必须提供']);
        //$message[]=[$value.'.require'=>$value.'必须提供'];
    }
    //p($rule); 
    $validate = new Validate($rule,$message);
    $result = $validate->check($re);
    if(!$result){
      return ['ID' => '-1','msg'=>'校验失败:'.$validate->getError()];
    }else{
      return ['ID' => '1','msg'=>'校验成功。'];
    }
}
function getDsQuery($tablename,$selectData,$showList,$orderList,$p,$pnum,$qry){
    $list['data'] = Db::name($tablename)
        ->where($selectData)
        ->where($qry)
        ->field($showList)
        ->order($orderList)
        ->page($p,$pnum)
        ->select();
    $list['count'] = Db::name($tablename)->where($selectData)->where($qry)->count('*');
    return $list  ;
}

function gettableRead($tablename,$selectData,$showlist='*'){
    $list =   Db::table($tablename)
              ->where($selectData)
              ->field($showlist)
              ->select();
    return $list  ;
}

function gettableReadHezp($tablename,$selectData,$showlist='*'){
    $list =   Db::table($tablename)
              ->where($selectData)
              ->field($showlist)
              ->find();
    return $list  ;
}

function update($table,$updata,$whdata=null){
  $updata = gettablecols($table,$updata);
    if($whdata!=null){
      $t = Db::table($table) 
          ->where($whdata)->update($updata);
    }else{
      $t = Db::table($table) 
          ->update($updata);
    }
    return $t;
}

//插入数据
function ins($table,$post){
  $data = gettablecols($table,$post);
  return Db::table($table)->insert($data);
}

function getDataToJson($tablename,$map,$showList='*',$orderList,$page,$pagesize){
    $m   =   Db::name($tablename);
    $list['data'] = $m->where($map)->field($showList)->order($orderList)->page($page,$pagesize)->select();
    $list['count'] = $m->where($map)->count('*');
    return json($list)  ;
}

function getDataMapsToJson($tablename,$map,$showList='*',$orderList,$page,$pagesize,$map2,$keystr=''){
    $m   =   Db::name($tablename);
    if($keystr==''){
      $list['data'] = $m->where($map)->where($map2)->field($showList)->order($orderList)->page($page,$pagesize)->select();
      $list['count'] = $m->where($map)->where($map2)->count('*');
    }else{
      $list['data'] = $m->where($map)->where($map2)
                  ->where('name|maker_name|t_assets.asset_num|person|deparment','like','%'.$keystr.'%')
                  ->field($showList)
                  ->order($orderList)
                  ->page($page,$pagesize)
                  ->select();
      $list['count'] = $m->where($map)->where($map2)
                  ->where('name|maker_name|t_assets.asset_num|person|deparment','like','%'.$keystr.'%')
                  ->count('*');
    }    
    return json($list)  ;
}

//返回分页数据集json数据
function getjsonDs($tablename,$selectData,$showList,$orderList,$p,$pnum){
    $m   =   Db::name($tablename)->strict(true);
    $list['data'] = $m->where($selectData)->field($showList)->order($orderList)->page($p,$pnum)->select();
    $list['count'] = $m->where($selectData)->count('*');
    return json($list)  ;
}

//返回分页数据集json数据
function getjsonDsReg($tablename,$selectData,$showList,$orderList,$p,$pnum,$keys,$reps,$reg){
    $list = array();
    $selDate   =   Db::view($tablename,$showList)
                      ->view("corpora_files","*",$tablename.".fid=corpora_files.ID")
                      ->where($selectData)
                      ->where($reg)
                      ->order($orderList)
                      ->page($p,$pnum)
                      ->select();
    $list['data'] = changeStrReg($selDate,$keys,$reps);
    $list['count'] = Db::view($tablename,"InnerID")
                      ->view("corpora_files","ID",$tablename.".fid=corpora_files.ID")
                      ->where($selectData)
                      ->where($reg)
                      ->count('*');
    return $list ;
}
//返回json数据，增加分页和数据转换(高亮显示)；
function getjsonhsk($tablename,$selectData,$showList,$orderList,$p,$pnum,$keys,$math){
    $selDate   =   Db::view($tablename,$showList)
                      ->view("corpora_files","*",$tablename.".fid=corpora_files.ID")
                      ->where($math)
                      ->where($selectData)
                      ->order($orderList)
                      ->page($p,$pnum)
                      ->select();
    $list['data'] = changeStr($selDate,$keys);
    $list['count'] = Db::view($tablename,"InnerID")
                      ->view("corpora_files","ID",$tablename.".fid=corpora_files.ID")
                      ->where($selectData)
                      ->where($math)->count('*');
    return json($list);
}

function getdsJson($tablename,$map,$showList='*',$orderList,$page,$pagesize){
    $m   =   Db::name($tablename);
    if(isset($map['plan_name'])){
      $str =$map['plan_name'];
      unset($map['plan_name']);
      $list['data'] = $m->where($map)->where('plan_name','like','%'.$str.'%')->field($showList)->order($orderList)->page($page,$pagesize)->select();
      $list['count'] = $m->where($map)->where('plan_name','like','%'.$str.'%')->count('*');
    }else{
      $list['data'] = $m->where($map)
                  ->field($showList)
                  ->order($orderList)
                  ->page($page,$pagesize)
                  ->select();
      $list['count'] = $m->where($map)
                  ->count('*');
    }    
    return json($list)  ;
}

function getDataToJsonLike($tablename,$map,$showList='*',$orderList,$page,$pagesize,$likecols,$keystr=''){
    $m   =   Db::name($tablename);
    if($keystr==''){
      $list['data'] = $m->where($map)->field($showList)->order($orderList)->page($page,$pagesize)->select();
      $list['count'] = $m->where($map)->count('*');
    }else{
      $list['data'] = $m->where($map)
                  ->where($likecols,'like','%'.$keystr.'%')
                  ->field($showList)
                  ->order($orderList)
                  ->page($page,$pagesize)
                  ->select();
      $list['count'] = $m->where($map)
                  ->where($likecols,'like','%'.$keystr.'%')
                  ->count('*');
    }    
    return json($list)  ;
}

function getDataToJsontagbind($map,$orderList,$page,$pagesize,$map2,$keystr=''){
    if($keystr==''){
      $list['data'] = Db::view('assets','*')
          ->view('tag_bind','*','assets.asset_num = tag_bind.asset_num')
          ->where($map)->where($map2)->order($orderList)->page($page,$pagesize)->select();
      $list['count'] = Db::view('assets','*')
          ->view('tag_bind','*','assets.asset_num = tag_bind.asset_num')
          ->where($map)->where($map2)->count('*');
    }else{
      $list['data'] = Db::view('assets','*')
          ->view('tag_bind','*','assets.asset_num = tag_bind.asset_num')
          ->where($map)
          ->where($map2)
          ->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
          ->order($orderList)
          ->page($page,$pagesize)
          ->select();
      $list['count'] = Db::view('assets','*')
          ->view('tag_bind','*','assets.asset_num = tag_bind.asset_num')
          ->where($map)->where($map2)
          ->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
          ->count('*');
    }    
    return json($list)  ;
}

function getDs($tablename,$selectData,$showList,$orderList,$p,$pnum){
    $list['data'] = Db::name($tablename)
        ->where($selectData)
        ->field($showList)
        ->order($orderList)
        ->page($p,$pnum)
        ->select();
    $list['count'] = Db::name($tablename)->where($selectData)->count('*');
    return $list  ;
}

function saveAll($datas,$model,$pk){
    $sql   = ''; //Sql
    $lists = []; //记录集$lists
    $ids = [];
    foreach ($datas[0] as $key => $value) {
        if($pk!=$key){
          $lists[$key] = '';
        } 
    }
    foreach ($datas as $data) {
        foreach ($data as $key=>$value) {
            if($pk===$key){
                $ids[]=$value;
            }else{
                $lists[$key] .= sprintf("WHEN %u THEN '%s' ",$data[$pk],$value);
            }
        }
    }
    foreach ($lists as $key => $value) {
        $sql.= sprintf("`%s` = CASE `%s` %s END,",$key,$pk,$value);
    }
    $sql = sprintf('UPDATE  %s  SET %s WHERE %s IN ( %s )',strtoupper($model),rtrim($sql,','),$pk,implode(',',$ids));
    return Db::execute($sql);
} 

//更新数据
function updata($table,$whdata,$updata){
    $updata = gettablecols($table,$updata);
    if($whdata!=null){
      $t = Db::table($table) 
          ->where($whdata)->update($updata);
    }else{
      $t = Db::table($table) 
          ->update($updata);
    }
    return $t;
}

//将图片转变为base64编码
function base64EncodeImage ($image_file) {
    $base64_image = '';
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    return $base64_image;
}

/**
     * [将Base64图片转换为本地图片并保存]
     * @param $base64_image_content [要保存的Base64]；格式：data:image/jpeg;base64
     * @param $path [要保存的路径]
     * @return bool|string
     */
function base64_image_content($base64_image_content,$path,$fileName){
    $res['result'] = 0;
    $res['imgurl'] = '';
    $res['msg'] = '';
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $new_file = $path."/";
        $basePutUrl = "./Public/{$new_file}";
        lg("basePutUrl = ".$basePutUrl);
        $ret = true;
        if(!file_exists($basePutUrl)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            $ret = mkdir($basePutUrl, 0700,true);
        }
        if(!$ret) {
          // 上传错误提示错误信息,目录创建失败
          $res['msg'] = "创建保存图片的路径失败！";
          return $res;
        }

        $ping_url = $fileName.".{$type}";
        $ftp_image_upload_url = $new_file.$ping_url;
        $local_file_url = $basePutUrl.$ping_url;

        if (file_put_contents($local_file_url, base64_decode(str_replace($result[1], '', $base64_image_content)))){
        //TODO 个人业务的FTP 账号图片上传
        //ftp_upload(C('REMOTE_ROOT').$ftp_image_upload_url,$local_file_url);
          $res["result"] = 1;
          $res["msg"] = "上传成功";
          $res["imgname"] = $ping_url ;
          $res["path"] = $basePutUrl;
          return $res;
        }else{
            $res['msg'] = "文件保存失败！";
            return $res;
        }
    }else{
        $res['msg'] = "base64格式不合格！";
        return $res;
    }
}

/**
 * [http 调用接口函数]
 * @Date   2018-10-01
 * @Author kl9904@163.com
 * @param  string       $url     [接口地址]
 * @param  array        $params  [数组]
 * @param  string       $method  [GET\POST\DELETE\PUT]
 * @param  array        $header  [HTTP头信息]
 * @param  integer      $timeout [超时时间]
 * @return [type]                [接口返回数据]
 */
function http($url, $params, $method = 'GET', $header = array(), $timeout = 5)
{
    // POST 提交方式的传入 $set_params 必须是字符串形式
    $opts = array(
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $header
    );

    /* 根据请求类型设置特定参数 */
    switch (strtoupper($method)) {
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            $params = http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        case 'DELETE':
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_HTTPHEADER] = array("X-HTTP-Method-Override: DELETE");
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        case 'PUT':
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 0;
            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
  
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    if($error){
      lg("Http webservice Error;",'error');
      lg("url:".$url);
      lg("params:".http_build_query($params));
      lg("Error info :".$error);
    }    
    return $data;
}

function wordAutoTag($str)
{
  $words = gettableDS('t_wordtag',['tag'=>'1'],'words,tags');
  if(empty($str)){
    return '';
  }
  $temp1 = fc_str($str);
  $temp = $temp1->keys_slipt;
  $temp = ' '.trim($temp).' ';
  foreach($words as $key => $value){
    $temp = str_replace(' '.$key.' ',' '.$value.' ', $temp);
  }
  return str_replace(' ','',$temp);
}

function wordAutoTagNew($str,$words)
{
  if(empty($str)){
    return '';
  }
  $temp1 = fc_str($str);
  $temp = $temp1->keys_slipt;
  $temp = ' '.trim($temp).' ';
  foreach($words as $key => $value){
    $temp = str_replace(' '.$key.' ',' '.$value.' ', $temp);
  }
  return str_replace(' ','',$temp);
}

function fc_str($str){
  if ($str!="") {
            $par = array(''=>$str);
            //GET  $par = array('keys'=>$str);
            //http://localhost:8066/
            //$json =  http('http://202.112.194.56/api/fc',
            $json =  http('http://localhost:8066/api/fc',
                        $par,
                        'POST',array('title' => 'bynewcorpora' ),
                        10);
            if(trim($json)==""){
              $json = '{"keys_all": "","keys_slipt": "","words_count": "0",}';
            }
            $jsonarray = json_decode($json);
            return $jsonarray;
            //echo fctoAttr($text); 
  }else{
    return array('keys_all' => "",'keys_slipt' => "",'words_count' => "0" );
  }
}

function fjcx($text){
        $text = trimall($text);
        if($text == ''){
          return;
        }
        $ywlist = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $fjarray = array();
        $arraytemp = array();
        $tag = '';
        foreach ($ywlist as $key => $value) {
            //echo $value,'&&';
            # code...
            if(strpos('。！？',$value)!==false){
                $value = trimall($value) ;
                $tag = $value;
                //echo $tag;
                if(isset($arraytemp['yj'])){
                    $arraytemp['yj'] = $arraytemp['yj'].$tag;
                    $arraytemp['fc'] = $arraytemp['fc'].$tag;
                    $arraytemp['fcattr'] = $arraytemp['fcattr'].$tag;
                    $fjarray[] = $arraytemp;
                    //p($arraytemp);
                    $arraytemp = array();
                    //p($arraytemp);
                    $tag = '';
                }
            }else{
               $value = trimall($value) ;
               if(trim($value)!=""){
                    //$fc = fc_str($value);
                    $temp = explode('/',$value); 
                    $count = count($temp);
                    //以后再此增加将词性剔除的函数。
                    $arraytemp = ['yj'=>$value,'fc'=>$value,'fcattr'=>$value,'count'=>$count]; 
                }
            }
        }
        return $fjarray;
    }

function fj($text){
        $text = trimall($text);
        if($text == ''){
          return;
        }
        $ywlist = preg_split("/(。|！|？)/",$text ,-1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $fjarray = array();
        $arraytemp = array();
        $tag = '';
        foreach ($ywlist as $key => $value) {
            //echo $value,'&&';
            # code...
            if(strpos('。！？',$value)!==false){
                $value = trimall($value) ;
                $tag = $value;
                //echo $tag;
                if(isset($arraytemp['yj'])){
                    $arraytemp['yj'] = $arraytemp['yj'].$tag;
                    $arraytemp['fc'] = $arraytemp['fc'].$tag;
                    $arraytemp['fcattr'] = $arraytemp['fcattr'].$tag;
                    $fjarray[] = $arraytemp;
                    //p($arraytemp);
                    $arraytemp = array();
                    //p($arraytemp);
                    $tag = '';
                }
            }else{
               $value = trimall($value) ;
               if(trim($value)!=""){
                    $fc = fc_str($value);
                    $arraytemp = ['yj'=>$value,'fc'=>$fc->keys_slipt,'fcattr'=>$fc->keys_all,'count'=>$fc->words_count]; 
                }
            }
        }
        return $fjarray;
    }

function insertjufunc($arr,$stype,$v){
  foreach ($arr as $k => $val) {
                  if(trim($val)!=""){
                        $fc = fc_str($val);
                        lg($val,"notice");
                        //p($fc);
                        //echo $fc->keys_slipt;
                        //echo $fc->keys_all;
                        
                        $data = [ 
                                'yt' => $val, 
                                'ytfc' => $fc->keys_slipt,
                                'ytfcattr' => $fc->keys_all,
                                'bzt' => '', 
                                'bztfc' => '',
                                'bzhtml' => '', 
                                'fid' => $v['fid'], 
                                'tid' => $v['tid'], 
                                'eid' => $v['eid'], 
                                'wordsnum' => mb_strlen($val,"UTF8"), 
                                'letternum' => $fc->words_count,
                                'seq' => $k, 
                                'tag' => "yw_".$stype, 
                                'imgidlist' => $v['imgidlist']
                            ];
                        Db::name('corpora_'.$stype)->insert($data); 
                      }
                    }
}

function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);   
}

function insertjufunc_bz($arr,$stype,$v){
  foreach ($arr as $k => $val) {
            $val = trimall($val) ;
                  if(trim($val)!=""){
                        $fc = fc_str($val);
                        lg($val,"notice");
                        $data = [ 
                                'yt' => $val, 
                                'ytfc' => $fc->keys_slipt,
                                'ytfcattr' => $fc->keys_all,
                                'bzt' => '', 
                                'bztfc' => '',
                                'bzhtml' => '', 
                                'fid' => $v['fid'], 
                                'tid' => $v['tid'], 
                                'eid' => $v['eid'], 
                                'wordsnum' => mb_strlen($val,"UTF8"), 
                                'letternum' => $fc->words_count,
                                'seq' => $k, 
                                'tag' => "bz_".$stype, 
                                'imgidlist' => $v['imgidlist']
                            ];
                        Db::name('corpora_'.$stype)->insert($data); 
                        $datalog = [
                            'forid'=>$v['eid'],
                            'fordate'=>$v['DATEOFRECEIPT']
                        ];
                        Db::name('loaddatelog')->insert($datalog); 
                      }
                    }
}

function Tbody($ds){
      $str = '<tbody>';
      foreach($ds as $key => $vo){        
        $str .=  "<tr>";
        foreach ($vo as $k => $v) {
          $str .=  "<td>".$v."</td>";
        }
        $str .= '</tr>';      
      }   
      $str .= '</tbody>';
      return $str;
}

function fulltextQueryStr($keystr,$tag='0'){
  $rtstr = array();
  $keystr = trim($keystr);
  $tag = trim($tag);
  //正常情况（非错误），返回查询串。
  if($tag == ''){
    if(strpos($keystr," ")){
      //查询多个和词，用空格分开,给每个词前面增加+，表示查询时必须出现关键字词；
      $keyarray ="";
      $akeys = explode(" ", $keystr);
      foreach ($akeys as $v) {
        $keyarray .= '+'.$v.' ';
      }
      //lg($keyarray);
      $rtstr['math'] =  "MATCH(ytfc) AGAINST('".$keyarray."' IN BOOLEAN MODE) ";
      $rtstr['key'] = $keyarray;
    }else{
      //查询句子或字词，通用分词。先分词后再按照分词后的结构进行整句查询；
      $k  = fc_str($keystr);
      $keyarray = $k->keys_slipt;
      $rtstr['math'] =  "MATCH(ytfc) AGAINST('\"".$keyarray."\"' IN BOOLEAN MODE) ";
      //$rtstr['math'] =  "MATCH(keyfc) AGAINST('".$keyarray."' IN BOOLEAN MODE) ";
      $rtstr['key'] = $keyarray;
    }
  }else{
    $str = $keystr . $tag;
    $k  = fc_str($str);
    $keyarray = $k->keys_slipt;
    //$keyarray = fc_str($str)["keys_slipt"];
    $rtstr['math'] =  "MATCH(ytfc) AGAINST('\"".$keyarray."\"' IN BOOLEAN MODE) ";
    //$rtstr['math'] =  "MATCH(keyfc) AGAINST('".$keyarray."' IN BOOLEAN MODE) ";
    $rtstr['key'] = $keyarray;
  }
  return $rtstr;
}

//数据转换，将hsk数据高亮显示；
function changeStr($d,$key){
  //$key = ' '.trim($key).' ';
  $rtlist = array();
  $key_array = explode("+", $key);
  if(trim($key_array[0])==''){
    unset($key_array[0]);
  }
  $old_a = array();
  $new_a = array();
  foreach ($key_array as $key => $value) {
    $old_a[]= trim($value).' ';
    //lg('[ ' . trim($value).' ]');
    $new_a[]='<em>' . trim($value).'</em>';
    //lg('[<em>' . trim($value).'</em>]');
  }
  foreach ($d as $k => $v) {
    $row = array();
    $temp ="";
    foreach ($v as $vk => $vv) {
      
      if($vk == 'ytfc'){
        //标注关键字；
        //lg('$vv==['.$vv.']');
        //lg('$key=['.$key.']');
        //$key_st = "<em>".$key."</em>";
        $temp = $vv;
        $vv = str_replace($old_a, $new_a , ' '.$vv);
        //替换掉分词后的空格，即还原字符串；
        $vv = str_replace(' ', '', $vv);
        //lg('$temp=='.$temp);
      }
      $row[$vk] = $vv;
    }
    //$row[]= str_replace(' ', '', $temp);
    $rtlist[] = $row;
  }
  return $rtlist;
}

//数据转换，将hsk数据高亮显示；
function changeStrReg($d,$keys,$reps){
  //$key = ' '.trim($key).' ';
  $rtlist = array();
  foreach ($d as $k => $v) {
    $row = array();
    $temp ="";
    foreach ($v as $vk => $vv) {
      if($vk == 'ytfc'){
        $temp = $vv;
        $vv = marktext4($keys,$reps,$vv);
        //$vv = str_replace($old_a, $new_a , ' '.$vv);
        //替换掉分词后的空格，即还原字符串；
        $vv = str_replace(' ', '', $vv);
        //lg('$temp=='.$temp);
      }
      $row[$vk] = $vv;
    }
    //$row[]= str_replace(' ', '', $temp);
    $rtlist[] = $row;
  }
  return $rtlist;
}
//正则表达式匹配高亮显示函数
function marktext4($keys,$reps,$source){
    //p($keys);
    $patterns = "/$keys/" ; 
    //lg($source);
    //lg($patterns);
    //lg($reps);
    $rt = preg_replace($patterns , $reps , $source);
    if($rt){
      return $rt;
    }else{
      return $source;
    }
}

//数据转换，将hsk数据高亮显示；
function changeStr2($d,$key){
  //$key = ' '.trim($key).' ';
  $rtlist = array();
  $key_array = explode("+", $key);
  if(trim($key_array[0])==''){
    unset($key_array[0]);
  }
  $old_a = array();
  $new_a = array();
  foreach ($key_array as $key => $value) {
    $old_a[]= trim($value).' ';
    //lg('[ ' . trim($value).' ]');
    $new_a[]='<em>' . trim($value).'</em>';
    //lg('[<em>' . trim($value).'</em>]');
  }
  foreach ($d as $k => $v) {
    $row = array();
    $temp ="";
    foreach ($v as $vk => $vv) {
      
      if($vk == 'ytfc'){
        //标注关键字；
        //lg('$vv==['.$vv.']');
        //lg('$key=['.$key.']');
        //$key_st = "<em>".$key."</em>";
        $temp = $vv;
        $vv = str_replace($old_a, $new_a , ' '.$vv);
        //替换掉分词后的空格，即还原字符串；
        $vv = str_replace(' ', '', $vv);
        //lg('$temp=='.$temp);
      }
      if($vk == 'aa'){
        //标注关键字；
        //lg('$vv==['.$vv.']');
        //lg('$key=['.$key.']');
        //$key_st = "<em>".$key."</em>";
        $temp = $vv;
        $vv = str_replace($old_a, $new_a , ' '.$vv);
        //替换掉分词后的空格，即还原字符串；
        $vv = str_replace(' ', '', $vv);
        //lg('$temp=='.$temp);
      }

      $row[] = $vv;
    }
    $row[]= str_replace(' ', '', $temp);
    $rtlist[] = $row;
  }
  return $rtlist;
}

function getTreeToUserID($UserID){
  $UserID = strtr($UserID,'\'','');
  $sql = "select distinct(t.FunID),t.FunName,t.FunLink,t.FatherID,t.SequenceID,t.GroupTag,t.MenuLevelID from t_systree t , t_funtorole fr , t_roletouser ru  where fr.FunID=t.FunID  and ru.RoleID=fr.RoleID and DisplayTag='1'  and UserID='" .$UserID . "' ";
    //$rt = Db::view("systree",'FunName,FunLink,FatherID,SequenceID,GroupTag,MenuLevelID')
        // ->view('funtorole','FunID','funtorole.FunID=systree.FunID')
        // ->view('roletouser','RoleID','roletouser.RoleID=funtorole.RoleID')
        // ->where(['UserID'=>$UserID,'DisplayTag'=>'1'])
        // ->select();
    $rt = Db::query($sql);
    return $rt;
}

function getOrgToUserID($UserID){
    $rt = Db::view("orgtouser",'OrgID')
        ->view('sysorg','OrgName,Detail','sysorg.OrgID=orgtouser.OrgID')
        ->where(['UserID'=>$UserID,'Tag'=>'1'])
        ->select();
    return $rt;
}

function getUserExtToUserID($UserID){
    $rt = Db::view("sysuser",'Tag')
        ->view('sysuserext','UserID,UserNo,level,score,UserTag,cext1,cext2,cext3,cext4','sysuserext.UserID=sysuser.UserID')
        ->where(['UserID'=>$UserID,'Tag'=>'1'])
        ->select();
    return $rt;
}

function getUserToUserID($UserID){
    $rt = Db::table("t_sysuser")
        ->where(['UserID'=>$UserID,'Tag'=>'1'])
        ->column('UserID,UserName,UserEmail,UserMobile');
    return $rt;
}

function getRoleInfoToUserID($UserID){
    $rt = Db::table("t_sysuser")
        ->where(['UserID'=>$UserID,'Tag'=>'1'])
        ->column('UserID,UserName,UserEmail,UserMobile');
    return $rt;
}

function getFunToRole($RoleID){
    $rt = Db::view("systree",'FunName,FunLink,FatherID')
        ->view('funtorole','FunID,RoleID','systree.FunID=funtorole.FunID')
        ->where(['RoleID'=>$RoleID])
        ->select();
    return json($rt);
}
    //#########para
    /*
      $table  表名，带前缀。
      $whereData  过滤条件，及fatherID = 给定的值；查出直接子节点的ID
      $colName  fatherid（及父节点的字段名）
    **/
function delTree($table,$deleteid,$fathercolName,$colname){
  static $result=array();
  $cols = gettableDs($table,[$fathercolName=>$deleteid],$colname);
  //p($cols);
  foreach ($cols as $key => $value) {
    //p($value);
    delTree($table,$value,$fathercolName,$colname);
  }
  $result[] = $deleteid;
  Db::table($table)->where([$colname=>$deleteid])->delete();
  return $result;
}

//-----------------------------------------------//
//###########一下是数据库基本操作################//
//-----------------------------------------------//

//插入数据
function insert($table,$post){
  $data = gettablecols($table,$post);
  //p($data);
  return Db::table($table)->insertGetId($data);
  //return model($table)->allowField(true)->save($data);
}

//批量插入
function insertAll($table,$post){
  //$data = gettablecols($table,$post);
  return Db::table($table)->insertAll($post);
  //return model($table)->allowField(true)->save($data);
}

function delete($table,$map){
  Db::table($table)->where($map)->delete();
}

function sel($sql){
  //进行原生的SQL查询
  return json(Db::query($sql));
}

function select($sql){
  //进行原生的SQL查询
  return Db::query($sql);
}

function gettablecol($table,$map,$colname){
  return Db::table($table)->where($map)->value($colname);
}

function getCount($tablename,$querystr){
      $m = Db::table($tablename); // 实例化对象'PAGE_START' => 1 ,
      $count = $m->where($querystr)->count();
      return $count  ;
}

function getS($tablename,$selectData,$showList,$ordList){
  $m   =   Db::name($tablename);
    $list = $m->where($selectData)->field($showList)->order($ordList)->select();
   // p($selectData);
    //lg();
    return json($list)  ;
}

function gettable($tablename,$selectData='',$showlist='*'){
    $m   =   Db::table($tablename);
    $list = $m->where($selectData)
              ->column($showlist);
    return json($list)  ;
}

function gettableDS($tablename,$selectData,$showlist='*'){
    $m   =   Db::table($tablename);
    $list = $m->where($selectData)
              ->column($showlist);
    return $list  ;
}

function gettableDSArray($tablename,$selectData,$showlist='*'){
    $m   =   Db::table($tablename);
    $list = $m->where($selectData)
              ->field($showlist)->select();
    return $list  ;
}

function getcols($tname){
    $arr = array();
    foreach ($tname as $key => $value) {
      $m   =   Db::query("SHOW COLUMNS FROM `".$value."` ");
      foreach ($m as $k =>  $v) {
        array_push($arr,$v["Field"]);
      }
    }
    
    return $arr;
}

function gettablecols($tname,$post){
    $arr = array();    
    $m   =   Db::query(" SHOW COLUMNS FROM `".$tname."` ");
    //$post = array_change_key_case($post);
    foreach ($m as $k =>  $v) {
      $col = $v["Field"];
      //$col = strtolower($col);
      $pst = "";
      $pstv = "";
      if(isset($post[$col]) ){
        $pst = $col;
        $pstv = $col;
      }
      if(isset($post[$tname.'_'.$col])){
        $pst = $tname.'.'.$col;
        $pstv = $tname.'_'.$col;
      }
      if($pst !=""){
        $arr[$pst]=$post[$pstv];
      }

    }
    foreach ($arr as $key => $value) {
            if($value==''){
                unset($arr[$key]);
            }
    } 
    return $arr;
}

function gettablecolsList($tnameArray,$post){
  $rtinfo = [];
  foreach ($tnameArray as $key => $value) {
    $m   =   Db::query("SHOW COLUMNS FROM `".$value."` ");
    //$post = array_change_key_case($post);
    foreach ($m as $k =>  $v) {
      $col = $v["Field"];
      //$col = strtolower($col);
      if(isset($post[$col])){
        $rtinfo[$col]=$post[$col];
      }
    }
  }
  return $rtinfo;
}

//增加登录次数
function incLogin($UserID){
  if(getCount("t_logintimes",["UserID"=>$UserID])>0){
    inc("t_logintimes",["UserID"=>$UserID],"times");
  }else{
    insert("t_logintimes",["UserID"=>$UserID,'times'=>'1']);
  }
}

//自增加函数
function inc($table,$data,$col){
    $t = Db::table($table); 
    $t->where($data)->setInc($col,1);
}

//通过token获取用户信息
function getUserfortocken($token){
   $rt = Db::query("select u.UserID,UserName,UserEmail,UserMobile from t_sysuser u , t_token t where t.UserID = u.UserID and t.token =?",[$token]);
   if(empty($rt)){
    return  ["UserID"=>'',"UserName"=>'',"UserEmail"=>'',"UserMobile"=>''];
   }else{
    if(isset($rt[0])){
      return $rt[0];
    }else
    {
      return  ["UserID"=>'',"UserName"=>'',"UserEmail"=>'',"UserMobile"=>''];
    }
    
   }
}

//通过rdkey获取终端设备信息
function getdeviceinfoforrdkey($rdkey){
  //select reader_id,reader_name,location,reader_type,maker_name,model_num,status,start_time,rdkey from t_reader_register;
   $rt = Db::query("select reader_id,reader_name,location,reader_type,maker_name,model_num,status,start_time,rdkey from t_reader_register where rdkey =?",[$rdkey]);
   if(empty($rt)){
    return  ["reader_id"=>'',"reader_name"=>'',"location"=>'',"reader_type"=>'','maker_name'=>'','model_num'=>'','status'=>'','start_time'=>'','rdkey'=>''];
   }else{
    return $rt[0];
   } 
}

//操作日志写入数据库
function userlog($oid,$otype='user',$detail,$modelname='sys',$ltype='sys'){
  return insert("t_SysLog",
    ["OperateID"=>$oid,
     'OperateType'=>$otype,
     'Detail'=>$detail,
     'ModuleName'=>$modelname,
     'Logtype'=>$ltype
    ]);
}

//type  0:加密  1:解密
function encryption($value,$type=0){
    $key=Config::get("encryption_key");
    if($type == 0){//加密
      return str_replace('=', '', base64_encode($value ^ $key));
    }else{
      $value=base64_decode($value);
      return $value ^ $key;
    }
}

//判断用户是否登录
function islogin($token){
  $timer=strtotime('now');
  $tokenstamp = gettablecol("t_token",["token"=>$token],"extDate");
  //p($tokenstamp);
  $rt=["stats"=>"-1","msg"=>"token验证失败"];
  if($tokenstamp>$timer){
    //没有过期，正常返回 ；
    $timer=$timer+10*60;
    updata("t_token",["token"=>$token],["extDate"=>$timer]);
    $userid = gettablecol("t_token",["token"=>$token],"UserID");
    $rt=["ID"=>"1","msg"=>$userid];
  }else{
    //过期，收回token令牌；
    $rt=["ID"=>"-1","msg"=>"token验证失败，登录超时"];
  }
  return $rt;
}

//判断用户是否登录
function islogindelphi($token){
  $timer=strtotime('now');
  $tokenstamp = gettablecol("t_token",["token"=>$token],"extDate");
  //p($tokenstamp);
  $rt=["stats"=>"-1","msg"=>"token验证失败"];
  if($tokenstamp>$timer){
    //没有过期，正常返回 ；
    //$timer=$timer+10*60;
    //updata("t_token",["token"=>$token],["extDate"=>$timer]);
    $userid = gettablecol("t_token",["token"=>$token],"UserID");
    $rt=["ID"=>"1","msg"=>$userid];
  }else{
    //过期，收回token令牌；
    $rt=["ID"=>"-1","msg"=>"token验证失败，登录超时"];
  }
  return $rt;
}

function isloginNew($token){
  $timer=strtotime('now');
  //p($timer);
  $tokenstamp = gettablecol("t_token",["token"=>$token],"extDate");

  //p($tokenstamp);
  //$rt=["stats"=>"-1","msg"=>"token验证失败"];
  if($tokenstamp>$timer){
    //没有过期，正常返回 ；
    $timer=$timer+10*60;
    updata("t_token",["token"=>$token],["extDate"=>$timer]);
    $rt=true;
  }else{
    //过期，收回token令牌；
    $rt=false;
  }
  return $rt;
}

//生成随机数 长度和类型
function randomStr($len,$type='num'){
    $str = "";
    $arr = array();
    if($type=='num'){
      $arr = range(0,9);
    }
    if($type=="all"){
      $arr = array_merge(range(0,9),range('A','Z'));
    }
    if($type=="char"){
      $arr = range('A','Z');
    }

    
    //$str = '';
    $arr_len = count($arr);
    for($i = 0;$i < $len;$i++){
        $rand = mt_rand(0,$arr_len-1);
        $str.=$arr[$rand];
    }
    return $str;
}

//获取验证码（用户==>随机码）
function getcode($re,$type='password',$num=4,$codetype='num'){
  $code = randomStr($num,$codetype);
  $timer=strtotime('now');
  $timer=$timer+5*60;
  $i=-1;
  if(gettablecol('t_code',"UserID='".$re."'",'*')!=null){
    $i = updata('t_code',['UserID'=>$re,'type'=>$type],['codenum'=>$code,'extDate'=>$timer]);
  }else{
    $i = insert('t_code',['UserID'=>$re,'type'=>$type,'codenum'=>$code,'extDate'=>$timer]);
  }
  if($i>=0){
    return $code;
  }else{
    return '-1';
  }
}

//发送电子邮件
function email($to,$title,$message,$cc="",$bcc="") {
  $mail = new PHPMailer();
  //3.设置属性，告诉我们的服务器，谁跟谁发送邮件
  $mail -> IsSMTP();      //告诉服务器使用smtp协议发送
  $mail -> SMTPAuth = true;   //开启SMTP授权
  $mail -> Host = Config::get("Email_host");  //告诉我们的服务器使用163的smtp服务器发送
  $mail -> From = Config::get("Email_from");  //发送者的邮件地址
  $mail -> FromName = Config::get("Email_fromname");   //发送邮件的用户昵称
  $mail -> Username = Config::get("Email_name"); //登录到邮箱的用户名
  $mail -> Password = Config::get("Email_password"); //第三方登录的授权码，在邮箱里面设置
  //编辑发送的邮件内容
  $mail -> IsHTML(true);        //发送的内容使用html编写
  $mail -> CharSet = 'utf-8';   //设置发送内容的编码
  $mail -> Subject = $title;//设置邮件的标题
  $mail -> MsgHTML($message); //发送的邮件内容主体
  if($cc!=""){$mail -> AddCC($cc);}
  if($bcc!=""){$mail -> AddBCC($bcc);}
  $mail -> AddAddress($to);    //收人的邮件地址
  //调用send方法，执行发送
  $result = $mail -> Send();
  if($result){
     return true;
  }else{
      return $mail -> ErrorInfo;
  }
}

//生成日期顺序字符串
function urlen($str){
  $dateStr=date("Y-m-d:H",time());
  return urlencode($str.'T_'.$dateStr) ;
}

//日期戳
function timestr(){
  $dateStr=date("Y-m-d H:i:s",time());
  return $dateStr ;
}

//加密函数
function enc($string = '', $skey = 'bjyydx') {
  //$dateStr=date("Y-m-d:H",time());
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

//解密函数
function dec($string = '', $skey = 'bjyydx') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

//百度翻译引擎调用
function language($value,$from="auto",$to="auto")
{
     $value_code = urlencode($value); //urlencode($value); //首先对要翻译的文字进行 urlencode 处理
     $appid = "20160724000025683"; //您注册的API Key
     $key = "Ap_3pXAMIZ67M9PcuG3r"; //密钥
     $salt = rand(1000000000,9999999999); //随机数
     $sign = md5($appid.$value_code.$salt.$key); //签名
     //生成翻译API的URL GET地址
     $languageurl = "http://api.fanyi.baidu.com/api/trans/vip/translate?q=$value_code&appid=$appid&salt=$salt&from=$from&to=$to&sign=$sign";
     p($languageurl);
     $text=json_decode(LanguageText($languageurl));
     p($text);
     return $text->trans_result;
}

// @sum_he
function getRoleByUserID($UserID, $page=0, $pagesize=20){
  $rt = Db::view("sysrole",'RoleName')
  ->view('roletouser','RoleID,UserID','sysrole.RoleID=roletouser.RoleID')
  ->view('sysuser','UserID,UserName,UserEmail,UserMobile','roletouser.UserID=sysuser.UserID')
  ->where(['UserID'=>$UserID])
  ->page($page, $pagesize)
  ->select();
  $count = Db::view("sysrole",'RoleName, RoleID')
  ->view('roletouser','RoleID,UserID','sysrole.RoleID=roletouser.RoleID')
  ->view('sysuser','UserName','roletouser.UserID=sysuser.UserID')
  ->where(['UserID'=>$UserID])
  ->count();
  $list['data'] = $rt;
  $list['count'] = $count;
  return $list;
}
// @sum_he
function getUserToRole($RoleID, $page, $pagesize){
  $rt = Db::view("sysrole",'RoleName')
  ->view('roletouser','RoleID,UserID','sysrole.RoleID=roletouser.RoleID')
  ->view('sysuser','UserID,UserName,UserEmail,UserMobile','roletouser.UserID=sysuser.UserID')
  ->where(['RoleID'=>$RoleID])
  ->page($page, $pagesize)
  ->select();
  $count = Db::view("sysrole",'RoleName')
  ->view('roletouser','RoleID,UserID','sysrole.RoleID=roletouser.RoleID')
  ->view('sysuser','UserID,UserName,UserEmail,UserMobile','roletouser.UserID=sysuser.UserID')
  ->where(['RoleID'=>$RoleID])
  ->count();
  $list['data'] = $rt;
  $list['count'] = $count;
  return $list;
}
// @sum_he
function getUserForOrgID ($OrgID, $page, $pagesize){
  $rt = Db::view("sysorg",'OrgName')
  ->view('orgtouser','OrgID,UserID','sysorg.OrgID=orgtouser.OrgID')
  ->view('sysuser','UserID,UserName,UserEmail,UserMobile','orgtouser.UserID=sysuser.UserID')
  ->where(['OrgID'=>$OrgID])
  ->page($page, $pagesize)
  ->select();
  $count = Db::view("sysorg",'OrgName')
  ->view('orgtouser','OrgID,UserID','sysorg.OrgID=orgtouser.OrgID')
  ->view('sysuser','UserID,UserName,UserEmail,UserMobile','orgtouser.UserID=sysuser.UserID')
  ->where(['OrgID'=>$OrgID])
  ->count();
  $list['data'] = $rt;
  $list['count'] = $count;
  return $list;
}

//返回标准化
function rtjson($msg='Error:',$id='-1'){
  return json(['ID' => $id,'msg'=>$msg]);
}

function hextostr($hex)
{
    return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
        return chr(hexdec($matches[1]));
    }, $hex);
}

function str_repls($str , $arry){
   return strtr($str, $arry);
}

function commandCreator($title,$asset_num,$asset_name,$department){
$tlpString = <<<EOD
{D0430,0900,0400|}
{C|}
{AX;+000,+000,+00|}
{AY;+00,0|}
{@003;-0430,2|}  
{PC000;0050,0035,2,2,r,00,B=$title|}
{XB00;0665,0050,T,L,04,A,0,M2=erweima|}
{PC002;0039,0233,1,1,r,00,B=资产编号|}
{PC003;0039,0453,1,1,r,00,B=资产名称|}
{PC004;0039,0673,1,1,r,00,B=部    门|}
{PC005;0200,0233,1,1,r,00,B=$asset_num|} 
{PC006;0200,0453,1,1,r,00,B=$asset_name|}
{PC007;0200,0673,1,1,r,00,B=$department|}
{LC;0020,0200,0600,0880,1,1|}
{LC;0020,0420,0600,0420,0,1|}
{LC;0020,0690,0600,0690,0,1|}
{LC;0200,0200,0200,0880,0,1|}
{XS;I,0001,0000C6201|}
EOD;
return $tlpString;
}

?>