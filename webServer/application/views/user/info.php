<h2><?=$this->dataInfo->field_list['name']->gen_show_html()?></h2>
<table class="table table-striped">
	<tr>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_name()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_html()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_name()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_html()?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_name()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_html()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_name()?>
        </td>
        <td>
            <?=$this->dataInfo->field_list['phone']->gen_show_html()?>
        </td>
    </tr>
</table>
<hr>
<h3>Ta 的文章</h3>
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
