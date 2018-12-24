function url(link){
    $("#content").html(link);
}
function u_head(link){
    l = "Index/"+link;
    //console.log(l);
    $.post(l,function(d){$("#content").html(d);});
}
function cg_show(){
  
    $("#wrongsel").show();
  
}
function cg_hide(){
  $("#wrongsel").hide();
  //console.log("ddddd");
}
function hskdown(url,PARAMS) { 
    var temp_form = document.createElement("form");      
    temp_form .action = "./Index/"+url;      
    temp_form .target = "_blank";
    temp_form .method = "post";      
    temp_form .style.display = "none"; 
      var opt = document.createElement("textarea");      
      opt.name = "ae";      
      opt.value = PARAMS;      
      temp_form .appendChild(opt);      
    document.body.appendChild(temp_form);      
    temp_form .submit();     
} 

function init_url(lik,cp){
    kssj = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    zwfs_fh2 = $("#zwfs_fh2").val();
    zwfs2 = $("#zwfs2").val();

    cj = $("#cjtype").val();
    shou = $("#shou").val();
    wei = $("#wei").val();
    kaishi = $("#kaishi").val();
    jiesu = $("#jiesu").val();
    num = $("#num").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+':'+cj+':'+shou+':'+wei+':'+kaishi+':'+jiesu+':'+num;
    //par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh
    //console.log(par);
    page_url_dataview(lik,cp,par);


}
function page_url_dataview(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        success:function(ss){
            $("#dataview_show").html(ss);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show").html("load failed.");
        }
    });
}

function init_url2(){
    //console.log($(odj).val());
    kssj = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    zwfs_fh2 = $("#zwfs_fh2").val();
    zwfs2 = $("#zwfs2").val();
  //  cj = $("#cjtype").val();
    shou = $("#shou").val();
    wei = $("#wei").val();
    kaishi = $("#kaishi").val();
    jiesu = $("#jiesu").val();
    num = $("#num").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+'::'+shou+':'+wei+':'+kaishi+':'+jiesu+':'+num;
    //par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh
    //console.log(par);

    $("#content_data").html('<per style="width:100px;text-align:center;">错篇查询结果显示'+
'<sapn style="padding-left:30px;"><a id="dlink" href="Index/zfcD_d/ae/pianzhanginfo:错篇:pz:'+par+'">下载</a></span></per>'+
      '<table class="layui-hide" id="usertb"></table>');
    layui.use(['table','form','layer'], function(){
      var table = layui.table
      ,layer = layui.layer
      ,form = layui.form;
      table.render({
        elem: '#usertb'
        ,cellMinWidth: 80
        ,size: 'sm'
        ,skin: 'line'
        ,url: "Index/nav_cpjs_data" //数据接口
        ,method:"post"
        ,where:{'ae':par}
        ,page: true //开启分页
        //keykh2,zwm,gm,sse,date,namezw,koushi,zuowen,lh,rh,ch,zh,jb,innerid;
        ,cols: [[ //表头
          {field: 'id', title: 'ID', sort: true, fixed: 'left',width:80}
          ,{field: 'pz', title: '篇章错句信息', fixed: 'left',templet:'#tTpl'}
          ,{field: '原文', title: '原文', fixed: 'left',width:100,templet: '#aTpl'}
          ,{field: '标注版', title: '标注版', fixed: 'left',width:100,templet: '#bTpl'}
        ]]
      });

    });


}


function extkai(){
    $("#guan").css("display","");
    $("#kai").css("display","none");
    $("#ext_select").css("display","");
}

function extguan(){
    $("#guan").css("display","none");
    $("#kai").css("display","");
    $("#ext_select").css("display","none");
}


function sluser(){
var username = $("#username").val()
,tname = $("#tname").val()
,tel = $("#tel").val();
$("#dlink").attr('href','Index/du/ae/u:'+username+':'+tname+':'+tel);
layui.use(['table','form'], function(){
  var table = layui.table
  ,form = layui.form;
table.reload('usertb',{
  where: { //设定异步数据接口的额外参数，任意设
    username: username
    ,tname: tname
    ,tel:tel
  }
  ,page: {
    curr: 1 //重新从第 1 页开始
  }
});
});
}


function user(){
var sel = ['<div class="block_left_header col-lg-12" style="text-align: center;">按条件查询</div><hr>',
'         <div class="col-lg-3" style=" margin: 2px;">',
'            <div class="input-group">',
'              <span class="input-group-addon">用户名</span>',
'              <input type="text" id="username" class="form-control">',
'            </div>',
'          </div>',
'         <div class="col-lg-3" style=" margin: 2px;">',
'            <div class="input-group">',
'              <span class="input-group-addon">真实姓名</span>',
'              <input type="text" id="tname" class="form-control">',
'            </div>',
'          </div>',
'          <div class="col-lg-3" style=" margin: 2px;">',
'           <div class="input-group">',
'              <span class="input-group-addon">手机号码</span>',
'              <input type="text" id="tel" class="form-control">',
'            </div>',
'          </div>',
'          <div class="col-lg-2" style=" margin: 2px;">',
'           <div class="input-group">',
'              <button class="btn btn-primary" onclick="sluser()" type="submit">查询</button>',
'            </div>',
'          </div>',
'</div><div class="block_left_header col-lg-12">'].join("");


$("#content").html(sel+'<per style="width:100px;text-align: center;">系统注册用户管理</per><table class="layui-hide" id="usertb">'+
'</table></div><div style="padding-left:30px;"><a id="dlink" href="Index/du/ae/u">下载</a></div>'+
'<script type="text/html" id="checkboxTpl">'+
'<input type="checkbox" name="userid" value="{{d.userid}}" title="锁定" lay-filter="lockDemo" {{ d.tag == 0 ? "checked" : "" }}>'+
'</script>');
//    $("#content").append("<div><input type='button' onclick='exp_png(\"usertb\")' value='导出PDF'></div>");

layui.use(['table','form'], function(){
  var table = layui.table
  ,form = layui.form;
table.render({
    elem: '#usertb'
    ,cellMinWidth: 80
    ,size: 'sm'
    ,skin: 'line'
    ,url: "Index/user" //数据接口
    ,page: true //开启分页
    ,cols: [[ //表头
      {field: 'userid', title: 'ID', sort: true}
      ,{field: 'username', title: '用户名'}
      ,{field: 'tname', title: '姓名'}
      ,{field: 'rights', title: '权限', sort: true} 
      ,{field: 'levels', title: '等级', sort: true}
      ,{field: 'score', title: '积分', sort: true}
      ,{field: 'tel', title: '手机号码'}
      ,{field: 'email', title: '电子邮箱'}
      ,{field: 'national', title: '国籍'}
      ,{field: 'unit', title: '公司'}
      ,{field: 'speciality', title: '职业'}
      ,{field: 'regdate', title: '注册日期',width:100}
      ,{field:'lock', title:'是否锁定', templet: '#checkboxTpl', unresize: true}
    ]]
    ,done: function(res, curr, count){
       // console.log(res);
    }
  });

  form.on('checkbox(lockDemo)', function(obj){
    tg = '1';
    if(obj.elem.checked){tg = '0';}
    $.ajax({
        type:"POST",
        async: false,
        url: "Index/lockuser",        
        data:{'userid':this.value,'tag':tg},
        success:function(data){
            if(data>0){
                layer.msg('已更新！');
            }else{
                layer.msg('没有更新成功！');
            }
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.msg('系统操作异常！错误号2018130214');
        }
    });
    //layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
  });
  
});
}


function sqaction(id,nm,r){

    var pic = '<div style=" margin : 10px 30px;"><div>用户名'+nm+'</div>';
    //var pic ="";
      pic += '<div style=" margin : 10px;">管理员<input name="rval" id="rval" type="radio" value="A" ';
        if(r=='A'){pic += 'checked >'}else{pic += '>'}
      pic += '审核员<input type="radio"   name="rval"  id="rval" value="B" ';
        if(r=='B'){pic += 'checked >'}else{pic += '>'}
      pic +=  '普通用户<input type="radio"   name="rval"  id="rval"   ';
        if((r=='1') || (r=='2')){pic += 'value="'+r+'" checked title="普通用户" >'}else{pic += 'value="1" title="普通用户">'}
      pic += '</div></div>';
  //console.log(pic);
            layer.open({
              type: 1,
              skin: 'layui-layer-rim', //加上边框
              title: '用户权限管理',
              //shadeClose: true,
              closeBtn: 0,
              scrollbar: false,
              shade: 0.8,
              btnAlign: 'c',
              area: ['300px', '200px'], //宽高
              content: pic,
              btn: ['更新', '关闭']
               ,yes: function(index, layero){
        $.ajax({
        type:"POST",
        async: false,
        url:'Index/uprights',        
        data:{'userid':id,'rights': $("input[name='rval']:checked").val() },
        success:function(ss){
            if(ss==1){
                layer.alert('更新成功！');

            }else{
                layer.alert('更新失败！');
            }
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.alert('系统操作异常！错误号201802021929');          
        }
    });
  }
  ,btn2: function(index, layero){
    //按钮【按钮二】的回调
    layer.close(index);
    //return false 开启该代码可禁止点击该按钮关闭
  }


            });


    
}
// function init_sample(lik,cp){
//     kssj = $("#kssj").val();
//     kssj_fh=$("#kssj_fh").val();
//     ksgj = $("#ksgj").val();
//     zwtm = $("#zwtm").val();
//     zsjb = $("#zsjb").val();
//     zwfs_fh = $("#zwfs_fh").val();
//     zwfs = $("#zwfs").val();
//     zwfs_fh2 = $("#zwfs_fh2").val();
//     zwfs2 = $("#zwfs2").val();
//     keys = $("#keys").val();
//     par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+':'+keys;
//     page_url_dataview(lik,cp,par);
// }

function init_sample(lik,cp){
    kssj = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    zwfs_fh2 = $("#zwfs_fh2").val();
    zwfs2 = $("#zwfs2").val();
    keys = $("#keys").val();
    k2= $("#wrongsel").val();
    k1 = $("input[name='tk']:checked").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+':'+keys+':'+k1+':'+k2;
    //console.log(k1+"---"+k2);
    page_url_dataview(lik,cp,par);
}



function qxgl(){
  var sel = ['<div class="block_left_header col-lg-12" style="text-align: center;">按条件查询</div><hr>',
'         <div class="col-lg-3" style=" margin: 2px;">',
'            <div class="input-group">',
'              <span class="input-group-addon">用户名</span>',
'              <input type="text" id="username" class="form-control">',
'            </div>',
'          </div>',
'         <div class="col-lg-3" style=" margin: 2px;">',
'            <div class="input-group">',
'              <span class="input-group-addon">真实姓名</span>',
'              <input type="text" id="tname" class="form-control">',
'            </div>',
'          </div>',
'          <div class="col-lg-3" style=" margin: 2px;">',
'           <div class="input-group">',
'              <span class="input-group-addon">手机号码</span>',
'              <input type="text" id="tel" class="form-control">',
'            </div>',
'          </div>',
'          <div class="col-lg-2" style=" margin: 2px;">',
'           <div class="input-group">',
'              <button class="btn btn-primary" onclick="sluser()" type="submit">查询</button>',
'<span style="padding-left:30px;"><a id="dlink" href="Index/du/ae/u">下载</a></span>',
'            </div>',
'          </div>',
'</div><div class="block_left_header col-lg-12">'].join("");


$("#content").html(sel+'<per style="width:100px;text-align: center;">系统用户权限管理</per><table class="layui-hide" id="usertb"></table>'+

'<script type="text/html" id="aTpl">'+
'<a href="javascript:sqaction(\'{{ d.userid }}\',\'{{ d.username }}\',\'{{ d.rights }}\')" class="layui-table-link"  lay-filter="qxbutton" >授权</a>'+
'</script>'
);
//userid,username,tname,rights,levels,score,tel,email,national,unit,speciality,DATE_FORMAT(regDate,'%Y-%m-%d') regdate,tag
layui.use(['table','form'], function(){
  var table = layui.table
  ,form = layui.form;
  table.render({
    elem: '#usertb'
    ,cellMinWidth: 80
    ,size: 'sm'
    ,skin: 'line'
    ,url: "Index/user" //数据接口
    ,page: true //开启分页
    ,cols: [[ //表头
      {field: 'userid', title: 'ID', sort: true,}
      ,{field: 'username', title: '用户名',}
      ,{field: 'tname', title: '姓名'}
      ,{field: 'tel', title: '手机号码'}
      ,{field: 'rights', title: '权限', sort: true} 
      ,{field: 'levels', title: '等级', sort: true}
      ,{field: 'score', title: '积分', sort: true}
      ,{field: 'national', title: '国籍'}
      ,{field: 'regdate', title: '注册日期'}
      ,{field:'quanxian', title:'授权', templet: '#aTpl', unresize: true}
    ]]
    ,done: function(res, curr, count){
       // console.log(res);
    }
  });
// //userid,username,tname,rights,levels,score,tel,email,national,unit,speciality,DATE_FORMAT(regDate,'%Y-%m-%d') regdate,tag
//   form.on('a(qxbutton)', function(obj){
//     layer.msg('已更新！');
//     // tg = '1';
//     // if(obj.elem.click){tg = '0';}
//     // $.ajax({
//     //     type:"POST",
//     //     async: false,
//     //     url: "Index/lockuser",        
//     //     data:{'userid':this.value,'tag':tg},
//     //     success:function(data){
//     //         if(data>0){
//     //             layer.msg('已更新！');
//     //         }else{
//     //             layer.msg('没有更新成功！');
//     //         }
//     //     },
//     //     error:function(XMLHttpRequest, textStatus, thrownError){
//     //       layer.msg('系统操作异常！错误号2018130214');
//     //     }
//     // });
//     //layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
//   });
  
});
}


function fk(){
    $("#content").html('<per style="width:100px;text-align: center;">用户留言反馈信息管理，设置是否可以公开发布信息。'+
'<span style="padding-left:30px;"><a id="dlink" href="Index/du/ae/fk">下载</a></span>'+
'</per><table class="layui-hide" id="usertb"></table>'+
'<script type="text/html" id="checkboxTpl">'+
'<input type="checkbox" name="ubackid" value="{{d.ubackid}}" title="发布" lay-filter="lockDemo" {{ d.uopen == 1 ? "checked" : "" }}>'+
'</script>');

layui.use(['table','form'], function(){
  var table = layui.table
  ,form = layui.form;
  table.render({
    elem: '#usertb'
    ,cellMinWidth: 80
    ,size: 'sm'
    ,skin: 'line'
    ,url: "Index/fk" //数据接口
    ,page: true //开启分页
    //select ubackid,username,utime,ubacktitle,uback,uopen from think_userback  ORDER BY utime desc limit 0,30;
    ,cols: [[ //表头
      {field: 'ubackid', title: 'ID', sort: true, fixed: 'left',width:80}
      ,{field: 'username', title: '用户名', fixed: 'left',width:100}
      ,{field: 'ubacktitle', title: '留言信息标题', fixed: 'left',width:200}
      ,{field: 'uback', title: '留言信息内容'} 
      ,{field: 'utime', title: '发布日期'} 
      ,{field:'lock', title:'是否发布', templet: '#checkboxTpl', unresize: true,width:100}
    ]]
  });

  form.on('checkbox(lockDemo)', function(obj){
    tg = '0';
    if(obj.elem.checked){tg = '1';}
    $.ajax({
        type:"POST",
        async: false,
        url: "Index/openfk",        
        data:{'ubackid':this.value,'uopen':tg},
        success:function(data){
            if(data>0){
                layer.msg('已更新！');
            }else{
                layer.msg('没有更新成功！');
            }
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.msg('系统操作异常！错误号2018130243');
        }
    });
    //layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
  });
  
});
}

function setimg(str){
  $("#yl_img").attr('src','/hsk/zt/'+str+'.gif');
 // console.log('hehe');
}


function get_zw_pic(zwm){
    zpic(zwm);
}
function get_zw_bzb(zwm){
    ztxt(zwm);
}

function zpic(zwm){
    $.ajax({
        type:"POST",
        async: false,
        url:'Index/get_zwm',        
        data:{'zwm':zwm},
        success:function(ss){
            var pic = '<div id="yl_pic">';
            pic += ss.info;
            pic += '<div id="yl_nav">'+ss.nav+'</div>';
            pic += '<img id="yl_img" src="/hsk/zt/'+ss.link+'.gif" style=" min-height: 700px; width: 100%;height: 100%; display: inline-block；"/>';
            pic += '</div>';
            layer.open({
              type: 1,
              skin: 'layui-layer-rim', //加上边框
              title: '原始语料 [语料编号:'+zwm+']',
              shadeClose: true,
              scrollbar: false,
              shade: 0.8,
              area: ['100%', '90%'], //宽高
              content: pic
            });
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.alert('没找到原文.');          
        }
    });
}

function ztxt(zwm){
    //$("#myimage2").attr('src','Tagged/'+zwm+"_2.txt");
    $.ajax({
        type:"POST",
        async: false,
        url:'Index/get_zwm_txt',        
        data:{'zwm':zwm},
        success:function(ss_txt){   
            var pic = '<div id="yl_txt">';
            pic += ss_txt.info;
            pic += '</div>';
            pic += '<pre  id="text_body">'+ss_txt.txt+'</pre>';
            layer.open({
              type: 1,
              skin: 'layui-layer-rim', //加上边框
              title: '标注版语料 [语料编号:'+zwm+']',
              shadeClose: true,
              scrollbar: false,
              shade: 0.8,
              area: ['700px', '90%'], //宽高
              content: pic
            });
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.alert('没有标注数据.');
        }
    });
}

function hsk(){
    $("#content").html('<per style="width:100px;text-align: center;">用户语料库修改记录，请确认审核，审核后将更新标准语料库。</per>'+
'<span style="padding-left:30px;"><a id="dlink" href="Index/du/ae/hsk">下载</a></span>'+
'<table class="layui-hide" id="usertb"></table>'+
'<script type="text/html" id="checkboxTpl">'+
'<input type="checkbox" data-hsk="{{ d.keykh2 }}" data-hid="{{ d.hskid }}" name="innerid" value="{{d.innerid}}" title="确认" lay-filter="lockDemo" {{ d.tag == 1 ? "checked" : "" }}>'+
'</script>'+
'<script type="text/html" id="aTpl">'+
'<a href="javascript:zpic(\'{{ d.zwm }}\')" class="layui-table-link">{{ d.innerid }}</a>'+
'</script>'
);

layui.use(['table','form','layer'], function(){
  var table = layui.table
  ,layer = layui.layer
  ,form = layui.form;
  table.render({
    elem: '#usertb'
    ,cellMinWidth: 80
    ,size: 'sm'
    ,skin: 'line'
    ,url: "Index/hsk" //数据接口
    ,page: true //开启分页
    //select ubackid,username,utime,ubacktitle,uback,uopen from think_userback  ORDER BY utime desc limit 0,30;
    ,cols: [[ //表头
      {field: 'innerid', title: 'ID', sort: true, fixed: 'left',width:80,templet: '#aTpl'}
      ,{field: 'hskid', title: 'HSKID', sort: true, fixed: 'left',width:80}
      ,{field: 'keykh2', title: '错句信息', fixed: 'left'}
      ,{field: 'checkuser', title: '提交人', fixed: 'left',width:100}
      ,{field: 'createdate', title: '提交日期', fixed: 'left',width:100}
      ,{field:'tag', title:'是否确认', templet: '#checkboxTpl', unresize: true,width:100}
    ]]
  });

  form.on('checkbox(lockDemo)', function(obj){
    tg = '0';
    if(obj.elem.checked){tg = '1';}
    if(tg=='1'){
    layer.confirm('确认要更新操作吗？更新操作将会更变原始语料库信息。', function(index){
        
        hskkey = obj.elem.dataset.hsk;
        kid = obj.elem.dataset.hid;
        id = obj.elem.value;
        $.ajax({
            type:"POST",
            async: false,
            url: "Index/cfm_hsk",        
            data:{'hskid':kid,'tag':tg,'innerid':id,'key':hskkey},
            success:function(data){
                if(data==1){
                    layer.msg('已更新！');
                }else{
                    layer.msg('没更新！');
                }
            },
            error:function(XMLHttpRequest, textStatus, thrownError){
              layer.msg('系统操作异常！错误号2018131458');
            }
        });

        layer.close(index);
      });
    }else{
       layer.msg('数据已更新到原始语料库，不能重复更新。'); 
    }


    });

  });

}


function page_url(lik,cp){

    layui.use('table', function(){
  var table = layui.table;
  //、、userid,username,tname,rights,levels,score,tel,email,national,unit,speciality,DATE_FORMAT(regDate,'%Y-%m-%d') regdate
  //第一个实例
  table.render({
    elem: '#usertb'
    ,height: 400
    ,url: lik //数据接口
    ,page: true //开启分页
    ,cols: [[ //表头
      {field: 'userid', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'username', title: '用户名', width:80}
      ,{field: 'tname', title: '姓名', width:80}
      ,{field: 'rights', title: '权限', width:60, sort: true} 
      ,{field: 'levels', title: '等级', width: 60, sort: true}
      ,{field: 'score', title: '积分', width: 60, sort: true}
      ,{field: 'tel', title: '手机号码', width: 100}
      ,{field: 'email', title: '电子邮箱', width: 120}
      ,{field: 'national', title: '国籍', width: 100}
      ,{field: 'unit', title: '公司', width: 135}
      ,{field: 'speciality', title: '职业', width: 135}
    ]]
  });
  
});




    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp},
        success:function(data){
            $("#content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#content").html("load failed. the link ID is "+lik);
        }
    });

}

function pu(lik,cp){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp},
        success:function(data){
            $("#content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#content").html("load failed. the link ID is "+lik);
        }
    });

}


function urlD(link,data){
    $.ajax({
        type:"POST",
        async: false,
        url:"Admin/Index/"+link,
        data: data,
        success:function(d){
            $("#content").html(d);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#content").html("load failed. the link ID is "+link);
        }
    });
}

//入口函数
$(function() {
  //$( "#menu" ).menu();
  cg_hide();
  $(this).val($(this).val().replace(/[~'!<>@#$%^&*()-+_=:]/g, ""));
  $("#keys").keydown(function(event) {  
         if (event.keyCode == 13) { 
             //执行操作
             init_sample('Index/nav_zfcsample_data',0);
         }  
     })  
  // $("#button_qpjs").click(function(){
  //   init_url('nav_qpjs_data','0');
  // });
  // $("[data-nav='n']").click(function(){
  //   //$("body_content").html("load failed.");
  //   mouseClk(this);
  //   chg_url(this);
    
  // });

    // $('#pic_dialog').dialog("close");
    // $('#bzb_dialog').dialog("close");


  // $("[data-nav='s']").click(function(){
  //   //$("body_content").html("load failed.");
  //  //mouseClk(this);
  //   chg_url(this);
    
  // });


  // $("[data-nav='n']").mousemove(function(){
  //   mouseMv(this);
  // });

 // $.ajax({
 //        type:"POST",
 //        async: false,
 //        url:"./Left",
 //        data: "sid=left",
 //        success:function(data){
 //            $("#left_side").html(data);
 //        },
 //        error:function(XMLHttpRequest, textStatus, thrownError){
 //          $("#left_side").html("load failed.");
 //        }
 //    });

 // $.ajax({
 //        type:"POST",
 //        async: false,
 //        url:"./Left",
 //        data: "sid=right",
 //        success:function(data){
 //            $("#right_side").html(data);
 //        },
 //        error:function(XMLHttpRequest, textStatus, thrownError){
 //          $("#right_side").html("load failed.");
 //        }
 //    });
});

function chg_url(lik){
    //console.log(lik);
    $.ajax({
        type:"POST",
        async: false,
        url:"./Index/"+$(lik).eq(0).attr('id'),
        // data: "nav="+$(lik).eq(0).attr('id'),
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
            //console.log(textStatus+'-----'+thrownError);
          $("#body_content").html("load failed.");
        }
    });

}


function getData(dataList){
var jsonstr = [];
for (var i = 0; i < dataList.length; i++) {
var json = {};
json.name = dataList[i];
json.value = "value";
jsonstr.push(json);
}
return jsonstr;
}

// function setimg(str){
//   $("#myimage2").attr('src','zt/'+str+'.gif');
// }

function hintsx(){
  if($('.zwsx_bzb').is(':hidden')){
    $(".zwsx_bzb").show(1000);
  }else{
    $(".zwsx_bzb").hide(1000);
  }
}

//2018-8-11升级后台查询检索功能；

function keyEnterCupture(id){
    //onsole.log("this is "+id);

    var theEvent = window.event || arguments.callee.caller.arguments[0]; //谷歌能识别event，火狐识别不了，所以增加了这一句，chrome浏览器可以直接支持event.keyCode
    var code = theEvent.keyCode;
    if(code == 13){
      $("#showmessage").show();
      $("#"+id).click();
    }
}

function selectOrderclick(){
    $('#pages').html('');
    $('#table_tr').html('<tr><td>正在检索...</td></tr>');
    //参数准备
    kssj = $("#kssj").val();
    psize = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    zwfs_fh2 = $("#zwfs_fh2").val();
    zwfs2 = $("#zwfs2").val();
    keys = $("#keys").val();
    k1 = $("input[name='tk']:checked").val();
    k3= $("#showNum").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+':'+keys+':'+k1+':'+k3;
    //得到记录数；
    rows = getRows('index/nav_zfcOrder_getRows2',par);
    //分页回调；
    if(rows > 0 ){
      layPagerForHsk('pages',rows,getOrderData,'index/nav_Order_data2',par,'table_tr');
    }else{
      $('#pages').html('');
      $('#table_tr').html('<tr><td>没有检索到任何信息...</td></tr>');
    }
  }



function getOrderData(page,psize,urls,par,owner){
    var json = {};
      json.page = page;
      json.psize = psize;
      json.ae = par;
      var trs = "";
      var header = '<tr> '
                       +'   <td class="success" width="20px">ID</td>'
                       +'   <td colspan="2" class="warning" ondblclick="hintsx()" >搜索原句</td>'
                       +'   <td class="info" width="50px">原文</td>'
                       +'   <td class="info" width="50px">标注版</td>'
                       +' </tr>';
      $.ajax({
        type:"GET",
        url:urls,     
        data:json,
        success:function(data){
          $.each(data['data'],function(idx,obj){
            id = idx+1;
             trs += "<tr>"
                +"<td class='primary'>"+id+"</td>"
                +"<td class='primary'  align='right' style='border-right:none'  ondblclick='changeorderdata(\""+obj[7]+"\")' title='"+obj[10]+"'>"+obj[1]+"</td>"
                +"<td class='primary' style='border-left:none' ondblclick='changeorderdata(\""+obj[7]+"\")'";
             var pars = par;
             if(pars.indexOf(":l:")!=-1){
                trs2 = "><span style='color:blue;' title='"+obj[8]+"'>"+obj[2]+"</span><em>"+obj[3]+"</em><span title='"+obj[0]+"'>"+obj[4]+"</span></td>";
             }else{
                trs2 = "><em>"+obj[3]+"</em><span style='color:blue;' title='"+obj[8]+"'>"+obj[2]+"</span><span title='"+obj[0]+"'>"+obj[4]+"</span></td>";
             }
                
                
                
                // title='"+obj[0]
                
               trs += trs2+"<td class='info'><a href='javascript:get_zw_pic(\""+obj[6]+"\")'>原文</a></td>"
                +"<td class='info'><a href='javascript:get_zw_bzb(\""+obj[6]+"\")'>标注版</a>"
                +"<span id='zwm"+obj[7]+"' style='display:none;'>"+obj[6]+"</span>"
                +"<span id='shuxinginfo"+obj[7]+"' style='display:none;'>"+obj[0]+"</span>"
                +"<span id='hskinfo"+obj[7]+"' style='display:none;'>"+obj[10]+"</span>"
                +"</td></tr>"

      　　});
          console.log(trs);
          $('#'+owner).html(header+trs);
          $("#samplelink").show();
          $("#samplelink").click(function(){
            //alert("在检索结果中随机选取500条记录下载，如果需要全部检索结果请联系管理员。");
            hskdown('d_order',data['xz']);
          });
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
                 $('#'+owner).html("<tr><td>load failed.</td></tr>");
        }
    });
  }


  
function getRows(url,par){
    rows = 0;
    $.ajax({
        type:"POST",
        url: url,     
        async:false,
        data:{'ae':par},
        success:function(data){
          rows = data;
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $('#'+owner).html("<tr><td>load rows failed.</td></tr>");
        }
      });
    return rows;
  }

  function getData(page,psize,urls,par,owner){
    var json = {};
      json.page = page;
      json.psize = psize;
      json.ae = par;
      var trs = "";
      var header = '<tr ondblclick="javascript:hintsx()"> '
                       +'   <td class="success" width="20px">ID</td>'
                       +'   <td class="warning" >搜索原句</td>'
                       +'   <td class="info" width="50px">原文</td>'
                       +'   <td class="info" width="50px">标注版</td>'
                       +' </tr>';
      $.ajax({
        type:"GET",
        url:urls,     
        data:json,
        success:function(data){
          $.each(data['data'],function(idx,obj){
            id = idx+1;
             trs += "<tr>"
                +"<td class='primary'>"+id+"</td>"
                +"<td class='primary'  ondblclick='changehskdata(\""+obj[3]+"\")'>"+obj[1]
                +"<div class='zwsx_bzb' id='shuxinginfo"+obj[3]+"' style='font-size: 10px;color: #66CC00;' >"+obj[0]+"</div>"
                +"<div id='zwm"+obj[3]+"' style='display:none;'>"+obj[2]+"</div><div id ='hskinfo"+obj[3]+"' style='display:none;'>"+obj[4]+"</div></td>"
                +"<td class='info'><a href='javascript:get_zw_pic(\""+obj[2]+"\")'>原文</a></td>"
                +"<td class='info'><a href='javascript:get_zw_bzb(\""+obj[2]+"\")'>标注版</a></td>"

      　　});
          $('#'+owner).html(header+trs);
          $("#samplelink").show();
          $("#samplelink").click(function(){
            //alert("在检索结果中随机选取500条记录下载，如果需要全部检索结果请联系管理员。");
            hskdown('d_sample2',data['xz']);
          });
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
                 $('#'+owner).html("<tr><td>load failed.</td></tr>");
        }
    });
  }
  function layPagerForHsk(elem_id,page_num,exefunc,urls,par,owner){
    layui.use('laypage', function(){
    var laypage = layui.laypage;
    laypage.render({
      elem: elem_id //注意，这里的 test1 是 ID，不用加 # 号
      ,count: page_num //数据总数，从服务端得到
      ,layout :['first','prev', 'page', 'next','last','count','limit','skip']
      ,groups: 3
      ,limits: [10, 20, 50,100] 
      ,first:'<<'
      ,prev:'<'
      ,next:'>'
      ,last:'>>'
      ,jump: function(obj, first){
          if(!first){
            exefunc(obj.curr,obj.limit,urls,par,owner);
          }else{
            exefunc(1,10,urls,par,owner);
          }
        }
      });
    });
  }

  function searchkeys(){
    var theEvent = window.event || arguments.callee.caller.arguments[0]; //谷歌能识别event，火狐识别不了，所以增加了这一句，chrome浏览器可以直接支持event.keyCode
        var code = theEvent.keyCode;
        if(code == 13){
           selecthskclick(); 
        }
  }


function selecthskclick(){
    $('#pages').html('');
    $('#table_tr').html('<tr><td>正在检索...</td></tr>');
    //参数准备
    kssj = $("#kssj").val();
    psize = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    zwfs_fh2 = $("#zwfs_fh2").val();
    zwfs2 = $("#zwfs2").val();
    keys = $("#keys").val();
    k2= $("#wrongsel").val();
    k1 = $("input[name='tk']:checked").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+zwfs2+':'+zwfs_fh2+':'+keys+':'+k1+':'+k2;
    //得到记录数；
    rows = getRows('index/nav_zfcsample_getRows',par);
    //分页回调；
    if(rows > 0 ){
      layPagerForHsk('pages',rows,getData,'index/nav_zfcsample_data2',par,'table_tr');
    }else{
      $('#pages').html('');
      $('#table_tr').html('<tr><td>没有检索到任何信息...</td></tr>');
    }
  }