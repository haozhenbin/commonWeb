<?php
//ClientId,OAuth参数的App Key
define('OAUTH_CLIENT_ID', '83789');
//ClientSecret ,OAuth参数中的App Secret
define('OAUTH_CLIENT_SECRET', 'NzL9rNXxljIY2XvyB67bPceiIggz7SAJ');
//回调地址,OAuth参数中的回调地址,必须与申请时候的回调地址一致
define('OAUTH_REDIRECT_URI', 'http://10.0.52.21/oauth');
//下列参数为固定值,根据服务器地址或者协议调整https://passporttest.escience.cn
define('OAUTH_ACCESS_TOKEN_URL', 'http://passporttest.escience.cn/oauth2/token');
define('OAUTH_AUTHORIZE_URL', 'http://passporttest.escience.cn/oauth2/authorize');
define('OAUTH_THEME', 'full');
define('OAUTH_LOGOUT_URL', 'http://passporttest.escience.cn/logout?WebServerURL=');


?>