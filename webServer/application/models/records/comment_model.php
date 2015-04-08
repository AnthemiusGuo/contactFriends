<?php
include_once(APPPATH."models/record_model.php");
class Comment_model extends Record_model {
    public function __construct() {
        parent::__construct('uComment');
        $this->uname = '';
        $this->uid = 0;

        $this->deleteCtrl = 'blog';
        $this->deleteMethod = 'doDelComments';
        $this->edit_link = 'contact/editUser/';
        $this->info_link = 'contact/info/';
        $this->default_is_lightbox_or_page = false;

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"uid","_id");
        $this->field_list['postUid'] = $this->load->field('Field_userid',"uid","uid");


        $this->field_list['postTS'] = $this->load->field('Field_ts',"注册时间","regTS");

        $this->field_list['toUid'] = $this->load->field('Field_userid',"回复","toUid");

        $this->field_list['content'] = $this->load->field('Field_text',"内容","content");


    }

    public function buildShowCard(){
        $_html = '<div class="shopInfoCard">';
        $url = '#';//$this->gen_front_url();
        $_html .= '<h4><a href="'.$url.'" target="_blank">'.$this->field_list['name']->gen_show_html().'</a></h4>';
        if (!$this->field_list['orgId']->isEmpty()){
            $_html .= '<span class="shopBegin"> '.$this->field_list['orgId']->gen_show_html().' </span>';
        }

        $_html .= '<span class="shopBegin"> '.$this->field_list['sign']->gen_show_html().' </span>';
        $_html .= '<p class="shopDesc">'.$this->field_list['intro']->gen_show_html().'</p>';

        $_html .= '<dt>电话</dt>';
        $_html .= '<dd>'.$this->field_list['phone']->gen_show_html().'</dd>';
        $_html .= '<dt>电邮</dt>';
        $_html .= '<dd>'.$this->field_list['email']->gen_show_html().'</dd>';
        // $_html .= '<dt>微信</dt>';
        // $_html .= '<dd>'.$this->field_list['orgId']->gen_show_html().'</dd>';
        // $_html .= '<dt>旺旺</dt>';
        // $_html .= '<dd>'.$this->field_list['wangwang']->gen_show_html().'</dd>';
        $_html .= '<div class="clearfix"></div></div>';


        return $_html;
    }

    public function buildChangeShowFields(){
            return array(
                    array('name'),
                    array('email','phone'),
                    array('qq','weixin'),
                    array('intro'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name','typ'),
                    array('email','phone'),
                    array('regTS'),
                    array('sign'),
                    array('intro'),
                );
    }
    
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return '用户:'.$this->field_list['name']->gen_show_html().'<small>'.$this->field_list['typ']->gen_show_html().'</small>';
    }


}
?>
