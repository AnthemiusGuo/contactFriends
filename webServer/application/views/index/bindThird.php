<!-- BEGIN LOGIN FORM -->
<div class="form-group">

    <div class="center-block text-center">欢迎来自<?=$this->third_plat_name?>的用户<br/><img src="<?=$this->third_user_info['head_img']?>" alt="..." class="img-circle"><br/><?=$this->third_user_info['name']?></div>
    <input type="hidden" id="third_plat" name="third_plat" value="<?=$this->third_plat?>">
    <input type="hidden" id="third_id" name="third_id" value="<?=$this->third_id?>">
</div>
<form id="regForm" class="reg-form" action="<?=site_url("index/doBindNew")?>" method="post">
    <h3 class="form-title">绑定新帐号</h3>
    <div>为何要绑定？手机上用微信绑定相同帐号可以通用哦</div>
    <div class="form-group">


        <label class="control-label visible-ie8 visible-ie9">登录手机号</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-phone"></span>
            <input class="form-control placeholder-no-fix" type="text" placeholder="登录手机号" id="regPhone" name="regPhone"  required="required" digits="digits">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">姓名</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-user"></span>
            <input class="form-control placeholder-no-fix" type="text" required="required" placeholder="姓名" id="regName"  name="regName" value="<?=$this->third_user_info['name']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">密码</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-lock"></span>
            <input class="form-control placeholder-no-fix" type="password" required="required"  id="regPassword" placeholder="密码，6位以上字符或数字" minlength=6 name="regPassword">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">重输密码</label>
        <div class="controls">
            <div class="input-icon">
                <span class="glyphicon glyphicon-lock"></span>
                <input class="form-control placeholder-no-fix" type="password" required="required"  placeholder="重输密码" id="regPassword2"  minlength=6 name="regPassword2">
            </div>
        </div>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" id="regAgree" name="regAgree" required checked="checked">同意<a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/license') ?>',size:'m'})">网站注册协议</a>
        </label>
    </div>
    <div class="form-group">
        <button type="button" id="register-submit-btn" class="btn green-haze pull-right" onclick="req_bind_new()">
        绑定<span class="glyphicon glyphicon-ok"></span>
        </button>
    </div>
</form>


    <div class="clearfix"></div>
    <hr/>
    <form id="loginForm" class="reg-form" action="<?=site_url("index/doBindOld")?>" method="post">

    <h3 class="form-title">绑定已有帐号</h3>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">登录手机号</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="text" placeholder="登录手机号" id="loginPhone" name="loginPhone">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">密码</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="password" placeholder="密码" id="loginPassword" name="loginPassword">
        </div>
    </div>
    <div class="form-group">
            <button type="button" class="btn green-haze pull-right" onclick="req_bind_old()">
            绑定 <span class="glyphicon glyphicon-ok"></span>
            </button>
    </div>
    <div class="clearfix"></div>
    <hr/>
</form>
<!-- END LOGIN FORM -->
<script>
var new_validator = $("#regForm").validate();
var old_validator = $("#loginForm").validate();
function req_bind_old(){
    $("#loginForm .form-group").removeClass('has-error');
    if (old_validator.form()==false) {
        return;
    };
    ajax_post({m:'index',a:'doBindOld',data:{uPhone:$("#loginPhone").val(),uPassword:$("#loginPassword").val(),third_plat:$("#third_plat").val(),third_id:$("#third_id").val()},callback:function(json){
            if (json.rstno>0){
                window.location.href=json.data.goto_url;
            } else {
                var showErr = {};
                showErr[json.data.err.id] = json.data.err.msg ;
                old_validator.showErrors(showErr);
            }
        }
  });
}
function req_bind_new(){
    var uAgree = $("#regAgree").prop('checked');
    if (uAgree==false){
        var showErr = {};
        showErr['regAgree'] = "请阅读并同意网站协议" ;
        new_validator.showErrors(showErr);
        return;
    }
    if ($("#regPassword").val()!==$("#regPassword2").val()){
        var showErr = {};
        showErr['regPassword2'] = "两次输入的密码不一致" ;
        new_validator.showErrors(showErr);
        return;
    }
    $("#regForm .form-group").removeClass('has-error');
    if (new_validator.form()==false) {
        return;
    };
    ajax_post({m:'index',a:'doBindNew',data:{uPhone:$("#regPhone").val(),uPassword:$("#regPassword").val(),uName:$("#regName").val(),third_plat:$("#third_plat").val(),third_id:$("#third_id").val()},callback:function(json){
            if (json.rstno>0){
                window.location.href=json.data.goto_url;
            } else {
                var showErr = {};
                showErr[json.data.err.id] = json.data.err.msg ;
                new_validator.showErrors(showErr);
            }
        }
  });
}
</script>
