<div class="blog-post">
  <h2 class="blog-post-title"><?=$this->dataInfo->field_list['title']->gen_show_html()?></h2>
<p class="blog-post-meta"><?=$this->dataInfo->field_list['postTS']->gen_show_html()?> by <a href="<?=site_url('contact/info/'.$this->dataInfo->field_list['postUser']->value)?>"><?=$this->dataInfo->field_list['postUser']->gen_show_html()?></a></p>
<hr/>
<?=$this->dataInfo->field_list['content']->gen_show_html()?>
<?
var_dump($this->dataInfo->data);
?>
</div>