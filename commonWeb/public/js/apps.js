//入口函数
$(function() {
  layui.use(['form',  'layer', 'element'], function(){
    var layer = layui.layer
    ,form = layui.form
    ,element = layui.element;
  });



  $(this).val($(this).val().replace(/[~'!<>@#$%^&*()-+_=:]/g, ""));

  $("[data-nav='n']").click(function(){
    chg_url(this);
  });

  $("[data-nav='s']").click(function(){
    chg_url(this);
  });
  

 $.ajax({
        type:"POST",
        async: false,
        url:"./Left",
        data: "sid=left",
        success:function(data){
            $("#left_side").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#left_side").html("load failed.");
        }
    });

});



///自定义函数

//过滤非法字符
function trm(s){ 
    var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]") 
    var rs = ""; 
    for (var i = 0; i < s.length; i++) { 
        rs = rs+s.substr(i, 1).replace(pattern, ''); 
    } 
    return rs; 
} 

function u(lik){
   $.ajax({
        type:"POST",
        async: false,
        url:"./Index/"+lik,
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
function chg_url(lik){
    $.ajax({
        type:"POST",
        async: false,
        url:"./Index/"+$(lik).eq(0).attr('id'),
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#body_content").html("load failed.");
        }
    });
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


// function hskdown(url,PARAMS){
//   $.ajax({
//         type:"POST",
//         async: false,
//         url:"./Index/"+url,        
//         data:{'ae':PARAMS},
//         success:function(data){
//             console.log(data);
//         },
//         error:function(XMLHttpRequest, textStatus, thrownError){
//           console.log("error");
//         }
//     });
// }

function page_url(lik,cp){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp},
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#body_content").html("load failed.");
        }
    });
}
//elem_id:接收分页的divID；
//page_num：记录条数（数据总数）；
//urls：获取数据的url连接；
//owner：获取数据区域，可自定义；
//exefunc：执行的回调函数；
function layPages(elem_id,page_num,urls,owner,exefunc){
  layui.use('laypage', function(){
  var laypage = layui.laypage;
  laypage.render({
    elem: elem_id //注意，这里的 test1 是 ID，不用加 # 号
    ,count: page_num //数据总数，从服务端得到
    ,layout :['first','prev', 'page', 'next','last','limit','skip']
    ,groups: 3
    ,limits: [10, 20, 50,100] 
    ,first:'<<'
    ,prev:'<'
    ,next:'>'
    ,last:'>>'
    ,jump: function(obj, first){
        if(!first){
          exefunc(obj.curr,obj.limit,urls,owner);
        }else{
          exefunc(1,10,urls,owner);
        }
      }
    });
  });
}


function page_url_dataview(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        // beforeSend:function(){
        //   //console.log("12123");
        //   //$("#dataview_show").thml("");
          
        // },
        success:function(ss){
            $("#dataview_show").html(ss);
            //$("#showmessage").hide();
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show").html("load failed.");
        }
    });
}

function page_url_dataview1(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        success:function(ss){
            $("#dataview_show1").html(ss);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show1").html("load failed.");
        }
    });
}

function detailz(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        success:function(ss){
            //console.log(ss);
            $("#dataview_show").html(ss);
            $("#dataview_show").attr('display','');
                layer.open({
                  type: 1,
                  title: '详细信息显示',
                  scrollbar:false,
                  skin: 'layui-layer-rim', //加上边框
                  area: ['90%', '90%'], //宽高
                  content: $("#dataview_show"),
                  cancel: function(index, layero){ 
                    $("#dataview_show").html("");
                    $("#dataview_show").attr('display','none');
                  },
                });
            
        },
        
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show").html("load failed.");
        }
    });
}

function detailz1(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        success:function(ss){
            //console.log(ss);
            $("#dataview_show1").html(ss);
            $("#dataview_show1").attr('display','');
                layer.open({
                  type: 1,
                  title: '详细信息显示',
                  scrollbar:false,
                  skin: 'layui-layer-rim', //加上边框
                  area: ['90%', '90%'], //宽高
                  content: $("#dataview_show1"),
                  cancel: function(index, layero){ 
                    $("#dataview_show1").html("");
                    $("#dataview_show1").attr('display','none');
                  },

                });
            
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show1").html("load failed.");
        }
    });
}




function page_url_par2(lik,cp,action){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':action},
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#body_content").html("load failed.");
        }
    });
}
function page_url_par3(lik,cp,odj){
    //console.log($(odj).val());
    page_url_par2(lik,cp,$(odj).val());
}

function page_url_par4(lik,cp,obj){
    sel = $(obj).val();
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'p':cp,'ae':sel},
        success:function(data){
            $("#dataview_show").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#dataview_show").html("load failed.");
        }
    });
}
//update
function updt_ajx(lik,dt){
    $.ajax({
        type:"POST",
        async: false,
        url:lik,        
        data:{'data':dt},
        success:function(rt){
            if(rt.tag > 0){
                layer.msg(rt.msg); 
            }else{
               layer.msg(rt.msg); 
            }
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.msg('出现了一个异常，系统没有正确执行。'); 
        }
    });
}

function init_url_uppswd(){
    newpwd = $.md5($("#L_newpass").val());
    repwd = $.md5($("#L_repass").val());
    oldpwd = $.md5($("#L_password").val());
    uid = $("#L_userid").val();
    par = oldpwd+':'+newpwd+':'+repwd+':'+uid;
    updt_ajx('Index/cgpswd',par);
}

function init_usr_cguser(){
    //$userid,$username,$tname,$email,$national,$unit,$speciality
    uid = trm($("#L_userid").val());
    ume = trm($("#L_username").val());
    tme = trm($("#L_tname").val());
    eil = trm($("#L_email").val());
    nal = trm($("#L_national").val());
    uit = trm($("#L_unit").val());
    sty = trm($("#L_speciality").val());
    rrk = trm($("#L_remark").val());
    
    par = uid+':'+ume+':'+tme+':'+eil+':'+nal+':'+uit+':'+sty+':'+rrk;
    updt_ajx('Index/cguser',par);
}

function init_url(lik,cp){
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



function cg_show(){
  
    $("#wrongsel").show();
  
}
function cg_hide(){
  $("#wrongsel").hide();
  //console.log("ddddd");
}

function init_url2(lik,cp){
    //console.log($(odj).val());
    kssj = $("#kssj").val();
    kssj_fh=$("#kssj_fh").val();
    ksgj = $("#ksgj").val();
    zwtm = $("#zwtm").val();
    zsjb = $("#zsjb").val();
    zwfs_fh = $("#zwfs_fh").val();
    zwfs = $("#zwfs").val();
    cj = $("#cjtype").val();
    par = kssj+':'+kssj_fh+':'+ksgj+':'+zwtm+':'+zsjb+':'+zwfs+':'+zwfs_fh+':'+cj;
    //console.log(par);
    page_url_dataview(lik,cp,par);
}

function get_zw_pic(zwm){
    $.ajax({
        type:"POST",
        async: false,
        url:'Index/get_zwm',        
        data:{'zwm':zwm},
        success:function(ss){
            var pic = '<div id="yl_pic">';
            pic += ss.info;
            pic += '<div id="yl_nav">'+ss.nav+'</div>';
            pic += '<img id="yl_img" src="zt/'+ss.link+'.gif" style=" min-height: 700px; width: 100%;height: 100%; display: inline-block；"/>';
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



function changehskdata(id){
     var htmlbody = "<div style='padding:10px;'>作文编码："+$('#zwm'+id).html()+"<br>属性信息："+$('#shuxinginfo'+id).html()+"<br>";
     htmlbody += '<div>'+
         ' <div class="layui-form-item layui-form-text">'+
         '   <label class="layui-form-label">语句</label>'+
         '   <div class="layui-input-block">'+
         '     <textarea id="hskchangeinfo" class="layui-textarea">'+$('#hskinfo'+id).html()+'</textarea>'+
         '   </div>'+
         ' </div>'+
         ' <div class="layui-form-item">'+
         '   <div class="layui-input-block">'+
         '     <button class="layui-btn" onclick="changehskaction(\''+id+'\')">提交更新</button>'+
         '   </div>'+
         " </div></div><div id='hskinnerid' style='display:none;'>"+id+"</div></div>";
    layer.open({
              type: 1,
              skin: 'layui-layer-lan',
              title: '修订语料',
              shadeClose: false,
              scrollbar: false,
              shade: 0.8,
              area: ['700px', '400px'], //宽高
              content: htmlbody,
            });
}


function changeorderdata(id){
  var htmlbody = "<div style='padding:10px;'>作文编码："+$('#zwm'+id).html()+"<br>属性信息："+$('#shuxinginfo'+id).html()+"<br>";
     htmlbody += '<div>'+
         ' <div class="layui-form-item layui-form-text">'+
         '   <label class="layui-form-label">语句</label>'+
         '   <div class="layui-input-block">'+
         '     <textarea id="hskchangeinfo" class="layui-textarea">'+$('#hskinfo'+id).html()+'</textarea>'+
         '   </div>'+
         ' </div>'+
         ' <div class="layui-form-item">'+
         '   <div class="layui-input-block">'+
         '     <button class="layui-btn" onclick="changehskaction(\''+id+'\')">提交更新</button>'+
         '   </div>'+
         " </div></div><div id='hskinnerid' style='display:none;'>"+id+"</div></div>";
    layer.open({
              type: 1,
              skin: 'layui-layer-lan',
              title: '修订语料',
              shadeClose: false,
              scrollbar: false,
              shade: 0.8,
              area: ['700px', '400px'], //宽高
              content: htmlbody,
            });
}
function upmyhskdata(id){
    //var htmlbody = "作文编码："+$('#zwm'+id).html()+"<br>";
     var htmlbody = "<div style='padding:10px;'>"+$('#shuxinginfo'+id).html()+"<br>";
     htmlbody += '<div>'+
         ' <div class="layui-form-item layui-form-text">'+
         '   <label class="layui-form-label">语句</label>'+
         '   <div class="layui-input-block">'+
         '     <textarea id="hskchangeinfo" class="layui-textarea">'+$('#myhskinfo'+id).html()+'</textarea>'+
         '   </div>'+
         ' </div>'+
         ' <div class="layui-form-item">'+
         '   <div class="layui-input-block">'+
         '     <button class="layui-btn" onclick="changemyhskaction(\''+id+'\')">提交更新</button>'+
         '   </div>'+
         " </div></div><div id='myinnerid' style='display:none;'>"+id+"</div></div>";
    layer.open({
              type: 1,
              //skin: 'layui-layer-rim', //加上边框
              skin: 'layui-layer-lan',
              title: '修订语料',
              shadeClose: false,
              scrollbar: false,
              shade: 0.8,
              area: ['700px', '400px'], //宽高
              content: htmlbody,
            });
}
function changemyhskaction(id){
    $.ajax({
        type:"POST",
        async: false,
        url:'Index/myhskupdate',        
        data:{'innerid':id,'keykh2':$('#hskchangeinfo').val()},
        success:function(msg){   
            if(msg > 0){
                layer.alert('提交成功！');
            }
            else{
                layer.alert('出错了，请重新提交！');
            }            
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.alert('系统异常.');
        }
    });
}
function changehskaction(id){

   // $("#msg").html('提交成功！');


    $.ajax({
        type:"POST",
        async: false,
        url:'Index/myhsk',        
        data:{'innerid':id,'zwm':$('#zwm'+id).html(),'keykh2':$('#hskchangeinfo').val()},
        success:function(msg){   
            if(msg > 0){
                layer.alert('提交成功！');
                //$("#msg").html('提交成功！');
            }
            else{
                layer.alert('出错了，请重新提交！');
                //$("#msg").html('出错了，请重新提交！');
            }            
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          layer.alert('系统异常.');
          //$("#msg").html('系统异常.');
        }
    });
}
function get_zw_bzb(zwm){
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

        
    // $.ajax({
    //     type:"POST",
    //     async: false,
    //     url:'Index/get_zwm_txt',        
    //     data:{'zwm':zwm},
    //     success:function(ss_txt){   
    //         var pic = '<div id="yl_txt">';
    //         pic += ss_txt.info;
    //         pic += '</div>';
    //         pic += '<pre  id="text_body">'+ss_txt.txt+'</pre>';
    //         layer.open({
    //           type: 1,
    //           skin: 'layui-layer-rim', //加上边框
    //           title: '标注版语料 [语料编号:'+zwm+']',
    //           shadeClose: true,
    //           scrollbar: false,
    //           shade: 0.8,
    //           area: ['700px', '90%'], //宽高
    //           content: pic
    //         });
    //     },
    //     error:function(XMLHttpRequest, textStatus, thrownError){
    //       layer.alert('没有标注数据.');
    //     }
    // });
       
}

function setimg(str){
  $("#yl_img").attr('src','zt/'+str+'.gif');
}

function hintsx(){
  if($('.zwsx_bzb').is(':hidden')){
    $(".zwsx_bzb").show(500);
  }else{
    $(".zwsx_bzb").hide(500);
  }
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

function fcAction() {
    dd = $("#sour_text").val();
    $.ajax({
        type:"POST",
        url:"index/fctools",     
        data:{'txt':dd},
        success:function(data){
          $("#fc_text").val(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#fc_text").val("分词工具异常！");
        }
    });
  }

  function searchkeys(){
    var theEvent = window.event || arguments.callee.caller.arguments[0]; //谷歌能识别event，火狐识别不了，所以增加了这一句，chrome浏览器可以直接支持event.keyCode
        var code = theEvent.keyCode;
        if(code == 13){
           selecthskclick(); 
        }
  }

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


  function fy(q,id){
  var appid = '20160724000025683';
  var key = 'Ap_3pXAMIZ67M9PcuG3r';
  var salt = (new Date).getTime();
  var query = q;
  // 多个query可以用\n连接  如 query='apple\norange\nbanana\npear'
  var from = 'zh';
  var to = 'en';
  var str1 = appid + query + salt +key;
  var sign = MD5(str1);
  $.ajax({
      url: 'http://api.fanyi.baidu.com/api/trans/vip/translate',
      type: 'get',
      dataType: 'jsonp',
      data: {
          q: query,
          appid: appid,
          salt: salt,
          from: from,
          to: to,
          sign: sign
      },
      success: function (data) {
          //console.log(data);
          $("#"+id).html("原文："+q+"<br>译文："+data.trans_result[0].dst);
          console.log(data.trans_result[0].dst);
      } 
  });
}


