<?php
namespace app\res\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;
use ewm\phpqrcode;


class Index extends Controller
{
    public function index()
    {
        // lg("call res /index/index;".date("Y-m-d H:i:s"));
        // return sel('select * from t_Assets');
        // return "HI:)))--->>>   welcom res!";
        return $this->fetch();
    }
    public function showimg()
    {
        // lg("call res /index/index;".date("Y-m-d H:i:s"));
        // return sel('select * from t_Assets');
        // return "HI:)))--->>>   welcom res!";
        return $this->fetch();
    }

    

    // 固定资产精确查询
    // 实现对固定资产的查询，支持分页（page和pagesize参数），支持资产价值的范围查询（minvalue、maxvalue）；支持启用日期的范围查询（minDate、maxDate）
    // 返回结果：{"data":[{"asset_id":5,"parent_tag_num":"14-16101JC","parent_name":"He-3制冷机","asset_num":"12-13084","tag_num":"3.20101E+12","name":"机械泵","value":4800,"financial_category":"固定资产-通用设备","category_bid":null,"category_sid":null,"category":null,"maker_name":null,"model_num":"RVP-4","person_id":null,"person":"杨天中","deparment_id":null,"deparment":"N08组","topic":null,"start_time":"2018-11-01 13:29:04","location":"*","status":null,"in_use_flag":null,"tag":"0","CreateDate":"0000-00-00 00:00:00","tag_bind_id":1,"tag_id":"111","pic1":"1","pic2":"1","pic3":"1","pic4":"1","video":"1","bind_date":"2018-11-02 20:30:55","bind_person":"1","bind_status":null}],"count":2}
    public function getasset(){
        $re = input('post.');
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_assets',$re);        
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }        
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        $rt = getDataMapsToJson('assets',$data,'*','asset_id',$page,$pagesize,$map2);
        return $rt;  
    }


    public function getassetfordev(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogindelphi($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }

        $userid = $loginstatus['msg'];
        $deparment = getOrgToUserID($userid);
        $dp = "";
        if(isset($deparment[0]['OrgName'])){
            $dp = $deparment[0]['OrgName'];
        }

        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_assets',$re);        
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }        
        $data['deparment'] =  $dp;
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        $rt = getDataMapsToJson('assets',$data,'*','asset_id',$page,$pagesize,$map2);
        return $rt;  
    }
    public function getassetImgCountInfo(){
        $re = input('post.');
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_assets',$re);        
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }        
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        $rt = getDataMapsToJson('assetsimgcount',$data,'*','asset_id',$page,$pagesize,$map2);
        return $rt;  
    }

    /***
    *获取资产图片
    ***/
    public function getAssetImg(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
            'asset_num' => 'require'
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'asset_num.require' => '资产编码必须提供'
        ];
        //echo $_SERVER["DOCUMENT_ROOT"].'----------';
        //echo $_SERVER["SCRIPT_NAME"] .'--------';
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        //$checkpath = '/assetwls/Public/AssetIntiDir/'.$re['asset_num']
        //$path = "/Public/AssetIntiDir/".

        //$url='http://'.$_SERVER['SERVER_NAME'];//.$_SERVER["REQUEST_URI"]; 
        //echo $url;
        //echo './Public/AssetIntiDir/'.$re['asset_num'].'_zt.jpg';
        //$pathHome = dirname($url);
        $imgPath = '/assetwls/Public/AssetIntiDir/'.$re['asset_num'];
        $checkpath = $_SERVER["DOCUMENT_ROOT"].$imgPath ;

        $imageUrl['img_zt'] = "";
        $curnum = 0;
        if(file_exists($checkpath.'_zt.jpeg')){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            //"http://localhost/asset/res/index/Public/AssetIntiDir/http://localhost/asset/res/index/getAssetImggood_bq.jpg"
            $imageUrl['img_zt'] =  $imgPath.'_zt.jpeg';
            $curnum++;
        }
        $imageUrl['img_jb'] = "";
        //$checkpath = $_SERVER['DOCUMENT_ROOT'].'/asset/Public/AssetIntiDir/'.$re['asset_num']; 
        //echo $checkpath ;
        //echo $checkpath.'_jb.jpg';
        if(file_exists($checkpath.'_jb.jpeg')){
            //echo $checkpath.'_jb.jpg';
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            $imageUrl['img_jb'] =  $imgPath.'_jb.jpeg';
            $curnum++;
        }
        $imageUrl['img_bq'] = "";
        if(file_exists($checkpath.'_bq.jpeg')){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            $imageUrl['img_bq'] =  $imgPath.'_bq.jpeg';
            $curnum++;
        }
        $imageUrl['img_qt'] = "";
        if(file_exists($checkpath.'_qt.jpeg')){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            $imageUrl['img_qt'] =  $imgPath.'_qt.jpeg';
            $curnum++;
        }
        $imageUrl['curnum'] = $curnum ;
        return json($imageUrl);
    } 
    

    /***
    *获取资产图片
    ***/
    public function getAssetImgApi($assetnum,$token){
        $rule =   [
            'token'   =>  'require',
            'assetnum' => 'require'
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'assetnum.require' => '资产编码必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($rule);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginstatus = islogindelphi($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        //select bind_status from t_assets where asset_num = '13234'
        $ischeck = gettablecol('t_assets',['asset_num'=>$assetnum],'bind_status');

        if($ischeck!='已绑定'){
            return json(['img_zt'=>'','img_jb'=>'','img_bq'=>'','img_qt'=>'','curnum'=>'0']);
        }

        $imgPath = '/assetwls/Public/AssetIntiDir/'.$assetnum;
        $checkpath = $_SERVER["DOCUMENT_ROOT"].$imgPath ;
        $imageUrl['img_zt'] = "";
        $curnum = 0;
        if(file_exists($checkpath.'_zt.jpeg')){
            $imageUrl['img_zt'] =  $imgPath.'_zt.jpeg';
            $curnum++;
        }
        $imageUrl['img_jb'] = "";
        if(file_exists($checkpath.'_jb.jpeg')){
            $imageUrl['img_jb'] =  $imgPath.'_jb.jpeg';
            $curnum++;
        }
        $imageUrl['img_bq'] = "";
        if(file_exists($checkpath.'_bq.jpeg')){
            $imageUrl['img_bq'] =  $imgPath.'_bq.jpeg';
            $curnum++;
        }
        $imageUrl['img_qt'] = "";
        if(file_exists($checkpath.'_qt.jpeg')){
            $imageUrl['img_qt'] =  $imgPath.'_qt.jpeg';
            $curnum++;
        }
        $imageUrl['curnum'] = $curnum ;
        return json($imageUrl);
    } 


    public function getassetdelphi(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogindelphi($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        //$userid = $loginstatus['msg'];




        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 50;
        }
        $data = gettablecols('t_assets',$re);        
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }        
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        $data['print_status']='待打印';
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        $rt = getDataMapsToJson('assets',$data,'*','CreateDate',$page,$pagesize,$map2);
        return $rt;  
    }
    // 固定资产模糊查询
    // 实现对固定资产的查询，支持分页（page和pagesize参数），支持资产价值的范围查询（minvalue、maxvalue）；支持启用日期的范围查询（minDate、maxDate），支持模糊查询（查询参数是keystr，支持对name|maker_name|t_assets.asset_num|person|deparment字段进行查询）；
    // 返回结果：{"data":[{"asset_id":5,"parent_tag_num":"14-16101JC","parent_name":"He-3制冷机","asset_num":"12-13084","tag_num":"3.20101E+12","name":"机械泵","value":4800,"financial_category":"固定资产-通用设备","category_bid":null,"category_sid":null,"category":null,"maker_name":null,"model_num":"RVP-4","person_id":null,"person":"杨天中","deparment_id":null,"deparment":"N08组","topic":null,"start_time":"2018-11-01 13:29:04","location":"*","status":null,"in_use_flag":null,"tag":"0","CreateDate":"0000-00-00 00:00:00","tag_bind_id":1,"tag_id":"111","pic1":"1","pic2":"1","pic3":"1","pic4":"1","video":"1","bind_date":"2018-11-02 20:30:55","bind_person":"1","bind_status":null}],"count":2}
    public function getassetlike(){
        $re = input('post.');
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        //$map=[];
        $data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        if(!isset( $re['keystr'])){
            return;
        }
        $keystr = $re['keystr'];
        //$re['value']='0';
        

        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $rt = getDataMapsToJson('assets',$data,'','asset_id',$page,$pagesize,$map2,$keystr);
        //$rt = gettable('t_Assets');
        return $rt;  
    }

    

    // 标签绑定精确查询
    // 实现对标签绑定结果的查询，支持分页（page和pagesize参数），支持资产价值的范围查询（minvalue、maxvalue）；支持启用日期的范围查询（minDate、maxDate）
    // 返回结果：{"data":[{"asset_id":5,"parent_tag_num":"14-16101JC","parent_name":"He-3制冷机","asset_num":"12-13084","tag_num":"3.20101E+12","name":"机械泵","value":4800,"financial_category":"固定资产-通用设备","category_bid":null,"category_sid":null,"category":null,"maker_name":null,"model_num":"RVP-4","person_id":null,"person":"杨天中","deparment_id":null,"deparment":"N08组","topic":null,"start_time":"2018-11-01 13:29:04","location":"*","status":null,"in_use_flag":null,"tag":"0","CreateDate":"0000-00-00 00:00:00","tag_bind_id":1,"tag_id":"111","pic1":"1","pic2":"1","pic3":"1","pic4":"1","video":"1","bind_date":"2018-11-02 20:30:55","bind_person":"1","bind_status":null}],"count":2}
    public function gettagbind(){
        $re = input('post.');
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_assets',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }

        $rt = getDataToJsontagbind($data,'CreateDate',$page,$pagesize,$map2);
        return $rt;  
    }

    // 标签绑定模糊查询
    // 实现对标签绑定结果的查询，支持分页（page和pagesize参数），支持资产价值的范围查询（minvalue、maxvalue）；支持启用日期的范围查询（minDate、maxDate），支持模糊查询（查询参数是keystr，支持对name|maker_name|t_assets.asset_num|person|deparment字段进行查询）；
    // 返回结果：{"data":[{"asset_id":5,"parent_tag_num":"14-16101JC","parent_name":"He-3制冷机","asset_num":"12-13084","tag_num":"3.20101E+12","name":"机械泵","value":4800,"financial_category":"固定资产-通用设备","category_bid":null,"category_sid":null,"category":null,"maker_name":null,"model_num":"RVP-4","person_id":null,"person":"杨天中","deparment_id":null,"deparment":"N08组","topic":null,"start_time":"2018-11-01 13:29:04","location":"*","status":null,"in_use_flag":null,"tag":"0","CreateDate":"0000-00-00 00:00:00","tag_bind_id":1,"tag_id":"111","pic1":"1","pic2":"1","pic3":"1","pic4":"1","video":"1","bind_date":"2018-11-02 20:30:55","bind_person":"1","bind_status":null}],"count":2}
    public function gettagbindlike(){
        $re = input('post.');
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_assets',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //$data = gettablecols('t_assets',$re);
        $map2 = [];
        if(isset($re['minvalue'])){
            if($re['minvalue']!=''){
                $data['value'] =['>=',$re['minvalue']];
            }
        }
        if(isset($re['maxvalue'])){
            if($re['maxvalue']!=''){
                $map2['value'] = ['<=',$re['maxvalue']];
            }
        }
        if(isset($re['minDate'])){
            if($re['minDate']!=''){
                $data['start_time'] = [">=",$re['minDate']];
            }
        }
        if(isset($re['maxDate'])){
            if($re['maxDate']!=''){
                $map2['start_time'] = ["<=",$re['maxDate']];
            }
        }
        $keystr = $re['keystr'];
        $rt = getDataToJsontagbind($data,'CreateDate',$page,$pagesize,$map2,$keystr);
        return $rt;  
    }

    //盘点计划资产列表录入
    //先录入计划，然后按照计划ID，录入计划对应的设备列表。
    //输入参数：plan_id （计划内部编号，自动生成，插入后返回）；asset_num  用逗号分隔的asset_num的list。如J201601,J201602,J201603,J201604,J201605,J201606,
    // 返回样例：{"ID":"1","msg":"OK"}
    public function putcheckdetailAll(){
        $re = input('post.');
        $rule =   [
            'asset_num'  => 'require',
            'plan_id'   =>  'require'
        ];
        $message  =   [
            'asset_num.require' => '资产编号必须提供',
            'plan_id.require' => '盘点计划ID必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);

        $assetArray=explode(",",$re['asset_num']);
        $data =  array();
        $insertlist ="";
        $existslist ="";
        foreach ($assetArray as $key => $value) {
            //array_push($data, ['RoleID'=> $value , 'OrgID'=> $re['OrgID'] ]);
            if($value!=""){
                $detailId = gettablecol('t_checks_detail',['plan_id'=>$re['plan_id'],'asset_num'=>$value],'detail_id');
                if($detailId==null){
                    $insertlist.= $value.',';
                    array_push($data,['plan_id'=>$re['plan_id'],'asset_num'=>$value]) ;
                }else{
                    $existslist.=$value.",";
                }
            }
            
        }
        $i = insertAll('t_checks_detail',$data);
        if($i>0){
            return rtjson('OK','1');
        }else{
            return rtjson('添加数据失败。');
        }
    }

    // 新建或更新计划接口（即生成盘点计划）
    // 输入参数必须有plan_name（盘点计划名称），token：用户令牌，deadline（失效时间）和plan_memo(计划描述)是可选项。
    //更新数据：需要提供plan_id;作为更新主键。
    // 新增计划后返回plan_id，作为选择盘点计划对应资产的父ID；
    //新增返回样例：{"ID":"1","msg":"新增计划成功，计划编号：9"}
    //更新返回样例：{"ID":"1","msg":"更新计划成功，计划ID：9"}
    public function putchecksplan(){
        $re = input('post.');
        $rule =   [
            'plan_name'  => 'require',
            'token'      => 'require',
        ];
        $message  =   [
            'plan_name.require' => '盘点名称必须提供',  
            'token.require' => '认证令牌token必须提供', 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];


        $data = gettablecols('t_checks_plan',$re);
        $data['create_person']=$userid;
        if(isset($data['plan_id'])){
            $i = updata('t_checks_plan',['plan_id'=>$data['plan_id']],$data);
            if($i>0){
                return rtjson('更新计划成功，计划ID：'.$data['plan_id'],'1');
            }else{
                return rtjson('更新数据失败。');
            }
        }else{
            $i = insert('t_checks_plan',$data);
            if($i>0){
                return rtjson('新增计划成功，计划编号：'.$i,'1');
            }else{
                return rtjson('添加数据失败。');
            }
        }
        
    }
    // 删除盘点计划
    // 输入参数：plan_id（盘点计划id）.
    // 返回样例：{"ID":"1","msg":"盘点计划已删除，计划编号：9"}
    public function deletechecksplan(){
        $re = input('post.');
        $rule =   [
            'plan_id'  => 'require',
            'token' => 'require'
        ];
        $message  =   [
            'plan_name.require' => '盘点id必须提供',
            'token.require' => 'token必须提供' 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        //userlog($oid,$otype='user',$detail,$modelname='sys',$ltype='sys')

        $data = gettablecols('t_checks_plan',$re);
        //$data['create_person']=$userid;
        if(isset($data['plan_id'])){
            $i = delete('t_checks_plan',['plan_id'=>$data['plan_id']]);
            if($i>0){
                userlog($userid,'user','delete  from t_checks_plan where plan_id = \''.$data['plan_id'].'\'','res','deletechecksplan');
                return rtjson('删除计划成功，计划ID：'.$data['plan_id'],'1');

            }else{
                return rtjson('删除数据失败。');
            }
        }
        
    }

    // 资产盘点计划查询
    // 实现对资产盘点计划的查询功能，支持分页（page和pagesize参数），支持对盘点计划名称和盘点计划创建人的模糊查询（模糊查询参数是keystr）；
    // 返回结果：{"data":[{"plan_id":1,"plan_name":"欧要建一个计划","plan_memo":"多到多得","deadline":"2018-12-10","create_person":"haozb","exeResult":"OK","CreateDate":"0000-00-00 00:00:00"}],"count":1}
    public function getchecksplanAll(){
        $re = input('post.');
        $rule =   [
            'token' => 'require'
        ];
        $message  =   [
            'token.require' => 'token必须提供' 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_checks_plan',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $orgname = getOrgToUserID($userid);
        $keystr = "";
        if(isset($re['keystr'])){$keystr = $re['keystr'] ;}
        //$rt = Db::s
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //select DISTINCT(p.plan_id),p.* from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = 'admin'
        // $rt = Db::select("assets",'asset_num')
        //     ->view('checks_detail','asset_num','checks_detail.asset_num=assets.asset_num')
        //     ->view('checks_plan','plan_id,plan_name,plan_memo,deadline,create_person,exeResult,CreateDate','checks_plan.plan_id=checks_detail.plan_id')
        //     ->view('orgtouser','orgID','assets.deparment_id=orgtouser.OrgID')
        //     ->where(['assets.deparment'=>'admin','checks_plan.Tag'=>'1'])
        //     ->select();
        //return $rt = getDataToJson('checks_plan',$data,'','asset_id',$page,$pagesize,$map2,$keystr);
        return getDataToJsonLike('checks_plan',$data,'*','CreateDate desc ',$page,$pagesize,'plan_name|create_person',$keystr);
        // $rt = select("select DISTINCT(p.plan_id),plan_name,plan_memo,deadline,create_person,exeResult,p.CreateDate from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = '".$orgname[0]['OrgName']."' and p.tag='1' and p.exeResult = '待盘点' and d.check_flag = '未盘点'");
        // $data['data']=$rt;
        // return json($data);
        
    }



    // 资产盘点计划查询
    // 实现对资产盘点计划的查询功能，支持分页（page和pagesize参数），支持对盘点计划名称的模糊查询（模糊查询参数是plan_name）；
    // 返回结果：{"data":[{"plan_id":1,"plan_name":"欧要建一个计划","plan_memo":"多到多得","deadline":"2018-12-10","create_person":"haozb","exeResult":"OK","CreateDate":"0000-00-00 00:00:00"}],"count":1}
    public function getchecksplan(){
        $re = input('post.');
        $rule =   [
            'token' => 'require'
        ];
        $message  =   [
            'token.require' => 'token必须提供' 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_checks_plan',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $orgname = getOrgToUserID($userid);
        //$rt = Db::s
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //select DISTINCT(p.plan_id),p.* from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = 'admin'
        // $rt = Db::select("assets",'asset_num')
        //     ->view('checks_detail','asset_num','checks_detail.asset_num=assets.asset_num')
        //     ->view('checks_plan','plan_id,plan_name,plan_memo,deadline,create_person,exeResult,CreateDate','checks_plan.plan_id=checks_detail.plan_id')
        //     ->view('orgtouser','orgID','assets.deparment_id=orgtouser.OrgID')
        //     ->where(['assets.deparment'=>'admin','checks_plan.Tag'=>'1'])
        //     ->select();

        $rt = select("select DISTINCT(p.plan_id),plan_name,plan_memo,deadline,create_person,exeResult,p.CreateDate from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = '".$orgname[0]['OrgName']."' and p.tag='1' and p.exeResult = '待盘点' and d.check_flag = '未盘点'");
        $data['data']=$rt;
        return json($data);
        
    }

    // 查询某一盘点计划下面的资产列表（任务表）
    // 实现对盘点计划的资产列表进行查询，支持分页（page和pagesize参数），支持对盘点计划名称的模糊查询（模糊查询参数是plan_name）；plan_id是必填项。
    // 返回结果：{"data":[{"detail_id":11,"plan_id":8,"asset_num":"J201601","check_flag":"未盘点","check_result":null,"check_memo":null,"pic1":null,"pic2":null,"pic3":null,"pic4":null,"pic5":null,"video":null,"check_date":null,"check_person":"","check_device_id":null}],"count":1}
    public function getchecksplanDetail(){
        $re = input('post.');
        $rule =   [
            'plan_id'  => 'require'
        ];
        $message  =   [
            'plan_id.require' => '盘点计划编号必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }

        $data = gettablecols('t_checks_detail',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        $rt = getdsJson('checks_detail',$data,'*','check_date desc ',$page,$pagesize);
        //$rt = gettable('t_Assets');
        return $rt;           
    }

    // 查询某一盘点计划下面的资产列表（任务表）
    // 实现对盘点计划的资产列表进行查询，支持分页（page和pagesize参数），支持对盘点计划名称的模糊查询（模糊查询参数是plan_name）；plan_id是必填项。
    // 返回结果：{"data":[{"asset_num":"14-16101JC","name":"He-3制冷机","deparment":"N08组","start_time":"2018-11-05 23:21:29","location":"*","detail_id":3,"plan_id":2,"check_flag":"已盘点","check_result":"正常","check_memo":"测试3","pic1":"","pic2":"","pic3":"","pic4":"","pic5":"","video":"","check_date":"2018-11-04 16:25:17","check_person":"haozb","check_device_id":"3"},{"asset_num":"12-13086","name":"机械泵","deparment":"N08组","start_time":"2018-11-05 23:21:29","location":"*","detail_id":1,"plan_id":2,"check_flag":"已盘点","check_result":"正常","check_memo":"1","pic1":"","pic2":"","pic3":"","pic4":"","pic5":"","video":"","check_date":"2018-11-04 16:25:17","check_person":"haozb","check_device_id":"3"},],"count":1}
    public function getchecksplanDetailV2(){
        $re = input('post.');
        $rule =   [
            'plan_id'  => 'require'
        ];
        $message  =   [
            'plan_id.require' => '盘点计划编号必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }

        $data = gettablecolsList(['t_checks_detail','t_assets'],$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }

        $list['data'] = Db::view('assets','asset_num, `name`, EPC,deparment,start_time,location ')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)
          ->order('check_date desc')
          ->page($page,$pagesize)
          ->select();
          //->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
        $list['count'] = Db::view('assets','*')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)          
          ->count('*');//->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
      return json($list);
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        // $rt = getdsJson('checks_detail',$data,'*','check_date desc ',$page,$pagesize);
        // //$rt = gettable('t_Assets');
        // return $rt;           
    }


    // 删除盘点计划下的部分设备清单
    // 盘点计划批量选择盘点设备后，通过该方法批量删除部分盘点设备。
    // 输入：plan_id (1) ;  ditail_id (1,2,3,4)  ;  token (b8a09425e44ef38f35a3b816a18d9263)
    // 返回结果：{"ID":"-1","msg":"无权删除别人创建的盘点计划清单."}
    // 返回结果（正确）：{"ID":"1","msg":"OK"}
    public function deletechecksplanDetail(){
        $re = input('post.');
        $rule =   [
            'detail_id'  => 'require',
            'token'  => 'require',
            'plan_id'  => 'require'
        ];
        $message  =   [
            'detail_id.require' => '盘点清单编号必须提供，格式1,2,3,4',
            'token.require' => 'token必须提供',
            'plan_id.require' => '盘点计划编号必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }


        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $creator = gettablecol('t_checks_plan',['plan_id'=>$re['plan_id']],'create_person');
        if($userid!=$creator){
            return rtjson('无权删除别人创建的盘点计划清单.'); 
        }

        
        $idArray=explode(",",$re['detail_id']);
        //$data =  array();
        $deletelist ="";
        //$existslist ="";
        $i = Db::table('t_checks_detail')->delete($idArray);
        if($i>0){
            return rtjson('OK','1');
        }else{
            return rtjson('添加数据失败。');
        }         
    }
    //删除已注册的读写器
    //需要输入必填参数reader_id 。
    //post 方式
    //return {"ID":"1","msg":"OK,deleted device succeed,device id is 5"}
    public function deleteReader(){
        $re = input('?post.reader_id')?input('post.reader_id'):'';
        if($re==''){return json(['ID'=>'-1','msg'=>'no data deleted. this reader_id is null.']);}
        delete("t_reader_register",["reader_id"=>$re]);  
        return json(['ID'=>'1','msg'=>"OK,deleted device succeed,device id is ".$re]);
    }

    //激活读写器
    //需要输入必填参数reader_name  （终端名称）,reader_type （终端类型：移动、固定）,maker_name （设备厂商）,deparment（使用部门），status （状态：启用，禁用），rdkey（设备序列号.
    //可选参数：location (所在位置)、model_num （设备型号）、start_time （启用日期）、
    //post 方式
    //return {"ID":"1","msg":"OK,更新成功."}
    public function putReaderRegist(){
        $re = input('post.');
        $rule =   [
            'reader_name'  => 'require',
            'reader_type'  => 'require',
            'maker_name'  => 'require',
            'status'    => 'require',
            'rdkey'    => 'require',
            'deparment'    => 'require'
        ];
        $message  =   [
            'reader_name.require' => '终端名称必须提供',  
            'reader_type.require' => '终端类型必须提供（手持、固定）',  
            'maker_name.require' => '终端厂商必须提供',  
            'status.require' => '注册终端状态信息必须提供',  
            'rdkey.require' => '注册终端设备序列号必须提供',  
            'deparment.require' => '使用部门必须提供',  
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $data = gettablecols('t_reader_register',$re);
        //$data['rdkey'] = guid();
        if(!isset($data['start_time'])){
            $data['start_time'] = date("Y-m-d");
        }
        if(!isset($data['status'])){
            $data['status'] = '启用';
        }
        if(isset($data['reader_id'])){
            unset($data['reader_id']);
        }
        $i = updata('t_reader_register',['rdkey'=>$re['rdkey']],$data);
        if($i>0){
            return rtjson('OK,更新成功.','1');
        }else{
            return rtjson('更新数据失败。');
        }
    }
    // 手持机获取服务器端盘点任务，根据手持端用户登录账号（token）确定获取的任务信息；
    // 使用post方式，参数：rdkey (读写器接入码)，token：用户身份认真令牌，
    // 返回信息：任务id，资产id，资产名称，资产部门，资产编号，标签编号
    //返回样例：{"data":[{"asset_num":"J201605","name":"物理所园区道路","financial_category":"固定资产-房屋构筑物","value":192705,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"*","plan_id":8,"detail_id":15,"check_flag":"未盘点","check_result":null}],"count":5}
    public function getcheckstask(){
        $re = input('get.');
        $rule =   [
            'token'  => 'require',
            'rdkey'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }

        
        //P($data);
        if(!isset($data['check_flag'])){
            $data['check_flag']='未盘点';
        }

        $token = $re['token'];
        $rdkey = $re['rdkey']; 
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }


        $orginfo = getOrgToUserID($loginstatus['msg']);
        $device = getdeviceinfoforrdkey($rdkey);
        if($device['reader_id']==""){
            return json(["ID"=>'-1',"msg"=>'设备尚未注册，注册后再操作.']);
        }
        if($device['status']=="禁用"){
            return json(["ID"=>'-1',"msg"=>'设备已禁用.请启用后再操作.']);
        }
        $data = gettablecolsList(['t_checks_detail','t_assets'],$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //p($orginfo);
        $data['deparment'] = $orginfo[0]['OrgName'];
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //select asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location from t_assets
        //select detail_id,plan_id,asset_num,check_flag,check_result,check_memo,pic1,pic2,pic3,pic4,pic5,video,check_date,check_person from t_checks_detail
        $list['data'] = Db::view('assets','asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location ')
          ->view('checks_detail','detail_id,plan_id,detail_id, check_flag,check_result','assets.asset_num = checks_detail.asset_num')
          ->where($data)
          ->order('location, start_time')
          ->page($page,$pagesize)
          ->select();
          //->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
        $list['count'] = Db::view('assets','*')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)          
          ->count('*');//->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
      return json($list);           
    }

    public function postcheckstask(){
        $re = input('post.');
        $rule =   [
            'token'  => 'require',
            'rdkey'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }

        
        //P($data);
        if(!isset($data['check_flag'])){
            $data['check_flag']='未盘点';
        }

        $token = $re['token'];
        $rdkey = $re['rdkey']; 
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }


        $orginfo = getOrgToUserID($loginstatus['msg']);
        $device = getdeviceinfoforrdkey($rdkey);
        if($device['reader_id']==""){
            return json(["ID"=>'-1',"msg"=>'设备尚未注册，注册后再操作.']);
        }
        if($device['status']=="禁用"){
            return json(["ID"=>'-1',"msg"=>'设备已禁用.请启用后再操作.']);
        }
        $data = gettablecolsList(['t_checks_detail','t_assets'],$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //p($orginfo);
        $data['deparment'] = $orginfo[0]['OrgName'];
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //select asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location from t_assets
        //select detail_id,plan_id,asset_num,check_flag,check_result,check_memo,pic1,pic2,pic3,pic4,pic5,video,check_date,check_person from t_checks_detail
        $list['data'] = Db::view('assets','asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location ')
          ->view('checks_detail','detail_id,plan_id,detail_id, check_flag,check_result','assets.asset_num = checks_detail.asset_num')
          ->where($data)
          ->order('location, start_time')
          ->page($page,$pagesize)
          ->select();
          //->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
        $list['count'] = Db::view('assets','*')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)          
          ->count('*');//->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
      return json($list);           
    }
    //打印标签回传接口（批量）。
    //输入参数说明：
    //print_list:是二元组，包括盘点任务表ID(asset_id)，通过json返回，格式如下：
    //[{"asset_id":"1","print_status":"已打印","print_command":"command_string"},{"asset_id":"2","print_status":"未打印","print_command":"command_string"},{"asset_id":"5","print_status":"待打印"},"print_command":"command_string"]
    //print_status为待打印，及打印机会自动获取打印。  
    // token ： 用户认证token， 
    // 返回样例：{"ID":"1","msg":"OK，changed  print_status 3 rows . "}
    public function ChangePrintStatusAll(){
        $re = input('post.');
        $rule =   [
            'print_list'  => 'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'print_list.require' => '打印的资产列表必须提供',
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $assetsData = [];
        $printlist = json_decode($re['print_list'],true); 
        //p($assetList);
        foreach ($printlist as $k => $v) {
                $assetsData[] = [   'asset_id' => $v['asset_id'],'print_status' => $v['print_status'],'print_command' =>'123'];
                //$assetsData[] = $temp2;           
        }
        //$i = insertAll('t_tag_print',$data);
        $i = saveAll($assetsData,'t_assets','asset_id');
        //$rttag = $i-$j;
        if($i>0){
            return rtjson('OK，changed  print_status '.$i.' rows . ','1');
        }else{
            return rtjson('打印异常.');
        }        
    }
    //打印标签回传接口（批量）。
    //输入参数说明：
    //print_list:是二元组，包括盘点任务表ID(asset_id)，通过json返回，格式如下：
    //[{"asset_id":"1","print_status":"已打印","print_command":"command_string"},{"asset_id":"2","print_status":"未打印","print_command":"command_string"},{"asset_id":"5","print_status":"待打印"},"print_command":"command_string"]
    //print_status为待打印，及打印机会自动获取打印。  
    // token ： 用户认证token， 
    // 返回样例：{"ID":"1","msg":"OK，changed  print_status 3 rows . "}
    public function SetPrintCommandAndStatus(){
        $re = input('post.');
        $rule =   [
            'print_list'  => 'require',
            'token'   =>  'require',
            'tag_tpl_id' =>'require'
        ];
        $message  =   [
            'print_list.require' => '打印的资产列表必须提供',
            'token.require' => '身份令牌必须提供',
            'tag_tpl_id.require' => '打印模板ID必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $assetsData = [];
        $printlist = json_decode($re['print_list'],true); 
        $str = gettablecol("t_tag_tpl",["tag_tpl_id"=>$re['tag_tpl_id']],"tag_command");
        
        //p($assetList);
        //$database =  Db::table('t_assets');

        foreach ($printlist as $k => $v) {
                $rows = Db::table('t_assets')->where(['asset_id' => $v['asset_id']])->find();
                //$rows['name'] = substr(0,10, $rows['name']);
                if(mb_strlen($rows['name'],"utf-8")<=8){
                    $rows['namsize'] = '15';
                }elseif (mb_strlen($rows['name'],"utf-8")<15) {
                    $rows['namsize'] = '10';
                }else{
                    $rows['namsize'] = '08';
                }
                if(mb_strlen($rows['deparment'],"utf-8")<=8){
                    $rows['depsize'] = '15';
                }elseif (mb_strlen($rows['deparment'],"utf-8")<15) {
                    $rows['depsize'] = '10';
                }else{
                    $rows['depsize'] = '08';
                }
                $command = str_repls($str,$rows);
                $assetsData[] = [   'asset_id' => $v['asset_id'],'print_status' => $v['print_status'],'print_command' =>$command];
                //$assetsData[] = $temp2;           
        }
        //$i = insertAll('t_tag_print',$data);
        $i = saveAll($assetsData,'t_assets','asset_id');
        //$rttag = $i-$j;
        if($i>0){
            return rtjson('OK，changed  print_status '.$i.' rows . ','1');
        }else{
            return rtjson('打印异常.');
        }        
    }

    //打印标签回传接口（批量）。
    //输入参数说明：
    //print_list:是四元组，包括盘点任务表ID(asset_num)，tid，print_rt打印结果，tag_type标签类型，通过json返回，格式如下：
    //[{"asset_id":"1","tid":"1234143143243","print_rt":"1","tag_type":"普通RFID"},{"asset_id":"2","tid":"1235573143243","print_rt":"0","tag_type":"普通RFID"}]
    //其中print_rt状态  1：正常；0：异常;正常时会更新t_asset表print_status状态为“已打印”
    // token ： 用户认证token， 
    // 返回样例：{"ID":"1","msg":"OK，print 3 tags,succeed 2 tags. "}
    public function putPrintStatusAll(){
        $re = input('post.');
        $rule =   [
            'print_list'  => 'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'print_list.require' => '打印的资产列表必须提供',
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        
        $data = [];
        $assetsData = [];
        $printlist = json_decode($re['print_list'],true); 
        //p($assetList);
        foreach ($printlist as $k => $v) {
            $temp = [
                'asset_id'=>$v['asset_id'],
                'tid'=>$v['tid'],
                'print_tag'=>$v['print_rt'],
                'printperson'=>$userid
                ];
            $data[] = $temp;
            if($v['print_rt'] == '1'){
                $temp2 = [   'asset_id' => $v['asset_id'],
                                    'print_status' => '已打印',
                                    'tag_type' => isset($v['tag_type']) ? $v['tag_type'] : "普通RFID"
                                ];
                $assetsData[] = $temp2;
            }            
        }
        $i = insertAll('t_tag_print',$data);
        $j = saveAll($assetsData,'t_assets','asset_id');
        //$rttag = $i-$j;
        if($i>0){
            return rtjson('OK，print '.$i.' tags , succeed '.$j.' tags . ','1');
        }else{
            return rtjson('打印异常.');
        }        
    }
    public function putPrintStatusAlldelphi(){
        $re = input('post.');
        $rule =   [
            'print_list'  => 'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'print_list.require' => '打印的资产列表必须提供',
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogindelphi($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        
        $data = [];
        $assetsData = [];
        $txt = str_replace(['\'[',',]\''], ['[',']'], $re['print_list']);
        $txt = str_replace(',]', ']', $txt);
        $printlist = json_decode($txt,true); 
        //p($assetList);
        foreach ($printlist as $k => $v) {
            $temp = [
                'asset_id'=>$v['asset_id'],
                'tid'=>$v['tid'],
                'print_tag'=>$v['print_rt'],
                'printperson'=>$userid
                ];
            $data[] = $temp;
            if($v['print_rt'] == '1'){
                $temp2 = [   'asset_id' => $v['asset_id'],
                                    'print_status' => '已打印',
                                    'tag_type' => isset($v['tag_type']) ? $v['tag_type'] : "普通RFID"
                                ];
                $assetsData[] = $temp2;
            }            
        }
        $i = insertAll('t_tag_print',$data);
        $j = saveAll($assetsData,'t_assets','asset_id');
        //$rttag = $i-$j;
        if($i>0){
            return rtjson('OK，print '.$i.' tags , succeed '.$j.' tags . ','1');
        }else{
            return rtjson('打印异常.');
        }        
    }
    
    //打印标签回传接口（单个回传）。
    //输入参数说明：
    //asset_id，tid，print_rt打印结果，tag_type标签类型。
    //其中print_rt状态  1：正常；0：异常;正常时会更新t_asset表print_status状态为“已打印”
    // token ： 用户认证token， 
    // 返回样例：{ID: "1", msg: "print tags OK , asset_id = 10 ."}
    public function putPrintStatus(){
        $re = input('post.');
        $rule =   [
            'asset_id'  => 'require',
            'tid'  => 'require',
            'print_rt'  => 'require',
            'tag_type'  => 'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'asset_id.require' => '资产ID必须提供',
            'tid.require' => '标签tid必须提供',
            'print_rt.require' => '打印结果必须提供',
            'tag_type.require' => '标签类型必须提供',
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];

        $data = [
            'asset_id'=>$re['asset_id'],
            'tid'=>$re['tid'],
            'print_tag'=>$re['print_rt'],
            'printperson'=>$userid
            ];

        $i = insert('t_tag_print',$data);
        $j = 0 ;
        if($i >0 and $re['print_rt']=='1'){
            $assetsData = ['print_status' => '已打印',
                       'tag_type' => isset($re['tag_type']) ? $re['tag_type'] : "普通RFID"
                      ];
            $j = updata('t_assets',['asset_id' => $re['asset_id']],$assetsData);

        }
        
        if($i>0){
            if($j>0){
                return rtjson('print tags OK , asset_id = '.$re['asset_id'].' .','1');
            }else{
                return rtjson(' print tags failed ,  asset_id = '.$re['asset_id'].' . ');
            }
            
        }else{
            return rtjson('打印异常.');
        }        
    }

    //盘点结果上传接口
    //手持端，通过该接口将结果上传到服务器端。
    //输入参数说明：
    //checkedlist:是三元组，包括盘点任务表ID(detail_id)，盘点结果，盘点说明，通过json返回，格式如下：
    //[{"detail_id":"20","check_result":"1","check_memo":"测试1"},{"detail_id":"21","check_result":"1","check_memo":"测试2"},{"detail_id":"22","check_result":"0","check_memo":"测试3"}]
    //其中check_result状态  1：正常；0：异常
    // rdkey ： 设备识别码,合法设备才能盘点，设备识别码做成配置文件，相当于设备序列号。
    // token ： 用户认证token， 
    // 返回样例：{"ID":"1","msg":"OK，updated 3 rows."}
    public function putcheckstask(){
        $re = input('post.');
        $rule =   [
            'checkedlist'  => 'require',
            'rdkey'   =>  'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'checkedlist.require' => '盘点结果必须提供',
            'token.require' => '身份令牌必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogindelphi($token);
        
        $userid = $loginstatus['msg'];
        $device = getdeviceinfoforrdkey($re['rdkey']);
        //$pid = $re['plan_id'];
        //前台传过来json，后台解析成数组；
        $data = [];
        $list = str_replace("'","\"",$re['checkedlist']);
        $assetList = json_decode($list,true); 
        //p($assetList);
        foreach ($assetList as $k => $v) {
            $temp = [
                'detail_id'=>$v['detail_id'],
                'check_result'=>$v['check_result']=='1'?"正常" : "异常",
                'check_memo'=>$v['check_memo'],
                'pic1'=>isset($re['video']) ? $re['pic1'] : "",
                'pic2'=>isset($re['video']) ? $re['pic2'] : "",
                'pic3'=>isset($re['video']) ? $re['pic3'] : "",
                'pic4'=>isset($re['video']) ? $re['pic4'] : "",
                'pic5'=>isset($re['video']) ? $re['pic5'] : "",
                'video'=>isset($re['video']) ? $re['video'] : "",
                'check_flag'=>'已盘点',
                'check_person'=>$userid,
                'check_device_id'=>$device['reader_id']
                ];
                $data[] = $temp;
        }
        $i = saveAll($data,'t_checks_detail','detail_id');
        if($i>0){
            return rtjson('OK，updated '.$i.' rows.','1');
        }else{
            return rtjson('更新数据失败。');
        }
    }
    //查询注册设备信息
    //输入参数：reader_id
    // reader_name
    // location
    // reader_type
    // maker_name
    // model_num
    // status
    // start_time
    // rdkey
    // deparment
    // reguser
    // token
    // keystr  模糊查询，对reader_name、location、deparment、reguser进行模糊查询
    // 返回样例：{"data":[{"reader_id":1,"reader_name":"RFID手持读写器","location":null,"reader_type":"手持","maker_name":"成为科技","model_num":"C71","status":"启用","start_time":"2018-11-03","rdkey":"DBF1945F-04AE-5DF9-FF0A","deparment":null,"reguser":null}],"count":1}
    public function getRegDevice(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require'
        ];
        $message  =   [
            'token.require' => 'token必须提供',
        ];


        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }


        
        $data = gettablecols('t_reader_register',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }

        
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //$rt = getdsJson('reader_register',$data,'*','reader_id',$page,$pagesize);
        //$rt = gettable('t_Assets');
        //return $rt; 
        $keystr = $re['keystr'];
        //$re['value']='0';
        $likecols = "reader_name|location|deparment|reguser";

        $rt = getDataToJsonLike('reader_register',$data,'','reader_id',$page,$pagesize,$likecols,$keystr);
        //$rt = gettable('t_Assets');
        return $rt; 
        

        //$i = insert('t_reader_register',$data);
        // if($i>0){
        //     return rtjson('OK，注册成功，待审核。设备ID：'.$data["rdkey"],'1');
        // }else{
        //     return rtjson('注册失败。');
        // }
    }
    //新购手持机注册
    //注册先从手持端发起，手持端安装tvams软件后，点击【设置】---【注册】出发服务器端注册动作
    //输入参数说明：
    //rdkey:字符串；
    // 返回样例：{"ID":"1","msg":"注册成功，待管理员审核."}
    public function regDevice(){
        $re = input('post.');
        $rule =   [
            'rdkey'   =>  'require',
            'userid'   =>  'require'
        ];
        $message  =   [
            'userid.require' => '用户名必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        //INSERT INTO t_reader_register(reader_name,reader_type,`status`,rdkey)values('RFID手持读写器','手持','待审核','0010432254325563462');

        $data["reguser"] = $re['userid'];
        //$data["rdkey"] = $re['rdkey'];
        

        $dv = getdeviceinfoforrdkey($re['rdkey']);
        if($dv['rdkey']!=''){
             $i = updata('t_reader_register',['rdkey'=>$re['rdkey']],$data);
            if($i>0){
                return rtjson('设备已提交注册申请，本次操作自动更新了注册人信息。设备ID：'.$re["rdkey"],'1');
            }else{
                return rtjson('注册失败，设备已注册，不能重复注册。');
            }
        }else{
            $data["rdkey"] = $re['rdkey'];
            $data["reader_name"] = 'RFID手持读写器';
        $data["reader_type"] = '手持';
        $data["status"] = '待审核';
            $i = insert('t_reader_register',$data);
            if($i>0){
                return rtjson('OK，注册成功，待审核。设备ID：'.$data["rdkey"],'1');
            }else{
                return rtjson('注册失败。');
            }
        }

        
    }
    // 手持机获取当前用户已经盘点的资产列表；
    // 使用post方式，参数：rdkey (读写器接入码)，token：用户身份认真令牌，plan_id 盘点计划ID
    // 返回信息：任务id，资产编号，资产名称，资产部门，EPC，盘点结果，盘点描述（建议：首页显示资产名称，盘点结果，资产编号）,page和pagesize可选，默认当前页0，每页10条数据
    //返回样例：{"data":[{"asset_num":"J201602","name":"围墙及大门（物理所南院）","financial_category":"固定资产-房屋构筑物","value":706720,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"中科院物理研究所南院","detail_id":21,"plan_id":8,"check_memo":"1","check_flag":"已盘点","check_result":"正常","check_date":"2018-11-04 16:20:48","check_person":"haozb"},{"asset_num":"J201601","name":"M楼","financial_category":"固定资产-房屋构筑物","value":234738000,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"中科院物理研究所南院","detail_id":20,"plan_id":8,"check_memo":"测试1","check_flag":"已盘点","check_result":"正常","check_date":"2018-11-04 16:20:23","check_person":"haozb"}],"count":6}
    public function getMyCheckedList()
    {
        $re = input('post.');
        $rule =   [
            'token'  => 'require',
            'rdkey'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $token = $re['token'];
        $rdkey = $re['rdkey']; 
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }        
        $orginfo = getOrgToUserID($loginstatus['msg']);
        $device = getdeviceinfoforrdkey($rdkey);
        if($device['reader_id']==""){
            return json(["ID"=>'-1',"msg"=>'设备尚未注册，注册后再操作.']);
        }
        if($device['status']=="禁用"){
            return json(["ID"=>'-1',"msg"=>'设备已禁用.请启用后再操作.']);
        }

        $data = gettablecolsList(['t_checks_detail','t_assets'],$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $data['check_person']=$loginstatus['msg'];
        $data['deparment'] = $orginfo[0]['OrgName'];
        if(!isset($data['check_flag'])){
            $data['check_flag']='已盘点';
        }
        $list['data'] = Db::view('assets','asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location ')
          ->view('checks_detail','detail_id,plan_id,check_memo, check_flag,check_result,check_date,check_person','assets.asset_num = checks_detail.asset_num')
          ->where($data)
          ->order('check_date desc ')
          ->page($page,$pagesize)
          ->select();
          //->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
        $list['count'] = Db::view('assets','*')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)          
          ->count('*');//->where('name|maker_name|assets.asset_num|person|deparment','like','%'.$keystr.'%')
      return json($list);   
    }


    // 手持机获取当前手持机已经盘点的资产列表；
    // 使用post方式，参数：rdkey (读写器接入码)，token：用户身份认真令牌，plan_id 盘点计划ID
    // 返回信息：任务id，资产编号，资产名称，资产部门，EPC，盘点结果，盘点描述（建议：首页显示资产名称，盘点结果，资产编号）,page和pagesize可选，默认当前页0，每页10条数据
    //返回样例：{"data":[{"asset_num":"J201602","name":"围墙及大门（物理所南院）","financial_category":"固定资产-房屋构筑物","value":706720,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"中科院物理研究所南院","detail_id":21,"plan_id":8,"check_memo":"1","check_flag":"已盘点","check_result":"正常","check_date":"2018-11-04 16:20:48","check_person":"haozb"},{"asset_num":"J201601","name":"M楼","financial_category":"固定资产-房屋构筑物","value":234738000,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"中科院物理研究所南院","detail_id":20,"plan_id":8,"check_memo":"测试1","check_flag":"已盘点","check_result":"正常","check_date":"2018-11-04 16:20:23","check_person":"haozb"}],"count":6}
    public function getDeviceCheckedList()
    {
        $re = input('post.');
        $rule =   [
            'token'  => 'require',
            'rdkey'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'rdkey.require' => '设备接入码必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $token = $re['token'];
        $rdkey = $re['rdkey']; 
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $orginfo = getOrgToUserID($loginstatus['msg']);
        $device = getdeviceinfoforrdkey($rdkey);
        if($device['reader_id']==""){
            return json(["ID"=>'-1',"msg"=>'设备尚未注册，注册后再操作.']);
        }
        if($device['status']=="禁用"){
            return json(["ID"=>'-1',"msg"=>'设备已禁用.请启用后再操作.']);
        }

        $data = gettablecolsList(['t_checks_detail','t_assets'],$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //$data['check_person']=$loginstatus['msg'];
        $data['check_device_id']=$device['reader_id'];
        $data['deparment'] = $orginfo[0]['OrgName'];
        if(!isset($data['check_flag'])){
            $data['check_flag']='已盘点';
        }
        $list['data'] = Db::view('assets','asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location ')
          ->view('checks_detail','detail_id,plan_id,check_memo, check_flag,check_result,check_date,check_person,check_device_id','assets.asset_num = checks_detail.asset_num')
          ->where($data)
          ->order('check_date desc ')
          ->page($page,$pagesize)
          ->select();
        $list['count'] = Db::view('assets','*')
          ->view('checks_detail','*','assets.asset_num = checks_detail.asset_num')
          ->where($data)          
          ->count('*');
      return json($list);   
    }

    // 获取资产列表，并将资产编号转换成16进制编码；
    // 使用post方式，参数：token：用户身份认证令牌
    // 返回信息：资产编号，资产名称，资产部门，EPC（转码后的资产编号）,page和pagesize可选，默认当前页0，每页10条数据
    //返回样例：[{"asset_num":"00-09009","name":"超薄切片机","financial_category":"固定资产-通用设备","value":354497,"parent_name":null,"model_num":"RMC Powertome XL","person":"禹日成","deparment":"A01组","start_time":"2018-11-01 13:29:04","location":"DB22","bind_status":"未绑定","EPC":"30302d3039303039"},{"asset_num":"00-11095","name":"压片机","financial_category":"固定资产-通用设备","value":5000,"parent_name":null,"model_num":"769YP-24B","person":"许燕萍","deparment":"A02组","start_time":"2018-11-01 13:29:04","location":"A楼148","bind_status":"未绑定","EPC":"30302d3131303935"}]
    public function getAssetBase16()
    {
        $re = input('post.');
        $rule =   [
            'token'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $data = gettablecols('t_assets',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $keystr =  "%".$re['keystr']."%";        
        $ds   =   Db::name('assets')
                    ->where($data)
                    ->where('deparment|location|name|asset_num' ,'like',$keystr)
                    ->field('asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location,bind_status,epc ')
                    ->order('deparment,start_time')
                    ->page($page,$pagesize)
                    ->select();
        $dscount = Db::name('assets')
                    ->where($data)
                    ->where('deparment|location|name|asset_num' ,'like',$keystr)
                    ->count('*');
        $rt['data'] = $ds;
        // foreach ($ds as $key => $value) {
        //     $value['EPC'] = bin2hex($value['asset_num']);
        //     $rt['data'][] = $value;
        // }
        $rt['count'] = $dscount ;
      return json($rt);   
    }
    public function getAssetBase16get()
    {
        $re = input('get.');
        $rule =   [
            'token'  => 'require',
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $data = gettablecols('t_assets',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $ds   =   Db::name('assets')
                    ->where($data)
                    ->field('asset_num, `name`, financial_category,`value`, parent_name,model_num,person,deparment,start_time,location,bind_status ')
                    ->order('deparment,start_time')
                    ->page($page,$pagesize)
                    ->select();
        $dscount = Db::name('assets')
                    ->where($data)
                    ->count('*');
        $rt = [];
        foreach ($ds as $key => $value) {
            $value['EPC'] = bin2hex($value['asset_num']);
            $rt['data'][] = $value;
        }
        $rt['count'] = $dscount ;
      return json($rt);   
    }

    
    // 新建或更新标签模板接口
    // 输入参数必须有tpl_name（模板名称必须提供），token：用户令牌，tag_command（模板命令必须提供）和img(模板例图必须提供)。
    //更新数据：需要提供tag_tpl_id;作为更新主键。
    // 新增模板后返回tag_tpl_id；
    //新增返回样例：{"ID":"1","msg":"新增模板成功，模板ID：9"}
    //更新返回样例：{"ID":"1","msg":"新增计划成功，模板ID：9"}
    public function putTpl(){
        $re = input('post.');
        $rule =   [
            'tpl_name'  => 'require',
            'tag_command'      => 'require',
            'img'      => 'require',            
            'token'      => 'require'
        ];
        $message  =   [
            'tpl_name.require' => '模板名称必须提供',  
            'tag_command.require' => '模板命令必须提供', 
            'img.require' => '模板例图必须提供', 
            'token.require' => '认证令牌token必须提供', 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        $data = gettablecols('t_tag_tpl',$re);
        $data['CreatePerson']=$userid;
        if(isset($data['tag_tpl_id'])){
            $i = updata('t_tag_tpl',['tag_tpl_id'=>$data['tag_tpl_id']],$data);
            if($i>0){
                return rtjson('更新模板成功，模板ID：'.$data['tag_tpl_id'],'1');
            }else{
                return rtjson('更新数据失败。');
            }
        }else{
            $i = insert('t_tag_tpl',$data);
            if($i>0){
                return rtjson('新增模板成功，模板编号：'.$i,'1');
            }else{
                return rtjson('添加数据失败。');
            }
        }
        
    }
    // 删除模板
    // 输入参数：tag_tpl_id（模板id）.
    // 返回样例：{"ID":"1","msg":"模板已删除，模板编号：9"}
    public function deleteTpl(){
        $re = input('post.');
        $rule =   [
            'tag_tpl_id'  => 'require',
            'token' => 'require'
        ];
        $message  =   [
            'tag_tpl_id.require' => '模板id必须提供',
            'token.require' => 'token必须提供' 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        //userlog($oid,$otype='user',$detail,$modelname='sys',$ltype='sys')

        $data = gettablecols('t_tag_tpl',$re);
        //$data['create_person']=$userid;
        if(isset($data['tag_tpl_id'])){
            $i = delete('t_tag_tpl',['tag_tpl_id'=>$data['tag_tpl_id']]);
            if($i>=0){
                userlog($userid,'user','delete  from t_tag_tpl where tag_tpl_id = \''.$data['tag_tpl_id'].'\'','res','deleteTpl');
                return rtjson('删除模板成功，模板ID：'.$data['tag_tpl_id'],'1');

            }else{
                return rtjson('删除数据失败。');
            }
        }
        
    }

    // 标签模板查询
    // 实现对标签模板的查询功能，支持分页（page和pagesize参数），支持对模板名称或者部门的模糊查询（模糊查询参数是keystr）；支持对其他所有字段的精确查询
    // 返回结果：json
    public function getTpl(){
        $re = input('post.');
        $rule =   [
            'token' => 'require'
        ];
        $message  =   [
            'token.require' => 'token必须提供' 
        ];
        $keystr = "";
        if(isset($re['keystr'])){
            $keystr = $re['keystr'] ;
        }
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $loginInfo = islogin($re['token']);
        //$user = getUserfortocken($re['token']);
        if($loginInfo['ID']=="-1"){
            return rtjson('登录超时');
        }
        $userid = $loginInfo['msg'];
        if(isset($re['page'])){
            $page = $re['page'] ;
        }else{
            $page = 0;
        }
        if(isset($re['pagesize'])){
            $pagesize = $re['pagesize'] ;
        }else{
            $pagesize = 10;
        }
        $data = gettablecols('t_tag_tpl',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $orgname = getOrgToUserID($userid);
        return getDataToJsonLike('tag_tpl',$data,'*','CreateDate desc',$page,$pagesize,'tpl_name|tpl_deparment',$keystr);
        //$rt = Db::s
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        //select DISTINCT(p.plan_id),p.* from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = 'admin'
        // $rt = Db::select("assets",'asset_num')
        //     ->view('checks_detail','asset_num','checks_detail.asset_num=assets.asset_num')
        //     ->view('checks_plan','plan_id,plan_name,plan_memo,deadline,create_person,exeResult,CreateDate','checks_plan.plan_id=checks_detail.plan_id')
        //     ->view('orgtouser','orgID','assets.deparment_id=orgtouser.OrgID')
        //     ->where(['assets.deparment'=>'admin','checks_plan.Tag'=>'1'])
        //     ->select();

        // $rt = select("select DISTINCT(p.plan_id),plan_name,plan_memo,deadline,create_person,exeResult,p.CreateDate from t_checks_plan p , t_checks_detail d,t_assets a  where p.plan_id = d.plan_id and a.asset_num = d.asset_num and a.deparment = '".$orgname[0]['OrgName']."' and p.tag='1' and p.exeResult = '待盘点' and d.check_flag = '未盘点'");
        // $data['data']=$rt;
        // return json($data);
        
    }



    public function addepc()
    {
        echo bin2hex('00-11038');
    }

//################################################################################################

    public function tobase16($value='')
    {
        return bin2hex($value);
    }

    public function tostring($value='')
    {
        return hextostr($value);
    }



    //para
    // 针对一个用户插入多个角色信息，参数：UserID，RoleIDList是RoleID逗号分割字符串，例如：
    // para : UserID,RoleIDList=admin,bjyydx
    // Post提交请求
    public function insertUserToRoleAll(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $tag = 1;
        $message = "";
        $rule =   [
            'RoleIDList'  => 'require',
            'UserID'=>'require',
        ];
        $message  =   [
            'UserID.require' => '用户ID必须提供',  
            'RoleIDList.require'     => '角色ID必须提供', 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>'校验失败:'.$validate->getError()]);
        }
        $insertlist  = "";
        $existslist = "";
        $RoleIDArray=explode(",",$re['RoleIDList']);
        $data =  array();

        foreach ($RoleIDArray as $key => $value) {
            //array_push($data, ['RoleID'=> $value , 'OrgID'=> $re['OrgID'] ]);
            $RoleID = gettablecol('t_roletouser',['UserID'=>$re['UserID'],'RoleID'=>$value],'RoleID');
            if($RoleID==null){
                $insertlist.= $value.',';
                array_push($data,['UserID'=>$re['UserID'],'RoleID'=>$value]) ;
            }else{
                $existslist.=$value.",";
            }
        }
        if(insertAll('t_roletouser',$data)){
            return json(["ID"=>'1',"Insertlist"=>$insertlist,"existslist"=>$existslist]);
        }else{
            return json(["ID"=>'-1',"Insertlist"=>$insertlist,"existslist"=>$existslist]);
        };
        
    }
    //para
    // 针对一个角色插入多个用户信息，参数：RoleID，UserIDList是UserID逗号分割字符串，例如：
    // para : RoleID,UserIDList=user1,user2
    // Post提交请求
    public function insertRoleToUserAll(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $tag = 1;
        $message = "";
        $rule =   [
            'RoleID'  => 'require',
            'UserIDList'=>'require',
        ];
        $message  =   [
            'UserIDList.require' => '用户ID必须提供',  
            'RoleID.require'     => '角色ID必须提供', 
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>'校验失败:'.$validate->getError()]);
        }
        $insertlist  = "";
        $existslist = "";
        $RoleIDArray=explode(",",$re['UserIDList']);
        $data =  array();

        foreach ($RoleIDArray as $key => $value) {
            //array_push($data, ['RoleID'=> $value , 'OrgID'=> $re['OrgID'] ]);
            $RoleID = gettablecol('t_roletouser',['UserID'=>$value,'RoleID'=>$re['RoleID']],'RoleID');
            if($RoleID==null){
                $insertlist.= $value.',';
                array_push($data,['UserID'=>$value,'RoleID'=>$re['RoleID']]) ;
            }else{
                $existslist.=$value.",";
            }
        }
        if(insertAll('t_roletouser',$data)){
            return json(["ID"=>'1',"Insertlist"=>$insertlist,"existslist"=>$existslist]);
        }else{
            return json(["ID"=>'-1',"Insertlist"=>$insertlist,"existslist"=>$existslist]);
        };

    }

    /*para
    根据RoleID，获取对应的菜单信息
    para : RoleID
    */
    public function getFuns(){
        $re = input('?post.RoleID')?input('post.RoleID'):'';
        if($re!=''){
           return getFunToRole($re); 
        }
        return json(['ID'=>'-1','msg'=>'RoleID Error.']);
    }


    public function test(){
        $token_url = "https://passport.escience.cn/oauth2/token";
        $map['client_id']='67571';
        $map['client_secret']='UN9Y3foQdM1WvtlWMZMvXELlWYADHkXa';
        $map['grant_type']='authorization_code';
        $map['redirect_uri']='http://www.dev.arp.cn/reddit_callback';
        $map['code']='http://www.dev.arp.cn/reddit_callback';
        
        $respose = http($token_url, $map, 'POST', [], $timeout = 5);
        echo $respose;

        //return gettable("t_sysuser");
        //return gettable("t_sysuser");
        //echo json([1::100]);
        //email('haozb@zhong-ying.com',"test","ceshiceshi","13311568625@163.com","21249904@qq.com");
       //echo userlog('haozb','user',"ceshi",'sys','sys');
        //$rt = getUserfortocken('f31530fc958ed0aecb816e2ee486ee87');
        //p($rt);
        //return json($rt) ;//json();
        // $str="1#2#3#4#5#";
        // $var=explode("#",$str);
        // print_r($var);
        // echo gettablecol("t_sysuser",["UserID"=>'haozb'],'UserEmail');
        // lg("nimen hao !");
        // updata('t_sysuser','',['Password'=>'11111','UserID'=>'haozb123']);
     
        // //email('haozb@zhong-ying.com','222','22222222','15237374870@163.com');
        // echo randomStr('23','num');
        // return json(getcols(['t_sysuser','t_systree']));
        // return sel('select * from t_sysuser');
        lg("sdfafdsafdsafdsa");
        //echo input("get.id");
    }
    
    /*
    找回密码,输入UserID，系统会根据UserID对应的Email以邮件的方式发验证码给用户，用户铜鼓验证码更改密码。成功后返回邮箱地址。ID状态1
    */
    //找回密码
    //para : UserID
    public function getPassword(){
      $re = input('?post.UserID')?input('post.UserID'):'';
      if($re==''){return "请输出用户名";}
      $emails = gettablecol('t_sysuser',"UserID='".$re."'",'UserEmail');
      $rt = array('ID' => '-1','msg'=>'Error' );      
      $code = getcode($re,'password',4,'num');
      if($code !="-1"){
        if(email($emails,"密码找回",$re." 您好！<br><br>&nbsp;&nbsp;&nbsp;&nbsp;系统验证码：".$code."。请注意保密，不要泄露给他人，请在5分钟内完成密码修改.")){
            $rt['ID'] =  "1";
            $rt["msg"] = "邮件发送成功.";
            $rt["email"] = $emails;
        }        
      }else{
        $rt['ID'] =  "-1";
        $rt["msg"] = "生成随机码错误.";
      }
      return json($rt);
    }

    /*para
    找回密码,输入UserID，系统会根据UserID对应的Email以邮件的方式发验证码给用户，用户铜鼓验证码更改密码。成功后返回邮箱地址。ID状态1
    */
    //找回密码
    //para : UserID,repassword,Password,Code
    public function changePassswordToCode(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $tag = 1;
        $message = "";
        $rule =   [
            'UserID'  => 'require',
            'repassword'=>'require|confirm:Password',
            'Password'=>'require',
            'Code'=>'require|max:25',
        ];    
        $message  =   [
            'UserID.require' => '用户名必须提供',  
            'Password.require'     => '新密码必须提供',
            'repassword.require'     => '确认密码必须提供',
            'repassword.confirm'   => '新密码两次输入的不一致',
            'Code.require'     => '验证码必须提供', 
        ];        
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>'校验错误:'.$validate->getError() ]);
        }

        $uid = gettablecol('t_sysuser',"UserID='".$re['UserID']."'",'InnerID');

        if($uid==null){
            return json(['ID' => '-1','msg'=>'用户名不存在']);
        }
        
        $code = gettablecol('t_code',["UserID"=>$re['UserID'],'type'=>'password'],'codenum');

        if($code!=$re['Code']){
            return json(['ID' => '-1','msg'=>'验证码错误']);
        }

        $timer=strtotime('now');
        $extdate = gettablecol('t_code',["UserID"=>$re['UserID'],'type'=>'password'],'extDate');
        if($extdate<$timer){
            return json(['ID' => '-1','msg'=>'验证码过期']);
        }
        $id = updata('t_sysuser',['InnerID'=>$uid],['Password'=>$re['Password']]);
        if($id==1){
            $rt['ID'] =  $id ;
            $rt["msg"] = "OK";
        }else{
            $rt['ID'] =  "-1";
            $rt["msg"] = '密码更新失败';
        }
        return json($rt);

    }



    //注册用户
    //para : UserID,repassword,Password,UserEmail,UserMobile,UserName
    public function regUser(){
        //$id = input('?post.')?input('post.'):""; 
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $tag = 1;
        $message = "";
        $rule =   [
            'UserID'  => 'regex:^[A-Za-z][_0-9a-zA-Z]{4,25}',
            'repassword'=>'require|confirm:Password',
            'Password'=>'require',
            'UserEmail'=>'require|email',
            'UserMobile'=>'require',
        ];
    
        $message  =   [
            'UserID.regex' => '用户名必须为5~25位，以字母开通，字母数字和下划线组成的字符串',  
            'UserEmail.require'     => '邮箱必须提供', 
            'UserEmail.email'     => '邮箱不合法',  
            'Password.require'     => '新密码必须提供',
            'repassword.require'     => '确认密码必须提供',
            'repassword.confirm'   => '新密码两次输入的不一致',
            'UserMobile.require'     => '手机号码必须提供', 
        ];

        
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if($result){
            if(gettablecol('t_sysuser',"UserID='".$re['UserID']."'",'InnerID')!=null){
                $message=$re['UserID']."用户已注册，请重新设置.";
                $tag = 0;
            }
            if(gettablecol('t_sysuser',"UserMobile='".$re['UserMobile']."'",'InnerID')!=null){
                $message="手机号".$re['UserMobile']."已被其他账号注册，请重新设置.";
                $tag = 0;
            }
            if(gettablecol('t_sysuser',"UserEmail='".$re['UserEmail']."'",'InnerID')!=null){
                $message="邮箱".$re['UserEmail']."已被其他账号注册，请重新设置.";
                $tag = 0;
            }
            if($tag==1){
                $rt['ID'] =  insert('t_sysuser',$re);
                $rt["msg"] = "OK";
            }else{
                $rt['ID'] =  "-1";
                $rt["msg"] = $message;
            }

        }else{
            //echo "no:".$validate->getError();;
            $rt['ID'] =  "-1";
            $rt["msg"] = $validate->getError();
        }        
        return json($rt);
    }
    //--------说明-----------//
    //para : UserID  
    //####para 返回ID=0  可以注册 //
    //####para 返回ID=1  已经存在，不可注册//
    public function isExistUser(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        if(isset($re['UserID'])and $re['UserID']!=""){
            if(gettablecol('t_sysuser',"UserID='".$re['UserID']."'",'InnerID')!=null){
                $message=$re['UserID']."用户已注册，请重新设置.";
                $rt["ID"] = "1";
                $rt["msg"] = $re['UserID']."用户名已存在";
            }else{
               $rt["ID"] = "0";
               $rt["msg"] = $re['UserID']."可以注册"; 
            }
        }
        return json($rt);
    }

    
    //#############para说明##############//
    /*------UserID   要更改的用户名----//
    ##------Password 新密码------------//
    ##------repassword   确认新密码----//
    ##------OldPassword   原密码-------*/
    public function putPassword(){
        $rt = array('ID' => '-1','msg'=>'init Error' );
        $re = input('post.');
        $rule =   [
            'UserID'  => 'require|max:25',
            'repassword'=>'require|confirm:Password',
            'Password'=>'require',
            'OldPassword'=>'require',
        ];
    
        $message  =   [
            'UserID.require' => '用户名必须提供',  
            'OldPassword.require'     => '原有密码必须提供',      
            'Password.require'     => '新密码必须提供',
            'repassword.require'     => '确认密码必须提供',
            'repassword.confirm'   => '新密码两次输入的不一致',
        ];

       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if($result){
            if(gettablecol('t_sysuser',['UserID'=>$re['UserID']],"Password")!=$re['OldPassword']){
                return json(['ID' => '-1','msg'=>'原密码输入错误!']);
            }
            $map["UserID"]=preg_replace("/'/", "", $re['UserID']);
                    $mapupdate["Password"] = $re['Password'];
                    $i = updata("t_sysuser",$map,$mapupdate);
                    if($i>0){
                        $rt["ID"] = "1";
                        $rt["msg"] = $re['UserID']."用户的密码已修改";
                    }else {
                        $rt["ID"] = "0";
                        $rt["msg"] = "密码没有修改，新密码与当前密码一致";
                    }
        }else{
            $rt["ID"] = "0";
            $rt["msg"] = $validate->getError();
            //return $validate->getError();
        }
        return json($rt);
    }
    //#############para说明##############//
    /*------UserID   要更改的用户名------//
    ##------Password 重置密码------------*/
    //管理员重置用户密码，初始化
    public function ResetPassword(){
        $rt = array('ID' => '-1','msg'=>'init Error' );
        $re = input('post.');
        $currentUser = array();
        if(input('?post.token')){
           // $currentUser("");
            echo input('post.token') ;
            $currentUser = getUserfortocken(input('post.token'));
        }else{
            $rt["ID"] = "0";
            $rt["msg"] = "登录超时.";
            return json($rt);
        }
        $rule =   [
            'UserID'  => 'require',
            'Password'=>'require',
        ];
    
        $message  =   [
            'UserID.require' => '用户名必须提供',
            'Password.require'     => '提供初始化密码',
        ];

       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if($result){
            $map["UserID"]=preg_replace("/'/", "", $re['UserID']);
                    $mapupdate["Password"] = $re['Password'];
                    $i = updata("t_sysuser",$map,$mapupdate);

                    if($i>0){
                        $rt["ID"] = "1";
                        $rt["msg"] = $re['UserID']."用户的密码已修改";
                        userlog($currentUser["UserID"],'user',"管理员密码重置：用户名:".$re['UserID'],'sys','sys');
                    }else {
                        $rt["ID"] = "0";
                        $rt["msg"] = "密码没有修改，新密码与当前密码一致";
                    }
        }else{
            $rt["ID"] = "0";
            $rt["msg"] = $validate->getError();
            //return $validate->getError();
        }
        return json($rt);
    }

    //para : 更新用户扩展信息
    //para : UserID  , post,
    public function putUser(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        if(isset($re['UserID'])and $re['UserID']!=""){
            $user = gettablecol('t_sysuserext',"UserID='".$re['UserID']."'",'InnerID');
            if($user!=null){
                $re["InnerID"] = $user;
                $i = updata('t_sysuserext',null,$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "OK";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "数据没有更新.";
                }
            }else{
                $i = insert('t_sysuserext',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "OK";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "添加数据失败。";
                }
            }
            
        }else{
            $rt["ID"] = "0";
            $rt["msg"] = $re['UserID']."尚未注册"; 
        }
        return json($rt);
    }

    //输入验证
    public function veri(){
        $rule = [
            ['name','require|max:25','名称必须|名称最多不能超过25个字符'],
            ['age','number|between:1,120','年龄必须是数字|年龄必须在1~120之间'],
            ['email','email','邮箱格式错误']
        ];
        $field = [
            'name'  => '名称',
            'email' => '邮箱',    
        ];
        $msg = [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'name.min'   => '名称最小不能小于5个字符',
            'name.token'  => 'toke校验失败！',
            //'email'        => '邮箱格式错误',
        ];
        $validate = new Validate([
            'name'  => 'require|max:25|min:5|token',
            'email' => 'email'
        ],$msg,$field);
        $data = [
            'name'  => 'thinkphp',
            'email' => 'thinkphpqq.com',
            'token' => 'dsfafdsafasfsadfsafdsafdsafsafsafdsaf'
        ];
        $scene = [
            'edit'  =>  ['name','age'],
        ];        
        $result = $validate->scene('edit')->check($data);

        $result = $this->validate(
        [
            'name'  => 'thinkphp',
            'email' => 'thinkphp@qq.com',
        ],
        [
            'name'  => 'require|max:25|min:5',
            'email'   => 'email',
        ]);
        if(true !== $result){
            // 验证失败 输出错误信息
            dump($result);
        }

        if (!$validate->check($data)) {
            dump($validate->getError());
        }else{
            echo "OK";
        }
    }

    //para 登录
    //输入用户名和口令，返回用户信息、扩展信息、组织信息、菜单信息  
    //para : UserID  , Password
    public function login(){
        $rt = array('stats' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'UserID'  => 'require',
            'Password'=>'require'
        ];
    
        $message  =   [
            'UserID.regex' => '登录ID必须输入',  
            'Password.require'     => '新密码必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['stats'=>'-1','msg'=>$validate->getError(),'token'=>'']);
        }



        //$userData=array();
        $userData['username']=trim($re['UserID']);
        $userData['password']=trim($re['Password']);
        //验证用户名或邮箱或手机号是否存在
        $users=db('sysuser')->where(['UserID'=>$userData['username']])->whereOr(['UserEmail'=>$userData['username']])->whereOr(['UserMobile'=>$userData['username']])->where(['Tag'=>'1'])->find();
        // dump($users); die;
        if($users){
            if($users['Password'] == $userData['password']){
                //登录成功
                //设置登录次数
                incLogin($userData['username']);
                //session('uid',$users['InnerID']);
                //session('UserName',$users['UserName']);
                //session('UserID',$users['UserID']);
                $token=md5($users['InnerID'].$users['UserID'].date('Y-m-d',time()).$users['Password']);
                $timer=strtotime('now');
                $timer=$timer+10*60;
                //echo $timer;
                if(getCount("t_token",["UserID"=>$users['UserID']])>0){
                    updata("t_token",null,["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
                }else{
                    insert("t_token",["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
                }
                
                //写入cookie
                if(isset($re['remember'])){
                    $aMonth=30*24*60*60;
                    $username=encryption($users['username'],0);
                    $password=encryption($data['password'],0);
                    cookie('username', $username, $aMonth, '/');
                    cookie('password', $password, $aMonth, '/');
                }
                $rt["stats"]='1';
                $rt["msg"]="登录成功！";
                $rt["token"]=$token;
                $rt['data']['treelist']=getTreeToUserID($users['UserID']);
                $rt['data']['Orginfo']=getOrgToUserID($users['UserID']);
                $rt['data']['userinfo']=getUserToUserID($users['UserID']);
                $rt['data']['userinfoext']=getUserExtToUserID($users['UserID']);
                return json($rt);
            }else{
               $rt=[
                'stats'=>"-1",
                'msg'=>"<i class='iconfont icon-minus-sign'>用户密码错误</i>",
                'token'=>'',
                ];
                return json($rt);
            }
        }else{
            $rt=[
                'stats'=>"-1",
                'msg'=>"<i class='iconfont icon-minus-sign'>用户名错误</i>",
                'token'=>'',
                ];
            return json($rt);
        }
    }

    //para检测用户是否登录，通过token检测；
    //para : token
    public function islogin(){
        //$token = "b8183303f9f4bbf00c7e96d8d6eba811";
        $re = input('post.');
        $rule =   [
            'token'  => 'require'
        ];    
        $message  =   [
            'token.require' => 'token必须提供',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        $rt = islogin($re['token']);
        return json($rt);
    }
    //para 登录时按照UserID返回该用户的菜单
    //para ： UserID
    public function getTreeToRoles(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'UserID'  => 'require|min:2'
        ];    
        $message  =   [
            'UserID.require' => '用户ID必须提供',
            'UserID.min' => '用户ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        $rt = Db::view("systree",'FunName,FunLink,FatherID,SequenceID,GroupTag,MenuLevelID')
            ->view('funtorole','FunID','funtorole.FunID=systree.FunID')
            ->view('roletouser','RoleID','roletouser.RoleID=funtorole.RoleID')
            ->where(['UserID'=>$re['UserID'],'DisplayTag'=>'1'])
            ->select();
        return json($rt);
    }


    //para删除菜单树（包括删除子菜单及以下菜单）。
    //para ： FunID
    public function deletetree(){
        $re = input('?post.FunID')?input('post.FunID'):'';
        if($re==''){return json(['ID'=>'-1','msg'=>'no data deleted.the FunID='.$re]);}
        $msg = delTree('t_systree',$re,'FatherID','FunID');  
        return json(['ID'=>'1','msg'=>$msg]);
    }

    //para将批量人员增加到组织中。
    //para : OrgID ,UserID [说明，其中UserID是逗号分隔的list，如果user1,user2,user3]
    public function setOrgUsers(){
        $re = input('post.');
        $rule =   [
            'OrgID'  => 'require|min:2',
            'UserID'  => 'require|min:2'
        ];    
        $message  =   [
            'OrgID.require' => '组织ID必须提供',
            'OrgID.min' => '组织ID长度不能小于2',
            'UserID.require'     => '用户ID必须提供',
            'UserID.min'     => '用户ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        $insertlist  = "";
        $faildlist  = "";
        $existslist = "";
        $FunIDArray=explode(",",$re['UserID']);
        $data =  array();
        foreach ($FunIDArray as $key => $value) {
            //array_push($data, ['RoleID'=> $value , 'OrgID'=> $re['OrgID'] ]);
            $Org = gettablecol('t_orgtouser',['UserID'=>$value],'OrgID');
            if($Org==null){
                //return json(['ID' => '-1','msg'=>"用户已经存在于".$role."组织。不能再添加" ]);
                $i = insert('t_orgtouser',['OrgID'=>$re['OrgID'],'UserID'=>$value]);
                if($i>0){
                    $insertlist.=$value.",";
                }else{
                    $faildlist.=$value.",";
                }
            }else{
                $existslist.=$value.",";
            }
        }
        return json(["ID"=>'msg',"Insertlist"=>$insertlist,"faildlist"=>$faildlist,"existslist"=>$existslist]);
    }

    //para修改组织与资源组关系表，一个组织可以对用多个资源组，以组织视角进行批量增加，增加时先删除组织下面的资源组。
    //para : OrgID ,ResGroupID [说明，其中ResGroupID是逗号分隔的list，如果res1,res2,res3]
    public function setOrgResGroups(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'OrgID'  => 'require|min:2',
            'ResGroupID'  => 'require|min:2'
        ];    
        $message  =   [
            'OrgID.require' => '组织ID必须提供',
            'OrgID.min' => '组织ID长度不能小于2',
            'ResGroupID.require'     => '角色ID必须提供',
            'ResGroupID.min'     => '角色ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }

        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        $role = gettablecol('t_orgtoresg',["OrgID"=>$re['OrgID']],'InnerID');
        if($role!=null){
            delete("t_orgtoresg",["OrgID"=>$re['OrgID']]);
        }

        $FunIDArray=explode(",",$re['ResGroupID']);
        $data =  array();
        foreach ($FunIDArray as $key => $value) {
            array_push($data, ['ResGroupID'=> $value , 'OrgID'=> $re['OrgID'] ]);
        }

        $i = insertAll('t_orgtoresg',$data);
        if($i>0){
            $rt['ID'] =  $i;
            $rt["msg"] = "orgtoresg insert OK ，insert ".$i." rows.";
        }else{
            $rt['ID'] =  "0";
            $rt["msg"] = "orgtoresg insert failed !";
        }
        return json($rt);
    }
    //para修改组织的角色信息，主要作用是角色按组织进行隔离，也就是不同的组织可以配置自己的角色。将多个角色加入组织中。角色逗号分隔
    //para : OrgID ,RoleID [说明，其中RoleID是逗号分隔的list，如果role1,role2,role3]
    public function setOrgRoles(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'OrgID'  => 'require|min:2',
            'RoleID'  => 'require|min:2'
        ];    
        $message  =   [
            'OrgID.require' => '组织ID必须提供',
            'OrgID.min' => '组织ID长度不能小于2',
            'RoleID.require'     => '角色ID必须提供',
            'RoleID.min'     => '角色ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }

        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        $role = gettablecol('t_roletoorg',["OrgID"=>$re['OrgID']],'InnerID');
        if($role!=null){
            delete("t_roletoorg",["OrgID"=>$re['OrgID']]);
        }

        $FunIDArray=explode(",",$re['RoleID']);
        $data =  array();
        foreach ($FunIDArray as $key => $value) {
            array_push($data, ['RoleID'=> $value , 'OrgID'=> $re['OrgID'] ]);
        }

        $i = insertAll('t_roletoorg',$data);
        if($i>0){
            $rt['ID'] =  $i;
            $rt["msg"] = "OrgRole insert OK ，insert ".$i." rows.";
        }else{
            $rt['ID'] =  "0";
            $rt["msg"] = "OrgRole insert failed !";
        }
        return json($rt);
    }

    //para增加组织的用户信息，一个用户只能归属一个组织。
    //para ： UserID，OrgID
    public function addOrgUser(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'OrgID'  => 'require|min:2',
            'UserID'  => 'require|min:2'
        ];    
        $message  =   [
            'OrgID.require' => '组织ID必须提供',
            'OrgID.min' => '组织ID长度不能小于2',
            'UserID.require'     => '用户ID必须提供',
            'UserID.min'     => '用户ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }

        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        $role = gettablecol('t_orgtouser',["OrgID"=>$re['OrgID'],'UserID'=>$re['UserID']],'InnerID');
        if($role!=null){
            return json(['ID' => '-1','msg'=>"用户已经存在于".$re['OrgID']."组织。不能重复添加" ]);
        }

        $role = gettablecol('t_orgtouser',['UserID'=>$re['UserID']],'OrgID');
        if($role!=null){
            return json(['ID' => '-1','msg'=>"用户已经存在于".$role."组织。不能再添加到其他组织" ]);
        }

        //$i = insertAll('t_roletoorg',$data);
        $i = insert('t_orgtouser',$re);
        if($i>0){
            $rt['ID'] =  $i;
            $rt["msg"] = "roletoorg insert OK.";
        }else{
            $rt['ID'] =  "0";
            $rt["msg"] = "roletoorg insert failed !";
        }
        return json($rt);
    }

    //修改用户角色,批量组织，先删除再增加
    //para : UserID , RoleID = role1,role2,role3
    public function setUserRoles(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'UserID'  => 'require|min:2',
            'RoleID'  => 'require|min:2'
        ];    
        $message  =   [
            'UserID.require' => '用户ID必须提供',
            'UserID.min' => '用户ID长度不能小于2',
            'RoleID.require'     => '角色ID必须提供',
            'RoleID.min'     => '角色ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }

        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        $role = gettablecol('t_roletouser',["UserID"=>$re['UserID']],'InnerID');
        if($role!=null){
            delete("t_roletouser",["UserID"=>$re['UserID']]);
        }

        $FunIDArray=explode(",",$re['RoleID']);
        $data =  array();
        foreach ($FunIDArray as $key => $value) {
            array_push($data, ['RoleID'=> $value , 'UserID'=> $re['UserID'] ]);
        }

        $i = insertAll('t_roletouser',$data);
        if($i>0){
            $rt['ID'] =  $i;
            $rt["msg"] = "userRole insert OK ，insert ".$i." rows.";
        }else{
            $rt['ID'] =  "0";
            $rt["msg"] = "userRole insert failed !";
        }
        return json($rt);
    }

    //角色权限修改,角色批量,先删除再增加；
    //para : RoleID,FunID=fun1,fun2,fun3
    public function setRoleFuncs(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'FunID'  => 'require|min:2',
            'RoleID'  => 'require|min:2'
        ];    
        $message  =   [
            'FunID.require' => '菜单ID必须提供',
            'FunID.min' => '菜单ID长度不能小于2',
            'RoleID.require'     => '角色ID必须提供',
            'RoleID.min'     => '角色ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }

        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        $role = gettablecol('t_funtorole',["RoleID"=>$re['RoleID']],'InnerID');
        if($role!=null){
            delete("t_funtorole",["RoleID"=>$re['RoleID']]);
        }

        $FunIDArray=explode(",",$re['FunID']);
        $data =  array();
        foreach ($FunIDArray as $key => $value) {
            array_push($data, ['FunID'=> $value , 'RoleID'=> $re['RoleID'] ]);
        }

        $i = insertAll('t_funtorole',$data);
        if($i>0){
            $rt['ID'] =  $i;
            $rt["msg"] = "funtorole insert OK ，insert ".$i." rows.";
        }else{
            $rt['ID'] =  "0";
            $rt["msg"] = "funtorole insert failed !";
        }
        return json($rt);
    }
    //增加或修改菜单，
    //para : FunName,FunID,FatherID,post
    public function putTree(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'FunID'  => 'require|min:2',
            'FunName'  => 'require|min:2',
            'FatherID'  => 'require|min:2'
        ];    
        $message  =   [
            'FunID.require' => '菜单ID必须提供',
            'FunID.min' => '菜单ID长度不能小于2',
            'FunName.require' => '菜单名称必须提供',
            'FunName.min'     => '菜单名称长度不能小于2',
            'FatherID.require'     => '父菜单ID必须提供',
            'FatherID.min'     => '父菜单ID长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_systree',null,$re);
            if($i>0){
                $rt['ID'] =  $i;
                $rt["msg"] = "Systree update OK !";
            }else{
                $rt['ID'] =  "-1";
                $rt["msg"] = "no data updated .";
            }
        }else{
            $tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
            if($tree!=null){
                $rt['ID'] =  "-1";
                $rt["msg"] = "Systree insert failed , FunID:".$re['FunID'].' already exists .';
            }else{
                $i = insert('t_systree',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "Systree insert OK !";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "Systree insert failed !";
                }
               
            }
        }
        return json($rt);
    }
    //增加或修改系统配置参数，
    //para : DataID,DataValue,post
    public function putSysData(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'DataID'  => 'require|min:2',
            'DataValue'  => 'require|min:2'
        ];    
        $message  =   [
            'DataID.require' => '参数ID必须提供',
            'DataID.min' => '参数ID长度不能小于2',
            'DataValue.require' => '参数值必须提供',
            'DataValue.min'     => '参数值称长度不能小于2',
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_sysdata',null,$re);
            if($i>0){
                $rt['ID'] =  $i;
                $rt["msg"] = "SysData update OK !";
            }else{
                $rt['ID'] =  "0";
                $rt["msg"] = "no data updated .";
            }
        }else{
            $tree = gettablecol('t_sysdata',['DataID'=>$re['DataID'],'DataValue'=>$re['DataValue']],'InnerID');
            if($tree!=null){
                $rt['ID'] =  "0";
                $rt["msg"] = "Systree insert failed , DataID:".$re['DataID'].' already exists .';
            }else{
                $i = insert('t_sysdata',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "SysData insert OK !";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "SysData insert failed !";
                }
               
            }
        }
        return json($rt);
    }
    //资源组维护，
    //para : DataID,DataValue,post
    public function putResGroupID(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'ResGroupID'  => 'require|min:2',
            'ResGroupName'  => 'require|min:2',
        ];    
        $message  =   [
            'ResGroupID.require' => '资源组ID必须提供',
            'ResGroupID.min' => '资源组ID长度不能小于2',
            'ResGroupName.require' => '资源组名称必须提供',
            'ResGroupName.min'     => '资源组名称长度不能小于2',
            
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        $re = gettablecols('t_resgroup',$re);
        p($re);
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_resgroup',null,$re);
            if($i>0){
                $rt['ID'] =  $i;
                $rt["msg"] = "resgroup update OK !";
            }else{
                $rt['ID'] =  "0";
                $rt["msg"] = "no data updated .";
            }
        }else{
            $tree = gettablecol('t_resgroup',"ResGroupID='".$re['ResGroupID']."'",'InnerID');
            if($tree!=null){
                $rt['ID'] =  "0";
                $rt["msg"] = "t_resgroup insert failed , FunID:".$re['ResGroupID'].' already exists .';
            }else{
                $i = insert('t_resgroup',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "resgroup insert OK !";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "resgroup insert failed !";
                }
               
            }
        }
        return json($rt);
    }

    //资源组维护，
    //para : OrgID ,OrgName ,post
    public function putSysOrg(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'OrgID'  => 'require|min:2',
            'OrgName'  => 'require|min:2',
        ];    
        $message  =   [
            'OrgID.require' => '组织ID必须提供',
            'OrgID.min' => '组织ID长度不能小于2',
            'OrgName.require' => '组织名称必须提供',
            'OrgName.min'     => '组织名称长度不能小于2',
            
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_sysorg',null,$re);
            if($i>0){
                $rt['ID'] =  $i;
                $rt["msg"] = "sysorg update OK !";
            }else{
                $rt['ID'] =  "0";
                $rt["msg"] = "no data updated .";
            }
        }else{
            $tree = gettablecol('t_sysorg',['OrgID'=>$re['OrgID'],'OrgName'=>$re['OrgName']],'InnerID');
            if($tree!=null){
                $rt['ID'] =  "0";
                $rt["msg"] = "sysorg insert failed , FunID:".$re['OrgID'].' already exists .';
            }else{
                $i = insert('t_sysorg',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "sysorg insert OK !";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "sysorg insert failed !";
                }
               
            }
        }
        return json($rt);
    }

    //系统角色维护，
    //para : RoleID,RoleName
    public function putSysRole(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $rule =   [
            'RoleID'  => 'require|min:2',
            'RoleName'  => 'require|min:2',
        ];    
        $message  =   [
            'RoleID.require' => '角色ID必须提供',
            'RoleID.min' => '角色ID长度不能小于2',
            'RoleName.require' => '角色名称必须提供',
            'RoleName.min'     => '角色名称长度不能小于2',
            
        ];       
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return json(['ID' => '-1','msg'=>$validate->getError() ]);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_sysrole',null,$re);
            if($i>0){
                $rt['ID'] =  $i;
                $rt["msg"] = "sysrole update OK !";
            }else{
                $rt['ID'] =  "0";
                $rt["msg"] = "no data updated .";
            }
        }else{
            $tree = gettablecol('t_sysrole',['RoleID'=>$re['RoleID'],'RoleName'=>$re['RoleName']],'InnerID');
            if($tree!=null){
                $rt['ID'] =  "0";
                $rt["msg"] = "sysrole insert failed , FunID:".$re['RoleID'].' already exists .';
            }else{
                $i = insert('t_sysrole',$re);
                if($i>0){
                    $rt['ID'] =  $i;
                    $rt["msg"] = "sysrole insert OK !";
                }else{
                    $rt['ID'] =  "0";
                    $rt["msg"] = "sysrole insert failed !";
                }
               
            }
        }
        return json($rt);
    }
    /***
    *通用文件上传
    *单张图片通过base64字符串编码异步post方式上传。
    *@param  token 用户身份认证码，必填。
    *@imgbase64  图片转码成base64字符串流。格式为：data:image/jpeg;base64,
    *@return 
          $res["result"] = 1;  状态
          $res["msg"] = "上传成功";  信息
          $res["imgname"] = $ping_url ;  图片保存名称（页面提交时需要提交）；
          $res["path"] = $basePutUrl;   图片路径
        Json格式：{"result":1,"imgurl":"","msg":"上传成功","imgname":"41F5203C-D5C1-8AF9-B5A4.png","path":".\/Public\/assetinti\/"}
    */
    public function putImg(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
            'imgbase64' => 'require'
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'imgbase64.require' => '图片资源码必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $uprt = base64_image_content($re['imgbase64'],'CommFileDir');
        if($uprt['result']==1){
            return json($uprt);
        }else
        {
            return rtjson("文件上传失败:"+$uprt['msg']);
        }
    }

    /***
    *资产绑定初始化文件上传
    *调用地址：http://132.232.65.200:8899/asset/res/index/putAssetImg
    *单张图片通过base64字符串编码异步post方式上传。
    *@param  token 用户身份认证码，必填。
    *@imgbase64  图片转码成base64字符串流。格式为：data:image/jpeg;base64,
    *@param  asset_num 资产编号，外键。
    *@param  imgSeq 上传照片的顺序号，标签照:1；近景:2；远景:3；位置:4。
    *@param  asset_name 文字识别的资产名称（服务器端与当前资产做核对)。
    *@param  bind_location 上传图片的当前位置信息
    *javascript调用参数组装：let params = {
    *                            imgbase64: `${fileData}`,
    *                            token: JSON.parse(window.sessionStorage.getItem('token')),
    *                            asset_num: '00-13095',
    *                            imgSeq: 2,
    *                            asset_name: '等静压机'
    *                            }
    *@return     
    * {"ID":"1","msg":"资产照片上传成功，照片名称：7CE2B683-8CB5-BD22-395D.png"}
    */
    public function putAssetImg(){
        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
            'imgbase64' => 'require',
            'asset_num' => 'require',
            'imgSeq'    =>  'require',
            'asset_name'=> 'require'
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'imgbase64.require' => '图片资源码必须提供',
            'asset_num.require' => '资产编码必须提供',
            'imgSeq.require' => '照片顺序号必须提供',
            'asset_name.require' => '资产名称必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $uprt = base64_image_content($re['imgbase64'],'AssetIntiDir',$re['asset_num'].$re['imgSeq']);
        if($uprt['result']==1){
            //在这里插入资产数据
            $data['asset_num'] = $re['asset_num'];
            $data['img_seq'] = $re['imgSeq'];
            $data['asset_name'] = $re['asset_name'];
            $data['img_name'] = $uprt['imgname'];
            $data['bind_person'] = $userid;
            if(isset($re['bind_location'])){
                $data['bind_location'] = $re['bind_location'] ;
            }
            //$data['bind_location'] = ? $re['bind_location'] : '';
            $i = insert('t_tag_bind',$data);
            if($i > 0){
               return rtjson("资产照片上传成功，照片名称：".$uprt['imgname'],'1'); 
            }else{
               unlink($uprt['path'].$uprt['imgname']);
               return rtjson("资产照片上传成功，照片登记失败，请重新上传。");
            }
            

        }else
        {
            return rtjson("文件上传失败:".$uprt['msg']);
        }
    } 
    /***
    *资产绑定初始化文件上传
    *调用地址：http://132.232.65.200:8899/asset/res/index/putAssetImgForForm
    *单张图片通过formData方式上传。
    *@param  token 用户身份认证码，必填。
    *@file  图片文件,
    *@param  asset_num 资产编号，外键。
    *@param  imgSeq 上传照片的顺序号，标签照:1；近景:2；远景:3；位置:4。
    *@param  asset_name 文字识别的资产名称（服务器端与当前资产做核对)。
    *javascript调用参数组装：var formData = new FormData();
    *                               formData.append('file', $("#uploadFile")[0].files[0]);
    *                               formData.append('token','?????');
    *                               formData.append('asset_num','?????');
    *                               formData.append('imgSeq','?????');
    *                               formData.append('asset_name','?????');
    *                               
    *@return     
    * {"ID":"1","msg":"资产照片上传成功，照片名称：7CE2B683-8CB5-BD22-395D.png"}
    */
    public function putAssetImgForForm(){
        $targetFolder = './Public/AssetIntiDir/';
        if (empty($_FILES)) {
            return rtjson('没有获取到要上传的照片.');
        }

        $re = input('post.');
        $rule =   [
            'token'   =>  'require',
            'asset_num' => 'require',
            'imgSeq'    =>  'require|number',
            'asset_name'=> 'require'
        ];
        $message  =   [
            'token.require' => '身份令牌必须提供',
            'asset_num.require' => '资产编码必须提供',
            'imgSeq.require' => '照片顺序号必须提供',
            'asset_name.require' => '资产名称必须提供'
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $file_name = iconv("UTF-8","gb2312", $_FILES['file']['name']); //文件名称
        $filenames= explode(".",$file_name);
        $tempFile = $_FILES['file']['tmp_name'];
        $fileParts = pathinfo($_FILES['file']['name']);
        //$rand = rand(1000, 9999);
        $targetPath = $targetFolder; //图片存放目录
        $ret = true;
        if(!file_exists($targetPath)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            $ret = mkdir($targetPath, 0700,true);
        }
        if(!$ret) {
          // 上传错误提示错误信息,目录创建失败
          $res['msg'] = "创建保存图片的路径失败！";
          return $res;
        }
        $newFileName = guid().'.'.$fileParts['extension'];
        $targetFile = rtrim($targetPath,'/') . '/' .$newFileName; //图片完整路徑
        // Validate the file type
        $fileTypes = array('jpg', 'jpeg', 'png'); // File extensions
        if (in_array($fileParts['extension'],$fileTypes)) {
            move_uploaded_file($tempFile,iconv("UTF-8","gb2312", $targetFile));
            $data['asset_num'] = $re['asset_num'];
            $data['img_seq'] = $re['imgSeq'];
            $data['asset_name'] = $re['asset_name'];
            $data['img_name'] = $newFileName;
            $data['bind_person'] = $userid;
            $i = insert('t_tag_bind',$data);
            if($i > 0){
                updata("t_assets",["asset_num"=>$re['asset_num']],["bind_status"=>'待审核']);
               return rtjson("资产照片上传成功，照片名称：".$newFileName,'1'); 
            }else{
               unlink($targetFile);
               return rtjson("资产照片上传失败，照片登记失败，请重新上传。");
            }
        } else {
            return rtjson("照片格式不合法，请上传格式为jpg、jpeg、png格式的照片.");
        }    
    } 

    //批量审核标签绑定【bind_status】:未绑定、待审核、已绑定、驳回
    public function ChangeTagStatusAll(){
        $re = input('post.');
        $rule =   [
            'asset_list'  => 'require',
            'token'   =>  'require',
        ];
        $message  =   [
            'asset_list.require' => '绑定的资产列表必须提供',
            'token.require' => '身份令牌必须提供',
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }

        //$data = gettablecols('t_checks_plan',$re);
        $token = $re['token'];
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $assetsData = [];
        $printlist = json_decode($re['asset_list'],true); 
        //p($assetList);
        foreach ($printlist as $k => $v) {
                $assetsData[] = [   'asset_id' => $v['asset_id'],'bind_status' => $v['bind_status']];
                //$assetsData[] = $temp2;           
        }
        //$i = insertAll('t_tag_print',$data);
        $i = saveAll($assetsData,'t_assets','asset_id');
        //$rttag = $i-$j;
        if($i>0){
            return rtjson('操作成功，更新资产数据 '.$i.' 条 . ','1');
        }else{
            return rtjson('打印异常.');
        }        
    }



    //post测试工具
    public function testpost(){
        return $this->fetch();
    }

    /*
    
    */
    public function testget(){
        return $this->fetch();
    }

    public function testpostimg(){
        return $this->fetch();
    }
    public function tlpstr(){
       echo  commandCreator('中国科学院物理研究所','12-12099','旋片真空泵','N08组');
    }
    public function rqpng($qrstr){
       Vendor('phpqrcode.phpqrcode');
       //include 'phpqrcode.php';
       echo  QRcode::png($qrstr); 
    }
}


