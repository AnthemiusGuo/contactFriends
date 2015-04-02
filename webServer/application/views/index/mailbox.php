<ul class="chats">
    <?php 
    $i = 1;
    foreach($this->listInfo->record_list as  $this_record): ?>
        
    <li class="in">
        <!-- <img class="avatar img-responsive" alt="" src="assets/img/avatar1.jpg"> -->
        <div class="senderOrg pull-left">
            <?=$this_record->field_list["orgId"]->gen_show_html()?><br/>
            <?=$this_record->field_list["fromUid"]->gen_show_html()?>
        </div>
        <div class="message <?=($this_record->field_list["readed"]->value==0)?'unread':''?>" >
            <span class="arrow">
            </span>
            
            <span class="datetime">
                @ <?=$this_record->field_list["sendTS"]->gen_show_html()?>
            </span>
            <span class="body">
                <?=$this_record->field_list["mailComments"]->gen_show_html()?>
            </span>
        </div>
    </li>       
    <?php $i++;
    endforeach; ?>
    
</ul>
<?=$this->pages?>