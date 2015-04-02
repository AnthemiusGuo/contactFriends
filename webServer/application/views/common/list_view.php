<?php
include_once(APPPATH."views/common/bread_and_search.php");
?>
<?
if ($this->need_plus!=""){
    include_once(APPPATH."views/".$this->need_plus.".php");
}
?>
<div class="row">
    <?php
    if ($this->canEdit):
    ?>
    <div class="col-lg-12 list-title-op">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url($this->create_link)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a>
        <a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="reqDelete('<?=$this->deleteCtrl?>','<?=$this->deleteMethod?>',0)"><span class="glyphicon glyphicon-trash"></span> 批量删除</a>
    </div>
    <?
    endif;
    ?>
    <?php
    if (isset($this->searchInfo) && $this->searchInfo['t']=="quick"):
    ?>
    <div class="col-lg-12 search_tips">
        <span class="glyphicon glyphicon-search"></span> 快捷搜索 : <?=(isset($this->quickSearchName)?$this->quickSearchName:'名称/编号');?> 包含 <?=(isset($this->quickSearchValue)?$this->quickSearchValue:'');?>;
        <a href='<?=site_url($this->controller_name.'/'.$this->method_name)?>'><span class='glyphicon glyphicon-circle-arrow-right'></span> 返回<?=$this->Menus->show_menus[$this->controller_name]['menu_array'][$this->method_name]['name']?></a>
    </div>
    <?php
    endif;
    if (isset($this->searchInfo) && $this->searchInfo['t']=="full"):
    ?>
    <div class="col-lg-12 search_tips">
        <span class="glyphicon glyphicon-search"></span> 高级搜索 :
        <?php
        foreach ($this->searchInfo['i'] as $key => $value) {
            echo  $this->listInfo->dataModel[$key]->gen_show_name();
            echo " : ";
            echo $this->listInfo->dataModel[$key]->gen_search_result_show($value['v']);
            echo " ; ";
        };
        ?>
        <a href='<?=site_url($this->controller_name.'/'.$this->method_name)?>'><span class='glyphicon glyphicon-circle-arrow-right'></span> 返回<?=$this->Menus->show_menus[$this->controller_name]['menu_array'][$this->method_name]['name']?></a>
    </div>
    <?php
    endif;
    ?>
    <div class="col-lg-12">
        <table class="table table-striped simplePagerContainer">
            <thead>
                <tr>
                    <th><input type="checkbox" value="" id="selectAll"> 全选</th>
                    <?
                    foreach ($this->listInfo->build_list_titles() as $key_names):
                    ?>
                        <th>
                            <?php
                            echo $this->listInfo->dataModel[$key_names]->gen_show_name();;
                            ?>
                        </th>
                    <?
                    endforeach;
                    ?>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="table-paged">
                <?php
                $i = 1;
                foreach($this->listInfo->record_list as  $this_record): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="check_target[]" value="<?=$this_record->field_list['_id']->gen_list_html()?>">
                        </td>
                        <?
                        foreach ($this->listInfo->build_list_titles() as $key_names):
                        ?>
                            <td>
                                <?php
                                if ($this_record->field_list[$key_names]->is_title):
                                    if ($this->listInfo->is_lightbox):
                                        echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                    else :
                                        echo '<a href="'.site_url($this->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                    endif;
                                elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                    echo $this_record->field_list[$key_names]->gen_list_html();
                                else :
                                    echo $this_record->field_list[$key_names]->gen_list_html();

                                endif;
                                ?>
                            </td>
                        <?
                        endforeach;
                        ?>
                        <td>
                            <?
                            if ($this->listInfo->is_lightbox):
                                echo '<a class="list_op tooltips" href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})"><span class="glyphicon glyphicon-search"></span></a>';
                            else :
                                echo '<a  class="list_op tooltips" href="'.site_url($this->info_link.$this_record->id).'"><span class="glyphicon glyphicon-search"></span></a>';
                            endif;
                            ?>
                             | 
                            <?php
                            if ($this->canEdit) {
                                echo $this_record->gen_list_op();
                            }
                            ?>
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
                没有相关记录
            </div>
        <?
        endif;
        ?>
    </div>
</div>
<script>


</script>
