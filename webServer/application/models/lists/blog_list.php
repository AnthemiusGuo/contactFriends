<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/blog_model.php");
class Blog_list extends List_model {
    public function __construct() {
        parent::__construct('bBlog');
        parent::init("Blog_list","Blog_model");
    }

    public function build_inline_list_titles(){
        return array('items','status','payStatus','totalGetting','beginTS');
    }
    public function build_short_list_titles(){
        return array('crmId','items','status','payStatus','totalGetting','beginTS');
    }
    public function build_list_titles(){
        return array('crmId','items','status','payStatus','totalGetting','beginTS');
    }
}
?>
