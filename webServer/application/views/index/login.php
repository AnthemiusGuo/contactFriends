<div class="row">
    <div class="col-sm-6">       
        <form id="loginForm" class="login-form" action="<?=site_url("index/doLogin")?>" method="post">
        <h3 class="form-title">登录</h3>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">登录手机号</label>
            <div class="input-icon">
                <span class="glyphicon glyphicon-edit"></span>
                <input class="form-control placeholder-no-fix" type="text" placeholder="登录手机号" id="uPhone" name="uPhone">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">密码</label>
            <div class="input-icon">
                <span class="glyphicon glyphicon-edit"></span>
                <input class="form-control placeholder-no-fix" type="password" placeholder="密码" id="uPassword" name="uPassword">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="uRememberMe">记住我
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <button type="button" class="btn green-haze pull-right" onclick="req_login()">
                登录 <span class="glyphicon glyphicon-ok"></span>
                </button>
            </div>
        </div>
        </form>
    </div>
    <div class="col-sm-6">
        <div class="forget-password">
            <h4>忘记密码？</h4>
            <p>
                <a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/forgot') ?>',size:'m'})">点击这里</a>
                重置密码
            </p>
        </div>
        <hr/>
        <div class="create-account">
            <h4>还没有帐号？</h4>
            <p>
                <a href="<?php echo site_url('index/reg') ?>">点击注册</a>注册帐号，或使用上面QQ等方式登录
            </p>
        </div>
    </div>
</div>    

<!-- END LOGIN FORM -->
<script>
var validator = $("#loginForm").validate();
function req_login(){
    var uPhone = $("#uPhone").val();
    var uPassword = $("#uPassword").val();
    var uRememberMe = $("#uRememberMe").prop('checked');
    $("#loginForm .form-group").removeClass('has-error');
    if (validator.form()==false) {
        return;
    };
    ajax_post({m:'index',a:'doLogin',data:{uPhone:uPhone,uPassword:uPassword,uRememberMe:uRememberMe},callback:function(json){
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
