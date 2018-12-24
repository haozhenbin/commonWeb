//鼠标移动


function bbs_reback_url(){
    nr = $("#ht_uback").val().replace(/[~'!<>@#$%^&*()-+_=:]/g, "");
    if($.trim(nr)!=""){
    data = {"fatherid": parseInt($("#ubackid").text()),"uback":nr};
    $.ajax({
        type:"POST",
        async: false,
        url:"./Bbs/add",
        data: data,//.eq(0).attr('id'),
        success:function(data){
            //$("#bbs_rt").html(data);
            layer.alert(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#bbs_rt").css("color",'red').html("发布出错，请重新提交.");
        }

        });
    }else
    {
        layer.alert("回帖内容不能为空.");
    }
}

function fk_detail(fkid){
       // console.log(fkid);
    layui.use('layer', function(){
          var layer = layui.layer;
          //layer.msg('hello');
        });

       $.ajax({
        type:"POST",
        async: false,
        url:"./Index/bbs",
        data: {'id':fkid},
        success:function(data){
            layer.open({
              type: 1,
              title:'回帖信息',
              skin: 'layui-layer-rim', //加上边框
              area: ['700px', '400px'], //宽高
              content: data
            });

            //$("#body_content").html(data);


        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          //$("#body_content").html("load failed.");
          layer.alert('没有找到回帖信息。');
        }
    });
}




function bbs_func_url(sstag){
    zt = $("#ubacktitle").val().replace(/[~'!<>@#$%^&*()-+_=:]/g, "");
    nr = $("#uback").val().replace(/[~'!<>@#$%^&*()-+_=:]/g, "");
    if($.trim(zt)!="" && $.trim(nr)!=""){
    data = {"ubacktitle": zt ,"uback": nr };
    
    //console.log(sstag);
    //console.log($(sstag).serialize());
    $.ajax({
        type:"POST",
        async: false,
        url:"./Bbs/newbbs",
        data: data,
        success:function(data){
            //$("#bbs_rt").html(data);
            layer.alert(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
            $("#bbs_rt").css("color",'red').html("发布出错，请重新提交.");
        }

    });
    }else{
        layer.alert('发布主题和内容不能为空，请填写信息在发布。');
    }

}

//入口函数
$(function() {
 
  $("#bbs_bt").click(function(){
    bbs_reback_url();
  });

  $("#bbs_fb_bt").click(function(){
    bbs_func_url("fk_form");
  });  

});