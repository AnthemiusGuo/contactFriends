<?php
include_once(APPPATH."models/record_model.php");
class Comment_model extends Record_model {
    public function __construct() {
        parent::__construct('bBlogComment');

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"评论id","_id");
        $this->field_list['postUid'] = $this->load->field('Field_userid',"uid","uid");

        $this->field_list['postTS'] = $this->load->field('Field_ts',"注册时间","regTS");

        $this->field_list['toUid'] = $this->load->field('Field_userid',"回复","toUid");
        $this->field_list['blogId'] = $this->load->field('Field_int',"blogid","blogId");
        
        $this->field_list['content'] = $this->load->field('Field_text',"内容","content");


    }

}
?>
