<div class="modal-body">
	<ul class="nav nav-tabs" id="nav-perInfo">
	    <li <?=($this->method_name=="perInfo")?'class="active"':''?>><a href="javascript:void(0)" onclick="lightbox({size:'m',url:'<?=site_url('index/perInfo')?>'})">个人信息</a></li>
	    <li <?=($this->method_name=="real")?'class="active"':''?>><a href="javascript:void(0)" onclick="lightbox({size:'m',url:'<?=site_url('index/real')?>'})">实名认证</a></li>
	    <li <?=($this->method_name=="changePwd")?'class="active"':''?>><a href="javascript:void(0)" onclick="lightbox({size:'m',url:'<?=site_url('index/changePwd')?>'})">修改密码</a></li>
	</ul>         