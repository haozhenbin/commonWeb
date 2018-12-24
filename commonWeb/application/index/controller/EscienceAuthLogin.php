<?php

namespace app\index\controller;
use lib\escienceoauth;
use lib\IDPException;
use think\Controller;
use think\Request;
use think\Db;

class EscienceAuthLogin extends Controller
{
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
        if(isset($_GET['act']) and $_GET['act']=='logout'){
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
        //验证用户名或邮箱或手机号是否存在
        $users=db('sysuser')->where(['UserEmail'=>$userInfo->cstnetId])->where(['Tag'=>'1'])->find();
        // dump($users); die;
        if($users){
                //设置登录次数
                incLogin($users['UserID']);
                $token=md5($users['InnerID'].$users['UserID'].date('Y-m-d',time()).$users['Password']);
                $timer=strtotime('now');
                $timer=$timer+60*60;
                $isrow = getCount("t_token",["UserID"=>$users['UserID']]);
                //lg($isrow);
                if($isrow>0){
                    updata("t_token",null,["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
                }else{
                    insert("t_token",["UserID"=>$users['UserID'],"token"=>$token,"extDate"=>$timer]);
                }
                
                //setcookie("token",$token);
                // $rt["stats"]='1';
                // $rt["msg"]="登录成功！";
                // $rt["token"]=$token;
                // $rt['data']['treelist']=getTreeToUserID($users['UserID']);
                // $rt['data']['Orginfo']=getOrgToUserID($users['UserID']);
                // $rt['data']['userinfo']=getUserToUserID($users['UserID']);
                // $rt['data']['userinfoext']=getUserExtToUserID($users['UserID']);
                //$rt;
                header("location:http://159.226.186.90/asset/sys/index.html#/translate/$token");
        		exit;
        }else{
        	//setcookie("token",'-1');
        	header("location:http://159.226.186.90/asset/sys/index.html#/translate/-1");
        	exit;
            // $rt=[
            //     'stats'=>"-1",
            //     'msg'=>"<i class='iconfont icon-minus-sign'>该系统没有科技云账号信息，请与管理员联系同步用户数据.</i>",
            //     'token'=>'',
            //     ];
            //return json($rt);
        }

        //header("location:http://159.226.186.90/asset/sys/index.html#/translate/$token");
        //exit;


        //echo 'hehe ';
		// $ch = curl_init();

		// //$data = array('data' => json_encode($rt));
		// //p($data);
		// curl_setopt($ch, CURLOPT_URL, 'http://159.226.186.90/asset/sys/index.html#/translate');
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $rt);

		// curl_exec($ch);


        //$this->assign('json',json($rt)); 
        //return $this->display('/sys/index.html#/translate');
        //header("location:$url");
        //return $this->fetch('/sys/index.html#/translate');
        // p($userInfo);
        // p($userInfo->cstnetId);
        // //p(json_decode($userInfo)['cstnetId']);
        // echo '<a href="?act=logout">退出登录</a>';
    }

    public function escienceAuthLogin(){
        echo 'test';
    }

}


