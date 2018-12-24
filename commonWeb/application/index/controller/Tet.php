<?php
namespace app\idnex\controller;
use think\Controller;
use think\Db;
use think\Request;

class Tet extends Controller
{
    public function index()
    {
		echo 'hehe';
    }

    public function escienceAuthLogin(){
        echo p($_REQUEST);
    }

}


