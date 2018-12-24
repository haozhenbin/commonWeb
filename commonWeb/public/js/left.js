//鼠标移动
function mouseMv(ss){
    $(".btn.btn-success.btn-sm.dv").removeClass("btn-success").addClass("btn-info");
    $(".btn.btn-info.btn-sm.dv.active").removeClass("btn-info").addClass("btn-defualt");
    $(ss).removeClass("btn-info").addClass("btn-success");
}

function mouseClk(ss){
    $(".btn.btn-defualt.btn-sm.dv.active").removeClass("btn-defualt active").addClass("btn-info");

    $(ss).removeClass("btn-info").addClass("btn-defualt active");
}

function chg_url(lik){

    $.ajax({
        type:"POST",
        async: false,
        url:"./Nav",
        data: "nav="+$(lik).eq(0).attr('id'),
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#body_content").html("load failed.");
        }
    });

}

function bbs_url(lik){

    $.ajax({
        type:"POST",
        async: false,
        url:"./Nav",
        data: {'nav':'bbs','id':$(lik).eq(0).attr('id')},
        success:function(data){
            $("#body_content").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#body_content").html("load failed.");
        }
    });

}
//入口函数
$(function() {
  //target="gogo"title='gogo'
  $("[title='gogo']").click(function(){
    $("#body_content").html("load failed.");
    bbs_url(this);
  });
  

  $(":button").click(function(){
    mouseClk(this);
    chg_url(this);

  });

  $(":button").mousemove(function(){
    mouseMv(this);
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

 $.ajax({
        type:"POST",
        async: false,
        url:"./Left",
        data: "sid=right",
        success:function(data){
            $("#right_side").html(data);
        },
        error:function(XMLHttpRequest, textStatus, thrownError){
          $("#right_side").html("load failed.");
        }
    });
  


});