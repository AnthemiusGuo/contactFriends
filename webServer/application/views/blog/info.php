<ul class='breadcrumb'>
    <li><a href='<?=site_url()?>'><span class='glyphicon glyphicon-home'></span> 首页</a></li>
    <li><a href='<?=site_url()?>'><span class='glyphicon glyphicon-list'></span> 文章</a></li>
    <li class='active'><span class='glyphicon glyphicon-circle-arrow-right'></span> 查看文章</a></li>
</ul>
<div class="blog-post" id="blog-<?=$this->dataInfo->field_list['_id']->toString()?>">
    <h2 class="blog-post-title"><?=$this->dataInfo->field_list['title']->gen_show_html()?></h2>
    <p class="blog-post-meta"><?=$this->dataInfo->field_list['postTS']->gen_show_html()?> by <a href="<?=site_url('contact/info/'.$this->dataInfo->field_list['postUser']->value)?>"><?=$this->dataInfo->field_list['postUser']->gen_show_html()?></a></p>
    <hr/>
    <?=$this->dataInfo->field_list['content']->gen_show_html()?>
    <div class="clear"></div>
    <div class="pull-right">
        <div class="clear"></div>
        <a href="#" onclick="zan_blog('<?=$this->dataInfo->field_list['_id']->toString()?>')" class="btn btn-sm btn-default">
            <span class='glyphicon glyphicon-thumbs-up'></span> <span class="good_count"><?=count($this->zanList->record_list)?></span>
            </a>
        <a href="#" class="btn btn-sm btn-default">
            <span class='glyphicon glyphicon-comment'></span> <span class="comment_count"><?=count($this->commentList->record_list)?></span>
        </a>
        
    </div>
    <div class="clear"></div>
    <br/>
    <hr/>
    <div>
    <ul class="list-inline zanList">
        <?
        foreach ($this->zanList->record_list as $key => $value) {
        ?>
            <li><a href="<?=site_url('contact/info/'.$value->field_list['postUid']->value)?>"><?=$value->field_list['postUid']->gen_show_html()?></a></li>
        <?
        }

        if (count($this->zanList->record_list)==0){
            echo '目前还没有赞';
        } else {
            echo '赞了这篇文章';
        }
        ?>
    </ul>
    </div>
    <hr/>
    <div>
    <h3>评论</h3>
        <?
        if (count($this->commentList->record_list)==0) {
            echo '目前还没有评论';
        } 
        ?>

        <table class="table table-striped simplePagerContainer">
            
        <?
        foreach ($this->commentList->record_list as $key => $value) {
        ?>
            <tr>
                <td>
                    <a href="<?=site_url('contact/info/'.$value->field_list['postUid']->value)?>"><?=$value->field_list['postUid']->gen_show_html()?></a>
                </td>
                <td>
                    <?=$value->field_list['postTS']->gen_show_html()?>
                </td>
                <td>
                    <?=$value->field_list['content']->gen_show_html()?>
                </td>
            </tr>
        <?
        }
        ?>
        </table>
        <form>
            <div class="form-group">
                <textarea class="form-control" id="commentInput" placeholder="输入评论"></textarea>
            </div>
            <button type="button" class="btn btn-primary pull-right" onclick="sendComments('<?=$this->dataInfo->field_list['_id']->toString()?>')">评论</button>
        </form>
        <br/>
    </div>
<?
var_dump($this->dataInfo->data);
?>
</div>