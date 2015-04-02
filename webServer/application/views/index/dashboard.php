<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<?php
include_once("dashboardHelper.php");
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-star"></span>
                    来电快捷输入
                </div>
            </div>
            <div class="portlet-body">
                <form role="form" action="<?=site_url("index/doLogin")?>" method="post">

                    <h3 class="form-title">请输入来电号码</h3>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">来电号码</label>
                        <div class="input-icon">
                            <span class="glyphicon glyphicon-ok-circle"></span>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="来电号码" id="dashPhoneSearch" name="dashPhoneSearch">
                        </div>
                    </div>

                    <div class="form-group">

                            <button type="button" class="btn green-meadow pull-right" onclick="phoneSearch('dashPhoneSearch')">
                            查询 <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </div>
                    <div class="clear"></div>
                    <br/>
                    <br/>
                    <br/>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    您的个人信息
                </div>
                <div class="tools">
                    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('user/editUserInfo/')?>'})"><span class="glyphicon glyphicon-edit"></span> 编辑</a>

                </div>
            </div>
            <div class="portlet-body">
                <?=$this->userInfo->buildShowCard()?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    您的商户信息
                </div>
            </div>
            <div class="portlet-body">
                <?=$this->myOrgInfo->buildShowCardAdmin()?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box green-haze">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-list-alt"></span>订单管理
                </div>
                <div class="tools">
                    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('crm/order')?>'})"><span class="glyphicon glyphicon-list"></span> 详情</a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#overview_1" data-toggle="tab">
                                未发货订单</a>
                        </li>
                        <li>
                            <a href="#overview_2" data-toggle="tab">
                                最近订单</a>
                        </li>
                        <li>
                            <a href="#overview_3" data-toggle="tab">
                                最近发货记录</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview_1">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->bookNoSendList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->bookNoSendList->dataModel[$key_names]->gen_show_name();;
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
                                        foreach($this->bookNoSendList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->bookNoSendList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->bookNoSendList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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

                                            </tr>
                                        <?php $i++;
                                        endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_2">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->bookList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->bookList->dataModel[$key_names]->gen_show_name();;
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
                                        foreach($this->bookList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->bookList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->bookList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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

                                            </tr>
                                        <?php $i++;
                                        endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_3">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->sendList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->sendList->dataModel[$key_names]->gen_show_name();;
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
                                        foreach($this->sendList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->sendList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->sendList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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

                                            </tr>
                                        <?php $i++;
                                        endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
