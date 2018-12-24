<?php
use Think\Log;
use Think\Controller;
use Think\Model;



//传递数据以易于阅读的样式格式化后输出
function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}

// //使用select语句
// function sel($sql){
// 	$Model = M();//进行原生的SQL查询
// 	return $Model->query($sql);
// }

// //插入数据
// function addM($tnaem,$data){
//     $insertId = -1;
//     $t = M($tnaem); 
//     $t->data($data);
//     if($t->create()){    
//         $result = $t->add($data); // 写入数据到数据库     
//     if($result){        // 如果主键是自动增长型 成功后返回值就是最新插入的值        
//             $insertId = $result;    
//         }
//     }
//     return $insertId;
// }


// function upM($tname,$where,$data){
//     $User = M($tname); // 实例化User对象// 要修改的数据对象属性赋值
//     return $User->where($where)->filter('strip_tags')->save($data); // 根据条件更新记录
// }

// //查询单挑记录
// function readD($dbname,$selpar,$showlist){
// 	$m   =   M($dbname);
//     $list = $m->where($selpar)->getField($showlist);
    
//     $td = array();
//     foreach ($list as $value) {
//     	$td = $value;
//     }
//     return $td ;
// }

// function readFind($dbname,$selpar){
//     $m   =   M($dbname);
//     $list = $m->where($selpar)->find();
//     return $list ;
// }
// //查询字段值（通过key）
// function getValueTokey($tablename,$selectData,$key){
// 	//echo $tablename.$selectData.$key;
// 	//p($selectData);
// 	$m   =   M($tablename);
//     $list = $m->where($selectData)->getField($key);
//     return $list ;
// }

// //查询会结果集
// function getDataSet($tablename,$selectData){
// 	//echo $tablename.$selectData.$key;
// 	//p($selectData);
// 	$m   =   D($tablename);
//     $list = $m->where($selectData)->select();
//     return $list ;
// }
// function getCount($tablename,$querystr){
//       $m = M($tablename); // 实例化对象'PAGE_START' => 1 ,
//       $count = $m->where($querystr)->count();
//       return $count;
// }

// //分页查询通用
// function selP($tName,$qData,$showList,$ordList,$fPaga,$pageNum,$url){
//     $m = M($tName); // 实例化对象'PAGE_START' => 1 ,
//     if($fPaga=='') { $fPaga=C('PAGE_START'); }else{$fPaga=(int)$fPaga;}
//     if($pageNum==''){$pageNum=C('PAGE_COUNT');}else{$pageNum=(int)$pageNum;}
//     $data['p']=(int)$fPaga;
//     $list = $m->where($qData)->field($showList)->order($ordList)->page($fPaga,$pageNum)->select();
//     $data['ds']=$list;
//     $count      = $m->where($qData)->count();
//     $Page = new \Common\Tools\PageA($count,$pageNum);
//     $show = $Page->show('Index/'.$url);
//     $data['pagelist']=$show;
//     return $data;
//   }

// //将datalist查询结果转换成html表格
//  function Tbody($ds){
//       $str = '<tbody>';
//       foreach($ds as $key => $vo){        
//         $str .=  "<tr>";
//         foreach ($vo as $k => $v) {
//           $str .=  "<td>".$v."</td>";
//         }
//         $str .= '</tr>';      
//       }   
//       $str .= '</tbody>';
//       return $str;
// }


// //返回满足layui的json数据
// function getjson($tablename,$selectData,$showList,$p,$pnum){
//     $m   =   M($tablename);
//     $list['data'] = $m->where($selectData)->field($showList)->page($p,$pnum)->select();
//     $list['count'] = $m->where($selectData)->count('*');
//     $list['code']=0;
//     $list['msg']='';

//     return $list ;
// }

function markstr($keys,$reps,$source){
    //p($keys);
    $patterns = "/$keys/" ; 
    //lg($source);
    //lg($patterns);
    //lg($reps);
    $rt = preg_replace($patterns , $reps , $source);
    if($rt){
      return $rt;
    }else{
      return $source;
    }
}


?>