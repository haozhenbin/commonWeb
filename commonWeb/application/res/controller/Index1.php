<?php
namespace app\res\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;



class Index extends Controller
{
    public function index()
    {
        lg("call res /index/index;".date("Y-m-d H:i:s"));
        return sel('select * from t_Assets');
        return "HI:)))--->>>   welcom res!";
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
        $rt = getDataToJson('assets',$data,'*','CreateDate',$page,$pagesize,$map2);
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
        $keystr = $re['keystr'];
        //$re['value']='0';
        

        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        $rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
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

    // 新建计划接口（即生成盘点计划）
    // 输入参数必须有plan_name（盘点计划名称），token：用户令牌，deadline（失效时间）和plan_memo(计划描述)是可选项。
    // 生成计划后返回plan_id，作为选择盘点计划对应资产的父ID；
    //返回样例：{"ID":"1","msg":"9"}
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
        $i = insert('t_checks_plan',$data);
        if($i>0){
            return rtjson($i,'1');
        }else{
            return rtjson('添加数据失败。');
        }
    }

    // 资产盘点计划查询
    // 实现对资产盘点计划的查询功能，支持分页（page和pagesize参数），支持对盘点计划名称的模糊查询（模糊查询参数是plan_name）；
    // 返回结果：{"data":[{"plan_id":1,"plan_name":"欧要建一个计划","plan_memo":"多到多得","deadline":"2018-12-10","create_person":"haozb","exeResult":"OK","CreateDate":"0000-00-00 00:00:00"}],"count":1}
    public function getchecksplan(){
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
        $data = gettablecols('t_checks_plan',$re);
        foreach ($data as $key => $value) {
            if($value==''){
                unset($data[$key]);
            }
        }
        //p($data);
        //$rt = getDataToJson('assets',$data,'','CreateDate',$page,$pagesize,$map2,$keystr);
        $rt = getdsJson('checks_plan',$data,'*','CreateDate desc',$page,$pagesize);
        //$rt = gettable('t_Assets');
        return $rt;           
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

        $list['data'] = Db::view('assets','asset_num, `name`, deparment,start_time,location ')
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


    //注册读写器
    //需要输入必填参数reader_name  （终端名称）,reader_type （终端类型：移动、固定）,maker_name （设备厂商）,
    //可选参数：location (所在位置)、model_num （设备型号）、start_time （启用日期）、status （状态：启用，禁用）
    //post 方式
    //return {"ID":"1","msg":"OK"}
    public function putReaderRegist(){
        $re = input('post.');
        $rule =   [
            'reader_name'  => 'require',
            'reader_type'  => 'require',
            'maker_name'  => 'require'
        ];
        $message  =   [
            'reader_name.require' => '终端名称必须提供',  
            'reader_type.require' => '终端类型必须提供（手持、固定）',  
            'maker_name.require' => '终端厂商必须提供',  
        ];
        $validate = new Validate($rule,$message);
        $result = $validate->check($re);
        if(!$result){
            return rtjson('校验失败:'.$validate->getError());
        }
        $data = gettablecols('t_reader_register',$re);
        $data['rdkey'] = guid();
        if(!isset($data['start_time'])){
            $data['start_time'] = date("Y-m-d");
        }
        if(!isset($data['status'])){
            $data['status'] = '启用';
        }
        $i = insert('t_reader_register',$data);
        if($i>0){
            return rtjson('OK','1');
        }else{
            return rtjson('添加数据失败。');
        }
    }
    // 手持机获取服务器端盘点任务，根据手持端用户登录账号（token）确定获取的任务信息；
    // 使用post方式，参数：rdkey (读写器接入码)，token：用户身份认真令牌，
    // 返回信息：任务id，资产id，资产名称，资产部门，资产编号，标签编号
    //返回样例：{"data":[{"asset_num":"J201605","name":"物理所园区道路","financial_category":"固定资产-房屋构筑物","value":192705,"parent_name":null,"model_num":null,"person":null,"deparment":"中国科学院物理研究所","start_time":"2018-11-01 13:29:04","location":"*","plan_id":8,"detail_id":15,"check_flag":"未盘点","check_result":null}],"count":5}
    public function getcheckstask(){
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
        $loginstatus = islogin($token);
        if($loginstatus['ID']=="-1"){
           return rtjson('登录超时，请重新登录.'); 
        }
        $userid = $loginstatus['msg'];
        $device = getdeviceinfoforrdkey($re['rdkey']);
        //$pid = $re['plan_id'];
        //前台传过来json，后台解析成数组；
        $data = [];
        $assetList = json_decode($re['checkedlist'],true); 
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
    
    //post测试工具
    public function testpost(){
        return $this->fetch();
    }

    /*
    
    */
    public function testget(){
        return $this->fetch();
    }


}


