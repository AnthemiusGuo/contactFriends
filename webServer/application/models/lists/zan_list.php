<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/zan_model.php");
class Zan_list extends List_model {
    public function __construct() {
        parent::__construct('bBlogZan');
        parent::init("Zan_list","Zan_model");
    }

    public function build_list_titles(){
        return array('crmId','items','status','payStatus','totalGetting','beginTS');
    }
}
?>
