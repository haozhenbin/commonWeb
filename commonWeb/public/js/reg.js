$(function(){
   var verifyURL = "../Login/verify_img";
   //$('#verify_code').attr({"src": verifyURL });
    $('#verify_code').click(function () {
                    var time = new Date().getTime();
                    $(this).attr({"src": verifyURL + "/" + time});
                });

    function req(ss,err){
        $("#"+ss+"_error").empty();
        $("#"+ss+"_error").parent().removeClass("has-success");
        $("#"+ss+"_error").parent().addClass("has-error");
        $("#"+ss+"_error").append("<font style='color:red;font-weight:bold;'><span class='glyphicon glyphicon-remove'></span> "+err+"</font>");
    }
    function no_req(ss){
        $("#"+ss+"_error").empty();
        $("#"+ss+"_error").parent().removeClass("has-error");
        $("#"+ss+"_error").parent().addClass("has-success");
        $("#"+ss+"_error").append("<font style='color:green;font-weight:bold;'><span class='glyphicon glyphicon-ok'></span></font>");
    }

    $("#exec_submit").click(function(){
        unm = $("#username").val().trim();
        eml = $("#email").val().trim();
        pwd = $("#password").val().trim();
        rpwd = $("#repassword").val().trim();
        gj = $("#national").val().trim();
        hy = $("#speciality").val().trim();
        tag = 1;
        if(unm==''){ req('username','用户名不能为空'); tag = 0 ; }else{ no_req('username') ;  }
        if(eml==''){ req('email','Email地址不能为空');  tag = 0 ;}else{ no_req('email') ;}
        if(gj==''){ req('national','国籍不能为空');  tag = 0 ;}else{ no_req('national') ;}
        if(hy==''){ req('speciality','从事行业不能为空');  tag = 0 ;}else{ no_req('speciality') ;}
        if(pwd==''){ req('password','密码不能为空');  tag = 0 ;}else{ 
            if(pwd.length<6){ req('password','密码长度至少为6位');  tag = 0 ;}else{no_req('password') ;}
        }
        if(rpwd==''){ req('repassword','确认密码不能为空');  tag = 0 ;}else{ 
            if(rpwd!=pwd){ req('repassword','两次输入的密码不一致');  tag = 0 ;}else{ no_req('repassword') ;}
        }
        //console.log('sdfasf'+$('#reg_form').serialize());
        if(tag == 1){
            $.ajax({
            url:"./insert",
            type:"POST",
            data: $('#reg_form').serialize(),
            success:function(json){
                if(json.ok == 1){
                    if(confirm("已成功注册，注册用户名："+unm + "。 确认后跳转到登录页面。")){
                    　　location.href="/hsk/Login";
                    }
                    
                }else{
                    if(typeof(json['username']) !== 'undefined'){
                        $("#username_error").empty();
                        $("#username_error").parent().removeClass("has-success");
                        $("#username_error").parent().addClass("has-error");
                        $("#username_error").append(
                            "<font style='color:red;font-weight:bold;'><span class='glyphicon glyphicon-remove'></span> "+json['username']+"</font>"
                        );
                        $('#verify_code').click();
                    }else{
                        $("#username_error").empty();
                        $("#username_error").parent().removeClass("has-error");
                        $("#username_error").parent().addClass("has-success");
                        $("#username_error").append(
                            "<font style='color:green;font-weight:bold;'><span class='glyphicon glyphicon-ok'></span></font>"
                        );
                    
                    }
                    if(typeof(json['admin_verify']) !== 'undefined'){
                        $("#admin_verify_error").empty();
                        $("#admin_verify_error").parent().removeClass("has-success");
                        $("#admin_verify_error").parent().addClass("has-error");
                        $("#admin_verify_error").append(
                            "<font style='color:red;font-weight:bold;'><span class='glyphicon glyphicon-remove'></span> "+json['admin_verify']+"</font>"
                        );
                       $('#verify_code').click();     

                    }else{
                        $("#admin_verify_error").empty();
                        $("#admin_verify_error").parent().removeClass("has-error");
                        $("#admin_verify_error").parent().addClass("has-success");
                        $("#admin_verify_error").append(
                            "<font style='color:green;font-weight:bold;'><span class='glyphicon glyphicon-ok'></span></font>"
                        );
                    
                    }
                }
            }
        });
    }
});
});