<?php include_once('perInfoHead.php')?>
<div class="info-perInfo row" id="info-perInfo-mini_info">
    <div class="col-lg-12">
        <form class="form-horizontal" role="form" id="changePwdForm">
        	<div class="form-group" id="uPasswordGroup">
                <label for="uPassword" class="col-sm-3 control-label">原密码</label>
                <div class="col-sm-9">
                    <input type="password" minlength="6" class="form-control" id="uPassword" placeholder="密码6位以上" name="uPassword" required>
                </div>
            </div>
            <div class="form-group" id="uPasswordGroup">
                <label for="uPassword" class="col-sm-3 control-label">新密码</label>
                <div class="col-sm-9">
                    <input type="password" minlength="6" class="form-control" id="uPasswordNew" placeholder="密码6位以上" name="uPasswordNew" required>
                </div>
            </div>
            <div class="form-group" id="uPasswordAgainGroup">
                <label for="uPasswordAgain" class="col-sm-3 control-label">确认新密码</label>
                <div class="col-sm-9">
                    <input type="password" minlength="6" class="form-control" id="uPasswordAgain" placeholder="再次确认密码" name="uPasswordAgain" required equalTo="#uPasswordNew">
                </div>
            </div>
            <div class="text-center">
            	<a href="javascript:void(0);" onclick="req_reg()" class="btn btn-primary">修 改</a>
            </div>
    </form>
    </div>
</div>
<?php include_once('perInfoFoot.php')?>
<script>
var validator = $("#changePwdForm").validate();
function req_reg(){
    if (validator.form()==false) {
        return;
    };
    var uPassword = $("#uPassword").val();
    var uPasswordNew = $("#uPasswordNew").val();

    ajax_post({m:'index',a:'doChangePwd',data:{uPassword:uPassword,uPasswordNew:uPasswordNew},callback:function(json){
            if (json.rstno>0){
                alert(json.data.succMsg);
                lightbox_close();
            } else {
                var showErr = {};
                showErr[json.data.err.id] = json.data.err.msg ;
                validator.showErrors(showErr);
            }
        }
  });
}
</script>