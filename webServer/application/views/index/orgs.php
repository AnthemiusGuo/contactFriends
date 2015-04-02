<div>
<?php
include_once(APPPATH."views/common/bread_and_search.php");
?>
</div>
<div class="row">
    <?php
    if (isset($this->searchInfo) && $this->searchInfo['t']=="quick"):
    ?>
    <div class="col-lg-12 search_tips">
        <span class="glyphicon glyphicon-search"></span> 快捷搜索 : <?=(isset($this->quickSearchName)?$this->quickSearchName:'名称/编号');?> 包含 <?=(isset($this->quickSearchValue)?$this->quickSearchValue:'');?>;
        <a href='<?=site_url($this->controller_name.'/'.$this->method_name)?>'><span class='glyphicon glyphicon-circle-arrow-right'></span> 返回<?=$this->Menus->show_menus[$this->controller_name]['menu_array'][$this->method_name]['name']?></a>
    </div>
    <?php
    endif;
    ?>
    <?
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
                        <?
                        foreach ($this->listInfo->build_list_titles() as $key_names):
                        ?>
                            <td>
                                <?php
                                if ($this_record->field_list[$key_names]->typ=="Field_title"):
                                
                                    echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                    echo $this_record->field_list[$key_names]->gen_list_html(8);
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
                            if (!isset($this->orgList[$this_record->id])):
                                if (in_array($this_record->id, $this->has_applied)):
                            ?>
                            <a href="<?=site_url("index/myOrgs/myApplyOrg")?>" >已申请</a>
                            <?
                                else:
                            ?>
                            <a href="javascript:void(0)" class="list_op tooltips" onclick="lightbox({url:'<?=site_url('org/createApply/'.$this_record->id)?>'})" title="申请"><span class="glyphicon glyphicon-hand-up"></span></a>
                            <?
                                endif;
                            else:
                            ?>
                            <span>已加入</span>
                            <?
                            endif;
                            ?>
                        </td>
                    </tr>        
                <?php $i++;
                endforeach; ?>
                
            </tbody>
        </table>

        <div id="main_pager">

        </div>
        
    </div>
</div>
