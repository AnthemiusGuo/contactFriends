<?php
include_once(APPPATH."views/common/bread.php");
?>
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
        <table class="table simplePagerContainer" >
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
                                if ($key_names=="name" || $key_names=="showId"):
                                
                                    echo '<a href="'. site_url($this->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                else :                         
                                    echo $this_record->field_list[$key_names]->gen_list_html();

                                endif;
                                ?>
                            </td>
                        <?
                        endforeach;
                        ?>
                        
                    </tr>        
                <?php $i++;
                endforeach; ?>                
                
            </tbody>
        </table>

        <div id="main_pager">

        </div>
    </div>
</div>
