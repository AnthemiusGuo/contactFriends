<!-- Modal -->
        <div class="modal-header logo-small">
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->title_create ?></h4>
        </div>
        <div class="modal-body">
            <form role="form" id="createForm">
                <?php echo $contents; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="$.fancybox.close();">取消</button>
            <button type="button" class="btn btn-primary" onclick="reqCreate('<?=$this->createUrlC?>','<?=$this->createUrlF?>',reqCreateFields,createFormValidator)">保存</button>
        </div>
<script>
var createFormValidator = $("#createForm").validate();
var reqCreateFields = [];
<?php
foreach ($this->createPostFields as $key => $value) {
    echo 'reqCreateFields.push({name:"'.$value.'",type:"'.$this->dataInfo->field_list[$value]->typ.'"});';
}
?>
</script>