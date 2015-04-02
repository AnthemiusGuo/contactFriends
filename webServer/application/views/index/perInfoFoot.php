</div>
<div class="modal-footer">
	<?php
	if ($this->method_name!="changePwd"):
	?>
    <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="lightbox({size:'m',url:'<?=site_url($this->controller_name.'/'.$this->method_name.'Edit')?>'})"><span class="glyphicon glyphicon-edit"></span>编辑</a>
    <?php
    endif
    ?>
</div>