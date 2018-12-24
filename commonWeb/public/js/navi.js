$(function(){   
// $.messager.show({
//                 title:'My Title',
//                 msg:'Message will be closed after 5 seconds.',
//                 timeout:5000,
//                 showType:'slide'
//             });
	$("#loginbutton").on("click",function(){
		//$('#login-window').css("display","");
		// aaa();
  //     bbb();
		$('#login-window').window('open');
	});
    
    $('#logout').on('click',  function () {
      //注销session

      $.post("/tras/url.php/sys/sys_index/logOut",function(){
      	window.location.reload();
      });
      //退出登录页面
     
    });

    $('#myinfo').on('click',  function () {
      	//var url = "./url.php/sys/sys_index/myinfo";
      	 var url = "/tras/url.php/sys/sys_index/info";
    	 window.open(url,'个人信息', 'top=0, left=0, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no'); 

    });


});




$(function(){

	// $.getScript("/tras/view/scripts/jquery.easyui.min.js")
 //    .done(function() {
 //        console.log("succuse!");
	// });


});
function clc(str){
	var url = "./url.php/sys/sys_index/"+str;
  	$("#content").load(url,null); 
  	
}


// function openwd(){
// 	$('#login-window').css("display","");
// 	$('#login-window').window('open');
// 	//alter("safsa");
// }

function login(){
	var uid = $("#uid").val();//.textbox('getValue');

	var pwd = $("#pwd").val();//.textbox('getValue');
	$.post("/tras/url.php/sys/sys_index/login_check",
		{userid:uid,passwd:pwd},
		function(data){
	    	if($.trim(data)=="s")
	    	{   //登录成功
	    		//console.log(data);
	    		$('#login-window').window('close');
	    		window.location.reload();
	    	}else if($.trim(data)=='l')
	    	{
	    		//console.log("times out.");
	    		alert("times out.");
	    		$('#login-window').window('close');
	    		//尝试次数超限
	    	}else{
	    		// console.log(data);
	    		// console.log("passwd error.");
	    		alert("passwd error.");
	    		$('#login-window').window('close');
	    	}
  		}
  );

}

// function aaa(){
// 	console.log("eheheh!");
// }

