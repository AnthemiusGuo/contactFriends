<div class="row">
    <div class="col-lg-12">
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
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        
    </div>
</div>