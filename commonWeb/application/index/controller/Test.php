<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
class Test extends Controller
{
    public function index()
    {
		echo 'hehe';
    }

    public function escienceAuthLogin(){
        echo p($_REQUEST);
    }

}


