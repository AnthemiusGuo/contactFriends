<div class="row login-bg">
    <div class="col-md-8">
        <div class="jumbotron" style="margin-left:20px;">
        <div class="alert <?=($this->result)?'alert-success':'alert-danger';?>"><?=$this->msg?></div>
            
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">登录</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="loginForm">
                        <div class="form-group" id="uEmailGroup">
                            <label for="uEmail" class="col-sm-3 control-label">登录邮箱</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="uEmail" name="uEmail" placeholder="邮箱" required value="<?php echo $this->loginname?>">
                            </div>
                        </div>
                        <div class="form-group" id="uPasswordGroup">
                            <label for="uPassword" class="col-sm-3 control-label">密码</label>
                            <div class="col-sm-9">
                                <input type="password" minlength="6" class="form-control" id="uPassword" placeholder="密码" name="uPassword" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="uRememberMe">记住我
                                    </label>
                              </div>
                            </div>
                        </div>
                        <div class="text-center">

                        <!-- <a href="<?php echo site_url('project/index') ?>" class="btn btn-primary">登   录</a> -->
                        <a href="javascript:void(0);" onclick="req_login()" class="btn btn-primary">登   录</a>
                        </div>
                        <a href="<?php echo site_url('index/reg') ?>">注   册</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/forgot') ?>',size:'m'})">忘记密码</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
var validator = $("#loginForm").validate();
function req_login(){
    var uEmail = $("#uEmail").val();
    var uPassword = $("#uPassword").val();
    var uRememberMe = $("#uRememberMe").prop('checked');
    $("#loginForm .form-group").removeClass('has-error');
    if (validator.form()==false) {
        return;
    };
    ajax_post({m:'index',a:'doLogin',data:{uEmail:uEmail,uPassword:uPassword,uRememberMe:uRememberMe},callback:function(json){
            if (json.rstno>0){
                window.location.href=json.data.goto_url; 
            } else {
                var showErr = {};
                showErr[json.data.err.id] = json.data.err.msg ;
                validator.showErrors(showErr);
            }
        }
  });
}
</script>