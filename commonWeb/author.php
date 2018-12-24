<?php
require_once 'config_oauth.php';
require_once 'class_escienceoauth.php';
//请求passport登录页面
if(!isset($_GET['code'])&&!isset($_GET['act'])){
    $url = OAUTH_AUTHORIZE_URL.'?response_type=code&theme='.OAUTH_THEME.'&client_id='.OAUTH_CLIENT_ID.'&redirect_uri='.OAUTH_REDIRECT_URI;
    header("location: $url");
    return;
}
//退出登录
if($_GET['act']=='logout'){
    //退出成功后,重定向到127.0.0.1
    //此处理应先清除本地的Session,再重定向到通行证的退出连接
    header("location:".OAUTH_LOGOUT_URL."http://127.0.0.1/oauth");
    return;
}
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
//回调处理逻辑
$provider = new EscienceOauth(array(
        'clientId'  =>  OAUTH_CLIENT_ID,
        'clientSecret'  =>  OAUTH_CLIENT_SECRET,
        'redirectUri'   =>  OAUTH_REDIRECT_URI
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
?>
