
function bbs_url(lik){

    $.ajax({
        type:"POST",
        async: false,
        url:"./Index/bbs",
        data: {'id':$(lik).eq(0).attr('id')},
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
  // $("[title='bbshead']").click(function(){
  //   $("body_content").html("load failed.");
  //   bbs_url(this);
    
  // });

  $("[data-my='gogo']").click(function(){
    $("body_content").html("load failed.");
    bbs_url(this);
    
  });

  //bbshead
 // $("[title='bbshead']").click(function(){
   
 //    ids = $(this).eq(0).attr('id');
 //    ids = 'bbs_'+ids;
 //    $(ids).slideToggle("slow");
 //  });
});