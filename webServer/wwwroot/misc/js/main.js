var g_uuid = '';
$(function() {
	g_uuid = $.uuid();
});

function zan_blog(id){
	var good_count = parseInt($("#blog-"+id+" .good_count").html());
    good_count = good_count+1;
    $("#blog-"+id+" good_count").html(good_count);
    ajax_get({m:'blog',a:'doZan',id:id,callback:function(json){
        if (json.rstno==1) {
            window.location.href=json.data.goto_url;
        } else {
            alert(json.data.error);
        }
    }});
}

function sendComments(id){
    var comments_count = parseInt($("#blog-"+id+" .comment_count").html());
    comments_count = comments_count+1;
    $("#blog-"+id+" comment_count").html(comments_count);

    var content = $("#commentInput").val();
    if (content.trim()==""){
        alert('评论不可为空');
        return;
    }
    ajax_post({m:'blog',a:'doComment',id:id,data:{comment:content,blogId:id},callback:function(json){
        if (json.rstno==1) {
            window.location.href=json.data.goto_url;
        } else {
            alert(json.data.error);
        }
    }});
}
