<!-- Modal -->
<div class="modal-header logo-small">
    <h4 class="modal-title" id="myModalLabel"><?php echo $this->infoTitle ?></h4>
</div>
<div class="modal-body">
        <?php echo $contents; ?>
</div>        
<div class="modal-footer">
    <?php
        if (isset($this->dataInfo->field_list['createUid'])):

    ?>
            <div class="note note-success text-left">
                <h5>条目编辑历史</h5>
                <dl class="editor_info">
                    <dt><?php echo $this->dataInfo->field_list['createUid']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['createUid']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['createTS']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['createTS']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_html() ?></dd>
                </dl>
                <div class="clearfix"></div>
            </div>
            <?
            endif;
            ?>
</div>
<script>
$('.tooltips').powerTip({offset:20});
<?
if (in_array($this->controller_name,array('crm','document','donation','task','schedule'))) {
?>

var comments_url = req_url_template.str_supplant({ctrller:'comments',action:'<?="index/".$this->controller_name."/".$this->id?>'});
var comment_id = "#light_comments";
$(comment_id).html('载入评论中').load(comments_url);
<?
}
?>

</script>