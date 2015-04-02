<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<div class="row">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#myAttendOrg" data-toggle="tab">我参与的组织</a></li>
        <li><a href="#myApplyOrg" data-toggle="tab">我申请的组织</a></li>
        <li><a href="#includeMeOrg" data-toggle="tab">已录入我的组织</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="myAttendOrg">
            <table class="table">
                <thead>
                    <tr>
                        <?
                        foreach ($this->listAttendInfo->list_titles as $key_names):
                        ?>
                            <th>
                                <?php
                                echo $this->listAttendInfo->dataModel[$key_names]->gen_show_name();;
                                ?>
                            </th>
                        <?
                        endforeach;
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($this->listAttendInfo->record_list as  $this_record): ?>
                        <tr>
                            <?
                            foreach ($this->listAttendInfo->list_titles as $key_names):
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
                        </tr>        
                    <?php $i++;
                    endforeach; ?>
                    
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="myApplyOrg">
            <table class="table">
                <thead>
                    <tr>
                        <?
                        foreach ($this->listApplyInfo->list_titles as $key_names):
                        ?>
                            <td>
                                <?php
                                echo $this->listApplyInfo->dataModel[$key_names]->gen_show_name();;
                                ?>
                            </td>
                        <?
                        endforeach;
                        ?>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($this->listApplyInfo->record_list as  $this_record): ?>
                        <tr>
                            <?
                            foreach ($this->listApplyInfo->list_titles as $key_names):
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
                                <?php
                                if ($this_record->field_list['applyResult']->value==0):
                                ?>
                                    <a href="javascript:void(0)" class="list_op tooltips" onclick='reqOperator("org","doDeleteApply",<?=$this_record->id?>)' title="取消申请"><span class="glyphicon glyphicon-trash"></span></a>
                                <?php
                                elseif ($this_record->field_list['applyResult']->value==1):
                                ?>
                                    <a href="javascript:void(0)" class="list_op tooltips" onclick="lightbox({url:'<?=site_url('org/createApply/'.$this_record->id)?>'})" title="申请"><span class="glyphicon glyphicon-hand-up"></span></a>
                                <?php
                                endif
                                ?>
                            </td>
                        </tr>        
                    <?php $i++;
                    endforeach; ?>
                    
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="includeMeOrg">
            <table class="table">
                <thead>
                    <tr>
                        <?
                        foreach ($this->listIncludeInfo->list_titles as $key_names):
                        ?>
                            <th>
                                <?php
                                echo $this->listIncludeInfo->dataModel[$key_names]->gen_show_name();;
                                ?>
                            </th>
                        <?
                        endforeach;
                        ?>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach($this->listIncludeInfo->record_list as  $this_record): ?>
                        <tr>
                            <?
                            foreach ($this->listIncludeInfo->list_titles as $key_names):
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
                                <a href="javascript:void(0)" class="list_op tooltips" onclick="reqOperator('index','doBind',<?=$this_record->id?>);" title="关联">
                                    <span class="glyphicon glyphicon-link"></span>
                                </a>
                            </td>
                        </tr>        
                    <?php $i++;
                    endforeach; ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
