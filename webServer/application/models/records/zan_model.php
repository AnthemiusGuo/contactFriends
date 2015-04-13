<?php
include_once(APPPATH."models/record_model.php");
class Zan_model extends Record_model {
    public function __construct() {
        parent::__construct('bBlogZan');

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"评论id","_id");
        $this->field_list['postUid'] = $this->load->field('Field_userid',"uid","postUid");
        $this->field_list['postTS'] = $this->load->field('Field_ts',"注册时间","postTS");
        $this->field_list['blogId'] = $this->load->field('Field_int',"blogid","blogId");
    }

    

}
?>
