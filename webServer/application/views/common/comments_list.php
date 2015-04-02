<div class="col-lg-12">
    <table class="table table-striped simplePagerContainer">
        <tbody class="table-paged">
            <?php 
            $i = 1;
            foreach($this->listInfo->record_list as  $this_record): ?>
                <tr>
                    <td class="td_title">
                        <?=$this_record->field_list["uploadPeapleId"]->gen_show_html();?>
                    </td>
                    <td>
                        发表于<?=$this_record->field_list["uploadZeit"]->gen_show_html();?>
                    </td>
                    <td>

                        <?php 
                        if ($this_record->field_list["uploadPeapleId"]->value==$this->peapleId || $this->roleId==99) {
                            echo $this_record->gen_list_op();
                        }


                        ?>
                    </td>
                </tr> 
                <tr>
                    <td class="td_title">     
                        <?=($this_record->field_list["isUserDefineField"]->value==1)?$this_record->field_list["fieldName"]->gen_show_html().' (自定义字段) ':'评论 ';?>:
                    </td>
                    <td colspan="2">
                        <?=$this_record->field_list["cdesc"]->gen_show_html();?>
                    </td>
                </tr>        
            <?php $i++;
            endforeach; ?>
            
        </tbody>
    </table>

    <div id="main_pager">

    </div>
    <?php
    if (count($this->listInfo->record_list)==0):
    ?>
        <div class="no-data-large">
            没有相关评论
        </div>
    <?
    endif;
    ?>

</div>
<div class="col-lg-12">
    <h4>新建评论</h4>
    <form role="form" id="createForm">
            
    <?php echo $this->dataInfo->field_list['crelateTyp']->gen_hidden_editor($this->editor_typ,$this->relateTyp) ?>
    <?php echo $this->dataInfo->field_list['crelateID']->gen_hidden_editor($this->editor_typ,$this->relateId) ?>
    <table class="table">
        <tr>
            <td class="td_title"><?php echo $this->dataInfo->field_list['isUserDefineField']->gen_editor_show_name(); ?></td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['isUserDefineField']->gen_editor($this->editor_typ) ?>    
            </td>
            <td class="td_title"><?php echo $this->dataInfo->field_list['fieldName']->gen_editor_show_name(); ?></td>
            <td class="td_data">
                    <?php echo $this->dataInfo->field_list['fieldName']->gen_editor($this->editor_typ) ?>    
            </td>
        </tr>
        <tr>
            <td class="td_title"><?php echo $this->dataInfo->field_list['cdesc']->gen_editor_show_name(); ?></td>
            <td colspan="3">
                    <?php echo $this->dataInfo->field_list['cdesc']->gen_editor($this->editor_typ) ?>    
            </td>
        </tr>
    </table>
    </form>
    <button type="button" class="btn btn-primary" onclick="reqCreate('<?=$this->createUrlC?>','<?=$this->createUrlF?>',commentsReqCreateFields,createFormValidator)">保存</button>
</div>
<script>
var createFormValidator = $("#createForm").validate();
var commentsReqCreateFields = [];
<?php
foreach ($this->createPostFields as $key => $value) {
    echo 'commentsReqCreateFields.push({name:"'.$value.'",type:"'.$this->dataInfo->field_list[$value]->typ.'"});';
}
?>
</script>