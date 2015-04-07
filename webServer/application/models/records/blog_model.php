<?php
include_once(APPPATH."models/record_model.php");
class Blog_model extends Record_model {
    public function __construct() {
        parent::__construct('bBlog');
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doSubDel/book';
        $this->edit_link = 'crm/subEdit/book/';
        $this->info_link = 'crm/subinfo/book/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['title'] = $this->load->field('Field_string',"标题","title",true);
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['status'] = $this->load->field('Field_enum',"发货状态","status");
        $this->field_list['status']->setEnum(array('未确定','现货备货','订单生产','打包发货','已到货'));

        $this->field_list['content'] = $this->load->field('Field_rich_text',"文章","content",true);
        $this->field_list['goodCount'] = $this->load->field('Field_int',"赞","goodCount");
        $this->field_list['commentCount'] = $this->load->field('Field_int',"评论","commentCount");
        $this->field_list['goods'] = $this->load->field('Field_array',"赞","goods");
        $this->field_list['comments'] = $this->load->field('Field_array_comments',"评论","comments");
        

        $this->field_list['postTS'] = $this->load->field('Field_date',"发布日期","beginTS");
        $this->field_list['editTS'] = $this->load->field('Field_date',"编辑日期","editTS");

        $this->field_list['postUser'] = $this->load->field('Field_userid',"发布人","beginTS");


        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
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
