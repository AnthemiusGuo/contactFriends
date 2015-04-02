<?php
if ($this->needInputUserInfo):
?>
	<div class="note note-warning text-left">
        <h4>您尚未完善个人资料</h4>
        <p>
        	<a href="javascript:void(0);" onclick="lightbox({size:'m',url:'<?=site_url('index/perInfo')?>'})">请点击这里</a>完善 &nbsp;<span class="glyphicon glyphicon-user"></span> 个人资料
        </p>
        
    </div>
<?
endif
?>
<?php
if ($this->isSupperUser && $this->needInputOrgInfo):
?>
	<div class="note note-warning text-left">
        <h4>您是组织超级管理员，请完善组织资料</h4>
        <p>
        	<a href="javascript:void(0);" onclick="lightbox({size:'m',url:'./?management/editOrg'})">请点击这里</a>完善 &nbsp;<span class="glyphicon glyphicon-globe"></span> 组织资料
        </p>
        
    </div>
<?
endif;
?>
<?php
if ($this->noOrgNow):
?>
	<div class="note note-danger text-left">
        <h4>您尚未加入任何组织</h4>
        <p>
        	<a href="<?=site_url('index/orgs')?>">请点击这里</a>申请加入组织
        </p>
        
    </div>
<?
endif;
?>
<?php
if (count($this->listIncludeInfo->record_list)>0):
?>
	<div class="note note-success text-left">
        <h4>以下组织已经收录您的资料</h4>
        <p>
        	<a href="<?=site_url('index/myOrgs')?>">请点击这里</a>关联帐号
        </p>
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
                                
                                    echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this->listIncludeInfo->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
<?
endif;
?>