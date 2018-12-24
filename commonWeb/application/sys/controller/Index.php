<?php
namespace app\sys\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;



class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    
    public function testlink(){
        echo 'OK';
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

    /*para
    根据UserID，获取对应的roleInfo
    para : UserID
    */
    public function getRoleInfo(){
        $re = input('?post.UserID')?input('post.UserID'):'';
        if($re!=''){
           return getFunToRole($re); 
        }
        return json(['ID'=>'-1','msg'=>'RoleID Error.']);
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
        $id = updata('t_sysuser',['Password'=>$re['Password']],['InnerID'=>$uid]);
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
                    $i = updata("t_sysuser",$mapupdate,$map);
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
                    $i = updata("t_sysuser",$mapupdate,$map);

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

    //para : 更新用户注册信息和用户扩展信息
    //para : UserID  , post,
    public function putUser(){
        $re = input('post.');
        $ckrt = checkinput(['UserID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        if(isset($re['Password'])){
            unset($re['Password']);
        }        
        $user = gettablecol('t_sysuserext',['UserID'=>$re['UserID']],'InnerID');
        $uid = gettablecol('t_sysuser',['UserID'=>$re['UserID']],'InnerID');
        $us = gettablecols('t_sysuserext',$re);
        if(!empty($user)){
            //$re["InnerID"] = $user;
            $j = 0 ;
            if(!empty($uid)){
                $u = gettablecols('t_sysuser',$re);
                $j = updata('t_sysuser',['InnerID'=>$uid],$u);
            }
            $i = updata('t_sysuserext',['InnerID'=>$user],$us);
            $i= $i+$j;
            if($i>0){
                return rtjson('更新成功！','1');
            }else{
                return rtjson('数据没有更新.','1');
            }
        }else{
            if(!empty($uid)){
              $i = insert('t_sysuserext',$us);
                if($i>0){
                    return rtjson('新增用户扩展信息成功.','1');
                }else{
                    return rtjson('新增用户扩展信息失败.');
                }
            }else{
              return rtjson('用户没有注册，请先注册用户.');  
            }
        }
    }

    //para 登录
    //输入用户名和口令，返回用户信息、扩展信息、组织信息、菜单信息  
    //para : UserID  , Password
    public function login(){
        $re = input('post.');
        $ckrt = checkinput(['UserID','Password'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        //$userData=array();
        $userData['username']=trim($re['UserID']);
        $userData['password']=md5(trim($re['Password']));//加密
        //验证用户名或邮箱或手机号是否存在
        //$users=db('sysuser')->where(['UserID'=>$userData['username']])->whereOr(['UserEmail'=>$userData['username']])->whereOr(['UserMobile'=>$userData['username']])->where(['Tag'=>'1'])->find();
        $users=db('sysuser')->where(['Tag'=>'1'])->where('UserID|UserEmail|UserMobile',$userData['username'])->find();
        // dump($users); die;
        if($users){
            if($users['Password'] == $userData['password']){
                //登录成功
                //设置登录次数
                incLogin($userData['username']);
                $token=md5($users['InnerID'].$users['UserID'].date('Y-m-d',time()).$users['Password']);
                $timer=strtotime('now');
                $timer=$timer+60*60*24;
                //echo $timer;
                if(getCount("t_token",["UserID"=>$users['UserID']])>0){
                    updata("t_token",["UserID"=>$users['UserID']],["token"=>$token,"extDate"=>$timer]);
                }else{
                    insert("t_token",["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
                }
                $rt["stats"]='1';
                $rt["msg"]="登录成功！";
                $rt["token"]=$token;
                $rt['data']['treelist']=getTreeToUserID($users['UserID']);
                $rt['data']['Orginfo']=getOrgToUserID($users['UserID']);
                $rt['data']['Roleinfo']=getRoleByUserID($users['UserID']);
                $rt['data']['userinfo']=getUserToUserID($users['UserID']);
                $rt['data']['userinfoext']=getUserExtToUserID($users['UserID']);
                return json($rt);
            }else{
               $rt=[
                'stats'=>"-1",
                'msg'=>"用户密码错误",
                'token'=>'',
                ];
                return json($rt);
            }
        }else{
            $rt=[
                'stats'=>"-1",
                'msg'=>"用户名错误",
                'token'=>'',
                ];
            return json($rt);
        }
    }

    //科技云回调
    public function kjylogin(){
        $re = input('post.');
        $ckrt = checkinput(['token'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        $token = $re['token'];
        $users = getUserfortocken($token);
        incLogin($users['UserID']);
        $timer=strtotime('now');
        $timer=$timer+60*60*24;
        //echo $timer;
        if(getCount("t_token",["UserID"=>$users['UserID']])>0){
            updata("t_token",["UserID"=>$users['UserID']],["token"=>$token,"extDate"=>$timer]);
            $rt["stats"]='1';
            $rt["msg"]="登录成功！";
            $rt["token"]=$token;
            $rt['data']['treelist']=getTreeToUserID($users['UserID']);
            $rt['data']['Orginfo']=getOrgToUserID($users['UserID']);
            $rt['data']['Roleinfo']=getRoleByUserID($users['UserID']);
            $rt['data']['userinfo']=getUserToUserID($users['UserID']);
            $rt['data']['userinfoext']=getUserExtToUserID($users['UserID']);
            return json($rt);
        }else{
            insert("t_token",["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
            $rt=[
                'stats'=>"-1",
                'msg'=>"该系统没有科技云账号信息，请与管理员联系同步用户数据.",
                'token'=>'',
                ];
                return json($rt);
        }
    }

    //退出科技云
    public function kjylogout(){
        header("location:".config('kjy.OAUTH_LOGOUT_URL').config('kjy.OAUTH_INDEX_URI'));
            exit;
    }
    
    //para检测用户是否登录，通过token检测；
    //para : token
    public function islogin(){
        //$token = "b8183303f9f4bbf00c7e96d8d6eba811";
        $re = input('post.');
        $ckrt = checkinput(['token'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        $rt = islogin($re['token']);
        return json($rt);
    }
    //para 登录时按照UserID返回该用户的菜单
    //para ： UserID
    public function getTreeToRoles(){
        $re = input('post.');
        $ckrt = checkinput(['UserID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
        $ckrt = checkinput(['UserID','OrgID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
        $re = input('post.');
        $ckrt = checkinput(['OrgID','ResGroupID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
            return rtjson('组织资源关系插入成功.共插入'.$i.'记录','1');
        }else{
            return rtjson('组织资源关系插入失败.');
        }
    }
    //para修改组织的角色信息，主要作用是角色按组织进行隔离，也就是不同的组织可以配置自己的角色。将多个角色加入组织中。角色逗号分隔
    //para : OrgID ,RoleID [说明，其中RoleID是逗号分隔的list，如果role1,role2,role3]
    public function setOrgRoles(){
        $re = input('post.');
        $ckrt = checkinput(['OrgID','RoleID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
            return rtjson('组织角色关系插入成功.共插入'.$i.'记录','1');
        }else{
            return rtjson('组织角色关系插入失败.');
        }
    }

    //para增加组织的用户信息，一个用户只能归属一个组织。
    //para ： UserID，OrgID
    public function addOrgUser(){
        $re = input('post.');
        $ckrt = checkinput(['OrgID','UserID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
            return rtjson('用户与组织关系插入成功.','1');
        }else{
            return rtjson('用户与组织关系插入失败.');
        }
    }

    //从组织中删除人员
    //para : UserID , OrgID
    public function deleteorgtouser(){
        $re = input('post.');
        $ckrt = checkinput(['OrgID','UserID','token'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        $i = delete('t_orgtouser',['UserID'=>$re['UserID'],'OrgID'=>$re['OrgID']]);        
        return json('删除成功.'.$i,'1');
    }

    //修改用户角色,批量组织，先删除再增加
    //para : UserID , RoleID = role1,role2,role3
    public function setUserRoles(){
        $re = input('post.');
        $ckrt = checkinput(['RoleID','UserID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
            return rtjson('用户角色分配成功.共分配角色'.$i.'个','1');
        }else{
            return rtjson('用户角色分配失败.');
        }
    }

    //角色权限修改,角色批量,先删除再增加；
    //para : RoleID,FunID=fun1,fun2,fun3
    public function setRoleFuncs(){
        $re = input('post.');
        $ckrt = checkinput(['RoleID','FunID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
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
            return rtjson('角色权限分配成功.共分配权限'.$i.'个','1');
        }else{
            return rtjson('角色权限分配失败.');
        }
    }
    //增加或修改菜单，
    //para : FunName,FunID,FatherID,post
    public function putTree(){
        $rt = array('ID' => '-1','msg'=>'Error' );
        $re = input('post.');
        $ckrt = checkinput(['FunName','FunID','FatherID'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_systree',['InnerID'=>$re['InnerID']],$re);
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
        $ckrt = checkinput(['DataID','DataValue'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_sysdata',['InnerID'=>$re['InnerID']],$re);
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
        $ckrt = checkinput(['ResGroupID','ResGroupName'],$re);
        if($ckrt['ID']!='1'){
            return rtjson($ckrt['msg']);
        }

        $re = gettablecols('t_resgroup',$re);
        p($re);
        //$tree = gettablecol('t_systree',"FunID='".$re['FunID']."'",'InnerID');
        //$treeid = gettablecol('t_systree',"InnerID='".$re['InnerID']."'",'InnerID');
        if(isset($re['InnerID'])){
            //$re["InnerID"] = $tree;
            $i = updata('t_resgroup',['InnerID'=>$re['InnerID']],$re);
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
            $i = updata('t_sysorg',['InnerID'=>$re['InnerID']],$re);
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
            $i = updata('t_sysrole',['InnerID'=>$re['InnerID']],$re);
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


    // @sum_he
    // 获取所有菜单
    public function gettree(){
    return json(gettableDS('t_systree', []));
    }
    // @sum_he
    // 获取所有角色
    public function getRolesTree(){
    $re = input('post.');
    // return getjsonDs("user",$arr,'username,userid,email,status',"createtime",'0','10');
    return getjsonDs('sysrole','RoleID'." like '%" .$re['RoleID']."%'" , '', '', $re['page'], $re['pagesize']);
    }
    // @sum_he
    // 获取所有资源
    public function getAllData(){
    $re = input('post.');
    // return getjsonDs("user",$arr,'username,userid,email,status',"createtime",'0','10');
    return getjsonDs('sysdata','DataID'." like '%" .$re['DataID']."%'" , '', '', $re['page'], $re['pagesize']);
    }
    // @sum_he
    // 获取系统内所有人员
    public function getAlluser () {
        $re = input('post.');
        $list = array();
        $m   =   Db::name('sysuser');
        if(isset($re['UserID']) and $re['UserID']!=''){
            $list['data'] = $m->where('UserID|UserName|UserEmail|UserMobile','like','%'.$re['UserID'].'%')
                      ->field('UserID, UserMobile, UserName, UserEmail')
                      ->order('UserID')
                      ->page($re['page'], $re['pagesize'])
                      ->select();
            $list['count'] = $m->where('UserID|UserName|UserEmail|UserMobile','like','%'.$re['UserID'].'%')
                      ->count('*');
        }else{
            $list['data'] = $m->field('UserID, UserMobile, UserName, UserEmail')->order('UserID')->page($re['page'], $re['pagesize'])->select();
            $list['count'] = $m->count('*');
          
        }    
        return json($list)  ;
    }
    // @sum_he
    // 通过roleid获取角色下的人员
    public function getUsersByRoleID () {
    $re = input('post.');
    $RoleID = $re['RoleID']?$re['RoleID']:'';
    $page = $re['page']?$re['page']:'0';
    $pagesize = $re['pagesize']?$re['pagesize']:'20';
    if($RoleID!=''){
    return json(getUserToRole($RoleID, $page, $pagesize)); 
    }
    return json(['ID'=>'-1','msg'=>'RoleID Error.']);
    }
    // @sum_he
    // 通过userid获取所有角色
    public function getRolesByUserID () {
    $re = input('post.');
    $UserID = $re['UserID']?$re['UserID']:'';
    // $UserID = $re['UserID']?$re['UserID']:'';
    $page = 0;
    $pagesize = 20;
    if(isset($re['page'])){$page = $re['page'];}
    if(isset($re['pagesize'])){$page = $re['pagesize'];}
    if($UserID!=''){
    return json(getRoleByUserID($UserID, $page, $pagesize)); 
    }
    return json(['ID'=>'-1','msg'=>'UserID Error.']);
    }
    // @sum_he 获取某一组织下的人员
    public function getUsersByOrgID () {
    $re = input('post.');
    $OrgID = $re['OrgID']?$re['OrgID']:'';
    $page = $re['page']?$re['page']:'0';
    $pagesize = $re['pagesize']?$re['pagesize']:'20';
    if($OrgID!=''){
    return json(getUserForOrgID($OrgID, $page, $pagesize)); 
    }
    return json(['ID'=>'-1','msg'=>'OrgID Error.']);
    }
    // @sum_he
    // 获取所有组织
    public function getAllOrg () {
    $re = input('post.');
    return getjsonDs('sysorg','OrgName'." like '%" .$re['OrgName']."%'" , '', '', $re['page'], $re['pagesize']);
    }
}