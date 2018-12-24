$(function(){
                var verifyURL = "./Login/verify_img";//"{:U('/Login/verify_img','','')}"; = "{:U("/Login/verify_img")}'}"
                $('#verify_code').attr({"src": verifyURL });
                // $('#verify_code').click(function () {
                //     $(this).attr({"src": verifyURL });
                // });

                $("#exec_submit").click(function(){

                    admin_name=$("#input_admin_name").val();
                    admin_pwd=$("#input_admin_pwd").val();
                    admin_verify=$("#input_admin_verify").val();
                    //console.log(admin_name + '---'+ admin_pwd + '---'+ admin_verify);
                    $.ajax({
                        url:"./Login/login",
                        data:"admin_name="+admin_name+"&admin_pwd="+admin_pwd+"&admin_verify="+admin_verify,
                        dataType:"json",
                        type:"post",
                        success:function(json){
                            //console.log(json);
                            if(json.ok === 1){
                                location.href="/hsk";
                            }else{
                                list = ["admin_name","admin_pwd","admin_verify"];
                                for(key in list){
                                    if(typeof(json[list[key]]) !== 'undefined'){
                                        $("#"+list[key]+"_error").empty();
                                        $("#"+list[key]+"_error").parent().removeClass("has-success");
                                        $("#"+list[key]+"_error").parent().addClass("has-error");
                                        $("#"+list[key]+"_error").append(
                                            "<font style='color:red;font-weight:bold;'><span class='glyphicon glyphicon-remove'></span> "+json[list[key]]+"</font>"
                                        );
                                        $('#verify_code').click();
                                    }else{
                                        $("#"+list[key]+"_error").empty();
                                        $("#"+list[key]+"_error").parent().removeClass("has-error");
                                        $("#"+list[key]+"_error").parent().addClass("has-success");
                                        $("#"+list[key]+"_error").append(
                                            "<font style='color:green;font-weight:bold;'><span class='glyphicon glyphicon-ok'></span></font>"
                                        );
                                    }
                                }
                            }
                        }
                    });
                });
});



function img(){
    //var verifyURL = "./Login/verify_img";//"{:U('/Login/verify_img','','')}"; = "{:U("/Login/verify_img")}'}"
    var time = new Date().getTime();
    var verifyURL = "./Login/verify_img/"+time;
    //$.get(verifyURL);
    $('#verify_code').attr({"src": verifyURL });
    console.log('hhh!');
}


function keyLogin(){

    var theEvent = window.event || arguments.callee.caller.arguments[0]; //谷歌能识别event，火狐识别不了，所以增加了这一句，chrome浏览器可以直接支持event.keyCode
    var code = theEvent.keyCode;
    if(code == 13){
       $("#exec_submit").click();
    }
}