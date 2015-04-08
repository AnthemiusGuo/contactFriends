<?php
include_once(APPPATH."models/record_model.php");
class Blog_model extends Record_model {
    public function __construct() {
        parent::__construct('bBlog');
        $this->deleteCtrl = 'blog';
        $this->deleteMethod = 'doSubDel/book';
        $this->edit_link = 'crm/subEdit/book/';
        $this->info_link = 'crm/subinfo/book/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['title'] = $this->load->field('Field_string',"标题","title",true);
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['content'] = $this->load->field('Field_rich_text',"文章","content",true);
        $this->field_list['goodCount'] = $this->load->field('Field_int',"赞","goodCount");
        $this->field_list['commentCount'] = $this->load->field('Field_int',"评论","commentCount");
        $this->field_list['goods'] = $this->load->field('Field_array',"赞","goods");
        $this->field_list['comments'] = $this->load->field('Field_array_comments',"评论","comments");
        

        $this->field_list['postTS'] = $this->load->field('Field_date',"发布日期","beginTS");
        $this->field_list['editTS'] = $this->load->field('Field_date',"编辑日期","editTS");

        $this->field_list['postUser'] = $this->load->field('Field_userid',"发布人","beginTS");
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return $this->field_list['title']->gen_show_html();
    }




    public function buildChangeShowFields(){
            return array(
                    array('title'),
                    array('content'),
                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('title'),
                    array('content'),
                );
    }

}
?>
