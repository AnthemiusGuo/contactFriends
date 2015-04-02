<?php
$i = 1;
foreach($this->listInfo->record_list as  $this_record): ?>
    <div class="blog-post">
        <h2 class="blog-post-title"><?=$this_record->field_list['title']->gen_show_html();?></h2>
        <p class="blog-post-meta"><?=$this_record->field_list['postTS']->gen_show_html();?> by 
            <a href="<?=site_url('contact/info/'.$this_record->field_list['postUser']->value)?>"><?=$this_record->field_list['postUser']->gen_show_html()?></a>
        </p>

        <?=$this_record->field_list['content']->gen_show_html()?>
    
    </div>
    <hr/>
<?php $i++;
endforeach; ?>
<nav>
    <ul class="pager">
      <li><a href="#">Previous</a></li>
      <li><a href="#">Next</a></li>
    </ul>
</nav>