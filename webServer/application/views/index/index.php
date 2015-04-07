<?php
$i = 1;
foreach($this->listInfo->record_list as  $this_record): 
?>
    <div class="blog-post" id="blog-<?=$this_record->field_list['_id']->toString()?>">
        <h2 class="blog-post-title"><?=$this_record->field_list['title']->gen_show_html();?></h2>
        <p class="blog-post-meta"><?=$this_record->field_list['postTS']->gen_show_html();?> by 
            <a href="<?=site_url('contact/info/'.$this_record->field_list['postUser']->value)?>"><?=$this_record->field_list['postUser']->gen_show_html()?></a>
        </p>

        <?=$this_record->field_list['content']->gen_show_html()?>
        <div class="clear"></div>
        <div class="pull-right">
            <a href="<?=site_url('blog/info/'.$this_record->field_list['_id']->toString())?>" class="btn btn-sm btn-default"><span class='glyphicon glyphicon-book'></span> </a>
            <a href="#" onclick="zan_blog('<?=$this_record->field_list['_id']->toString()?>')" class="btn btn-sm btn-default">
                <span class='glyphicon glyphicon-thumbs-up'></span> <span class="good_count"><?=$this_record->field_list['goodCount']->gen_show_value()?></span>
            </a>
            <a href="<?=site_url('blog/info/'.$this_record->field_list['_id']->toString())?>" class="btn btn-sm btn-default">
                <span class='glyphicon glyphicon-comment'></span> <span class="good_count"><?=$this_record->field_list['commentCount']->gen_show_value()?></span>
            </a>
        </div>
        <br/>
        <div class="clear"></div>
    </div>
    <hr/>
<?php $i++;
endforeach; ?>
<nav class="center-block">
    <?php echo $this->pagination->create_links(); ?>
</nav>