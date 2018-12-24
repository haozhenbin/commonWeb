<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//Route::rule('路由表达式','路由地址','请求类新','路由参数(数组)','变量规则(数组)');
use think\Route;


Route::resource('blogs','index/Blog');



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    // '[hello]'     => [
    //     ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
    //     ':name' => ['index/hello', ['method' => 'post']],
    // ],
    //'hello/:name/[:id]/[:num]' => 'index/index/hello',
    //http://localhost:8899/corpora/v2/user/1
    //':version/user/:id' => 'api/:version.User/read',
    //'blogs' => 'index/blog',
	


];

