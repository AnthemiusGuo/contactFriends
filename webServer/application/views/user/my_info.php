<ul class='breadcrumb'>
    <li><a href='<?=site_url()?>'><span class='glyphicon glyphicon-home'></span> 首页</a></li>
    <li><a href='<?=site_url('contact/lists')?>'><span class='glyphicon glyphicon-list'></span> 同学录</a></li>
    <li class='active'><span class='glyphicon glyphicon-circle-arrow-right'></span> 我的信息</a></li>
</ul>
<h2><?=$this->dataInfo->field_list['name']->gen_show_html()?></h2>
<div>
    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('contact/editMy')?>'})" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-edit"></span> 编辑资料</a>
    <a href="<?=site_url('blog/write')?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-picture"></span> 发表文章</a>
</div>
<hr/>
<div>
    <blockquote>
        <?=$this->dataInfo->field_list['intro']->gen_show_html()?>
    </blockquote>
</div>
<hr/>
<table class="table table-striped">
	<tr>
        <td class="td_title">
            <?=$this->dataInfo->field_list['phone']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['phone']->gen_show_html()?>
        </td>
        <td class="td_title">
            <?=$this->dataInfo->field_list['email']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['email']->gen_show_html()?>
        </td>
    </tr>
    <tr>
        <td class="td_title">
            <?=$this->dataInfo->field_list['qq']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['qq']->gen_show_html()?>
        </td>
        <td class="td_title">
            <?=$this->dataInfo->field_list['weixin']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['weixin']->gen_show_html()?>
        </td>
    </tr>
    <tr>
        <td class="td_title">
            <?=$this->dataInfo->field_list['inviteCode']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['inviteCode']->gen_show_html()?>
        </td>
        <td class="td_title">
            <?=$this->dataInfo->field_list['weixin']->gen_show_name()?>
        </td>
        <td class="td_data">
            <?=$this->dataInfo->field_list['weixin']->gen_show_html()?>
        </td>
    </tr>
</table>
<hr>
<h3>我的文章</h3>
<table class="table table-striped simplePagerContainer">
    <tbody class="table-paged">
        <?php
        $i = 1;
        foreach($this->listInfo->record_list as  $this_record): ?>
            <tr>
                <td>
                	<a href="<?=site_url('blog/info/'.$this_record->field_list['_id']->toString());?>"><?=$this_record->field_list['title']->gen_show_html();?></a>
                </td>
                <td>
                	<?=$this_record->field_list['postTS']->gen_show_html();?>
                </td>
            </tr>
        <?php $i++;
        endforeach; ?>

    </tbody>
</table>
<?
var_dump($this->dataInfo->data);
?>
