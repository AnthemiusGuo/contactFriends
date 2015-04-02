<div class="row">
    <div class="col-lg-12">
        <form role="form" id="createForm">
        <table class="table">
            <?php
            foreach ($this->modifyNeedFields as $key => $value) {
            ?>
            <tr>
                <?php
                $colspan = 0;
                if (count($value)==1){
                    $colspan = 3;
                }
                foreach ($value as $k => $v) {
                    if ($v=="null") {
                ?>
                    <td class="td_title"></td><td class="td_data"></td>
                <?
                    } else {
                ?>
                    <td class="td_title"><?php echo $this->dataInfo->field_list[$v]->gen_editor_show_name(); ?></td>
                    <td <?=($colspan==0)?'class="td_data"':'colspan="'.$colspan.'"'?> >
                            <?php echo $this->dataInfo->field_list[$v]->gen_editor($this->editor_typ) ?>
                            <?php if ($this->dataInfo->field_list[$v]->tips!=''){ ?>
                            <p  class="help-block"><?=$this->dataInfo->field_list[$v]->tips?></p>
                            <?php } ?>
                    </td>
                <?
                    }
                ?>
                
                <?
                }
                ?>
            </tr>
            <?
            }
            ?>
            
        </table>
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        <?
        if ($this->editor_typ==0):
        ?>
        <button type="button" class="btn btn-primary pull-right" onclick="reqCreate('<?=$this->createUrlC?>','<?=$this->createUrlF?>',reqCreateFields,createFormValidator)">保存</button>
        <script>
        var createFormValidator = $("#createForm").validate();
        var reqCreateFields = [];
        <?php
        foreach ($this->createPostFields as $key => $value) {
            echo 'reqCreateFields.push({name:"'.$value.'",type:"'.$this->dataInfo->field_list[$value]->typ.'"});';
        }
        ?>
        </script>
        <?
        else:
        ?>

        <?
        endif;
        ?>
        <div class="clearfix"></div>
    </div>
</div>