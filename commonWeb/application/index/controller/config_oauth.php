<?php
//ClientId,OAuth参数的App Key
define('OAUTH_CLIENT_ID', '92658');
//ClientSecret ,OAuth参数中的App Secret
define('OAUTH_CLIENT_SECRET', 'i4lCecACOc1fkM3dbsgscPzH2xml0qJo');
//回调地址,OAuth参数中的回调地址,必须与申请时候的回调地址一致
define('OAUTH_REDIRECT_URI', 'http://159.226.186.90/asset/index/escienceAuthLogin');
//下列参数为固定值,根据服务器地址或者协议调整https://passporttest.escience.cn
//http://passporttest.escience.cn/oauth2/authorize
define('OAUTH_ACCESS_TOKEN_URL', 'https://passport.escience.cn/oauth2/token');
define('OAUTH_AUTHORIZE_URL', 'https://passport.escience.cn/oauth2/authorize');
define('OAUTH_THEME', 'full');
define('OAUTH_LOGOUT_URL', 'https://passport.escience.cn/logout?WebServerURL=');


?>